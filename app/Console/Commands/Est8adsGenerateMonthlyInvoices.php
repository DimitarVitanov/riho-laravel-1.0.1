<?php

namespace App\Console\Commands;

use App\Models\Est8ads\Profile;
use App\Services\Est8ads\BillingService;
use Illuminate\Console\Command;

class Est8adsGenerateMonthlyInvoices extends Command
{
    protected $signature = 'est8ads:generate-monthly-invoices';

    protected $description = 'Opens the next $12/month invoice for every individual EST8ADS user whose previous billing period has elapsed.';

    public function handle(BillingService $billing): int
    {
        $created = 0;

        Profile::where('type', 'individual')
            ->whereNull('deleted_at')
            ->chunkById(200, function ($profiles) use ($billing, &$created) {
                foreach ($profiles as $profile) {
                    if ($billing->createNextInvoiceIfDue($profile)) {
                        $created++;
                    }
                }
            });

        $this->info("Generated {$created} invoice(s).");

        return self::SUCCESS;
    }
}
