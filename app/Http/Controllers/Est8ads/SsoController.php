<?php

namespace App\Http\Controllers\Est8ads;

use App\Http\Controllers\Controller;
use App\Models\Est8ads\LoginToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    public function initiate(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user?->canAccessPlatform('est8ads'), 403, 'This account does not have EST8ADS access.');

        $rawToken = Str::random(64);
        LoginToken::create([
            'token_hash' => hash('sha256', $rawToken),
            'user_id' => $user->id,
            'expires_at' => now()->addMinute(),
            'requested_ip' => $request->ip(),
        ]);

        $route = $request->getHost() === '127.0.0.1' || $request->getHost() === 'localhost'
            ? 'est8ads.dev.sso.consume'
            : 'est8ads.sso.consume';

        return redirect()->route($route, ['token' => $rawToken]);
    }

    public function consume(Request $request, string $token): RedirectResponse
    {
        $user = DB::transaction(function () use ($token) {
            $loginToken = LoginToken::where('token_hash', hash('sha256', $token))
                ->lockForUpdate()
                ->first();

            if (!$loginToken || !$loginToken->isValid()) {
                return null;
            }

            $user = $loginToken->user;
            if (!$user || $user->status !== 'active' || !$user->canAccessPlatform('est8ads')) {
                return null;
            }

            $loginToken->update(['used_at' => now()]);

            return $user;
        });

        if (!$user) {
            return redirect()->route($request->routeIs('est8ads.dev.*') ? 'est8ads.dev.login' : 'est8ads.login')
                ->withErrors(['email' => 'This EST8ADS login link is invalid, expired, or already used.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($request->routeIs('est8ads.dev.*')
            ? ($user->isAdmin() ? 'est8ads.dev.admin.dashboard' : 'est8ads.dev.dashboard')
            : ($user->isAdmin() ? 'est8ads.admin.dashboard' : 'est8ads.dashboard'));
    }
}
