<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgencyProfile;
use App\Models\InvestorProfile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Models\AffiliateReferral;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $countries = DB::table('countries')->orderBy('name')->get(['name', 'calling_code', 'flag']);
        return view('auth.register', compact('countries'));
    }

    protected function validator(array $data)
    {
        $rules = [
            'account_type' => ['required', 'in:real_estate_agency,investor'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_acceptance' => ['required', 'accepted'],
        ];

        if ($data['account_type'] === 'real_estate_agency') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['agency_website_url'] = ['nullable', 'url', 'max:255'];
            $rules['target_city'] = ['nullable', 'string', 'max:255'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'company_name' => $data['company_name'] ?? null,
            'country' => $data['country'],
            'account_type' => $data['account_type'],
            'role' => $data['account_type'],
            'status' => 'waitlist',
        ]);

        if ($data['account_type'] === 'real_estate_agency') {
            AgencyProfile::create([
                'user_id' => $user->id,
                'agency_name' => $data['company_name'] ?? null,
                'official_website_url' => $data['agency_website_url'] ?? null,
                'target_city' => $data['target_city'] ?? null,
                'country' => $data['country'],
                'contact_email' => $data['email'],
                'contact_phone' => $data['phone'] ?? null,
            ]);
        } elseif ($data['account_type'] === 'investor') {
            InvestorProfile::create([
                'user_id' => $user->id,
                'citizenship_country' => $data['country'],
                'residence_country' => $data['country'],
            ]);
        }

        if (!empty($data['referral_code'])) {
            $referral = AffiliateReferral::where('referral_code', $data['referral_code'])
                ->whereNull('converted_user_id')
                ->latest()
                ->first();
            if ($referral) {
                $referral->markConverted($user);
            }
        }

        return $user;
    }
}
