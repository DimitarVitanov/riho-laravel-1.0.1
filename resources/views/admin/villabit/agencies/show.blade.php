@extends('layouts.simple.master')
@section('title', 'Agency Details')
@section('breadcrumb-title')<h3>Agency Details</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.agencies.index') }}">Agencies</a></li>
    <li class="breadcrumb-item active">{{ $user->agencyProfile->agency_name ?? $user->company_name }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Agency Info</h5></div>
                <div class="card-body">
                    @if($user->agencyProfile)
                    <p><strong>Agency Name:</strong> {{ $user->agencyProfile->agency_name }}</p>
                    <p><strong>Website:</strong> {{ $user->agencyProfile->official_website_url ?? '—' }}</p>
                    <p><strong>City:</strong> {{ $user->agencyProfile->city ?? '—' }}, {{ $user->agencyProfile->country ?? '' }}</p>
                    <p><strong>Target City:</strong> {{ $user->agencyProfile->target_city ?? '—' }}</p>
                    <p><strong>AI Status:</strong> <span class="badge bg-{{ $user->agencyProfile->ai_status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($user->agencyProfile->ai_status ?? 'n/a') }}</span></p>
                    <p><strong>Subscription:</strong> {{ $user->agencyProfile->subscription_status ?? '—' }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>AI Feature Settings</h5></div>
                <div class="card-body">
                    @if($user->agencyProfile && $user->agencyProfile->aiFeatureSettings->count())
                        @foreach($user->agencyProfile->aiFeatureSettings as $fs)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ str_replace('_', ' ', ucfirst($fs->feature_key)) }}</span>
                            <span class="badge bg-{{ $fs->is_enabled ? 'success' : 'danger' }}">{{ $fs->is_enabled ? 'ON' : 'OFF' }}</span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No feature settings configured.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Usage Limits</h5></div>
                <div class="card-body">
                    @if($user->agencyProfile && $user->agencyProfile->usageLimits->count())
                        @php $ul = $user->agencyProfile->usageLimits->first(); @endphp
                        <p><strong>Period:</strong> {{ $ul->period_start }} — {{ $ul->period_end }}</p>
                        <p>Local SEO: {{ $ul->local_seo_pages_used }}/{{ $ul->local_seo_pages_limit }}</p>
                        <p>Competitor Scans: {{ $ul->competitor_scans_used }}/{{ $ul->competitor_scans_limit }}</p>
                        <p>AI Search Updates: {{ $ul->ai_search_freshness_updates_used }}/{{ $ul->ai_search_freshness_updates_limit }}</p>
                        <p>Authority Reviews: {{ $ul->authority_review_updates_used }}/{{ $ul->authority_review_updates_limit }}</p>
                        <p>Small Actions: {{ $ul->small_ai_content_actions_used }}/{{ $ul->small_ai_content_actions_limit }}</p>
                    @else
                        <p class="text-muted">No usage limits set.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
