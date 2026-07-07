<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CopyscapeChecker
 *
 * Checks text against the public Internet using Copyscape Premium API.
 * Requires COPYSCAPE_USERNAME and COPYSCAPE_API_KEY in .env
 *
 * Pricing: ~$0.03 per search (as of 2024)
 * Docs: https://www.copyscape.com/apiconfigure.php
 */
final class CopyscapeChecker
{
    private string $username;
    private string $apiKey;
    private string $apiUrl = 'https://www.copyscape.com/api/';

    public function __construct(?string $username = null, ?string $apiKey = null)
    {
        $this->username = $username ?? config('services.copyscape.username', '');
        $this->apiKey = $apiKey ?? config('services.copyscape.api_key', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->username) && !empty($this->apiKey);
    }

    /**
     * Check text against the public Internet.
     *
     * @param string $text Text to check (minimum 15 words recommended)
     * @return array{
     *   success: bool,
     *   verdict: 'passed'|'review'|'failed'|'error',
     *   internet_check: true,
     *   words_checked: int,
     *   matches_found: int,
     *   percent_matched: float,
     *   credits_remaining: int|null,
     *   matches: array<int, array{
     *     url: string,
     *     title: string,
     *     percent_matched: float,
     *     words_matched: int
     *   }>,
     *   error: string|null
     * }
     */
    public function check(string $text): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'verdict' => 'error',
                'internet_check' => true,
                'words_checked' => 0,
                'matches_found' => 0,
                'percent_matched' => 0.0,
                'credits_remaining' => null,
                'matches' => [],
                'error' => 'Copyscape API not configured. Add COPYSCAPE_USERNAME and COPYSCAPE_API_KEY to .env',
            ];
        }

        $wordCount = str_word_count(strip_tags($text));
        if ($wordCount < 15) {
            return [
                'success' => false,
                'verdict' => 'error',
                'internet_check' => true,
                'words_checked' => $wordCount,
                'matches_found' => 0,
                'percent_matched' => 0.0,
                'credits_remaining' => null,
                'matches' => [],
                'error' => 'Text too short. Copyscape requires at least 15 words.',
            ];
        }

        try {
            $response = Http::timeout(30)->asForm()->post($this->apiUrl, [
                'u' => $this->username,
                'o' => 'csearch',
                'f' => 'json',
                'e' => 'UTF-8',
                'c' => '10',
                't' => $text,
                'k' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::error('Copyscape API HTTP error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'verdict' => 'error',
                    'internet_check' => true,
                    'words_checked' => $wordCount,
                    'matches_found' => 0,
                    'percent_matched' => 0.0,
                    'credits_remaining' => null,
                    'matches' => [],
                    'error' => 'Copyscape API request failed: HTTP ' . $response->status(),
                ];
            }

            $data = $response->json();

            if (isset($data['error'])) {
                return [
                    'success' => false,
                    'verdict' => 'error',
                    'internet_check' => true,
                    'words_checked' => $wordCount,
                    'matches_found' => 0,
                    'percent_matched' => 0.0,
                    'credits_remaining' => null,
                    'matches' => [],
                    'error' => 'Copyscape API error: ' . $data['error'],
                ];
            }

            $matches = [];
            $totalPercentMatched = 0.0;
            $resultCount = (int) ($data['count'] ?? 0);

            if ($resultCount > 0 && isset($data['result']) && is_array($data['result'])) {
                foreach ($data['result'] as $result) {
                    $percentMatched = (float) ($result['percentmatched'] ?? 0);
                    $totalPercentMatched = max($totalPercentMatched, $percentMatched);

                    $matches[] = [
                        'url' => $result['url'] ?? '',
                        'title' => $result['title'] ?? '',
                        'percent_matched' => $percentMatched,
                        'words_matched' => (int) ($result['wordsmatched'] ?? 0),
                    ];
                }
            }

            $verdict = match (true) {
                $totalPercentMatched >= 15.0 => 'failed',
                $totalPercentMatched >= 5.0 => 'review',
                default => 'passed',
            };

            return [
                'success' => true,
                'verdict' => $verdict,
                'internet_check' => true,
                'words_checked' => $wordCount,
                'matches_found' => $resultCount,
                'percent_matched' => round($totalPercentMatched, 2),
                'credits_remaining' => isset($data['remaining']) ? (int) $data['remaining'] : null,
                'matches' => $matches,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('Copyscape API exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'verdict' => 'error',
                'internet_check' => true,
                'words_checked' => $wordCount,
                'matches_found' => 0,
                'percent_matched' => 0.0,
                'credits_remaining' => null,
                'matches' => [],
                'error' => 'Copyscape API exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check remaining API credits.
     */
    public function getBalance(): ?int
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get($this->apiUrl, [
                'u' => $this->username,
                'o' => 'balance',
                'f' => 'json',
                'k' => $this->apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['value']) ? (int) $data['value'] : null;
            }
        } catch (\Exception $e) {
            Log::error('Copyscape balance check failed', ['error' => $e->getMessage()]);
        }

        return null;
    }
}
