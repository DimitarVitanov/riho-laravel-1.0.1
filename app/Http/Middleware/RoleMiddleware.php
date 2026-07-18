<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Routes that agencies can access during onboarding (step 4+)
    protected array $onboardingAllowedRoutes = [
        'agency.settings.domain',
        'agency.settings.domain.update',
        'agency.support.index',
        'agency.support.create',
        'agency.support.store',
        'agency.support.show',
    ];

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
                // Check if agency is on waitlist but at step 4+ (domain connection)
                if ($user->isAgency() && $user->isOnWaitlist()) {
                    if ($user->onboarding_step >= User::ONBOARDING_DOMAIN_CONNECTION) {
                        // Allow access to domain settings and support routes
                        $currentRoute = $request->route()?->getName();
                        if (in_array($currentRoute, $this->onboardingAllowedRoutes)) {
                            return $next($request);
                        }
                    }
                    // Still on waitlist, redirect to dashboard
                    return redirect()->route('dashboard');
                }
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
