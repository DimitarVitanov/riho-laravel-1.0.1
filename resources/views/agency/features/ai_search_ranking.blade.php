@extends('layouts.simple.master')

@section('title', __('messages.ai_search_ranking'))

@section('breadcrumb-title')
    <h3>{{ __('messages.ai_search_ranking') }}</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.ai_search_ranking') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    {{-- Feature Header & Status --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.ai_search_ranking') }}</h5>
                        <small class="text-muted">{{ __('messages.ai_search_ranking_subtitle') }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <form action="{{ route('agency.ai-search.save-settings') }}" method="POST" id="settingsForm" class="mb-0">
                            @csrf
                            <input type="hidden" name="feature" value="ai_search_ranking">
                            <input type="hidden" name="is_enabled" id="isEnabledInput" value="{{ $featureSetting && $featureSetting->is_enabled ? '1' : '0' }}">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="featureToggle" role="switch"
                                    {{ $featureSetting && $featureSetting->is_enabled ? 'checked' : '' }}
                                    onchange="toggleAiSearchFeature(this)">
                                <label class="form-check-label" for="featureToggle">
                                    {{ $featureSetting && $featureSetting->is_enabled ? __('messages.enabled') : __('messages.disabled') }}
                                </label>
                            </div>
                        </form>
                        <span class="badge {{ $featureSetting && $featureSetting->is_enabled ? 'bg-success' : 'bg-secondary' }} fs-6">
                            {{ $featureSetting && $featureSetting->is_enabled ? __('messages.active') : __('messages.inactive') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ __('messages.ai_search_ranking_summary') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- AI Search Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">{{ __('messages.authority_pages') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $pages->total() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">{{ __('messages.ai_search_freshness_updates') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $usageLimit->ai_search_freshness_updates_used ?? 0 }} / {{ $usageLimit->ai_search_freshness_updates_limit ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">{{ __('messages.data_blocks') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $dataBlocks->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">{{ __('messages.pending_notifications') }}</h6>
                    <h2 class="fw-bold mb-0">{{ $notifications->count() ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Authority Pages Generator --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.generate_authority_pages') }}</h5>
                    <small class="text-muted">{{ __('messages.authority_pages_help') }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.ai-search.generate-authority-pages') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.target_city') }}</label>
                                <input type="text" name="target_city" class="form-control" value="{{ $profile->target_city ?? '' }}" placeholder="{{ __('messages.target_city_placeholder') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.agency_name') }}</label>
                                <input type="text" name="agency_name" class="form-control" value="{{ $profile->agency_name ?? '' }}" placeholder="{{ __('messages.agency_name_placeholder') }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-dark">
                                <i class="fa fa-magic me-1"></i>{{ __('messages.generate_authority_pages') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Generate Data Blocks --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.generate_data_blocks') }}</h5>
                    <small class="text-muted">{{ __('messages.data_blocks_help') }}</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.ai-search.generate-data-blocks') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.target_city') }}</label>
                                <input type="text" name="target_city" class="form-control" value="{{ $profile->target_city ?? '' }}" placeholder="{{ __('messages.target_city_placeholder') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.data_block_types') }}</label>
                                <select name="block_types[]" class="form-select" multiple size="4">
                                    <option value="recent_properties" selected>{{ __('messages.recent_properties') }}</option>
                                    <option value="buyer_questions" selected>{{ __('messages.buyer_questions') }}</option>
                                    <option value="market_notes" selected>{{ __('messages.market_notes') }}</option>
                                    <option value="price_ranges" selected>{{ __('messages.price_ranges') }}</option>
                                    <option value="rental_yield" selected>{{ __('messages.rental_yield') }}</option>
                                    <option value="buyer_locations" selected>{{ __('messages.buyer_locations') }}</option>
                                    <option value="foreign_buyer_mistakes" selected>{{ __('messages.foreign_buyer_mistakes') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-dark">
                                <i class="fa fa-cube me-1"></i>{{ __('messages.generate_data_blocks') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Generated Authority Pages --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.authority_pages') }}</h5>
                        <small class="text-muted">{{ __('messages.authority_pages_for_review') }}</small>
                    </div>
                    <span class="badge bg-dark text-white fs-6">{{ $pages->total() ?? 0 }} {{ __('messages.total_pages') }}</span>
                </div>
                <div class="card-body p-0">
                    @if($pages->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.title') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $page)
                                    <tr>
                                        <td><strong>{{ $page->title }}</strong></td>
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
                                                <a href="{{ route('agency.ai-search.pages.preview', $page) }}" class="btn btn-outline-dark">{{ __('messages.preview') }}</a>
                                                <a href="{{ route('agency.ai-search.pages.edit', $page) }}" class="btn btn-outline-secondary">{{ __('messages.edit') }}</a>
                                                @if($page->status !== 'published')
                                                <form action="{{ route('agency.ai-search.pages.publish', $page) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark">{{ __('messages.publish') }}</button>
                                                </form>
                                                @endif
                                                <form action="{{ route('agency.ai-search.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete_page') }}')">
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
                            {{ $pages->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">{{ __('messages.no_authority_pages_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Advice Notifications --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.ai_search_advice') }}</h5>
                    <small class="text-muted">{{ __('messages.ai_search_advice_help') }}</small>
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <p class="mb-0 text-muted small">{{ $notification->description }}</p>
                                </div>
                                @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" target="_blank" class="btn btn-sm btn-outline-dark">{{ __('messages.open') }}</a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted mb-0">{{ __('messages.no_notifications') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Daily AI Report --}}
    @if($latestReport)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.latest_ai_report') }}</h5>
                    <small class="text-muted">{{ $latestReport->report_date->format('M d, Y') }}</small>
                </div>
                <div class="card-body">
                    <p>{{ $latestReport->summary }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleAiSearchFeature(toggle) {
        document.getElementById('isEnabledInput').value = toggle.checked ? '1' : '0';
        document.getElementById('settingsForm').submit();
    }
</script>
@endpush
@endsection
