<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgencyAiReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        $reports = collect();

        if ($profile) {
            $reports = $profile->aiDailyReports()
                ->latest('report_date')
                ->paginate(20);
        }

        return view('agency.ai-reports.index', compact('user', 'reports'));
    }
}
