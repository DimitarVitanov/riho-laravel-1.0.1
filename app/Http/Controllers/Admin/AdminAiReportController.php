<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiDailyReport;

class AdminAiReportController extends Controller
{
    public function index()
    {
        $reports = AiDailyReport::with('agencyProfile', 'agencyProfile.user')
            ->latest('report_date')
            ->paginate(30);

        return view('admin.villabit.ai-reports.index', compact('reports'));
    }

    public function show(AiDailyReport $report)
    {
        $report->load('agencyProfile', 'agencyProfile.user');
        return view('admin.villabit.ai-reports.show', compact('report'));
    }
}
