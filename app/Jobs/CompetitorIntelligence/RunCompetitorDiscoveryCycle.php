<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorScanRun;
use App\Services\CompetitorIntelligence\UrlCollectorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunCompetitorDiscoveryCycle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    protected ?int $competitorId;

    public function __construct(?int $competitorId = null)
    {
        $this->competitorId = $competitorId;
        // Use default queue for easier local development
        // $this->onQueue('competitor-discovery');
    }

    public function handle(UrlCollectorService $urlCollector): void
    {
        $competitors = $this->competitorId
            ? Competitor::where('id', $this->competitorId)->active()->get()
            : Competitor::active()->get();

        foreach ($competitors as $competitor) {
            $this->processCompetitor($competitor, $urlCollector);
        }
    }

    protected function processCompetitor(Competitor $competitor, UrlCollectorService $urlCollector): void
    {
        $scanRun = CompetitorScanRun::create([
            'competitor_id' => $competitor->id,
            'level' => 'discovery',
            'status' => 'pending',
        ]);

        try {
            $scanRun->markAsRunning();

            $results = $urlCollector->collectFromSitemap($competitor);

            $scanRun->markAsSuccess(
                $results['total'],
                $results['new']
            );

            $competitor->update(['last_scan_at' => now()]);

            if ($results['new'] > 0) {
                ScanChangedUrls::dispatch($competitor->id)
                    ->delay(now()->addMinutes(1));
            }

            Log::info("Discovery completed for {$competitor->name}", $results);

        } catch (\Exception $e) {
            $scanRun->markAsFailed($e->getMessage());
            Log::error("Discovery failed for {$competitor->name}", ['error' => $e->getMessage()]);
        }
    }
}
