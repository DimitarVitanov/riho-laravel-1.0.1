@extends('layouts.simple.master')
@section('title', __('messages.local_seo'))
@section('breadcrumb-title')
    <h3>{{ __('messages.local_seo') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.local_seo') }}</li>
@endsection

@section('content')
<div class="container-fluid local-seo-feature">

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
                                <select name="ai_language" class="form-select">
                                    @php
                                        $languages = \App\Http\Controllers\Agency\AgencySettingsController::supportedAiContentLanguages();
                                    @endphp
                                    @foreach($languages as $lang)
                                        <option value="{{ $lang }}" {{ ($profile->ai_content_language ?? 'English') === $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.uniqueness_status') }}</label>
                                <div class="form-control bg-light">
                                    <span class="fw-bold">{{ __('messages.passed_before_publish') }}</span>
                                </div>
                            </div>
                        </div>

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
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    {{-- Agency Listings Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.agency_listings') }}</h5>
                    <small class="text-muted">{{ __('messages.add_real_estate_listings') }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.listings.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="row g-3">
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
                            <div class="col-md-6 d-flex align-items-end">
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
    }
</script>
@endsection
