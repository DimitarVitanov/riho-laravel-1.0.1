<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\Competitor;
use App\Services\CompetitorIntelligence\GoogleReputationScraperService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class RefreshCompetitorReputation implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 90;
    public int $backoff = 120;
    public int $uniqueFor = 300;

    public function __construct(protected int $competitorId)
    {
    }

    public function uniqueId(): string
    {
        return (string) $this->competitorId;
    }

    public function handle(GoogleReputationScraperService $scraper): void
    {
        $competitor = Competitor::find($this->competitorId);

        if (!$competitor || !$competitor->is_active) {
            return;
        }

        if (!$competitor->google_maps_url && !$competitor->google_place_id) {
            return;
        }

        $metric = $scraper->refresh($competitor);

        Log::info('Google reputation snapshot captured', [
            'competitor_id' => $competitor->id,
            'metric_id' => $metric->id,
            'rating' => $metric->rating,
            'review_count' => $metric->review_count,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Google reputation refresh failed', [
            'competitor_id' => $this->competitorId,
            'error' => $exception?->getMessage(),
        ]);
    }
}
