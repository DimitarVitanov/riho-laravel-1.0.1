<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;
use Illuminate\Support\Facades\Auth;

class AgencyAffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->is_reseller_enabled) {
            return view('agency.affiliate.disabled');
        }

        $referrals = AffiliateReferral::where('reseller_user_id', $user->id)
            ->latest()
            ->paginate(20);

        $totalClicks = AffiliateReferral::where('reseller_user_id', $user->id)->count();
        $totalSignups = AffiliateReferral::where('reseller_user_id', $user->id)
            ->whereNotNull('converted_user_id')
            ->count();

        $pendingCommissions = AffiliateCommission::where('reseller_user_id', $user->id)
            ->where('commission_status', 'pending')
            ->sum('commission_amount');

        $paidCommissions = AffiliateCommission::where('reseller_user_id', $user->id)
            ->where('commission_status', 'paid')
            ->sum('commission_amount');

        return view('agency.affiliate.index', compact(
            'user', 'referrals', 'totalClicks', 'totalSignups',
            'pendingCommissions', 'paidCommissions'
        ));
    }
}
