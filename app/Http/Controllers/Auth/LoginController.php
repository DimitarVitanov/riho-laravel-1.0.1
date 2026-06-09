<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->status === 'suspended' || $user->status === 'archived') {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact support.');
        }

        $user->update(['last_login_at' => now()]);

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isManager()) {
            return redirect()->route('manager.dashboard');
        }

        if ($user->isAgency()) {
            return redirect()->route('agency.dashboard');
        }

        if ($user->isInvestor()) {
            return redirect()->route('investor.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
