<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminInvestorController extends Controller
{
    public function index()
    {
        $investors = User::where('role', 'investor')
            ->with('investorProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.investors.index', compact('investors'));
    }

    public function show(User $user)
    {
        $user->load('investorProfile', 'investorProfile.investments', 'investorProfile.investments.project');
        return view('admin.villabit.investors.show', compact('user'));
    }
}
