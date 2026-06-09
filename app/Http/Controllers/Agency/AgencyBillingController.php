<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgencyBillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;

        return view('agency.billing.index', compact('user', 'profile'));
    }
}
