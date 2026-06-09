@extends('layouts.simple.master')

@section('title', 'Create Manager')

@section('breadcrumb-title')
    <h3>Create Manager</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Create Manager</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header pb-0"><h5>New Manager Account</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.villabit.users.store-manager') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                    @error('first_name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                    @error('last_name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control" value="{{ old('department') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password *</label>
                                    <input type="password" name="password" class="form-control" required>
                                    @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password *</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6>Permissions</h6>
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_manage_agencies" value="1" checked>
                                        <label class="form-check-label">Can Manage Agencies</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_manage_investors" value="1">
                                        <label class="form-check-label">Can Manage Investors</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_review_ai_outputs" value="1" checked>
                                        <label class="form-check-label">Can Review AI Outputs</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_prepare_payouts" value="1">
                                        <label class="form-check-label">Can Prepare Payouts</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_view_financials" value="1">
                                        <label class="form-check-label">Can View Financials</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="can_login_as_user" value="1">
                                        <label class="form-check-label">Can Login As User</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Create Manager</button>
                                <a href="{{ route('admin.villabit.users.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
