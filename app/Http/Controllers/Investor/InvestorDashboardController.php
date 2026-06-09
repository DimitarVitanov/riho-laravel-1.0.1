<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestorInvestment;
use App\Models\CapitalCall;
use App\Models\InvestorPayout;
use Illuminate\Support\Facades\Auth;

class InvestorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->investorProfile;

        $investments = collect();
        $pendingCalls = collect();
        $recentPayouts = collect();

        if ($profile) {
            $investments = InvestorInvestment::where('investor_user_id', $user->id)->get();
            $pendingCalls = CapitalCall::where('investor_user_id', $user->id)
                ->where('status', 'sent')
                ->get();
            $recentPayouts = InvestorPayout::where('investor_user_id', $user->id)
                ->latest()
                ->take(10)
                ->get();
        }

        $data = [
            'user' => $user,
            'profile' => $profile,
            'investments' => $investments,
            'pendingCalls' => $pendingCalls,
            'recentPayouts' => $recentPayouts,
            'totalCommitted' => $investments->sum('committed_amount'),
            'totalFunded' => $investments->sum('funded_amount'),
            'totalEarnings' => $investments->sum('total_earnings_accrued'),
            'totalPaid' => $investments->sum('total_payouts_paid'),
        ];

        return view('investor.dashboard', $data);
    }
}
