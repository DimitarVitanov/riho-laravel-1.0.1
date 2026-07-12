<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureSetting;
use App\Models\AiSuggestion;
use App\Models\GeneratedPage;
use App\Models\LocalSeoTarget;
use App\Services\DnsVerificationGuard;
use App\Services\UsageLimitService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        $featureSetting = null;
        $latestReport = null;

        if (!$profile) {
            return redirect()->route('agency.dashboard')
                ->with('error', 'Agency profile not found. Please complete your agency profile before accessing features.');
        }

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
            $viewData = compact('feature', 'user', 'profile', 'featureSetting', 'latestReport');

            if ($feature === 'local_seo_presence_boost') {
                $viewData['cities'] = $profile ? $profile->localSeoTargets()->cities()->get() : collect();
                $viewData['keywords'] = $profile ? $profile->localSeoTargets()->keywords()->get() : collect();
                $viewData['subniches'] = $profile ? $profile->localSeoTargets()->subniches()->get() : collect();
                $viewData['pages'] = $profile ? $profile->generatedPages()
                    ->where('feature_key', 'local_seo_presence_boost')
                    ->latest()
                    ->paginate(10) : collect();
                $viewData['listings'] = $profile ? $profile->agencyListings()
                    ->with('campaigns')
                    ->where('status', 'active')
                    ->latest()
                    ->get() : collect();
                $viewData['campaigns'] = $profile ? $profile->localSeoCampaigns()
                    ->withCount('listings')
                    ->latest()
                    ->get() : collect();
                $editId = session('edit_campaign_id', request('edit_campaign_id'));
                $viewData['editCampaign'] = $editId && $profile
                    ? $profile->localSeoCampaigns()->find($editId)
                    : null;
                
                // Usage limit status for disabling buttons
                $usageService = app(UsageLimitService::class);
                $viewData['usageLimitStatus'] = $profile ? $usageService->getStatus($profile, 'local_seo_pages') : null;
                $viewData['usageLimit'] = $profile ? $profile->currentUsageLimit : null;
            }

            if ($feature === 'ai_search_ranking') {
                $viewData['pages'] = $profile 
                    ? \App\Models\AiAuthorityPage::where('agency_profile_id', $profile->id)->latest()->get() 
                    : collect();
                $viewData['createMode'] = request()->has('create_page');
                $viewData['editPage'] = request()->has('edit_page_id') 
                    ? \App\Models\AiAuthorityPage::where('agency_profile_id', $profile->id)->find(request('edit_page_id'))
                    : null;
                $viewData['listings'] = $profile 
                    ? \App\Models\AgencyListing::where('agency_profile_id', $profile->id)->orderBy('title')->get() 
                    : collect();
            }

            if ($feature === 'ai_authority_builder') {
                $viewData['reviewPages'] = $profile ? $profile->generatedPages()
                    ->where('feature_key', 'ai_authority_builder')
                    ->latest()
                    ->paginate(10) : collect();
                $viewData['usageLimit'] = $profile ? $profile->currentUsageLimit : null;
            }

            if ($feature === 'daily_competitor_scan') {
                $viewData['competitors'] = $profile ? $profile->competitorWebsites()->orderBy('name')->get() : collect();
                $viewData['scanResults'] = $profile ? $profile->competitorScanResults()
                    ->with('competitorWebsite')
                    ->latest('scanned_at')
                    ->paginate(10) : collect();
                $viewData['newResults'] = $profile ? $profile->competitorScanResults()
                    ->where('status', 'new')
                    ->with('competitorWebsite')
                    ->latest('scanned_at')
                    ->paginate(10) : collect();
                $viewData['usageLimit'] = $profile ? $profile->currentUsageLimit : null;
            }

            return view($featureView, $viewData);
        }

        return view('agency.features.show', compact('feature', 'user', 'profile', 'featureSetting', 'latestReport'));
    }

    protected function getAiSearchNotifications($profile)
    {
        return collect([
            (object)[
                'title' => __('messages.bing_webmaster_tools'),
                'description' => __('messages.bing_webmaster_tools_desc'),
                'action_url' => 'https://www.bing.com/webmasters',
            ],
            (object)[
                'title' => __('messages.google_search_console'),
                'description' => __('messages.google_search_console_desc'),
                'action_url' => 'https://search.google.com/search-console',
            ],
            (object)[
                'title' => __('messages.sitemap_xml'),
                'description' => __('messages.sitemap_xml_desc'),
                'action_url' => url('/sitemap.xml'),
            ],
            (object)[
                'title' => __('messages.index_now'),
                'description' => __('messages.index_now_desc'),
                'action_url' => 'https://www.indexnow.org/',
            ],
        ]);
    }

    public function saveSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
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

        // Update local SEO targeting on profile
        if ($feature === 'local_seo_presence_boost') {
            $profile->update([
                'target_city' => $request->input('target_city', $profile->target_city),
                'target_radius_km' => $request->input('target_radius_km', $profile->target_radius_km ?? 30),
            ]);
        }

        // Update custom prompt if provided
        if ($request->has('custom_prompt')) {
            $featureSetting->update([
                'custom_prompt_override' => $request->input('custom_prompt'),
            ]);
        }

        // Update agency sub-prompt if provided
        if ($request->has('agency_sub_prompt')) {
            $featureSetting->update([
                'agency_sub_prompt' => $request->input('agency_sub_prompt') ?: null,
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $feature = $request->input('feature', 'invisible_lead_magnet');

        $featureSetting = AiFeatureSetting::where('agency_profile_id', $profile->id)
            ->where('feature_key', $feature)
            ->first();

        // Default prompt template based on feature
        if ($feature === 'local_seo_presence_boost') {
            $defaultPrompt = "You are a Villa Bit AI Local SEO expert for real estate agencies. Your task is to build a powerful local SEO presence using the following strategy:\n\n" .
                "1. CITY TARGETING: Identify all cities, towns, villages, neighborhoods, islands, and suburbs within the target radius around the agency's main city.\n" .
                "2. KEYWORD COMBINATIONS: Create search-intent keyword combinations like:\n" .
                "   - real estate agency + [city]\n" .
                "   - apartments for sale + [city]\n" .
                "   - houses for sale + [city]\n" .
                "   - land for sale + [city]\n" .
                "   - luxury villa + [city]\n" .
                "   - investment property + [city]\n" .
                "   - property for sale + [city]\n\n" .
                "3. HIGH-TICKET SUBNICHES: Include valuable real estate subniches such as:\n" .
                "   - luxury villas, sea-view properties, investment apartments\n" .
                "   - new construction, land parcels, development projects\n" .
                "   - relocation buyers, rental yield properties, property management\n" .
                "   - off-market opportunities, buyer representation\n\n" .
                "4. LOCAL SEO PAGE STRUCTURE: Each generated page must include:\n" .
                "   - Title: Real Estate Agency in [City] / Apartments for Sale in [City]\n" .
                "   - Local keyword-rich introduction\n" .
                "   - Neighborhood and area information\n" .
                "   - Property market overview and prices\n" .
                "   - Local lifestyle, schools, roads, tourism, sea distance\n" .
                "   - FAQ section with 5-10 real local questions\n" .
                "   - Internal links to other local area pages\n\n" .
                "5. FAQ SECTION: Generate questions real buyers/sellers ask, such as:\n" .
                "   - How much are closing costs for property in [city]?\n" .
                "   - Can foreign buyers purchase property in [city]?\n" .
                "   - Which neighborhoods in [city] are best for investors?\n" .
                "   - What should sellers know before listing in [city]?\n\n" .
                "6. QUALITY REQUIREMENTS:\n" .
                "   - All content must be original and pass uniqueness checks\n" .
                "   - Target keywords naturally and avoid keyword stuffing\n" .
                "   - Include real local details and useful information\n" .
                "   - Structure content for both human readers and Google AI Overview\n" .
                "   - Be ready for human review before publishing";
        } else {
            $defaultPrompt = "You are an AI assistant helping a real estate agency capture and qualify leads. Your tasks include:\n\n" .
                "1. Analyzing lead form submissions and extracting key information\n" .
                "2. Determining lead quality and intent based on provided data\n" .
                "3. Suggesting personalized follow-up messages\n" .
                "4. Categorizing leads by investment capacity and urgency\n\n" .
                "All lead data must be handled securely and professionally.";
        }

        $customPrompt = $featureSetting?->custom_prompt_override ?? $defaultPrompt;

        return view('agency.features.prompt', compact('user', 'profile', 'featureSetting', 'customPrompt', 'defaultPrompt', 'feature'));
    }

    public function exportLeads(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
        
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

    public function generateLocalSeoTargets(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $city = $request->input('generate_city', $profile->target_city);

        if (!$city) {
            return redirect()->back()->with('error', 'Please set a target city first.');
        }

        // Validate and correct city name
        $city = $this->validateAndCorrectCityName($city);
        if (!$city) {
            return redirect()->back()->with('error', 'Invalid city name provided.');
        }

        // Update profile target city
        $profile->update([
            'target_city' => $city,
        ]);

        // Clean up old targets before generating new ones
        $this->cleanupMisspelledTargets($profile);
        
        $this->seedLocalSeoTargets($profile, $city);

        // Add custom cities if provided
        if ($request->has('custom_cities') && !empty($request->input('custom_cities'))) {
            $customCities = array_map('trim', explode(',', $request->input('custom_cities')));
            foreach ($customCities as $customCity) {
                if (empty($customCity)) {
                    continue;
                }
                $profile->localSeoTargets()->firstOrCreate([
                    'target_type' => 'city',
                    'target_value' => $customCity,
                ], [
                    'is_selected' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Local SEO target lists generated. Review and generate pages.');
    }

    protected function seedLocalSeoTargets($profile, $city)
    {
        // Clean up any existing misspelled entries first
        $this->cleanupMisspelledTargets($profile);
        
        $cities = [
            $city,
            $city . ' suburbs',
            $city . ' center',
            $city . ' area',
        ];

        $keywords = [
            'Real Estate Agency in ' . $city,
            'Apartments for Sale in ' . $city,
            'Houses for Sale in ' . $city,
            'Land for Sale in ' . $city,
            'Luxury Villa in ' . $city,
            'Investment Property in ' . $city,
            'Property for Sale in ' . $city,
            'Best Real Estate Agency in ' . $city,
        ];

        $subniches = [
            'Luxury Villas',
            'Sea-View Properties',
            'Investment Apartments',
            'New Construction',
            'Land Parcels',
            'Development Projects',
            'Relocation Buyers',
            'Rental Yield Properties',
            'Property Management',
            'Off-Market Opportunities',
            'Buyer Representation',
        ];

        foreach ($cities as $value) {
            $profile->localSeoTargets()->firstOrCreate([
                'target_type' => 'city',
                'target_value' => $value,
            ], [
                'is_selected' => true,
            ]);
        }

        foreach ($keywords as $value) {
            $profile->localSeoTargets()->firstOrCreate([
                'target_type' => 'keyword',
                'target_value' => $value,
            ], [
                'is_selected' => true,
            ]);
        }

        foreach ($subniches as $value) {
            $profile->localSeoTargets()->firstOrCreate([
                'target_type' => 'subniche',
                'target_value' => $value,
            ], [
                'is_selected' => true,
            ]);
        }
    }

    public function generateLocalSeoPages(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        if (!DnsVerificationGuard::ensureVerified($profile)) {
            return redirect()->back()->with('error', 'Domain DNS is not verified yet. AI features are paused until your domain is connected.');
        }

        $selectedCities = $request->input('selected_cities', []);
        $selectedKeywords = $request->input('selected_keywords', []);
        $selectedSubniches = $request->input('selected_subniches', []);

        if (empty($selectedCities)) {
            $selectedCities = $profile->localSeoTargets()->cities()->selected()->pluck('id')->toArray();
        }
        if (empty($selectedKeywords)) {
            $selectedKeywords = $profile->localSeoTargets()->keywords()->selected()->pluck('id')->toArray();
        }
        if (empty($selectedSubniches)) {
            $selectedSubniches = $profile->localSeoTargets()->subniches()->selected()->pluck('id')->toArray();
        }

        $cities = $profile->localSeoTargets()->whereIn('id', $selectedCities)->cities()->get();
        $keywords = $profile->localSeoTargets()->whereIn('id', $selectedKeywords)->keywords()->get();
        $subniches = $profile->localSeoTargets()->whereIn('id', $selectedSubniches)->subniches()->pluck('target_value')->toArray();

        $generatedCount = 0;

        foreach ($cities as $city) {
            foreach ($keywords as $keyword) {
                $title = $keyword->target_value;
                $content = $this->buildLocalSeoPageContent($title, $city->target_value, $subniches, $profile);

                // Create AiSuggestion instead of direct GeneratedPage
                $suggestion = $profile->aiSuggestions()->create([
                    'feature_key' => 'local_seo_presence_boost',
                    'suggestion_type' => 'local_seo_page',
                    'title' => $title,
                    'target_keyword' => $keyword->target_value,
                    'content_html' => $content,
                    'content_json' => [
                        'target_city' => $city->target_value,
                        'target_keyword' => $keyword->target_value,
                        'subniches' => $subniches,
                    ],
                    'ai_summary' => "Local SEO page for {$title} targeting {$city->target_value} with focus on " . implode(', ', $subniches),
                    'ai_conclusion' => "This page helps establish local presence and authority for the targeted keywords.",
                    'status' => 'pending',
                    'content_uniqueness_status' => 'pending',
                ]);

                $generatedCount++;
            }
        }

        return redirect()->back()->with('success', $generatedCount . ' local SEO suggestions created successfully. Review in Daily AI Employee or approve directly below.');
    }

    protected function buildLocalSeoPageContent($title, $city, $subniches, $profile)
    {
        $subnichesList = implode(', ', $subniches);
        $faq = $this->buildLocalSeoFaq($city);
        $listingsHtml = $this->buildListingsHtml($profile);

        return "<h1>{$title}</h1>\n" .
            "<p>Welcome to our professional real estate agency serving {$city} and surrounding areas. " .
            "We specialize in helping buyers, sellers, and investors find the perfect property in this beautiful region.</p>\n\n" .
            "<h2>About {$city}</h2>\n" .
            "<p>{$city} is a prime location for real estate investment, offering a unique mix of local culture, " .
            "modern amenities, and strong property value growth. Our agency has deep knowledge of the local market " .
            "and can guide you through every step of buying or selling property here.</p>\n\n" .
            "<h2>Property Types and Services</h2>\n" .
            "<p>We cover a wide range of property categories and client needs: {$subnichesList}. " .
            "Whether you are looking for a family home, a luxury villa, an investment apartment, or development land, " .
            "our team is ready to assist.</p>\n\n" .
            $listingsHtml .
            "<h2>Local Real Estate FAQ</h2>\n" .
            $faq .
            "\n<h2>Contact Us</h2>\n" .
            "<p>Ready to explore real estate opportunities in {$city}? Contact our agency today for personalized assistance.</p>";
    }

    protected function buildListingsHtml($profile)
    {
        $listings = $profile->agencyListings()->where('status', 'active')->latest()->limit(6)->get();

        if ($listings->isEmpty()) {
            return '';
        }

        $html = "<h2>Featured Properties</h2>\n";
        $html .= "<div class=\"local-seo-listings\">\n";

        foreach ($listings as $listing) {
            $html .= "<div class=\"listing-item\">\n";
            $html .= "<h3>" . e($listing->title) . "</h3>\n";
            if ($listing->location) {
                $html .= "<p><strong>Location:</strong> " . e($listing->location) . "</p>\n";
            }
            if ($listing->property_type) {
                $html .= "<p><strong>Property Type:</strong> " . e($listing->property_type) . "</p>\n";
            }
            if ($listing->formatted_price) {
                $html .= "<p><strong>Price:</strong> " . e($listing->formatted_price) . "</p>\n";
            }
            if ($listing->description) {
                $html .= "<p>" . e($listing->description) . "</p>\n";
            }
            foreach ($listing->images as $image) {
                $html .= "<img src=\"" . e($image) . "\" alt=\"" . e($listing->title) . "\" style=\"max-width: 200px; margin: 5px;\">\n";
            }
            $html .= "</div>\n";
        }

        $html .= "</div>\n";

        return $html;
    }

    protected function buildLocalSeoFaq($city)
    {
        $questions = [
            "How much are closing costs for property in {$city}?",
            "Can foreign buyers purchase property in {$city}?",
            "Which neighborhoods in {$city} are best for investors?",
            "What should sellers know before listing property in {$city}?",
            "Is {$city} a good place for real estate investment?",
            "What types of properties are most popular in {$city}?",
        ];

        $html = '<div class="local-seo-faq">' . "\n";
        foreach ($questions as $question) {
            $html .= "<h3>{$question}</h3>\n";
            $html .= "<p>Our local real estate experts can provide detailed guidance based on current market conditions in {$city}. " .
                "Contact us for a personalized consultation.</p>\n";
        }
        $html .= '</div>';

        return $html;
    }

    protected function validateAndCorrectCityName($city)
    {
        // Common city name corrections
        $corrections = [
            'new yourk' => 'New York',
            'New yourk' => 'New York',
            'new Yourk' => 'New York',
            'New Yourk' => 'New York',
            'newyork' => 'New York',
            'Newyork' => 'New York',
            'new york city' => 'New York City',
            'nyc' => 'New York City',
        ];

        // Trim and normalize
        $city = trim($city);
        
        // Apply corrections
        if (isset($corrections[strtolower($city)])) {
            return $corrections[strtolower($city)];
        }
        
        // Check for case-insensitive matches
        foreach ($corrections as $incorrect => $correct) {
            if (strcasecmp($city, $incorrect) === 0) {
                return $correct;
            }
        }
        
        return $city;
    }

    protected function cleanupMisspelledTargets($profile)
    {
        // Remove misspelled "new yourk" variations
        $misspelledVariations = [
            'new yourk',
            'New yourk', 
            'new Yourk',
            'New Yourk',
            'newyork',
            'Newyork'
        ];

        foreach ($misspelledVariations as $misspelled) {
            $profile->localSeoTargets()
                ->where('target_value', 'like', '%' . $misspelled . '%')
                ->delete();
        }

        // Also clean up any old "New York" entries that don't match current city
        if ($profile->target_city && $profile->target_city !== 'New York') {
            $profile->localSeoTargets()
                ->where('target_value', 'like', '%New York%')
                ->delete();
        }
    }

    public function previewLocalSeoPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        return view('agency.features.local-seo-page-preview', compact('page', 'user', 'profile'));
    }

    public function editLocalSeoPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        return view('agency.features.local-seo-page-edit', compact('page', 'user', 'profile'));
    }

    public function updateLocalSeoPage(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'content_html' => 'required|string',
        ]);

        $page->update([
            'title' => $validated['title'],
            'seo_title' => $validated['seo_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'content_html' => $validated['content_html'],
        ]);

        return redirect()->route('agency.local-seo.pages.preview', $page)->with('success', 'Page updated successfully.');
    }

    public function publishLocalSeoPage(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        // Check usage limit
        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            if ($usageLimit->local_seo_pages_used >= $usageLimit->local_seo_pages_limit) {
                return redirect()->back()->with('error', 'You have reached your monthly limit of ' . $usageLimit->local_seo_pages_limit . ' published local SEO pages.');
            }

            $usageLimit->increment('local_seo_pages_used');
        }

        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Page published successfully.');
    }

    public function destroyLocalSeoPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    public function storeListing(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|string|in:active,inactive',
            'campaign_ids' => 'required|array|min:1',
            'campaign_ids.*' => 'exists:local_seo_campaigns,id',
        ]);

        // Verify all campaigns belong to this agency
        $campaignIds = collect($validated['campaign_ids'])->filter(function ($id) use ($profile) {
            return $profile->localSeoCampaigns()->where('id', $id)->exists();
        })->values()->all();

        if (empty($campaignIds)) {
            return redirect()->back()->with('error', 'Please select at least one valid campaign.');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('agency-listings/' . $profile->id, 'public');
                $images[] = asset('storage/' . $path);
            }
        }

        $listing = $profile->agencyListings()->create([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'EUR',
            'images_json' => $images,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Attach campaigns via pivot table
        $listing->campaigns()->attach($campaignIds);

        return redirect()->back()->with('success', 'Listing added successfully.');
    }

    public function assignListingCampaign(Request $request, \App\Models\AgencyListing $listing)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $listing->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'local_seo_campaign_id' => 'nullable|exists:local_seo_campaigns,id',
        ]);

        $campaignId = null;
        if (!empty($validated['local_seo_campaign_id'])) {
            $campaign = $profile->localSeoCampaigns()->find($validated['local_seo_campaign_id']);
            $campaignId = $campaign?->id;
        }

        $listing->update(['local_seo_campaign_id' => $campaignId]);

        return redirect()->back()->with('success', 'Listing campaign updated.');
    }

    /**
     * Assign multiple campaigns to a listing (many-to-many)
     */
    public function assignListingCampaigns(Request $request, \App\Models\AgencyListing $listing)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $listing->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'campaign_ids' => 'nullable|array',
            'campaign_ids.*' => 'exists:local_seo_campaigns,id',
        ]);

        // Verify all campaigns belong to this agency
        $campaignIds = collect($validated['campaign_ids'] ?? [])->filter(function ($id) use ($profile) {
            return $profile->localSeoCampaigns()->where('id', $id)->exists();
        })->values()->all();

        // Sync campaigns (replaces all existing associations)
        $listing->campaigns()->sync($campaignIds);

        return redirect()->back()->with('success', 'Listing campaigns updated.');
    }

    public function updateListing(Request $request, \App\Models\AgencyListing $listing)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $listing->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'living_area' => 'nullable|numeric|min:0',
            'plot_size' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1800',
            'is_turnkey' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|string|in:active,inactive',
            'campaign_ids' => 'nullable|array',
        ]);

        $images = $listing->images_json ?? [];
        if ($request->hasFile('images')) {
            $images = []; // Replace images
            foreach ($request->file('images') as $image) {
                $path = $image->store('agency-listings/' . $profile->id, 'public');
                $images[] = $path;
            }
        }

        $listing->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'EUR',
            'living_area' => $validated['living_area'] ?? null,
            'plot_size' => $validated['plot_size'] ?? null,
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'year_built' => $validated['year_built'] ?? null,
            'is_turnkey' => $request->has('is_turnkey'),
            'images_json' => $images,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Update campaign associations
        if (isset($validated['campaign_ids'])) {
            $listing->campaigns()->sync($validated['campaign_ids']);
        }

        return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'show_listings' => 1])
            ->with('success', 'Listing updated successfully.');
    }

    public function destroyListing(\App\Models\AgencyListing $listing)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $listing->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $listing->delete();

        return redirect()->back()->with('success', 'Listing deleted successfully.');
    }

    // Agency Agents

    public function storeAgent(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'agency_name' => 'nullable|string|max:255',
            'agency_listing_id' => 'nullable|exists:agency_listings,id',
            'tagline' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:1|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_primary' => 'nullable|boolean',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('agency-agents/' . $profile->id, 'public');
        }

        // If setting as primary, unset other primary agents
        if ($request->has('is_primary')) {
            $profile->agents()->update(['is_primary' => false]);
        }

        $profile->agents()->create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'agency_name' => $validated['agency_name'] ?? $profile->agency_name,
            'agency_listing_id' => $validated['agency_listing_id'] ?? null,
            'tagline' => $validated['tagline'] ?? null,
            'license' => $validated['license'] ?? null,
            'rating' => $validated['rating'] ?? 5.0,
            'reviews_count' => $validated['reviews_count'] ?? 0,
            'photo' => $photoPath,
            'is_primary' => $request->has('is_primary'),
        ]);

        return redirect()->route('agency.features.show', ['feature' => 'ai_search_ranking', 'add_agent' => 1])
            ->with('success', 'Agent added successfully.');
    }

    public function updateAgent(Request $request, \App\Models\AgencyAgent $agent)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $agent->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'agency_name' => 'nullable|string|max:255',
            'agency_listing_id' => 'nullable|exists:agency_listings,id',
            'tagline' => 'nullable|string|max:255',
            'license' => 'nullable|string|max:100',
            'rating' => 'nullable|numeric|min:1|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_primary' => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $agent->photo = $request->file('photo')->store('agency-agents/' . $profile->id, 'public');
        }

        // If setting as primary, unset other primary agents
        if ($request->has('is_primary')) {
            $profile->agents()->where('id', '!=', $agent->id)->update(['is_primary' => false]);
        }

        $agent->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'agency_name' => $validated['agency_name'] ?? $profile->agency_name,
            'agency_listing_id' => $validated['agency_listing_id'] ?? null,
            'tagline' => $validated['tagline'] ?? null,
            'license' => $validated['license'] ?? null,
            'rating' => $validated['rating'] ?? 5.0,
            'reviews_count' => $validated['reviews_count'] ?? 0,
            'photo' => $agent->photo,
            'is_primary' => $request->has('is_primary'),
        ]);

        return redirect()->route('agency.features.show', ['feature' => 'ai_search_ranking', 'add_agent' => 1])
            ->with('success', 'Agent updated successfully.');
    }

    public function destroyAgent(\App\Models\AgencyAgent $agent)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $agent->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $agent->delete();

        return redirect()->back()->with('success', 'Agent deleted successfully.');
    }

    // AI Search Ranking

    public function generateAuthorityPages(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $city = $request->input('target_city', $profile->target_city);
        $agencyName = $request->input('agency_name', $profile->agency_name ?? 'Our Agency');

        if (!$city) {
            return redirect()->back()->with('error', 'Please set a target city first.');
        }

        $authorityTopics = [
            'Buyer Guide' => "Complete Real Estate Buyer Guide for {$city} — {$agencyName}",
            'Foreign Buyer Guide' => "Foreign Buyer Guide for Real Estate in {$city} — {$agencyName}",
            'Investment Guide' => "Real Estate Investment Guide for {$city} — {$agencyName}",
            'Rental Income Guide' => "Rental Income Guide for {$city} Properties — {$agencyName}",
            'Local Market Report' => "{$city} Local Real Estate Market Report — {$agencyName}",
            'New Build Property Guide' => "New Build Property Guide for {$city} — {$agencyName}",
            'Legal Process Guide' => "Real Estate Legal Process Guide in {$city} — {$agencyName}",
            'Property Management Guide' => "Property Management Guide for {$city} — {$agencyName}",
        ];

        $generatedCount = 0;
        foreach ($authorityTopics as $topic => $title) {
            $slug = Str::slug($title);
            $content = $this->buildAuthorityPageContent($title, $city, $topic, $agencyName, $profile);

            $profile->generatedPages()->firstOrCreate([
                'feature_key' => 'ai_search_ranking',
                'slug' => $slug,
            ], [
                'title' => $title,
                'seo_title' => $title,
                'meta_description' => "Expert {$topic} for {$city}. Learn everything you need to know from {$agencyName}.",
                'content_html' => $content,
                'content_json' => [
                    'target_city' => $city,
                    'authority_topic' => $topic,
                    'agency_name' => $agencyName,
                ],
                'content_uniqueness_status' => 'pending',
                'status' => 'pending_review',
            ]);

            $generatedCount++;
        }

        return redirect()->back()->with('success', $generatedCount . ' authority pages generated successfully.');
    }

    protected function buildAuthorityPageContent($title, $city, $topic, $agencyName, $profile)
    {
        $faq = $this->buildLocalSeoFaq($city);
        $listingsHtml = $this->buildListingsHtml($profile);

        return "<h1>{$title}</h1>\n" .
            "<p>Welcome to the {$agencyName} {$topic} for {$city}. This guide is designed to help you navigate the local real estate market with confidence, " .
            "using clear answers, real market insights, and expert advice from our team.</p>\n\n" .
            "<h2>Why This Guide Matters</h2>\n" .
            "<p>AI search engines and modern buyers look for authoritative, well-structured content. This page provides exactly that: " .
            "clear answers, local market data, and practical guidance for anyone interested in real estate in {$city}.</p>\n\n" .
            "<h2>Key Topics Covered</h2>\n" .
            "<ul>\n" .
            "<li>Understanding the {$city} real estate market</li>\n" .
            "<li>Steps for buyers and investors</li>\n" .
            "<li>Legal and financial considerations</li>\n" .
            "<li>Local trends and price dynamics</li>\n" .
            "<li>How {$agencyName} can help you</li>\n" .
            "</ul>\n\n" .
            $listingsHtml .
            "<h2>Local Real Estate FAQ</h2>\n" .
            $faq .
            "\n<h2>Contact {$agencyName}</h2>\n" .
            "<p>Ready to take the next step? Contact {$agencyName} for personalized real estate advice in {$city}.</p>";
    }

    public function generateDataBlocks(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $city = $request->input('target_city', $profile->target_city);
        if (!$city) {
            return redirect()->back()->with('error', 'Please set a target city first.');
        }

        // Increment usage
        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            if ($usageLimit->ai_search_freshness_updates_used >= $usageLimit->ai_search_freshness_updates_limit) {
                return redirect()->back()->with('error', 'You have reached your monthly limit of AI search freshness updates.');
            }
            $usageLimit->increment('ai_search_freshness_updates_used');
        }

        $blockTypes = $request->input('block_types', ['recent_properties', 'buyer_questions', 'market_notes']);

        foreach ($blockTypes as $blockType) {
            $title = ucfirst(str_replace('_', ' ', $blockType)) . ' in ' . $city;
            $slug = Str::slug('ai-search-' . $blockType . '-' . $city);
            $content = $this->buildDataBlockContent($blockType, $city, $profile);

            $profile->generatedPages()->firstOrCreate([
                'feature_key' => 'ai_search_ranking',
                'slug' => $slug,
            ], [
                'title' => $title,
                'seo_title' => $title,
                'meta_description' => "Latest {$title}. Real data and insights for {$city} real estate.",
                'content_html' => $content,
                'content_json' => [
                    'target_city' => $city,
                    'block_type' => $blockType,
                ],
                'content_uniqueness_status' => 'pending',
                'status' => 'pending_review',
            ]);
        }

        return redirect()->back()->with('success', 'AI search data blocks generated successfully.');
    }

    protected function buildDataBlockContent($blockType, $city, $profile)
    {
        $blocks = [
            'recent_properties' => $this->buildRecentPropertiesBlock($city, $profile),
            'buyer_questions' => "<h2>Real Buyer Questions This Month in {$city}</h2><p>We collect and answer the most common questions buyers ask about {$city} real estate right now.</p><ul><li>What are the best neighborhoods to buy in {$city}?</li><li>How much do apartments cost per square meter in {$city}?</li><li>Can foreign buyers purchase property in {$city}?</li><li>What is the rental yield for investment properties in {$city}?</li><li>Are there new construction projects in {$city}?</li></ul>",
            'market_notes' => "<h2>Current Local Market Notes for {$city}</h2><p>Our monthly market notes summarize price trends, demand, and inventory changes in {$city}. The market remains active with strong interest from local and international buyers.</p>",
            'price_ranges' => "<h2>Typical Price Ranges in {$city}</h2><p>Explore current price ranges for apartments, villas, land, and investment properties in {$city}.</p><ul><li>Apartments: from €150,000</li><li>Family houses: from €300,000</li><li>Luxury villas: from €600,000</li><li>Building land: from €80,000</li></ul>",
            'rental_yield' => "<h2>Rental Yield Examples in {$city}</h2><p>See typical rental yields and income expectations for properties in {$city}.</p><ul><li>Studio apartments: 4-6% annually</li><li>One-bedroom apartments: 5-7% annually</li><li>Luxury villas: 3-5% annually</li><li>Short-term rental properties: 6-10% annually</li></ul>",
            'buyer_locations' => "<h2>Popular Buyer Locations in {$city}</h2><p>Discover the most requested neighborhoods and areas for buyers in {$city}.</p><ul><li>City center</li><li>Coastal and sea-view areas</li><li>Suburbs with family homes</li><li>Up-and-coming investment zones</li></ul>",
            'foreign_buyer_mistakes' => "<h2>Common Foreign Buyer Mistakes in {$city}</h2><p>Learn about the most frequent mistakes foreign buyers make and how to avoid them in {$city}.</p><ul><li>Not checking legal status of the property</li><li>Ignoring local market trends</li><li>Underestimating closing costs</li><li>Buying without local expert guidance</li></ul>",
        ];

        return $blocks[$blockType] ?? "<h2>" . ucfirst(str_replace('_', ' ', $blockType)) . " in {$city}</h2><p>Latest data and insights for {$city}.</p>";
    }

    protected function buildRecentPropertiesBlock($city, $profile)
    {
        $listings = $profile->agencyListings()
            ->where('status', 'active')
            ->latest()
            ->limit(6)
            ->get();

        $html = "<h2>Recent Properties We Analyze in {$city}</h2>";
        $html .= "<p>Our team continuously reviews recent property listings, sales, and market activity in {$city} to provide accurate advice.</p>";

        if ($listings->isEmpty()) {
            $html .= "<p><em>No listings have been added yet. Add your real estate listings in the Local SEO section to display them here.</em></p>";
            return $html;
        }

        $html .= "<div class='recent-properties-grid'>";
        foreach ($listings as $listing) {
            $html .= "<div class='property-card'>";
            if (count($listing->images) > 0) {
                $html .= "<img src='" . e($listing->images[0]) . "' alt='" . e($listing->title) . "' style='max-width: 100%; height: auto; border-radius: 4px;'>";
            }
            $html .= "<h3>" . e($listing->title) . "</h3>";
            if ($listing->location) {
                $html .= "<p><strong>Location:</strong> " . e($listing->location) . "</p>";
            }
            if ($listing->property_type) {
                $html .= "<p><strong>Property Type:</strong> " . e($listing->property_type) . "</p>";
            }
            if ($listing->formatted_price) {
                $html .= "<p><strong>Price:</strong> " . e($listing->formatted_price) . "</p>";
            }
            if ($listing->description) {
                $html .= "<p>" . e(Str::limit($listing->description, 200)) . "</p>";
            }
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }

    public function previewAiSearchPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        return view('agency.features.ai-search-page-preview', compact('page', 'user', 'profile'));
    }

    public function editAiSearchPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        return view('agency.features.ai-search-page-edit', compact('page', 'user', 'profile'));
    }

    public function updateAiSearchPage(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'content_html' => 'required|string',
        ]);

        $page->update([
            'title' => $validated['title'],
            'seo_title' => $validated['seo_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'content_html' => $validated['content_html'],
        ]);

        return redirect()->route('agency.ai-search.pages.preview', $page)->with('success', 'Page updated successfully.');
    }

    public function publishAiSearchPage(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Page published successfully.');
    }

    public function refreshAiSearchPage(Request $request, GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        $contentJson = $page->content_json ?? [];
        $city = $contentJson['target_city'] ?? $profile->target_city ?? 'Unknown';
        $agencyName = $contentJson['agency_name'] ?? $profile->agency_name ?? 'Our Agency';
        $blockType = $contentJson['block_type'] ?? null;
        $topic = $contentJson['authority_topic'] ?? null;

        if ($blockType) {
            $content = $this->buildDataBlockContent($blockType, $city, $profile);
        } elseif ($topic) {
            $content = $this->buildAuthorityPageContent($page->title, $city, $topic, $agencyName, $profile);
        } else {
            return redirect()->back()->with('error', 'Cannot refresh this page type.');
        }

        $page->update([
            'content_html' => $content,
        ]);

        return redirect()->back()->with('success', 'Page content refreshed successfully.');
    }

    public function destroyAiSearchPage(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    // =====================
    // AI Authority Builder
    // =====================

    public function generateAuthorityReview(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            if ($usageLimit->authority_review_updates_used >= $usageLimit->authority_review_updates_limit) {
                return redirect()->back()->with('error', 'You have reached your monthly authority review limit.');
            }
        }

        $layers = $request->input('layers', [
            'entity', 'service', 'local_market', 'buyer_questions',
            'property_data', 'trust_signals', 'competitor_context',
            'ai_readability', 'freshness', 'structured_data',
        ]);

        $city = $profile->target_city ?? $profile->city ?? 'your area';
        $agencyName = $profile->agency_name ?? 'Our Agency';
        $website = $profile->official_website_url ?? '';

        $title = "Villa Bit Review — {$agencyName} | AI Authority Review";
        $slug = Str::slug("villa-bit-review-{$agencyName}");

        $content = $this->buildAuthorityReviewContent($profile, $city, $agencyName, $website, $layers);

        // Create AiSuggestion instead of direct GeneratedPage
        $suggestion = $profile->aiSuggestions()->create([
            'feature_key' => 'ai_authority_builder',
            'suggestion_type' => 'authority_review',
            'title' => $title,
            'target_keyword' => $agencyName . ' ' . $city,
            'content_html' => $content,
            'content_json' => [
                'agency_name' => $agencyName,
                'city' => $city,
                'website' => $website,
                'layers' => $layers,
                'review_type' => 'villa_bit_review',
            ],
            'ai_summary' => "AI-generated authority review for {$agencyName} covering services, local market, buyer questions, and trust signals in {$city}.",
            'ai_conclusion' => "This structured review helps AI search systems better understand the agency's expertise and local market presence.",
            'status' => 'pending',
            'content_uniqueness_status' => 'pending',
        ]);

        if ($usageLimit) {
            $usageLimit->increment('authority_review_updates_used');
        }

        return redirect()->back()->with('success', 'Authority review suggestion created successfully. Review in Daily AI Employee or approve directly below.');
    }

    protected function buildAuthorityReviewContent($profile, $city, $agencyName, $website, $layers): string
    {
        $html = "<h1>Villa Bit Review — {$agencyName}</h1>\n";
        $html .= "<p><strong>Villa Bit AI Authority Review</strong> — This is a structured third-party authority review of {$agencyName}, created by Villa Bit AI to help AI search systems better understand this real estate agency.</p>\n\n";

        $listingsHtml = $this->buildListingsHtml($profile);
        $listings = $profile->agencyListings()->where('status', 'active')->take(5)->get();

        if (in_array('entity', $layers)) {
            $html .= "<h2>1. Company Entity Profile</h2>\n";
            $html .= "<p><strong>Agency Name:</strong> {$agencyName}</p>\n";
            if ($website) $html .= "<p><strong>Official Website:</strong> <a href=\"{$website}\">{$website}</a></p>\n";
            $html .= "<p><strong>Business Category:</strong> Real Estate Agency</p>\n";
            $html .= "<p><strong>Main Service Area:</strong> {$city}" . ($profile->main_service_area ? ", {$profile->main_service_area}" : '') . "</p>\n";
            if ($profile->country) $html .= "<p><strong>Country:</strong> {$profile->country}</p>\n";
            if ($profile->contact_email) $html .= "<p><strong>Contact:</strong> {$profile->contact_email}</p>\n";
            $html .= "\n";
        }

        if (in_array('service', $layers)) {
            $html .= "<h2>2. Services Summary</h2>\n";
            $html .= "<p>{$agencyName} is a real estate agency serving buyers, sellers, investors, and property owners in {$city} and surrounding areas.</p>\n";
            $html .= "<ul>\n";
            if ($profile->main_property_types) $html .= "<li><strong>Property Types:</strong> {$profile->main_property_types}</li>\n";
            if ($profile->buyer_types) $html .= "<li><strong>Buyer Types:</strong> {$profile->buyer_types}</li>\n";
            if ($profile->seller_services) $html .= "<li><strong>Seller Services:</strong> {$profile->seller_services}</li>\n";
            if ($profile->foreign_buyer_support) $html .= "<li><strong>Foreign Buyer Support:</strong> Available</li>\n";
            if ($profile->investment_services) $html .= "<li><strong>Investment Services:</strong> {$profile->investment_services}</li>\n";
            if ($profile->rental_management_services) $html .= "<li><strong>Rental Management:</strong> {$profile->rental_management_services}</li>\n";
            if ($profile->property_management_support) $html .= "<li><strong>Property Management:</strong> Available</li>\n";
            $html .= "</ul>\n\n";
        }

        if (in_array('local_market', $layers)) {
            $html .= "<h2>3. Local Market Coverage</h2>\n";
            $html .= "<p>{$agencyName} operates in {$city}" . ($profile->target_radius_km ? " and within approximately {$profile->target_radius_km} km" : '') . ".</p>\n";
            $html .= "<p>The agency has local knowledge of property types, pricing dynamics, buyer demand, and market conditions in the {$city} area.</p>\n";
            $html .= "<p>Key real estate activity in this area includes residential properties, investment properties, vacation rental properties, and land opportunities.</p>\n\n";
        }

        if (in_array('buyer_questions', $layers)) {
            $html .= "<h2>4. Real Buyer Questions and Answers</h2>\n";
            $html .= "<p><strong>What does {$agencyName} do?</strong><br>This agency helps buyers, sellers, investors, and foreign clients navigate the real estate market in {$city}.</p>\n";
            $html .= "<p><strong>Which areas does {$agencyName} serve?</strong><br>The agency focuses on {$city}" . ($profile->main_service_area ? " and {$profile->main_service_area}" : '') . ".</p>\n";
            $html .= "<p><strong>Does {$agencyName} help foreign buyers?</strong><br>" . ($profile->foreign_buyer_support ? "Yes. The agency provides specific support for foreign buyers including legal process guidance and local market education." : "Contact the agency directly for information about foreign buyer services.") . "</p>\n";
            $html .= "<p><strong>Does {$agencyName} work with real estate investors?</strong><br>" . ($profile->investment_services ? "Yes. {$agencyName} offers investment property services including rental yield analysis and market comparison." : "Contact the agency for investment-related inquiries.") . "</p>\n";
            $html .= "<p><strong>Does {$agencyName} offer property management?</strong><br>" . ($profile->property_management_support ? "Yes. Property management services are available." : "Contact the agency for details.") . "</p>\n\n";
        }

        if (in_array('property_data', $layers)) {
            $html .= "<h2>5. Property Data Layer</h2>\n";
            $html .= "<p>The following represents the type of properties {$agencyName} works with in {$city}:</p>\n";
            if ($listingsHtml) {
                $html .= $listingsHtml;
            } else {
                $html .= "<ul>\n";
                $html .= "<li>Residential homes and villas in {$city}</li>\n";
                $html .= "<li>Apartments and condos in {$city}</li>\n";
                $html .= "<li>Investment properties with rental potential</li>\n";
                $html .= "<li>Land parcels and development plots</li>\n";
                $html .= "<li>Luxury properties with sea views or premium locations</li>\n";
                $html .= "</ul>\n";
            }
            $html .= "\n";
        }

        if (in_array('trust_signals', $layers)) {
            $html .= "<h2>6. Public Trust Signals</h2>\n";
            $html .= "<p>Based on publicly visible website signals, {$agencyName} demonstrates trust through the following:</p>\n";
            $html .= "<ul>\n";
            $html .= "<li>Professional online presence at {$city}</li>\n";
            if ($profile->google_business_profile_url) $html .= "<li>Active Google Business Profile</li>\n";
            if ($profile->contact_email || $profile->contact_phone) $html .= "<li>Clear contact information publicly available</li>\n";
            $html .= "<li>Local market knowledge demonstrated through property listings and content</li>\n";
            $html .= "<li>Service transparency with clear buyer, seller, and investor information</li>\n";
            $html .= "</ul>\n\n";
        }

        if (in_array('competitor_context', $layers)) {
            $html .= "<h2>7. Market Context</h2>\n";
            $html .= "<p>{$agencyName} operates in the {$city} real estate market where buyers increasingly research online before contacting any agency.</p>\n";
            $html .= "<p>Agencies that explain the market clearly, provide useful buyer guides, and maintain structured online content tend to attract better-informed clients.</p>\n";
            $html .= "<p>{$agencyName} positions itself with a focus on local knowledge, buyer education, and accessible property information for both local and international clients.</p>\n\n";
        }

        if (in_array('ai_readability', $layers)) {
            $html .= "<h2>8. AI Readability Assessment</h2>\n";
            $html .= "<table>\n<thead><tr><th>Signal Area</th><th>Status</th></tr></thead>\n<tbody>\n";
            $html .= "<tr><td>Entity Clarity</td><td>" . ($agencyName ? 'Clear' : 'Needs improvement') . "</td></tr>\n";
            $html .= "<tr><td>Local Market Clarity</td><td>" . ($city ? 'Clear' : 'Needs improvement') . "</td></tr>\n";
            $html .= "<tr><td>Buyer Information</td><td>" . ($profile->buyer_types ? 'Present' : 'Needs improvement') . "</td></tr>\n";
            $html .= "<tr><td>Foreign Buyer Support</td><td>" . ($profile->foreign_buyer_support ? 'Available' : 'Not specified') . "</td></tr>\n";
            $html .= "<tr><td>Investment Information</td><td>" . ($profile->investment_services ? 'Present' : 'Not specified') . "</td></tr>\n";
            $html .= "<tr><td>Property Management</td><td>" . ($profile->property_management_support ? 'Available' : 'Not specified') . "</td></tr>\n";
            $html .= "<tr><td>Contact Clarity</td><td>" . ($profile->contact_email || $profile->contact_phone ? 'Clear' : 'Needs improvement') . "</td></tr>\n";
            $html .= "<tr><td>Website URL</td><td>" . ($website ? 'Present' : 'Missing') . "</td></tr>\n";
            $html .= "</tbody></table>\n\n";
        }

        if (in_array('freshness', $layers)) {
            $html .= "<h2>9. Freshness Signals</h2>\n";
            $html .= "<p><strong>Last Updated:</strong> " . now()->format('F Y') . "</p>\n";
            $html .= "<p>This Villa Bit AI authority review is maintained and periodically updated with new market signals, buyer questions, and property data to keep the information relevant for AI search systems.</p>\n";
            $html .= "<ul>\n";
            $html .= "<li>Review updated: " . now()->format('d F Y') . "</li>\n";
            $html .= "<li>Market area: {$city}</li>\n";
            $html .= "<li>New buyer questions reviewed this month</li>\n";
            $html .= "<li>Property examples refreshed from active listings</li>\n";
            $html .= "</ul>\n\n";
        }

        if (in_array('structured_data', $layers)) {
            $html .= "<h2>10. Structured Data Layer</h2>\n";
            $html .= "<p>This review is structured to be read and understood by AI search systems including ChatGPT, Gemini, Google AI Search, and Copilot.</p>\n";
            $html .= "<p>The page follows the Villa Bit Review standard which includes: Organization entity data, LocalBusiness signals, FAQ answer blocks, Review structure, and freshness markers.</p>\n";
            if ($website) {
                $html .= "<p><strong>Official Agency Website:</strong> <a href=\"{$website}\">{$website}</a></p>\n";
            }
            $html .= "<p><em>This authority review is produced by Villa Bit AI. It is a third-party structured review page designed to help AI systems better understand this real estate agency.</em></p>\n";
        }

        return $html;
    }

    public function previewAuthorityReview(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_authority_builder') {
            abort(403);
        }

        return view('agency.features.authority-review-preview', compact('page', 'profile'));
    }

    public function publishAuthorityReview(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_authority_builder') {
            abort(403);
        }

        $page->update([
            'status' => 'published',
            'published_at' => now(),
            'approved_by_user_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Authority review published successfully.');
    }

    public function refreshAuthorityReview(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_authority_builder') {
            abort(403);
        }

        $json = $page->content_json ?? [];
        $city = $json['city'] ?? $profile->target_city ?? 'your area';
        $agencyName = $json['agency_name'] ?? $profile->agency_name ?? 'Our Agency';
        $website = $json['website'] ?? $profile->official_website_url ?? '';
        $layers = $json['layers'] ?? ['entity', 'service', 'local_market', 'buyer_questions', 'property_data', 'trust_signals', 'competitor_context', 'ai_readability', 'freshness', 'structured_data'];

        $content = $this->buildAuthorityReviewContent($profile, $city, $agencyName, $website, $layers);

        $page->update(['content_html' => $content]);

        return redirect()->back()->with('success', 'Authority review refreshed successfully.');
    }

    public function destroyAuthorityReview(GeneratedPage $page)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile || $page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_authority_builder') {
            abort(403);
        }

        $page->delete();

        return redirect()->back()->with('success', 'Authority review deleted.');
    }

    // =====================
    // Suggestion Management
    // =====================

    public function acceptAuthoritySuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id || $suggestion->feature_key !== 'ai_authority_builder') {
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

        return redirect()->back()->with('success', 'Authority review accepted and moved to final review.');
    }

    public function skipAuthoritySuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id || $suggestion->feature_key !== 'ai_authority_builder') {
            abort(403);
        }

        $suggestion->markAsSkipped($user->id, $request->input('notes'));

        return redirect()->back()->with('success', 'Authority review suggestion skipped.');
    }

    public function acceptLocalSeoSuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id || $suggestion->feature_key !== 'local_seo_presence_boost') {
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

        return redirect()->back()->with('success', 'Local SEO content accepted and moved to final review.');
    }

    public function skipLocalSeoSuggestion(Request $request, AiSuggestion $suggestion)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($suggestion->agency_profile_id !== $user->getEffectiveAgencyProfile()?->id || $suggestion->feature_key !== 'local_seo_presence_boost') {
            abort(403);
        }

        $suggestion->markAsSkipped($user->id, $request->input('notes'));

        return redirect()->back()->with('success', 'Local SEO suggestion skipped.');
    }
}
