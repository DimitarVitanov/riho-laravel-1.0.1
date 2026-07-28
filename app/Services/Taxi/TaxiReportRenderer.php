<?php

namespace App\Services\Taxi;

use App\Models\TaxiCountryReport;

/**
 * Serves a stored report exactly as it was authored, changing only the internal
 * links (and canonical/hreflang) so the original files work under
 * https://realestate.taxi/globaldata/ and its localized variants.
 */
class TaxiReportRenderer
{
    public const SITE_URL = 'https://realestate.taxi';

    public function render(TaxiCountryReport $report, string $locale = 'en', array $locales = []): string
    {
        $html = $report->html_full;
        $base = self::basePath($locale);

        // Absolute links to the old location become taxi links first.
        $html = preg_replace('#https?://(?:www\.)?villabit\.ai/market-reports/#i', self::SITE_URL . '/market-reports/', $html);

        // The country index (/market-reports/countries/ and the bare folder) -> {base}/
        $html = str_replace('/market-reports/countries/', $base . '/', $html);

        // Country report links: /market-reports/{slug}/ -> {base}/{slug}/
        $html = preg_replace('#/market-reports/([a-z0-9\-]+)/#i', $base . '/$1/', $html);

        // Anything left (the bare folder) -> {base}/
        $html = str_replace('/market-reports/', $base . '/', $html);

        // Site root + contact stay on the taxi site, localized.
        $localeRoot = $locale === 'en' ? '/' : '/' . $locale . '/';
        $html = str_replace('href="/"', 'href="' . $localeRoot . '"', $html);
        $html = str_replace('href="/contact/"', 'href="' . $localeRoot . 'contact/"', $html);

        $html = $this->replaceCanonical($html, $report, $locale);
        $html = $this->injectHreflang($html, $report, $locales);
        $html = $this->setHtmlLang($html, $locale);

        return $html;
    }

    public static function basePath(string $locale = 'en'): string
    {
        return $locale === 'en' ? '/globaldata' : '/' . $locale . '/globaldata';
    }

    public static function publicUrl(TaxiCountryReport $report, string $locale = 'en'): string
    {
        $base = self::basePath($locale);

        return self::SITE_URL . ($report->isIndex() ? $base . '/' : $base . '/' . $report->country_slug . '/');
    }

    private function replaceCanonical(string $html, TaxiCountryReport $report, string $locale): string
    {
        $url = self::publicUrl($report, $locale);

        $html = preg_replace('#<link rel="canonical" href="[^"]*"\s*/?>#i', '<link rel="canonical" href="' . $url . '">', $html, 1);

        return preg_replace('#(<meta property="og:url" content=")[^"]*(")#i', '$1' . $url . '$2', $html, 1);
    }

    /**
     * @param  array<int, string>  $locales  locales that actually have this report
     */
    private function injectHreflang(string $html, TaxiCountryReport $report, array $locales): string
    {
        if (empty($locales)) {
            return $html;
        }

        $tags = '';
        foreach ($locales as $code) {
            $tags .= '<link rel="alternate" hreflang="' . e($code) . '" href="' . self::publicUrl($report, $code) . '">' . "\n";
        }
        $tags .= '<link rel="alternate" hreflang="x-default" href="' . self::publicUrl($report, 'en') . '">' . "\n";

        return preg_replace('#</head>#i', $tags . '</head>', $html, 1);
    }

    private function setHtmlLang(string $html, string $locale): string
    {
        return preg_replace('#(<html[^>]*\slang=")[^"]*(")#i', '$1' . $locale . '$2', $html, 1);
    }
}
