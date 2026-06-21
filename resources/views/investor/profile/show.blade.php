@extends('layouts.simple.master')
@section('title', 'Profile & KYC')

@php
$phase = $profile->onboarding_phase ?? 'initial';
$structure = $profile->eligible_structure ?? 'pending_review';
$phaseLabels = [
    'initial'            => ['label' => 'Not Started',        'color' => '#6b7280'],
    'eligibility_review' => ['label' => 'Eligibility Review', 'color' => '#d97706'],
    'kyc_portal'         => ['label' => 'KYC Portal',         'color' => '#2563eb'],
    'documents_review'   => ['label' => 'Documents Review',   'color' => '#7c3aed'],
    'approved'           => ['label' => 'Approved',           'color' => '#16a34a'],
    'rejected'           => ['label' => 'Rejected',           'color' => '#dc2626'],
];
$phaseInfo = $phaseLabels[$phase] ?? $phaseLabels['initial'];
@endphp

@section('main_content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="vb-page-header">
        <div>
            <h1>Profile & KYC Onboarding</h1>
            <p>Villa Bit Capital — Investor onboarding, identity verification, and KYC data.</p>
        </div>
        <div style="display:flex;gap:10px;align-items:center;">
            <span class="vb-badge" style="background:{{ $phaseInfo['color'] }}20;color:{{ $phaseInfo['color'] }};border:1px solid {{ $phaseInfo['color'] }}40;font-size:13px;padding:8px 14px;">
                Onboarding: {{ $phaseInfo['label'] }}
            </span>
            @if($profile && $profile->kyc_status === 'approved')
                <span class="vb-badge vb-badge-success" style="font-size:13px;padding:8px 14px;">KYC Verified</span>
            @else
                <span class="vb-badge vb-badge-warning" style="font-size:13px;padding:8px 14px;">KYC Pending</span>
            @endif
        </div>
    </div>

    @include('components.villabit.usage-banner')

    @if(session('success'))
    <div class="vb-notice" style="margin-bottom:20px;background:#edf7ee;border-color:#86efac;">{{ session('success') }}</div>
    @endif

    {{-- Legal Disclaimer --}}
    <div class="vb-notice" style="margin-bottom:24px;background:#fffbeb;border-color:#fbbf24;color:#92400e;">
        <strong>Important Notice:</strong> This onboarding form collects data for eligibility review only. No investment is accepted and no funds should be sent until you have received written approval, reviewed the full offering documents, and completed all required legal documentation. The project is designed to provide a targeted annual cumulative preferred return, subject to the definitive legal documents, project performance, available cash, applicable law, and the risk of loss of some or all invested capital.
    </div>

    {{-- KYC Status Overview --}}
    @if($profile)
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">Account & KYC Status</h2>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;">
            <div>
                <div class="vb-label">Investor Type</div>
                <div style="font-size:14px;font-weight:600;color:#111827;">{{ ucfirst(str_replace('_',' ',$profile->investor_type ?? '—')) }}</div>
            </div>
            <div>
                <div class="vb-label">Structure</div>
                <div style="font-size:14px;font-weight:600;color:#1d4ed8;">{{ strtoupper(str_replace('_',' ',$structure)) }}</div>
            </div>
            <div>
                <div class="vb-label">KYC Status</div>
                <span class="vb-badge {{ $profile->kyc_status === 'approved' ? 'vb-badge-success' : 'vb-badge-warning' }}">{{ ucfirst($profile->kyc_status ?? 'n/a') }}</span>
            </div>
            <div>
                <div class="vb-label">AML Status</div>
                <span class="vb-badge {{ $profile->aml_status === 'approved' ? 'vb-badge-success' : 'vb-badge-warning' }}">{{ ucfirst($profile->aml_status ?? 'n/a') }}</span>
            </div>
            <div>
                <div class="vb-label">Accreditation</div>
                <span class="vb-badge {{ $profile->accreditation_status === 'verified' ? 'vb-badge-success' : 'vb-badge-warning' }}">{{ ucfirst(str_replace('_',' ',$profile->accreditation_status ?? 'n/a')) }}</span>
            </div>
        </div>
        @if($profile->kyc_submitted_at)
        <div style="margin-top:12px;font-size:12px;color:#6b7280;">
            Submitted: {{ $profile->kyc_submitted_at->format('d M Y') }}
            @if($profile->kyc_approved_at) &nbsp;·&nbsp; Approved: {{ $profile->kyc_approved_at->format('d M Y') }} @endif
        </div>
        @endif
    </div>
    @endif

    {{-- Structure Routing Info --}}
    <div class="vb-card" style="margin-bottom:24px;background:#f0f7ff;border:1px solid #bfdbfe;">
        <h2 class="vb-section-title" style="color:#1e40af;">Investment Structure — Villa Bit Capital</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
            <div style="background:#fff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;">
                <div style="font-weight:700;color:#1d4ed8;margin-bottom:8px;">🇺🇸 USA LLC</div>
                <div style="font-size:13px;color:#374151;line-height:1.6;">Only for <strong>U.S. persons</strong> who are verified accredited investors. Requires W-9, Accredited Investor Questionnaire, Subscription Agreement, LLC Operating Agreement, and Private Placement Memorandum.</div>
            </div>
            <div style="background:#fff;border:1px solid #bfdbfe;border-radius:10px;padding:16px;">
                <div style="font-weight:700;color:#1d4ed8;margin-bottom:8px;">🇬🇧 UK LLP</div>
                <div style="font-size:13px;color:#374151;line-height:1.6;">For <strong>eligible non-U.S. persons</strong>, subject to jurisdiction, sanctions, tax, financial-promotion, and legal review. A person holding citizenship in one country may still be a U.S. person if they hold a U.S. green card, have U.S. tax residence, or invest through a U.S. entity.</div>
            </div>
        </div>
    </div>

    {{-- ===== SECTION A: PERSONAL IDENTIFICATION ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">A. Personal Identification</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="personal">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Investor / Entity Type</label>
                    <select name="investor_entity_type" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                        @foreach(['individual'=>'Individual','company'=>'Company','trust'=>'Trust','fund'=>'Fund','family_office'=>'Family Office'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($profile->investor_entity_type ?? 'individual') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vb-field">
                    <label class="vb-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="vb-input" value="{{ $profile->date_of_birth?->format('Y-m-d') ?? '' }}">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Previous Names / Aliases</label>
                    <input type="text" name="previous_names" class="vb-input" value="{{ $profile->previous_names ?? '' }}" placeholder="Other surnames or aliases">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Place of Birth</label>
                    <input type="text" name="place_of_birth" class="vb-input" value="{{ $profile->place_of_birth ?? '' }}" placeholder="City">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Country of Birth</label>
                    <input type="text" name="country_of_birth" class="vb-input" value="{{ $profile->country_of_birth ?? '' }}" placeholder="Country">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">All Citizenships <span style="font-weight:400;color:#6b7280;">(comma-separated)</span></label>
                    <input type="text" name="all_citizenships" class="vb-input" value="{{ implode(', ', $profile->all_citizenships ?? []) }}" placeholder="e.g. Croatia, Germany">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Citizenship Country (Primary)</label>
                    <input type="text" name="citizenship_country" class="vb-input" value="{{ $profile->citizenship_country ?? '' }}" placeholder="Primary citizenship">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Permanent Residential Address</label>
                    <textarea name="permanent_address" class="vb-input" rows="2" placeholder="Full address including city, postal code, country">{{ $profile->permanent_address ?? '' }}</textarea>
                </div>
                <div class="vb-field">
                    <label class="vb-label">Mailing Address <span style="font-weight:400;color:#6b7280;">(if different)</span></label>
                    <textarea name="mailing_address" class="vb-input" rows="2" placeholder="Leave blank if same as above">{{ $profile->mailing_address ?? '' }}</textarea>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Occupation</label>
                    <input type="text" name="occupation" class="vb-input" value="{{ $profile->occupation ?? '' }}" placeholder="e.g. Business Owner">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Employer / Company Name</label>
                    <input type="text" name="employer_name" class="vb-input" value="{{ $profile->employer_name ?? '' }}" placeholder="Employer or own company name">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Job Title / Position</label>
                    <input type="text" name="job_title" class="vb-input" value="{{ $profile->job_title ?? '' }}" placeholder="e.g. Director">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">ID Document Type</label>
                    <select name="id_document_type" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                        <option value="">— Select —</option>
                        @foreach(['passport'=>'Passport','national_id'=>'National ID Card','driving_licence'=>'Driving Licence'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($profile->id_document_type ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vb-field">
                    <label class="vb-label">Document Number</label>
                    <input type="text" name="id_document_number" class="vb-input" value="{{ $profile->id_document_number ?? '' }}" placeholder="Document number">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Issuing Country</label>
                    <input type="text" name="id_document_issuing_country" class="vb-input" value="{{ $profile->id_document_issuing_country ?? '' }}" placeholder="Issuing country">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Expiry Date</label>
                    <input type="date" name="id_document_expiry_date" class="vb-input" value="{{ $profile->id_document_expiry_date?->format('Y-m-d') ?? '' }}">
                </div>
            </div>
            <div class="vb-notice" style="margin-bottom:16px;background:#fffbeb;border-color:#fbbf24;color:#92400e;font-size:12px;">
                Do not submit copies of passports or sensitive documents here. Document uploads are requested separately through the secure KYC portal after eligibility review.
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Personal Information</button>
        </form>
    </div>

    {{-- ===== SECTION B: ROUTING & JURISDICTION ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">B. Routing & Jurisdiction</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#eff6ff;border-color:#93c5fd;color:#1e40af;font-size:13px;">
            Citizenship alone is not sufficient. We must identify where you live, pay tax, and where you were located when the investment opportunity was offered and accepted. A French citizen with a U.S. green card or U.S. tax residence may still be a U.S. person under U.S. tax and securities law.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="jurisdiction">
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px;">
                <div style="font-weight:700;color:#374151;margin-bottom:12px;font-size:13px;">U.S. Person Determination</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
                    @foreach([
                        ['is_us_person','Are you a U.S. person?'],
                        ['is_us_citizen','Are you a U.S. citizen?'],
                        ['has_us_green_card','Do you hold a U.S. green card?'],
                        ['is_us_tax_resident','Are you a U.S. tax resident?'],
                        ['has_us_ssn_or_itin','Do you have a U.S. SSN or ITIN?'],
                        ['investing_through_us_entity','Investing through a U.S. company, trust, or partnership?'],
                    ] as [$field, $label])
                    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}"
                            {{ ($profile->$field ?? false) ? 'checked' : '' }}
                            style="margin-top:2px;width:16px;height:16px;flex-shrink:0;">
                        <label for="{{ $field }}" style="font-size:13px;color:#374151;cursor:pointer;">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">All Countries of Tax Residence <span style="font-weight:400;color:#6b7280;">(comma-separated)</span></label>
                    <input type="text" name="all_tax_residences" class="vb-input" value="{{ implode(', ', $profile->all_tax_residences ?? []) }}" placeholder="e.g. Croatia, Germany">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Country of Normal Residence</label>
                    <input type="text" name="residence_country" class="vb-input" value="{{ $profile->residence_country ?? '' }}" placeholder="Country where you live">
                </div>
            </div>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px;">
                <div style="font-weight:700;color:#374151;margin-bottom:12px;font-size:13px;">Country When Investment Activity Occurred</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="vb-field">
                        <label class="vb-label">When you first received investment information</label>
                        <input type="text" name="country_when_received_info" class="vb-input" value="{{ $profile->country_when_received_info ?? '' }}" placeholder="Country">
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">When discussing the investment</label>
                        <input type="text" name="country_when_discussing" class="vb-input" value="{{ $profile->country_when_discussing ?? '' }}" placeholder="Country">
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">When signing documentation</label>
                        <input type="text" name="country_when_signing" class="vb-input" value="{{ $profile->country_when_signing ?? '' }}" placeholder="Country">
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">When sending investment funds</label>
                        <input type="text" name="country_when_sending_funds" class="vb-input" value="{{ $profile->country_when_sending_funds ?? '' }}" placeholder="Country">
                    </div>
                </div>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Jurisdiction Information</button>
        </form>
    </div>

    {{-- ===== SECTION C: AML / KYC / PEP / SANCTIONS ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">C. AML, KYC, PEP & Sanctions Declarations</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="aml">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                @foreach([
                    ['investing_own_name_confirmed', 'I am investing in my own name and for my own account'],
                    ['beneficial_owner_disclosed', 'If investing for another person, the true beneficial owner is fully disclosed'],
                    ['is_pep', 'I am or have been a Politically Exposed Person (PEP)'],
                    ['sanctions_clear_confirmed', 'I confirm I am not subject to sanctions, and am not associated with a sanctioned person, company, or country'],
                    ['third_party_funds_excluded', 'I confirm funds are not from cash, crypto, or third-party sources without prior written approval'],
                ] as [$field, $label])
                <div style="display:flex;align-items:flex-start;gap:10px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}"
                        {{ ($profile->$field ?? false) ? 'checked' : '' }}
                        style="margin-top:2px;width:16px;height:16px;flex-shrink:0;">
                    <label for="{{ $field }}" style="font-size:13px;color:#374151;cursor:pointer;">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            <div class="vb-field" style="margin-bottom:16px;" id="pep_details_section" style="{{ ($profile->is_pep ?? false) ? '' : 'display:none;' }}">
                <label class="vb-label">PEP Details <span style="font-weight:400;color:#6b7280;">(position, country, dates, close associates)</span></label>
                <textarea name="pep_details" class="vb-input" rows="3" placeholder="Provide full PEP details if applicable">{{ isset($profile->pep_details['details']) ? $profile->pep_details['details'] : '' }}</textarea>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save AML Declarations</button>
        </form>
    </div>

    {{-- ===== SECTION D: SOURCE OF FUNDS & WEALTH ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">D. Source of Funds & Source of Wealth</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="source_of_funds">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:16px;">
                <div>
                    <div style="font-weight:700;color:#374151;margin-bottom:8px;font-size:13px;">Source of Funds</div>
                    <div style="font-size:12px;color:#6b7280;margin-bottom:10px;">The origin of the specific money being invested in this project.</div>
                    <div class="vb-field" style="margin-bottom:10px;">
                        <label class="vb-label">Category</label>
                        <select name="source_of_funds" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            <option value="">— Select —</option>
                            @foreach(['Personal bank savings','Sale of real estate','Sale of a company','Dividend distribution','Employment income','Inheritance','Business income','Investment returns','Other'] as $opt)
                                <option value="{{ $opt }}" {{ ($profile->source_of_funds ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">Details</label>
                        <textarea name="source_of_funds_details" class="vb-input" rows="3" placeholder="Provide details of how the investment funds were accumulated">{{ $profile->source_of_funds_details ?? '' }}</textarea>
                    </div>
                </div>
                <div>
                    <div style="font-weight:700;color:#374151;margin-bottom:8px;font-size:13px;">Source of Wealth</div>
                    <div style="font-size:12px;color:#6b7280;margin-bottom:10px;">How you accumulated your overall wealth over time.</div>
                    <div class="vb-field" style="margin-bottom:10px;">
                        <label class="vb-label">Category</label>
                        <select name="source_of_wealth" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            <option value="">— Select —</option>
                            @foreach(['Business ownership','Long-term employment income','Sale of a company','Investments','Inheritance','Pension income','Dividends','Real estate','Other'] as $opt)
                                <option value="{{ $opt }}" {{ ($profile->source_of_wealth ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">Details</label>
                        <textarea name="source_of_wealth_details" class="vb-input" rows="3" placeholder="Provide details of how your overall wealth was accumulated">{{ $profile->source_of_wealth_details ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Source of Funds / Wealth</button>
        </form>
    </div>

    {{-- ===== SECTION E: TAX INFORMATION ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">E. Tax Information</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="tax">
            <div class="vb-notice" style="margin-bottom:16px;background:#fef3c7;border-color:#fbbf24;color:#92400e;font-size:12px;">
                The USA LLC and UK LLP structures are not described as "tax-free." A partnership-style structure may be treated as pass-through in some circumstances, but your actual tax treatment depends on your residence, citizenship, local law, tax treaties, documentation, and the final legal structure. You are responsible for obtaining your own legal and tax advice and for making all required tax filings in your country of residence.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">FATCA Status</label>
                    <select name="fatca_status" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                        <option value="">— Select —</option>
                        @foreach(['us_person'=>'U.S. Person','non_us'=>'Non-U.S. Person','exempt'=>'Exempt / NPFFE','unknown'=>'Under Review'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($profile->fatca_status ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
                    <input type="hidden" name="crs_certified" value="0">
                    <input type="checkbox" name="crs_certified" value="1" id="crs_certified"
                        {{ ($profile->crs_certified ?? false) ? 'checked' : '' }}
                        style="width:16px;height:16px;flex-shrink:0;">
                    <label for="crs_certified" style="font-size:13px;color:#374151;cursor:pointer;">CRS Self-Certification completed</label>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
                    <input type="hidden" name="tax_advice_confirmed" value="0">
                    <input type="checkbox" name="tax_advice_confirmed" value="1" id="tax_advice_confirmed"
                        {{ ($profile->tax_advice_confirmed ?? false) ? 'checked' : '' }}
                        style="width:16px;height:16px;flex-shrink:0;">
                    <label for="tax_advice_confirmed" style="font-size:13px;color:#374151;cursor:pointer;">I confirm I have obtained independent tax advice and am responsible for my own tax filings</label>
                </div>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Tax Information</button>
        </form>
    </div>

    {{-- ===== SECTION F: BANKING ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">F. Banking & Investment Amount</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#fef3c7;border-color:#fbbf24;color:#92400e;font-size:12px;">
            All investment funds must be sent from a bank account in your own name or in the name of a fully disclosed entity you control. Third-party bank accounts are not accepted without prior written AML review and approval by legal counsel.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="banking">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Bank Name</label>
                    <input type="text" name="bank_name" class="vb-input" value="{{ $profile->bank_name ?? '' }}" placeholder="e.g. Erste Bank">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Account Holder Name</label>
                    <input type="text" name="bank_account_holder" class="vb-input" value="{{ $profile->bank_account_holder ?? '' }}" placeholder="Full name as on account">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Bank Country</label>
                    <input type="text" name="bank_country" class="vb-input" value="{{ $profile->bank_country ?? '' }}" placeholder="Country">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">IBAN / Account Number</label>
                    <input type="text" name="bank_iban" class="vb-input" value="{{ $profile->bank_iban ?? '' }}" placeholder="HR12 3456 7890 1234 5678 9">
                </div>
                <div class="vb-field">
                    <label class="vb-label">SWIFT / BIC</label>
                    <input type="text" name="bank_swift_bic" class="vb-input" value="{{ $profile->bank_swift_bic ?? '' }}" placeholder="e.g. ESBCHR22">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Maximum Capital Call Commitment</label>
                    <input type="number" name="max_commitment_amount" class="vb-input" value="{{ $profile->max_commitment_amount ?? '' }}" placeholder="0.00" step="0.01" min="0">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Investment Currency</label>
                    <select name="investment_currency" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                        @foreach(['EUR'=>'EUR — Euro','USD'=>'USD — US Dollar','GBP'=>'GBP — British Pound','CHF'=>'CHF — Swiss Franc'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($profile->investment_currency ?? 'EUR') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Banking Information</button>
        </form>
    </div>

    {{-- ===== STRUCTURE-SPECIFIC: USA LLC ===== --}}
    @if($structure === 'usa_llc' || $profile->is_us_person)
    <div class="vb-card" style="margin-bottom:24px;border-left:4px solid #2563eb;">
        <h2 class="vb-section-title" style="color:#1d4ed8;">🇺🇸 USA LLC — Additional Requirements (Rule 506(c))</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#eff6ff;border-color:#93c5fd;font-size:13px;">
            Where the USA LLC structure relies on Rule 506(c) and the offering is publicly marketed, every purchaser must be a verified accredited investor. A simple checkbox stating "I am accredited" is not sufficient. Third-party verification is required.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="payout">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">U.S. Residential Address</label>
                    <textarea name="us_residential_address" class="vb-input" rows="2" placeholder="Full U.S. address">{{ $profile->us_residential_address ?? '' }}</textarea>
                </div>
                <div class="vb-field">
                    <label class="vb-label">U.S. State of Residence</label>
                    <input type="text" name="us_state" class="vb-input" value="{{ $profile->us_state ?? '' }}" placeholder="e.g. California">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                @foreach([
                    ['w9_signed','Form W-9 Signed'],
                    ['accredited_questionnaire_signed','Accredited Investor Questionnaire Signed'],
                    ['subscription_agreement_signed','Subscription Agreement Signed'],
                    ['llc_agreement_signed','LLC Operating Agreement Signed'],
                    ['bad_actor_questionnaire_signed','Bad Actor Questionnaire Completed'],
                ] as [$field, $label])
                <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}"
                        {{ ($profile->$field ?? false) ? 'checked' : '' }}
                        style="width:16px;height:16px;flex-shrink:0;">
                    <label for="{{ $field }}" style="font-size:13px;color:#1e40af;cursor:pointer;">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save USA LLC Documents</button>
        </form>
    </div>
    @endif

    {{-- ===== STRUCTURE-SPECIFIC: UK LLP ===== --}}
    @if($structure === 'uk_llp' || (!$profile->is_us_person && $structure !== 'usa_llc'))
    <div class="vb-card" style="margin-bottom:24px;border-left:4px solid #7c3aed;">
        <h2 class="vb-section-title" style="color:#6d28d9;">🇬🇧 UK LLP — Additional Requirements (Eligible Non-U.S. Investors)</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#f5f3ff;border-color:#c4b5fd;font-size:13px;">
            UK LLP participation is not automatically available to every non-U.S. citizen. Eligibility depends on U.S. person status, citizenship, tax residence, sanctions status, investor classification, the jurisdiction in which the person is approached, and applicable local law.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="payout">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">Local Investor Classification</label>
                    <select name="local_investor_classification" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                        <option value="">— Select —</option>
                        @foreach(['retail'=>'Retail','professional'=>'Professional','sophisticated'=>'Sophisticated / High Net Worth','institutional'=>'Institutional'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($profile->local_investor_classification ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                @foreach([
                    ['not_us_person_confirmed','I confirm I am not a U.S. person'],
                    ['local_legal_advice_confirmed','I confirm I have obtained independent legal and tax advice in my local jurisdiction'],
                    ['participation_permitted_locally','I confirm that participation is permitted under my own local laws'],
                    ['llp_agreement_signed','LLP Agreement / Deed of Adherence Signed'],
                    ['admission_agreement_signed','Admission Agreement Signed'],
                    ['capital_call_agreement_signed','Capital Call Agreement Signed'],
                    ['risk_acknowledgement_signed','Risk Acknowledgement Signed'],
                ] as [$field, $label])
                <div style="display:flex;align-items:flex-start;gap:10px;padding:12px;background:#f5f3ff;border:1px solid #c4b5fd;border-radius:8px;">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}"
                        {{ ($profile->$field ?? false) ? 'checked' : '' }}
                        style="margin-top:2px;width:16px;height:16px;flex-shrink:0;">
                    <label for="{{ $field }}" style="font-size:13px;color:#4c1d95;cursor:pointer;">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save UK LLP Documents</button>
        </form>
    </div>
    @endif

    {{-- ===== PANEL LANGUAGE SETTINGS ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">Panel Language & Payout Settings</h2>
        <div class="vb-grid-2">
            <form action="{{ route('investor.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="form_type" value="language">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div class="vb-field">
                        <label class="vb-label">Control Panel Language</label>
                        <select name="preferred_language" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages() as $code => $name)
                                <option value="{{ $code }}" {{ ($user->preferred_language ?? 'en') === $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field">
                        <label class="vb-label">Preferred Currency</label>
                        <input type="text" name="preferred_currency" class="vb-input" value="{{ $profile->preferred_currency ?? 'EUR' }}" maxlength="10" placeholder="EUR">
                    </div>
                </div>
                <button type="submit" class="vb-btn vb-btn-primary">Save Language Settings</button>
            </form>
            <form action="{{ route('investor.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <input type="hidden" name="form_type" value="payout">
                <div style="display:grid;grid-template-columns:1fr;gap:16px;margin-bottom:16px;">
                    <div class="vb-field">
                        <label class="vb-label">Payout Method</label>
                        <select name="payout_method" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;">
                            @foreach(['bank_wire'=>'Bank Wire','paypal'=>'PayPal','stripe'=>'Stripe','crypto'=>'Crypto','other'=>'Other'] as $val => $label)
                                <option value="{{ $val }}" {{ ($profile->payout_method ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="vb-btn vb-btn-primary">Save Payout Settings</button>
            </form>
        </div>
    </div>

    {{-- ===== SUBMIT KYC ===== --}}
    @if(($profile->onboarding_phase ?? 'initial') === 'initial')
    <div class="vb-card" style="margin-bottom:24px;background:#f0fdf4;border:1px solid #86efac;">
        <h2 class="vb-section-title" style="color:#15803d;">Submit KYC Application</h2>
        <p style="font-size:14px;color:#374151;margin-bottom:16px;">
            Once you have completed all sections above, submit your application for eligibility review. Our team will review your information and contact you within 5 business days.
        </p>
        <div class="vb-notice" style="margin-bottom:16px;background:#fffbeb;border-color:#fbbf24;color:#92400e;font-size:12px;">
            This is an indication of interest only. No investment is accepted and no funds should be sent until you have received written approval and completed all required legal documentation.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="submit_kyc">
            <button type="submit" class="vb-btn vb-btn-primary" style="background:#16a34a;">Submit KYC Application for Review</button>
        </form>
    </div>
    @elseif(in_array($profile->onboarding_phase ?? 'initial', ['eligibility_review','kyc_portal','documents_review']))
    <div class="vb-card" style="margin-bottom:24px;background:#fffbeb;border:1px solid #fbbf24;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="font-size:24px;">⏳</div>
            <div>
                <div style="font-weight:700;color:#92400e;">Application Under Review</div>
                <div style="font-size:13px;color:#78350f;">Your KYC application was submitted on {{ $profile->kyc_submitted_at?->format('d M Y') ?? 'recently' }}. Our team will contact you shortly.</div>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
document.getElementById('is_pep')?.addEventListener('change', function() {
    const section = document.getElementById('pep_details_section');
    if (section) section.style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
