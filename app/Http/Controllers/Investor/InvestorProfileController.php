<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Notifications\KycApplicationReceivedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->investorProfile;

        return view('investor.profile.show', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $formType = $request->input('form_type');

        if ($formType === 'language') {
            $validated = $request->validate([
                'preferred_language' => 'nullable|string|max:10',
                'preferred_currency' => 'nullable|string|max:10',
            ]);
            $user->preferred_language = $validated['preferred_language'] ?? 'en';
            $user->save();
            if ($user->investorProfile) {
                $user->investorProfile->update(['preferred_currency' => $validated['preferred_currency'] ?? 'EUR']);
            }
        } elseif ($formType === 'payout') {
            $validated = $request->validate([
                'citizenship_country'    => 'nullable|string|max:100',
                'residence_country'      => 'nullable|string|max:100',
                'preferred_currency'     => 'nullable|string|max:10',
                'payout_method'          => 'nullable|string|in:bank_wire,stripe,paypal,crypto,other',
                'us_residential_address' => 'nullable|string|max:500',
                'us_state'               => 'nullable|string|max:100',
                'local_investor_classification' => 'nullable|string|max:50',
            ]);
            // USA LLC boolean fields
            $boolFields = [
                'w9_signed', 'accredited_questionnaire_signed', 'subscription_agreement_signed',
                'llc_agreement_signed', 'bad_actor_questionnaire_signed',
                // UK LLP boolean fields
                'not_us_person_confirmed', 'local_legal_advice_confirmed', 'participation_permitted_locally',
                'llp_agreement_signed', 'admission_agreement_signed', 'capital_call_agreement_signed',
                'risk_acknowledgement_signed',
            ];
            foreach ($boolFields as $field) {
                if ($request->has($field)) {
                    $validated[$field] = $request->boolean($field);
                }
            }
            if ($user->investorProfile) {
                $user->investorProfile->update($validated);
            }
        } elseif ($formType === 'personal') {
            $validated = $request->validate([
                'previous_names'              => 'nullable|string|max:255',
                'date_of_birth'               => 'nullable|date',
                'place_of_birth'              => 'nullable|string|max:100',
                'country_of_birth'            => 'nullable|string|max:100',
                'all_citizenships'            => 'nullable|string',
                'permanent_address'           => 'nullable|string|max:500',
                'mailing_address'             => 'nullable|string|max:500',
                'occupation'                  => 'nullable|string|max:100',
                'employer_name'               => 'nullable|string|max:200',
                'job_title'                   => 'nullable|string|max:100',
                'id_document_type'            => 'nullable|string|max:50',
                'id_document_number'          => 'nullable|string|max:100',
                'id_document_issuing_country' => 'nullable|string|max:100',
                'id_document_expiry_date'     => 'nullable|date',
                'investor_entity_type'        => 'nullable|string|in:individual,company,trust,fund,family_office',
            ]);
            // Convert comma-separated citizenships to array
            if (!empty($validated['all_citizenships'])) {
                $validated['all_citizenships'] = array_map('trim', explode(',', $validated['all_citizenships']));
            }
            if ($user->investorProfile) {
                $user->investorProfile->update($validated);
            }
        } elseif ($formType === 'jurisdiction') {
            $boolFields = [
                'is_us_person', 'is_us_citizen', 'has_us_green_card',
                'is_us_tax_resident', 'has_us_ssn_or_itin', 'investing_through_us_entity',
            ];
            $data = [
                'all_tax_residences'       => array_map('trim', array_filter(explode(',', $request->input('all_tax_residences', '')))),
                'country_when_received_info' => $request->input('country_when_received_info'),
                'country_when_discussing'   => $request->input('country_when_discussing'),
                'country_when_signing'      => $request->input('country_when_signing'),
                'country_when_sending_funds'=> $request->input('country_when_sending_funds'),
            ];
            foreach ($boolFields as $field) {
                $data[$field] = $request->boolean($field);
            }
            if ($user->investorProfile) {
                $user->investorProfile->update($data);
                // Determine structure routing
                $profile = $user->investorProfile;
                if ($data['is_us_person']) {
                    $profile->update(['eligible_structure' => 'usa_llc']);
                } elseif ($profile->eligible_structure === null) {
                    $profile->update(['eligible_structure' => 'pending_review']);
                }
            }
        } elseif ($formType === 'aml') {
            $boolFields = [
                'investing_own_name_confirmed', 'beneficial_owner_disclosed',
                'is_pep', 'sanctions_clear_confirmed', 'third_party_funds_excluded',
            ];
            $data = ['pep_details' => $request->input('pep_details') ? ['details' => $request->input('pep_details')] : null];
            foreach ($boolFields as $field) {
                $data[$field] = $request->boolean($field);
            }
            if ($user->investorProfile) {
                $user->investorProfile->update($data);
            }
        } elseif ($formType === 'source_of_funds') {
            $validated = $request->validate([
                'source_of_funds'         => 'nullable|string|max:100',
                'source_of_funds_details' => 'nullable|string|max:1000',
                'source_of_wealth'        => 'nullable|string|max:100',
                'source_of_wealth_details'=> 'nullable|string|max:1000',
            ]);
            if ($user->investorProfile) {
                $user->investorProfile->update($validated);
            }
        } elseif ($formType === 'tax') {
            $data = [
                'fatca_status'         => $request->input('fatca_status'),
                'crs_certified'        => $request->boolean('crs_certified'),
                'tax_advice_confirmed' => $request->boolean('tax_advice_confirmed'),
            ];
            if ($user->investorProfile) {
                $user->investorProfile->update($data);
            }
        } elseif ($formType === 'banking') {
            $validated = $request->validate([
                'bank_name'            => 'nullable|string|max:100',
                'bank_account_holder'  => 'nullable|string|max:200',
                'bank_iban'            => 'nullable|string|max:50',
                'bank_swift_bic'       => 'nullable|string|max:20',
                'bank_country'         => 'nullable|string|max:100',
                'investment_currency'  => 'nullable|string|max:10',
                'max_commitment_amount'=> 'nullable|numeric|min:0',
            ]);
            if ($user->investorProfile) {
                $user->investorProfile->update($validated);
            }
        } elseif ($formType === 'submit_kyc') {
            if ($user->investorProfile && $user->investorProfile->onboarding_phase === 'initial') {
                $user->investorProfile->update([
                    'onboarding_phase' => 'eligibility_review',
                    'kyc_submitted_at' => now(),
                    'kyc_status'       => 'pending',
                ]);
                $user->notify(new KycApplicationReceivedNotification());
            }
            return back()->with('success', 'Your KYC application has been submitted. Our team will review it shortly.');
        }

        return back()->with('success', 'Saved successfully.')->with('saved_section', $formType);
    }
}
