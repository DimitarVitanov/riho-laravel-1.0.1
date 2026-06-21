@extends('layouts.simple.master')
@section('title', __('messages.competitor_scan'))
@section('breadcrumb-title')
    <h3>{{ __('messages.competitor_scan') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.competitor_scan') }}</li>
@endsection

@section('content')
<div class="container-fluid daily-competitor-scan-feature">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Feature Header & Status --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.competitor_scan') }}</h5>
                        <small class="text-muted">{{ __('messages.competitor_scan_subtitle') }}</small>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <form action="{{ route('agency.features.show', 'daily_competitor_scan') }}" method="POST" id="settingsForm" class="mb-0">
                            @csrf
                            <input type="hidden" name="feature" value="daily_competitor_scan">
                            <input type="hidden" name="is_enabled" id="isEnabledInput" value="{{ $featureSetting && $featureSetting->is_enabled ? '1' : '0' }}">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="featureToggle" role="switch"
                                    {{ $featureSetting && $featureSetting->is_enabled ? 'checked' : '' }}
                                    onchange="toggleCompetitorFeature(this)">
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
                    <p class="mb-0">{{ __('messages.competitor_scan_summary') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-dark">{{ $competitors->count() }}</div>
                    <div class="text-muted small">{{ __('messages.competitors_tracked') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-warning">{{ $newResults->count() }}</div>
                    <div class="text-muted small">{{ __('messages.new_findings') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-primary">
                        {{ $usageLimit ? $usageLimit->competitor_scans_used . ' / ' . $usageLimit->competitor_scans_limit : '— / —' }}
                    </div>
                    <div class="text-muted small">{{ __('messages.competitor_scans') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-success">
                        {{ $scanResults->total() }}
                    </div>
                    <div class="text-muted small">{{ __('messages.total_findings') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- LEFT COLUMN: Competitors + Run Scan --}}
        <div class="col-lg-4 mb-4">

            {{-- Add Competitor --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-plus me-2"></i>{{ __('messages.add_competitor') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.competitor.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.competitor_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Miami Realty Pro" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.competitor_website_url') }}</label>
                            <input type="url" name="url" class="form-control" placeholder="https://www.competitorsite.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.google_business_url') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                            <input type="url" name="google_business_url" class="form-control" placeholder="https://g.page/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.notes') }} <span class="text-muted">({{ __('messages.optional') }})</span></label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="{{ __('messages.competitor_notes_placeholder') }}"></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm w-100">{{ __('messages.add_competitor') }}</button>
                    </form>
                </div>
            </div>

            {{-- Run Scan --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-search me-2"></i>{{ __('messages.run_competitor_scan') }}</h6>
                </div>
                <div class="card-body">
                    @if($competitors->isEmpty())
                    <div class="alert alert-info small mb-0">
                        {{ __('messages.no_competitors_yet') }}
                    </div>
                    @else
                    <form action="{{ route('agency.competitor.run-scan') }}" method="POST">
                        @csrf
                        <p class="small text-muted mb-3">{{ __('messages.run_scan_help') }}</p>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.scan_types') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach([
                                    'new_properties' => __('messages.scan_new_properties'),
                                    'seo_pages' => __('messages.scan_seo_pages'),
                                    'blog' => __('messages.scan_blog'),
                                    'price_movement' => __('messages.scan_price_movement'),
                                    'gbp_reviews' => __('messages.scan_gbp_reviews'),
                                    'weakness_detection' => __('messages.scan_weakness_detection'),
                                ] as $type => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="scan_types[]" value="{{ $type }}" id="scan_{{ $type }}" checked>
                                    <label class="form-check-label small" for="scan_{{ $type }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm w-100">
                            <i class="fa fa-play me-1"></i>{{ __('messages.run_scan_now') }}
                        </button>
                        @if($usageLimit)
                        <div class="mt-2 text-center">
                            <small class="text-muted">{{ __('messages.scans_remaining') }}: {{ $usageLimit->remaining('competitor_scans') }} / {{ $usageLimit->competitor_scans_limit }}</small>
                        </div>
                        @endif
                    </form>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Competitors list + Results --}}
        <div class="col-lg-8">

            {{-- Competitors List --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-building me-2"></i>{{ __('messages.tracked_competitors') }}</h6>
                </div>
                <div class="card-body p-0">
                    @if($competitors->isEmpty())
                    <div class="p-4 text-muted text-center small">{{ __('messages.no_competitors_yet') }}</div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small">{{ __('messages.name') }}</th>
                                    <th class="small">{{ __('messages.website') }}</th>
                                    <th class="small">{{ __('messages.last_scanned') }}</th>
                                    <th class="small">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($competitors as $competitor)
                                <tr>
                                    <td class="fw-bold small">{{ $competitor->name }}</td>
                                    <td>
                                        <a href="{{ $competitor->url }}" target="_blank" class="text-decoration-none small text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ $competitor->url }}
                                        </a>
                                    </td>
                                    <td class="small text-muted">{{ $competitor->last_scanned_at ? $competitor->last_scanned_at->diffForHumans() : __('messages.never') }}</td>
                                    <td>
                                        <form action="{{ route('agency.competitor.destroy', $competitor) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- New Findings --}}
            @if($newResults->isNotEmpty())
            <div class="card mb-4 border-warning">
                <div class="card-header bg-warning bg-opacity-10 border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-warning"><i class="fa fa-exclamation-circle me-2"></i>{{ __('messages.new_findings') }} ({{ $newResults->count() }})</h6>
                    <small class="text-muted">{{ __('messages.new_findings_help') }}</small>
                </div>
                <div class="card-body p-0">
                    @foreach($newResults as $result)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-{{ $result->scanTypeBadgeColor() }} me-2">{{ $result->scanTypeLabel() }}</span>
                                @if($result->competitorWebsite)
                                <small class="text-muted">{{ $result->competitorWebsite->name }}</small>
                                @endif
                            </div>
                            <small class="text-muted">{{ $result->scanned_at->diffForHumans() }}</small>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $result->title }}</h6>
                        <p class="small text-muted mb-2">{{ $result->summary }}</p>
                        @if($result->recommended_action)
                        <div class="alert alert-light border-start border-primary border-3 mb-2 py-2 px-3">
                            <small class="fw-bold text-primary">{{ __('messages.recommended_action') }}:</small>
                            <small class="d-block text-dark mt-1">{{ $result->recommended_action }}</small>
                        </div>
                        @endif
                        @if($result->recommended_content)
                        <div class="bg-light rounded p-2 mb-2">
                            <small class="fw-bold text-muted">{{ __('messages.suggested_content') }}:</small>
                            <small class="d-block text-dark mt-1 text-pre-wrap">{{ $result->recommended_content }}</small>
                        </div>
                        @endif
                        <div class="d-flex gap-2 mt-2">
                            <form action="{{ route('agency.competitor.results.acted', $result) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">{{ __('messages.mark_acted') }}</button>
                            </form>
                            <form action="{{ route('agency.competitor.results.dismissed', $result) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary btn-sm">{{ __('messages.dismiss') }}</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    
                    {{-- Pagination for new findings --}}
                    @if($newResults->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                            <small class="text-muted">
                                Showing {{ $newResults->firstItem() }} to {{ $newResults->lastItem() }} of {{ $newResults->total() }} new findings
                            </small>
                            @include('partials.pagination', ['paginator' => $newResults])
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- All Scan Results --}}
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-list me-2"></i>{{ __('messages.all_scan_results') }}</h6>
                </div>
                <div class="card-body p-0">
                    @if($scanResults->isEmpty())
                    <div class="p-4 text-muted text-center small">{{ __('messages.no_scan_results_yet') }}</div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.type') }}</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.competitor_label') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scanResults as $result)
                                <tr>
                                    <td><span class="badge bg-{{ $result->scanTypeBadgeColor() }}">{{ $result->scanTypeLabel() }}</span></td>
                                    <td class="fw-bold">{{ Str::limit($result->title, 50) }}</td>
                                    <td class="text-muted">{{ $result->competitorWebsite->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $result->status === 'new' ? 'warning' : ($result->status === 'acted' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($result->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $result->scanned_at->format('d M Y') }}</td>
                                    <td>
                                        <form action="{{ route('agency.competitor.results.destroy', $result) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        @include('partials.pagination', ['paginator' => $scanResults])
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Latest AI Report --}}
    @if($latestReport)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-file-text me-2"></i>{{ __('messages.daily_ai_report') }}</h6>
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
    function toggleCompetitorFeature(toggle) {
        document.getElementById('isEnabledInput').value = toggle.checked ? '1' : '0';
        document.getElementById('settingsForm').submit();
    }
</script>
@endpush
@endsection
