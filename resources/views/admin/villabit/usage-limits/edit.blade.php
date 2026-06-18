@extends('layouts.simple.master')
@section('title', 'Edit Usage Limits')
@section('breadcrumb-title')<h3>Edit Usage Limits</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.usage-limits.index') }}">Usage Limits</a></li>
    <li class="breadcrumb-item active">{{ $usageLimit->agencyProfile->agency_name ?? 'Agency' }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Usage Limits for {{ $usageLimit->agencyProfile->agency_name ?? 'Agency' }}</h5>
                    <p class="text-muted mb-0">Period: {{ $usageLimit->period_start->format('M j, Y') }} — {{ $usageLimit->period_end->format('M j, Y') }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.villabit.usage-limits.update', $usageLimit) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Local SEO Pages Limit</label>
                                <input type="number" name="local_seo_pages_limit" class="form-control" value="{{ $usageLimit->local_seo_pages_limit }}" min="0">
                                <small class="text-muted">Current usage: {{ $usageLimit->local_seo_pages_used }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Competitor Scans Limit</label>
                                <input type="number" name="competitor_scans_limit" class="form-control" value="{{ $usageLimit->competitor_scans_limit }}" min="0">
                                <small class="text-muted">Current usage: {{ $usageLimit->competitor_scans_used }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">AI Search Freshness Updates Limit</label>
                                <input type="number" name="ai_search_freshness_updates_limit" class="form-control" value="{{ $usageLimit->ai_search_freshness_updates_limit }}" min="0">
                                <small class="text-muted">Current usage: {{ $usageLimit->ai_search_freshness_updates_used }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Authority Review Updates Limit</label>
                                <input type="number" name="authority_review_updates_limit" class="form-control" value="{{ $usageLimit->authority_review_updates_limit }}" min="0">
                                <small class="text-muted">Current usage: {{ $usageLimit->authority_review_updates_used }}</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Small AI Content Actions Limit</label>
                                <input type="number" name="small_ai_content_actions_limit" class="form-control" value="{{ $usageLimit->small_ai_content_actions_limit }}" min="0">
                                <small class="text-muted">Current usage: {{ $usageLimit->small_ai_content_actions_used }}</small>
                            </div>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">Update Limits</button>
                        <a href="{{ route('admin.villabit.usage-limits.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0"><h5>Default Limits</h5></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Local SEO Pages:</strong> 10</p>
                    <p class="mb-1"><strong>Competitor Scans:</strong> 30</p>
                    <p class="mb-1"><strong>AI Freshness Updates:</strong> 4</p>
                    <p class="mb-1"><strong>Authority Updates:</strong> 1</p>
                    <p class="mb-1"><strong>Small AI Actions:</strong> 30</p>
                    <hr>
                    <small class="text-muted">These are the default limits applied when a new agency is created.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
