<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use App\Models\AiDailyReport;
use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->managerProfile;

        $data = [
            'user' => $user,
            'profile' => $profile,
            'assigned_agencies' => AgencyProfile::where('assigned_manager_id', $user->id)->count(),
            'assigned_investors' => InvestorProfile::where('assigned_manager_id', $user->id)->count(),
            'recent_reports' => AiDailyReport::latest()->take(10)->get(),
        ];

        return view('manager.dashboard', $data);
    }
}
