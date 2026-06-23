<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\AffiliateReferral;
use Illuminate\Support\Facades\Auth;

class InvestorAffiliateController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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

        return view('investor.affiliate.index', compact(
            'user', 'referrals', 'totalClicks', 'totalSignups',
            'pendingCommissions', 'paidCommissions'
        ));
    }
}
