<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($userIds) {
            DB::table('agency_profiles')->whereIn('user_id', $userIds)->delete();
            DB::table('investor_profiles')->whereIn('user_id', $userIds)->delete();
            DB::table('agency_feature_toggles')->whereIn('user_id', $userIds)->delete();
            DB::table('agency_language_settings')->whereIn('user_id', $userIds)->delete();
            DB::table('usage_limits')->whereIn('user_id', $userIds)->delete();
            DB::table('ai_reports')->whereIn('user_id', $userIds)->delete();
            DB::table('generated_pages')->whereIn('user_id', $userIds)->delete();
            DB::table('leads')->whereIn('user_id', $userIds)->delete();
            DB::table('affiliate_referrals')->whereIn('referrer_id', $userIds)->orWhereIn('referee_id', $userIds)->delete();
            DB::table('support_notes')->whereIn('user_id', $userIds)->delete();
            DB::table('manager_agency')->whereIn('manager_id', $userIds)->orWhereIn('agency_id', $userIds)->delete();
            DB::table('manager_investor')->whereIn('manager_id', $userIds)->orWhereIn('investor_id', $userIds)->delete();
            DB::table('password_reset_tokens')->whereIn('email', User::whereIn('id', $userIds)->pluck('email')->toArray())->delete();

            User::whereIn('id', $userIds)->delete();
        });

        $this->info("✓ Wiped {$count} user(s) and all associated records.");
        $this->line('Tables cleaned: agency_profiles, investor_profiles, feature_toggles, language_settings, usage_limits, ai_reports, generated_pages, leads, affiliate_referrals, support_notes, manager assignments, password_reset_tokens.');

        return self::SUCCESS;
    }
}
