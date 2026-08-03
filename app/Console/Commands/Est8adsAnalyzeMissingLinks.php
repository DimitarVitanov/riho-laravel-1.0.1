<?php

namespace App\Console\Commands;

use App\Jobs\Est8ads\AnalyzeMissingLinks;
use Illuminate\Console\Command;

class Est8adsAnalyzeMissingLinks extends Command
{
    protected $signature = 'est8ads:analyze-missing-links {--sync : Run immediately instead of queueing the job}';

    protected $description = 'Ask the AI what buyer, seller, property or condition is blocking each stalled EST8ADS request from becoming a chain.';

    public function handle(): int
    {
        if ($this->option('sync')) {
            AnalyzeMissingLinks::dispatchSync();
        } else {
            AnalyzeMissingLinks::dispatch();
        }

        $this->info('Missing-link analysis queued.');

        return self::SUCCESS;
    }
}
