@extends('layouts.simple.master')
@section('title', 'Daily AI Employee - Logs')
@section('breadcrumb-title')
    <h3>Daily AI Employee Logs</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.daily-ai-employee.index') }}">Daily AI Employee</a></li>
    <li class="breadcrumb-item active">Logs</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">AI Activity Logs</h5>
                        <small class="text-muted">History of all AI-generated content and actions</small>
                    </div>
                    <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Inbox</a>
                </div>
                <div class="card-body p-0">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Feature</th>
                                        <th>Status</th>
                                        <th>Summary</th>
                                        <th>Tokens</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->report_date->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ str_replace('_', ' ', ucwords($log->feature_key)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($log->ai_actions_summary, 60) }}</td>
                                        <td>{{ number_format($log->token_input_count + $log->token_output_count) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">
                                                View Details
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">No logs yet</h5>
                            <p class="text-muted">AI activity will appear here once the feature starts generating content.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Log Detail Modals --}}
@foreach($logs as $log)
<div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details - {{ $log->report_date->format('M d, Y H:i') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Feature:</strong> {{ str_replace('_', ' ', ucwords($log->feature_key)) }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-{{ $log->status === 'completed' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}">
                        {{ ucfirst($log->status) }}
                    </span>
                </p>
                <p><strong>AI Model:</strong> {{ $log->ai_model_used ?? 'N/A' }}</p>
                <p><strong>Tokens:</strong> {{ number_format($log->token_input_count + $log->token_output_count) }}</p>
                
                <hr>
                
                <h6>AI Actions Summary:</h6>
                <p>{{ $log->ai_actions_summary ?? 'No summary available.' }}</p>
                
                @if($log->completed_tasks_json)
                <h6>Completed Tasks:</h6>
                <ul>
                    @foreach($log->completed_tasks_json as $task)
                    <li>{{ $task }}</li>
                    @endforeach
                </ul>
                @endif
                
                @if($log->detected_issues_json)
                <h6>Issues Detected:</h6>
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        @foreach($log->detected_issues_json as $issue)
                        <li>{{ $issue }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
