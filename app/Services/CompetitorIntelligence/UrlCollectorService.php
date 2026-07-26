<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorSource;
use App\Models\CompetitorUrl;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrlCollectorService
{
    protected int $timeout = 30;
    protected array $userAgents = [
        'Mozilla/5.0 (compatible; VillaBitBot/1.0; +https://villabit.ai/bot)',
    ];

    public function collectFromSitemap(Competitor $competitor): array
    {
        $collectedUrls = [];
        $sitemapUrls = $this->discoverSitemaps($competitor->website_url);

        foreach ($sitemapUrls as $sitemapUrl) {
            try {
                $urls = $this->parseSitemap($sitemapUrl);
                $collectedUrls = array_merge($collectedUrls, $urls);
            } catch (\Exception $e) {
                Log::warning("Failed to parse sitemap: {$sitemapUrl}", ['error' => $e->getMessage()]);
            }
        }

        return $this->processCollectedUrls($competitor, $collectedUrls, 'sitemap');
    }

    public function discoverSitemaps(string $websiteUrl): array
    {
        $baseUrl = rtrim($websiteUrl, '/');
        $sitemaps = [];

        $commonPaths = [
            '/sitemap.xml',
            '/sitemap_index.xml',
            '/sitemap-index.xml',
            '/wp-sitemap.xml',
        ];

        foreach ($commonPaths as $path) {
            $url = $baseUrl . $path;
            if ($this->urlExists($url)) {
                $sitemaps[] = $url;
                break;
            }
        }

        $robotsTxt = $this->fetchRobotsTxt($baseUrl);
        if ($robotsTxt) {
            $robotsSitemaps = $this->extractSitemapsFromRobots($robotsTxt);
            $sitemaps = array_merge($sitemaps, $robotsSitemaps);
        }

        return array_unique($sitemaps);
    }

    protected function parseSitemap(string $url): array
    {
        $response = Http::timeout($this->timeout)
            ->withHeaders(['User-Agent' => $this->userAgents[0]])
            ->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch sitemap: HTTP {$response->status()}");
        }

        $content = $response->body();
        $urls = [];

        try {
            $xml = simplexml_load_string($content);

            if ($xml === false) {
                throw new \Exception("Invalid XML");
            }

            $xml->registerXPathNamespace('sm', 'http://www.sitemaps.org/schemas/sitemap/0.9');

            if ($xml->sitemap) {
                foreach ($xml->sitemap as $sitemap) {
                    $nestedUrls = $this->parseSitemap((string)$sitemap->loc);
                    $urls = array_merge($urls, $nestedUrls);
                }
            }

            if ($xml->url) {
                foreach ($xml->url as $urlNode) {
                    $urls[] = [
                        'url' => (string)$urlNode->loc,
                        'lastmod' => isset($urlNode->lastmod) ? (string)$urlNode->lastmod : null,
                        'changefreq' => isset($urlNode->changefreq) ? (string)$urlNode->changefreq : null,
                        'priority' => isset($urlNode->priority) ? (float)$urlNode->priority : null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("XML parsing failed for {$url}", ['error' => $e->getMessage()]);
        }

        return $urls;
    }

    protected function fetchRobotsTxt(string $baseUrl): ?string
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => $this->userAgents[0]])
                ->get($baseUrl . '/robots.txt');

            return $response->successful() ? $response->body() : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function extractSitemapsFromRobots(string $robotsTxt): array
    {
        $sitemaps = [];
        $lines = explode("\n", $robotsTxt);

        foreach ($lines as $line) {
            if (preg_match('/^Sitemap:\s*(.+)$/i', trim($line), $matches)) {
                $sitemaps[] = trim($matches[1]);
            }
        }

        return $sitemaps;
    }

    protected function urlExists(string $url): bool
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['User-Agent' => $this->userAgents[0]])
                ->head($url);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function processCollectedUrls(Competitor $competitor, array $urls, string $sourceType): array
    {
        $source = $competitor->sources()->where('type', $sourceType)->first();

        if (!$source) {
            $source = $competitor->sources()->create([
                'type' => $sourceType,
                'url' => $competitor->website_url,
                'status' => 'active',
            ]);
        }

        $results = [
            'new' => 0,
            'existing' => 0,
            'total' => count($urls),
        ];

        foreach ($urls as $urlData) {
            $url = is_array($urlData) ? $urlData['url'] : $urlData;
            $lastmod = is_array($urlData) ? ($urlData['lastmod'] ?? null) : null;

            $existing = CompetitorUrl::where('competitor_id', $competitor->id)
                ->where('url', $url)
                ->first();

            if ($existing) {
                $existing->update([
                    'last_seen_at' => now(),
                    'sitemap_lastmod' => $lastmod ? \Carbon\Carbon::parse($lastmod) : null,
                ]);
                $results['existing']++;
            } else {
                CompetitorUrl::create([
                    'competitor_id' => $competitor->id,
                    'competitor_source_id' => $source->id,
                    'url' => $url,
                    'status' => 'active',
                    'first_detected_at' => now(),
                    'last_seen_at' => now(),
                    'sitemap_lastmod' => $lastmod ? \Carbon\Carbon::parse($lastmod) : null,
                ]);
                $results['new']++;
            }
        }

        $source->update(['last_checked_at' => now()]);

        return $results;
    }

    public function classifyPageType(string $url, ?string $title = null): ?string
    {
        $urlLower = strtolower($url);
        $titleLower = $title ? strtolower($title) : '';

        // RE/MAX pattern: /hr-hr/listinzi/stan-apartman/za-prodaju/location/ID
        // Has 6 segments after domain = property detail
        if (preg_match('/\/(listinzi|listings)\/(stan-apartman|apartment|kuca|house|vila|villa|zemljiste|land|poslovni-prostor|commercial|garaza|garage)\/(za-prodaju|for-sale|za-najam|for-rent)\/[^\/]+\/[^\/]+/i', $url)) {
            return 'property_detail';
        }

        if (preg_match('/\/(property|listing|nekretnina|vila|apartment|stan|kuca|house|villa|oglas)\/[^\/]+$/i', $url)) {
            return 'property_detail';
        }

        // Generic property detail with numeric ID at end
        if (preg_match('/\/\d{5,}(-\d+)?$/i', $url)) {
            return 'property_detail';
        }

        if (preg_match('/\/(properties|listings|listinzi|nekretnine|vile|apartments|stanovi|kuce|houses|villas|for-sale|na-prodaju)/i', $url)) {
            return 'property_listing';
        }

        if (preg_match('/\/(location|area|region|grad|mjesto|lokacija|split|dubrovnik|zagreb|zadar|sibenik|primosten)/i', $url)) {
            return 'location_page';
        }

        if (preg_match('/\/(blog|news|vijesti|novosti|article|clanak)/i', $url)) {
            return 'blog_post';
        }

        if (preg_match('/\/(team|about|o-nama|tim|agent|agents)/i', $url)) {
            return 'team';
        }

        if (preg_match('/\/(services|usluge|service)/i', $url)) {
            return 'services';
        }

        if (preg_match('/\/(contact|kontakt)/i', $url)) {
            return 'contact';
        }

        if (preg_match('/\/(faq|pitanja|questions)/i', $url)) {
            return 'faq';
        }

        $parsed = parse_url($url);
        if (empty($parsed['path']) || $parsed['path'] === '/') {
            return 'homepage';
        }

        return 'other';
    }
}
