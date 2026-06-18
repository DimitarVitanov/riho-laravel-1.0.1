@extends('layouts.simple.master')
@section('title', 'Daily AI Employee')
@section('breadcrumb-title')
    <h3>Daily AI Employee</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">Daily AI Employee</li>
@endsection

@section('content')
<div class="container-fluid daily-ai-employee">
    
    {{-- Main Settings Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                {{-- Card Header --}}
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">Daily AI Employee</h5>
                        <small class="text-muted">Always Active</small>
                    </div>
                    <span class="badge bg-success"><i class="fa fa-check-circle me-1"></i>Running</span>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('agency.daily-ai-employee.save-settings') }}" method="POST">
                        @csrf
                        
                        {{-- Settings Row --}}
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Feature status</label>
                                <div class="form-control bg-success bg-opacity-10 text-success fw-bold">
                                    <i class="fa fa-check-circle me-2"></i>Always Active (Cannot be disabled)
                                </div>
                                <input type="hidden" name="is_enabled" value="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">AI posting language</label>
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
                                <label class="form-label text-muted small fw-bold">Usage this period / month</label>
                                <div class="form-control bg-light">
                                    <span class="text-dark">{{ now()->format('F Y') }} status: {{ $featureSetting && $featureSetting->frequency === 'daily' ? 'Daily active' : 'Active' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">Uniqueness status</label>
                                <div class="form-control bg-light">
                                    <span class="text-success fw-bold">PASSED before publish</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-dark" role="progressbar" style="width: 75%"></div>
                        </div>
                        
                        {{-- Daily AI Report Section --}}
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold mb-2">Daily AI Report</h6>
                            <p class="text-muted mb-0">
                                {{ $latestReport->ai_actions_summary ?? 'AI summarized completed tasks across SEO, competitor scan, lead magnet, and authority builder.' }}
                            </p>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">Save</button>
                            <a href="{{ route('agency.daily-ai-employee.logs') }}" class="btn btn-outline-secondary">View Logs</a>
                            <a href="{{ route('agency.daily-ai-employee.prompt') }}" class="btn btn-outline-secondary">Open Prompt</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Date Filter Section with Calendar --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('agency.daily-ai-employee.index') }}" class="row align-items-end">
                        {{-- Quick Date Presets --}}
                        <div class="col-md-2 mb-2">
                            <label class="form-label text-muted small">Quick Select</label>
                            <select name="date_preset" class="form-select" onchange="applyDatePreset(this.value)">
                                <option value="">Select...</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last_7_days">Last 7 Days</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                            </select>
                        </div>
                        
                        {{-- Calendar Date From --}}
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-muted small">From Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" name="date_from" id="dateFrom" class="form-control" 
                                    value="{{ request('date_from', $dateFrom?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        
                        {{-- Calendar Date To --}}
                        <div class="col-md-3 mb-2">
                            <label class="form-label text-muted small">To Date</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                <input type="date" name="date_to" id="dateTo" class="form-control" 
                                    value="{{ request('date_to', $dateTo?->format('Y-m-d')) }}">
                            </div>
                        </div>
                        
                        {{-- Action Buttons --}}
                        <div class="col-md-4 mb-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fa fa-filter me-1"></i>Apply Filter
                                </button>
                                <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Active Filter Display --}}
                    @if($dateFrom && $dateTo)
                    <div class="mt-2 pt-2 border-top">
                        <span class="badge bg-dark">
                            <i class="fa fa-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                        </span>
                        <small class="text-muted ms-2">Click dates above to change range</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function applyDatePreset(preset) {
            const today = new Date();
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            
            switch(preset) {
                case 'today':
                    dateFrom.value = formatDate(today);
                    dateTo.value = formatDate(today);
                    break;
                case 'yesterday':
                    const yesterday = new Date(today);
                    yesterday.setDate(yesterday.getDate() - 1);
                    dateFrom.value = formatDate(yesterday);
                    dateTo.value = formatDate(yesterday);
                    break;
                case 'last_7_days':
                    const last7 = new Date(today);
                    last7.setDate(last7.getDate() - 7);
                    dateFrom.value = formatDate(last7);
                    dateTo.value = formatDate(today);
                    break;
                case 'last_30_days':
                    const last30 = new Date(today);
                    last30.setDate(last30.getDate() - 30);
                    dateFrom.value = formatDate(last30);
                    dateTo.value = formatDate(today);
                    break;
                case 'this_month':
                    dateFrom.value = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                    dateTo.value = formatDate(today);
                    break;
                case 'last_month':
                    dateFrom.value = formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1));
                    dateTo.value = formatDate(new Date(today.getFullYear(), today.getMonth(), 0));
                    break;
            }
            
            if (preset) {
                document.querySelector('form').submit();
            }
        }
        
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }
        
        function markAsReviewed(id, checkbox) {
            if (checkbox.checked) {
                fetch(`{{ url('daily-ai-employee/suggestions') }}/${id}/review`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        checkbox.closest('tr').style.opacity = '0.5';
                        checkbox.closest('tr').style.textDecoration = 'line-through';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
        
        function toggleAll(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.review-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
                if (masterCheckbox.checked) {
                    markAsReviewed(cb.dataset.id, cb);
                }
            });
        }
    </script>

    {{-- Content Inbox Section --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.content_inbox') }}</h5>
                        <small class="text-muted">{{ __('messages.review_and_approve') }}</small>
                    </div>
                    <span class="badge bg-dark text-white fs-6">{{ $stats['pending_count'] }} {{ __('messages.awaiting_review') }}</span>
                </div>
                <div class="card-body p-0">
                    @if($pendingSuggestions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAll(this)">
                                            </div>
                                        </th>
                                        <th>{{ __('messages.feature') }}</th>
                                        <th>{{ __('messages.type') }}</th>
                                        <th>{{ __('messages.title_keyword') }}</th>
                                        <th>{{ __('messages.summary') }}</th>
                                        <th style="width: 200px;">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingSuggestions as $suggestion)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input review-checkbox" type="checkbox" 
                                                    data-id="{{ $suggestion->id }}"
                                                    onchange="markAsReviewed({{ $suggestion->id }}, this)">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $featureLabels[$suggestion->feature_key] ?? str_replace('_', ' ', ucwords($suggestion->feature_key)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ ucfirst(str_replace('_', ' ', $suggestion->suggestion_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $suggestion->title }}</strong>
                                            @if($suggestion->target_keyword)
                                                <br><small class="text-muted">{{ $suggestion->target_keyword }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ Str::limit($suggestion->ai_summary ?? strip_tags($suggestion->content_html), 80) }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $suggestion->id }}">
                                                    {{ __('messages.accept') }}
                                                </button>
                                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#skipModal{{ $suggestion->id }}">
                                                    {{ __('messages.skip') }}
                                                </button>
                                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeModal{{ $suggestion->id }}">
                                                    {{ __('messages.remove') }}
                                                </button>
                                            </div>
                                            <button class="btn btn-sm btn-outline-dark mt-1 d-block" data-bs-toggle="modal" data-bs-target="#previewModal{{ $suggestion->id }}">
                                                {{ __('messages.preview') }}
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">🎉 {{ __('messages.all_caught_up') }}</h5>
                            <p class="text-muted">{{ __('messages.no_pending_suggestions') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

    {{-- Ready to Publish Section --}}
    @if($acceptedSuggestions->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">✓ Ready to Publish ({{ $stats['accepted_count'] }})</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($acceptedSuggestions as $suggestion)
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-secondary">{{ $featureLabels[$suggestion->feature_key] ?? $suggestion->feature_key }}</span>
                                        <span class="badge bg-success">Accepted</span>
                                    </div>
                                    <h6>{{ $suggestion->title }}</h6>
                                    <p class="small text-muted mb-2">{{ Str::limit(strip_tags($suggestion->content_html), 150) }}</p>
                                    
                                    @if($suggestion->converted_to_page_id)
                                        @php
                                            $page = $suggestion->generatedPage;
                                        @endphp
                                        @if($page && $page->status !== 'published')
                                            <form action="{{ route('agency.daily-ai-employee.publish', $page) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                    {{ $page->content_uniqueness_status !== 'passed' ? 'disabled' : '' }}>
                                                    🚀 Publish Now
                                                </button>
                                                @if($page->content_uniqueness_status !== 'passed')
                                                    <small class="text-warning d-block mt-1">Waiting for uniqueness check...</small>
                                                @endif
                                            </form>
                                        @elseif($page && $page->status === 'published')
                                            <span class="badge bg-success">✓ Published</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Recent Activity --}}
    @if($suggestionsHistory->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📜 Recent Activity</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Feature</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                    <th>Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suggestionsHistory as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $featureLabels[$item->feature_key] ?? $item->feature_key }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($item->title, 40) }}</td>
                                    <td>
                                        @if($item->status === 'skipped')
                                            <span class="badge bg-secondary">Skipped</span>
                                        @elseif($item->status === 'removed')
                                            <span class="badge bg-danger">Removed</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $item->reviewed_at?->diffForHumans() ?? 'N/A' }}</small></td>
                                    <td><small class="text-muted">{{ $item->reviewer_notes ? Str::limit($item->reviewer_notes, 30) : '-' }}</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Modals for each pending suggestion --}}
