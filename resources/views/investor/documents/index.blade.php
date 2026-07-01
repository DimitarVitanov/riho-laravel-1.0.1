@extends('layouts.simple.master')
@section('title', 'KYC Documents')

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
            <h1>KYC Documents</h1>
            <p>Villa Bit Capital — Complete your KYC application and upload required documents.</p>
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

    @if(session('success'))
    <div class="vb-notice" style="margin-bottom:20px;background:#edf7ee;border-color:#86efac;">{{ session('success') }}</div>
    @endif

    {{-- Legal Disclaimer --}}
    <div class="vb-notice" style="margin-bottom:24px;background:#fffbeb;border-color:#fbbf24;color:#92400e;">
        <strong>Important Notice:</strong> This onboarding form collects data for eligibility review only. No investment is accepted and no funds should be sent until you have received written approval, reviewed the full offering documents, and completed all required legal documentation.
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
                    <select name="country_of_birth" class="vb-input">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ ($profile->country_of_birth ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field">
                    <label class="vb-label">All Citizenships <span style="font-weight:400;color:#6b7280;">(comma-separated)</span></label>
                    <input type="text" name="all_citizenships" class="vb-input" value="{{ implode(', ', $profile->all_citizenships ?? []) }}" placeholder="e.g. Croatia, Germany">
                </div>
                <div class="vb-field">
                    <label class="vb-label">Citizenship Country (Primary)</label>
                    <select name="citizenship_country" class="vb-input">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ ($profile->citizenship_country ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
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
                    <select name="id_document_issuing_country" class="vb-input">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ ($profile->id_document_issuing_country ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="vb-field">
                    <label class="vb-label">Expiry Date</label>
                    <input type="date" name="id_document_expiry_date" class="vb-input" value="{{ $profile->id_document_expiry_date?->format('Y-m-d') ?? '' }}">
                </div>
            </div>
            <div class="vb-notice" style="margin-bottom:16px;background:#fffbeb;border-color:#fbbf24;color:#92400e;font-size:12px;">
                Do not submit copies of passports or sensitive documents here. Document uploads are requested separately through the secure upload section below.
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Personal Information</button>
        </form>
    </div>

    {{-- ===== SECTION B: ROUTING & JURISDICTION ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">B. Routing & Jurisdiction</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#eff6ff;border-color:#93c5fd;color:#1e40af;font-size:13px;">
            Citizenship alone is not sufficient. We must identify where you live, pay tax, and where you were located when the investment opportunity was offered and accepted.
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
                    <select name="residence_country" class="vb-input">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ ($profile->residence_country ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px;">
                <div style="font-weight:700;color:#374151;margin-bottom:12px;font-size:13px;">Country When Investment Activity Occurred</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div class="vb-field"><label class="vb-label">When you first received investment information</label>
                        <select name="country_when_received_info" class="vb-input">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->country_when_received_info ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field"><label class="vb-label">When discussing the investment</label>
                        <select name="country_when_discussing" class="vb-input">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->country_when_discussing ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field"><label class="vb-label">When signing documentation</label>
                        <select name="country_when_signing" class="vb-input">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->country_when_signing ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vb-field"><label class="vb-label">When sending investment funds</label>
                        <select name="country_when_sending_funds" class="vb-input">
                            <option value="">-- Select Country --</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->country_when_sending_funds ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                            @endforeach
                        </select>
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
                <label class="vb-label">PEP Details</label>
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
                        <textarea name="source_of_funds_details" class="vb-input" rows="3" placeholder="Details of how the investment funds were accumulated">{{ $profile->source_of_funds_details ?? '' }}</textarea>
                    </div>
                </div>
                <div>
                    <div style="font-weight:700;color:#374151;margin-bottom:8px;font-size:13px;">Source of Wealth</div>
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
                        <textarea name="source_of_wealth_details" class="vb-input" rows="3" placeholder="Details of how your overall wealth was accumulated">{{ $profile->source_of_wealth_details ?? '' }}</textarea>
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
                You are responsible for obtaining your own legal and tax advice and for making all required tax filings in your country of residence.
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
                    <input type="checkbox" name="crs_certified" value="1" id="crs_certified" {{ ($profile->crs_certified ?? false) ? 'checked' : '' }} style="width:16px;height:16px;flex-shrink:0;">
                    <label for="crs_certified" style="font-size:13px;color:#374151;cursor:pointer;">CRS Self-Certification completed</label>
                </div>
                <div style="display:flex;align-items:center;gap:10px;padding:12px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;">
                    <input type="hidden" name="tax_advice_confirmed" value="0">
                    <input type="checkbox" name="tax_advice_confirmed" value="1" id="tax_advice_confirmed" {{ ($profile->tax_advice_confirmed ?? false) ? 'checked' : '' }} style="width:16px;height:16px;flex-shrink:0;">
                    <label for="tax_advice_confirmed" style="font-size:13px;color:#374151;cursor:pointer;">I confirm I have obtained independent tax advice</label>
                </div>
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save Tax Information</button>
        </form>
    </div>

    {{-- ===== SECTION F: BANKING ===== --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <h2 class="vb-section-title">F. Banking & Investment Amount</h2>
        <div class="vb-notice" style="margin-bottom:16px;background:#fef3c7;border-color:#fbbf24;color:#92400e;font-size:12px;">
            All investment funds must be sent from a bank account in your own name or in the name of a fully disclosed entity you control.
        </div>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="banking">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field"><label class="vb-label">Bank Name</label>
                    <input type="text" name="bank_name" class="vb-input" value="{{ $profile->bank_name ?? '' }}" placeholder="e.g. Erste Bank">
                </div>
                <div class="vb-field"><label class="vb-label">Account Holder Name</label>
                    <input type="text" name="bank_account_holder" class="vb-input" value="{{ $profile->bank_account_holder ?? '' }}" placeholder="Full name as on account">
                </div>
                <div class="vb-field"><label class="vb-label">Bank Country</label>
                    <select name="bank_country" class="vb-input">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->name }}" {{ ($profile->bank_country ?? '') == $c->name ? 'selected' : '' }}>{{ $c->iso_3166_2 }} — {{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field"><label class="vb-label">IBAN / Account Number</label>
                    <input type="text" name="bank_iban" class="vb-input" value="{{ $profile->bank_iban ?? '' }}" placeholder="HR12 3456 7890 1234 5678 9">
                </div>
                <div class="vb-field"><label class="vb-label">SWIFT / BIC</label>
                    <input type="text" name="bank_swift_bic" class="vb-input" value="{{ $profile->bank_swift_bic ?? '' }}" placeholder="e.g. ESBCHR22">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field"><label class="vb-label">Maximum Capital Call Commitment</label>
                    <input type="number" name="max_commitment_amount" class="vb-input" value="{{ $profile->max_commitment_amount ?? '' }}" placeholder="0.00" step="0.01" min="0">
                </div>
                <div class="vb-field"><label class="vb-label">Investment Currency</label>
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

    {{-- ===== USA LLC ===== --}}
    @if($structure === 'usa_llc' || ($profile->is_us_person ?? false))
    <div class="vb-card" style="margin-bottom:24px;border-left:4px solid #2563eb;">
        <h2 class="vb-section-title" style="color:#1d4ed8;">🇺🇸 USA LLC — Additional Requirements</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="payout">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field"><label class="vb-label">U.S. Residential Address</label>
                    <textarea name="us_residential_address" class="vb-input" rows="2" placeholder="Full U.S. address">{{ $profile->us_residential_address ?? '' }}</textarea>
                </div>
                <div class="vb-field"><label class="vb-label">U.S. State of Residence</label>
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
                    <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}" {{ ($profile->$field ?? false) ? 'checked' : '' }} style="width:16px;height:16px;flex-shrink:0;">
                    <label for="{{ $field }}" style="font-size:13px;color:#1e40af;cursor:pointer;">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save USA LLC Documents</button>
        </form>
    </div>
    @endif

    {{-- ===== UK LLP ===== --}}
    @if($structure === 'uk_llp' || (!($profile->is_us_person ?? false) && $structure !== 'usa_llc'))
    <div class="vb-card" style="margin-bottom:24px;border-left:4px solid #7c3aed;">
        <h2 class="vb-section-title" style="color:#6d28d9;">🇬🇧 UK LLP — Additional Requirements</h2>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="payout">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                <div class="vb-field"><label class="vb-label">Local Investor Classification</label>
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
                    ['not_us_person_confirmed','I confirm that I am not a U.S. person.'],
                    ['local_legal_advice_confirmed','I confirm that I have obtained independent legal and tax advice in my local jurisdiction.'],
                    ['participation_permitted_locally','I confirm that participation is permitted under my local laws.'],
                    ['llp_agreement_signed','I confirm that I will sign the LLP Agreement / Deed of Adherence before my investment is accepted.'],
                    ['admission_agreement_signed','I confirm that I will sign the Admission Agreement before my investment is accepted.'],
                    ['capital_call_agreement_signed','I confirm that I will sign the Capital Call Agreement before making my investment payment.'],
                    ['risk_acknowledgement_signed','I confirm that I will sign the Risk Acknowledgement before my investment is accepted.'],
                ] as [$field, $label])
                <div style="display:flex;align-items:flex-start;gap:10px;padding:12px;background:#f5f3ff;border:1px solid #c4b5fd;border-radius:8px;">
                    <input type="hidden" name="{{ $field }}" value="0">
                    <input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}" {{ ($profile->$field ?? false) ? 'checked' : '' }} style="margin-top:2px;width:16px;height:16px;flex-shrink:0;">
                    <label for="{{ $field }}" style="font-size:13px;color:#4c1d95;cursor:pointer;">{{ $label }}</label>
                </div>
                @endforeach
            </div>
            <button type="submit" class="vb-btn vb-btn-primary">Save UK LLP Documents</button>
        </form>
    </div>
    @endif

    {{-- ===== SUBMIT KYC ===== --}}
    @if($phase === 'initial')
    <div class="vb-card" style="margin-bottom:24px;background:#f0fdf4;border:1px solid #86efac;">
        <h2 class="vb-section-title" style="color:#15803d;">Submit KYC Application</h2>
        <p style="font-size:14px;color:#374151;margin-bottom:16px;">Once you have completed all sections above, submit your application for eligibility review. Our team will review within 5 business days.</p>
        <form action="{{ route('investor.profile.update') }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="form_type" value="submit_kyc">
            <button type="submit" class="vb-btn vb-btn-primary" style="background:#16a34a;">Submit KYC Application for Review</button>
        </form>
    </div>
    @elseif(in_array($phase, ['eligibility_review','kyc_portal','documents_review']))
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

    {{-- ===== DOCUMENT UPLOAD ===== --}}
    <div class="vb-card" style="margin-bottom:24px;border-top:3px solid #111827;padding-top:24px;">
        <h2 class="vb-section-title">Upload KYC Documents</h2>
        <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">Upload your signed agreements, passport copies, proof of address, and other required documents here.</p>
        <form action="{{ route('investor.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:12px;align-items:flex-end;">
                <div style="flex:1;">
                    <input type="file" name="document" class="vb-input" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="vb-btn">Upload</button>
            </div>
            <div class="vb-period" style="margin-top:8px;">Accepted: PDF, DOC, DOCX, JPG, PNG. Max 10MB.</div>
        </form>
    </div>

    {{-- ===== MY DOCUMENTS ===== --}}
    <div class="vb-card">
        <h2 class="vb-section-title">My Documents</h2>
        @if(count($documents))
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($documents as $doc)
            <tr>
                <td><strong>{{ $doc['name'] }}</strong></td>
                <td>{{ number_format($doc['size'] / 1024, 1) }} KB</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($doc['date'])->format('M d, Y H:i') }}</td>
                <td><span class="vb-badge vb-badge-success">Uploaded</span></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <div class="vb-empty">
            <h3>No documents uploaded yet</h3>
            <p>Upload your subscription agreement, KYC documents, or other investment materials above.</p>
        </div>
        @endif
    </div>

</div>

<script>
document.getElementById('is_pep')?.addEventListener('change', function() {
    const section = document.getElementById('pep_details_section');
    if (section) section.style.display = this.checked ? 'block' : 'none';
});
</script>
@endsection
