@extends('layouts.simple.master')
@section('title', 'Create Investment Project')
@section('breadcrumb-title')<h3>Create Project</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.investment-projects.index') }}">Projects</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0"><h5>New Investment Project</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.villabit.investment-projects.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6"><label class="form-label">Project Name *</label><input type="text" name="project_name" class="form-control" required value="{{ old('project_name') }}"></div>
                            <div class="col-md-6"><label class="form-label">Project Code *</label><input type="text" name="project_code" class="form-control" required value="{{ old('project_code') }}"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><label class="form-label">Location</label><input type="text" name="project_location" class="form-control" value="{{ old('project_location') }}"></div>
                            <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="project_country" class="form-control" value="{{ old('project_country') }}"></div>
                            <div class="col-md-3"><label class="form-label">Status</label>
                                <select name="project_status" class="form-select">
                                    @foreach(['draft','open','funding','building','rented','exiting','closed'] as $s)
                                    <option value="{{ $s }}" {{ old('project_status','draft')===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label class="form-label">Min Investment</label><input type="number" step="0.01" name="minimum_investment_amount" class="form-control" value="{{ old('minimum_investment_amount', 0) }}"></div>
                            <div class="col-md-4"><label class="form-label">Target Raise</label><input type="number" step="0.01" name="target_raise_amount" class="form-control" value="{{ old('target_raise_amount', 0) }}"></div>
                            <div class="col-md-4"><label class="form-label">Max Raise</label><input type="number" step="0.01" name="max_raise_amount" class="form-control" value="{{ old('max_raise_amount', 0) }}"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><label class="form-label">Preferred Return %</label><input type="number" step="0.01" name="preferred_return_percent" class="form-control" value="{{ old('preferred_return_percent', 0) }}"></div>
                            <div class="col-md-4"><label class="form-label">Rental Profit Share %</label><input type="number" step="0.01" name="rental_profit_share_percent" class="form-control" value="{{ old('rental_profit_share_percent', 0) }}"></div>
                            <div class="col-md-4"><label class="form-label">Exit Profit Share %</label><input type="number" step="0.01" name="project_exit_profit_share_percent" class="form-control" value="{{ old('project_exit_profit_share_percent', 0) }}"></div>
                        </div>
                        <div class="mb-3"><label class="form-label">Summary</label><textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea></div>
                        <div class="mb-3"><label class="form-label">Full Description</label><textarea name="full_description" class="form-control" rows="5">{{ old('full_description') }}</textarea></div>
                        <button type="submit" class="btn btn-primary">Create Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
