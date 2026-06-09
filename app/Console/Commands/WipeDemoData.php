<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
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
            $this->warn('This will permanently delete all non-admin users and their data.');
            if (!$this->confirm('Are you sure you want to continue?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        $adminRoles = ['super_admin', 'admin'];

        $nonAdminUsers = User::whereNotIn('role', $adminRoles)->get();
        $count = $nonAdminUsers->count();

        if ($count === 0) {
            $this->info('No demo/test users found. Nothing to wipe.');
            return self::SUCCESS;
        }

        $this->info("Found {$count} non-admin user(s). Wiping associated data...");

        $userIds = $nonAdminUsers->pluck('id')->toArray();
        $emails = $nonAdminUsers->pluck('email')->toArray();

        $tables = [
            ['table' => 'agency_profiles', 'column' => 'user_id'],
            ['table' => 'investor_profiles', 'column' => 'user_id'],
            ['table' => 'agency_feature_toggles', 'column' => 'user_id'],
            ['table' => 'agency_language_settings', 'column' => 'user_id'],
            ['table' => 'usage_limits', 'column' => 'user_id'],
            ['table' => 'ai_reports', 'column' => 'user_id'],
            ['table' => 'generated_pages', 'column' => 'user_id'],
            ['table' => 'leads', 'column' => 'user_id'],
            ['table' => 'support_tickets', 'column' => 'user_id'],
            ['table' => 'support_ticket_messages', 'column' => 'user_id'],
        ];

        DB::transaction(function () use ($userIds, $emails, $tables) {
            foreach ($tables as $t) {
                if (Schema::hasTable($t['table'])) {
                    DB::table($t['table'])->whereIn($t['column'], $userIds)->delete();
                }
            }

            if (Schema::hasTable('affiliate_referrals')) {
                DB::table('affiliate_referrals')->whereIn('referrer_id', $userIds)->orWhereIn('referee_id', $userIds)->delete();
            }
            if (Schema::hasTable('manager_agency')) {
                DB::table('manager_agency')->whereIn('manager_id', $userIds)->orWhereIn('agency_id', $userIds)->delete();
            }
            if (Schema::hasTable('manager_investor')) {
                DB::table('manager_investor')->whereIn('manager_id', $userIds)->orWhereIn('investor_id', $userIds)->delete();
            }
            if (Schema::hasTable('password_reset_tokens')) {
                DB::table('password_reset_tokens')->whereIn('email', $emails)->delete();
            }

            User::whereIn('id', $userIds)->delete();
        });

        $this->info("✓ Wiped {$count} user(s) and all associated records.");

        return self::SUCCESS;
    }
}
