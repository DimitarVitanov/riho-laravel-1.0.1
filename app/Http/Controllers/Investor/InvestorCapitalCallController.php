<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\CapitalCall;
use Illuminate\Support\Facades\Auth;

class InvestorCapitalCallController extends Controller
{
    public function index()
    {
        $capitalCalls = CapitalCall::where('investor_user_id', Auth::id())
            ->with('project')
            ->latest()
            ->paginate(20);

        return view('investor.capital-calls.index', compact('capitalCalls'));
    }
}
