<?php

namespace App\Console\Commands;

use App\Jobs\CompetitorIntelligence\GenerateDailyCompetitorReport;
use App\Models\AgencyProfile;
use Illuminate\Console\Command;

class CompetitorDailyReport extends Command
{
    protected $signature = 'competitor:daily-report
                            {--agency= : Specific agency profile ID}
                            {--date= : Report date (YYYY-MM-DD), defaults to yesterday}
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Generate daily competitor intelligence report';

    public function handle(): int
    {
        $agencyId = $this->option('agency');
        $date = $this->option('date');

        if ($agencyId) {
            $agency = AgencyProfile::find($agencyId);
            if (!$agency) {
                $this->error("Agency profile {$agencyId} not found");
                return Command::FAILURE;
            }
            $this->info("Generating report for agency: {$agency->agency_name}");
        } else {
            $count = AgencyProfile::whereHas('competitors', fn($q) => $q->where('is_active', true))->count();
            $this->info("Generating reports for {$count} agencies with active competitors");
        }

        if ($this->option('sync')) {
            $job = new GenerateDailyCompetitorReport($agencyId, $date);
            $job->handle(app(\App\Services\CompetitorIntelligence\EventAnalysisService::class));
            $this->info('Report generation completed synchronously');
        } else {
            GenerateDailyCompetitorReport::dispatch($agencyId, $date);
            $this->info('Report generation job dispatched to queue');
        }

        return Command::SUCCESS;
    }
}
