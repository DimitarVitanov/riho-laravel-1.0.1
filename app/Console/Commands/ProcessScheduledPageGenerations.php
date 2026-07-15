<?php

namespace App\Console\Commands;

use App\Models\ScheduledPageGeneration;
use App\Jobs\ProcessScheduledPageJob;
use Illuminate\Console\Command;

class ProcessScheduledPageGenerations extends Command
{
    protected $signature = 'pages:process-scheduled';
    protected $description = 'Dispatch jobs for scheduled page generations (run daily via cron)';

    public function handle()
    {
        $this->info('Dispatching scheduled page generation jobs...');
        
        // Get all pending items due today or earlier
        $scheduled = ScheduledPageGeneration::pending()
            ->dueToday()
            ->get();
        
        if ($scheduled->isEmpty()) {
            $this->info('No scheduled pages to process.');
            return 0;
        }
        
        $dispatched = 0;
        
        foreach ($scheduled as $item) {
            // Dispatch a job for each item - the job handles daily limits
            ProcessScheduledPageJob::dispatch($item->id);
            $dispatched++;
            $this->info("Dispatched job for: {$item->place_name}");
        }
        
        $this->info("Dispatched {$dispatched} jobs to queue.");
        return 0;
    }
}
