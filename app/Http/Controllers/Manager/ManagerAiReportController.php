<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\AiDailyReport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerAiReportController extends Controller
{
    public function index()
    {
        $assignedAgencyIds = User::where('role', 'real_estate_agency')
            ->where('assigned_manager_id', Auth::id())
            ->pluck('id');

        $reports = AiDailyReport::whereHas('agencyProfile', function ($q) use ($assignedAgencyIds) {
            $q->whereIn('user_id', $assignedAgencyIds);
        })
            ->with('agencyProfile', 'agencyProfile.user')
            ->latest('report_date')
            ->paginate(30);

        return view('manager.ai-reports.index', compact('reports'));
    }
}
