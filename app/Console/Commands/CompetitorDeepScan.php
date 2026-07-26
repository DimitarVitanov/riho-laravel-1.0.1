<?php

namespace App\Console\Commands;

use App\Jobs\CompetitorIntelligence\ScanChangedUrls;
use App\Models\Competitor;
use Illuminate\Console\Command;

class CompetitorDeepScan extends Command
{
    protected $signature = 'competitor:scan
                            {competitor : Competitor ID to scan}
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Run deep scan for a specific competitor (page extraction and diff detection)';

    public function handle(): int
    {
        $competitorId = $this->argument('competitor');

        $competitor = Competitor::find($competitorId);
        if (!$competitor) {
            $this->error("Competitor {$competitorId} not found");
            return Command::FAILURE;
        }

        $this->info("Running deep scan for: {$competitor->name}");

        if ($this->option('sync')) {
            $job = new ScanChangedUrls($competitorId);
            $job->handle(
                app(\App\Services\CompetitorIntelligence\PageExtractorService::class),
                app(\App\Services\CompetitorIntelligence\PropertyExtractorService::class),
                app(\App\Services\CompetitorIntelligence\UrlCollectorService::class),
                app(\App\Services\CompetitorIntelligence\DiffService::class)
            );
            $this->info('Deep scan completed synchronously');
        } else {
            ScanChangedUrls::dispatch($competitorId);
            $this->info('Deep scan job dispatched to queue');
        }

        return Command::SUCCESS;
    }
}
