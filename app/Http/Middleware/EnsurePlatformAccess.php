<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformAccess
{
    public function handle(Request $request, Closure $next, string $platform): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route($platform === 'est8ads' ? 'est8ads.login' : 'login');
        }

        abort_unless($user->canAccessPlatform($platform), 403, 'This account does not have access to this platform.');

        return $next($request);
    }
}
