<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('investor_profiles', function (Blueprint $table) {

            // --- A. PERSONAL IDENTIFICATION ---
            $table->text('previous_names')->nullable()->after('residence_country');
            $table->date('date_of_birth')->nullable()->after('previous_names');
            $table->string('place_of_birth')->nullable()->after('date_of_birth');
            $table->string('country_of_birth')->nullable()->after('place_of_birth');
            $table->json('all_citizenships')->nullable()->after('country_of_birth');
            $table->text('permanent_address')->nullable()->after('all_citizenships');
            $table->text('mailing_address')->nullable()->after('permanent_address');
            $table->string('occupation')->nullable()->after('mailing_address');
            $table->string('employer_name')->nullable()->after('occupation');
            $table->string('job_title')->nullable()->after('employer_name');
            $table->string('id_document_type')->nullable()->after('job_title');
            $table->string('id_document_number')->nullable()->after('id_document_type');
            $table->string('id_document_issuing_country')->nullable()->after('id_document_number');
            $table->date('id_document_expiry_date')->nullable()->after('id_document_issuing_country');

            // --- B. ROUTING AND JURISDICTION ---
            $table->boolean('is_us_person')->nullable()->after('id_document_expiry_date');
            $table->boolean('is_us_citizen')->nullable()->after('is_us_person');
            $table->boolean('has_us_green_card')->nullable()->after('is_us_citizen');
            $table->boolean('is_us_tax_resident')->nullable()->after('has_us_green_card');
            $table->boolean('has_us_ssn_or_itin')->nullable()->after('is_us_tax_resident');
            $table->boolean('investing_through_us_entity')->nullable()->after('has_us_ssn_or_itin');
            $table->json('all_tax_residences')->nullable()->after('investing_through_us_entity');
            $table->json('tax_id_numbers')->nullable()->after('all_tax_residences');
            $table->string('country_when_received_info')->nullable()->after('tax_id_numbers');
            $table->string('country_when_discussing')->nullable()->after('country_when_received_info');
            $table->string('country_when_signing')->nullable()->after('country_when_discussing');
            $table->string('country_when_sending_funds')->nullable()->after('country_when_signing');

            // --- C. AML / KYC / PEP / SANCTIONS ---
            $table->boolean('investing_own_name_confirmed')->nullable()->after('country_when_sending_funds');
            $table->boolean('beneficial_owner_disclosed')->nullable()->after('investing_own_name_confirmed');
            $table->boolean('is_pep')->nullable()->after('beneficial_owner_disclosed');
            $table->json('pep_details')->nullable()->after('is_pep');
            $table->boolean('sanctions_clear_confirmed')->nullable()->after('pep_details');
            $table->boolean('third_party_funds_excluded')->nullable()->after('sanctions_clear_confirmed');

            // --- D. SOURCE OF FUNDS AND WEALTH ---
            $table->string('source_of_funds')->nullable()->after('third_party_funds_excluded');
            $table->text('source_of_funds_details')->nullable()->after('source_of_funds');
            $table->string('source_of_wealth')->nullable()->after('source_of_funds_details');
            $table->text('source_of_wealth_details')->nullable()->after('source_of_wealth');

            // --- E. TAX INFORMATION ---
            $table->string('fatca_status')->nullable()->after('source_of_wealth_details');
            $table->boolean('crs_certified')->nullable()->after('fatca_status');
            $table->boolean('tax_advice_confirmed')->nullable()->after('crs_certified');

            // --- F. BANKING ---
            $table->string('bank_name')->nullable()->after('tax_advice_confirmed');
            $table->string('bank_account_holder')->nullable()->after('bank_name');
            $table->string('bank_iban')->nullable()->after('bank_account_holder');
            $table->string('bank_swift_bic')->nullable()->after('bank_iban');
            $table->string('bank_country')->nullable()->after('bank_swift_bic');
            $table->boolean('bank_account_verified')->default(false)->after('bank_country');
            $table->string('investment_currency', 10)->nullable()->after('bank_account_verified');

            // --- USA LLC SPECIFIC ---
            $table->text('us_residential_address')->nullable()->after('investment_currency');
            $table->string('us_state')->nullable()->after('us_residential_address');
            $table->boolean('w9_signed')->nullable()->after('us_state');
            $table->boolean('accredited_questionnaire_signed')->nullable()->after('w9_signed');
            $table->boolean('subscription_agreement_signed')->nullable()->after('accredited_questionnaire_signed');
            $table->boolean('llc_agreement_signed')->nullable()->after('subscription_agreement_signed');
            $table->boolean('bad_actor_questionnaire_signed')->nullable()->after('llc_agreement_signed');
            $table->enum('accredited_verification_method', ['income_test', 'net_worth_test', 'third_party_letter'])->nullable()->after('bad_actor_questionnaire_signed');

            // --- UK LLP SPECIFIC ---
            $table->boolean('not_us_person_confirmed')->nullable()->after('accredited_verification_method');
            $table->string('local_investor_classification')->nullable()->after('not_us_person_confirmed');
            $table->boolean('local_legal_advice_confirmed')->nullable()->after('local_investor_classification');
            $table->boolean('participation_permitted_locally')->nullable()->after('local_legal_advice_confirmed');
            $table->boolean('llp_agreement_signed')->nullable()->after('participation_permitted_locally');
            $table->boolean('admission_agreement_signed')->nullable()->after('llp_agreement_signed');
            $table->boolean('capital_call_agreement_signed')->nullable()->after('admission_agreement_signed');
            $table->boolean('risk_acknowledgement_signed')->nullable()->after('capital_call_agreement_signed');

            // --- ENTITY INVESTOR ---
            $table->enum('investor_entity_type', ['individual', 'company', 'trust', 'fund', 'family_office'])->default('individual')->after('risk_acknowledgement_signed');
            $table->string('entity_legal_name')->nullable()->after('investor_entity_type');
            $table->string('entity_registration_number')->nullable()->after('entity_legal_name');
            $table->string('entity_country_of_incorporation')->nullable()->after('entity_registration_number');
            $table->text('entity_registered_address')->nullable()->after('entity_country_of_incorporation');
            $table->string('entity_tax_id')->nullable()->after('entity_registered_address');

            // --- ONBOARDING PHASE ---
            $table->enum('onboarding_phase', ['initial', 'eligibility_review', 'kyc_portal', 'documents_review', 'approved', 'rejected'])->default('initial')->after('entity_tax_id');
            $table->timestamp('kyc_submitted_at')->nullable()->after('onboarding_phase');
            $table->timestamp('kyc_approved_at')->nullable()->after('kyc_submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('investor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'previous_names', 'date_of_birth', 'place_of_birth', 'country_of_birth',
                'all_citizenships', 'permanent_address', 'mailing_address', 'occupation',
                'employer_name', 'job_title', 'id_document_type', 'id_document_number',
                'id_document_issuing_country', 'id_document_expiry_date',
                'is_us_person', 'is_us_citizen', 'has_us_green_card', 'is_us_tax_resident',
                'has_us_ssn_or_itin', 'investing_through_us_entity', 'all_tax_residences',
                'tax_id_numbers', 'country_when_received_info', 'country_when_discussing',
                'country_when_signing', 'country_when_sending_funds',
                'investing_own_name_confirmed', 'beneficial_owner_disclosed', 'is_pep',
                'pep_details', 'sanctions_clear_confirmed', 'third_party_funds_excluded',
                'source_of_funds', 'source_of_funds_details', 'source_of_wealth', 'source_of_wealth_details',
                'fatca_status', 'crs_certified', 'tax_advice_confirmed',
                'bank_name', 'bank_account_holder', 'bank_iban', 'bank_swift_bic',
                'bank_country', 'bank_account_verified', 'investment_currency',
                'us_residential_address', 'us_state', 'w9_signed', 'accredited_questionnaire_signed',
                'subscription_agreement_signed', 'llc_agreement_signed', 'bad_actor_questionnaire_signed',
                'accredited_verification_method',
                'not_us_person_confirmed', 'local_investor_classification', 'local_legal_advice_confirmed',
                'participation_permitted_locally', 'llp_agreement_signed', 'admission_agreement_signed',
                'capital_call_agreement_signed', 'risk_acknowledgement_signed',
                'investor_entity_type', 'entity_legal_name', 'entity_registration_number',
                'entity_country_of_incorporation', 'entity_registered_address', 'entity_tax_id',
                'onboarding_phase', 'kyc_submitted_at', 'kyc_approved_at',
            ]);
        });
    }
};
