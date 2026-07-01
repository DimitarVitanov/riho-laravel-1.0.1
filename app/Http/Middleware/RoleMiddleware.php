<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        foreach ($roles as $role) {
            if ($role === 'admin' && $user->isAdmin()) {
                return $next($request);
            }
            if ($user->role === $role) {
                return $next($request);
            }
            // Allow view-only managers to access agency routes
            if ($role === 'real_estate_agency' && $user->role === 'manager' && $user->managerProfile?->can_view_agency_readonly) {
                return $next($request);
            }
        }

        if ($user->isOnWaitlist()) {
            return redirect()->route('dashboard');
        }

        abort(403, 'Unauthorized access.');
    }
}
