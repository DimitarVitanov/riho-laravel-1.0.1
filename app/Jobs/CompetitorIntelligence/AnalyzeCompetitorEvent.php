<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\CompetitorEvent;
use App\Services\CompetitorIntelligence\EventAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzeCompetitorEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;
    public int $backoff = 60;

    protected int $eventId;

    public function __construct(int $eventId)
    {
        $this->eventId = $eventId;
        // Use default queue for easier local development
        // $this->onQueue('competitor-ai');
    }

    public function handle(EventAnalysisService $analysisService): void
    {
        $event = CompetitorEvent::find($this->eventId);

        if (!$event) {
            Log::warning("Event {$this->eventId} not found for analysis");
            return;
        }

        if ($event->ai_interpretation) {
            Log::info("Event {$this->eventId} already analyzed");
            return;
        }

        try {
            $analysisService->analyzeEvent($event);
            Log::info("Event {$this->eventId} analyzed successfully");
        } catch (\Exception $e) {
            Log::error("Event analysis failed for {$this->eventId}", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
