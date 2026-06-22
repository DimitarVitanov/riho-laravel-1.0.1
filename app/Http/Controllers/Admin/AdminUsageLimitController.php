<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\UsageLimit;
use Illuminate\Http\Request;

class AdminUsageLimitController extends Controller
{
    public function index()
    {
        $limits = UsageLimit::with('agencyProfile', 'agencyProfile.user')
            ->latest('period_start')
            ->paginate(20);

        return view('admin.villabit.usage-limits.index', compact('limits'));
    }

    public function edit(UsageLimit $usageLimit)
    {
        $usageLimit->load('agencyProfile', 'agencyProfile.user');
        return view('admin.villabit.usage-limits.edit', compact('usageLimit'));
    }

    public function update(Request $request, UsageLimit $usageLimit)
    {
        $validated = $request->validate([
            'local_seo_pages_limit' => 'nullable|integer|min:0',
            'competitor_scans_limit' => 'nullable|integer|min:0',
            'ai_search_freshness_updates_limit' => 'nullable|integer|min:0',
            'authority_review_updates_limit' => 'nullable|integer|min:0',
            'small_ai_content_actions_limit' => 'nullable|integer|min:0',
        ]);

        $usageLimit->update($validated);

        return back()->with('success', 'Usage limits updated.');
    }

    public function bulkCreate()
    {
        $periodStart = now()->startOfMonth();
        $periodEnd   = now()->endOfMonth();

        $profiles = AgencyProfile::all();
        $created  = 0;

        foreach ($profiles as $profile) {
            $exists = UsageLimit::where('agency_profile_id', $profile->id)
                ->where('period_start', $periodStart->toDateString())
                ->exists();

            if (!$exists) {
                UsageLimit::create([
                    'agency_profile_id'                   => $profile->id,
                    'period_start'                        => $periodStart,
                    'period_end'                          => $periodEnd,
                    'local_seo_pages_limit'               => 10,
                    'local_seo_pages_used'                => 0,
                    'competitor_scans_limit'              => 10,
                    'competitor_scans_used'               => 0,
                    'ai_search_freshness_updates_limit'   => 4,
                    'ai_search_freshness_updates_used'    => 0,
                    'authority_review_updates_limit'      => 1,
                    'authority_review_updates_used'       => 0,
                    'small_ai_content_actions_limit'      => 10,
                    'small_ai_content_actions_used'       => 0,
                ]);
                $created++;
            }
        }

        return redirect()->route('admin.villabit.usage-limits.index')
            ->with('success', "Created default limits for {$created} agencies.");
    }
}
