<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestorPayout;
use Illuminate\Http\Request;

class AdminInvestorPayoutController extends Controller
{
    public function index()
    {
        $payouts = InvestorPayout::with(['investorUser', 'project'])
            ->latest()
            ->paginate(20);

        return view('admin.villabit.investor-payouts.index', compact('payouts'));
    }

    public function approve(Request $request, InvestorPayout $investorPayout)
    {
        $investorPayout->update([
            'payout_status' => 'approved',
            'approved_by_admin_id' => auth()->id(),
        ]);

        return back()->with('success', 'Payout approved.');
    }

    public function markPaid(Request $request, InvestorPayout $investorPayout)
    {
        $request->validate([
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        $investorPayout->update([
            'payout_status' => 'paid',
            'paid_at' => now(),
            'transaction_reference' => $request->transaction_reference,
        ]);

        return back()->with('success', 'Payout marked as paid.');
    }
}
