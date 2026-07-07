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

        foreach ($phrases as $index => $phrase) {
            // Rate limiting - wait between requests
            if ($index > 0) {
                usleep($this->delayBetweenRequests * 1000);
            }

            $googleResults = $this->searchGoogle($phrase);

            if ($googleResults === null) {
                continue; // Skip if search failed
            }

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
            'results' => $results,
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
     * Search Google and parse first page results.
     * 
     * @return array<array{title:string, url:string, snippet:string}>|null
     */
    private function searchGoogle(string $query): ?array
    {
        // Check cache first (1 hour)
        $cacheKey = 'google_search_' . md5($query);
        $cached = Cache::get($cacheKey);
        
        if ($cached !== null) {
            return $cached;
        }

        try {
            $userAgent = self::USER_AGENTS[array_rand(self::USER_AGENTS)];
            
            $response = Http::timeout(15)
                ->withHeaders([
                    'User-Agent' => $userAgent,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Accept-Encoding' => 'gzip, deflate',
                    'Connection' => 'keep-alive',
                ])
                ->get('https://www.google.com/search', [
                    'q' => '"' . $query . '"', // Exact phrase search
                    'num' => 10,
                    'hl' => 'en',
                ]);

            if (!$response->successful()) {
                Log::warning('Google search failed', [
                    'status' => $response->status(),
                    'query' => $query,
                ]);
                return null;
            }

            $html = $response->body();
            $results = $this->parseGoogleResults($html);

            // Cache for 1 hour
            Cache::put($cacheKey, $results, 3600);

            return $results;

        } catch (\Exception $e) {
            Log::error('Google search exception', [
                'error' => $e->getMessage(),
                'query' => $query,
            ]);
            return null;
        }
    }

    /**
     * Parse Google search results HTML.
     * 
     * @return array<array{title:string, url:string, snippet:string}>
     */
    private function parseGoogleResults(string $html): array
    {
        $results = [];

        // Google's result structure changes, but we look for common patterns
        // Each result typically has: title in <h3>, URL in <a href>, snippet in <span> or <div>

        // Pattern 1: Look for result divs
        preg_match_all('/<div class="[^"]*"[^>]*>.*?<h3[^>]*>(.*?)<\/h3>.*?<a[^>]*href="([^"]*)"[^>]*>.*?<\/a>.*?<span[^>]*>(.*?)<\/span>/is', $html, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            // Pattern 2: Simpler extraction
            preg_match_all('/<a[^>]*href="\/url\?q=([^&"]+)[^"]*"[^>]*>.*?<h3[^>]*>(.*?)<\/h3>/is', $html, $urlMatches, PREG_SET_ORDER);
            
            foreach ($urlMatches as $match) {
                $url = urldecode($match[1] ?? '');
                $title = strip_tags($match[2] ?? '');
                
                if ($url && $title && !str_contains($url, 'google.com')) {
                    $results[] = [
                        'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
                        'url' => $url,
                        'snippet' => '',
                    ];
                }
            }
        } else {
            foreach ($matches as $match) {
                $title = strip_tags($match[1] ?? '');
                $url = $match[2] ?? '';
                $snippet = strip_tags($match[3] ?? '');

                // Clean URL if it's a Google redirect
                if (str_contains($url, '/url?q=')) {
                    preg_match('/\/url\?q=([^&]+)/', $url, $urlMatch);
                    $url = urldecode($urlMatch[1] ?? $url);
                }

                if ($title && !str_contains($url, 'google.com')) {
                    $results[] = [
                        'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
                        'url' => $url,
                        'snippet' => html_entity_decode($snippet, ENT_QUOTES, 'UTF-8'),
                    ];
                }
            }
        }

        // Also try to extract snippets separately if we have URLs but no snippets
        if (!empty($results) && empty($results[0]['snippet'])) {
            preg_match_all('/<span[^>]*class="[^"]*"[^>]*>([^<]{50,300})<\/span>/i', $html, $snippetMatches);
            
            foreach ($snippetMatches[1] ?? [] as $index => $snippet) {
                if (isset($results[$index])) {
                    $results[$index]['snippet'] = html_entity_decode(strip_tags($snippet), ENT_QUOTES, 'UTF-8');
                }
            }
        }

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
