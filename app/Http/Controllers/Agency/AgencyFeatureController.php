<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureSetting;
use Illuminate\Support\Facades\Auth;

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
            $featureSetting = AiFeatureSetting::where('agency_profile_id', $profile->id)
                ->where('feature_key', $feature)
                ->first();

            $latestReport = $profile->aiDailyReports()
                ->where('feature_key', $feature)
                ->latest('report_date')
                ->first();
        }

        return view('agency.features.show', compact('feature', 'user', 'profile', 'featureSetting', 'latestReport'));
    }
}
