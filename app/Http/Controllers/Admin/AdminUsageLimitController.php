<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
