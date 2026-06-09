@extends('layouts.simple.master')

@section('title', 'Create Agency')

@section('breadcrumb-title')
    <h3>Create Agency User</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.users.index') }}">Users</a></li>
    <li class="breadcrumb-item active">Create Agency</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-10 mx-auto">
                <div class="card">
                    <div class="card-header pb-0"><h5>New Agency Account</h5></div>
                    <div class="card-body">
                        <form action="{{ route('admin.villabit.users.store-agency') }}" method="POST">
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
                                    <label class="form-label">Company / Agency Name *</label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                                    @error('company_name')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country *</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country') }}" required>
                                    @error('country')<span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Assign Manager</label>
                                    <select name="assigned_manager_id" class="form-select">
                                        <option value="">-- None --</option>
                                        @foreach($managers as $mgr)
                                            <option value="{{ $mgr->id }}">{{ $mgr->first_name }} {{ $mgr->last_name }}</option>
                                        @endforeach
                                    </select>
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
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Create Agency</button>
                                <a href="{{ route('admin.villabit.users.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
