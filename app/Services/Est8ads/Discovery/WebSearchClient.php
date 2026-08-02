<?php

namespace App\Services\Est8ads\Discovery;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Finds real URLs on the open internet for a search phrase.
 *
 * Language models cannot do this on their own: without browsing they either
 * invent URLs or, when told not to, return nothing. So the actual finding is
 * done by a search engine, and the AI is used for what it is good at — turning
 * a buyer profile into good queries and judging the results afterwards.
 *
 * Backends, in order of preference:
 *  - Brave Search API   (BRAVE_SEARCH_API_KEY)
 *  - Google Custom Search (GOOGLE_CSE_KEY + GOOGLE_CSE_CX)
 *  - DuckDuckGo Lite HTML (no key, used as the default fallback)
 */
class WebSearchClient
{
    private const USER_AGENT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36';

    /**
     * @return array<int, string> Absolute result URLs, best first.
     */
    public function search(string $query, int $limit = 20): array
    {
        foreach (['brave', 'google', 'duckduckgo'] as $backend) {
            $results = match ($backend) {
                'brave' => $this->brave($query, $limit),
                'google' => $this->google($query, $limit),
                'duckduckgo' => $this->duckduckgo($query, $limit),
            };

            if ($results !== []) {
                return $results;
            }
        }

        return [];
    }

    /**
     * @return array<int, string>
     */
    private function brave(string $query, int $limit): array
    {
        $key = config('services.brave_search.api_key');

        if (! $key) {
            return [];
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders(['X-Subscription-Token' => $key, 'Accept' => 'application/json'])
                ->get('https://api.search.brave.com/res/v1/web/search', ['q' => $query, 'count' => min(20, $limit)]);

            if (! $response->successful()) {
                return [];
            }

            return collect($response->json('web.results') ?? [])
                ->pluck('url')
                ->filter()
                ->take($limit)
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::info('Brave search failed', ['error' => $exception->getMessage()]);

            return [];
        }
    }

    /**
     * @return array<int, string>
     */
    private function google(string $query, int $limit): array
    {
        $key = config('services.google_cse.key');
        $cx = config('services.google_cse.cx');

        if (! $key || ! $cx) {
            return [];
        }

        try {
            $urls = [];

            // The CSE API returns at most 10 results per call.
            for ($start = 1; $start <= $limit && $start <= 91; $start += 10) {
                $response = Http::timeout(20)->get('https://www.googleapis.com/customsearch/v1', [
                    'key' => $key, 'cx' => $cx, 'q' => $query, 'start' => $start, 'num' => 10,
                ]);

                if (! $response->successful()) {
                    break;
                }

                foreach ($response->json('items') ?? [] as $item) {
                    if (! empty($item['link'])) {
                        $urls[] = $item['link'];
                    }
                }

                if (count($urls) >= $limit) {
                    break;
                }
            }

            return array_slice($urls, 0, $limit);
        } catch (Throwable $exception) {
            Log::info('Google CSE search failed', ['error' => $exception->getMessage()]);

            return [];
        }
    }

    /**
     * Keyless fallback. Parses the redirect targets out of the Lite results page.
     *
     * @return array<int, string>
     */
    private function duckduckgo(string $query, int $limit): array
    {
        try {
            $response = Http::timeout(20)
                ->withHeaders(['User-Agent' => self::USER_AGENT, 'Accept-Language' => 'en-US,en;q=0.9'])
                ->get('https://lite.duckduckgo.com/lite/', ['q' => $query]);

            if (! $response->successful()) {
                return [];
            }

            preg_match_all('/uddg=([^"&]+)/', $response->body(), $matches);

            $urls = [];

            foreach ($matches[1] ?? [] as $encoded) {
                $url = urldecode($encoded);

                if (str_starts_with($url, 'https://') && ! str_contains($url, 'duckduckgo.com')) {
                    $urls[$url] = true;
                }
            }

            return array_slice(array_keys($urls), 0, $limit);
        } catch (Throwable $exception) {
            Log::info('DuckDuckGo search failed', ['error' => $exception->getMessage()]);

            return [];
        }
    }
}
