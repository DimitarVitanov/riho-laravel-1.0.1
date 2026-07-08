<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\LocalSeoCampaign;
use App\Models\GeneratedPage;
use App\Services\PlaceSuggestionService;
use App\Services\UniquenessService;
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
            $campaign->update($data);
            $message = 'Campaign updated.';
        } else {
            $campaign = $profile->localSeoCampaigns()->create($data + ['status' => 'draft']);
            $message = 'Campaign draft saved.';
        }

        return redirect()->route('agency.features.show', ['feature' => 'local_seo_presence_boost'])
            ->with('success', $message)
            ->with('edit_campaign_id', $campaign->id);
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
            $campaign->update(['status' => 'unpublished']);
            if ($campaign->generatedPage) {
                $campaign->generatedPage->update(['status' => 'draft']);
            }
            $msg = 'Campaign unpublished.';
        } else {
            $campaign->update(['status' => 'published', 'published_at' => now()]);
            if ($campaign->generatedPage) {
                $campaign->generatedPage->update(['status' => 'published', 'published_at' => now()]);
            }
            $msg = 'Campaign published.';
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

        return redirect()->route('agency.features.show', 'local_seo_presence_boost')
            ->with('success', 'Campaign published to ' . $targetUrl);
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

        $listings = $campaign->listings()->latest()->get();
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
        $listings = $campaign->listings()->get();
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
}
