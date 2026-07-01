<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Only restrict view-only managers
        if ($user && $user->role === 'manager' && $user->managerProfile?->can_view_agency_readonly) {
            // Block all write methods
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You cannot submit any data because you have view-only access.',
                    ], 403);
                }

                return back()->with('error', 'You cannot submit any data because you have view-only access.');
            }
        }

        return $next($request);
    }
}
