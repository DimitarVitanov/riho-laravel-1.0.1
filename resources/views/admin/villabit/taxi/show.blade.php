@extends('layouts.simple.master')
@section('title', 'TAXI — ' . $report->country)
@section('breadcrumb-title')<h3>{{ $report->country }} — Global Data Report</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.taxi.global-data') }}">TAXI · Global Data</a></li>
    <li class="breadcrumb-item active">{{ $report->country }}</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Report</h5></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Public URL</dt>
                        <dd class="col-sm-8"><a href="{{ $publicUrl }}" target="_blank">{{ $publicUrl }}</a></dd>

                        <dt class="col-sm-4">Language</dt>
                        <dd class="col-sm-8">{{ strtoupper($report->locale) }}</dd>

                        <dt class="col-sm-4">Title</dt>
                        <dd class="col-sm-8">{{ $report->title }}</dd>

                        <dt class="col-sm-4">Meta description</dt>
                        <dd class="col-sm-8">{{ $report->meta_description }}</dd>

                        <dt class="col-sm-4">Last updated</dt>
                        <dd class="col-sm-8">
                            {{ $report->last_generated_at?->format('d M Y H:i') ?? 'never' }}
                            @if($report->last_generated_at)
                                <small class="text-muted">({{ $report->last_generated_at->diffForHumans() }})</small>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Next scheduled refresh</dt>
                        <dd class="col-sm-8">{{ $report->next_refresh_at?->format('d M Y') ?? '—' }}
                            <small class="text-muted">(every {{ $report->refresh_interval_days }} days)</small>
                        </dd>

                        <dt class="col-sm-4">Last refresh result</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $report->last_refresh_status === 'failed' ? 'danger' : 'success' }}">
                                {{ $report->last_refresh_status ?? '—' }}
                            </span>
                            <div class="small text-muted">{{ $report->last_refresh_note }}</div>
                        </dd>

                        <dt class="col-sm-4">Source file</dt>
                        <dd class="col-sm-8"><code class="small">{{ $report->source_file }}</code></dd>

                        <dt class="col-sm-4">Size</dt>
                        <dd class="col-sm-8">{{ number_format(strlen($report->html_full) / 1024, 1) }} KB</dd>
                    </dl>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">Live preview</h5></div>
                <div class="card-body p-0">
                    <iframe src="{{ route('admin.villabit.taxi.reports.preview', $report) }}"
                            style="width:100%;height:700px;border:0;"></iframe>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Actions</h5></div>
                <div class="card-body d-grid gap-2">
                    <form method="POST" action="{{ route('admin.villabit.taxi.reports.refresh', $report) }}">
                        @csrf
                        <button class="btn btn-primary w-100">Refresh content with AI now</button>
                    </form>

                    <form method="POST" action="{{ route('admin.villabit.taxi.reports.toggle-published', $report) }}">
                        @csrf
                        <button class="btn btn-outline-{{ $report->is_published ? 'danger' : 'success' }} w-100">
                            {{ $report->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>

                    <a class="btn btn-outline-secondary w-100"
                       href="{{ route('admin.villabit.taxi.reports.preview', $report) }}" target="_blank">Open preview in new tab</a>
                </div>
            </div>

            @if($report->locale === 'en')
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Translations</h5></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.villabit.taxi.reports.translate', $report) }}" class="d-flex gap-2 mb-3">
                        @csrf
                        <select name="locale" class="form-select form-select-sm">
                            @foreach(\App\Http\Controllers\Agency\AgencySettingsController::supportedPanelLanguages() as $code => $label)
                                @if($code !== 'en')
                                    <option value="{{ $code }}">{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-primary text-nowrap">Translate</button>
                    </form>

                    <ul class="list-group list-group-flush">
                        @forelse($translations as $t)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <a href="{{ route('admin.villabit.taxi.reports.show', $t) }}">{{ strtoupper($t->locale) }}</a>
                                <small class="text-muted">{{ $t->last_generated_at?->diffForHumans() ?? '—' }}</small>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">No translations yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
