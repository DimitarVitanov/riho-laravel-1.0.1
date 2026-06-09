@extends('layouts.simple.master')
@section('title', 'My Agencies')
@section('breadcrumb-title')<h3>Assigned Agencies</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Manager</a></li>
    <li class="breadcrumb-item active">Agencies</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Agency</th><th>Owner</th><th>City</th><th>AI Status</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($agencies as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->agencyProfile->agency_name ?? $a->company_name ?? '—' }}</td>
                        <td>{{ $a->first_name }} {{ $a->last_name }}</td>
                        <td>{{ $a->agencyProfile->city ?? '—' }}</td>
                        <td><span class="badge bg-{{ ($a->agencyProfile->ai_status ?? '') === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($a->agencyProfile->ai_status ?? 'n/a') }}</span></td>
                        <td><span class="badge bg-{{ $a->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($a->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No assigned agencies.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $agencies->links() }}
        </div>
    </div>
</div>
@endsection
