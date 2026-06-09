@extends('layouts.simple.master')
@section('title', 'Investor Profile')
@section('breadcrumb-title')<h3>My Profile</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Profile</li>
@endsection
@section('content')
<div class="container-fluid">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Account Info</h5></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? '—' }}</p>
                    <p><strong>Country:</strong> {{ $user->country ?? '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>KYC / Accreditation</h5></div>
                <div class="card-body">
                    @if($profile)
                    <p><strong>Investor Type:</strong> {{ $profile->investor_type ?? '—' }}</p>
                    <p><strong>KYC Status:</strong> <span class="badge bg-{{ $profile->kyc_status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($profile->kyc_status ?? 'n/a') }}</span></p>
                    <p><strong>AML Status:</strong> <span class="badge bg-{{ $profile->aml_status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($profile->aml_status ?? 'n/a') }}</span></p>
                    <p><strong>Accreditation:</strong> <span class="badge bg-{{ $profile->accreditation_status === 'verified' ? 'success' : 'warning' }}">{{ ucfirst($profile->accreditation_status ?? 'n/a') }}</span></p>
                    <p><strong>Structure:</strong> {{ $profile->eligible_structure ?? '—' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Update Profile</h5></div>
                <div class="card-body">
                    <form action="{{ route('investor.profile.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3"><label class="form-label">Citizenship Country</label><input type="text" name="citizenship_country" class="form-control" value="{{ $profile->citizenship_country ?? '' }}"></div>
                        <div class="mb-3"><label class="form-label">Residence Country</label><input type="text" name="residence_country" class="form-control" value="{{ $profile->residence_country ?? '' }}"></div>
                        <div class="mb-3"><label class="form-label">Preferred Currency</label><input type="text" name="preferred_currency" class="form-control" value="{{ $profile->preferred_currency ?? '' }}" maxlength="10"></div>
                        <div class="mb-3"><label class="form-label">Payout Method</label>
                            <select name="payout_method" class="form-select">
                                @foreach(['bank_wire','stripe','paypal','crypto','other'] as $m)
                                <option value="{{ $m }}" {{ ($profile->payout_method ?? '') === $m ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$m)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
