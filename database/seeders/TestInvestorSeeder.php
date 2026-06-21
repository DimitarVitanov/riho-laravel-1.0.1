<?php

namespace Database\Seeders;

use App\Models\AffiliateReferral;
use App\Models\InvestorProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestInvestorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'investor@test.com'],
            [
                'first_name'         => 'Test',
                'last_name'          => 'Investor',
                'password'           => Hash::make('password'),
                'phone'              => '+385991234567',
                'country'            => 'Croatia',
                'account_type'       => 'investor',
                'role'               => 'investor',
                'status'             => 'active',
                'email_verified_at'  => now(),
            ]
        );

        InvestorProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'investor_type'               => 'non_us_eligible',
                'citizenship_country'         => 'Croatia',
                'residence_country'           => 'Croatia',
                'kyc_status'                  => 'approved',
                'aml_status'                  => 'approved',
                'preferred_currency'          => 'EUR',
                'eligible_structure'          => 'uk_llp',
                'onboarding_phase'            => 'approved',
                'accreditation_status'        => 'verified',
                // Personal
                'date_of_birth'               => '1985-06-15',
                'place_of_birth'              => 'Zagreb',
                'country_of_birth'            => 'Croatia',
                'all_citizenships'            => ['Croatia'],
                'permanent_address'           => 'Ilica 10, Zagreb, Croatia',
                'occupation'                  => 'Business Owner',
                'employer_name'               => 'Test Company d.o.o.',
                'job_title'                   => 'Director',
                'id_document_type'            => 'passport',
                'id_document_number'          => 'TEST123456',
                'id_document_issuing_country' => 'Croatia',
                'id_document_expiry_date'     => '2030-01-01',
                // Jurisdiction
                'is_us_person'                => false,
                'is_us_citizen'               => false,
                'has_us_green_card'           => false,
                'is_us_tax_resident'          => false,
                'has_us_ssn_or_itin'          => false,
                'investing_through_us_entity' => false,
                'all_tax_residences'          => ['Croatia'],
                'country_when_received_info'  => 'Croatia',
                'country_when_signing'        => 'Croatia',
                // AML
                'investing_own_name_confirmed' => true,
                'is_pep'                      => false,
                'sanctions_clear_confirmed'   => true,
                'third_party_funds_excluded'  => true,
                // Source of funds
                'source_of_funds'             => 'Business income',
                'source_of_wealth'            => 'Business ownership',
                // Tax
                'fatca_status'                => 'non_us',
                'crs_certified'               => true,
                'tax_advice_confirmed'        => true,
                // Banking
                'bank_name'                   => 'Zagrebačka banka',
                'bank_country'                => 'Croatia',
                'investment_currency'         => 'EUR',
                'max_commitment_amount'       => 50000.00,
                // UK LLP
                'not_us_person_confirmed'     => true,
                'local_investor_classification' => 'sophisticated',
                'local_legal_advice_confirmed'  => true,
                'participation_permitted_locally' => true,
                'risk_acknowledgement_signed' => true,
                // Entity
                'investor_entity_type'        => 'individual',
                // Timestamps
                'kyc_submitted_at'            => now()->subDays(10),
                'kyc_approved_at'             => now()->subDays(5),
            ]
        );

        // Create affiliate referral record for the investor
        // First ensure there's a reseller to link to (use admin or create one)
        $resellerUser = User::where('role', 'admin')->first();
        if ($resellerUser) {
            AffiliateReferral::firstOrCreate(
                ['converted_user_id' => $user->id],
                [
                    'reseller_user_id'       => $resellerUser->id,
                    'referral_code'          => 'TEST-REF-' . strtoupper(Str::random(6)),
                    'cookie_expires_at'      => now()->addDays(180),
                    'converted_account_type' => 'investor',
                    'converted_at'           => now()->subDays(10),
                    'status'                 => 'converted',
                ]
            );
        }

        $this->command->info('Test investor created/updated: investor@test.com / password');
    }
}
