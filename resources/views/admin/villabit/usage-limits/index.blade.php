@extends('layouts.simple.master')
@section('title', 'Usage Limits')
@section('breadcrumb-title')<h3>Usage Limits</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Usage Limits</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ── GLOBAL PRESET PANEL ─────────────────────────────────────── --}}
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <strong>Global Limit Presets</strong>
            <small class="opacity-75">Apply one preset to ALL agencies at once, or use the dropdown in the table to change one agency.</small>
        </div>
        <div class="card-body">

            {{-- Preset overview table --}}
            <div class="table-responsive mb-3">
                <table class="table table-sm table-bordered mb-0" style="font-size:0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Preset</th>
                            <th>SEO Pages</th>
                            <th>Scans</th>
                            <th>Search Updates</th>
                            <th>Authority</th>
                            <th>Small Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($presets as $key => $p)
                    <tr>
                        <td><strong>{{ $p['label'] }}</strong></td>
                        <td>{{ $p['local_seo_pages_limit'] }}</td>
                        <td>{{ $p['competitor_scans_limit'] }}</td>
                        <td>{{ $p['ai_search_freshness_updates_limit'] }}</td>
                        <td>{{ $p['authority_review_updates_limit'] }}</td>
                        <td>{{ $p['small_ai_content_actions_limit'] }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Apply to ALL + Create missing --}}
            <div class="d-flex gap-3 flex-wrap align-items-end">
                <form method="POST" action="{{ route('admin.villabit.usage-limits.bulk-apply-preset') }}"
                      onsubmit="return confirm('Apply this preset to ALL agencies this month? This overwrites their current limits.')">
                    @csrf
                    <div class="input-group">
                        <select name="preset" class="form-select form-select-sm" style="min-width:150px;" required>
                            <option value="">— choose preset —</option>
                            @foreach($presets as $key => $p)
                                <option value="{{ $key }}">{{ $p['label'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Apply to ALL agencies</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.villabit.usage-limits.bulk-create') }}"
                      onsubmit="return confirm('Create Basic limits for agencies missing a limit this month?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">Create Missing (Basic)</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── PER-AGENCY TABLE ─────────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <strong>Agency Usage Limits — {{ now()->format('F Y') }}</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Agency</th>
                            <th>Period</th>
                            <th>SEO</th>
                            <th>Scans</th>
                            <th>Search</th>
                            <th>Authority</th>
                            <th>Small</th>
                            <th style="min-width:200px;">Change Preset</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($limits as $l)
                    <tr>
                        <td><strong>{{ $l->agencyProfile->agency_name ?? '—' }}</strong></td>
                        <td class="text-muted" style="font-size:0.8rem;">
                            {{ \Carbon\Carbon::parse($l->period_start)->format('d M') }}–{{ \Carbon\Carbon::parse($l->period_end)->format('d M Y') }}
                        </td>
                        <td>{{ $l->local_seo_pages_used }}/{{ $l->local_seo_pages_limit }}</td>
                        <td>{{ $l->competitor_scans_used }}/{{ $l->competitor_scans_limit }}</td>
                        <td>{{ $l->ai_search_freshness_updates_used }}/{{ $l->ai_search_freshness_updates_limit }}</td>
                        <td>{{ $l->authority_review_updates_used }}/{{ $l->authority_review_updates_limit }}</td>
                        <td>{{ $l->small_ai_content_actions_used }}/{{ $l->small_ai_content_actions_limit }}</td>
                        <td>
                            {{-- Inline preset dropdown --}}
                            <form method="POST" action="{{ route('admin.villabit.usage-limits.apply-preset', $l) }}"
                                  class="d-flex gap-1">
                                @csrf
                                <select name="preset" class="form-select form-select-sm" required>
                                    <option value="">— preset —</option>
                                    @foreach($presets as $key => $p)
                                        <option value="{{ $key }}">{{ $p['label'] }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Apply preset">✓</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.villabit.usage-limits.edit', $l) }}"
                               class="btn btn-outline-secondary btn-sm" title="Manual edit">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No usage limits found. Click "Create Missing" above.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $limits->links() }}</div>
        </div>
    </div>
</div>
@endsection
