@extends('layouts.simple.master')
@section('title', 'AI Daily Reports')
@section('breadcrumb-title')<h3>AI Daily Reports</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">AI Reports</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Date</th><th>Agency</th><th>Feature</th><th>Status</th><th>Uniqueness</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($reports as $r)
                    <tr>
                        <td>{{ $r->report_date->format('M d, Y') }}</td>
                        <td>{{ $r->agencyProfile->agency_name ?? '—' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($r->feature_key)) }}</td>
                        <td><span class="badge bg-{{ $r->status === 'completed' ? 'success' : ($r->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($r->status) }}</span></td>
                        <td><span class="badge bg-secondary">{{ strtoupper($r->content_uniqueness_status ?? 'N/A') }}</span></td>
                        <td><a href="{{ route('admin.villabit.ai-reports.show', $r) }}" class="btn btn-outline-primary btn-sm">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No reports found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
