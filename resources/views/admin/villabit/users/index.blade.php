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
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    + Add User
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
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

                        <div class="table-responsive" style="overflow: visible;">
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
                                            <td>
                                                @php
                                                    $roleColors = [
                                                        'super_admin'       => 'bg-danger',
                                                        'admin'             => 'bg-warning text-dark',
                                                        'manager'           => 'bg-info text-dark',
                                                        'real_estate_agency'=> 'bg-primary',
                                                        'investor'          => 'bg-success',
                                                    ];
                                                    $roleLabels = [
                                                        'super_admin'       => 'Super Admin',
                                                        'admin'             => 'Admin',
                                                        'manager'           => 'Manager',
                                                        'real_estate_agency'=> 'Real Estate Agency',
                                                        'investor'          => 'Investor',
                                                    ];
                                                    $roleColor = $roleColors[$u->role] ?? 'bg-secondary';
                                                    $roleLabel = $roleLabels[$u->role] ?? ucfirst(str_replace('_', ' ', $u->role));
                                                @endphp
                                                <span class="badge {{ $roleColor }}">{{ $roleLabel }}</span>
                                            </td>
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
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-strategy="fixed" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="background-color:#fff !important; z-index:9999 !important; box-shadow:0 4px 16px rgba(0,0,0,0.15);">
                                                        @if(!$u->isAdmin())
                                                            @if($u->status === 'waitlist')
                                                                <li>
                                                                    <form action="{{ route('admin.villabit.users.approve-waitlist', $u) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item text-success">
                                                                            ✔ Approve Access
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <form action="{{ route('admin.villabit.users.toggle-status', $u) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item {{ $u->status === 'active' ? 'text-warning' : 'text-success' }}">
                                                                            {{ $u->status === 'active' ? '⏸ Suspend' : '▶ Activate' }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <form action="{{ route('admin.villabit.impersonate.start', $u) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item text-info">
                                                                        → Login As
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('admin.villabit.users.destroy', $u) }}" method="POST"
                                                                      onsubmit="return confirm('Delete {{ addslashes($u->first_name . ' ' . $u->last_name) }}? This cannot be undone.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        🗑 Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <li><span class="dropdown-item text-muted">No actions</span></li>
                                                        @endif
                                                    </ul>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (el) {
            el.addEventListener('shown.bs.dropdown', function () {
                if (typeof feather !== 'undefined') feather.replace();
            });
        });
    });
</script>
@endpush
