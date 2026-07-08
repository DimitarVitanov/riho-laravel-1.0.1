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
/* Local SEO page: +1px font sizes */
.local-seo-feature { font-size: 15px; }
.local-seo-feature .form-label { font-size: 13px; }
.local-seo-feature .form-control, .local-seo-feature .form-select { font-size: 15px; }
.local-seo-feature .btn { font-size: 15px; }
.local-seo-feature h5 { font-size: 19px; }
.local-seo-feature small, .local-seo-feature .small { font-size: 13px; }
.local-seo-feature .text-muted { font-size: 13px; }
.local-seo-feature .card-header h5 { font-size: 18px; }
.local-seo-feature table { font-size: 14px; }

/* Card styling matching mockup - subtle shadow */
.local-seo-feature .card {
    border: 1px solid #dde1e5;
    border-radius: 16px;
    box-shadow: 0 7px 18px rgba(22,28,35,.04);
}
.local-seo-feature .card-header {
    border-radius: 16px 16px 0 0;
}
.local-seo-feature .card-body {
    border-radius: 0 0 16px 16px;
}

/* Perfect circle for step numbers - black background */
.local-seo-feature .step-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border-radius: 50%;
    background: #0a0b0c;
    color: #fff;
    font-size: 14px;
    font-weight: 800;
    flex-shrink: 0;
}
</style>
@endsection

