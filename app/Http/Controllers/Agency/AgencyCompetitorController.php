<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\CompetitorWebsite;
use App\Models\CompetitorScanResult;
use App\Models\AiSuggestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AgencyCompetitorController extends Controller
{
    public function storeCompetitor(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'google_business_url' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $profile->competitorWebsites()->create($validated);

        return redirect()->back()->with('success', 'Competitor added successfully.');
    }

    public function destroyCompetitor(CompetitorWebsite $competitor)
    {
        $user = Auth::user();
        if ($competitor->agency_profile_id !== $user->agencyProfile?->id) {
            abort(403);
        }
        $competitor->delete();
        return redirect()->back()->with('success', 'Competitor removed.');
    }

    public function runScan(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            if ($usageLimit->competitor_scans_used >= $usageLimit->competitor_scans_limit) {
                return redirect()->back()->with('error', 'You have reached your monthly competitor scan limit.');
            }
            $usageLimit->increment('competitor_scans_used');
        }

        $scanTypes = $request->input('scan_types', ['new_properties', 'seo_pages', 'weakness_detection']);
        $competitors = $profile->competitorWebsites()->where('is_active', true)->get();
        $city = $profile->target_city ?? 'your area';
        $agencyName = $profile->agency_name ?? 'Your Agency';

        if ($competitors->isEmpty()) {
            return redirect()->back()->with('error', 'Please add at least one competitor website to scan.');
        }

        $suggestionCount = 0;
        
        foreach ($competitors as $competitor) {
            foreach ($scanTypes as $scanType) {
                $result = $this->buildScanResult($scanType, $competitor, $city, $agencyName);
                
                $scanResult = CompetitorScanResult::create([
                    'agency_profile_id' => $profile->id,
                    'competitor_website_id' => $competitor->id,
                    'scan_type' => $scanType,
                    'title' => $result['title'],
                    'summary' => $result['summary'],
                    'details_json' => $result['details'],
                    'recommended_action' => $result['action'],
                    'recommended_content' => $result['content'],
                    'status' => 'new',
                    'scanned_at' => now(),
                ]);

                // Create AiSuggestion if there's recommended content
                if (!empty($result['content']) && !empty($result['action'])) {
                    $suggestion = $profile->aiSuggestions()->create([
                        'feature_key' => 'daily_competitor_scan',
                        'suggestion_type' => 'competitor_response',
                        'title' => $result['action'],
                        'target_keyword' => $competitor->name,
                        'content_html' => "<h2>" . $result['action'] . "</h2>\n<p>" . $result['content'] . "</p>\n<p><strong>Competitor:</strong> " . $competitor->name . "</p>\n<p><strong>Scan Type:</strong> " . ucfirst(str_replace('_', ' ', $scanType)) . "</p>",
                        'content_json' => [
                            'competitor_name' => $competitor->name,
                            'competitor_url' => $competitor->url,
                            'scan_type' => $scanType,
                            'scan_result_id' => $scanResult->id,
                            'recommended_action' => $result['action'],
                            'recommended_content' => $result['content'],
                        ],
                        'ai_summary' => "Competitor analysis suggestion based on {$competitor->name} {$scanType} scan.",
                        'ai_conclusion' => "This content helps respond to competitor strategies and maintain competitive advantage.",
                        'status' => 'pending',
                        'content_uniqueness_status' => 'pending',
                    ]);
                    $suggestionCount++;
                }
            }
            $competitor->update(['last_scanned_at' => now()]);
        }

        $message = 'Competitor scan completed. ';
        if ($suggestionCount > 0) {
            $message .= "Created {$suggestionCount} content suggestions for review in Daily AI Employee.";
        } else {
            $message .= "Review the findings below.";
        }

        return redirect()->back()->with('success', $message);
    }

    protected function buildScanResult($scanType, $competitor, $city, $agencyName): array
    {
        $competitorName = $competitor->name;
        $competitorUrl = $competitor->url;

        return match($scanType) {
            'new_properties' => [
                'title' => "New Properties Detected — {$competitorName}",
                'summary' => "{$competitorName} may be adding new property listings. Check their website for recent additions and identify the property types and locations they are targeting.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'new_properties',
                    'signals' => [
                        'Check for luxury homes, condos, or villas with sea views',
                        'Look for new land plots or investment properties',
                        'Note any new areas or neighborhoods being promoted',
                    ],
                ],
                'action' => "Review {$competitorName} listings and create matching or superior pages targeting the same buyer intent in {$city}.",
                'content' => "Create a page: \"Luxury Properties With Sea Views in {$city}\" — Include property types, locations, price ranges, and buyer benefits to capture the same demand.",
            ],
            'seo_pages' => [
                'title' => "SEO Page Analysis — {$competitorName}",
                'summary' => "{$competitorName} may have strong local SEO pages targeting keywords your agency is missing. Review their sitemap and key landing pages.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'seo_pages',
                    'signals' => [
                        "Check for city-specific pages beyond {$city}",
                        'Look for property type + location combinations',
                        'Identify any regional area or neighborhood pages',
                    ],
                ],
                'action' => "Identify SEO pages {$competitorName} has that your agency lacks. Create matching Local SEO pages for {$city} and surrounding areas.",
                'content' => "Create pages: \"Properties For Sale In {$city} And Surrounding Areas\" — Include all relevant towns and neighborhoods to broaden your local coverage.",
            ],
            'blog' => [
                'title' => "Blog & Content Activity — {$competitorName}",
                'summary' => "{$competitorName} may be publishing buyer guides and market updates that attract early-stage buyers. Check their blog for recent content topics.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'blog',
                    'signals' => [
                        'Look for foreign buyer articles',
                        'Check for investment or rental yield guides',
                        'Look for legal process or buying steps content',
                    ],
                ],
                'action' => "Publish a stronger version of any guide {$competitorName} has written, with more practical detail and local {$city} specifics.",
                'content' => "Create article: \"Why Foreign Buyers Choose {$city} Real Estate In 2025\" — Include buying process, taxes, rental potential, legal steps, and agency support sections.",
            ],
            'price_movement' => [
                'title' => "Price Movement Signals — {$competitorName}",
                'summary' => "{$competitorName} listing prices can reveal current market conditions. Observe price ranges for apartments, villas, and land in {$city}.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'price_movement',
                    'signals' => [
                        'Apartments: observe price per m² range',
                        'Villas: note price bands being promoted',
                        'Land: check if supply appears limited or growing',
                    ],
                ],
                'action' => "Add a fresh market notes block to your website reflecting current price activity in {$city}.",
                'content' => "Add website block: \"Current {$city} Real Estate Market Notes\" — Recent listings show strong demand for sea-view apartments, villas with pools, and buildable land. Prices vary depending on location, views, condition, and rental potential.",
            ],
            'gbp_reviews' => [
                'title' => "Google Review Signals — {$competitorName}",
                'summary' => "{$competitorName} Google reviews may reveal what buyers value most. Review keywords like 'professional', 'fast response', 'foreign buyers', and 'local knowledge' repeated in their reviews.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'gbp_reviews',
                    'signals' => [
                        'Note repeated trust words in recent reviews',
                        'Identify buyer types mentioned (foreign, investor, family)',
                        'Look for service quality signals (speed, communication, knowledge)',
                    ],
                ],
                'action' => "Add trust language to your website reflecting the buyer values {$competitorName} reviews highlight. Ask happy clients to mention similar aspects.",
                'content' => "Add to your website: \"We help foreign buyers understand local property paperwork, market conditions, and practical purchase steps before making a decision.\"",
            ],
            'weakness_detection' => [
                'title' => "Competitor Weakness Detected — {$competitorName}",
                'summary' => "{$competitorName} may be missing important buyer guides and authority content. This is where your agency can fill the gap and attract buyers they ignore.",
                'details' => [
                    'competitor' => $competitorName,
                    'url' => $competitorUrl,
                    'scan_type' => 'weakness_detection',
                    'missing_pages' => [
                        'Foreign buyer guide',
                        'Real estate investment guide',
                        'Rental yield explanation',
                        'Land buying guide',
                        'Renovation property guide',
                        'Buying through company guide',
                        'Property taxes guide',
                        'Common foreign buyer mistakes',
                    ],
                ],
                'action' => "Create authority pages that {$competitorName} is missing. These pages attract buyers who search for answers before contacting any agency.",
                'content' => "Priority pages to create:\n1. Foreign Buyer Guide for {$city}\n2. Real Estate Investment Guide — {$city}\n3. Rental Yield Guide\n4. Common Foreign Buyer Mistakes\n5. Property Taxes Guide",
            ],
            default => [
                'title' => "Scan Result — {$competitorName}",
                'summary' => "Scan completed for {$competitorName}. Review the findings and take action.",
                'details' => ['competitor' => $competitorName, 'url' => $competitorUrl],
                'action' => "Review the scan result and take action.",
                'content' => "",
            ],
        };
    }

    public function markActed(CompetitorScanResult $result)
    {
        $user = Auth::user();
        if ($result->agency_profile_id !== $user->agencyProfile?->id) {
            abort(403);
        }
        $result->update(['status' => 'acted']);
        return redirect()->back()->with('success', 'Marked as acted.');
    }

    public function markDismissed(CompetitorScanResult $result)
    {
        $user = Auth::user();
        if ($result->agency_profile_id !== $user->agencyProfile?->id) {
            abort(403);
        }
        $result->update(['status' => 'dismissed']);
        return redirect()->back()->with('success', 'Finding dismissed.');
    }

    public function destroyResult(CompetitorScanResult $result)
    {
        $user = Auth::user();
        if ($result->agency_profile_id !== $user->agencyProfile?->id) {
            abort(403);
        }
        $result->delete();
        return redirect()->back()->with('success', 'Scan result deleted.');
    }
}
