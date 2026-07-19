<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\VillaReadyPropertyReferral;
use App\Models\VillaReadyAgencyPublication;
use Illuminate\Support\Facades\Auth;

class AgencyVillaReadyController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        if (!$profile) {
            return redirect()->route('agency.dashboard')->with('error', 'Agency profile not found.');
        }

        // Get all properties published for this agency
        $publications = VillaReadyAgencyPublication::where('agency_profile_id', $profile->id)
            ->where('is_published', true)
            ->with('property')
            ->get();

        // Get all referrals for this agency
        $referrals = VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)
            ->with('property')
            ->latest()
            ->paginate(20);

        // Stats
        $stats = [
            'total_visits' => VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)->count(),
            'viewed' => VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)
                ->where('status', 'viewed')->count(),
            'paid_sales' => VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)
                ->where('status', 'paid')->count(),
            'pending_commission' => VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)
                ->whereIn('status', ['visited', 'viewed'])
                ->count(),
            'total_commission' => VillaReadyPropertyReferral::where('agency_profile_id', $profile->id)
                ->where('status', 'paid')
                ->sum('commission_amount'),
        ];

        return view('agency.villa-ready.index', compact('publications', 'referrals', 'stats'));
    }
}
