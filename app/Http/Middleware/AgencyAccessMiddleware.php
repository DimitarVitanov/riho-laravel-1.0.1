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

        if (!$user || !in_array($user->role, ['real_estate_agency', 'admin', 'super_admin'])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
