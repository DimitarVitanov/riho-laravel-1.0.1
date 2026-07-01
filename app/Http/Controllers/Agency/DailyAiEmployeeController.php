<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureSetting;
use App\Models\AiSuggestion;
use App\Models\AiDailyReport;
use App\Models\GeneratedPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DailyAiEmployeeController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $feature = 'daily_ai_employee';
        
        // Get feature settings
        $featureSetting = AiFeatureSetting::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->first();

        // ALL AI features that can generate suggestions
        $allFeatureKeys = [
            'daily_ai_employee',
            'invisible_lead_magnet',
            'local_seo_presence_boost',
            'ai_search_ranking',
            'daily_competitor_scan',
            'ai_authority_builder',
            'small_ai_actions',
        ];

        // Date filtering - handle both preset and manual date inputs
        $datePreset = $request->input('date_preset');
        $dateFrom = $request->input('date_from') ? \Carbon\Carbon::parse($request->input('date_from'))->startOfDay() : null;
        $dateTo = $request->input('date_to') ? \Carbon\Carbon::parse($request->input('date_to'))->endOfDay() : null;
        
        // Handle preset selections
        if ($datePreset && !$dateFrom && !$dateTo) {
            switch ($datePreset) {
                case 'today':
                    $dateFrom = now()->startOfDay();
                    $dateTo = now()->endOfDay();
                    break;
                case 'yesterday':
                    $dateFrom = now()->subDay()->startOfDay();
                    $dateTo = now()->subDay()->endOfDay();
                    break;
                case 'last_7_days':
                    $dateFrom = now()->subDays(7)->startOfDay();
                    $dateTo = now()->endOfDay();
                    break;
                case 'last_30_days':
                    $dateFrom = now()->subDays(30)->startOfDay();
                    $dateTo = now()->endOfDay();
                    break;
                case 'this_month':
                    $dateFrom = now()->startOfMonth();
                    $dateTo = now()->endOfDay();
                    break;
                case 'last_month':
                    $dateFrom = now()->subMonth()->startOfMonth();
                    $dateTo = now()->subMonth()->endOfMonth();
                    break;
            }
        }

        // Build query with date filters
        $suggestionsQuery = AiSuggestion::where('agency_profile_id', $profile->id)
            ->whereIn('feature_key', $allFeatureKeys);

        if ($dateFrom && $dateTo) {
            $suggestionsQuery->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        // Get pending suggestions from ALL AI features (unified inbox)
        $pendingSuggestions = (clone $suggestionsQuery)
            ->pending()
            ->latest()
            ->paginate(10);

        // Get accepted suggestions ready for final approval/publish
        $acceptedSuggestions = (clone $suggestionsQuery)
            ->accepted()
            ->latest()
            ->paginate(10);

        // Get recent suggestions history
        $suggestionsHistory = (clone $suggestionsQuery)
            ->whereIn('status', ['skipped', 'removed'])
            ->latest()
            ->take(10)
            ->get();

        // Get latest AI report for Daily AI Employee feature
        $latestReport = AiDailyReport::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->latest('report_date')
            ->first();

        // Get recent reports from ALL features for the summary
        $recentReports = AiDailyReport::where('agency_profile_id', $profile->id)
            ->whereIn('feature_key', $allFeatureKeys)
            ->where('created_at', '>=', now()->subDays(7))
            ->latest('report_date')
            ->get();

        // Stats from ALL features
        $stats = [
            'pending_count' => $pendingSuggestions->count(),
            'accepted_count' => $acceptedSuggestions->count(),
            'published_count' => GeneratedPage::where('agency_profile_id', $profile->id)
                ->whereIn('feature_key', $allFeatureKeys)
                ->where('status', 'published')
                ->count(),
            'total_this_month' => AiSuggestion::where('agency_profile_id', $profile->id)
                ->whereIn('feature_key', $allFeatureKeys)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        // Feature labels for display
        $featureLabels = [
            'daily_ai_employee' => 'Daily AI Employee',
            'invisible_lead_magnet' => 'Invisible Lead Magnet',
            'local_seo_presence_boost' => 'Local SEO',
            'ai_search_ranking' => 'AI Search Ranking',
            'daily_competitor_scan' => 'Competitor Scan',
            'ai_authority_builder' => 'Authority Builder',
            'small_ai_actions' => 'Small AI Actions',
        ];

        return view('agency.daily-ai-employee.index', compact(
            'user', 'profile', 'featureSetting', 'latestReport', 'recentReports',
            'pendingSuggestions', 'acceptedSuggestions', 'suggestionsHistory', 
            'stats', 'featureLabels', 'datePreset', 'dateFrom', 'dateTo'
        ));
    }

    public function acceptSuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id) {
            abort(403);
        }

        $suggestion->markAsAccepted($user->id, $request->input('notes'));

        // Convert to GeneratedPage for final approval workflow
        $page = GeneratedPage::create([
            'agency_profile_id' => $suggestion->agency_profile_id,
            'feature_key' => $suggestion->feature_key,
            'title' => $suggestion->title,
            'slug' => Str::slug($suggestion->title),
            'seo_title' => $suggestion->title,
            'meta_description' => substr(strip_tags($suggestion->content_html), 0, 160),
            'content_html' => $suggestion->content_html,
            'content_json' => $suggestion->content_json,
            'status' => 'pending_review',
            'content_uniqueness_status' => $suggestion->content_uniqueness_status ?? 'pending',
        ]);

        $suggestion->update(['converted_to_page_id' => $page->id]);

        return redirect()->back()->with('success', 'Suggestion accepted and moved to final review.');
    }

    public function skipSuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id) {
            abort(403);
        }

        $suggestion->markAsSkipped($user->id, $request->input('notes'));

        return redirect()->back()->with('success', 'Suggestion skipped.');
    }

    public function removeSuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id) {
            abort(403);
        }

        $suggestion->markAsRemoved($user->id, $request->input('notes'));

        return redirect()->back()->with('success', 'Suggestion removed.');
    }

    public function markAsReviewed(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id) {
            abort(403);
        }

        $suggestion->markAsReviewed($user->id);

        return redirect()->back()->with('success', 'Item marked as reviewed.');
    }

    public function publishContent(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($page->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id) {
            abort(403);
        }

        // Ensure uniqueness check passed
        if ($page->content_uniqueness_status !== 'passed') {
            return redirect()->back()->with('error', 'Content must pass uniqueness check before publishing.');
        }

        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Content published successfully.');
    }

    public function saveSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        // Update feature setting
        $featureSetting = AiFeatureSetting::firstOrCreate(
            [
                'agency_profile_id' => $profile->id,
                'feature_key' => 'daily_ai_employee',
            ],
            [
                'is_enabled' => true,
                'frequency' => 'daily',
                'ai_model_provider' => 'openai',
                'ai_model_name' => 'gpt-4',
            ]
        );

        $featureSetting->update([
            'is_enabled' => $request->input('is_enabled', false),
        ]);

        // Update AI content language on profile
        if ($request->has('ai_language')) {
            $profile->update([
                'ai_content_language' => $request->input('ai_language'),
            ]);
        }

        // Update custom prompt if provided
        if ($request->has('custom_prompt')) {
            $featureSetting->update([
                'custom_prompt_override' => $request->input('custom_prompt'),
            ]);
        }

        // Reset prompt to default if requested
        if ($request->input('reset_prompt')) {
            $featureSetting->update([
                'custom_prompt_override' => null,
            ]);
            return redirect()->back()->with('success', 'Prompt reset to default.');
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

    public function viewLogs()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        // Get recent AI reports/logs
        $logs = AiDailyReport::where('agency_profile_id', $profile->id)
            ->whereIn('feature_key', [
                'daily_ai_employee',
                'invisible_lead_magnet',
                'local_seo_presence_boost',
                'ai_search_ranking',
                'daily_competitor_scan',
                'ai_authority_builder',
                'small_ai_actions',
            ])
            ->latest('report_date')
            ->paginate(20);

        return view('agency.daily-ai-employee.logs', compact('user', 'profile', 'logs'));
    }

    public function openPrompt()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $featureSetting = AiFeatureSetting::where('agency_profile_id', $profile->id)
            ->where('feature_key', 'daily_ai_employee')
            ->first();

        // Default prompt template
        $defaultPrompt = "You are a Daily AI Employee for a real estate agency. Your tasks include:\n\n" .
            "1. Generating local SEO blog posts targeting specific keywords\n" .
            "2. Creating content for lead magnet pages\n" .
            "3. Summarizing competitor analysis findings\n" .
            "4. Building authority content for the agency\n\n" .
            "All content must:\n" .
            "- Be original and pass uniqueness checks\n" .
            "- Target the specified keywords naturally\n" .
            "- Follow real estate industry best practices\n" .
            "- Be ready for human review before publishing";

        $customPrompt = $featureSetting?->custom_prompt_override ?? $defaultPrompt;

        return view('agency.daily-ai-employee.prompt', compact('user', 'profile', 'featureSetting', 'customPrompt', 'defaultPrompt'));
    }
}
