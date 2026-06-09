<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentProject;
use Illuminate\Http\Request;

class AdminInvestmentProjectController extends Controller
{
    public function index()
    {
        $projects = InvestmentProject::latest()->paginate(20);
        return view('admin.villabit.investment-projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.villabit.investment-projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required|string|max:255',
            'project_code' => 'required|string|max:50|unique:investment_projects,project_code',
            'project_location' => 'nullable|string|max:255',
            'project_country' => 'nullable|string|max:100',
            'project_type' => 'nullable|string|max:100',
            'project_status' => 'required|string',
            'minimum_investment_amount' => 'nullable|numeric|min:0',
            'target_raise_amount' => 'nullable|numeric|min:0',
            'max_raise_amount' => 'nullable|numeric|min:0',
            'preferred_return_percent' => 'nullable|numeric|min:0|max:100',
            'preferred_return_type' => 'nullable|string',
            'rental_profit_share_percent' => 'nullable|numeric|min:0|max:100',
            'project_exit_profit_share_percent' => 'nullable|numeric|min:0|max:100',
            'estimated_duration_months' => 'nullable|integer|min:1',
            'summary' => 'nullable|string',
            'full_description' => 'nullable|string',
            'risk_notes' => 'nullable|string',
        ]);

        InvestmentProject::create($validated);

        return redirect()->route('admin.villabit.investment-projects.index')
            ->with('success', 'Investment project created.');
    }

    public function show(InvestmentProject $investmentProject)
    {
        $investmentProject->load('investments', 'capitalCalls');
        return view('admin.villabit.investment-projects.show', compact('investmentProject'));
    }
}
