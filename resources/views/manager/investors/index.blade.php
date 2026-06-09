@extends('layouts.simple.master')
@section('title', 'My Investors')
@section('breadcrumb-title')<h3>Assigned Investors</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Manager</a></li>
    <li class="breadcrumb-item active">Investors</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Type</th><th>KYC</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($investors as $i)
                    <tr>
                        <td>{{ $i->id }}</td>
                        <td>{{ $i->first_name }} {{ $i->last_name }}</td>
                        <td>{{ $i->email }}</td>
                        <td>{{ $i->investorProfile->investor_type ?? '—' }}</td>
                        <td><span class="badge bg-{{ ($i->investorProfile->kyc_status ?? '') === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($i->investorProfile->kyc_status ?? 'n/a') }}</span></td>
                        <td><span class="badge bg-{{ $i->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($i->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No assigned investors.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $investors->links() }}
        </div>
    </div>
</div>
@endsection
