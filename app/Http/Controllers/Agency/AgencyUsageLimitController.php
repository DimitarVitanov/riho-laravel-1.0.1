<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgencyUsageLimitController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->agencyProfile;
        $currentUsage = null;

        if ($profile) {
            $currentUsage = $profile->usageLimits()
                ->where('period_start', '<=', now())
                ->where('period_end', '>=', now())
                ->first();
        }

        return view('agency.usage-limits.index', compact('user', 'profile', 'currentUsage'));
    }
}
