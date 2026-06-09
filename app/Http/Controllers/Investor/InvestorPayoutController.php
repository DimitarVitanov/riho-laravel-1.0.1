<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestorPayout;
use Illuminate\Support\Facades\Auth;

class InvestorPayoutController extends Controller
{
    public function index()
    {
        $payouts = InvestorPayout::where('investor_user_id', Auth::id())
            ->with('project')
            ->latest()
            ->paginate(20);

        return view('investor.payouts.index', compact('payouts'));
    }
}
