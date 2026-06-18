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

                    <hr>
                    <h6 class="mb-3">Account / Usage Status Control</h6>
                    <form method="POST" action="{{ route('admin.villabit.agencies.toggle-status', $user) }}" class="d-flex align-items-center gap-2">
                        @csrf
                        @method('POST')
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="subscriptionToggle" name="subscription_status" value="active" {{ $user->agencyProfile && $user->agencyProfile->subscription_status === 'active' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="subscriptionToggle">
                                {{ $user->agencyProfile && $user->agencyProfile->subscription_status === 'active' ? 'Active' : 'On Hold' }}
                            </label>
                        </div>
                    </form>
                    <small class="text-muted">Toggle to set agency as Active or On Hold</small>
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
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>Usage Limits</h5>
                    @if($user->agencyProfile && $user->agencyProfile->usageLimits->count())
                        <a href="{{ url('admin/villabit/usage-limits/' . $user->agencyProfile->usageLimits->first()->id . '/edit') }}" class="btn btn-sm btn-outline-primary">Edit Limits</a>
                    @elseif($user->agencyProfile)
                        <form action="{{ url('admin/villabit/agencies/' . $user->id . '/create-usage-limits') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Create Default Limits</button>
                        </form>
                    @endif
                </div>
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
                        @if($user->agencyProfile)
                            <small class="text-muted">Click "Create Default Limits" to set up default usage limits for this agency.</small>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
