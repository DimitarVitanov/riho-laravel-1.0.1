<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $locale = auth()->user()->preferred_language ?? 'en';
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
