@extends('layouts.simple.master')
@section('title', 'Investors')
@section('breadcrumb-title')<h3>Investors</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Investors</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>All Investors</h5>
                    <a href="{{ route('admin.villabit.users.create-investor') }}" class="btn btn-primary btn-sm">+ Add Investor</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>#</th><th>Name</th><th>Email</th><th>Type</th><th>KYC</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @forelse($investors as $i)
                                <tr>
                                    <td>{{ $i->id }}</td>
                                    <td>{{ $i->first_name }} {{ $i->last_name }}</td>
                                    <td>{{ $i->email }}</td>
                                    <td>{{ $i->investorProfile->investor_type ?? '—' }}</td>
                                    <td><span class="badge bg-{{ ($i->investorProfile->kyc_status ?? '') === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($i->investorProfile->kyc_status ?? 'n/a') }}</span></td>
                                    <td><span class="badge bg-{{ $i->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($i->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.villabit.investors.show', $i) }}" class="btn btn-outline-primary btn-sm">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted">No investors found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $investors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
