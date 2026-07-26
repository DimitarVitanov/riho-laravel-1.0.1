<?php

namespace App\Console\Commands;

use App\Jobs\CompetitorIntelligence\RunCompetitorDiscoveryCycle;
use App\Models\Competitor;
use Illuminate\Console\Command;

class CompetitorDiscoveryScan extends Command
{
    protected $signature = 'competitor:discover
                            {--competitor= : Specific competitor ID to scan}
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Run competitor URL discovery scan (sitemap crawling)';

    public function handle(): int
    {
        $competitorId = $this->option('competitor');

        if ($competitorId) {
            $competitor = Competitor::find($competitorId);
            if (!$competitor) {
                $this->error("Competitor {$competitorId} not found");
                return Command::FAILURE;
            }
            $this->info("Running discovery for: {$competitor->name}");
        } else {
            $count = Competitor::active()->count();
            $this->info("Running discovery for {$count} active competitors");
        }

        if ($this->option('sync')) {
            $job = new RunCompetitorDiscoveryCycle($competitorId);
            $job->handle(app(\App\Services\CompetitorIntelligence\UrlCollectorService::class));
            $this->info('Discovery completed synchronously');
        } else {
            RunCompetitorDiscoveryCycle::dispatch($competitorId);
            $this->info('Discovery job dispatched to queue');
        }

        return Command::SUCCESS;
    }
}
