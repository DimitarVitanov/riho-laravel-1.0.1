<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\Competitor;
use App\Models\CompetitorScanRun;
use App\Models\CompetitorUrl;
use App\Services\CompetitorIntelligence\DiffService;
use App\Services\CompetitorIntelligence\PageExtractorService;
use App\Services\CompetitorIntelligence\PropertyExtractorService;
use App\Services\CompetitorIntelligence\UrlCollectorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ScanChangedUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1800;

    protected int $competitorId;
    protected int $batchSize = 50;
    protected int $delayBetweenRequests = 2;

    public function __construct(int $competitorId)
    {
        $this->competitorId = $competitorId;
        // Use default queue for easier local development
        // $this->onQueue('competitor-deep-scan');
    }

    public function handle(
        PageExtractorService $pageExtractor,
        PropertyExtractorService $propertyExtractor,
        UrlCollectorService $urlCollector,
        DiffService $diffService
    ): void {
        $competitor = Competitor::find($this->competitorId);

        if (!$competitor || !$competitor->is_active) {
            return;
        }

        $lockKey = "competitor-scan:{$this->competitorId}";
        $lock = Cache::lock($lockKey, 1800);

        if (!$lock->get()) {
            Log::info("Scan already running for competitor {$this->competitorId}");
            return;
        }

        try {
            $scanRun = CompetitorScanRun::create([
                'competitor_id' => $competitor->id,
                'level' => 'deep_scan',
                'status' => 'pending',
            ]);

            $scanRun->markAsRunning();

            $urlsToScan = $this->getUrlsToScan($competitor);
            $scannedCount = 0;
            $changesCount = 0;

            foreach ($urlsToScan as $url) {
                try {
                    $url->page_type = $url->page_type ?? $urlCollector->classifyPageType($url->url);
                    $url->save();

                    if ($url->page_type === 'property_detail') {
                        $propertyData = $propertyExtractor->extractProperty($url);
                        if ($propertyData) {
                            $property = $propertyExtractor->createOrUpdateProperty($url, $propertyData);
                            $snapshot = $property->latestSnapshot;

                            if ($snapshot) {
                                $events = $diffService->detectPropertyChanges($property, $snapshot);
                                $changesCount += count($events);

                                foreach ($events as $event) {
                                    AnalyzeCompetitorEvent::dispatch($event->id)
                                        ->delay(now()->addSeconds(rand(5, 30)));
                                }
                            }
                        }
                    } else {
                        $snapshot = $pageExtractor->extractPage($url);
                        if ($snapshot) {
                            $events = $diffService->detectUrlChanges($url, $snapshot);
                            $changesCount += count($events);

                            foreach ($events as $event) {
                                AnalyzeCompetitorEvent::dispatch($event->id)
                                    ->delay(now()->addSeconds(rand(5, 30)));
                            }
                        }
                    }

                    $scannedCount++;
                    sleep($this->delayBetweenRequests);

                } catch (\Exception $e) {
                    Log::warning("Failed to scan URL {$url->url}", ['error' => $e->getMessage()]);
                }
            }

            $removedEvents = $diffService->detectRemovedProperties($competitor);
            $changesCount += count($removedEvents);

            $confirmedEvents = $diffService->confirmRemovedProperties($competitor);
            $changesCount += count($confirmedEvents);

            $scanRun->markAsSuccess($scannedCount, $changesCount);

            Log::info("Deep scan completed for {$competitor->name}", [
                'scanned' => $scannedCount,
                'changes' => $changesCount,
            ]);

        } catch (\Exception $e) {
            if (isset($scanRun)) {
                $scanRun->markAsFailed($e->getMessage());
            }
            Log::error("Deep scan failed for competitor {$this->competitorId}", ['error' => $e->getMessage()]);
        } finally {
            $lock->release();
        }
    }

    protected function getUrlsToScan(Competitor $competitor): \Illuminate\Support\Collection
    {
        $recentlyChanged = $competitor->urls()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('last_seen_at')
                    ->orWhere('sitemap_lastmod', '>', now()->subDays(7))
                    ->orWhereDoesntHave('snapshots');
            })
            ->limit($this->batchSize)
            ->get();

        if ($recentlyChanged->count() < $this->batchSize) {
            $remaining = $this->batchSize - $recentlyChanged->count();

            $stale = $competitor->urls()
                ->where('status', 'active')
                ->whereNotIn('id', $recentlyChanged->pluck('id'))
                ->orderBy('last_seen_at', 'asc')
                ->limit($remaining)
                ->get();

            $recentlyChanged = $recentlyChanged->merge($stale);
        }

        return $recentlyChanged;
    }
}
