<?php

namespace App\Jobs\CompetitorIntelligence;

use App\Models\AgencyProfile;
use App\Services\CompetitorIntelligence\EventAnalysisService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateDailyCompetitorReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 300;

    protected ?int $agencyProfileId;
    protected ?string $date;

    public function __construct(?int $agencyProfileId = null, ?string $date = null)
    {
        $this->agencyProfileId = $agencyProfileId;
        $this->date = $date;
        // Use default queue for easier local development
        // $this->onQueue('competitor-reports');
    }

    public function handle(EventAnalysisService $analysisService): void
    {
        $date = $this->date ? Carbon::parse($this->date) : Carbon::yesterday();

        if ($this->agencyProfileId) {
            $this->generateForAgency($this->agencyProfileId, $date, $analysisService);
        } else {
            $agencies = AgencyProfile::whereHas('competitors', function ($query) {
                $query->where('is_active', true);
            })->get();

            foreach ($agencies as $agency) {
                $this->generateForAgency($agency->id, $date, $analysisService);
            }
        }
    }

    protected function generateForAgency(
        int $agencyProfileId,
        Carbon $date,
        EventAnalysisService $analysisService
    ): void {
        try {
            $report = $analysisService->generateDailyReport($agencyProfileId, $date);

            if ($report) {
                Log::info("Daily report generated for agency {$agencyProfileId}", [
                    'date' => $date->toDateString(),
                    'report_id' => $report->id,
                ]);
            } else {
                Log::info("No events for daily report", [
                    'agency_id' => $agencyProfileId,
                    'date' => $date->toDateString(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Daily report generation failed", [
                'agency_id' => $agencyProfileId,
                'date' => $date->toDateString(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