@section('content')
<div class="container-fluid local-seo-feature">

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
  {{-- Main Settings Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.local_seo') }}</h5>
                        <small class="text-muted">{{ __('messages.feature_status') }}</small>
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

                        {{-- Settings Row --}}
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.feature_status') }}</label>
                                <div class="form-control bg-light" id="statusDisplay">
                                    <span class="fw-bold {{ $featureSetting && $featureSetting->is_enabled ? 'text-dark' : 'text-muted' }}">
                                        <i class="fa {{ $featureSetting && $featureSetting->is_enabled ? 'fa-check-circle' : 'fa-circle-o' }} me-2"></i>
                                        {{ $featureSetting && $featureSetting->is_enabled ? __('messages.on_collecting_leads') : __('messages.off_not_active') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.ai_posting_language') }}</label>
                                <div class="form-control bg-light">
                                    <span class="fw-bold text-dark">{{ $profile->ai_content_language ?? 'English' }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.uniqueness_status') }}</label>
                                <div class="form-control bg-light">
                                    <span class="fw-bold text-dark">{{ \App\Http\Controllers\Agency\AgencySettingsController::uniquenessCheckMethods()[$profile->uniqueness_check_method ?? 'villabit_ai'] ?? __('messages.passed_before_publish') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden per request: only Feature Status / AI Posting Language / Uniqueness Status are shown --}}
                        @if(false)
                        {{-- Location Targeting Row --}}
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa fa-map-marker me-2"></i>{{ __('messages.location_targeting') }}</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold">{{ __('messages.target_city') }}</label>
                                    <input type="text" name="target_city" class="form-control" value="{{ $profile->target_city ?? '' }}" placeholder="{{ __('messages.target_city_placeholder') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small fw-bold">{{ __('messages.target_radius_km') }}</label>
                                    <div class="input-group">
                                        <input type="number" name="target_radius_km" class="form-control" value="{{ $profile->target_radius_km ?? 30 }}" min="5" max="200">
                                        <span class="input-group-text">km</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-dark" role="progressbar" style="width: {{ $featureSetting && $featureSetting->is_enabled ? '100%' : '0%' }}"></div>
                        </div>

                        {{-- Daily AI Report Section --}}
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold mb-2">{{ __('messages.daily_ai_report') }}</h6>
                            <p class="text-muted mb-0">
                                {{ $latestReport->ai_actions_summary ?? __('messages.local_seo_ai_summary') }}
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row g-2">
                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn btn-dark w-100">{{ __('messages.save') }}</button>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="{{ route('agency.local-seo.logs') }}" class="btn btn-outline-secondary w-100">{{ __('messages.view_logs') }}</a>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="{{ route('agency.local-seo.prompt') }}" class="btn btn-outline-secondary w-100">{{ __('messages.open_prompt') }}</a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- ============ CAMPAIGNS TABLE ============ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">Your Campaigns</h5>
                    <small class="text-muted">Activate, edit or remove your Local SEO campaigns.</small>
                </div>
                <div class="card-body p-0">
                    @if($campaigns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>USE</th>
                                        <th>CAMPAIGN</th>
                                        <th>MARKET</th>
                                        <th>COVERAGE</th>
                                        <th>LISTINGS</th>
                                        <th>STATUS</th>
                                        <th class="text-end">ACTION</th>
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
                                        <td><span class="badge bg-secondary">{{ $campaign->listings_count }}</span></td>
                                        <td>
                                            @if($campaign->status === 'published')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($campaign->status === 'unpublished')
                                                <span class="badge bg-warning text-dark">Unpublished</span>
                                            @else
                                                <span class="badge bg-light text-dark border">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('agency.local-seo.campaigns.toggle', $campaign) }}" method="POST" class="d-inline">
                                                @csrf
                                                @if($campaign->status === 'published')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Unpublish this campaign? It will be removed from the server.')">Unpublish</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-success">Publish</button>
                                                @endif
                                            </form>
                                            <a href="{{ route('agency.local-seo.campaigns.preview', $campaign) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">Preview</a>
                                            <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}?edit_campaign_id={{ $campaign->id }}" class="btn btn-sm btn-outline-dark">Edit</a>
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
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">No campaigns yet. Define your first campaign above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ============ SECTION 1: Define Campaign ============ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold"><span class="step-circle me-2">1</span>Define Your Local SEO Campaign</h5>
                    <small class="text-muted">These rules tell AI where the agency works, its coverage area, and how it positions itself.</small>
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
                                <label class="form-label text-muted small fw-bold">Primary Market / Main City *</label>
                                <input type="text" id="citySearch" class="form-control" autocomplete="off"
                                       value="{{ $editCampaign ? trim(($editCampaign->primary_city ?? '') . ($editCampaign->country ? ', ' . $editCampaign->country : '')) : '' }}"
                                       placeholder="Start typing a city…">
                                <div id="citySuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; display:none; max-height: 240px; overflow-y:auto;"></div>
                                <small class="text-muted">Location autocomplete. Saves city + country + coordinates.</small>
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

                        <div class="d-flex justify-content-end mt-4">
                            @if($editCampaign)
                                <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" class="btn btn-outline-secondary me-2">Cancel edit</a>
                            @endif
                            <button type="submit" class="btn btn-dark">Save Draft</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- ============ UNIQUENESS CHECKER ============ --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="fa fa-shield-alt me-2"></i>Content Uniqueness Checker</h5>
                        <small class="text-muted">Check AI-generated content for duplicates before publishing.</small>
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
        </div>
    </div>

    {{-- ============ SECTION 3: Publishing (only when editing a campaign) ============ --}}
    @if($editCampaign)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold"><span class="step-circle me-2">2</span>Publish</h5>
                    <small class="text-muted">Publish "{{ $editCampaign->name }}" to your connected domain.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.campaigns.publish', $editCampaign) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-dark" style="color:black">Publishing Domain</label>
                                <input type="text" class="form-control bg-light text-dark" value="{{ $profile->custom_domain ?? 'Not connected yet' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Page URL Slug</label>
                                <input type="text" name="page_slug" class="form-control"
                                       value="{{ $editCampaign->page_slug ?? ('/' . \Illuminate\Support\Str::slug('real-estate-' . ($editCampaign->primary_city ?: $editCampaign->name)) . '/') }}">
                                <small class="text-muted">Suggested automatically — you can change it.</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark">Save & Publish</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

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
    @endif

    {{-- ============ VILLA BIT AI OFFICE - Page Settings ============ --}}
    @if($editCampaign)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold"><i class="fa fa-building me-2"></i>Villa Bit AI Office</h5>
                    <small class="text-muted">Configure how your published Local SEO page will look.</small>
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

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-dark">
                                <i class="fa fa-save me-1"></i> Save Page Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Agency Listings Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold"><span class="step-circle me-2">3</span>{{ __('messages.agency_listings') }}</h5>
                    <small class="text-muted">{{ __('messages.add_real_estate_listings') }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.listings.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Campaigns</label>
                                <select name="campaign_ids[]" class="form-select select2-campaigns" multiple required>
                                    @foreach($campaigns as $campaignOption)
                                        <option value="{{ $campaignOption->id }}"
                                            {{ (string)($editCampaign->id ?? '') === (string)$campaignOption->id ? 'selected' : '' }}>
                                            {{ $campaignOption->name }}{{ $campaignOption->primary_city ? ' — ' . $campaignOption->primary_city : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select one or more campaigns. The listing will appear on all selected campaign pages.</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.listing_title') }}</label>
                                <input type="text" name="title" class="form-control" placeholder="{{ __('messages.listing_title_placeholder') }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.property_type') }}</label>
                                <input type="text" name="property_type" class="form-control" placeholder="{{ __('messages.property_type_placeholder') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.location') }}</label>
                                <input type="text" name="location" class="form-control" placeholder="{{ __('messages.location_placeholder') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.price') }}</label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control" placeholder="0" min="0">
                                    <select name="currency" class="form-select" style="max-width: 80px;">
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                        <option value="GBP">GBP</option>
                                        <option value="HRK">HRK</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.description') }}</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="{{ __('messages.listing_description_placeholder') }}"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.images') }}</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                <small class="text-muted">{{ __('messages.images_help') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-dark">
                                    <i class="fa fa-plus me-1"></i>{{ __('messages.add_listing') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($listings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.title') }}</th>
                                        <th>Campaign</th>
                                        <th>{{ __('messages.location') }}</th>
                                        <th>{{ __('messages.price') }}</th>
                                        <th>{{ __('messages.images') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($listings as $listing)
                                    <tr>
                                        <td><strong>{{ $listing->title }}</strong></td>
                                        <td>
                                            <form action="{{ route('agency.local-seo.listings.assign-campaigns', $listing) }}" method="POST" class="listing-campaigns-form">
                                                @csrf
                                                <select name="campaign_ids[]" class="form-select form-select-sm select2-listing-campaigns" style="min-width: 200px;" multiple data-listing-id="{{ $listing->id }}">
                                                    @foreach($campaigns as $campaignOption)
                                                        <option value="{{ $campaignOption->id }}"
                                                            {{ $listing->campaigns->contains($campaignOption->id) ? 'selected' : '' }}>
                                                            {{ $campaignOption->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-outline-dark mt-1 d-none save-campaigns-btn">Save</button>
                                            </form>
                                        </td>
                                        <td>{{ $listing->location ?? '—' }}</td>
                                        <td>{{ $listing->formatted_price ?? '—' }}</td>
                                        <td>
                                            @if(count($listing->images) > 0)
                                                <span class="badge bg-dark">{{ count($listing->images) }} {{ __('messages.images') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ ucfirst($listing->status) }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('agency.local-seo.listings.destroy', $listing) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete_listing') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">{{ __('messages.no_listings_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                fetch('https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=6&featuretype=city&q=' + encodeURIComponent(q), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function (r) { return r.json(); })
                .then(function (results) {
                    box.innerHTML = '';
                    if (!results || !results.length) { hideBox(); return; }
                    results.forEach(function (item) {
                        var addr = item.address || {};
                        var city = addr.city || addr.town || addr.village || addr.municipality || addr.county || item.display_name.split(',')[0];
                        var country = addr.country || '';
                        var a = document.createElement('button');
                        a.type = 'button';
                        a.className = 'list-group-item list-group-item-action';
                        a.textContent = item.display_name;
                        a.addEventListener('click', function () {
                            input.value = city + (country ? ', ' + country : '');
                            document.getElementById('primaryCity').value = city;
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
        // Load Copyscape status on page load
        fetch('{{ route('agency.local-seo.copyscape-status') }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var el = document.getElementById('copyscapeStatus');
            if (data.configured) {
                var balanceText = data.balance !== null ? ' (' + data.balance + ' credits)' : '';
                el.innerHTML = '<i class="fa fa-check-circle text-success"></i> Copyscape configured' + balanceText;
                document.getElementById('includeCopyscape').disabled = false;
            } else {
                el.innerHTML = '<i class="fa fa-exclamation-circle text-warning"></i> Copyscape not configured';
                document.getElementById('includeCopyscape').disabled = true;
            }
        })
        .catch(function() {
            document.getElementById('copyscapeStatus').innerHTML = '<i class="fa fa-times-circle text-danger"></i> Status check failed';
        });

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
        document.getElementById('runUniquenessCheck').addEventListener('click', function() {
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
