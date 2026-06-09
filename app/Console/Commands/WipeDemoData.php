<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeDemoData extends Command
{
    protected $signature = 'villabit:wipe-demo
                            {--force : Skip confirmation prompt}';

    protected $description = 'Wipe all non-admin users and their associated data (demo/test data cleanup)';

    public function handle(): int
    {
        if (!$this->option('force')) {
            $this->warn('This will permanently delete demo/test data from all related tables (users are NOT deleted).');
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        $tables = [
            'agency_profiles',
            'investor_profiles',
            'agency_feature_toggles',
            'agency_language_settings',
            'usage_limits',
            'ai_reports',
            'generated_pages',
            'leads',
            'support_tickets',
            'support_ticket_messages',
            'affiliate_referrals',
            'affiliate_commissions',
            'affiliate_payouts',
            'investor_investments',
            'investor_payouts',
            'capital_calls',
            'investment_projects',
            'manager_agency',
            'manager_investor',
            'landing_pages',
            'password_reset_tokens',
        ];

        $wiped = [];

        DB::transaction(function () use ($tables, &$wiped) {
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $deleted = DB::table($table)->count();
                    if ($deleted > 0) {
                        DB::table($table)->delete();
                        $wiped[] = "{$table} ({$deleted} rows)";
                    }
                }
            }
        });

        if (empty($wiped)) {
            $this->info('No demo data found. All tables are already empty.');
        } else {
            $this->info('✓ Wiped data from:');
            foreach ($wiped as $entry) {
                $this->line("  - {$entry}");
            }
        }

        return self::SUCCESS;
    }
}
