<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\InvestmentProject;
use Illuminate\Support\Facades\Auth;

class InvestorProjectController extends Controller
{
    public function index()
    {
        $projects = InvestmentProject::where('project_status', '!=', 'draft')
            ->latest()
            ->paginate(20);

        return view('investor.projects.index', compact('projects'));
    }

    public function show(InvestmentProject $project)
    {
        return view('investor.projects.show', compact('project'));
    }
}
