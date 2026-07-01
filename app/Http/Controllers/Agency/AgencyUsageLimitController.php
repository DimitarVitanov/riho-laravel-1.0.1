<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AgencyUsageLimitController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->getEffectiveAgencyProfile();
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
