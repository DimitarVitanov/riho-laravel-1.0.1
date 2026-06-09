<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestorInvestment;
use Illuminate\Support\Facades\Auth;

class InvestorEarningsController extends Controller
{
    public function index()
    {
        $investments = InvestorInvestment::where('investor_user_id', Auth::id())
            ->with('project')
            ->get();

        $totalPreferredReturn = $investments->sum('preferred_return_accrued_amount');
        $totalRentalProfit = $investments->sum('rental_profit_accrued_amount');
        $totalExitProfit = $investments->sum('project_exit_profit_accrued_amount');
        $totalEarnings = $investments->sum('total_earnings_accrued');

        return view('investor.earnings.index', compact(
            'investments', 'totalPreferredReturn', 'totalRentalProfit',
            'totalExitProfit', 'totalEarnings'
        ));
    }
}
