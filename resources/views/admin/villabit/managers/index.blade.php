@extends('layouts.simple.master')
@section('title', 'Managers')
@section('breadcrumb-title')<h3>Managers</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Managers</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>All Managers</h5>
                    <a href="{{ route('admin.villabit.users.create-manager') }}" class="btn btn-primary btn-sm">+ Add Manager</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th><th>Name</th><th>Email</th><th>Department</th><th>Status</th><th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($managers as $m)
                                <tr>
                                    <td>{{ $m->id }}</td>
                                    <td>{{ $m->first_name }} {{ $m->last_name }}</td>
                                    <td>{{ $m->email }}</td>
                                    <td>{{ $m->managerProfile->department ?? '—' }}</td>
                                    <td><span class="badge bg-{{ $m->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($m->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.villabit.managers.show', $m) }}" class="btn btn-outline-primary btn-sm">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted">No managers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $managers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
