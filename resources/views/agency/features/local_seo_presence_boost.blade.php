@extends('layouts.simple.master')
@section('title', __('messages.local_seo'))
@section('breadcrumb-title')
    <h3>{{ __('messages.local_seo') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.local_seo') }}</li>
@endsection

@section('css')
<style>
:root {
  --ink: #0a0b0c;
  --soft: #f5f6f7;
  --line: #dde1e5;
  --muted: #69717a;
  --accent: #1d8d64;
  --accent-soft: #e7f6ef;
}

/* Local SEO page - exact mockup match */
.local-seo-feature { font-size: 14px; line-height: 1.45; }
.local-seo-feature .form-label { display: block; font-size: 12px; font-weight: 800; color: #3e454c; margin: 0 0 5px; }
.local-seo-feature .form-control, .local-seo-feature .form-select { 
    display: block; width: 100%; font-size: 14px; 
    border: 1px solid #cfd4d9; border-radius: 8px; 
    padding: 10px 11px; min-height: 40px;
    color: #262c31; background: #fff;
}
.local-seo-feature .btn { font-size: 13px; font-weight: 800; border-radius: 8px; padding: 10px 14px; border: 0; cursor: pointer; }
.local-seo-feature .btn-accent { background: var(--accent); color: #fff !important; }
.local-seo-feature .btn-accent:hover { background: #176347; color: #fff !important; }
.local-seo-feature .btn.btn-outline-secondary,
.local-seo-feature a.btn.btn-outline-secondary,
.local-seo-feature .actions-bar .btn-outline-secondary { 
    background: #fff !important; 
    color: #26303a !important; 
    border: 1px solid #cfd4d9 !important; 
}
.local-seo-feature .btn.btn-outline-secondary:hover,
.local-seo-feature a.btn.btn-outline-secondary:hover { 
    background: #f5f6f7 !important; 
    color: #0a0b0c !important; 
}
.local-seo-feature .btn-dark { background: var(--ink); color: #fff !important; }
.local-seo-feature h5 { margin: 0; font-size: 21px; line-height: 1.2; display: flex; align-items: center; gap: 6px; }
.local-seo-feature small, .local-seo-feature .text-muted { font-size: 12px; color: var(--muted); }
.local-seo-feature .help-text { font-size: 11.5px; color: var(--muted); margin: 5px 0 0; }
.local-seo-feature table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.local-seo-feature th { 
    text-align: left; font-size: 10px; letter-spacing: .05em; 
    text-transform: uppercase; color: #58616a; background: #f7f8f9; 
}
.local-seo-feature th, .local-seo-feature td { padding: 10px; border-bottom: 1px solid #edf0f2; vertical-align: top; }
.local-seo-feature tr:last-child td { border-bottom: 0; }

/* Card styling - exact mockup */
.local-seo-feature .card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    margin-bottom: 0;
    transition: box-shadow 0.2s ease;
}
.local-seo-feature .card:hover {
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
}
.local-seo-feature .card-header {
    display: flex; justify-content: space-between; gap: 16px; align-items: flex-start;
    border-bottom: 1px solid var(--line);
    padding: 21px;
    border-radius: 16px 16px 0 0;
    background: #fff;
}
.local-seo-feature .card-body {
    padding: 21px;
    border-radius: 0 0 16px 16px;
}

/* Step numbers - black circle */
.local-seo-feature .step-circle {
    display: inline-grid; place-items: center;
    width: 31px; height: 31px; border-radius: 50%;
    background: var(--ink); color: #fff;
    font-size: 14px; font-weight: 800;
    margin-right: 9px;
}

/* Output badge */
.local-seo-feature .output-badge {
    padding: 10px 13px;
    background: var(--accent-soft);
    color: #176347;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

/* Flow arrow between steps */
.local-seo-feature .flow-arrow {
    position: relative;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    min-height: 86px;
    color: #4d5a62; text-align: center; font-size: 13px; font-weight: 700;
}
.local-seo-feature .flow-arrow .arrow { font-size: 42px; color: var(--accent); line-height: .72; margin-top: 6px; }
.local-seo-feature .flow-arrow .caption { 
    background: #eef9f4; color: #226f53; 
    padding: 6px 11px; border-radius: 999px; border: 1px solid #cfe9dc;
    font-size: 13px;
}

/* Actions bar */
.local-seo-feature .actions-bar {
    display: flex; justify-content: space-between; gap: 10px; align-items: center;
    margin-top: 19px; padding-top: 16px;
    border-top: 1px solid var(--line);
    flex-wrap: wrap;
}

/* Loader overlay for long-running AI actions */
.vb-loader-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(3px);
    z-index: 9999;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 18px;
}
.vb-loader-overlay.active { display: flex; }
.vb-loader-overlay .spinner {
    width: 56px; height: 56px;
    border: 4px solid #e5e7eb;
    border-top-color: #1d8d64;
    border-radius: 50%;
    animation: vb-spin 1s linear infinite;
}
@keyframes vb-spin { to { transform: rotate(360deg); } }
.vb-loader-overlay .message {
    font-size: 16px; font-weight: 700; color: #0a0b0c;
    text-align: center; max-width: 320px; line-height: 1.5;
}
.vb-loader-overlay .sub-message {
    font-size: 14px; color: #374151; font-weight: 500;
}

/* Table wrap */
.local-seo-feature .table-wrap {
    overflow: auto;
    border: 1px solid var(--line);
    border-radius: 10px;
}
.local-seo-feature .table-wrap table { margin-bottom: 0; min-width: 700px; }

/* Badge styles */
.local-seo-feature .badge-high { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #e7f6ef; color: #176347; }
.local-seo-feature .badge-medium { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #fff5d8; color: #765303; }
.local-seo-feature .badge-low { display: inline-block; border-radius: 999px; padding: 3px 7px; font-size: 10px; font-weight: 800; background: #eef1f4; color: #5c656d; }

/* Row spacing - tighter like mockup */
.local-seo-feature .row.mb-4 { margin-bottom: 0 !important; }
.local-seo-feature .row.g-3 { gap: 13px; }
</style>
@endsection

@section('content')
<div class="container-fluid local-seo-feature">

    {{-- LOADER OVERLAY --}}
    <div id="vbLoader" class="vb-loader-overlay">
        <div class="spinner"></div>
        <div class="message"><strong>Villa Bit AI</strong> is building your campaign…</div>
        <div class="sub-message">This requires up to one minute of processing time. Please do not close the page.</div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Back link when editing/creating --}}
    @if(request('create_campaign') || request('edit_campaign_id'))
    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:16px;">← Back to Campaigns</a>
    @endif

    {{-- ============ MAIN SETTINGS CARD ============ --}}
    <div class="card" style="margin-bottom:26px;">
        <div class="card-header">
            <div>
                <h5 style="font-size:18px;">{{ __('messages.local_seo') }}</h5>
                <p style="margin:4px 0 0;color:var(--muted);font-size:13px;">{{ __('messages.feature_status') }}</p>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="featureToggle"
                    {{ $featureSetting && $featureSetting->is_enabled ? 'checked' : '' }}
                    style="width: 3em; height: 1.5em;"
                    onchange="toggleFeature(this)">
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('agency.local-seo.save-settings') }}" method="POST" id="settingsForm">
                @csrf
                <input type="hidden" name="feature" value="local_seo_presence_boost">
                <input type="hidden" name="is_enabled" id="isEnabledInput" value="{{ $featureSetting && $featureSetting->is_enabled ? '1' : '0' }}">

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:13px;">
                    <div>
                        <label class="form-label">{{ __('messages.feature_status') }}</label>
                        <div class="form-control" style="background:var(--soft);" id="statusDisplay">
                            <span style="font-weight:750;color:{{ $featureSetting && $featureSetting->is_enabled ? '#0a0b0c' : 'var(--muted)' }}">
                                <i class="fa {{ $featureSetting && $featureSetting->is_enabled ? 'fa-check-circle' : 'fa-circle-o' }}" style="margin-right:8px;"></i>
                                {{ $featureSetting && $featureSetting->is_enabled ? __('messages.on_collecting_leads') : __('messages.off_not_active') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.ai_posting_language') }}</label>
                        <div class="form-control" style="background:var(--soft);">
                            <span style="font-weight:750;">{{ $profile->ai_content_language ?? 'English' }}</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">{{ __('messages.uniqueness_status') }}</label>
                        <div class="form-control" style="background:var(--soft);">
                            <span style="font-weight:750;">{{ \App\Http\Controllers\Agency\AgencySettingsController::uniquenessCheckMethods()[$profile->uniqueness_check_method ?? 'villabit_ai'] ?? __('messages.passed_before_publish') }}</span>
                        </div>
                    </div>
                </div>
            </form>


    {{-- ============ ACTION BUTTONS BAR ============ --}}
    @php $canUseAiGlobal = ($usageLimitStatus['can_use_today'] ?? true); @endphp
    <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
        @if($canUseAiGlobal)
        <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?create_campaign=1" class="btn btn-accent">
            <i class="fa fa-magic me-1"></i> Add Campaign
        </a>
        @else
        <span class="btn" style="background:#9ca3af;color:#fff;cursor:not-allowed;opacity:0.7;" title="Daily limit reached - try again tomorrow">
            <i class="fa fa-magic me-1"></i> Add Campaign
        </span>
        @endif
        <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?add_listing=1" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">
            <i class="fa fa-plus me-1"></i> Add Listing
        </a>
        <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?show_listings=1" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">
            <i class="fa fa-list me-1"></i> Show Listings
        </a>
    </div>
        </div>
    </div>

    {{-- ============ USAGE LIMIT WARNING ============ --}}
    @if(isset($usageLimitStatus) && !($usageLimitStatus['can_use_today'] ?? true))
    <div style="background:#fef3cd;border:1px solid #ffc107;border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <i class="fa fa-exclamation-triangle" style="color:#856404;"></i>
        <div>
            <strong style="color:#856404;">AI Usage Limit Reached</strong>
            <p style="margin:4px 0 0;font-size:13px;color:#856404;">
                @if(($usageLimitStatus['daily_remaining'] ?? 0) <= 0)
                    Daily limit reached ({{ $usageLimitStatus['daily_used'] ?? 0 }}/{{ $usageLimitStatus['daily_limit'] ?? 1 }} today). Try again tomorrow.
                @else
                    Monthly limit reached ({{ $usageLimitStatus['monthly_used'] ?? 0 }}/{{ $usageLimitStatus['monthly_limit'] ?? 0 }} this month). Upgrade your plan for more.
                @endif
            </p>
        </div>
    </div>
    @endif


    {{-- ============ CAMPAIGNS TABLE (hidden when adding listing or showing listings) ============ --}}
    @if(!request('add_listing') && !request('show_listings'))
    <div class="card">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">●</span>Your Campaigns</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Manage your Local SEO campaigns</p>
            </div>
            @if(request('create_campaign') || request('edit_campaign_id'))
            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">
                ← Back
            </a>
            @endif
        </div>
        <div class="card-body" style="padding:0;">
                    @if($campaigns->count() > 0)
                        <div class="table-wrap">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px">Use</th>
                                        <th>Campaign</th>
                                        <th>Market</th>
                                        <th>Coverage</th>
                                        <th>Listings</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campaigns as $campaign)
                                    <tr>
                                        <td>
                                            <form action="{{ route('agency.local-seo.campaigns.toggle', $campaign) }}" method="POST">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" onchange="this.form.submit()"
                                                           {{ $campaign->status === 'published' ? 'checked' : '' }}>
                                                </div>
                                            </form>
                                        </td>
                                        <td><strong>{{ $campaign->name }}</strong></td>
                                        <td>{{ $campaign->primary_city ?? '—' }}</td>
                                        <td>{{ $campaign->coverage_area ? $campaign->coverage_area . ' ' . $campaign->coverage_unit : '—' }}</td>
                                        <td><span class="badge bg-secondary">{{ $campaign->nearbyListings()->count() }}</span></td>
                                        <td>
                                            @if($campaign->status === 'published')
                                                <span class="badge badge-high">Active</span>
                                            @elseif($campaign->status === 'unpublished')
                                                <span class="badge badge-medium">Unpublished</span>
                                            @else
                                                <span class="badge badge-low">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('agency.local-seo.campaigns.toggle', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $campaign->status === 'published' ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $campaign->status === 'published' ? 'Pause campaign' : 'Activate campaign' }}">
                                                    <i class="fa {{ $campaign->status === 'published' ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('agency.local-seo.campaigns.preview', $campaign) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Preview</a>
                                            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?edit_campaign_id={{ $campaign->id }}" class="btn btn-sm btn-dark">Edit</a>
                                            <form action="{{ route('agency.local-seo.campaigns.destroy', $campaign) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this campaign?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-3">No campaigns yet.</p>
                            @if($canUseAiGlobal ?? true)
                            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?create_campaign=1" class="btn btn-accent">
                                <i class="fa fa-magic me-1"></i> Create Your First Campaign
                            </a>
                            @else
                            <span class="btn" style="background:#9ca3af;color:#fff;cursor:not-allowed;opacity:0.7;" title="Daily limit reached - try again tomorrow">
                                <i class="fa fa-magic me-1"></i> Create Your First Campaign
                            </span>
                            @endif
                        </div>
                    @endif
        </div>
    </div>
    @endif {{-- End of campaigns table conditional --}}

    {{-- ============ SECTION 1: Define Campaign (only shown when creating/editing) ============ --}}
    @if(request('create_campaign') || $editCampaign)
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">1</span>{{ $editCampaign ? 'Edit Campaign' : 'Define Your Local SEO Campaign' }}</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">These rules tell AI where the agency works, what it sells, whom it wants to reach.</p>
            </div>
            <span class="output-badge">OUTPUT → Campaign rules</span>
        </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.campaigns.store') }}" method="POST" id="campaignForm">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $editCampaign->id ?? '' }}">
                        <input type="hidden" name="primary_city" id="primaryCity" value="{{ $editCampaign->primary_city ?? '' }}">
                        <input type="hidden" name="country" id="primaryCountry" value="{{ $editCampaign->country ?? '' }}">
                        <input type="hidden" name="latitude" id="primaryLat" value="{{ $editCampaign->latitude ?? '' }}">
                        <input type="hidden" name="longitude" id="primaryLng" value="{{ $editCampaign->longitude ?? '' }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Campaign Name *</label>
                                <input type="text" name="name" class="form-control" required
                                       value="{{ $editCampaign->name ?? '' }}"
                                       placeholder="E.g. Split & Dalmatia Luxury Property 2026">
                                <small class="text-muted">Internal name. Used to find this campaign later.</small>
                            </div>
                            <div class="col-md-6 position-relative">
                                <label class="form-label text-muted small fw-bold">Primary Market / Main City * <span style="color:#6b7280;font-weight:400;">/ City Area / Street</span></label>
                                <input type="text" id="citySearch" class="form-control" autocomplete="off"
                                       value="{{ $editCampaign ? trim(($editCampaign->primary_city ?? '') . ($editCampaign->country ? ', ' . $editCampaign->country : '')) : '' }}"
                                       placeholder="Start typing a city, area or street…">
                                <div id="citySuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display:none; max-height: 240px; overflow-y:auto;"></div>
                                <small class="text-muted">Location autocomplete. Saves city or country or coordinates.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">Coverage Area *</label>
                                <div class="input-group">
                                    <input type="number" name="coverage_area" id="coverageArea" class="form-control" min="1" max="1000"
                                           value="{{ $editCampaign->coverage_area ?? 50 }}">
                                    <select name="coverage_unit" id="coverageUnit" class="form-select" style="max-width: 90px;">
                                        <option value="km" {{ ($editCampaign->coverage_unit ?? 'km') === 'km' ? 'selected' : '' }}>km</option>
                                        <option value="mi" {{ ($editCampaign->coverage_unit ?? '') === 'mi' ? 'selected' : '' }}>miles</option>
                                    </select>
                                </div>
                                <small class="text-muted">AI suggests relevant places inside this boundary.</small>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label text-muted small fw-bold d-flex justify-content-between align-items-center">
                                    <span>Places Inside Coverage Area</span>
                                    <button type="button" class="btn btn-sm btn-outline-dark" id="suggestPlacesBtn">
                                        <i class="fa fa-magic me-1"></i>AI suggest places
                                    </button>
                                </label>
                                <div id="placesList" class="border rounded p-2 bg-light" style="min-height: 60px; max-height: 220px; overflow-y:auto;">
                                    @php $existingPlaces = $editCampaign->target_places ?? []; @endphp
                                    @if(!empty($existingPlaces))
                                        @foreach($existingPlaces as $i => $place)
                                            <label class="d-flex align-items-start gap-2 mb-1 place-item">
                                                <input type="checkbox" checked onchange="togglePlace(this)">
                                                <input type="hidden" name="target_places[{{ $i }}][name]" value="{{ $place['name'] ?? '' }}">
                                                <input type="hidden" name="target_places[{{ $i }}][type]" value="{{ $place['type'] ?? '' }}">
                                                <input type="hidden" name="target_places[{{ $i }}][distance]" value="{{ $place['distance'] ?? '' }}">
                                                <input type="hidden" name="target_places[{{ $i }}][reason]" value="{{ $place['reason'] ?? '' }}">
                                                <input type="hidden" name="target_places[{{ $i }}][priority]" value="{{ $place['priority'] ?? '' }}">
                                                <span><strong>{{ $place['name'] ?? '' }}</strong> <span class="text-muted small">{{ $place['type'] ?? '' }} · {{ $place['distance'] ?? '' }}</span></span>
                                            </label>
                                        @endforeach
                                    @else
                                        <p class="text-muted small mb-0" id="placesEmpty">Set a city + coverage, then click "AI suggest places".</p>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Agency-Specific Positioning Note</label>
                                <textarea name="positioning_note" class="form-control" rows="6"
                                          placeholder="Add extra guidance for the AI beyond the main strategy. E.g. Emphasize sea view, marinas, rental potential and trusted local guidance.">{{ $editCampaign->positioning_note ?? '' }}</textarea>
                                <small class="text-muted">This is added on top of the main Villa Bit AI prompt. The AI checks the main prompt and only adds your extra, non-duplicate specifications.</small>
                            </div>
                        </div>

                        <div class="actions-bar">
                            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">← Back to Campaigns</a>
                            <button type="submit" class="btn btn-accent">{{ $editCampaign ? 'Save Changes' : 'Create Campaign' }}</button>
                        </div>
                    </form>
        </div>
    </div>
    @endif

    {{-- ============ UNIQUENESS CHECKER (only when editing) ============ --}}
    @if($editCampaign)
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">2</span>Content Uniqueness Checker</h5>
                <p style="margin:7px 0 0;color:var  (--muted);font-size:14px;">Check AI-generated content for duplicates before publishing.</p>
            </div>
            <div id="copyscapeStatus" class="text-muted small">
                <i class="fa fa-spinner fa-spin"></i> Loading Copyscape status...
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                        @if(isset($campaigns) && $campaigns->count() > 0)
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Select campaign to check</label>
                            <div class="input-group">
                                <select id="campaignSelect" class="form-select">
                                    <option value="">-- Select a campaign --</option>
                                    @foreach($campaigns as $campaign)
                                    <option value="{{ $campaign->id }}" data-name="{{ $campaign->name }}">
                                        {{ $campaign->name }} ({{ $campaign->primary_city ?? 'No city' }})
                                    </option>
                                    @endforeach
                                </select>
                                <button type="button" id="loadCampaignContent" class="btn btn-outline-dark">
                                    <i class="fa fa-download me-1"></i> Load Content
                                </button>
                            </div>
                            <small class="text-muted">Load AI-generated content from your campaign to check for uniqueness.</small>
                        </div>
                        <div class="col-12">
                            <hr class="my-2">
                        </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label text-muted small fw-bold">Content to check</label>
                            <textarea id="uniquenessText" class="form-control" rows="6" placeholder="Select a campaign above or paste content manually..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeInternal" checked disabled>
                                <label class="form-check-label" for="includeInternal">Internal check</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeGoogle" checked>
                                <label class="form-check-label" for="includeGoogle">Google 1st page <span class="badge bg-success">FREE</span></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="includeCopyscape">
                                <label class="form-check-label" for="includeCopyscape">Copyscape <span class="badge bg-secondary">~$0.03</span></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="autoRewrite" checked>
                                <label class="form-check-label" for="autoRewrite"><strong>Auto-rewrite if duplicate</strong> <span class="badge bg-dark">AI</span></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="button" id="runUniquenessCheck" class="btn btn-dark">
                                <i class="fa fa-search me-1"></i> Run Uniqueness Check
                            </button>
                        </div>
                    </div>

            <div id="uniquenessResult" class="mt-4" style="display:none;">
                <div class="alert" id="uniquenessAlert">
                    <strong id="uniquenessVerdict"></strong>
                    <p id="uniquenessSummary" class="mb-0 mt-1"></p>
                </div>
                <div id="uniquenessMatches" class="mt-3"></div>
            </div>
        </div>
    </div>

    {{-- ============ SECTION 3: Publishing ============ --}}
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">3</span>Publish</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Publish "{{ $editCampaign->name }}" to your connected domain.</p>
            </div>
            <span class="output-badge">OUTPUT → Published page</span>
        </div>
        <div class="card-body">
            <form action="{{ route('agency.local-seo.campaigns.publish', $editCampaign) }}" method="POST">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:13px;">
                    <div>
                        <label class="form-label">Publishing Domain</label>
                        <input type="text" class="form-control" style="background:var(--soft);" value="{{ $profile->custom_domain ?? 'Not connected yet' }}" readonly>
                    </div>
                    <div>
                        <label class="form-label">Page URL Slug</label>
                        <input type="text" name="page_slug" class="form-control"
                               value="{{ $editCampaign->page_slug ?? ('/' . \Illuminate\Support\Str::slug('real-estate-' . ($editCampaign->primary_city ?: $editCampaign->name)) . '/') }}">
                        <div class="help-text">Suggested automatically — you can change it.</div>
                    </div>
                </div>
                <div class="actions-bar">
                    <button type="button" id="checkAndPublish" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;" data-campaign-id="{{ $editCampaign->id }}">
                        <i class="fa fa-shield me-1"></i> Check Uniqueness First
                    </button>
                    <button type="submit" class="btn btn-accent">Publish Now</button>
                </div>
            </form>
        </div>
    </div>
    @endif {{-- End of editCampaign sections --}}

    {{-- Hidden per request (legacy sections) --}}
    @if(false)
    {{-- Agency Sub-Prompt --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">LOCAL SEO Optional Additional Agency-Specific Instructions</h5>
                    <small class="text-muted">Villa Bit AI Server already uses a complete expert AI prompt and proven logic for this feature. You can add additional suggestions that we can use as extra guidance to help the AI emphasize what matters most for your unique case.<br>These instructions do not replace or override the main Villa Bit AI Server strategy, quality controls, or platform rules. They simply add more specific AI targets and priorities for your real estate agency.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.save-settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="feature" value="local_seo_presence_boost">
                        <textarea name="agency_sub_prompt" class="form-control mb-3" rows="6" placeholder="E.g. Focus more on luxury sea-view villas. Emphasize proximity to marinas. Target English-speaking buyers from UK and Germany.">{{ old('agency_sub_prompt', $featureSetting->agency_sub_prompt ?? '') }}</textarea>
                        <button type="submit" class="btn btn-dark">{{ __('messages.save') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Target Generation --}}
    @if($featureSetting && $featureSetting->is_enabled)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.local_seo_attack_targets') }}</h5>
                    <small class="text-muted">{{ __('messages.generate_city_keyword_subniche_lists') }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.generate-targets') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.generate_for_city') }}</label>
                                <input type="text" name="generate_city" class="form-control" value="{{ $profile->target_city ?? '' }}" placeholder="{{ __('messages.city_name') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.add_custom_cities') }}</label>
                                <input type="text" name="custom_cities" class="form-control" placeholder="{{ __('messages.custom_cities_placeholder') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="fa fa-magic me-1"></i>{{ __('messages.generate_targets') }}
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">{{ __('messages.custom_cities_help') }}</small>
                    </form>

                    @if($cities->count() > 0 || $keywords->count() > 0 || $subniches->count() > 0)
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <h6 class="fw-bold">{{ __('messages.target_cities') }} ({{ $cities->count() }})</h6>
                                <div class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($cities as $city)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_cities[]" value="{{ $city->id }}" {{ $city->is_selected ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark">{{ $city->target_value }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="fw-bold">{{ __('messages.target_keywords') }} ({{ $keywords->count() }})</h6>
                                <div class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($keywords as $keyword)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_keywords[]" value="{{ $keyword->id }}" {{ $keyword->is_selected ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark">{{ $keyword->target_value }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <h6 class="fw-bold">{{ __('messages.target_subniches') }} ({{ $subniches->count() }})</h6>
                                <div class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                                    @foreach($subniches as $subniche)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_subniches[]" value="{{ $subniche->id }}" {{ $subniche->is_selected ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark">{{ $subniche->target_value }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('agency.local-seo.generate-pages') }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-dark">
                                <i class="fa fa-file-text-o me-1"></i>{{ __('messages.generate_local_seo_pages') }}
                            </button>
                            <small class="text-muted ms-2">{{ __('messages.generates_pages_for_selected_targets') }}</small>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">{{ __('messages.no_targets_generated_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif {{-- End of @if(false) legacy sections --}}

    {{-- ============ VILLA BIT AI OFFICE - Page Settings (only when editing) ============ --}}
    @if($editCampaign)
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">4</span>Villa Bit AI Office</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Configure how your published page will look.</p>
            </div>
            <span class="output-badge">OUTPUT → Page settings</span>
        </div>
        <div class="card-body">
                    <form action="{{ route('agency.local-seo.campaigns.update-settings', $editCampaign) }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            {{-- Page Sections Toggle --}}
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Page Sections</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="showLeadMagnet" name="show_lead_magnet" value="1"
                                                {{ ($editCampaign->page_settings['show_lead_magnet'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="showLeadMagnet">
                                                <i class="fa fa-magnet me-1"></i> Show Invisible Lead Magnet
                                            </label>
                                            <small class="d-block text-muted">Display lead capture form on the published page</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="showFaq" name="show_faq" value="1"
                                                {{ ($editCampaign->page_settings['show_faq'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="showFaq">
                                                <i class="fa fa-question-circle me-1"></i> Show FAQ Section
                                            </label>
                                            <small class="d-block text-muted">Display 6 AI-generated FAQ questions</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="showListings" name="show_listings" value="1"
                                                {{ ($editCampaign->page_settings['show_listings'] ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="showListings">
                                                <i class="fa fa-home me-1"></i> Show Listings Section
                                            </label>
                                            <small class="d-block text-muted">Display property listings on the page</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Listings Distribution --}}
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">Listings Distribution</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">Featured Listings (%)</label>
                                        <input type="number" name="featured_listings_percent" class="form-control" 
                                               value="{{ $editCampaign->page_settings['featured_listings_percent'] ?? 10 }}" min="0" max="100">
                                        <small class="text-muted">Percentage of listings shown as featured (larger cards)</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small fw-bold">Regular Listings (%)</label>
                                        <input type="number" name="regular_listings_percent" class="form-control" 
                                               value="{{ $editCampaign->page_settings['regular_listings_percent'] ?? 6 }}" min="0" max="100">
                                        <small class="text-muted">Percentage of listings shown in regular grid</small>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="actions-bar">
                            <span></span>
                            <button type="submit" class="btn btn-accent">Save Page Settings</button>
                        </div>
                    </form>
        </div>
    </div>

    @endif {{-- End of Villa Bit AI Office --}}

    {{-- ============ SHOW LISTINGS TABLE (only when show_listings) ============ --}}
    @if(request('show_listings'))
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:8px;">← Back</a>
                <h5><span class="step-circle">●</span>Your Listings</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">All property listings across your campaigns</p>
            </div>
            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?add_listing=1" class="btn btn-accent">
                <i class="fa fa-plus me-1"></i> Add Listing
            </a>
        </div>
        <div class="card-body" style="padding:0;">
            @if($listings->count() > 0)
                <div class="table-wrap">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th style="width:80px;">Preview</th>
                                <th>Title</th>
                                <th>Campaign</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listings as $listing)
                            <tr>
                                <td>
                                    @if(count($listing->images) > 0)
                                        <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="" style="width:60px;height:45px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <div style="width:60px;height:45px;background:#f5f6f7;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#ccc;"><i class="fa fa-image"></i></div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $listing->title }}</strong>
                                    @if($listing->property_type)
                                    <br><span style="font-size:11px;color:var(--muted);">{{ $listing->property_type }}</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($listing->campaigns as $camp)
                                        <span class="badge bg-light text-dark" style="font-size:10px;">{{ $camp->name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $listing->location ?? '—' }}</td>
                                <td>
                                    @if($listing->price)
                                        <strong>{{ number_format($listing->price, 0, ',', '.') }}</strong> {{ $listing->currency ?? 'EUR' }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end" style="white-space:nowrap;">
                                    <button type="button" class="btn btn-sm btn-outline-dark" onclick="previewListing({{ $listing->id }})">Preview</button>
                                    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?edit_listing={{ $listing->id }}" class="btn btn-sm btn-dark">Edit</a>
                                    <form action="{{ route('agency.local-seo.listings.destroy', $listing) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this listing?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted mb-3">No listings yet.</p>
                    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?add_listing=1" class="btn btn-accent">
                        <i class="fa fa-plus me-1"></i> Add Your First Listing
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Listing Preview Modal --}}
    <div id="listingPreviewModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:16px;max-width:800px;width:90%;max-height:90vh;overflow:auto;position:relative;">
            <button onclick="closePreviewModal()" style="position:absolute;top:12px;right:12px;background:none;border:none;font-size:24px;cursor:pointer;color:#666;">×</button>
            <div id="listingPreviewContent" style="padding:24px;"></div>
        </div>
    </div>

    @php
        $listingsJson = [];
        foreach($listings as $listing) {
            $listingsJson[$listing->id] = [
                'title' => $listing->title,
                'property_type' => $listing->property_type ?? '',
                'location' => $listing->location ?? '',
                'price' => $listing->price ? number_format($listing->price, 0, ',', '.') . ' ' . ($listing->currency ?? 'EUR') : '',
                'description' => $listing->description ?? '',
                'images' => $listing->images ?? [],
                'campaigns' => $listing->campaigns->pluck('name')->toArray()
            ];
        }
    @endphp
    <script>
    var listingsData = {!! json_encode($listingsJson) !!};

    function previewListing(id) {
        var data = listingsData[id];
        if (!data) return;
        
        var imagesHtml = '';
        if (data.images && data.images.length > 0) {
            imagesHtml = '<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">';
            data.images.forEach(function(img) {
                var imgSrc = img.startsWith('http') ? img : '/storage/' + img;
                imagesHtml += '<img src="' + imgSrc + '" style="width:150px;height:110px;object-fit:cover;border-radius:8px;" onerror="this.style.display=\'none\'">';
            });
            imagesHtml += '</div>';
        }

        var campaignsHtml = data.campaigns.map(function(c) {
            return '<span style="background:#f0f0f0;padding:2px 8px;border-radius:4px;font-size:11px;margin-right:4px;">' + c + '</span>';
        }).join('');

        var html = imagesHtml +
            '<h4 style="margin:0 0 8px;font-size:20px;">' + data.title + '</h4>' +
            (data.property_type ? '<p style="color:#666;margin:0 0 12px;font-size:13px;">' + data.property_type + '</p>' : '') +
            '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">' +
                '<div><strong style="font-size:11px;color:#888;display:block;">Location</strong>' + (data.location || '—') + '</div>' +
                '<div><strong style="font-size:11px;color:#888;display:block;">Price</strong>' + (data.price || '—') + '</div>' +
            '</div>' +
            (data.description ? '<div><strong style="font-size:11px;color:#888;display:block;">Description</strong><p style="margin:4px 0 0;font-size:14px;line-height:1.5;">' + data.description + '</p></div>' : '') +
            (campaignsHtml ? '<div style="margin-top:16px;"><strong style="font-size:11px;color:#888;display:block;margin-bottom:6px;">Campaigns</strong>' + campaignsHtml + '</div>' : '');

        var previewContent = document.getElementById('listingPreviewContent');
        var previewModalEl = document.getElementById('listingPreviewModal');
        if (previewContent) previewContent.innerHTML = html;
        if (previewModalEl) previewModalEl.style.display = 'flex';
    }

    function closePreviewModal() {
        var modal = document.getElementById('listingPreviewModal');
        if (modal) modal.style.display = 'none';
    }

    var previewModal = document.getElementById('listingPreviewModal');
    if (previewModal) {
        previewModal.addEventListener('click', function(e) {
            if (e.target === this) closePreviewModal();
        });
    }
    </script>
    @endif

    {{-- ============ EDIT LISTING FORM ============ --}}
    @if(request('edit_listing'))
    @php $editListing = \App\Models\AgencyListing::find(request('edit_listing')); @endphp
    @if($editListing)
    <div class="card">
        <div class="card-header">
            <div>
                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?show_listings=1" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:8px;">← Back to Listings</a>
                <h5><span class="step-circle">●</span>Edit Listing</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Update listing details</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('agency.local-seo.listings.update', $editListing) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Campaigns</label>
                        <select name="campaign_ids[]" class="form-select select2-campaigns" multiple>
                            @foreach($campaigns as $campaignOption)
                                <option value="{{ $campaignOption->id }}"
                                    {{ $editListing->campaigns->contains($campaignOption->id) ? 'selected' : '' }}>
                                    {{ $campaignOption->name }}{{ $campaignOption->primary_city ? ' — ' . $campaignOption->primary_city : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Optional. Campaign pages will auto-show listings within their coverage radius.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Listing Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $editListing->title }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property Type</label>
                        <input type="text" name="property_type" class="form-control" value="{{ $editListing->property_type }}">
                    </div>
                    <div class="col-md-4 position-relative">
                        <label class="form-label">Location *</label>
                        <input type="hidden" name="location" id="editListingLocation" value="{{ $editListing->location }}">
                        <input type="hidden" name="primary_city" id="editListingCity" value="{{ $editListing->primary_city }}">
                        <input type="hidden" name="country" id="editListingCountry" value="{{ $editListing->country }}">
                        <input type="hidden" name="latitude" id="editListingLat" value="{{ $editListing->latitude }}">
                        <input type="hidden" name="longitude" id="editListingLng" value="{{ $editListing->longitude }}">
                        <input type="text" id="editListingSearch" class="form-control" autocomplete="off"
                               value="{{ trim(($editListing->location ?? '') . ($editListing->country ? ', ' . $editListing->country : ''), ', ') }}"
                               placeholder="Start typing a city or area…">
                        <div id="editListingSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display:none; max-height: 240px; overflow-y:auto;"></div>
                        <small class="text-muted">Autocomplete saves city + coordinates for radius filtering.</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" value="{{ $editListing->price }}" min="0">
                            <select name="currency" class="form-select" style="max-width:80px;">
                                <option value="EUR" {{ $editListing->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="USD" {{ $editListing->currency === 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="GBP" {{ $editListing->currency === 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Living Area (m²)</label>
                        <input type="number" name="living_area" class="form-control" value="{{ $editListing->living_area }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Plot Size (m²)</label>
                        <input type="number" name="plot_size" class="form-control" value="{{ $editListing->plot_size }}" min="0" step="0.01">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bedrooms / Bathrooms</label>
                        <div class="input-group">
                            <input type="number" name="bedrooms" class="form-control" value="{{ $editListing->bedrooms }}" min="0" placeholder="Beds">
                            <input type="number" name="bathrooms" class="form-control" value="{{ $editListing->bathrooms }}" min="0" placeholder="Baths">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ $editListing->description }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year Built</label>
                        <input type="number" name="year_built" class="form-control" value="{{ $editListing->year_built }}" min="1800" max="{{ date('Y') + 5 }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-block">Turnkey Ready</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_turnkey" value="1" {{ $editListing->is_turnkey ? 'checked' : '' }}>
                            <label class="form-check-label">Yes, ready to move in</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Images</label>
                        @if($editListing->images && count($editListing->images) > 0)
                        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px;">
                            @foreach($editListing->images as $img)
                            <div style="position:relative;">
                                <img src="{{ asset('storage/' . $img) }}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Upload new images to replace existing ones.</small>
                    </div>
                </div>
                <div class="actions-bar">
                    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?show_listings=1" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">Cancel</a>
                    <button type="submit" class="btn btn-accent">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif

    {{-- ============ ADD LISTING FORM (only when add_listing or editing campaign) ============ --}}
    @if(request('add_listing') || $editCampaign)
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                @if(request('add_listing'))
                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" style="font-size:13px;color:var(--muted);text-decoration:none;display:inline-block;margin-bottom:8px;">← Back</a>
                @endif
                <h5><span class="step-circle">{{ $editCampaign ? '5' : '●' }}</span>Add Listing</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Add real estate listings to enhance your Local SEO pages</p>
            </div>
            <span class="output-badge">OUTPUT → Unique source data</span>
        </div>
        <div class="card-body">
            <form action="{{ route('agency.local-seo.listings.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Listing Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Luxury Villa with Sea View" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property Type</label>
                        <input type="text" name="property_type" class="form-control" placeholder="e.g. Villa, Apartment, Land">
                    </div>
                    <div class="col-md-4 position-relative">
                        <label class="form-label">Location *</label>
                        <input type="hidden" name="location" id="addListingLocation">
                        <input type="hidden" name="primary_city" id="addListingCity">
                        <input type="hidden" name="country" id="addListingCountry">
                        <input type="hidden" name="latitude" id="addListingLat">
                        <input type="hidden" name="longitude" id="addListingLng">
                        <input type="text" id="addListingSearch" class="form-control" autocomplete="off"
                               placeholder="Start typing a city or area…">
                        <div id="addListingSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display:none; max-height: 240px; overflow-y:auto;"></div>
                        <small class="text-muted">Autocomplete saves city + coordinates for radius filtering.</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" placeholder="0" min="0">
                            <select name="currency" class="form-select" style="max-width:80px;">
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Describe the property, features, and unique selling points..."></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Images</label>
                        <div id="imageUploadContainer">
                            <div class="image-upload-row mb-2">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addImageField()">+ Add Image</button>
                        <small class="text-muted d-block mt-1">Max 5MB per image.</small>
                    </div>
                </div>
                <div class="actions-bar">
                    <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?show_listings=1" class="btn" style="background:#fff;color:#26303a;border:1px solid #cfd4d9;">View All Listings</a>
                    <button type="submit" class="btn btn-accent">+ Add Listing</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ============ EXISTING LISTINGS TABLE (only when editing campaign - compact version) ============ --}}
    @php
        $campaignListings = $editCampaign ? $editCampaign->nearbyListings()->get() : collect();
    @endphp
    @if($editCampaign && $campaignListings->count() > 0)
    <div class="card" style="margin-top:26px;">
        <div class="card-header">
            <div>
                <h5><span class="step-circle">6</span>Campaign Listings</h5>
                <p style="margin:7px 0 0;color:var(--muted);font-size:14px;">Listings within {{ $editCampaign->coverage_area }} {{ $editCampaign->coverage_unit }} of {{ $editCampaign->primary_city ?? 'this location' }}</p>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <div class="table-wrap">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">Preview</th>
                            <th>Title</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaignListings as $listing)
                        <tr>
                            <td>
                                @if(count($listing->images) > 0)
                                    <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="" style="width:50px;height:38px;object-fit:cover;border-radius:4px;">
                                @else
                                    <div style="width:50px;height:38px;background:#f5f6f7;border-radius:4px;display:flex;align-items:center;justify-content:center;color:#ccc;font-size:10px;"><i class="fa fa-image"></i></div>
                                @endif
                            </td>
                            <td><strong>{{ $listing->title }}</strong></td>
                            <td>{{ $listing->location ?? '—' }}</td>
                            <td>
                                @if($listing->price)
                                    {{ number_format($listing->price, 0, ',', '.') }} {{ $listing->currency ?? 'EUR' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                <form action="{{ route('agency.local-seo.listings.destroy', $listing) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Hidden per request (legacy sections) --}}
    @if(false)
    {{-- Pending Suggestions Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning bg-opacity-10 border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="fa fa-lightbulb-o me-2"></i>{{ __('messages.pending_suggestions') }}</h5>
                        <small class="text-muted">{{ __('messages.local_seo_suggestions_ready') }}</small>
                    </div>
                    <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-inbox me-1"></i>{{ __('messages.daily_ai_employee') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @php
                        $pendingSuggestions = $profile ? $profile->aiSuggestions()
                            ->where('feature_key', 'local_seo_presence_boost')
                            ->where('status', 'pending')
                            ->latest()
                            ->paginate(10) : collect();
                    @endphp
                    @if($pendingSuggestions->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="fa fa-lightbulb-o fa-2x mb-2 d-block text-muted opacity-50"></i>
                        <p class="mb-0 small">{{ __('messages.no_pending_suggestions') }}</p>
                    </div>
                    @else
                    <div class="p-3">
                        @foreach($pendingSuggestions as $suggestion)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $suggestion->title }}</h6>
                                    <small class="text-muted">{{ $suggestion->content_json['target_city'] ?? 'Unknown City' }} • {{ $suggestion->ai_summary }}</small>
                                </div>
                                <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                            </div>
                            <div class="d-flex gap-3 mt-3">
                                <form action="{{ route('agency.local-seo.suggestions.accept', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm px-3">
                                        <i class="fas fa-check-circle me-2"></i>{{ __('messages.accept') }}
                                    </button>
                                </form>
                                <form action="{{ route('agency.local-seo.suggestions.skip', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning btn-sm px-3">
                                        <i class="fas fa-forward me-2"></i>{{ __('messages.skip') }}
                                    </button>
                                </form>
                                <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-inbox me-2"></i>{{ __('messages.review_in_ai_employee') }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    
                    {{-- Pagination for pending suggestions --}}
                    @if($pendingSuggestions->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                            <small class="text-muted">
                                Showing {{ $pendingSuggestions->firstItem() }} to {{ $pendingSuggestions->lastItem() }} of {{ $pendingSuggestions->total() }} pending suggestions
                            </small>
                            @include('partials.pagination', ['paginator' => $pendingSuggestions])
                        </div>
                    @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Generated Pages Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.generated_pages') }}</h5>
                        <small class="text-muted">{{ __('messages.local_seo_pages_for_review') }}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        @php
                            $usageLimit = $profile->currentUsageLimit;
                            $used = $usageLimit?->local_seo_pages_used ?? 0;
                            $limit = $usageLimit?->local_seo_pages_limit ?? 10;
                            $remaining = max(0, $limit - $used);
                        @endphp
                        <span class="badge bg-secondary text-white fs-6">{{ $used }}/{{ $limit }} {{ __('messages.local_seo_pages') }} {{ __('messages.used') }}</span>
                        <span class="badge bg-dark text-white fs-6">{{ $pages->total() }} {{ __('messages.total_pages') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.title') }}</th>
                                        <th>{{ __('messages.target_city') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th style="width: 200px;">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                    <tr>
                                        <td><strong>{{ $page->title }}</strong></td>
                                        <td>{{ $page->content_json['target_city'] ?? '—' }}</td>
                                        <td>
                                            @if($page->status === 'published')
                                                <span class="badge bg-dark">{{ __('messages.published') }}</span>
                                            @elseif($page->status === 'pending_review')
                                                <span class="badge bg-secondary">{{ __('messages.pending_review') }}</span>
                                            @else
                                                <span class="badge bg-light text-dark border">{{ ucfirst($page->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $page->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('agency.local-seo.pages.preview', $page) }}" class="btn btn-outline-dark">{{ __('messages.preview') }}</a>
                                                <a href="{{ route('agency.local-seo.pages.edit', $page) }}" class="btn btn-outline-secondary">{{ __('messages.edit') }}</a>
                                                @if($page->status !== 'published')
                                                <form action="{{ route('agency.local-seo.pages.publish', $page) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark">{{ __('messages.publish') }}</button>
                                                </form>
                                                @endif
                                                <form action="{{ route('agency.local-seo.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete_page') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">{{ __('messages.delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            @include('partials.pagination', ['paginator' => $pages])
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">{{ __('messages.no_pages_generated_yet') }}</h5>
                            <p class="text-muted">{{ __('messages.generate_your_first_local_seo_pages') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function toggleFeature(checkbox) {
        var isEnabled = checkbox.checked ? '1' : '0';
        document.getElementById('isEnabledInput').value = isEnabled;

        var statusDisplay = document.getElementById('statusDisplay');
        if (checkbox.checked) {
            statusDisplay.innerHTML = '<span class="fw-bold text-dark"><i class="fa fa-check-circle me-2"></i>{{ __('messages.on_collecting_leads') }}</span>';
        } else {
            statusDisplay.innerHTML = '<span class="fw-bold text-muted"><i class="fa fa-circle-o me-2"></i>{{ __('messages.off_not_active') }}</span>';
        }
        // Persist immediately since the Save button is hidden.
        document.getElementById('settingsForm').submit();
    }

    // ---- Primary Market / Main City autocomplete (OpenStreetMap Nominatim) ----
    (function () {
        var input = document.getElementById('citySearch');
        var box = document.getElementById('citySuggestions');
        if (!input || !box) return;

        var timer = null;

        function hideBox() { box.style.display = 'none'; box.innerHTML = ''; }

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);
            if (q.length < 3) { hideBox(); return; }
            timer = setTimeout(function () {
                // Search without featuretype restriction to find neighborhoods, suburbs, districts, etc.
                fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=8&q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function (r) { return r.json(); })
                .then(function (results) {
                    box.innerHTML = '';
                    if (!results || !results.length) { hideBox(); return; }
                    
                    // Filter to show relevant place types (streets, neighborhoods, suburbs, cities, etc.)
                    var validTypes = ['street', 'road', 'pedestrian', 'residential', 'suburb', 'neighbourhood', 'quarter', 'city_district', 'city', 'town', 'village', 'municipality', 'administrative'];
                    var validClasses = ['place', 'boundary', 'highway'];
                    var filtered = results.filter(function(item) {
                        return validTypes.some(function(t) { return item.type === t; }) || 
                               validClasses.some(function(c) { return item.class === c; });
                    });
                    
                    // If no filtered results, use all results
                    if (!filtered.length) filtered = results;
                    
                    filtered.slice(0, 6).forEach(function (item) {
                        var addr = item.address || {};
                        var country = addr.country || '';
                        // Get the most specific place name (street, neighborhood, suburb, quarter, city_district, etc.)
                        var placeName = addr.road || addr.street || addr.pedestrian ||
                                        addr.neighbourhood || addr.suburb || addr.quarter || addr.city_district || 
                                        addr.city || addr.town || addr.village || addr.municipality || 
                                        item.display_name.split(',')[0];
                        // Get parent city for context
                        var parentCity = addr.city || addr.town || addr.municipality || '';
                        // Get neighborhood for street context
                        var neighborhood = addr.neighbourhood || addr.suburb || addr.quarter || '';
                        // Build full location name
                        var fullName = placeName;
                        // If it's a street, add neighborhood if available
                        if ((addr.road || addr.street) && neighborhood && neighborhood !== placeName) {
                            fullName = placeName + ', ' + neighborhood;
                        }
                        if (parentCity && parentCity !== placeName && parentCity !== neighborhood) {
                            fullName = fullName + ', ' + parentCity;
                        }
                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = item.display_name;
                        a.addEventListener('click', function () {
                            input.value = fullName + (country ? ', ' + country : '');
                            document.getElementById('primaryCity').value = fullName;
                            document.getElementById('primaryCountry').value = country;
                            document.getElementById('primaryLat').value = item.lat || '';
                            document.getElementById('primaryLng').value = item.lon || '';
                            hideBox();
                        });
                        box.appendChild(a);
                    });
                    box.style.display = 'block';
                })
                .catch(hideBox);
            }, 350);
        });

        document.addEventListener('click', function (e) {
            if (!box.contains(e.target) && e.target !== input) hideBox();
        });
    })();

    // ---- AI suggest places inside coverage area ----
    function togglePlace(cb) {
        var label = cb.closest('.place-item');
        if (!label) return;
        label.querySelectorAll('input[type=hidden]').forEach(function (h) { h.disabled = !cb.checked; });
    }

    (function () {
        var btn = document.getElementById('suggestPlacesBtn');
        if (!btn) return;
        btn.addEventListener('click', function () {
            var city = document.getElementById('primaryCity').value || document.getElementById('citySearch').value;
            var country = document.getElementById('primaryCountry').value;
            var coverage = document.getElementById('coverageArea').value;
            var unit = document.getElementById('coverageUnit').value;
            var list = document.getElementById('placesList');

            if (!city || !coverage) { alert('Please set a city and coverage area first.'); return; }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Thinking…';

            fetch('{{ route('agency.local-seo.campaigns.suggest-places') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ primary_city: city, country: country, coverage_area: parseInt(coverage, 10), coverage_unit: unit })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var places = (data && data.places) || [];
                list.innerHTML = '';
                if (!places.length) {
                    list.innerHTML = '<p class="text-muted small mb-0">No suggestions returned. Check that the AI key is configured, or add places manually later.</p>';
                    return;
                }
                places.forEach(function (p, i) {
                    var label = document.createElement('label');
                    label.className = 'd-flex align-items-start gap-2 mb-1 place-item';
                    label.innerHTML =
                        '<input type="checkbox" checked onchange="togglePlace(this)">' +
                        '<input type="hidden" name="target_places[' + i + '][name]" value="' + (p.name || '').replace(/"/g, '&quot;') + '">' +
                        '<input type="hidden" name="target_places[' + i + '][type]" value="' + (p.type || '').replace(/"/g, '&quot;') + '">' +
                        '<input type="hidden" name="target_places[' + i + '][distance]" value="' + (p.distance || '').replace(/"/g, '&quot;') + '">' +
                        '<input type="hidden" name="target_places[' + i + '][reason]" value="' + (p.reason || '').replace(/"/g, '&quot;') + '">' +
                        '<input type="hidden" name="target_places[' + i + '][priority]" value="' + (p.priority || '').replace(/"/g, '&quot;') + '">' +
                        '<span><strong>' + (p.name || '') + '</strong> <span class="text-muted small">' + (p.type || '') + ' · ' + (p.distance || '') + ' · ' + (p.priority || '') + '</span></span>';
                    list.appendChild(label);
                });
            })
            .catch(function () {
                list.innerHTML = '<p class="text-danger small mb-0">Could not fetch suggestions. Please try again.</p>';
            })
            .finally(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-magic me-1"></i>AI suggest places';
            });
        });
    })();

    // ============ UNIQUENESS CHECKER ============
    (function() {
        var copyscapeStatusEl = document.getElementById('copyscapeStatus');
        var includeCopyscapeEl = document.getElementById('includeCopyscape');
        
        // Load Copyscape status on page load (only if elements exist)
        if (copyscapeStatusEl) {
            fetch('{{ route('agency.local-seo.copyscape-status') }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.configured) {
                    var balanceText = data.balance !== null ? ' (' + data.balance + ' credits)' : '';
                    copyscapeStatusEl.innerHTML = '<i class="fa fa-check-circle text-success"></i> Copyscape configured' + balanceText;
                    if (includeCopyscapeEl) includeCopyscapeEl.disabled = false;
                } else {
                    copyscapeStatusEl.innerHTML = '<i class="fa fa-exclamation-circle text-warning"></i> Copyscape not configured';
                    if (includeCopyscapeEl) includeCopyscapeEl.disabled = true;
                }
            })
            .catch(function() {
                copyscapeStatusEl.innerHTML = '<i class="fa fa-times-circle text-danger"></i> Status check failed';
            });
        }

        // Load campaign content for uniqueness check
        var loadBtn = document.getElementById('loadCampaignContent');
        if (loadBtn) {
            loadBtn.addEventListener('click', function() {
                var select = document.getElementById('campaignSelect');
                var campaignId = select.value;
                var btn = this;
                var textarea = document.getElementById('uniquenessText');

                if (!campaignId) {
                    alert('Please select a campaign first.');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Loading...';

                fetch('/agency/local-seo-presence-boost/campaigns/' + campaignId + '/content', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success && data.content) {
                        textarea.value = data.content;
                        textarea.style.backgroundColor = '#d4edda';
                        setTimeout(function() {
                            textarea.style.backgroundColor = '';
                        }, 1000);
                        // Show word count
                        var wordCount = data.word_count || 0;
                        var info = document.createElement('small');
                        info.className = 'text-success d-block mt-1';
                        info.innerHTML = '<i class="fa fa-check me-1"></i> Loaded "' + data.campaign_name + '" (' + wordCount + ' words)';
                        textarea.parentNode.appendChild(info);
                        setTimeout(function() { info.remove(); }, 5000);
                    } else {
                        var msg = data.message || 'Campaign has minimal content. Add positioning notes, target places, or listings to generate meaningful content for uniqueness checking.';
                        alert(msg);
                    }
                })
                .catch(function(err) {
                    alert('Failed to load campaign content.');
                    console.error(err);
                })
                .finally(function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-download me-1"></i> Load Content';
                });
            });
        }

        // Run uniqueness check
        var runUniquenessBtn = document.getElementById('runUniquenessCheck');
        if (runUniquenessBtn) runUniquenessBtn.addEventListener('click', function() {
            var text = document.getElementById('uniquenessText').value.trim();
            var includeGoogle = document.getElementById('includeGoogle').checked;
            var includeCopyscape = document.getElementById('includeCopyscape').checked;
            var autoRewrite = document.getElementById('autoRewrite').checked;
            var btn = this;
            var resultDiv = document.getElementById('uniquenessResult');
            var alertDiv = document.getElementById('uniquenessAlert');
            var verdictEl = document.getElementById('uniquenessVerdict');
            var summaryEl = document.getElementById('uniquenessSummary');
            var matchesEl = document.getElementById('uniquenessMatches');

            if (text.length < 50) {
                alert('Please enter at least 50 characters to check.');
                return;
            }

            btn.disabled = true;
            var checkingText = '<i class="fa fa-spinner fa-spin me-1"></i> Checking';
            if (includeGoogle) checkingText += ' Google';
            if (autoRewrite) checkingText += ' + AI rewrite ready';
            checkingText += '...';
            btn.innerHTML = checkingText;
            resultDiv.style.display = 'none';

            fetch('{{ route('agency.local-seo.check-uniqueness') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    text: text,
                    include_google: includeGoogle,
                    include_copyscape: includeCopyscape,
                    auto_rewrite: autoRewrite
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                resultDiv.style.display = 'block';

                // Set alert class based on verdict
                alertDiv.className = 'alert';
                if (data.overall_verdict === 'passed') {
                    alertDiv.classList.add('alert-success');
                    verdictEl.innerHTML = '<i class="fa fa-check-circle me-1"></i> Passed';
                } else if (data.overall_verdict === 'review') {
                    alertDiv.classList.add('alert-warning');
                    verdictEl.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i> Needs Review';
                } else if (data.overall_verdict === 'failed') {
                    alertDiv.classList.add('alert-danger');
                    verdictEl.innerHTML = '<i class="fa fa-times-circle me-1"></i> Failed';
                } else {
                    alertDiv.classList.add('alert-secondary');
                    verdictEl.innerHTML = '<i class="fa fa-question-circle me-1"></i> Error';
                }

                // Build detailed summary showing all checks
                var summaryParts = [];
                
                // Internal check result
                if (data.internal) {
                    var intVerdict = data.internal.verdict || 'unknown';
                    var intPercent = data.internal.repeated_new_text_percent || 0;
                    summaryParts.push('Internal: ' + intVerdict + ' (' + intPercent + '%)');
                }
                
                // Google check result
                if (data.google) {
                    var googleVerdict = data.google.verdict || 'unknown';
                    var googleSimilar = data.google.max_similarity_percent || 0;
                    summaryParts.push('Google: ' + googleVerdict + ' (' + googleSimilar + '% similar)');
                }
                
                // Copyscape check result
                if (data.copyscape) {
                    var csVerdict = data.copyscape.verdict || 'unknown';
                    var csPercent = data.copyscape.percent_matched || 0;
                    var csCredits = data.copyscape.credits_remaining;
                    var csText = 'Copyscape: ' + csVerdict + ' (' + csPercent + '% matched)';
                    if (csCredits !== null && csCredits !== undefined) {
                        csText += ' [' + csCredits + ' credits left]';
                    }
                    summaryParts.push(csText);
                } else if (includeCopyscape) {
                    summaryParts.push('Copyscape: not run (check credentials)');
                }
                
                summaryEl.innerHTML = summaryParts.join(' | ');

                // Show matches if any
                matchesEl.innerHTML = '';
                var allMatches = [];

                // Internal matches
                if (data.internal && data.internal.matches && data.internal.matches.length > 0) {
                    allMatches = allMatches.concat(data.internal.matches.map(function(m) {
                        return { source: 'Internal', title: m.title || 'Page #' + m.id, url: '', percent: m.repeated_new_text_percent || 0 };
                    }));
                }

                // Google matches
                if (data.google && data.google.results && data.google.results.length > 0) {
                    data.google.results.forEach(function(phraseResult) {
                        if (phraseResult.google_results && phraseResult.google_results.length > 0) {
                            phraseResult.google_results.forEach(function(gr) {
                                allMatches.push({
                                    source: 'Google',
                                    title: gr.title || gr.url,
                                    url: gr.url,
                                    percent: gr.similarity_percent || 0
                                });
                            });
                        }
                    });
                }

                // Copyscape matches
                if (data.copyscape && data.copyscape.matches && data.copyscape.matches.length > 0) {
                    allMatches = allMatches.concat(data.copyscape.matches.map(function(m) {
                        return { source: 'Copyscape', title: m.title || m.url, url: m.url, percent: m.percent_matched || 0 };
                    }));
                }

                if (allMatches.length > 0) {
                    var table = '<table class="table table-sm table-bordered"><thead><tr><th>Source</th><th>Found On</th><th>Similarity</th></tr></thead><tbody>';
                    allMatches.forEach(function(m) {
                        var badgeClass = m.source === 'Internal' ? 'bg-dark' : (m.source === 'Google' ? 'bg-warning text-dark' : 'bg-info');
                        var titleHtml = m.url ? '<a href="' + m.url + '" target="_blank" rel="noopener">' + m.title + '</a>' : m.title;
                        table += '<tr><td><span class="badge ' + badgeClass + '">' + m.source + '</span></td>';
                        table += '<td class="bg-dark">' + titleHtml + '</td>';
                        table += '<td>' + m.percent + '%</td></tr>';
                    });
                    table += '</tbody></table>';
                    matchesEl.innerHTML = table;
                } else if (data.overall_verdict === 'passed') {
                    matchesEl.innerHTML = '<p class="text-success mb-0"><i class="fa fa-check me-1"></i> No similar content found. Your text appears to be unique!</p>';
                }

                // Show rewritten text if available
                if (data.rewrite && data.rewrite.success && data.rewrite.rewritten_text) {
                    var rewriteHtml = '<div class="card mt-3 border-success">';
                    rewriteHtml += '<div class="card-header bg-success text-white"><i class="fa fa-magic me-2"></i><strong>✓ Auto-Rewritten Unique Version</strong></div>';
                    rewriteHtml += '<div class="card-body">';
                    rewriteHtml += '<p class="text-muted small mb-2">' + (data.rewrite.message || 'Text has been rewritten to be unique.') + '</p>';
                    rewriteHtml += '<textarea class="form-control" rows="8" id="rewrittenText" readonly>' + data.rewrite.rewritten_text.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</textarea>';
                    rewriteHtml += '<div class="mt-2">';
                    rewriteHtml += '<button type="button" class="btn btn-success btn-sm me-2" onclick="copyRewrittenText()"><i class="fa fa-copy me-1"></i> Copy to Clipboard</button>';
                    rewriteHtml += '<button type="button" class="btn btn-outline-dark btn-sm" onclick="useRewrittenText()"><i class="fa fa-arrow-up me-1"></i> Use This Text</button>';
                    rewriteHtml += '</div></div></div>';
                    matchesEl.innerHTML += rewriteHtml;
                }
            })
            .catch(function(err) {
                resultDiv.style.display = 'block';
                alertDiv.className = 'alert alert-danger';
                verdictEl.innerHTML = '<i class="fa fa-times-circle me-1"></i> Error';
                summaryEl.textContent = 'Failed to run uniqueness check. Please try again.';
                matchesEl.innerHTML = '';
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-search me-1"></i> Run Uniqueness Check';
            });
        });
    })();

    // Helper functions for rewritten text
    function copyRewrittenText() {
        var textarea = document.getElementById('rewrittenText');
        if (textarea) {
            textarea.select();
            document.execCommand('copy');
            // Show feedback
            var btn = event.target.closest('button');
            var originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-check me-1"></i> Copied!';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
            setTimeout(function() {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-success');
            }, 2000);
        }
    }

    function useRewrittenText() {
        var rewrittenTextarea = document.getElementById('rewrittenText');
        var originalTextarea = document.getElementById('uniquenessText');
        if (rewrittenTextarea && originalTextarea) {
            originalTextarea.value = rewrittenTextarea.value;
            // Scroll to top and show feedback
            originalTextarea.scrollIntoView({ behavior: 'smooth' });
            originalTextarea.focus();
            // Flash effect
            originalTextarea.style.backgroundColor = '#d4edda';
            setTimeout(function() {
                originalTextarea.style.backgroundColor = '';
            }, 1000);
        }
    }

    // Generate AI Content button
    (function() {
        var btn = document.getElementById('generateAiContent');
        if (!btn) return;

        btn.addEventListener('click', function() {
            var campaignId = btn.getAttribute('data-campaign-id');
            var resultDiv = document.getElementById('aiGenerationResult');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Generating...';
            resultDiv.style.display = 'none';

            fetch('/agency/local-seo-presence-boost/campaigns/' + campaignId + '/generate-content', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                resultDiv.style.display = 'block';
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success"><i class="fa fa-check-circle me-1"></i> <strong>Content Generated!</strong> ' + data.word_count + ' words created. <a href="javascript:location.reload()">Refresh</a> to see updated status, or <a href="' + btn.closest('.card').querySelector('a[target="_blank"]').href + '" target="_blank">Preview Page</a>.</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle me-1"></i> ' + (data.message || 'Generation failed.') + '</div>';
                }
            })
            .catch(function(err) {
                resultDiv.style.display = 'block';
                resultDiv.innerHTML = '<div class="alert alert-danger"><i class="fa fa-times-circle me-1"></i> Error generating content. Please try again.</div>';
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-magic me-1"></i> Generate AI Content';
            });
        });
    })();

    // Check Uniqueness before publishing
    (function() {
        var btn = document.getElementById('checkAndPublish');
        if (!btn) return;

        btn.addEventListener('click', function() {
            var campaignId = btn.getAttribute('data-campaign-id');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Checking...';

            fetch('/agency/local-seo-presence-boost/campaigns/' + campaignId + '/check-and-publish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.can_publish) {
                    alert('✓ ' + data.message + '\n\nYou can now publish safely.');
                } else {
                    alert('⚠ ' + data.message + '\n\nConsider regenerating content or reviewing matches.');
                }
                location.reload();
            })
            .catch(function(err) {
                alert('Error checking uniqueness. Please try again.');
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-shield me-1"></i> Check Uniqueness First';
            });
        });
    })();

    // Regenerate AI Content buttons (in campaign table)
    document.querySelectorAll('.regenerate-ai-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var campaignId = btn.getAttribute('data-campaign-id');
            
            if (!confirm('Regenerate AI content for this campaign? This will replace existing content.')) {
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            fetch('/agency/local-seo-presence-boost/campaigns/' + campaignId + '/generate-content', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    alert('✓ AI Content Generated!\n\n' + data.word_count + ' words created.');
                    location.reload();
                } else {
                    alert('⚠ Generation failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(function(err) {
                alert('Error generating content. Please try again.');
            })
            .finally(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-magic"></i>';
            });
        });
    });

    // ---- Listing Location autocomplete (used by add/edit forms) ----
    function initListingLocationAutocomplete(inputId, boxId, locationId, cityId, countryId, latId, lngId) {
        var input = document.getElementById(inputId);
        var box = document.getElementById(boxId);
        if (!input || !box) return;

        var timer = null;
        function hideBox() { box.style.display = 'none'; box.innerHTML = ''; }

        input.addEventListener('input', function () {
            var q = input.value.trim();
            clearTimeout(timer);
            if (q.length < 3) { hideBox(); return; }
            timer = setTimeout(function () {
                fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=8&q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function (r) { return r.json(); })
                .then(function (results) {
                    box.innerHTML = '';
                    if (!results || !results.length) { hideBox(); return; }

                    var validTypes = ['street', 'road', 'pedestrian', 'residential', 'suburb', 'neighbourhood', 'quarter', 'city_district', 'city', 'town', 'village', 'municipality', 'administrative'];
                    var validClasses = ['place', 'boundary', 'highway'];
                    var filtered = results.filter(function(item) {
                        return validTypes.some(function(t) { return item.type === t; }) ||
                               validClasses.some(function(c) { return item.class === c; });
                    });
                    if (!filtered.length) filtered = results;

                    filtered.slice(0, 6).forEach(function (item) {
                        var addr = item.address || {};
                        var country = addr.country || '';
                        var placeName = addr.road || addr.street || addr.pedestrian ||
                                        addr.neighbourhood || addr.suburb || addr.quarter || addr.city_district ||
                                        addr.city || addr.town || addr.village || addr.municipality ||
                                        item.display_name.split(',')[0];
                        var parentCity = addr.city || addr.town || addr.municipality || '';
                        var neighborhood = addr.neighbourhood || addr.suburb || addr.quarter || '';
                        var fullName = placeName;
                        if ((addr.road || addr.street) && neighborhood && neighborhood !== placeName) {
                            fullName = placeName + ', ' + neighborhood;
                        }
                        if (parentCity && parentCity !== placeName && parentCity !== neighborhood) {
                            fullName = fullName + ', ' + parentCity;
                        }

                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = item.display_name;
                        a.addEventListener('click', function () {
                            input.value = fullName + (country ? ', ' + country : '');
                            document.getElementById(locationId).value = fullName;
                            document.getElementById(cityId).value = fullName;
                            document.getElementById(countryId).value = country;
                            document.getElementById(latId).value = item.lat || '';
                            document.getElementById(lngId).value = item.lon || '';
                            hideBox();
                        });
                        box.appendChild(a);
                    });
                    box.style.display = 'block';
                })
                .catch(hideBox);
            }, 350);
        });

        document.addEventListener('click', function (e) {
            if (!box.contains(e.target) && e.target !== input) hideBox();
        });
    }

    initListingLocationAutocomplete(
        'addListingSearch', 'addListingSuggestions',
        'addListingLocation', 'addListingCity', 'addListingCountry', 'addListingLat', 'addListingLng'
    );
    initListingLocationAutocomplete(
        'editListingSearch', 'editListingSuggestions',
        'editListingLocation', 'editListingCity', 'editListingCountry', 'editListingLat', 'editListingLng'
    );

    // ---- Loader overlay for AI/long actions ----
    function showLoader(message) {
        var loader = document.getElementById('vbLoader');
        if (!loader) return;
        var msgEl = loader.querySelector('.message');
        if (message && msgEl) msgEl.innerHTML = message;
        loader.classList.add('active');
    }

    // Campaign create/edit form (triggers AI content generation)
    var campaignForm = document.getElementById('campaignForm');
    if (campaignForm) {
        campaignForm.addEventListener('submit', function (e) {
            showLoader('<strong>Villa Bit AI</strong> is building your campaign…');
        });
    }

    // Add / edit listing forms (save location + optional campaign sync)
    var addListingForm = document.querySelector('form[action="{{ route('agency.local-seo.listings.store') }}"]');
    if (addListingForm) {
        addListingForm.addEventListener('submit', function () { showLoader('Saving listing…'); });
    }
    var editListingForm = document.querySelector('form[action^="{{ url('local-seo-presence-boost/listings') }}"]');
    if (editListingForm && editListingForm.method.toUpperCase() !== 'DELETE') {
        editListingForm.addEventListener('submit', function () { showLoader('Updating listing…'); });
    }

    // Add Image field function
    function addImageField() {
        var container = document.getElementById('imageUploadContainer');
        var row = document.createElement('div');
        row.className = 'image-upload-row mb-2 d-flex gap-2';
        row.innerHTML = '<input type="file" name="images[]" class="form-control" accept="image/*">' +
                        '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">✕</button>';
        container.appendChild(row);
    }
    window.addImageField = addImageField;

</script>
@endsection

@section('scripts')
<script>
    // Initialize Select2 for campaign multi-selects (must be after Select2 is loaded)
    $(document).ready(function() {
        // For new listing form
        if ($('.select2-campaigns').length) {
            $('.select2-campaigns').select2({
                placeholder: 'Select campaigns...',
                allowClear: true,
                width: '100%'
            });
        }

        // For listing table - inline campaign selects
        if ($('.select2-listing-campaigns').length) {
            $('.select2-listing-campaigns').select2({
                placeholder: 'Select campaigns...',
                allowClear: true,
                width: '100%'
            }).on('change', function() {
                // Show save button when selection changes
                $(this).closest('form').find('.save-campaigns-btn').removeClass('d-none');
            });
        }
    });
</script>
@endsection
