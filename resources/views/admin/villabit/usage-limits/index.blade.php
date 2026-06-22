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
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Agency Usage Limits — {{ now()->format('F Y') }}</strong>
            <form method="POST" action="{{ route('admin.villabit.usage-limits.bulk-create') }}" onsubmit="return confirm('Create default limits for all agencies missing a limit this month?')">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">Create Missing Limits</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead><tr><th>Agency</th><th>Period</th><th>SEO</th><th>Scans</th><th>Search</th><th>Authority</th><th>Small</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($limits as $l)
                    <tr>
                        <td>{{ $l->agencyProfile->agency_name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->period_start)->format('d M Y') }} — {{ \Carbon\Carbon::parse($l->period_end)->format('d M Y') }}</td>
                        <td>{{ $l->local_seo_pages_used }}/{{ $l->local_seo_pages_limit }}</td>
                        <td>{{ $l->competitor_scans_used }}/{{ $l->competitor_scans_limit }}</td>
                        <td>{{ $l->ai_search_freshness_updates_used }}/{{ $l->ai_search_freshness_updates_limit }}</td>
                        <td>{{ $l->authority_review_updates_used }}/{{ $l->authority_review_updates_limit }}</td>
                        <td>{{ $l->small_ai_content_actions_used }}/{{ $l->small_ai_content_actions_limit }}</td>
                        <td>
                            <a href="{{ route('admin.villabit.usage-limits.edit', $l) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">No usage limits found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $limits->links() }}
        </div>
    </div>
</div>
@endsection
