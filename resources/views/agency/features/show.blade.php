@extends('layouts.simple.master')
@section('title', str_replace('_', ' ', ucfirst($feature)))
@section('breadcrumb-title')<h3>{{ str_replace('_', ' ', ucwords($feature, '_')) }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">{{ str_replace('_', ' ', ucwords($feature, '_')) }}</li>
@endsection
@section('content')
<div class="container-fluid">
    {{-- Feature ON/OFF --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-{{ $featureSetting && $featureSetting->is_enabled ? 'success' : 'secondary' }}">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ str_replace('_', ' ', ucwords($feature, '_')) }}</h5>
                        <small class="text-muted">Feature status</small>
                    </div>
                    <span class="badge bg-{{ $featureSetting && $featureSetting->is_enabled ? 'success' : 'danger' }} fs-6">
                        {{ $featureSetting && $featureSetting->is_enabled ? 'ON' : 'OFF' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature settings --}}
    @if($featureSetting)
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Settings</h5></div>
                <div class="card-body">
                    <p><strong>AI Model:</strong> {{ $featureSetting->ai_model_provider ?? '—' }} / {{ $featureSetting->ai_model_name ?? '—' }}</p>
                    <p><strong>Frequency:</strong> {{ ucfirst($featureSetting->frequency ?? 'manual') }}</p>
                    <p><strong>Last Run:</strong> {{ $featureSetting->last_run_at ?? 'Never' }}</p>
                    <p><strong>Next Run:</strong> {{ $featureSetting->next_run_at ?? '—' }}</p>
                    @if($featureSetting->custom_prompt_override)
                    <p><strong>Custom Prompt:</strong></p>
                    <pre class="bg-light p-2">{{ $featureSetting->custom_prompt_override }}</pre>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Latest AI Report</h5></div>
                <div class="card-body">
                    @if($latestReport)
                    <p><strong>Date:</strong> {{ $latestReport->report_date->format('M d, Y') }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $latestReport->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($latestReport->status) }}</span></p>
                    <p><strong>Uniqueness:</strong> <span class="badge bg-info">{{ strtoupper($latestReport->content_uniqueness_status ?? 'N/A') }}</span></p>
                    <p><strong>Summary:</strong> {{ $latestReport->ai_actions_summary ?? 'No summary.' }}</p>
                    @if($latestReport->completed_tasks_json)
                    <p><strong>Tasks:</strong></p>
                    <ul>@foreach($latestReport->completed_tasks_json as $t)<li>{{ $t }}</li>@endforeach</ul>
                    @endif
                    @else
                    <p class="text-muted">No reports yet for this feature.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
