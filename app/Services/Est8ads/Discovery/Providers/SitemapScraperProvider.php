<?php

namespace App\Services\Est8ads\Discovery\Providers;

use App\Models\Est8ads\InternetSource;
use App\Services\Est8ads\Discovery\Contracts\DiscoveryProvider;
use App\Services\Est8ads\Discovery\ListingPageScraper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

/**
 * Our own scraper as a discovery provider.
 *
 * Candidate listing URLs come from the source's sitemap (or an explicitly
 * configured list of sitemaps), are filtered down to listing detail pages, and
 * are then scraped for real figures. Location and property-type hints from the
 * wanted profile are used to prioritise which URLs get visited, because a full
 * portal sitemap is far larger than any single search needs.
 */
class SitemapScraperProvider implements DiscoveryProvider
{
    private const USER_AGENT = 'Mozilla/5.0 (compatible; VillaBitBot/1.0; +https://villabit.ai/bot)';

    /** Hard ceiling on pages visited per job, regardless of configuration. */
    private const MAX_PAGES = 120;

    public function __construct(private ListingPageScraper $scraper)
    {
    }

    public function search(InternetSource $source, array $profile): iterable
    {
        $limit = min(self::MAX_PAGES, (int) data_get($source->configuration, 'result_limit', 40));
        $host = $source->domain ?: parse_url($source->base_url, PHP_URL_HOST);
        $urls = $this->rank($this->candidateUrls($source), $profile, $limit * 3);

        $emitted = 0;

        foreach ($urls as $url) {
            $record = $this->scraper->scrape($url, $host);

            if (! $record) {
                continue;
            }

            $record['discovered_by'] = 'sitemap_scraper';

            yield $record;

            if (++$emitted >= $limit) {
                return;
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function candidateUrls(InternetSource $source): array
    {
        $sitemaps = array_values(array_filter((array) data_get($source->configuration, 'sitemaps', [])));

        if ($sitemaps === []) {
            $sitemaps = [rtrim($source->base_url, '/') . '/sitemap.xml'];
        }

        $urls = [];

        foreach ($sitemaps as $sitemap) {
            foreach ($this->readSitemap((string) $sitemap) as $url) {
                $urls[$url] = true;
            }
        }

        return array_keys($urls);
    }

    /**
     * Reads a sitemap, following one level of sitemap-index nesting.
     *
     * @return array<int, string>
     */
    private function readSitemap(string $sitemapUrl, bool $followIndex = true): array
    {
        try {
            $response = Http::timeout(25)->withHeaders(['User-Agent' => self::USER_AGENT])->get($sitemapUrl);

            if (! $response->successful()) {
                return [];
            }

            $xml = @simplexml_load_string($response->body());

            if (! $xml) {
                return [];
            }
        } catch (Throwable $exception) {
            Log::info('Sitemap read failed', ['sitemap' => $sitemapUrl, 'error' => $exception->getMessage()]);

            return [];
        }

        $urls = [];

        if ($xml->getName() === 'sitemapindex') {
            if (! $followIndex) {
                return [];
            }

            foreach ($xml->sitemap as $child) {
                $urls = array_merge($urls, $this->readSitemap((string) $child->loc, false));
            }

            return $urls;
        }

        foreach ($xml->url as $entry) {
            $location = trim((string) $entry->loc);

            if ($location !== '') {
                $urls[] = $location;
            }
        }

        return $urls;
    }

    /**
     * Prioritise URLs that mention the wanted city or property type, so the
     * limited crawl budget is spent on the most likely candidates.
     *
     * @param  array<int, string>  $urls
     * @return array<int, string>
     */
    private function rank(array $urls, array $profile, int $keep): array
    {
        $needles = collect(array_merge($profile['cities'] ?? [], $profile['areas'] ?? [], $profile['property_types'] ?? []))
            ->map(fn ($value) => Str::slug((string) $value))
            ->filter()
            ->all();

        $scored = [];

        foreach ($urls as $url) {
            if (! $this->looksLikeListing($url)) {
                continue;
            }

            $slug = Str::slug((string) parse_url($url, PHP_URL_PATH));
            $score = 0;

            foreach ($needles as $needle) {
                if ($needle !== '' && str_contains($slug, $needle)) {
                    $score++;
                }
            }

            $scored[] = ['url' => $url, 'score' => $score];
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_slice(array_column($scored, 'url'), 0, $keep);
    }

    private function looksLikeListing(string $url): bool
    {
        $path = strtolower((string) parse_url($url, PHP_URL_PATH));

        if ($path === '' || $path === '/') {
            return false;
        }

        foreach (['/blog', '/news', '/agent', '/about', '/contact', '/search', '/page/', '.pdf', '.jpg', '.png'] as $excluded) {
            if (str_contains($path, $excluded)) {
                return false;
            }
        }

        // Listing detail pages almost always carry an id or a multi-segment slug.
        return (bool) preg_match('#\d#', $path) || substr_count(trim($path, '/'), '/') >= 2;
    }
}
