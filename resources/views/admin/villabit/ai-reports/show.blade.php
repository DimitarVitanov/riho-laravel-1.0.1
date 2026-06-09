@extends('layouts.simple.master')
@section('title', 'AI Report Detail')
@section('breadcrumb-title')<h3>AI Report — {{ $report->report_date->format('M d, Y') }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.ai-reports.index') }}">AI Reports</a></li>
    <li class="breadcrumb-item active">{{ $report->report_date->format('M d, Y') }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Report Summary</h5></div>
                <div class="card-body">
                    <p><strong>Agency:</strong> {{ $report->agencyProfile->agency_name ?? '—' }}</p>
                    <p><strong>Feature:</strong> {{ str_replace('_', ' ', ucfirst($report->feature_key)) }}</p>
                    <p><strong>AI Model:</strong> {{ $report->ai_model_used ?? '—' }}</p>
                    <p><strong>Tokens:</strong> {{ $report->token_input_count ?? 0 }} in / {{ $report->token_output_count ?? 0 }} out</p>
                    <p><strong>Cost Estimate:</strong> ${{ $report->api_cost_estimate ?? '0.0000' }}</p>
                    <p><strong>Uniqueness:</strong> <span class="badge bg-info">{{ strtoupper($report->content_uniqueness_status ?? 'N/A') }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>AI Actions Summary</h5></div>
                <div class="card-body">
                    <p>{{ $report->ai_actions_summary ?? 'No summary available.' }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Completed Tasks</h5></div>
                <div class="card-body">
                    @if($report->completed_tasks_json)
                        <ul>@foreach($report->completed_tasks_json as $t)<li>{{ $t }}</li>@endforeach</ul>
                    @else <p class="text-muted">None.</p> @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Detected Issues</h5></div>
                <div class="card-body">
                    @if($report->detected_issues_json)
                        <ul>@foreach($report->detected_issues_json as $i)<li>{{ $i }}</li>@endforeach</ul>
                    @else <p class="text-muted">None.</p> @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
