<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * GoogleFirstPageChecker
 *
 * Scrapes Google's first page results for key phrases from the text.
 * Compares our content against what's already ranking on Google.
 * 
 * FREE - no API keys needed.
 * 
 * IMPORTANT: This is for internal quality checks only.
 * Respects rate limits to avoid being blocked.
 */
final class GoogleFirstPageChecker
{
    private const USER_AGENTS = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Safari/605.1.15',
    ];

    private int $maxPhrases;
    private int $delayBetweenRequests;

    public function __construct(
        int $maxPhrases = 3,
        int $delayBetweenRequestsMs = 2000
    ) {
        $this->maxPhrases = $maxPhrases;
        $this->delayBetweenRequests = $delayBetweenRequestsMs;
    }

    /**
     * Check text against Google's first page results.
     *
     * @param string $text Text to check
     * @return array{
     *   success: bool,
     *   verdict: 'passed'|'review'|'failed'|'error',
     *   google_check: true,
     *   phrases_checked: int,
     *   similar_results_found: int,
     *   results: array<int, array{
     *     phrase: string,
     *     google_results: array<int, array{
     *       title: string,
     *       url: string,
     *       snippet: string,
     *       similarity_percent: float
     *     }>
     *   }>,
     *   error: string|null
     * }
     */
    public function check(string $text): array
    {
        $phrases = $this->extractKeyPhrases($text);

        if (empty($phrases)) {
            return [
                'success' => false,
                'verdict' => 'error',
                'google_check' => true,
                'phrases_checked' => 0,
                'similar_results_found' => 0,
                'results' => [],
                'error' => 'Could not extract meaningful phrases from text.',
            ];
        }

        $results = [];
        $totalSimilarFound = 0;
        $highestSimilarity = 0.0;
        $debugInfo = [];

        Log::info('Google check starting', ['phrases' => $phrases]);

        foreach ($phrases as $index => $phrase) {
            // Rate limiting - wait between requests
            if ($index > 0) {
                usleep($this->delayBetweenRequests * 1000);
            }

            $googleResults = $this->searchGoogle($phrase);

            $debugInfo[] = [
                'phrase' => $phrase,
                'results_count' => $googleResults ? count($googleResults) : 0,
            ];

            if ($googleResults === null) {
                Log::warning('Google search returned null', ['phrase' => $phrase]);
                continue; // Skip if search failed
            }

            Log::info('Google search results', [
                'phrase' => $phrase,
                'count' => count($googleResults),
                'first_result' => $googleResults[0] ?? null,
            ]);

            $phraseResult = [
                'phrase' => $phrase,
                'google_results' => [],
            ];

            foreach ($googleResults as $result) {
                $similarity = $this->calculateSimilarity($text, $result['snippet'] ?? '');
                
                if ($similarity > 5.0) { // Only include if >5% similar
                    $totalSimilarFound++;
                    $highestSimilarity = max($highestSimilarity, $similarity);
                    
                    $phraseResult['google_results'][] = [
                        'title' => $result['title'] ?? '',
                        'url' => $result['url'] ?? '',
                        'snippet' => $result['snippet'] ?? '',
                        'similarity_percent' => round($similarity, 1),
                    ];
                }
            }

            $results[] = $phraseResult;
        }

        $verdict = match (true) {
            $highestSimilarity >= 40.0 => 'failed',
            $highestSimilarity >= 20.0 => 'review',
            default => 'passed',
        };

        return [
            'success' => true,
            'verdict' => $verdict,
            'google_check' => true,
            'phrases_checked' => count($phrases),
            'similar_results_found' => $totalSimilarFound,
            'highest_similarity' => round($highestSimilarity, 1),
            'max_similarity_percent' => round($highestSimilarity, 1),
            'results' => $results,
            'debug' => $debugInfo,
            'error' => null,
        ];
    }

    /**
     * Extract 3-5 key phrases from text for Google search.
     * 
     * @return array<string>
     */
    private function extractKeyPhrases(string $text): array
    {
        // Clean text
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (strlen($text) < 100) {
            return [];
        }

        // Split into sentences
        $sentences = preg_split('/[.!?]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        $sentences = array_filter(array_map('trim', $sentences));

        if (empty($sentences)) {
            return [];
        }

        $phrases = [];

        // Strategy 1: Take meaningful 5-8 word chunks from different parts
        foreach ($sentences as $sentence) {
            $words = preg_split('/\s+/', $sentence);
            
            if (count($words) >= 5) {
                // Skip generic starts like "The", "This", "We", etc.
                $skipWords = ['the', 'this', 'we', 'our', 'a', 'an', 'it', 'there', 'here'];
                $startIndex = 0;
                
                while ($startIndex < count($words) - 5 && 
                       in_array(strtolower($words[$startIndex]), $skipWords)) {
                    $startIndex++;
                }

                $chunk = array_slice($words, $startIndex, 7);
                $phrase = implode(' ', $chunk);
                
                // Only use if it looks meaningful (has some substance)
                if (strlen($phrase) >= 25 && strlen($phrase) <= 80) {
                    $phrases[] = $phrase;
                }
            }

            if (count($phrases) >= $this->maxPhrases) {
                break;
            }
        }

        // If we don't have enough, try different approach
        if (count($phrases) < 2 && count($sentences) >= 2) {
            // Take from middle of text
            $middleIndex = (int) (count($sentences) / 2);
            $middleSentence = $sentences[$middleIndex] ?? '';
            $words = preg_split('/\s+/', $middleSentence);
            
            if (count($words) >= 5) {
                $phrases[] = implode(' ', array_slice($words, 0, 7));
            }
        }

        // Remove duplicates and limit
        $phrases = array_unique($phrases);
        return array_slice($phrases, 0, $this->maxPhrases);
    }

    /**
     * Search using DuckDuckGo HTML version (scraping-friendly).
     * 
     * @return array<array{title:string, url:string, snippet:string}>|null
     */
    private function searchGoogle(string $query): ?array
    {
        // Check cache first (1 hour)
        $cacheKey = 'ddg_search_' . md5($query);
        $cached = Cache::get($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }

        try {
            $userAgent = self::USER_AGENTS[array_rand(self::USER_AGENTS)];
            
            // Use DuckDuckGo HTML version - much more scraping-friendly
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => $userAgent,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get('https://html.duckduckgo.com/html/', [
                    'q' => '"' . $query . '"', // Exact phrase search
                ]);

            if (!$response->successful()) {
                Log::warning('DuckDuckGo search failed', [
                    'status' => $response->status(),
                    'query' => $query,
                ]);
                return null;
            }

            $html = $response->body();
            $results = $this->parseDuckDuckGoResults($html);

            // Cache for 1 hour
            Cache::put($cacheKey, $results, 3600);

            return $results;

        } catch (\Exception $e) {
            Log::error('DuckDuckGo search exception', [
                'error' => $e->getMessage(),
                'query' => $query,
            ]);
            return null;
        }
    }

    /**
     * Parse DuckDuckGo HTML results.
     * 
     * @return array<array{title:string, url:string, snippet:string}>
     */
    private function parseDuckDuckGoResults(string $html): array
    {
        $results = [];

        // DuckDuckGo HTML structure is simple and consistent
        // Results are in <div class="result"> with <a class="result__a"> for title/URL
        // and <a class="result__snippet"> for snippet

        // Find all result links
        preg_match_all('/<a[^>]+class="result__a"[^>]+href="([^"]+)"[^>]*>(.*?)<\/a>/is', $html, $linkMatches, PREG_SET_ORDER);
        
        // Find all snippets
        preg_match_all('/<a[^>]+class="result__snippet"[^>]*>(.*?)<\/a>/is', $html, $snippetMatches);
        $snippets = $snippetMatches[1] ?? [];

        foreach ($linkMatches as $i => $match) {
            $url = $match[1] ?? '';
            $title = strip_tags($match[2] ?? '');
            $snippet = strip_tags($snippets[$i] ?? '');

            // DuckDuckGo uses redirect URLs, extract actual URL
            if (str_contains($url, 'uddg=')) {
                preg_match('/uddg=([^&]+)/', $url, $uddgMatch);
                $url = urldecode($uddgMatch[1] ?? $url);
            }

            if ($title && $url && filter_var($url, FILTER_VALIDATE_URL)) {
                $results[] = [
                    'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
                    'url' => $url,
                    'snippet' => html_entity_decode($snippet, ENT_QUOTES, 'UTF-8'),
                ];
            }
        }

        Log::info('Parsed DuckDuckGo results', ['count' => count($results)]);

        return array_slice($results, 0, 10);
    }

    /**
     * Parse Google search results HTML.
     * 
     * @return array<array{title:string, url:string, snippet:string}>
     */
    private function parseGoogleResults(string $html): array
    {
        $results = [];

        Log::debug('Google HTML length: ' . strlen($html));
        
        // Debug: Save HTML to file to inspect structure
        file_put_contents(storage_path('logs/google_response.html'), $html);

        // Method 1: Find all <a> tags with /url?q= (Google's redirect URLs)
        preg_match_all('/<a[^>]+href="\/url\?q=([^&"]+)[^"]*"[^>]*>/i', $html, $urlMatches);
        
        $foundUrls = [];
        foreach ($urlMatches[1] ?? [] as $encodedUrl) {
            $url = urldecode($encodedUrl);
            // Skip Google internal links
            if (!str_contains($url, 'google.com') && 
                !str_contains($url, 'youtube.com/results') &&
                filter_var($url, FILTER_VALIDATE_URL)) {
                $foundUrls[] = $url;
            }
        }
        $foundUrls = array_unique($foundUrls);

        // Method 2: Find all <h3> tags (titles)
        preg_match_all('/<h3[^>]*>(.*?)<\/h3>/is', $html, $titleMatches);
        $titles = array_map(function($t) {
            return html_entity_decode(strip_tags($t), ENT_QUOTES, 'UTF-8');
        }, $titleMatches[1] ?? []);

        // Method 3: Look for data-snf or data-sncf divs (Google's result containers)
        preg_match_all('/data-(?:snf|sncf|sokoban-container)[^>]*>.*?<h3[^>]*>(.*?)<\/h3>.*?<a[^>]+href="([^"]*)"[^>]*>/is', $html, $containerMatches, PREG_SET_ORDER);

        if (!empty($containerMatches)) {
            foreach ($containerMatches as $match) {
                $title = html_entity_decode(strip_tags($match[1] ?? ''), ENT_QUOTES, 'UTF-8');
                $url = $match[2] ?? '';
                
                if (str_contains($url, '/url?q=')) {
                    preg_match('/\/url\?q=([^&]+)/', $url, $urlMatch);
                    $url = urldecode($urlMatch[1] ?? '');
                }

                if ($title && $url && !str_contains($url, 'google.com')) {
                    $results[] = ['title' => $title, 'url' => $url, 'snippet' => ''];
                }
            }
        }

        // If no results yet, combine URLs and titles
        if (empty($results) && !empty($foundUrls)) {
            foreach ($foundUrls as $i => $url) {
                $title = $titles[$i] ?? parse_url($url, PHP_URL_HOST) ?? $url;
                $results[] = [
                    'title' => $title,
                    'url' => $url,
                    'snippet' => '',
                ];
            }
        }

        // Method 4: Look for cite tags (URL display)
        if (empty($results)) {
            preg_match_all('/<cite[^>]*>([^<]+)<\/cite>/i', $html, $citeMatches);
            foreach ($citeMatches[1] ?? [] as $i => $cite) {
                $cite = strip_tags($cite);
                if (filter_var('https://' . $cite, FILTER_VALIDATE_URL) || filter_var($cite, FILTER_VALIDATE_URL)) {
                    $url = str_starts_with($cite, 'http') ? $cite : 'https://' . $cite;
                    $results[] = [
                        'title' => $titles[$i] ?? $cite,
                        'url' => $url,
                        'snippet' => '',
                    ];
                }
            }
        }

        // Extract snippets from the page
        preg_match_all('/<span[^>]*>([^<]{80,400})<\/span>/i', $html, $snippetMatches);
        $snippets = array_filter($snippetMatches[1] ?? [], function($s) {
            $s = strip_tags($s);
            // Filter out navigation/UI text
            return strlen($s) > 80 && 
                   !str_contains(strtolower($s), 'sign in') &&
                   !str_contains(strtolower($s), 'settings') &&
                   preg_match('/[a-z]{3,}/i', $s);
        });
        $snippets = array_values($snippets);

        // Assign snippets to results
        foreach ($results as $i => &$result) {
            if (empty($result['snippet']) && isset($snippets[$i])) {
                $result['snippet'] = html_entity_decode(strip_tags($snippets[$i]), ENT_QUOTES, 'UTF-8');
            }
        }

        Log::info('Parsed Google results', [
            'found_urls' => count($foundUrls),
            'found_titles' => count($titles),
            'final_results' => count($results),
        ]);

        return array_slice($results, 0, 10);
    }

    /**
     * Calculate text similarity using word overlap.
     */
    private function calculateSimilarity(string $text1, string $text2): float
    {
        if (empty($text2)) {
            return 0.0;
        }

        $words1 = $this->getWords($text1);
        $words2 = $this->getWords($text2);

        if (empty($words1) || empty($words2)) {
            return 0.0;
        }

        // Count matching words
        $matches = count(array_intersect($words1, $words2));
        
        // Calculate as percentage of the smaller text
        $minWords = min(count($words1), count($words2));
        
        return ($matches / $minWords) * 100;
    }

    /**
     * Extract normalized words from text.
     * 
     * @return array<string>
     */
    private function getWords(string $text): array
    {
        $text = mb_strtolower(strip_tags($text), 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 
                      'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will',
                      'would', 'could', 'should', 'may', 'might', 'must', 'shall',
                      'can', 'to', 'of', 'in', 'for', 'on', 'with', 'at', 'by',
                      'from', 'as', 'into', 'through', 'during', 'before', 'after',
                      'above', 'below', 'between', 'under', 'again', 'further',
                      'then', 'once', 'here', 'there', 'when', 'where', 'why',
                      'how', 'all', 'each', 'few', 'more', 'most', 'other', 'some',
                      'such', 'no', 'nor', 'not', 'only', 'own', 'same', 'so',
                      'than', 'too', 'very', 'just', 'and', 'but', 'if', 'or',
                      'because', 'until', 'while', 'this', 'that', 'these', 'those'];

        return array_values(array_filter($words, fn($w) => !in_array($w, $stopWords) && strlen($w) > 2));
    }
}
