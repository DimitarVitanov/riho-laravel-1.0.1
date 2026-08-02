<?php

namespace App\Services\Est8ads\Discovery;

use App\Services\CompetitorIntelligence\PropertyExtractorService;
use App\Support\CountryCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Fetches a single listing page and turns it into a structured discovery
 * record, reusing the competitor-intelligence extraction cascade (JSON-LD,
 * DOM selectors, heuristics).
 *
 * Every fetch goes through SafeUrlPolicy first, so private networks and
 * unapproved hosts can never be requested.
 */
class ListingPageScraper
{
    private const USER_AGENT = 'Mozilla/5.0 (compatible; VillaBitBot/1.0; +https://villabit.ai/bot)';

    public function __construct(
        private PropertyExtractorService $extractor,
        private SafeUrlPolicy $urlPolicy,
    ) {
    }

    /**
     * @return array<string, mixed>|null A record shaped for ListingNormalizer.
     */
    public function scrape(string $url, ?string $expectedHost = null, array $hints = []): ?array
    {
        try {
            $this->urlPolicy->assertAllowed($url, $expectedHost);
        } catch (Throwable $exception) {
            Log::info('Discovery skipped a disallowed URL', ['url' => $url, 'reason' => $exception->getMessage()]);

            return null;
        }

        try {
            $response = Http::timeout(25)
                ->withHeaders([
                    'User-Agent' => self::USER_AGENT,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();
            $data = $this->extractor->extractFromHtml($html, $url) ?? [];

            // The competitor extractor is tuned for known layouts. On the open
            // internet most pages need a generic pass as well, so anything it
            // could not find is filled in from meta tags and page text.
            $data = $this->enrich($data, $html, $hints);
        } catch (Throwable $exception) {
            Log::info('Discovery scrape failed', ['url' => $url, 'error' => $exception->getMessage()]);

            return null;
        }

        if ($data === []) {
            return null;
        }

        return $this->toRecord($url, $data);
    }

    /**
     * Fill missing fields from OpenGraph tags and the visible page text.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function enrich(array $data, string $html, array $hints = []): array
    {
        $text = $this->readableText($html);
        $haystack = mb_strtolower($text . ' ' . ($data['title'] ?? ''));

        if (empty($data['title'])) {
            $data['title'] = $this->meta($html, 'og:title') ?? $this->tag($html, 'title');
        }

        if (empty($data['description'])) {
            $data['description'] = $this->meta($html, 'og:description') ?? $this->meta($html, 'description');
        }

        // Structured markup is authoritative. The heuristic extractors can glue
        // adjacent figures together, so they are only a fallback.
        $structuredPrice = $this->structuredPrice($html);

        if ($structuredPrice !== null) {
            $data['price'] = $structuredPrice;
        } elseif (empty($data['price'])) {
            $data['price'] = $this->parsePrice($text);
        }

        if (empty($data['surface_area']) && empty($data['size_m2']) && empty($data['living_area'])) {
            $data['size_m2'] = $this->parseArea($text);
        }

        if (empty($data['bedrooms'])) {
            $data['bedrooms'] = $this->parseBedrooms($text);
        }

        if (empty($data['city'])) {
            $data['city'] = $this->structured($html, 'addressLocality') ?? $this->matchHint($haystack, $hints['cities'] ?? []);
        }

        if (empty($data['country_code'])) {
            $country = $this->structured($html, 'addressCountry') ?? $this->matchHint($haystack, $hints['countries'] ?? []);
            $data['country_code'] = $country ? CountryCode::normalize($country) : null;
        }

        if (empty($data['property_type'])) {
            $data['property_type'] = $this->matchHint($haystack, $hints['property_types'] ?? []) ?? $this->guessPropertyType($haystack);
        }

        return array_filter($data, fn ($value) => $value !== null && $value !== '');
    }

    /**
     * Confirm a value from the search context actually appears on the page.
     * This verifies rather than assumes: if the page never mentions the city,
     * nothing is filled in.
     *
     * @param  array<int, string>  $candidates
     */
    private function matchHint(string $haystack, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            $needle = mb_strtolower(trim((string) $candidate));

            if ($needle !== '' && str_contains($haystack, $needle)) {
                return (string) $candidate;
            }
        }

        return null;
    }

    private function guessPropertyType(string $haystack): ?string
    {
        $types = [
            'apartment' => ['apartment', 'apartman', 'stan ', 'flat', 'wohnung'],
            'house' => ['house', 'kuća', 'kuca', 'haus'],
            'villa' => ['villa', 'vila'],
            'land' => ['land plot', 'building land', 'zemljište', 'zemljiste'],
            'office' => ['office space', 'poslovni prostor'],
        ];

        foreach ($types as $type => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, $needle)) {
                    return $type;
                }
            }
        }

        return null;
    }

    /**
     * Read a schema.org property out of JSON-LD or microdata.
     */
    private function structured(string $html, string $property): ?string
    {
        $patterns = [
            '/"' . preg_quote($property, '/') . '"\s*:\s*"([^"]{2,80})"/i',
            '/itemprop=["\']' . preg_quote($property, '/') . '["\'][^>]*content=["\']([^"\']{2,80})["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                return trim($match[1]);
            }
        }

        return null;
    }

    /**
     * Machine-readable price, which is far more reliable than page text.
     * Covers JSON-LD offers, OpenGraph product tags and schema.org microdata.
     */
    private function structuredPrice(string $html): ?float
    {
        $patterns = [
            '/"price"\s*:\s*"?([0-9][0-9.,]*)"?/i',
            '/<meta[^>]+property=["\']product:price:amount["\'][^>]+content=["\']([^"\']+)["\']/i',
            '/itemprop=["\']price["\'][^>]*content=["\']([^"\']+)["\']/i',
        ];

        foreach ($patterns as $pattern) {
            if (! preg_match_all($pattern, $html, $matches)) {
                continue;
            }

            foreach ($matches[1] as $raw) {
                $value = $this->toNumber($raw);

                if ($value !== null && $value >= 10000 && $value <= 100000000) {
                    return $value;
                }
            }
        }

        return null;
    }

    /**
     * Text fallback for pages without structured markup.
     *
     * Pages repeat unrelated amounts (banners, "from" teasers, other listings),
     * so the most frequently repeated plausible amount is used: on a detail page
     * the asking price is normally shown several times, while noise is not.
     */
    private function parsePrice(string $text): ?float
    {
        // Thousand groups must be exactly three digits. Without this a price
        // followed by another figure ("€390,000 2.60") is read as one number.
        $number = '(?:\d{1,3}(?:[.\s\x{a0}]\d{3})+(?:,\d{1,2})?|\d{1,3}(?:,\d{3})+(?:\.\d{1,2})?|\d{4,9}(?:[.,]\d{1,2})?)';

        $patterns = [
            '/(?:€|EUR)\s*(' . $number . ')/iu',
            '/(' . $number . ')\s*(?:€|EUR)/iu',
        ];

        $counts = [];

        foreach ($patterns as $pattern) {
            if (! preg_match_all($pattern, $text, $matches)) {
                continue;
            }

            foreach ($matches[1] as $raw) {
                $value = $this->toNumber($raw);

                // Ignore fees, monthly rents and page furniture.
                if ($value !== null && $value >= 10000 && $value <= 100000000) {
                    $key = (string) $value;
                    $counts[$key] = ($counts[$key] ?? 0) + 1;
                }
            }
        }

        if ($counts === []) {
            return null;
        }

        // Most repeated wins; ties are broken by the larger amount, since
        // teaser prices ("from 99,000") are usually the smaller ones.
        $best = null;
        $bestCount = 0;

        foreach ($counts as $value => $count) {
            $value = (float) $value;

            if ($count > $bestCount || ($count === $bestCount && $value > (float) $best)) {
                $best = $value;
                $bestCount = $count;
            }
        }

        return $best;
    }

    private function parseArea(string $text): ?float
    {
        if (! preg_match_all('/([0-9]{2,5}(?:[.,][0-9]{1,2})?)\s*(?:m2|m²|kvadrat|sqm|sq\.?\s?m)/iu', $text, $matches)) {
            return null;
        }

        foreach ($matches[1] as $raw) {
            $value = $this->toNumber($raw);

            if ($value !== null && $value >= 10 && $value <= 10000) {
                return $value;
            }
        }

        return null;
    }

    private function parseBedrooms(string $text): ?int
    {
        if (preg_match('/([0-9]{1,2})\s*(?:bedroom|bed\b|spava|schlafzimmer)/iu', $text, $match)) {
            $value = (int) $match[1];

            return $value > 0 && $value <= 30 ? $value : null;
        }

        return null;
    }

    /**
     * Normalise European and Anglo thousand/decimal separators.
     */
    private function toNumber(string $raw): ?float
    {
        $clean = preg_replace('/[^0-9.,]/', '', $raw) ?? '';

        if ($clean === '') {
            return null;
        }

        $lastComma = strrpos($clean, ',');
        $lastDot = strrpos($clean, '.');

        if ($lastComma !== false && $lastDot !== false) {
            // Whichever separator comes last is the decimal separator.
            $decimal = $lastComma > $lastDot ? ',' : '.';
            $thousands = $decimal === ',' ? '.' : ',';
            $clean = str_replace($thousands, '', $clean);
            $clean = str_replace($decimal, '.', $clean);
        } elseif ($lastComma !== false) {
            $clean = strlen($clean) - $lastComma - 1 === 3 ? str_replace(',', '', $clean) : str_replace(',', '.', $clean);
        } elseif ($lastDot !== false) {
            $clean = strlen($clean) - $lastDot - 1 === 3 ? str_replace('.', '', $clean) : $clean;
        }

        return is_numeric($clean) ? (float) $clean : null;
    }

    private function readableText(string $html): string
    {
        $stripped = preg_replace('#<(script|style|noscript)[^>]*>.*?</\1>#is', ' ', $html) ?? $html;

        return trim(preg_replace('/\s+/u', ' ', strip_tags($stripped)) ?? '');
    }

    private function meta(string $html, string $property): ?string
    {
        $pattern = '/<meta[^>]+(?:property|name)=["\']' . preg_quote($property, '/') . '["\'][^>]+content=["\']([^"\']*)["\']/i';

        return preg_match($pattern, $html, $match) ? html_entity_decode(trim($match[1])) : null;
    }

    private function tag(string $html, string $tag): ?string
    {
        $pattern = '#<' . preg_quote($tag, '#') . '[^>]*>(.*?)</' . preg_quote($tag, '#') . '>#is';

        return preg_match($pattern, $html, $match) ? html_entity_decode(trim(strip_tags($match[1]))) : null;
    }

    /**
     * Pull individual property links out of a category / search results page.
     *
     * Search engines mostly return listing overview pages, so following one
     * level down is what turns a hit into actual properties.
     *
     * @return array<int, string>
     */
    public function listingLinks(string $url, ?string $expectedHost = null, int $limit = 25): array
    {
        try {
            $this->urlPolicy->assertAllowed($url, $expectedHost);

            $response = Http::timeout(20)
                ->withHeaders(['User-Agent' => self::USER_AGENT, 'Accept' => 'text/html,application/xhtml+xml'])
                ->get($url);

            if (! $response->successful()) {
                return [];
            }
        } catch (Throwable) {
            return [];
        }

        $host = parse_url($url, PHP_URL_HOST);
        $scheme = parse_url($url, PHP_URL_SCHEME) ?: 'https';

        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\']/i', $response->body(), $matches);

        $links = [];

        foreach ($matches[1] ?? [] as $href) {
            $absolute = $this->absolutize(trim($href), $scheme, (string) $host);

            if ($absolute === null) {
                continue;
            }

            // Stay on the same site and keep only plausible detail pages.
            if (parse_url($absolute, PHP_URL_HOST) !== $host || ! $this->looksLikeDetailPage($absolute)) {
                continue;
            }

            // A numeric id is the strongest signal of a real detail page;
            // keyword-only paths are usually category listings.
            $links[$absolute] = preg_match('#\d{3,}#', (string) parse_url($absolute, PHP_URL_PATH)) ? 2 : 1;
        }

        arsort($links);

        return array_slice(array_keys($links), 0, $limit);
    }

    private function absolutize(string $href, string $scheme, string $host): ?string
    {
        if ($href === '' || str_starts_with($href, '#') || str_starts_with($href, 'mailto:') || str_starts_with($href, 'tel:')) {
            return null;
        }

        if (str_starts_with($href, 'https://')) {
            return $href;
        }

        if (str_starts_with($href, 'http://')) {
            // Only HTTPS is fetchable under the URL policy.
            return 'https://' . substr($href, 7);
        }

        if (str_starts_with($href, '//')) {
            return 'https:' . $href;
        }

        if (str_starts_with($href, '/')) {
            return $scheme . '://' . $host . $href;
        }

        return null;
    }

    private function looksLikeDetailPage(string $url): bool
    {
        $path = strtolower((string) parse_url($url, PHP_URL_PATH));

        if ($path === '' || $path === '/') {
            return false;
        }

        foreach (['/blog', '/news', '/about', '/contact', '/login', '/register', '/privacy', '/terms', '/cookie', '.pdf', '.jpg', '.png', '.css', '.js'] as $excluded) {
            if (str_contains($path, $excluded)) {
                return false;
            }
        }

        // Detail pages nearly always carry an id or a descriptive multi-part slug.
        return (bool) preg_match('#\d{3,}#', $path)
            || substr_count(trim($path, '/'), '/') >= 2
            || (bool) preg_match('#(property|listing|nekretnin|stan|apartman|apartment|villa|house|oglas|immobili)#', $path);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function toRecord(string $url, array $data): array
    {
        $size = $data['surface_area'] ?? $data['size_m2'] ?? $data['living_area'] ?? null;

        return array_filter([
            'external_id' => $this->externalId($url),
            'canonical_url' => $url,
            'availability_status' => 'active',
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'property_type' => $data['property_type'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? $data['location'] ?? null,
            'country_code' => $data['country_code'] ?? null,
            'price' => isset($data['price']) ? (float) $data['price'] : null,
            'currency' => $data['currency'] ?? 'EUR',
            'size_m2' => $size !== null ? (float) $size : null,
            'land_m2' => isset($data['plot_size']) ? (float) $data['plot_size'] : null,
            'bedrooms' => $data['bedrooms'] ?? null,
            'bathrooms' => $data['bathrooms'] ?? null,
            'condition' => $data['condition'] ?? null,
            'features' => array_values((array) ($data['features'] ?? [])),
            'offer_type' => 'sale',
            'discovered_by' => 'scraper',
            'extraction_method' => $data['extraction_method'] ?? null,
        ], fn ($value) => $value !== null && $value !== '' && $value !== []);
    }

    private function externalId(string $url): string
    {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');

        return Str::limit($path !== '' ? $path : sha1($url), 255, '');
    }
}
