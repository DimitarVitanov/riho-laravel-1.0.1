@extends('layouts.simple.master')
@section('title', 'Usage Limits')
@section('breadcrumb-title')<h3>Usage Limits</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Usage Limits</li>
@endsection
@section('content')
<div class="container-fluid">
    {{-- Usage Period Status Block (June 2026 spec) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Usage Period: {{ now()->format('F Y') }}</h5>
                            <p class="mb-0 text-muted">
                                {{ now()->startOfMonth()->format('F j, Y') }} — {{ now()->endOfMonth()->format('F j, Y') }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success fs-6">Active</span>
                            <p class="mb-0 mt-1 text-muted small">Next reset: {{ now()->addMonth()->startOfMonth()->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($currentUsage)
    <div class="row">
        @php
        $limits = [
            ['Local SEO Pages', $currentUsage->local_seo_pages_used, $currentUsage->local_seo_pages_limit],
            ['Competitor Scans', $currentUsage->competitor_scans_used, $currentUsage->competitor_scans_limit],
            ['AI Search Updates', $currentUsage->ai_search_freshness_updates_used, $currentUsage->ai_search_freshness_updates_limit],
            ['Authority Reviews', $currentUsage->authority_review_updates_used, $currentUsage->authority_review_updates_limit],
            ['Small AI Actions', $currentUsage->small_ai_content_actions_used, $currentUsage->small_ai_content_actions_limit],
        ];
        @endphp
        @foreach($limits as [$label, $used, $limit])
        <div class="col-md-4 col-lg-2-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h6>{{ $label }}</h6>
                    <h3 class="{{ $used >= $limit ? 'text-danger' : 'text-success' }}">{{ $used }} / {{ $limit }}</h3>
                    <div class="progress" style="height: 8px;">
                        @php $pct = $limit > 0 ? min(100, ($used / $limit) * 100) : 0; @endphp
                        <div class="progress-bar bg-{{ $pct >= 100 ? 'danger' : ($pct >= 75 ? 'warning' : 'success') }}" style="width: {{ $pct }}%"></div>
                    </div>
                    <small class="text-muted">{{ $limit - $used }} remaining</small>
                    <br><small class="text-muted">Usage month: {{ now()->format('F Y') }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="alert alert-info">No usage limits configured for the current period.</div>
    @endif
</div>
@endsection
