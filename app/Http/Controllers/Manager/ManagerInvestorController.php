<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerInvestorController extends Controller
{
    public function index()
    {
        $investors = User::where('role', 'investor')
            ->where('assigned_manager_id', Auth::id())
            ->with('investorProfile')
            ->latest()
            ->paginate(20);

        return view('manager.investors.index', compact('investors'));
    }
}
