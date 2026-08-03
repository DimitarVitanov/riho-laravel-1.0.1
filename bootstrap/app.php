<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'agency' => \App\Http\Middleware\AgencyAccessMiddleware::class,
            'view_only' => \App\Http\Middleware\ViewOnlyMiddleware::class,
            'platform' => \App\Http\Middleware\EnsurePlatformAccess::class,
        ]);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\AffiliateClickMiddleware::class,
            \App\Http\Middleware\SetUserLanguage::class,
        ]);

        // Laravel's built-in `auth` middleware always runs before any custom
        // middleware (its position in the global priority list is fixed), so
        // EnsurePlatformAccess's own "redirect to est8ads.login" never gets a
        // chance to run for guests on EST8ADS routes — `auth` already threw
        // and redirected to the main site's login by then. Route the guest
        // redirect itself based on the request path instead.
        $middleware->redirectGuestsTo(fn ($request) => $request->routeIs('est8ads.*')
            ? route(\App\Support\Est8adsRoute::name('login'))
            : route('login'));

        // Mirror the above for the reverse case: an already-authenticated
        // user hitting a `guest`-only route (e.g. /est8ads/login) is caught
        // by RedirectIfAuthenticated before Est8adsAuthController::create()
        // ever runs. Its default target is the first route literally named
        // "dashboard", which is the main site's dashboard — not EST8ADS's —
        // so admins landed on /admin/villabit/dashboard instead of
        // /est8ads/admin. Send them to the EST8ADS-specific destination when
        // the request came from an EST8ADS route.
        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(
            fn ($request) => $request->routeIs('est8ads.*')
                ? route(\App\Support\Est8adsRoute::name(
                    optional(auth()->user())->isAdmin() ? 'admin.dashboard' : 'dashboard'
                ))
                : route('dashboard')
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
