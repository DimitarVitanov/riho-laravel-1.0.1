<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectFor(Auth::user()->role);
        }

        return view('est8ads.auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:user,agency,admin'],
            'remember' => ['nullable', 'boolean'],
        ]);

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 'active'], $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => 'The supplied credentials are invalid or the account is not active.']);
        }

        $request->session()->regenerate();
        $user = Auth::user();
        $actualRole = $user->role;

        if (!$user->canAccessPlatform('est8ads')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages(['email' => 'This account does not have EST8ADS access.']);
        }

        if (!$this->roleMatches($credentials['role'], $actualRole)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages(['role' => 'This account does not have access to the selected workspace.']);
        }

        return $this->redirectFor($actualRole);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($request->routeIs('est8ads.dev.*') ? 'est8ads.local.home' : 'est8ads.home');
    }

    private function roleMatches(string $selectedRole, string $actualRole): bool
    {
        return match ($selectedRole) {
            'admin' => in_array($actualRole, ['super_admin', 'admin'], true),
            'agency' => $actualRole === 'real_estate_agency',
            'user' => in_array($actualRole, ['investor', 'manager'], true),
        };
    }

    private function redirectFor(string $role): RedirectResponse
    {
        $development = request()->routeIs('est8ads.dev.*');
        $admin = in_array($role, ['super_admin', 'admin'], true);

        return redirect()->route(match (true) {
            $development && $admin => 'est8ads.dev.admin.dashboard',
            $development => 'est8ads.dev.dashboard',
            $admin => 'est8ads.admin.dashboard',
            default => 'est8ads.dashboard',
        });
    }
}
