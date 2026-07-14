<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiAuthorityPage;
use App\Models\AgencyProfile;
use App\Models\AgencyListing;
use App\Models\GeneratedPage;
use App\Services\AiAuthorityContentGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AiSearchRankingController extends Controller
{
    protected function profileOrFail(): AgencyProfile
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        if (!$profile) {
            abort(403, 'No agency profile found.');
        }

        return $profile;
    }

    public function index(Request $request)
    {
        $profile = $this->profileOrFail();

        $pages = AiAuthorityPage::where('agency_profile_id', $profile->id)
            ->latest()
            ->get();

        $editPage = null;
        if ($request->has('edit_page_id')) {
            $editPage = AiAuthorityPage::where('agency_profile_id', $profile->id)
                ->find($request->edit_page_id);
        }

        // Get listings for this agency
        $listings = AgencyListing::where('agency_profile_id', $profile->id)
            ->orderBy('title')
            ->get();

        // Get feature setting (use FeatureSetting if exists, otherwise null)
        $featureSetting = null;
        if (class_exists(\App\Models\FeatureSetting::class)) {
            $featureSetting = \App\Models\FeatureSetting::where('agency_profile_id', $profile->id)
                ->where('feature', 'ai_search_ranking')
                ->first();
        }

        // Simple usage limit status
        $usageLimitStatus = ['can_use_today' => true];

        return view('agency.features.ai_search_ranking', [
            'profile' => $profile,
            'pages' => $pages,
            'editPage' => $editPage,
            'createMode' => $request->has('create_page'),
            'listings' => $listings,
            'featureSetting' => $featureSetting,
            'usageLimitStatus' => $usageLimitStatus,
        ]);
    }

    public function store(Request $request)
    {
        $profile = $this->profileOrFail();

        $validated = $request->validate([
            'page_id' => 'nullable|exists:ai_authority_pages,id',
            'name' => 'required|string|max:255',
            'target_city' => 'required|string|max:255',
            'target_neighborhood' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'property_type' => 'nullable|string|max:100',
            'page_type' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:255',
        ]);

        $slug = $validated['slug'] ?? Str::slug('ai-' . $validated['target_city'] . '-' . $validated['name']);

        if ($request->page_id) {
            $page = AiAuthorityPage::where('agency_profile_id', $profile->id)
                ->findOrFail($request->page_id);

            $page->update([
                'name' => $validated['name'],
                'target_city' => $validated['target_city'],
                'target_neighborhood' => $validated['target_neighborhood'] ?? null,
                'country' => $validated['country'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'property_type' => $validated['property_type'] ?? null,
                'page_type' => $validated['page_type'] ?? 'property',
                'slug' => $slug,
            ]);

            return redirect()->route('agency.features.show', 'ai_search_ranking')
                ->with('success', 'Authority page updated.');
        }

        $page = AiAuthorityPage::create([
            'agency_profile_id' => $profile->id,
            'name' => $validated['name'],
            'target_city' => $validated['target_city'],
            'target_neighborhood' => $validated['target_neighborhood'] ?? null,
            'country' => $validated['country'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'property_type' => $validated['property_type'] ?? null,
            'page_type' => $validated['page_type'] ?? 'property',
            'slug' => $slug,
            'status' => 'draft',
        ]);

        return redirect()->route('agency.ai-search-ranking.generate', $page->id)
            ->with('success', 'Authority page created. Generating AI content...');
    }

    public function generate(AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $usageLimit = $profile->currentUsageLimit;
        if ($usageLimit) {
            if (!$usageLimit->consume('ai_search_ranking')) {
                return redirect()->route('agency.features.show', 'ai_search_ranking')
                    ->with('error', 'You have reached your monthly limit of ' . $usageLimit->ai_search_ranking_limit . ' AI Search Ranking pages.');
            }
        }

        try {
            $generator = new AiAuthorityContentGenerator();
            $content = $generator->generateForPage($page, $profile);

            $page->update([
                'ai_generated_content' => $content,
                'meta_title' => $content['meta_title'] ?? $page->name,
                'meta_description' => $content['meta_description'] ?? null,
            ]);

            return redirect()->route('agency.features.show', 'ai_search_ranking')
                ->with('success', 'AI content generated successfully.');
        } catch (\Exception $e) {
            // Refund the consumed unit on failure
            if ($usageLimit) {
                $usageLimit->decrement('ai_search_ranking_used');
            }

            return redirect()->route('agency.features.show', 'ai_search_ranking')
                ->with('error', 'Failed to generate AI content: ' . $e->getMessage());
        }
    }

    public function preview(AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        return view('realestate-taxi.ai-search-page', [
            'page' => $page,
            'profile' => $profile,
            'listing' => $page->listing,
            'preview' => true,
        ]);
    }

    public function edit(AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $listings = \App\Models\AgencyListing::where('agency_profile_id', $profile->id)
            ->orderBy('title', 'asc')
            ->get();

        return view('agency.features.ai-search-ranking-edit', [
            'page' => $page,
            'profile' => $profile,
            'listings' => $listings,
        ]);
    }

    public function updateListing(Request $request, AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $page->update([
            'agency_listing_id' => $request->agency_listing_id ?: null,
        ]);

        return redirect()->back()->with('success', 'Listing connection updated.');
    }

    public function updateContent(Request $request, AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $validated = $request->validate([
            'ai_generated_content' => 'required|array',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $page->update($validated);

        return redirect()->route('agency.features.show', 'ai_search_ranking')
            ->with('success', 'Page content updated.');
    }

    public function toggleStatus(AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        if ($page->status === 'published') {
            $page->update(['status' => 'draft', 'published_at' => null]);
            $msg = 'Page unpublished.';
        } else {
            $page->update(['status' => 'published', 'published_at' => now()]);
            $msg = 'Page published.';
        }

        return redirect()->route('agency.features.show', 'ai_search_ranking')
            ->with('success', $msg);
    }

    public function destroy(AiAuthorityPage $page)
    {
        $profile = $this->profileOrFail();

        if ($page->agency_profile_id !== $profile->id) {
            abort(403);
        }

        $page->delete();

        return redirect()->route('agency.features.show', 'ai_search_ranking')
            ->with('success', 'Authority page deleted.');
    }
}
