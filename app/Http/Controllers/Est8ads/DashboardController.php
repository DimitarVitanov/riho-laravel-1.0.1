<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\Profile;
use App\Services\Est8ads\BillingService;
use App\Services\Est8ads\PanelData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, PanelData $panelData, BillingService $billing): View
    {
        $profile = Profile::where('user_id', $request->user()->id)->first();

        // Payment more than the grace period overdue: hide the whole
        // workspace behind a "please pay" screen instead of the dashboard.
        if ($profile && $billing->isSuspended($profile)) {
            return view('est8ads.user.suspended', [
                'subscription' => $billing->subscriptionSummary($profile),
            ]);
        }

        return view('est8ads.user.dashboard', [
            'est8adsData' => $panelData->forUser($request->user()),
        ]);
    }
}
