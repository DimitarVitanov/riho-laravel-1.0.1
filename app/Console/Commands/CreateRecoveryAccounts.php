<?php

namespace App\Console\Commands;

use App\Models\AgencyProfile;
use App\Models\AiFeatureSetting;
use App\Models\Est8ads\Profile as Est8adsProfile;
use App\Models\InvestorProfile;
use App\Models\UsageLimit;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateRecoveryAccounts extends Command
{
    protected $signature = 'accounts:create-recovery {--password=}';

    protected $description = 'Create or update the default administrator, agency, and investor accounts';

    public function handle(): int
    {
        $password = (string) $this->option('password');
        if (strlen($password) < 8) {
            $this->error('Provide a password of at least 8 characters with --password.');
            return self::FAILURE;
        }

        DB::transaction(function () use ($password) {
            $now = now();
            $common = [
                'password' => Hash::make($password),
                'status' => 'active',
                'email_verified_at' => $now,
                'privacy_accepted_at' => $now,
                'terms_accepted_at' => $now,
                'has_villabit_access' => true,
                'has_est8ads_access' => true,
            ];

            User::withTrashed()->whereIn('email', [
                'admin@villabit.ai',
                'agency@villabit.ai',
                'investor@villabit.ai',
            ])->restore();

            $admin = User::updateOrCreate(['email' => 'admin@villabit.ai'], $common + [
                'first_name' => 'Villa Bit',
                'last_name' => 'Admin',
                'company_name' => 'Villa Bit AI',
                'country' => 'Croatia',
                'role' => 'super_admin',
                'account_type' => null,
                'is_reseller_enabled' => true,
                'is_affiliate_enabled' => true,
                'is_investor_enabled' => true,
                'is_agency_enabled' => true,
                'referral_code' => 'ADMIN',
            ]);

            $agencyUser = User::updateOrCreate(['email' => 'agency@villabit.ai'], $common + [
                'first_name' => 'Villa Bit',
                'last_name' => 'Agency',
                'company_name' => 'Villa Bit Agency',
                'country' => 'Croatia',
                'role' => 'real_estate_agency',
                'account_type' => 'real_estate_agency',
                'agency_server_type' => 'subdomain_ai_server',
                'agency_server_price' => 0,
                'is_reseller_enabled' => true,
                'is_affiliate_enabled' => true,
                'is_investor_enabled' => true,
                'is_agency_enabled' => true,
                'referral_code' => 'AGENCY',
                'onboarding_step' => User::ONBOARDING_COMPLETED,
                'onboarding_step_updated_at' => $now,
            ]);

            $agency = AgencyProfile::updateOrCreate(['user_id' => $agencyUser->id], [
                'agency_name' => 'Villa Bit Agency',
                'country' => 'Croatia',
                'city' => 'Split',
                'main_service_area' => 'Croatia',
                'target_city' => 'Split',
                'target_radius_km' => 100,
                'contact_email' => $agencyUser->email,
                'subscription_status' => 'active',
                'ai_status' => 'active',
                'foreign_buyer_support' => true,
                'property_management_support' => true,
            ]);

            foreach ([
                'daily_ai_employee',
                'invisible_lead_magnet',
                'local_seo_presence_boost',
                'ai_search_ranking',
                'daily_competitor_scan',
                'ai_authority_builder',
                'small_ai_actions',
                'affiliate_sale',
            ] as $feature) {
                AiFeatureSetting::updateOrCreate(
                    ['agency_profile_id' => $agency->id, 'feature_key' => $feature],
                    ['is_enabled' => true, 'frequency' => 'daily']
                );
            }

            UsageLimit::updateOrCreate(
                ['agency_profile_id' => $agency->id, 'period_start' => $now->copy()->startOfMonth()],
                [
                    'period_end' => $now->copy()->endOfMonth(),
                    'ai_search_ranking_limit' => 999999,
                    'ai_search_ranking_used' => 0,
                    'local_seo_pages_limit' => 999999,
                    'local_seo_pages_used' => 0,
                    'competitor_scans_limit' => 999999,
                    'competitor_scans_used' => 0,
                    'ai_search_freshness_updates_limit' => 999999,
                    'ai_search_freshness_updates_used' => 0,
                    'authority_review_updates_limit' => 999999,
                    'authority_review_updates_used' => 0,
                    'small_ai_content_actions_limit' => 999999,
                    'small_ai_content_actions_used' => 0,
                ]
            );

            $investorUser = User::updateOrCreate(['email' => 'investor@villabit.ai'], $common + [
                'first_name' => 'Villa Bit',
                'last_name' => 'Investor',
                'country' => 'Croatia',
                'role' => 'investor',
                'account_type' => 'investor',
                'is_reseller_enabled' => true,
                'is_affiliate_enabled' => true,
                'is_investor_enabled' => true,
                'is_agency_enabled' => false,
                'referral_code' => 'INVESTOR',
            ]);

            InvestorProfile::updateOrCreate(['user_id' => $investorUser->id], [
                'investor_type' => 'non_us_eligible',
                'citizenship_country' => 'Croatia',
                'residence_country' => 'Croatia',
                'eligible_structure' => 'pending_review',
                'accreditation_status' => 'verified',
                'kyc_status' => 'approved',
                'aml_status' => 'approved',
                'preferred_currency' => 'EUR',
                'tax_form_status' => 'approved',
                'risk_acknowledgement_at' => $now,
                'terms_accepted_at' => $now,
                'onboarding_phase' => 'approved',
                'kyc_submitted_at' => $now,
                'kyc_approved_at' => $now,
                'investing_own_name_confirmed' => true,
                'beneficial_owner_disclosed' => true,
                'sanctions_clear_confirmed' => true,
                'third_party_funds_excluded' => true,
                'crs_certified' => true,
                'tax_advice_confirmed' => true,
                'risk_acknowledgement_signed' => true,
            ]);

            Est8adsProfile::updateOrCreate(['user_id' => $investorUser->id], [
                'type' => 'individual',
                'status' => 'active',
                'first_name' => $investorUser->first_name,
                'last_name' => $investorUser->last_name,
                'email' => $investorUser->email,
                'public_reference' => Est8adsProfile::where('user_id', $investorUser->id)->value('public_reference') ?: 'EST-' . strtoupper(Str::random(12)),
                'consent_at' => $now,
                'metadata' => ['source' => 'recovery_account'],
            ]);

            $this->line("Created administrator #{$admin->id}, agency #{$agencyUser->id}, and investor #{$investorUser->id}.");
        });

        return self::SUCCESS;
    }
}
