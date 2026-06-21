<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investor_type',
        'citizenship_country',
        'residence_country',
        'eligible_structure',
        'accreditation_status',
        'kyc_status',
        'aml_status',
        'max_commitment_amount',
        'preferred_currency',
        'payout_method',
        'payout_details_encrypted',
        'tax_form_status',
        'investor_notes',
        'risk_acknowledgement_at',
        'terms_accepted_at',
        'assigned_manager_id',
        // Personal identification
        'previous_names',
        'date_of_birth',
        'place_of_birth',
        'country_of_birth',
        'all_citizenships',
        'permanent_address',
        'mailing_address',
        'occupation',
        'employer_name',
        'job_title',
        'id_document_type',
        'id_document_number',
        'id_document_issuing_country',
        'id_document_expiry_date',
        // Routing & jurisdiction
        'is_us_person',
        'is_us_citizen',
        'has_us_green_card',
        'is_us_tax_resident',
        'has_us_ssn_or_itin',
        'investing_through_us_entity',
        'all_tax_residences',
        'tax_id_numbers',
        'country_when_received_info',
        'country_when_discussing',
        'country_when_signing',
        'country_when_sending_funds',
        // AML / KYC / PEP / Sanctions
        'investing_own_name_confirmed',
        'beneficial_owner_disclosed',
        'is_pep',
        'pep_details',
        'sanctions_clear_confirmed',
        'third_party_funds_excluded',
        // Source of funds & wealth
        'source_of_funds',
        'source_of_funds_details',
        'source_of_wealth',
        'source_of_wealth_details',
        // Tax
        'fatca_status',
        'crs_certified',
        'tax_advice_confirmed',
        // Banking
        'bank_name',
        'bank_account_holder',
        'bank_iban',
        'bank_swift_bic',
        'bank_country',
        'bank_account_verified',
        'investment_currency',
        // USA LLC
        'us_residential_address',
        'us_state',
        'w9_signed',
        'accredited_questionnaire_signed',
        'subscription_agreement_signed',
        'llc_agreement_signed',
        'bad_actor_questionnaire_signed',
        'accredited_verification_method',
        // UK LLP
        'not_us_person_confirmed',
        'local_investor_classification',
        'local_legal_advice_confirmed',
        'participation_permitted_locally',
        'llp_agreement_signed',
        'admission_agreement_signed',
        'capital_call_agreement_signed',
        'risk_acknowledgement_signed',
        // Entity investor
        'investor_entity_type',
        'entity_legal_name',
        'entity_registration_number',
        'entity_country_of_incorporation',
        'entity_registered_address',
        'entity_tax_id',
        // Onboarding
        'onboarding_phase',
        'kyc_submitted_at',
        'kyc_approved_at',
    ];

    protected function casts(): array
    {
        return [
            'risk_acknowledgement_at'       => 'datetime',
            'terms_accepted_at'             => 'datetime',
            'kyc_submitted_at'              => 'datetime',
            'kyc_approved_at'               => 'datetime',
            'date_of_birth'                 => 'date',
            'id_document_expiry_date'       => 'date',
            'all_citizenships'              => 'array',
            'all_tax_residences'            => 'array',
            'tax_id_numbers'                => 'array',
            'pep_details'                   => 'array',
            'is_us_person'                  => 'boolean',
            'is_us_citizen'                 => 'boolean',
            'has_us_green_card'             => 'boolean',
            'is_us_tax_resident'            => 'boolean',
            'has_us_ssn_or_itin'            => 'boolean',
            'investing_through_us_entity'   => 'boolean',
            'investing_own_name_confirmed'  => 'boolean',
            'beneficial_owner_disclosed'    => 'boolean',
            'is_pep'                        => 'boolean',
            'sanctions_clear_confirmed'     => 'boolean',
            'third_party_funds_excluded'    => 'boolean',
            'crs_certified'                 => 'boolean',
            'tax_advice_confirmed'          => 'boolean',
            'bank_account_verified'         => 'boolean',
            'w9_signed'                     => 'boolean',
            'accredited_questionnaire_signed' => 'boolean',
            'subscription_agreement_signed' => 'boolean',
            'llc_agreement_signed'          => 'boolean',
            'bad_actor_questionnaire_signed' => 'boolean',
            'not_us_person_confirmed'       => 'boolean',
            'local_legal_advice_confirmed'  => 'boolean',
            'participation_permitted_locally' => 'boolean',
            'llp_agreement_signed'          => 'boolean',
            'admission_agreement_signed'    => 'boolean',
            'capital_call_agreement_signed' => 'boolean',
            'risk_acknowledgement_signed'   => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function investments()
    {
        return $this->hasMany(InvestorInvestment::class);
    }

    public function capitalCalls()
    {
        return $this->hasManyThrough(CapitalCall::class, InvestorInvestment::class);
    }

    public function payouts()
    {
        return $this->hasMany(InvestorPayout::class, 'investor_user_id', 'user_id');
    }
}
