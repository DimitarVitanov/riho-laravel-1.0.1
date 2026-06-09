<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\CapitalCall;
use Illuminate\Support\Facades\Auth;

class ManagerCapitalCallController extends Controller
{
    public function index()
    {
        $manager = Auth::user();
        $assignedInvestorIds = \App\Models\User::where('assigned_manager_id', $manager->id)
            ->where('role', 'investor')
            ->pluck('id');

        $capitalCalls = CapitalCall::whereIn('investor_user_id', $assignedInvestorIds)
            ->with(['investorUser', 'project'])
            ->latest()
            ->paginate(20);

        return view('manager.capital-calls.index', compact('capitalCalls'));
    }
}
