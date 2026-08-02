<?php

namespace App\Services\Est8ads\Discovery\Providers;

use App\Models\Est8ads\InternetSource;
use App\Services\AiService;
use App\Services\Est8ads\Discovery\Contracts\DiscoveryProvider;
use App\Services\Est8ads\Discovery\ListingPageScraper;
use App\Services\Est8ads\Discovery\WebSearchClient;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Open-web property discovery: search the whole internet, scrape what is found.
 *
 * Pipeline:
 *  1. AI turns the buyer profile into search queries (with a deterministic
 *     fallback, including local-language phrasing).
 *  2. A real search engine returns real URLs. This step is essential — a model
 *     without browsing either invents URLs or returns nothing at all.
 *  3. Category pages are expanded into their individual listing links.
 *  4. Our scraper visits every page and extracts the true price, size and
 *     location. Nothing the AI claims about a property is trusted.
 *  5. Results are de-duplicated twice: by URL, and by property fingerprint so
 *     the same flat listed on three portals is only kept once.
 */
class WebSearchProvider implements DiscoveryProvider
{
    public function __construct(
        private AiService $ai,
        private ListingPageScraper $scraper,
        private WebSearchClient $search,
    ) {
    }

    public function search(InternetSource $source, array $profile): iterable
    {
        $limit = (int) data_get($source->configuration, 'result_limit', 25);
        $restrictTo = $this->restrictedHost($source);

        $seenUrls = [];
        $seenFingerprints = [];
        $emitted = 0;
        $scraped = 0;
        $maxPages = max($limit * 6, 60);

        // Used by the scraper to confirm (never assume) location and type.
        $hints = [
            'cities' => $profile['cities'] ?? [],
            'countries' => $profile['countries'] ?? [],
            'property_types' => $profile['property_types'] ?? [],
        ];

        foreach ($this->queries($profile, $restrictTo) as $query) {
            foreach ($this->search->search($query, 20) as $resultUrl) {
                foreach ($this->expand($resultUrl, $restrictTo) as $url) {
                    $url = $this->normalizeUrl($url);

                    if ($url === '' || isset($seenUrls[$url])) {
                        continue;
                    }

                    $seenUrls[$url] = true;

                    if ($scraped++ >= $maxPages) {
                        return;
                    }

                    // The search engine only nominates a page.
                    // Our scraper decides the facts.
                    $record = $this->scraper->scrape($url, $restrictTo, $hints);

                    if (! $record || ! $this->isProperty($record)) {
                        continue;
                    }

                    // The same property is often listed on several portals under
                    // different URLs, so de-duplicate on the property itself too.
                    $fingerprint = $this->fingerprint($record);

                    if (isset($seenFingerprints[$fingerprint])) {
                        continue;
                    }

                    $seenFingerprints[$fingerprint] = true;
                    $record['discovered_by'] = 'web_search';
                    $record['search_query'] = $query;

                    yield array_filter($record, fn ($value) => $value !== null);

                    if (++$emitted >= $limit) {
                        return;
                    }
                }
            }
        }

        Log::info('Open web discovery finished', [
            'pages_visited' => $scraped,
            'listings_verified' => $emitted,
        ]);
    }

    /**
     * A search hit is often a category page listing many properties. Pull the
     * individual listing links out of it so real detail pages get scraped.
     *
     * @return array<int, string>
     */
    private function expand(string $url, ?string $restrictTo): array
    {
        $urls = [$url];

        foreach ($this->scraper->listingLinks($url, $restrictTo) as $child) {
            $urls[] = $child;
        }

        return $urls;
    }

    /**
     * A page only counts as a property once the scraper has read a price and
     * either a size or a location from it.
     */
    private function isProperty(array $record): bool
    {
        return ! empty($record['price'])
            && (! empty($record['size_m2']) || ! empty($record['city']) || ! empty($record['address']));
    }

    /**
     * Ask the AI for good search phrases, and always keep a deterministic
     * fallback so discovery still works when no model is reachable.
     *
     * @return array<int, string>
     */
    private function queries(array $profile, ?string $restrictTo): array
    {
        $type = (string) (($profile['property_types'][0] ?? null) ?: 'property');
        $city = (string) (($profile['cities'][0] ?? null) ?: '');
        $country = (string) (($profile['countries'][0] ?? null) ?: '');
        $budget = (float) data_get($profile, 'price.max', 0);
        $currency = (string) data_get($profile, 'price.currency', 'EUR');

        $queries = array_values(array_filter([
            trim("{$type} for sale {$city} {$country}"),
            trim("{$type} for sale {$city} " . ($budget > 0 ? 'under ' . number_format($budget, 0, '.', '') . ' ' . $currency : '')),
            trim("buy {$type} {$city} {$country} real estate listing"),
        ]));

        foreach ($this->aiQueries($profile) as $query) {
            $queries[] = $query;
        }

        if ($restrictTo) {
            $queries = array_map(fn ($q) => $q . ' site:' . $restrictTo, $queries);
        }

        return array_values(array_unique(array_filter($queries)));
    }

    /**
     * @return array<int, string>
     */
    private function aiQueries(array $profile): array
    {
        $summary = json_encode(array_intersect_key($profile, array_flip([
            'property_types', 'cities', 'countries', 'price', 'target_size_m2', 'minimum_bedrooms',
        ])), JSON_UNESCAPED_UNICODE);

        $prompt = <<<PROMPT
A buyer is looking for a property with these requirements:
{$summary}

Write 4 web search engine queries that would find real listing pages for this buyer.
Include local-language queries where relevant (for example Croatian for Croatia).
Do not include the word "site:" and do not invent URLs.

Respond with JSON only: {"queries": ["...", "..."]}
PROMPT;

        foreach ($this->models() as $model) {
            try {
                $response = $this->ai->generateJson($prompt, $model, ['temperature' => 0.4, 'max_tokens' => 600]);
            } catch (Throwable) {
                continue;
            }

            $queries = array_filter((array) ($response['queries'] ?? []), 'is_string');

            if ($queries !== []) {
                return array_values($queries);
            }
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function models(): array
    {
        return array_values(array_filter(array_map(
            'strval',
            (array) config('est8ads.discovery.web_search_providers', ['openai', 'gemini'])
        )));
    }

    /**
     * Open-web sources search everywhere. A source may still pin itself to one
     * domain, in which case the scraper enforces it.
     */
    private function restrictedHost(InternetSource $source): ?string
    {
        if (data_get($source->configuration, 'allow_any_host', false)) {
            return null;
        }

        return $source->domain ?: parse_url((string) $source->base_url, PHP_URL_HOST) ?: null;
    }

    /**
     * Identity of a property independent of which portal published it.
     */
    private function fingerprint(array $record): string
    {
        return sha1(implode('|', [
            strtolower(trim((string) ($record['city'] ?? ''))),
            strtolower(trim((string) ($record['property_type'] ?? ''))),
            round((float) ($record['price'] ?? 0), -3),
            round((float) ($record['size_m2'] ?? 0)),
        ]));
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        $parts = parse_url($url);

        if (! is_array($parts) || empty($parts['host'])) {
            return '';
        }

        $scheme = strtolower($parts['scheme'] ?? 'https');
        $path = rtrim(preg_replace('#/+#', '/', $parts['path'] ?? '/'), '/');

        return $scheme . '://' . strtolower($parts['host']) . $path;
    }

    private function value(mixed $value): string
    {
        return $value === null || $value === '' ? 'not specified' : (string) $value;
    }
}
