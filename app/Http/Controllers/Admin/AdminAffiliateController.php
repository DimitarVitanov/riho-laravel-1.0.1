<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateReferral;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use Illuminate\Http\Request;

class AdminAffiliateController extends Controller
{
    public function tracking()
    {
        $referrals = AffiliateReferral::with('resellerUser', 'convertedUser')
            ->latest()
            ->paginate(30);

        return view('admin.villabit.affiliate.tracking', compact('referrals'));
    }

    public function commissions()
    {
        $commissions = AffiliateCommission::with('resellerUser', 'referredUser')
            ->latest('earned_at')
            ->paginate(30);

        return view('admin.villabit.affiliate.commissions', compact('commissions'));
    }

    public function payouts()
    {
        $payouts = AffiliatePayout::with('resellerUser')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.affiliate.payouts', compact('payouts'));
    }

    public function approvePayout(AffiliatePayout $payout)
    {
        $payout->update(['payout_status' => 'approved']);
        return back()->with('success', 'Affiliate payout approved.');
    }

    public function markPayoutPaid(Request $request, AffiliatePayout $payout)
    {
        $request->validate([
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        $payout->update([
            'payout_status' => 'paid',
            'paid_at' => now(),
            'transaction_reference' => $request->transaction_reference,
        ]);

        return back()->with('success', 'Affiliate payout marked as paid.');
    }
}
