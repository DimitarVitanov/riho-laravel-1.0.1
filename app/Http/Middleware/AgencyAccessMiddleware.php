<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgencyAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        if (in_array($user->role, ['real_estate_agency', 'admin', 'super_admin'])) {
            return $next($request);
        }

        // Allow managers with view-only agency access
        if ($user->role === 'manager' && $user->managerProfile?->can_view_agency_readonly) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
