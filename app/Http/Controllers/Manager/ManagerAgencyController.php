<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ManagerAgencyController extends Controller
{
    public function index()
    {
        $agencies = User::where('role', 'real_estate_agency')
            ->where('assigned_manager_id', Auth::id())
            ->with('agencyProfile')
            ->latest()
            ->paginate(20);

        return view('manager.agencies.index', compact('agencies'));
    }
}
