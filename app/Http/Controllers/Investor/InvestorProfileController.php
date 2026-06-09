<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->investorProfile;

        return view('investor.profile.show', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'citizenship_country' => 'nullable|string|max:100',
            'residence_country' => 'nullable|string|max:100',
            'preferred_currency' => 'nullable|string|max:10',
            'payout_method' => 'nullable|string|in:bank_wire,stripe,paypal,crypto,other',
        ]);

        $user = Auth::user();
        if ($user->investorProfile) {
            $user->investorProfile->update($validated);
        }

        return back()->with('success', 'Profile updated.');
    }
}
