@extends('layouts.simple.master')
@section('title', 'TAXI — Global Data')
@section('breadcrumb-title')<h3>TAXI — Global Data Reports</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">TAXI · Global Data</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    {{-- Overview --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card"><div class="card-body py-3">
                <div class="fs-4 fw-bold">{{ $stats['total'] }}</div>
                <small class="text-muted">Country reports (EN)</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body py-3">
                <div class="fs-4 fw-bold">{{ $stats['published'] }}</div>
                <small class="text-muted">Published live</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body py-3">
                <div class="fs-4 fw-bold {{ $stats['due'] > 0 ? 'text-warning' : '' }}">{{ $stats['due'] }}</div>
                <small class="text-muted">Due for 30-day refresh</small>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body py-3">
                <div class="fs-4 fw-bold">{{ $stats['locales'] }}</div>
                <small class="text-muted">Languages published</small>
            </div></div>
        </div>
    </div>

    {{-- Cron settings --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Automatic content refresh (cron)</h5>
                <small class="text-muted">
                    Command: <code>php artisan taxi:refresh-reports</code> —
                    last run: <strong>{{ $settings['last_cron_run_at'] ?? 'never' }}</strong>
                </small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.villabit.taxi.prompts') }}" class="btn btn-outline-secondary btn-sm">AI Prompts</a>
                <form method="POST" action="{{ route('admin.villabit.taxi.run-cron') }}">
                    @csrf
                    <button class="btn btn-primary btn-sm">Run refresh now</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.villabit.taxi.settings.update') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-2">
                    <label class="form-label">Refresh every (days)</label>
                    <input type="number" name="refresh_interval_days" class="form-control" min="1" max="365"
                           value="{{ $settings['refresh_interval_days'] }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Reports per run</label>
                    <input type="number" name="reports_per_run" class="form-control" min="1" max="100"
                           value="{{ $settings['reports_per_run'] }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Content AI provider</label>
                    <select name="ai_provider" class="form-select">
                        @foreach(['openai' => 'OpenAI (premium writing)', 'gemini' => 'Google Gemini (cheap, search-grounded)', 'anthropic' => 'Anthropic Claude'] as $val => $lbl)
                            <option value="{{ $val }}" @selected($settings['ai_provider'] === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Translation provider</label>
                    <select name="translation_provider" class="form-select">
                        @foreach(['gemini' => 'Google Gemini', 'openai' => 'OpenAI', 'anthropic' => 'Anthropic Claude'] as $val => $lbl)
                            <option value="{{ $val }}" @selected($settings['translation_provider'] === $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="auto_refresh_enabled" value="0">
                        <input class="form-check-input" type="checkbox" name="auto_refresh_enabled" value="1"
                               id="autoRefresh" @checked($settings['auto_refresh_enabled'] === '1')>
                        <label class="form-check-label" for="autoRefresh">Cron enabled</label>
                    </div>
                    <button class="btn btn-outline-primary btn-sm w-100">Save settings</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reports --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reports on realestate.taxi/globaldata/</h5>
            <form method="GET" class="d-flex align-items-center gap-2">
                <label class="mb-0 small text-muted">Language</label>
                <select name="locale" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($locales as $code)
                        <option value="{{ $code }}" @selected($locale === $code)>{{ strtoupper($code) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th>URL</th>
                        <th>Last updated</th>
                        <th>Next refresh</th>
                        <th>Last run</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('admin.villabit.taxi.reports.show', $report) }}">
                                <strong>{{ $report->country }}</strong>
                            </a>
                        </td>
                        <td><code class="small">{{ $report->publicUrl($report->locale) }}</code></td>
                        <td>
                            @if($report->last_generated_at)
                                {{ $report->last_generated_at->format('d M Y H:i') }}
                                <br><small class="text-muted">{{ $report->last_generated_at->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">never</span>
                            @endif
                        </td>
                        <td>
                            @if($report->next_refresh_at)
                                <span class="{{ $report->next_refresh_at->isPast() ? 'text-warning fw-bold' : '' }}">
                                    {{ $report->next_refresh_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><small>{{ $report->last_refresh_status ?? '—' }}</small></td>
                        <td>
                            <span class="badge bg-{{ $report->is_published ? 'success' : 'secondary' }}">
                                {{ $report->is_published ? 'Published' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-outline-secondary btn-sm"
                               href="{{ route('admin.villabit.taxi.reports.preview', $report) }}" target="_blank">Preview</a>
                            <form method="POST" action="{{ route('admin.villabit.taxi.reports.refresh', $report) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-primary btn-sm">Refresh with AI</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No reports imported yet. Run <code>php artisan taxi:import-reports</code>.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
