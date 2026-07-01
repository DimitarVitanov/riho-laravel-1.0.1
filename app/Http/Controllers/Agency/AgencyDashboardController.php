<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\UsageLimit;
use App\Models\AiDailyReport;
use App\Models\AiFeatureSetting;
use Illuminate\Support\Facades\Auth;

class AgencyDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();

        $currentUsage = null;
        $aiFeatures = collect();
        $recentReports = collect();

        if ($profile) {
            $currentUsage = UsageLimit::where('agency_profile_id', $profile->id)
                ->where('period_start', '<=', now())
                ->where('period_end', '>=', now())
                ->first();

            $aiFeatures = AiFeatureSetting::where('agency_profile_id', $profile->id)->get();
            $recentReports = AiDailyReport::where('agency_profile_id', $profile->id)
                ->latest('report_date')
                ->take(7)
                ->get();
        }

        $data = [
            'user' => $user,
            'profile' => $profile,
            'currentUsage' => $currentUsage,
            'aiFeatures' => $aiFeatures,
            'recentReports' => $recentReports,
        ];

        return view('agency.dashboard', $data);
    }
}
