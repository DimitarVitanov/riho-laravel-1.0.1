<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgencyFeatureController extends Controller
{
    protected $validFeatures = [
        'daily_ai_employee',
        'invisible_lead_magnet',
        'local_seo_presence_boost',
        'ai_search_ranking',
        'daily_competitor_scan',
        'ai_authority_builder',
        'small_ai_actions',
    ];

    public function show(string $feature)
    {
        if (!in_array($feature, $this->validFeatures)) {
            abort(404);
        }

        $user = Auth::user();
        $profile = $user->agencyProfile;
        $featureSetting = null;
        $latestReport = null;

        if ($profile) {
            $featureSetting = AiFeatureSetting::firstOrCreate(
                [
                    'agency_profile_id' => $profile->id,
                    'feature_key' => $feature,
                ],
                [
                    'is_enabled' => true,
                    'frequency' => 'daily',
                    'ai_model_provider' => 'openai',
                    'ai_model_name' => 'gpt-4',
                ]
            );

            $latestReport = $profile->aiDailyReports()
                ->where('feature_key', $feature)
                ->latest('report_date')
                ->first();
        }

        // Check for feature-specific view
        $featureView = "agency.features.{$feature}";
        if (view()->exists($featureView)) {
            return view($featureView, compact('feature', 'user', 'profile', 'featureSetting', 'latestReport'));
        }

        return view('agency.features.show', compact('feature', 'user', 'profile', 'featureSetting', 'latestReport'));
    }

    public function saveSettings(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        
        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $feature = $request->input('feature', 'invisible_lead_magnet');

        // Update feature setting
        $featureSetting = AiFeatureSetting::firstOrCreate(
            [
                'agency_profile_id' => $profile->id,
                'feature_key' => $feature,
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

    public function viewLogs(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $feature = $request->input('feature', 'invisible_lead_magnet');

        // Get recent AI reports/logs
        $logs = \App\Models\AiDailyReport::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->latest('report_date')
            ->paginate(20);

        return view('agency.features.logs', compact('user', 'profile', 'logs', 'feature'));
    }

    public function openPrompt(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $feature = $request->input('feature', 'invisible_lead_magnet');

        $featureSetting = AiFeatureSetting::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->first();

        // Default prompt template for lead magnet
        $defaultPrompt = "You are an AI assistant helping a real estate agency capture and qualify leads. Your tasks include:\n\n" .
            "1. Analyzing lead form submissions and extracting key information\n" .
            "2. Determining lead quality and intent based on provided data\n" .
            "3. Suggesting personalized follow-up messages\n" .
            "4. Categorizing leads by investment capacity and urgency\n\n" .
            "All lead data must be handled securely and professionally.";

        $customPrompt = $featureSetting?->custom_prompt_override ?? $defaultPrompt;

        return view('agency.features.prompt', compact('user', 'profile', 'featureSetting', 'customPrompt', 'defaultPrompt', 'feature'));
    }

    public function exportLeads(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        
        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $leads = $profile->leads()->where('source', 'invisible_lead_magnet')->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="lead-magnet-leads-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Country', 'Investor Type', 'Interest Amount', 'Message', 'Status', 'Created At', 'Landing Page']);
            
            // CSV Data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->id,
                    $lead->first_name,
                    $lead->last_name,
                    $lead->email,
                    $lead->phone,
                    $lead->country,
                    $lead->investor_type,
                    $lead->interest_amount,
                    $lead->message,
                    $lead->status,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->landing_page_url,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
