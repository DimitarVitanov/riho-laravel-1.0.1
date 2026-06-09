@extends('layouts.simple.master')
@section('title', 'Manager Details')
@section('breadcrumb-title')<h3>Manager Details</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.managers.index') }}">Managers</a></li>
    <li class="breadcrumb-item active">{{ $user->first_name }} {{ $user->last_name }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>User Info</h5></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? '—' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($user->status) }}</span></p>
                    <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Manager Profile</h5></div>
                <div class="card-body">
                    @if($user->managerProfile)
                        <p><strong>Employee Code:</strong> {{ $user->managerProfile->employee_code ?? '—' }}</p>
                        <p><strong>Job Title:</strong> {{ $user->managerProfile->job_title ?? '—' }}</p>
                        <p><strong>Department:</strong> {{ $user->managerProfile->department ?? '—' }}</p>
                        <p><strong>Can Manage Agencies:</strong> {{ $user->managerProfile->can_manage_agencies ? 'Yes' : 'No' }}</p>
                        <p><strong>Can Manage Investors:</strong> {{ $user->managerProfile->can_manage_investors ? 'Yes' : 'No' }}</p>
                        <p><strong>Can Review AI:</strong> {{ $user->managerProfile->can_review_ai_outputs ? 'Yes' : 'No' }}</p>
                        <p><strong>Can Login As User:</strong> {{ $user->managerProfile->can_login_as_user ? 'Yes' : 'No' }}</p>
                    @else
                        <p class="text-muted">No manager profile yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
