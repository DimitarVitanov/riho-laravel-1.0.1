@extends('layouts.simple.master')
@section('title', __('messages.invisible_lead_magnet'))
@section('breadcrumb-title')
    <h3>{{ __('messages.invisible_lead_magnet') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.invisible_lead_magnet') }}</li>
@endsection

@section('content')
<div class="container-fluid invisible-lead-magnet">
    
    {{-- Main Settings Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                {{-- Card Header with Title and Toggle --}}
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.invisible_lead_magnet') }}</h5>
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
                    <form action="{{ route('agency.invisible-lead-magnet.save-settings') }}" method="POST" id="settingsForm">
                        @csrf
                        <input type="hidden" name="is_enabled" id="isEnabledInput" value="{{ $featureSetting && $featureSetting->is_enabled ? '1' : '0' }}">
                        
                        {{-- Settings Row --}}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.feature_status') }}</label>
                                <div class="form-control bg-light" id="statusDisplay">
                                    <span class="fw-bold {{ $featureSetting && $featureSetting->is_enabled ? 'text-dark' : 'text-muted' }}">
                                        <i class="fa {{ $featureSetting && $featureSetting->is_enabled ? 'fa-check-circle' : 'fa-circle-o' }} me-2"></i>
                                        {{ $featureSetting && $featureSetting->is_enabled ? __('messages.on_collecting_leads') : __('messages.off_not_active') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
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
                        </div>
                        
                        {{-- Status Info Row --}}
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.usage_this_period') }}</label>
                                <div class="form-control bg-light">
                                    <span class="text-dark">{{ now()->format('F Y') }}: {{ $featureSetting && $featureSetting->is_enabled ? __('messages.active') : __('messages.inactive') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.uniqueness_status') }}</label>
                                <div class="form-control bg-light">
                                    <span class="fw-bold">{{ __('messages.passed_before_publish') }}</span>
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
                                {{ $latestReport->ai_actions_summary ?? __('messages.lead_magnet_ai_summary') }}
                            </p>
                        </div>
                        
                        {{-- Lead Magnet URL --}}
                        @if($featureSetting && $featureSetting->is_enabled)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="fw-bold text-dark"><i class="fa fa-link me-2"></i>{{ __('messages.your_lead_capture_url') }}</h6>
                            <p class="mb-2 text-dark">{{ __('messages.share_this_link') }}</p>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ route('lead-magnet.show', ['agency' => $profile->id]) }}" readonly id="leadUrl">
                                <button class="btn btn-dark" type="button" id="copyBtn" onclick="copyUrl()">
                                    <i class="fa fa-copy"></i> {{ __('messages.copy') }}
                                </button>
                            </div>
                        </div>
                        @endif
                        
                        {{-- Action Buttons --}}
                        <div class="row g-2">
                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn btn-dark w-100">{{ __('messages.save') }}</button>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="{{ route('agency.invisible-lead-magnet.logs') }}" class="btn btn-outline-secondary w-100">{{ __('messages.view_logs') }}</a>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="{{ route('agency.invisible-lead-magnet.prompt') }}" class="btn btn-outline-secondary w-100">{{ __('messages.open_prompt') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Leads Table Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.captured_leads') }}</h5>
                        <small class="text-muted">{{ __('messages.leads_collected_through_lead_magnet') }}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('agency.invisible-lead-magnet.export') }}" class="btn btn-dark btn-sm">
                            <i class="fa fa-download me-1"></i>{{ __('messages.export_csv') }}
                        </a>
                        <span class="badge bg-dark text-white fs-6">{{ $profile->leads()->count() }} {{ __('messages.total_leads') }}</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @php
                        $leads = $profile->leads()->where('source', 'invisible_lead_magnet')->latest()->paginate(20);
                    @endphp
                    
                    @if($leads->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.name') }}</th>
                                        <th>{{ __('messages.email') }}</th>
                                        <th>{{ __('messages.phone') }}</th>
                                        <th>{{ __('messages.interest') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                    <tr>
                                        <td><strong>{{ $lead->full_name }}</strong></td>
                                        <td>{{ $lead->email }}</td>
                                        <td>{{ $lead->phone ?? '—' }}</td>
                                        <td>{{ $lead->interest_amount ? '€' . number_format($lead->interest_amount, 0) : '—' }}</td>
                                        <td>
                                            <span class="badge bg-dark">{{ ucfirst($lead->status) }}</span>
                                        </td>
                                        <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('agency.leads.show', $lead) }}" class="btn btn-sm btn-outline-dark">{{ __('messages.view') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            @include('partials.pagination', ['paginator' => $leads])
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">{{ __('messages.no_leads_captured_yet') }}</h5>
                            <p class="text-muted">{{ __('messages.enable_feature_and_share_url') }}</p>
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
            statusDisplay.innerHTML = '<span class="fw-bold text-dark"><i class="fa fa-check-circle me-2"></i>ON - Collecting Leads</span>';
        } else {
            statusDisplay.innerHTML = '<span class="fw-bold text-muted"><i class="fa fa-circle-o me-2"></i>OFF - Not Active</span>';
        }
    }
    
    function copyUrl() {
        var urlInput = document.getElementById('leadUrl');
        var btn = document.getElementById('copyBtn');
        var url = urlInput.value;
        
        // Create temporary textarea for reliable copying
        var textarea = document.createElement('textarea');
        textarea.value = url;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        
        try {
            document.execCommand('copy');
            btn.innerHTML = '<i class="fa fa-check"></i> Copied!';
            btn.classList.remove('btn-dark');
            btn.classList.add('btn-success');
        } catch (err) {
            console.error('Copy failed', err);
        }
        
        document.body.removeChild(textarea);
        
        // Reset button after 2 seconds
        setTimeout(function() {
            btn.innerHTML = '<i class="fa fa-copy"></i> Copy';
            btn.classList.remove('btn-success');
            btn.classList.add('btn-dark');
        }, 2000);
    }
</script>
@endsection
