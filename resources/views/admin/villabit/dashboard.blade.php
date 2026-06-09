@extends('layouts.simple.master')

@section('title', 'Villa Bit AI — Admin Dashboard')

@section('breadcrumb-title')
    <h3>Admin Dashboard</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Usage Period Status Block -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="text-white mb-1">Platform Overview — {{ now()->format('F Y') }}</h5>
                                <span>Period: {{ now()->startOfMonth()->format('M j, Y') }} – {{ now()->endOfMonth()->format('M j, Y') }}</span>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark fs-6">System Active</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6">
                <div class="card o-hidden border-0">
                    <div class="card-body bg-light-primary d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="f-14 f-w-500">Total Agencies</span>
                            <h4 class="mb-0 mt-1 counter">{{ $total_agencies }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i data-feather="briefcase" class="font-primary" style="width:40px;height:40px;opacity:0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card o-hidden border-0">
                    <div class="card-body bg-light-secondary d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="f-14 f-w-500">Total Investors</span>
                            <h4 class="mb-0 mt-1 counter">{{ $total_investors }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i data-feather="trending-up" class="font-secondary" style="width:40px;height:40px;opacity:0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card o-hidden border-0">
                    <div class="card-body bg-light-success d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="f-14 f-w-500">Total Managers</span>
                            <h4 class="mb-0 mt-1 counter">{{ $total_managers }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i data-feather="users" class="font-success" style="width:40px;height:40px;opacity:0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card o-hidden border-0">
                    <div class="card-body bg-light-warning d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="f-14 f-w-500">Waitlist</span>
                            <h4 class="mb-0 mt-1 counter">{{ $waitlist_count }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i data-feather="clock" class="font-warning" style="width:40px;height:40px;opacity:0.5;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-xl-4 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.villabit.users.create-manager') }}" class="btn btn-outline-primary">+ Add Manager</a>
                            <a href="{{ route('admin.villabit.users.create-agency') }}" class="btn btn-outline-success">+ Add Agency</a>
                            <a href="{{ route('admin.villabit.users.create-investor') }}" class="btn btn-outline-info">+ Add Investor</a>
                            <a href="{{ route('admin.villabit.users.index') }}" class="btn btn-outline-secondary">View All Users</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-sm-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Recent Signups</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_users as $u)
                                        <tr>
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
                                            <td>{{ $u->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-muted">No users yet</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
