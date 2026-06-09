@extends('layouts.simple.master')
@section('title', 'AI Reports')
@section('breadcrumb-title')<h3>AI Daily Reports</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Manager</a></li>
    <li class="breadcrumb-item active">AI Reports</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Date</th><th>Agency</th><th>Feature</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($reports as $r)
                    <tr>
                        <td>{{ $r->report_date->format('M d, Y') }}</td>
                        <td>{{ $r->agencyProfile->agency_name ?? '—' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($r->feature_key)) }}</td>
                        <td><span class="badge bg-{{ $r->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($r->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">No reports for assigned agencies.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
