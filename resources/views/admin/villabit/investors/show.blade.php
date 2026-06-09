@extends('layouts.simple.master')
@section('title', 'Investor Details')
@section('breadcrumb-title')<h3>Investor Details</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.investors.index') }}">Investors</a></li>
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
                    <p><strong>Status:</strong> <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($user->status) }}</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Investor Profile</h5></div>
                <div class="card-body">
                    @if($user->investorProfile)
                    <p><strong>Type:</strong> {{ $user->investorProfile->investor_type ?? '—' }}</p>
                    <p><strong>KYC:</strong> {{ ucfirst($user->investorProfile->kyc_status ?? 'n/a') }}</p>
                    <p><strong>AML:</strong> {{ ucfirst($user->investorProfile->aml_status ?? 'n/a') }}</p>
                    <p><strong>Accreditation:</strong> {{ ucfirst($user->investorProfile->accreditation_status ?? 'n/a') }}</p>
                    <p><strong>Structure:</strong> {{ $user->investorProfile->eligible_structure ?? '—' }}</p>
                    <p><strong>Max Commitment:</strong> {{ number_format($user->investorProfile->max_commitment_amount ?? 0, 2) }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if($user->investorProfile && $user->investorProfile->investments->count())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h5>Investments</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Project</th><th>Committed</th><th>Funded</th><th>Earnings</th><th>Status</th></tr></thead>
                            <tbody>
                            @foreach($user->investorProfile->investments as $inv)
                                <tr>
                                    <td>{{ $inv->project->project_name ?? '—' }}</td>
                                    <td>{{ number_format($inv->committed_amount, 2) }}</td>
                                    <td>{{ number_format($inv->funded_amount, 2) }}</td>
                                    <td>{{ number_format($inv->total_earnings_accrued, 2) }}</td>
                                    <td><span class="badge bg-info">{{ ucfirst($inv->investment_status) }}</span></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
