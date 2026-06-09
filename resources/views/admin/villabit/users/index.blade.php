@extends('layouts.simple.master')

@section('title', 'User Management')

@section('breadcrumb-title')
    <h3>User Management</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>All Users</h5>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">+ Add User</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-manager') }}">Add Manager</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-agency') }}">Add Agency</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-investor') }}">Add Investor</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-auto">
                                <select name="role" class="form-select form-select-sm">
                                    <option value="">All Roles</option>
                                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="real_estate_agency" {{ request('role') === 'real_estate_agency' ? 'selected' : '' }}>Agency</option>
                                    <option value="investor" {{ request('role') === 'investor' ? 'selected' : '' }}>Investor</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="waitlist" {{ request('status') === 'waitlist' ? 'selected' : '' }}>Waitlist</option>
                                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Reseller</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $u)
                                        <tr>
                                            <td>{{ $u->id }}</td>
                                            <td>{{ $u->first_name }} {{ $u->last_name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td><span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</span></td>
                                            <td>
                                                @if($u->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($u->status === 'waitlist')
                                                    <span class="badge bg-warning">Waitlist</span>
                                                @elseif($u->status === 'suspended')
                                                    <span class="badge bg-danger">Suspended</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($u->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($u->is_reseller_enabled)
                                                    <span class="badge bg-info">{{ $u->referral_code }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $u->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if(!$u->isAdmin())
                                                        @if($u->status === 'waitlist')
                                                            <form action="{{ route('admin.villabit.users.approve-waitlist', $u) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success" title="Approve & Grant Access">
                                                                    <i data-feather="check-circle"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                        <form action="{{ route('admin.villabit.users.toggle-status', $u) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-{{ $u->status === 'active' ? 'warning' : 'success' }}" title="{{ $u->status === 'active' ? 'Suspend' : 'Activate' }}">
                                                                <i data-feather="{{ $u->status === 'active' ? 'pause-circle' : 'play-circle' }}"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                        <form action="{{ route('admin.villabit.impersonate.start', $u) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-info" title="Login As">
                                                                <i data-feather="log-in"></i>
                                                            </button>
                                                        </form>
                                                        @if(!$u->is_reseller_enabled)
                                                            <form action="{{ route('admin.villabit.users.enable-reseller', $u) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-secondary" title="Enable Reseller">
                                                                    <i data-feather="share-2"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">No users found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
