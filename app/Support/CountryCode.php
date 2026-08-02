<?php

namespace App\Support;

/**
 * Country names and ISO codes used interchangeably across the app.
 *
 * An agency types "Croatia" on a listing while a scraped page exposes "HR",
 * so matching has to compare them on equal terms.
 */
class CountryCode
{
    private const NAMES = [
        'croatia' => 'HR', 'hrvatska' => 'HR',
        'slovenia' => 'SI', 'slovenija' => 'SI',
        'bosnia and herzegovina' => 'BA', 'bosnia' => 'BA',
        'serbia' => 'RS', 'srbija' => 'RS',
        'montenegro' => 'ME', 'crna gora' => 'ME',
        'italy' => 'IT', 'italia' => 'IT',
        'austria' => 'AT', 'österreich' => 'AT', 'osterreich' => 'AT',
        'germany' => 'DE', 'deutschland' => 'DE',
        'hungary' => 'HU', 'magyarország' => 'HU',
        'greece' => 'GR', 'spain' => 'ES', 'españa' => 'ES',
        'portugal' => 'PT', 'france' => 'FR', 'netherlands' => 'NL',
        'belgium' => 'BE', 'poland' => 'PL', 'polska' => 'PL',
        'czechia' => 'CZ', 'czech republic' => 'CZ', 'slovakia' => 'SK',
        'united kingdom' => 'GB', 'england' => 'GB', 'ireland' => 'IE',
        'switzerland' => 'CH', 'sweden' => 'SE', 'norway' => 'NO',
        'denmark' => 'DK', 'finland' => 'FI', 'north macedonia' => 'MK',
        'macedonia' => 'MK', 'albania' => 'AL', 'bulgaria' => 'BG',
        'romania' => 'RO', 'turkey' => 'TR', 'cyprus' => 'CY', 'malta' => 'MT',
        'united states' => 'US', 'usa' => 'US', 'canada' => 'CA',
    ];

    /**
     * Convert a country name or code to its ISO 3166-1 alpha-2 code.
     * Returns null when the value cannot be recognised, so callers never
     * store a guessed code such as "CR" for Croatia.
     */
    public static function normalize(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $lower = mb_strtolower($value);

        if (isset(self::NAMES[$lower])) {
            return self::NAMES[$lower];
        }

        if (preg_match('/^[a-z]{2}$/i', $value)) {
            return strtoupper($value);
        }

        return null;
    }

    /**
     * True when two country values refer to the same country, whichever
     * notation each of them uses.
     */
    public static function matches(?string $a, ?string $b): bool
    {
        $left = self::normalize($a);
        $right = self::normalize($b);

        if ($left === null || $right === null) {
            return mb_strtolower(trim((string) $a)) === mb_strtolower(trim((string) $b));
        }

        return $left === $right;
    }
}
