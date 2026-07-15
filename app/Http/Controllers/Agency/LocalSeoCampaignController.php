<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\LocalSeoCampaign;
use App\Models\GeneratedPage;
use App\Models\ScheduledPageGeneration;
use App\Services\PlaceSuggestionService;
use App\Services\UniquenessService;
use App\Services\LocalSeoContentGenerator;
use App\Services\UsageLimitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LocalSeoCampaignController extends Controller
{
    protected function profileOrFail()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->getEffectiveAgencyProfile();
    }

    protected function authorizeCampaign(LocalSeoCampaign $campaign, $profile): void
    {
        if (!$profile || $campaign->agency_profile_id !== $profile->id) {
            abort(403);
        }
    }

    /**
     * Create or update a campaign draft (Section 1).
     */
    public function storeDraft(Request $request)
    {
        $profile = $this->profileOrFail();
        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        $validated = $request->validate([
            'campaign_id'      => 'nullable|exists:local_seo_campaigns,id',
            'name'             => 'required|string|max:255',
            'primary_city'     => 'nullable|string|max:255',
            'country'          => 'nullable|string|max:255',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'coverage_area'    => 'nullable|integer|min:1|max:1000',
            'coverage_unit'    => 'nullable|string|in:km,mi',
            'target_places'    => 'nullable|array',
            'positioning_note' => 'nullable|string',
        ]);

        $data = [
            'name'             => $validated['name'],
            'primary_city'     => $validated['primary_city'] ?? null,
            'country'          => $validated['country'] ?? null,
            'latitude'         => $validated['latitude'] ?? null,
            'longitude'        => $validated['longitude'] ?? null,
            'coverage_area'    => $validated['coverage_area'] ?? null,
            'coverage_unit'    => $validated['coverage_unit'] ?? 'km',
            'target_places'    => $validated['target_places'] ?? [],
            'positioning_note' => $validated['positioning_note'] ?? null,
        ];

        if (!empty($validated['campaign_id'])) {
            $campaign = LocalSeoCampaign::findOrFail($validated['campaign_id']);
            $this->authorizeCampaign($campaign, $profile);
            $oldPlaces = $campaign->target_places ?? [];
            $campaign->update($data);
            $message = 'Campaign updated.';
            
            // Schedule articles for new places only
            $newPlaces = $this->getNewPlaces($oldPlaces, $data['target_places'] ?? []);
            $scheduled = $this->createArticlesForPlaces($campaign, $profile, $newPlaces);
            if ($scheduled > 0) {
                $message .= $this->getScheduleMessage($campaign, $profile, $scheduled);
            }
        } else {
            $campaign = $profile->localSeoCampaigns()->create($data + ['status' => 'draft']);
            $message = 'Campaign draft saved.';
            
            // Schedule articles for all places
            $scheduled = $this->createArticlesForPlaces($campaign, $profile, $data['target_places'] ?? []);
            if ($scheduled > 0) {
                $message .= $this->getScheduleMessage($campaign, $profile, $scheduled);
            }
        }

        // Auto-generate AI content in background
        $this->generateAiContentAsync($campaign, $profile);

        return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost'])
            ->with('success', $message)
            ->with('edit_campaign_id', $campaign->id);
    }
    
    /**
     * Get places that are new (not in old list).
     */
    protected function getNewPlaces(array $oldPlaces, array $newPlaces): array
    {
        $oldNames = array_map(fn($p) => $p['name'] ?? '', $oldPlaces);
        return array_filter($newPlaces, fn($p) => !in_array($p['name'] ?? '', $oldNames));
    }
    
    /**
     * Schedule SEO article pages for each place in the campaign.
     * Creates first one immediately (if quota available), schedules rest for future days.
     */
    protected function createArticlesForPlaces(LocalSeoCampaign $campaign, $profile, array $places): int
    {
        $scheduled = 0;
        $createdToday = 0;
        
        // Check today's usage - how many pages created today for this profile
        $todayUsage = GeneratedPage::where('agency_profile_id', $profile->id)
            ->where('feature_key', 'local_seo_presence_boost')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        // Daily limit (from plan, default 1)
        $dailyLimit = $profile->plan_limits['local_seo_pages_per_day'] ?? 1;
        $canCreateToday = $dailyLimit - $todayUsage;
        
        // Get next available date for scheduling
        $nextScheduleDate = now()->addDay();
        
        // Check existing scheduled items to find the last scheduled date
        $lastScheduled = ScheduledPageGeneration::where('agency_profile_id', $profile->id)
            ->where('local_seo_campaign_id', $campaign->id)
            ->where('status', 'pending')
            ->orderBy('scheduled_for', 'desc')
            ->first();
        
        if ($lastScheduled) {
            $nextScheduleDate = $lastScheduled->scheduled_for->addDay();
        }
        
        foreach ($places as $place) {
            $placeName = $place['name'] ?? '';
            if (empty($placeName)) continue;
            
            // Check if article or schedule already exists for this place
            $pageExists = GeneratedPage::where('agency_profile_id', $profile->id)
                ->where('local_seo_campaign_id', $campaign->id)
                ->where('target_neighborhood', $placeName)
                ->exists();
                
            $scheduleExists = ScheduledPageGeneration::where('agency_profile_id', $profile->id)
                ->where('local_seo_campaign_id', $campaign->id)
                ->where('place_name', $placeName)
                ->whereIn('status', ['pending', 'processing'])
                ->exists();
                
            if ($pageExists || $scheduleExists) continue;
            
            // Can we create today?
            if ($canCreateToday > 0 && $createdToday < $canCreateToday) {
                // Create immediately
                $this->createPageForPlace($campaign, $profile, $place);
                $createdToday++;
                $scheduled++;
            } else {
                // Schedule for future
                ScheduledPageGeneration::create([
                    'agency_profile_id' => $profile->id,
                    'local_seo_campaign_id' => $campaign->id,
                    'place_name' => $placeName,
                    'place_type' => $place['type'] ?? null,
                    'place_distance' => $place['distance'] ?? null,
                    'scheduled_for' => $nextScheduleDate,
                    'status' => 'pending',
                ]);
                $nextScheduleDate = $nextScheduleDate->copy()->addDay();
                $scheduled++;
            }
        }
        
        return $scheduled;
    }
    
    /**
     * Create a single page for a place.
     */
    protected function createPageForPlace(LocalSeoCampaign $campaign, $profile, array $place): GeneratedPage
    {
        $placeName = $place['name'] ?? '';
        $pageName = "Real Estate in {$placeName}";
        if ($campaign->primary_city && $placeName !== $campaign->primary_city) {
            $pageName = "Real Estate in {$placeName}, {$campaign->primary_city}";
        }
        
        return GeneratedPage::create([
            'agency_profile_id' => $profile->id,
            'local_seo_campaign_id' => $campaign->id,
            'feature_key' => 'local_seo_presence_boost',
            'name' => $pageName,
            'title' => $pageName,
            'slug' => Str::slug($pageName . '-' . uniqid()),
            'target_city' => $campaign->primary_city,
            'target_neighborhood' => $placeName,
            'country' => $campaign->country,
            'latitude' => $campaign->latitude,
            'longitude' => $campaign->longitude,
            'property_type' => 'apartment',
            'status' => 'draft',
            'page_type' => 'location_seo',
        ]);
    }

    /**
     * Get a message describing what was scheduled.
     */
    protected function getScheduleMessage(LocalSeoCampaign $campaign, $profile, int $total): string
    {
        // Count how many were created today vs scheduled
        $createdToday = GeneratedPage::where('agency_profile_id', $profile->id)
            ->where('local_seo_campaign_id', $campaign->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $pendingScheduled = ScheduledPageGeneration::where('agency_profile_id', $profile->id)
            ->where('local_seo_campaign_id', $campaign->id)
            ->where('status', 'pending')
            ->count();
        
        if ($pendingScheduled > 0) {
            $lastScheduled = ScheduledPageGeneration::where('agency_profile_id', $profile->id)
                ->where('local_seo_campaign_id', $campaign->id)
                ->where('status', 'pending')
                ->orderBy('scheduled_for', 'desc')
                ->first();
            
            $lastDate = $lastScheduled ? $lastScheduled->scheduled_for->format('M j, Y') : '';
            
            return " {$createdToday} page(s) created now, {$pendingScheduled} scheduled (completing {$lastDate}).";
        }
        
        return " {$createdToday} page(s) created.";
    }
    
    /**
     * Generate AI content asynchronously (non-blocking).
     */
    protected function generateAiContentAsync(LocalSeoCampaign $campaign, $profile): void
    {
        try {
            $generator = new LocalSeoContentGenerator();
            $generator->generateForCampaign($campaign, $profile);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('AI content generation failed: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: AI-suggested places inside coverage area.
     */
    public function suggestPlaces(Request $request, PlaceSuggestionService $service)
    {
        $validated = $request->validate([
            'primary_city'  => 'required|string|max:255',
            'country'       => 'nullable|string|max:255',
            'coverage_area' => 'required|integer|min:1|max:1000',
            'coverage_unit' => 'nullable|string|in:km,mi',
        ]);

        $places = $service->suggest(
            $validated['primary_city'],
            $validated['country'] ?? null,
            (int) $validated['coverage_area'],
            $validated['coverage_unit'] ?? 'km'
        );

        return response()->json(['places' => $places]);
    }

    public function destroy(LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        $campaign->delete();

        return redirect()->route('agency.features.show', 'local_seo_presence_boost')
            ->with('success', 'Campaign removed.');
    }

    /**
     * Update page settings for a campaign (Villa Bit AI Office section).
     */
    public function updateSettings(Request $request, LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        $pageSettings = [
            'show_lead_magnet' => $request->boolean('show_lead_magnet'),
            'show_faq' => $request->boolean('show_faq'),
            'show_listings' => $request->boolean('show_listings'),
            'featured_listings_percent' => (int) $request->input('featured_listings_percent', 10),
            'regular_listings_percent' => (int) $request->input('regular_listings_percent', 6),
            'approval_status' => $request->input('approval_status', 'pending'),
        ];

        $campaign->update(['page_settings' => $pageSettings]);

        return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit_campaign_id' => $campaign->id])
            ->with('success', 'Page settings saved.');
    }

    /**
     * Render the realestate.taxi-styled campaign page for previewing,
     * even before it is published.
     */
    public function preview(LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        $page = $campaign->generatedPage ?? new GeneratedPage([
            'title'            => $campaign->name,
            'seo_title'        => $campaign->name . ' | ' . $profile->agency_name,
            'meta_description' => $this->buildCampaignMetaDescription($campaign, $profile),
        ]);

        return view('realestate-taxi.campaign', [
            'page'     => $page,
            'campaign' => $campaign,
            'profile'  => $profile,
        ]);
    }

    /**
     * Toggle a campaign between published (active) and unpublished.
     */
    public function toggleStatus(LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        if ($campaign->status === 'published') {
            // Unpublish - also delete from server
            $campaign->update(['status' => 'unpublished']);
            if ($campaign->generatedPage) {
                $campaign->generatedPage->update(['status' => 'draft']);
            }

            // Delete from server via SFTP
            if ($profile->server_ip && $profile->sftp_username && $profile->sftp_password) {
                $uploader = new \App\Services\PageSftpUploader();
                $uploader->deleteCampaignPage($campaign, $profile);
            }

            $msg = 'Campaign unpublished and removed from server.';
        } else {
            // Publish - also upload to server
            $campaign->update(['status' => 'published', 'published_at' => now()]);
            if ($campaign->generatedPage) {
                $campaign->generatedPage->update(['status' => 'published', 'published_at' => now()]);
            }

            // Upload to server via SFTP
            if ($profile->server_ip && $profile->sftp_username && $profile->sftp_password) {
                $uploader = new \App\Services\PageSftpUploader();
                $uploadResult = $uploader->uploadCampaignPage($campaign, $profile);
                if ($uploadResult['success']) {
                    $msg = 'Campaign published and uploaded to server.';
                } else {
                    $msg = 'Campaign published but upload failed: ' . $uploadResult['message'];
                }
            } else {
                $msg = 'Campaign published (SFTP not configured).';
            }
        }

        return redirect()->route('agency.features.show', 'local_seo_presence_boost')->with('success', $msg);
    }

    /**
     * Save publishing settings (Section 3) and publish the campaign.
     */
    public function publish(Request $request, LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        $validated = $request->validate([
            'page_slug' => 'nullable|string|max:255',
        ]);

        $slug = $validated['page_slug'] ?? null;
        $slug = $slug ? '/' . trim(Str::slug(trim($slug, '/')), '-') . '/' : $this->suggestedSlug($campaign);

        $domain = $profile->custom_domain ?: 'your-domain.com';
        $targetUrl = rtrim($domain, '/') . $slug;

        $page = $campaign->generatedPage;
        $content = $this->buildCampaignPageHtml($campaign, $profile);
        $seoTitle = $campaign->name . ' | ' . $profile->agency_name;
        $metaDescription = $this->buildCampaignMetaDescription($campaign, $profile);

        if ($page) {
            $page->update([
                'title'            => $campaign->name,
                'seo_title'        => $seoTitle,
                'meta_description' => $metaDescription,
                'slug'             => trim($slug, '/'),
                'content_html'     => $content,
                'target_url'       => $targetUrl,
                'status'           => 'published',
                'published_at'     => now(),
            ]);
        } else {
            $page = $profile->generatedPages()->create([
                'feature_key'      => 'local_seo_presence_boost',
                'title'            => $campaign->name,
                'seo_title'        => $seoTitle,
                'meta_description' => $metaDescription,
                'slug'             => trim($slug, '/'),
                'content_html'     => $content,
                'target_url'       => $targetUrl,
                'status'           => 'published',
                'published_at'     => now(),
            ]);
        }

        $campaign->update([
            'page_slug'         => $slug,
            'status'            => 'published',
            'published_at'      => now(),
            'generated_page_id' => $page->id,
        ]);

        // Upload to server via SFTP if credentials are configured
        $uploadResult = null;
        if ($profile->server_ip && $profile->sftp_username && $profile->sftp_password) {
            $uploader = new \App\Services\PageSftpUploader();
            $uploadResult = $uploader->uploadCampaignPage($campaign, $profile);
        }

        if ($uploadResult && $uploadResult['success']) {
            return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit_campaign_id' => $campaign->id])
                ->with('success', 'Campaign published and uploaded to ' . ($uploadResult['url'] ?? $targetUrl));
        } elseif ($uploadResult) {
            return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit_campaign_id' => $campaign->id])
                ->with('warning', 'Campaign saved but SFTP upload failed: ' . $uploadResult['message']);
        }

        return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost', 'edit_campaign_id' => $campaign->id])
            ->with('success', 'Campaign published to ' . $targetUrl . ' (SFTP not configured - page saved locally only)');
    }

    protected function suggestedSlug(LocalSeoCampaign $campaign): string
    {
        $base = $campaign->primary_city ?: $campaign->name;
        return '/' . Str::slug('real-estate-' . $base) . '/';
    }

    protected function buildCampaignMetaDescription(LocalSeoCampaign $campaign, $profile): string
    {
        $city = $campaign->primary_city ?? $profile->city ?? 'this area';
        $base = $campaign->positioning_note
            ?? 'Explore real estate in ' . $city . ' with ' . $profile->agency_name . '.';
        return Str::limit(strip_tags($base), 155);
    }

    /**
     * Basic published-page HTML fallback for non-template contexts (RSS, exports, etc.).
     * The public page is rendered by the realestate-taxi/campaign Blade template.
     */
    protected function buildCampaignPageHtml(LocalSeoCampaign $campaign, $profile): string
    {
        $city = e($campaign->primary_city ?? '');
        $html = "<h1>Real Estate in {$city}</h1>\n";

        if ($campaign->positioning_note) {
            $html .= '<p>' . e($campaign->positioning_note) . "</p>\n";
        }

        $places = collect($campaign->target_places ?? [])->pluck('name')->filter()->all();
        if (!empty($places)) {
            $html .= "<h2>Areas We Cover</h2>\n<ul>\n";
            foreach ($places as $place) {
                $html .= '<li>' . e($place) . "</li>\n";
            }
            $html .= "</ul>\n";
        }

        $listings = $campaign->nearbyListings()->latest()->get();
        if ($listings->isNotEmpty()) {
            $html .= "<h2>Featured Listings</h2>\n";
            foreach ($listings as $listing) {
                $html .= '<div class="listing"><h3>' . e($listing->title) . '</h3>';
                if ($listing->formatted_price) {
                    $html .= '<p><strong>Price:</strong> ' . e($listing->formatted_price) . '</p>';
                }
                if ($listing->location) {
                    $html .= '<p><strong>Location:</strong> ' . e($listing->location) . '</p>';
                }
                if ($listing->description) {
                    $html .= '<p>' . e($listing->description) . '</p>';
                }
                $html .= "</div>\n";
            }
        }

        return $html;
    }

    /**
     * Check uniqueness of text content.
     * POST /agency/local-seo-presence-boost/check-uniqueness
     */
    public function checkUniqueness(Request $request)
    {
        $profile = $this->profileOrFail();
        if (!$profile) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'text' => 'required|string|min:50',
            'include_google' => 'boolean',
            'include_copyscape' => 'boolean',
            'auto_rewrite' => 'boolean',
        ]);

        // Pass agency's Copyscape credentials if they want to use Copyscape
        $copyscapeUsername = null;
        $copyscapeApiKey = null;
        if ($validated['include_copyscape'] ?? false) {
            $copyscapeUsername = $profile->copyscape_username;
            $copyscapeApiKey = $profile->copyscape_api_key;
        }

        $service = new UniquenessService($copyscapeUsername, $copyscapeApiKey);

        $result = $service->check(
            $validated['text'],
            $profile->id,
            $validated['include_google'] ?? true,   // Google is FREE, enabled by default
            $validated['include_copyscape'] ?? false,
            $validated['auto_rewrite'] ?? false     // Auto-rewrite when duplicates found
        );

        return response()->json($result);
    }

    /**
     * Get Copyscape status and balance.
     * GET /agency/local-seo-presence-boost/copyscape-status
     */
    public function copyscapeStatus()
    {
        $profile = $this->profileOrFail();
        if (!$profile) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if agency has Copyscape credentials configured
        $hasCredentials = !empty($profile->copyscape_username) && !empty($profile->copyscape_api_key);

        if ($hasCredentials) {
            // Try to get balance using agency's credentials
            $checker = new \App\Services\CopyscapeChecker(
                $profile->copyscape_username,
                $profile->copyscape_api_key
            );
            $balance = $checker->getBalance();

            return response()->json([
                'configured' => true,
                'balance' => $balance,
            ]);
        }

        return response()->json([
            'configured' => false,
            'balance' => null,
        ]);
    }

    /**
     * Get campaign content for uniqueness checking.
     * Extracts all AI-generated text from the campaign preview.
     * GET /agency/local-seo-presence-boost/campaigns/{campaign}/content
     */
    public function getCampaignContent(LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        if (!$profile || $campaign->agency_profile_id !== $profile->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Build comprehensive content from all campaign elements
        $contentParts = [];

        // 1. Campaign header/intro
        $city = $campaign->primary_city ?? 'this area';
        $contentParts[] = "Explore real estate opportunities in {$city} and the surrounding area. We cover the places that matter most for buyers, sellers, and investors.";

        // 2. Areas we cover - with AI-generated "Why it matters" descriptions
        $places = $campaign->target_places ?? [];
        if (!empty($places)) {
            $placesSection = "Areas we cover around {$city}:\n";
            foreach ($places as $place) {
                $name = $place['name'] ?? '';
                $type = $place['type'] ?? '';
                $distance = $place['distance'] ?? '';
                $whyItMatters = $place['why_it_matters'] ?? $place['description'] ?? '';
                
                if ($name) {
                    $placesSection .= "\n• {$name}";
                    if ($type) $placesSection .= " ({$type})";
                    if ($distance) $placesSection .= " — {$distance}";
                    if ($whyItMatters) $placesSection .= "\n  {$whyItMatters}";
                }
            }
            $contentParts[] = $placesSection;
        }

        // 3. Featured listings
        $listings = $campaign->nearbyListings()->get();
        if ($listings->isNotEmpty()) {
            $listingsSection = "Featured properties in {$city}:\n";
            foreach ($listings as $listing) {
                $listingsSection .= "\n• " . $listing->title;
                if ($listing->location) {
                    $listingsSection .= " — " . $listing->location;
                }
                if ($listing->description) {
                    $listingsSection .= "\n  " . $listing->description;
                }
            }
            $contentParts[] = $listingsSection;
        }

        // 4. About section / positioning note
        if ($campaign->positioning_note) {
            $contentParts[] = $campaign->positioning_note;
        }

        // 5. Agency description
        $agencyName = $profile->agency_name ?? 'Our agency';
        $contentParts[] = "{$agencyName} is a real estate agency focused on {$city} and the surrounding region. We track listings, market signals, and buyer interest across the wider market — helping you make better decisions with clear, local knowledge.";

        // Combine all content with proper line breaks
        $fullContent = implode("\n\n", $contentParts);

        // Clean up but preserve line breaks
        $plainText = strip_tags($fullContent);
        $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
        // Normalize multiple spaces but keep newlines
        $plainText = preg_replace('/[ \t]+/', ' ', $plainText);
        $plainText = preg_replace('/\n\s*\n/', "\n\n", $plainText);
        $plainText = trim($plainText);

        $wordCount = str_word_count($plainText);

        if ($wordCount < 30) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign has minimal content (' . $wordCount . ' words). Add more target places with descriptions or listings.',
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
            ]);
        }

        return response()->json([
            'success' => true,
            'campaign_id' => $campaign->id,
            'campaign_name' => $campaign->name,
            'content' => $plainText,
            'word_count' => $wordCount,
        ]);
    }

    /**
     * Generate AI content for a campaign.
     * POST /agency/local-seo-presence-boost/campaigns/{campaign}/generate-content
     */
    public function generateContent(LocalSeoCampaign $campaign, LocalSeoContentGenerator $generator, UsageLimitService $usageService)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        // Check usage limits (daily + monthly)
        $check = $usageService->canUse($profile, 'local_seo_pages');
        if (!$check['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $check['message'] ?? 'Usage limit reached. Try again tomorrow or upgrade your plan.',
                'limit_reached' => true,
            ], 429);
        }

        $result = $generator->generateForCampaign($campaign, $profile);

        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'message' => 'Content generation failed: ' . $result['error'],
            ], 500);
        }

        // Consume usage after successful generation
        $usageService->consume($profile, 'local_seo_pages');

        return response()->json([
            'success' => true,
            'message' => 'AI content generated successfully!',
            'word_count' => $result['word_count'] ?? 0,
            'generated_at' => $result['generated_at'] ?? now()->toIso8601String(),
        ]);
    }

    /**
     * Check uniqueness and publish if passed.
     * POST /agency/local-seo-presence-boost/campaigns/{campaign}/check-and-publish
     */
    public function checkAndPublish(Request $request, LocalSeoCampaign $campaign)
    {
        $profile = $this->profileOrFail();
        $this->authorizeCampaign($campaign, $profile);

        // Get campaign content for uniqueness check
        $content = $this->extractCampaignText($campaign, $profile);

        if (strlen($content) < 100) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough content to check. Generate AI content first.',
            ], 400);
        }

        // Check uniqueness
        $service = new UniquenessService(
            $profile->copyscape_username,
            $profile->copyscape_api_key
        );

        $result = $service->check($content, $profile->id, true, false, false);

        // Save result
        $campaign->update([
            'content_uniqueness_status' => $result['verdict'] ?? 'unknown',
            'uniqueness_result' => $result,
        ]);

        // If passed, allow publishing
        if (($result['verdict'] ?? '') === 'unique' || ($result['verdict'] ?? '') === 'likely_unique') {
            return response()->json([
                'success' => true,
                'can_publish' => true,
                'verdict' => $result['verdict'],
                'message' => 'Content is unique! Ready to publish.',
            ]);
        }

        return response()->json([
            'success' => true,
            'can_publish' => false,
            'verdict' => $result['verdict'] ?? 'unknown',
            'message' => 'Content may have duplicates. Review before publishing.',
            'matches' => $result['matches'] ?? [],
        ]);
    }

    /**
     * Extract all text content from campaign for uniqueness checking.
     */
    protected function extractCampaignText(LocalSeoCampaign $campaign, $profile): string
    {
        $parts = [];
        $aiContent = $campaign->ai_generated_content ?? [];

        // Hero content
        if (!empty($aiContent['hero_content'])) {
            $parts[] = $aiContent['hero_content'];
        }

        // Area descriptions
        foreach ($aiContent['area_descriptions'] ?? [] as $area) {
            if (!empty($area['description'])) {
                $parts[] = $area['description'];
            }
        }

        // FAQ content
        foreach ($aiContent['faq_content'] ?? [] as $faq) {
            if (!empty($faq['answer'])) {
                $parts[] = $faq['answer'];
            }
        }

        // About content
        if (!empty($aiContent['about_content'])) {
            $parts[] = $aiContent['about_content'];
        }

        // Positioning note
        if (!empty($campaign->positioning_note)) {
            $parts[] = $campaign->positioning_note;
        }

        return implode("\n\n", $parts);
    }

    /**
     * Place autocomplete for manual place input.
     * Returns the query as a suggestion - user can add any place manually.
     * GET /agency/local-seo-presence-boost/place-autocomplete
     */
    public function placeAutocomplete(Request $request)
    {
        $profile = $this->profileOrFail();
        if (!$profile) {
            return response()->json(['suggestions' => []], 403);
        }

        $query = $request->input('query', '');
        $city = $request->input('city', '');

        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // Return the query as suggestions with different types
        // User can click to add or just press Enter to add as custom
        $suggestions = [
            [
                'name' => $query,
                'type' => 'Street',
                'distance' => '',
            ],
            [
                'name' => $query,
                'type' => 'Neighborhood',
                'distance' => '',
            ],
            [
                'name' => $query,
                'type' => 'Landmark',
                'distance' => '',
            ],
        ];

        return response()->json(['suggestions' => $suggestions]);
    }
}
