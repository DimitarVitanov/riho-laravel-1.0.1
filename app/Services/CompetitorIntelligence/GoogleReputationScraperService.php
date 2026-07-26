<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorEvent;
use App\Models\CompetitorGoogleMetric;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\Process\Process;

class GoogleReputationScraperService
{
    public function refresh(Competitor $competitor): CompetitorGoogleMetric
    {
        $placeId = null;
        if ($competitor->google_place_id && preg_match('/ChI[A-Za-z0-9_-]+/', $competitor->google_place_id, $match)) {
            $placeId = $match[0];
        }

        $url = $placeId
            ? 'https://www.google.com/maps/place/?q=place_id:' . rawurlencode($placeId)
            : $competitor->google_maps_url;

        if (!$url) {
            throw new RuntimeException('No Google Maps URL or Place ID is configured.');
        }

        $response = Http::timeout(20)
            ->withHeaders([
                'Accept-Language' => 'en-US,en;q=0.9',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
            ])
            ->get($url);

        if (!$response->successful()) {
            throw new RuntimeException('Google profile request failed with HTTP ' . $response->status() . '.');
        }

        $html = $response->body();
        $data = $this->extractProfileData($html);

        if ($data['review_count'] === null) {
            $browserData = $this->extractProfileDataWithBrowser($url);
            $data = [
                'rating' => $browserData['rating'] ?? $data['rating'],
                'review_count' => $browserData['review_count'] ?? $data['review_count'],
                'business_name' => $browserData['business_name'] ?? $data['business_name'],
            ];

        }

        if ($data['rating'] === null && $data['review_count'] === null) {
            throw new RuntimeException('Google did not expose a rating or review count for this profile.');
        }

        return DB::transaction(function () use ($competitor, $url, $data) {
            $previous = $competitor->googleMetrics()->orderByDesc('captured_at')->first();
            $metric = $competitor->googleMetrics()->create([
                'rating' => $data['rating'],
                'review_count' => $data['review_count'],
                'business_name' => $data['business_name'] ?? $competitor->name,
                'captured_at' => now(),
            ]);

            $this->createChangeEvents($competitor, $previous, $metric, $url);

            return $metric;
        });
    }

    protected function extractProfileData(string $html): array
    {
        $rating = null;
        $reviewCount = null;
        $businessName = null;

        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)/i', $html, $match)) {
            $businessName = html_entity_decode($match[1], ENT_QUOTES, 'UTF-8');
            $businessName = preg_replace('/\s*-\s*Google Maps\s*$/i', '', $businessName);
        }

        $ratingPatterns = [
            '/aria-label=["\']([0-5](?:[\.,][0-9])?)\s*(?:stars?|zvjezdica)/iu',
            '/"rating"\s*:\s*"?([0-5](?:\.[0-9])?)/i',
            '/([0-5](?:[\.,][0-9])?)\s*(?:stars?|zvjezdica)/iu',
        ];

        foreach ($ratingPatterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $rating = (float) str_replace(',', '.', $match[1]);
                break;
            }
        }

        $reviewPatterns = [
            '/aria-label=["\']([\d\s.,]+)\s*(?:reviews?|recenzija)/iu',
            '/"reviewCount"\s*:\s*"?(\d+)/i',
            '/([\d\s.,]+)\s*(?:reviews?|recenzija)/iu',
        ];

        foreach ($reviewPatterns as $pattern) {
            if (preg_match($pattern, $html, $match)) {
                $reviewCount = (int) preg_replace('/\D/', '', $match[1]);
                break;
            }
        }

        return [
            'rating' => $rating,
            'review_count' => $reviewCount,
            'business_name' => $businessName,
        ];
    }

    protected function extractProfileDataWithBrowser(string $url): array
    {
        $process = new Process([
            'node',
            base_path('scripts/google-reputation-scraper.mjs'),
            $url,
        ], base_path());
        $process->setTimeout(60);
        $process->run();

        if (!$process->isSuccessful()) {
            return ['rating' => null, 'review_count' => null, 'business_name' => null];
        }

        $data = json_decode($process->getOutput(), true);

        return is_array($data) ? $data : ['rating' => null, 'review_count' => null, 'business_name' => null];
    }

    protected function createChangeEvents(Competitor $competitor, ?CompetitorGoogleMetric $previous, CompetitorGoogleMetric $metric, string $url): void
    {
        if (!$previous) {
            return;
        }

        $ratingChange = $metric->getRatingChangeFrom($previous);
        if ($ratingChange) {
            CompetitorEvent::create([
                'competitor_id' => $competitor->id,
                'event_type' => 'rating_changed',
                'entity_type' => 'google_profile',
                'entity_id' => $metric->id,
                'detected_at' => $metric->captured_at,
                'old_value_json' => ['rating' => $ratingChange['old_rating']],
                'new_value_json' => ['rating' => $ratingChange['new_rating']],
                'evidence_url' => $url,
                'confidence' => 75,
                'importance_score' => 70,
            ]);
        }

        $reviewChange = $metric->getReviewCountChangeFrom($previous);
        if ($reviewChange) {
            CompetitorEvent::create([
                'competitor_id' => $competitor->id,
                'event_type' => 'new_review',
                'entity_type' => 'google_profile',
                'entity_id' => $metric->id,
                'detected_at' => $metric->captured_at,
                'old_value_json' => ['review_count' => $reviewChange['old_count']],
                'new_value_json' => ['review_count' => $reviewChange['new_count']],
                'evidence_url' => $url,
                'confidence' => 75,
                'importance_score' => 55,
            ]);
        }
    }
}
