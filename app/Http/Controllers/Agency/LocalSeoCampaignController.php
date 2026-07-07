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
            'include_copyscape' => 'boolean',
        ]);

        $service = new UniquenessService();

        $result = $service->check(
            $validated['text'],
            $profile->id,
            $validated['include_copyscape'] ?? false
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

        $service = new UniquenessService();

        return response()->json([
            'configured' => $service->isCopyscapeAvailable(),
            'balance' => $service->getCopyscapeBalance(),
        ]);
    }
}
