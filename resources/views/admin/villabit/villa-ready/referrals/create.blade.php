@extends('layouts.simple.master')
@section('title', 'Add Manual Referral')
@section('breadcrumb-title')<h3>Add Manual Referral</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.villa-ready.referrals.index') }}">Affiliate Tracking</a></li>
    <li class="breadcrumb-item active">Add Manual Referral</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Add Manual Referral</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.villabit.villa-ready.referrals.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Property *</label>
                            <select name="villa_ready_property_id" class="form-control" required>
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                <option value="{{ $property->id }}">{{ $property->property_id }} — {{ $property->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agency *</label>
                            <select name="agency_profile_id" class="form-control" required>
                                <option value="">Select Agency</option>
                                @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}">{{ $agency->agency_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visitor Name</label>
                            <input type="text" name="visitor_name" class="form-control" value="{{ old('visitor_name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visitor Email</label>
                            <input type="email" name="visitor_email" class="form-control" value="{{ old('visitor_email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="visited">VISITED</option>
                                <option value="viewed">VIEWED</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.villabit.villa-ready.referrals.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Referral</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
