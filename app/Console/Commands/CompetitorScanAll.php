<?php

namespace App\Console\Commands;

use App\Jobs\CompetitorIntelligence\ScanChangedUrls;
use App\Models\Competitor;
use Illuminate\Console\Command;

class CompetitorScanAll extends Command
{
    protected $signature = 'competitor:scan-all
                            {--sync : Run synchronously instead of dispatching to queue}';

    protected $description = 'Run deep scan for all active competitors';

    public function handle(): int
    {
        $competitors = Competitor::active()->get();
        
        $this->info("Running deep scan for {$competitors->count()} active competitors");

        foreach ($competitors as $competitor) {
            $this->line("  → Dispatching scan for: {$competitor->name}");
            
            if ($this->option('sync')) {
                $job = new ScanChangedUrls($competitor->id);
                $job->handle(
                    app(\App\Services\CompetitorIntelligence\PageExtractorService::class),
                    app(\App\Services\CompetitorIntelligence\PropertyExtractorService::class),
                    app(\App\Services\CompetitorIntelligence\UrlCollectorService::class),
                    app(\App\Services\CompetitorIntelligence\DiffService::class)
                );
            } else {
                ScanChangedUrls::dispatch($competitor->id);
            }
        }

        $this->info('All deep scan jobs dispatched');
        return Command::SUCCESS;
    }
}
