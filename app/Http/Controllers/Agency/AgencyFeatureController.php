<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureSetting;
use App\Models\GeneratedPage;
use App\Models\LocalSeoTarget;
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
            $viewData = compact('feature', 'user', 'profile', 'featureSetting', 'latestReport');

            if ($feature === 'local_seo_presence_boost') {
                $viewData['cities'] = $profile->localSeoTargets()->cities()->get();
                $viewData['keywords'] = $profile->localSeoTargets()->keywords()->get();
                $viewData['subniches'] = $profile->localSeoTargets()->subniches()->get();
                $viewData['pages'] = $profile->generatedPages()
                    ->where('feature_key', 'local_seo_presence_boost')
                    ->latest()
                    ->paginate(20);
                $viewData['listings'] = $profile->agencyListings()
                    ->where('status', 'active')
                    ->latest()
                    ->get();
            }

            if ($feature === 'ai_search_ranking') {
                $viewData['pages'] = $profile->generatedPages()
                    ->where('feature_key', 'ai_search_ranking')
                    ->latest()
                    ->paginate(20);
                $viewData['dataBlocks'] = collect();
                $viewData['notifications'] = $this->getAiSearchNotifications($profile);
                $viewData['usageLimit'] = $profile->currentUsageLimit;
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

    public function generateLocalSeoTargets(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $city = $request->input('generate_city', $profile->target_city);

        if (!$city) {
            return redirect()->back()->with('error', 'Please set a target city first.');
        }

        // Update profile target city
        $profile->update([
            'target_city' => $city,
        ]);

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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if (!$profile) {
            return redirect()->back()->with('error', 'Agency profile not found.');
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
                $slug = Str::slug($title);

                $page = $profile->generatedPages()->firstOrCreate([
                    'feature_key' => 'local_seo_presence_boost',
                    'slug' => $slug,
                ], [
                    'title' => $title,
                    'seo_title' => $title,
                    'meta_description' => 'Find ' . $title . '. Professional real estate agency serving ' . $city->target_value . ' and surrounding areas.',
                    'content_html' => $this->buildLocalSeoPageContent($title, $city->target_value, $subniches, $profile),
                    'content_json' => [
                        'target_city' => $city->target_value,
                        'target_keyword' => $keyword->target_value,
                        'subniches' => $subniches,
                    ],
                    'content_uniqueness_status' => 'pending',
                    'status' => 'pending_review',
                ]);

                $city->update(['generated_page_id' => $page->id]);
                $generatedCount++;
            }
        }

        return redirect()->back()->with('success', $generatedCount . ' local SEO pages generated successfully.');
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

    public function previewLocalSeoPage(GeneratedPage $page)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        return view('agency.features.local-seo-page-preview', compact('page', 'user', 'profile'));
    }

    public function editLocalSeoPage(GeneratedPage $page)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        return view('agency.features.local-seo-page-edit', compact('page', 'user', 'profile'));
    }

    public function updateLocalSeoPage(Request $request, GeneratedPage $page)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id) {
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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id) {
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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }

    public function storeListing(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

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
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('agency-listings/' . $profile->id, 'public');
                $images[] = asset('storage/' . $path);
            }
        }

        $profile->agencyListings()->create([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'EUR',
            'images_json' => $images,
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->back()->with('success', 'Listing added successfully.');
    }

    public function updateListing(Request $request, \App\Models\AgencyListing $listing)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($listing->agency_profile_id !== $profile->id) {
            abort(403);
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
        ]);

        $images = $listing->images_json ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('agency-listings/' . $profile->id, 'public');
                $images[] = asset('storage/' . $path);
            }
        }

        $listing->update([
            'title' => $validated['title'],
            'property_type' => $validated['property_type'] ?? null,
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'] ?? null,
            'currency' => $validated['currency'] ?? 'EUR',
            'images_json' => $images,
            'status' => $validated['status'] ?? 'active',
        ]);

        return redirect()->back()->with('success', 'Listing updated successfully.');
    }

    public function destroyListing(\App\Models\AgencyListing $listing)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($listing->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $listing->delete();

        return redirect()->back()->with('success', 'Listing deleted successfully.');
    }

    // AI Search Ranking

    public function generateAuthorityPages(Request $request)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

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
        $user = Auth::user();
        $profile = $user->agencyProfile;

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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        return view('agency.features.ai-search-page-preview', compact('page', 'user', 'profile'));
    }

    public function editAiSearchPage(GeneratedPage $page)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        return view('agency.features.ai-search-page-edit', compact('page', 'user', 'profile'));
    }

    public function updateAiSearchPage(Request $request, GeneratedPage $page)
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
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
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if ($page->agency_profile_id !== $profile->id || $page->feature_key !== 'ai_search_ranking') {
            abort(403);
        }

        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully.');
    }
}
