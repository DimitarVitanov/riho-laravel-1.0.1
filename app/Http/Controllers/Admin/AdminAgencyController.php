<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgencyProfile;

class AdminAgencyController extends Controller
{
    public function index()
    {
        $agencies = User::where('role', 'real_estate_agency')
            ->with('agencyProfile')
            ->latest()
            ->paginate(20);

        return view('admin.villabit.agencies.index', compact('agencies'));
    }

    public function show(User $user)
    {
        $user->load('agencyProfile', 'agencyProfile.aiFeatureSettings', 'agencyProfile.usageLimits');
        return view('admin.villabit.agencies.show', compact('user'));
    }
}