@foreach($pendingSuggestions as $suggestion)

{{-- Accept Modal --}}
<div class="modal fade" id="acceptModal{{ $suggestion->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('agency.daily-ai-employee.accept', $suggestion) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">✓ Accept Suggestion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ $suggestion->title }}</strong></p>
                    <p class="text-muted">This will move the content to "Ready to Publish" queue.</p>
                    <div class="mb-3">
                        <label class="form-label">Notes (optional):</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Add any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Accept & Move to Queue</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Skip Modal --}}
<div class="modal fade" id="skipModal{{ $suggestion->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('agency.daily-ai-employee.skip', $suggestion) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">⊘ Skip Suggestion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ $suggestion->title }}</strong></p>
                    <p class="text-muted">This will skip the suggestion but keep it in history.</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (optional):</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Why are you skipping this?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-secondary">Skip</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Remove Modal --}}
<div class="modal fade" id="removeModal{{ $suggestion->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('agency.daily-ai-employee.remove', $suggestion) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">✕ Remove Suggestion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ $suggestion->title }}</strong></p>
                    <p class="text-danger">This will permanently remove the suggestion from the queue.</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (optional):</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Why are you removing this?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal{{ $suggestion->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title">{{ $suggestion->title }}</h5>
                    <small class="text-muted">
                        From: {{ $featureLabels[$suggestion->feature_key] ?? $suggestion->feature_key }} | 
                        Type: {{ ucfirst($suggestion->suggestion_type) }}
                        @if($suggestion->target_keyword)
                            | Keyword: "{{ $suggestion->target_keyword }}"
                        @endif
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($suggestion->ai_summary)
                    <div class="alert alert-info">
                        <strong>AI Summary:</strong> {{ $suggestion->ai_summary }}
                    </div>
                @endif
                
                @if($suggestion->ai_conclusion)
                    <div class="alert alert-secondary">
                        <strong>AI Conclusion:</strong> {{ $suggestion->ai_conclusion }}
                    </div>
                @endif

                <div class="border rounded p-3 bg-light">
                    <h6>Content Preview:</h6>
                    <div class="content-preview">
                        {!! $suggestion->content_html !!}
                    </div>
                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Uniqueness Status: 
                        <span class="badge bg-{{ ($suggestion->content_uniqueness_status ?? 'pending') === 'passed' ? 'success' : 'warning' }}">
                            {{ strtoupper($suggestion->content_uniqueness_status ?? 'PENDING') }}
                        </span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('agency.daily-ai-employee.accept', $suggestion) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">✓ Accept</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endforeach

@endsection

@push('styles')
<style>
.daily-ai-employee .content-preview {
    max-height: 400px;
    overflow-y: auto;
}
.daily-ai-employee .card {
    transition: all 0.2s ease;
}
.daily-ai-employee .card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush
