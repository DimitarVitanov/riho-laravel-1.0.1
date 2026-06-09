<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use App\Models\InvestmentProject;
use App\Models\AffiliateCommission;

class VillaBitDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_agencies' => User::where('role', 'real_estate_agency')->count(),
            'total_investors' => User::where('role', 'investor')->count(),
            'total_managers' => User::where('role', 'manager')->count(),
            'total_users' => User::count(),
            'waitlist_count' => User::where('status', 'waitlist')->count(),
            'active_count' => User::where('status', 'active')->count(),
            'suspended_count' => User::where('status', 'suspended')->count(),
            'total_projects' => InvestmentProject::count(),
            'recent_users' => User::latest()->take(10)->get(),
        ];

        return view('admin.villabit.dashboard', $data);
    }
}
