<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isOnWaitlist()) {
            return view('dashboards.waitlist', compact('user'));
        }

        if ($user->isAgency()) {
            return view('dashboards.agency_dashboard', compact('user'));
        }

        if ($user->isInvestor()) {
            return view('dashboards.investor_dashboard', compact('user'));
        }

        return view('dashboards.default_dashboard', compact('user'));
    }
}
