<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Agency\AgencySettingsController;
use App\Models\TaxiPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RealEstateTaxiController extends Controller
{
    public function home(Request $request)
    {
        $languages = AgencySettingsController::supportedPanelLanguages();
        $currencies = self::supportedCurrencies();

        // Auto-detect language + currency from the visitor's IP (cached per IP).
        $geo = $this->detectGeo($request);

        // Language: explicit choice (query) > saved cookie > IP detection > English.
        $locale = $request->query('lang')
            ?: $request->cookie('taxi_lang')
            ?: ($geo['language'] ?? null)
            ?: 'en';
        if (!array_key_exists($locale, $languages)) {
            $locale = 'en';
        }

        // Currency: explicit choice (query) > saved cookie > IP detection > USD.
        $currency = $request->query('currency')
            ?: $request->cookie('taxi_currency')
            ?: ($geo['currency'] ?? null)
            ?: 'USD';
        if (!array_key_exists($currency, $currencies)) {
            $currency = 'USD';
        }

        $page = TaxiPage::forLocale($locale) ?? TaxiPage::forLocale('en');

        $response = response()->view('realestate-taxi.home', compact(
            'page', 'locale', 'currency', 'languages', 'currencies'
        ));

        // Persist manual choices for a year so we don't override the visitor next time.
        if ($request->query('lang')) {
            $response->cookie('taxi_lang', $locale, 60 * 24 * 365);
        }
        if ($request->query('currency')) {
            $response->cookie('taxi_currency', $currency, 60 * 24 * 365);
        }

        return $response;
    }

    /**
     * Resolve the visitor's language + currency from their IP address.
     * Uses the free ip-api.com service, cached per IP for a day.
     */
    private function detectGeo(Request $request): array
    {
        $ip = $request->ip();

        if (in_array($ip, ['127.0.0.1', '::1'], true) || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return [];
        }

        return Cache::remember("taxi_geo_{$ip}", now()->addDay(), function () use ($ip) {
            try {
                $resp = Http::timeout(2)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,countryCode,currency',
                ]);

                if ($resp->ok() && $resp->json('status') === 'success') {
                    return [
                        'language' => self::countryToLanguage($resp->json('countryCode')),
                        'currency' => $resp->json('currency'),
                    ];
                }
            } catch (\Throwable $e) {
                // Network/geo failure — silently fall back to defaults.
            }

            return [];
        });
    }

    /**
     * Map an ISO country code to one of our supported language codes.
     */
    private static function countryToLanguage(?string $countryCode): ?string
    {
        $map = [
            'HR' => 'hr', 'DE' => 'de', 'AT' => 'de', 'CH' => 'de',
            'FR' => 'fr', 'BE' => 'fr', 'ES' => 'es', 'MX' => 'es', 'AR' => 'es',
            'IT' => 'it', 'PT' => 'pt', 'BR' => 'pt', 'NL' => 'nl',
            'SE' => 'sv', 'DK' => 'da', 'NO' => 'no', 'FI' => 'fi',
            'PL' => 'pl', 'CZ' => 'cs', 'SK' => 'sk', 'HU' => 'hu',
            'RO' => 'ro', 'BG' => 'bg', 'GR' => 'el', 'TR' => 'tr',
            'SA' => 'ar', 'AE' => 'ar', 'EG' => 'ar', 'JP' => 'ja',
            'CN' => 'zh', 'TW' => 'zh', 'KR' => 'ko', 'RU' => 'ru',
            'UA' => 'uk', 'SI' => 'sl', 'RS' => 'sr', 'BA' => 'bs',
            'MK' => 'mk', 'AL' => 'sq',
        ];

        return $map[strtoupper((string) $countryCode)] ?? 'en';
    }

    /**
     * Supported display currencies: code => symbol/label.
     */
    public static function supportedCurrencies(): array
    {
        return [
            'USD' => 'US Dollar $',
            'EUR' => 'Euro €',
            'GBP' => 'British Pound £',
            'CHF' => 'Swiss Franc',
            'HRK' => 'Croatian Euro €',
            'JPY' => 'Japanese Yen ¥',
            'CNY' => 'Chinese Yuan ¥',
            'AUD' => 'Australian Dollar $',
            'CAD' => 'Canadian Dollar $',
            'SEK' => 'Swedish Krona',
            'NOK' => 'Norwegian Krone',
            'DKK' => 'Danish Krone',
            'PLN' => 'Polish Zloty',
            'CZK' => 'Czech Koruna',
            'HUF' => 'Hungarian Forint',
            'RON' => 'Romanian Leu',
            'BGN' => 'Bulgarian Lev',
            'TRY' => 'Turkish Lira ₺',
            'RUB' => 'Russian Ruble ₽',
            'AED' => 'UAE Dirham',
        ];
    }
}
