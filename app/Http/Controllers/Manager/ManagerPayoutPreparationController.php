<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\InvestorPayout;
use App\Models\AffiliatePayout;
use Illuminate\Support\Facades\Auth;

class ManagerPayoutPreparationController extends Controller
{
    public function index()
    {
        $manager = Auth::user();
        $assignedInvestorIds = \App\Models\User::where('assigned_manager_id', $manager->id)
            ->where('role', 'investor')
            ->pluck('id');

        $investorPayouts = InvestorPayout::whereIn('investor_user_id', $assignedInvestorIds)
            ->where('payout_status', 'pending')
            ->with('investorUser')
            ->latest()
            ->get();

        $affiliatePayouts = AffiliatePayout::where('payout_status', 'requested')
            ->with('resellerUser')
            ->latest()
            ->get();

        return view('manager.payout-preparation.index', compact('investorPayouts', 'affiliatePayouts'));
    }
}
