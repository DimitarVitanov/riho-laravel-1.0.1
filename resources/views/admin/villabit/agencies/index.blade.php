@extends('layouts.simple.master')
@section('title', 'Agencies')
@section('breadcrumb-title')<h3>Real Estate Agencies</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Agencies</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>All Agencies</h5>
                    <a href="{{ route('admin.villabit.users.create-agency') }}" class="btn btn-primary btn-sm">+ Add Agency</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>#</th><th>Agency</th><th>Owner</th><th>City</th><th>AI Status</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @forelse($agencies as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>
                                    <td>{{ $a->agencyProfile->agency_name ?? $a->company_name ?? '—' }}</td>
                                    <td>{{ $a->first_name }} {{ $a->last_name }}</td>
                                    <td>{{ $a->agencyProfile->city ?? '—' }}</td>
                                    <td><span class="badge bg-{{ ($a->agencyProfile->ai_status ?? '') === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($a->agencyProfile->ai_status ?? 'n/a') }}</span></td>
                                    <td><span class="badge bg-{{ $a->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($a->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.villabit.agencies.show', $a) }}" class="btn btn-outline-primary btn-sm">View</a>
                                        <form action="{{ route('admin.villabit.impersonate.start', $a) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-info btn-sm">Login As</button></form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center text-muted">No agencies found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $agencies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
