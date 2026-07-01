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
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.villabit.managers.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0"><h5>User Info</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                        </div>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
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
                            <div class="mb-3">
                                <label class="form-label">Job Title</label>
                                <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $user->managerProfile->job_title) }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" value="{{ old('department', $user->managerProfile->department) }}">
                            </div>
                            <hr>
                            <h6 class="mb-3">Permissions</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_manage_agencies" value="1" {{ $user->managerProfile->can_manage_agencies ? 'checked' : '' }}>
                                        <label class="form-check-label">Can Manage Agencies</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_manage_investors" value="1" {{ $user->managerProfile->can_manage_investors ? 'checked' : '' }}>
                                        <label class="form-check-label">Can Manage Investors</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_review_ai_outputs" value="1" {{ $user->managerProfile->can_review_ai_outputs ? 'checked' : '' }}>
                                        <label class="form-check-label">Can Review AI Outputs</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_prepare_payouts" value="1" {{ $user->managerProfile->can_prepare_payouts ? 'checked' : '' }}>
                                        <label class="form-check-label">Can Prepare Payouts</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_view_financials" value="1" {{ $user->managerProfile->can_view_financials ? 'checked' : '' }}>
                                        <label class="form-check-label">Can View Financials</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="can_login_as_user" value="1" {{ $user->managerProfile->can_login_as_user ? 'checked' : '' }}>
                                        <label class="form-check-label">Can Login As User</label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="can_view_agency_readonly" value="1" id="edit_view_agency" {{ $user->managerProfile->can_view_agency_readonly ? 'checked' : '' }} onchange="document.getElementById('edit-agency-select').style.display = this.checked ? 'block' : 'none'">
                                <label class="form-check-label fw-semibold" for="edit_view_agency">Can View Agency Panel (Read-Only)</label>
                            </div>
                            <div id="edit-agency-select" style="{{ $user->managerProfile->can_view_agency_readonly ? '' : 'display:none;' }}">
                                <label class="form-label">Assigned Agency</label>
                                <select name="view_agency_user_id" class="form-select">
                                    <option value="">— None —</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->id }}" {{ $user->managerProfile->view_agency_user_id == $agency->id ? 'selected' : '' }}>{{ $agency->agencyProfile->agency_name ?? $agency->company_name }} ({{ $agency->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <p class="text-muted">No manager profile yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <form action="{{ route('admin.villabit.impersonate.start', $user) }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm">Login As This Manager</button>
                </form>
            </div>
        </div>
    </form>
</div>
@endsection
