<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestorInvestment;
use Illuminate\Support\Facades\Auth;

class InvestorInvestmentController extends Controller
{
    public function index()
    {
        $investments = InvestorInvestment::where('investor_user_id', Auth::id())
            ->with('project')
            ->latest()
            ->paginate(20);

        return view('investor.investments.index', compact('investments'));
    }
}
