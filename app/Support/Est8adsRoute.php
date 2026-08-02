<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

/**
 * EST8ADS routes are registered three times: on the est8ads.com domain
 * (est8ads.*), behind the local /est8ads prefix (est8ads.local.*) and behind
 * the /est8ads-app prefix (est8ads.dev.*).
 *
 * Views must link inside the group they were rendered from, otherwise a page
 * served from localhost points its forms at the live est8ads.com host and the
 * request times out.
 */
class Est8adsRoute
{
    public static function prefix(): string
    {
        return match (true) {
            Route::is('est8ads.local.*') => 'est8ads.local.',
            Route::is('est8ads.dev.*') => 'est8ads.dev.',
            default => 'est8ads.',
        };
    }

    public static function name(string $name): string
    {
        foreach ([self::prefix(), 'est8ads.local.', 'est8ads.'] as $prefix) {
            if (Route::has($prefix . $name)) {
                return $prefix . $name;
            }
        }

        return 'est8ads.' . $name;
    }

    public static function to(string $name, array $parameters = [], bool $absolute = true): string
    {
        return route(self::name($name), $parameters, $absolute);
    }
}
