<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfile;
use App\Models\Est8ads\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('est8ads.auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_type' => ['required', 'in:individual,agency'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'company_name' => ['nullable', 'required_if:account_type,agency', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', 'min:8'],
            'terms' => ['accepted'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            User::onlyTrashed()->where('email', $validated['email'])->forceDelete();
            $agency = $validated['account_type'] === 'agency';

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'company_name' => $validated['company_name'] ?? null,
                'country' => $validated['country'],
                'account_type' => $agency ? 'real_estate_agency' : 'investor',
                'role' => $agency ? 'real_estate_agency' : 'investor',
                'status' => 'active',
                'has_villabit_access' => false,
                'has_est8ads_access' => true,
                'privacy_accepted_at' => now(),
                'terms_accepted_at' => now(),
                'referral_code' => strtoupper(Str::random(8)),
            ]);

            if ($agency) {
                AgencyProfile::create([
                    'user_id' => $user->id,
                    'agency_name' => $validated['company_name'],
                    'country' => $validated['country'],
                    'contact_email' => $user->email,
                    'contact_phone' => $validated['phone'] ?? null,
                ]);
            } else {
                Profile::create([
                    'user_id' => $user->id,
                    'type' => 'individual',
                    'status' => 'active',
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'public_reference' => 'EST-' . strtoupper(Str::random(12)),
                    'consent_at' => now(),
                    'metadata' => ['source' => 'est8ads_registration'],
                ]);
            }

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $user->sendEmailVerificationNotification();
        $request->session()->flash('just_registered', true);

        return redirect()->route('verification.notice');
    }
}
