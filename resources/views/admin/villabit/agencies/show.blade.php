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
    {{-- Onboarding Progress Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Onboarding Progress</h5>
                    <span class="badge bg-{{ $user->isOnboardingComplete() ? 'success' : 'warning' }} fs-6">
                        Step {{ $user->onboarding_step }}/6: {{ $user->getOnboardingStepLabel() }}
                    </span>
                </div>
                <div class="card-body">
                    {{-- Progress Steps --}}
                    <div class="d-flex justify-content-between mb-4" style="position: relative;">
                        @foreach(\App\Models\User::$onboardingSteps as $stepNum => $stepInfo)
                            @php
                                $isCompleted = $user->onboarding_step > $stepNum;
                                $isCurrent = $user->onboarding_step == $stepNum;
                                $isPending = $user->onboarding_step < $stepNum;
                            @endphp
                            <div class="text-center" style="flex: 1; position: relative;">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                     style="width: 40px; height: 40px; 
                                            background: {{ $isCompleted ? '#28a745' : ($isCurrent ? '#ffc107' : '#e9ecef') }};
                                            color: {{ $isCompleted ? '#fff' : ($isCurrent ? '#000' : '#aaa') }};">
                                    @if($isCompleted)
                                        <i data-feather="check" style="width:18px;height:18px;"></i>
                                    @else
                                        {{ $stepNum }}
                                    @endif
                                </div>
                                <div class="small {{ $isCurrent ? 'fw-bold' : '' }}" style="font-size: 0.75rem;">
                                    {{ $stepInfo['label'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Admin Controls --}}
                    <div class="border-top pt-3">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Current Step:</strong> {{ $user->getOnboardingStepLabel() }}</p>
                                <p class="mb-0 text-muted small">{{ $user->getOnboardingStepDescription() }}</p>
                                @if($user->onboarding_step_updated_at)
                                    <p class="mb-0 text-muted small">Last updated: {{ \Carbon\Carbon::parse($user->onboarding_step_updated_at)->diffForHumans() }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                @if(!$user->isOnboardingComplete())
                                    <form action="{{ route('admin.villabit.agencies.advance-onboarding', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Advance to next step?')">
                                            <i data-feather="arrow-right" style="width:16px;height:16px;"></i> Advance to Next Step
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success fs-6 py-2 px-3">✓ Onboarding Complete</span>
                                @endif

                                {{-- Dropdown to set specific step --}}
                                <div class="dropdown d-inline ms-2">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Set Step
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach(\App\Models\User::$onboardingSteps as $stepNum => $stepInfo)
                                            <li>
                                                <form action="{{ route('admin.villabit.agencies.set-onboarding-step', $user) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="step" value="{{ $stepNum }}">
                                                    <button type="submit" class="dropdown-item {{ $user->onboarding_step == $stepNum ? 'active' : '' }}">
                                                        {{ $stepNum }}. {{ $stepInfo['label'] }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header pb-0"><h5>View-Only Manager</h5></div>
                <div class="card-body">
                    @php
                        $viewOnlyManager = \App\Models\ManagerProfile::where('view_agency_user_id', $user->id)
                            ->where('can_view_agency_readonly', true)
                            ->with('user')
                            ->first();
                    @endphp
                    @if($viewOnlyManager)
                        <p><strong>Assigned Manager:</strong> {{ $viewOnlyManager->user->first_name }} {{ $viewOnlyManager->user->last_name }} ({{ $viewOnlyManager->user->email }})</p>
                        <form action="{{ route('admin.villabit.agencies.remove-view-only-manager', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove view-only manager from this agency?')">Remove</button>
                        </form>
                    @else
                        <form action="{{ route('admin.villabit.agencies.assign-view-only-manager', $user) }}" method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label">Select Manager</label>
                                <select name="manager_user_id" class="form-select" required>
                                    <option value="">— Select Manager —</option>
                                    @foreach(\App\Models\User::where('role', 'manager')->where('status', 'active')->get() as $mgr)
                                        <option value="{{ $mgr->id }}">{{ $mgr->first_name }} {{ $mgr->last_name }} ({{ $mgr->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-sm">Assign as View-Only</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header pb-0"><h5>Domain Settings</h5></div>
                <div class="card-body">
                    @if($user->agencyProfile)
                    <form method="POST" action="{{ route('admin.villabit.agencies.domain-settings.update', $user) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Custom Domain</label>
                            <input type="text" name="custom_domain" class="form-control" value="{{ old('custom_domain', $user->agencyProfile->custom_domain) }}" placeholder="yourdomain.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Server Name</label>
                            <input type="text" name="server_name" class="form-control" value="{{ old('server_name', $user->agencyProfile->server_name) }}" placeholder="Server1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Server IP</label>
                            <input type="text" name="server_ip" class="form-control" value="{{ old('server_ip', $user->agencyProfile->server_ip) }}" placeholder="165.227.125.83">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SFTP Username</label>
                            <input type="text" name="sftp_username" class="form-control" value="{{ old('sftp_username', $user->agencyProfile->sftp_username) }}" placeholder="username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SFTP Password</label>
                            <input type="password" name="sftp_password" class="form-control" value="{{ old('sftp_password', $user->agencyProfile->sftp_password) }}" placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">SFTP Path</label>
                            <input type="text" name="sftp_path" class="form-control" value="{{ old('sftp_path', $user->agencyProfile->sftp_path) }}" placeholder="/public_html">
                            <small class="text-muted">Default: /public_html</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nameserver 1</label>
                            <input type="text" name="nameserver_1" class="form-control" value="{{ old('nameserver_1', $user->agencyProfile->nameserver_1) }}" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nameserver 2</label>
                            <input type="text" name="nameserver_2" class="form-control" value="{{ old('nameserver_2', $user->agencyProfile->nameserver_2) }}" placeholder="">
                        </div>
                        <div class="mb-3">
                            <strong>DNS Status:</strong>
                            @if($user->agencyProfile->is_dns_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning text-dark">Waiting</span>
                            @endif
                            @if($user->agencyProfile->last_dns_check_at)
                                <br><small class="text-muted">Last checked: {{ \Carbon\Carbon::parse($user->agencyProfile->last_dns_check_at)->diffForHumans() }}</small>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Save Domain Settings</button>
                    </form>
                    @else
                        <p class="text-muted">Agency profile not found.</p>
                    @endif
                </div>
            </div>
        </div>
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
