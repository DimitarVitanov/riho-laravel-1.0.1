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

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Row 1: User Info + Investor Profile --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header pb-0"><h5>User Info</h5></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Country:</strong> {{ $user->country ?? '—' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($user->status) }}</span></p>
                    <p><strong>Registered:</strong> {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header pb-0"><h5>Investor Profile</h5></div>
                <div class="card-body">
                    @if($user->investorProfile)
                    <p><strong>Type:</strong> {{ $user->investorProfile->investor_type ?? '—' }}</p>
                    <p><strong>Structure:</strong> {{ $user->investorProfile->eligible_structure ?? '—' }}</p>
                    <p><strong>KYC:</strong>
                        <span class="badge bg-{{ $user->investorProfile->kyc_status === 'approved' ? 'success' : ($user->investorProfile->kyc_status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($user->investorProfile->kyc_status ?? 'n/a') }}
                        </span>
                    </p>
                    <p><strong>AML:</strong>
                        <span class="badge bg-{{ $user->investorProfile->aml_status === 'approved' ? 'success' : ($user->investorProfile->aml_status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($user->investorProfile->aml_status ?? 'n/a') }}
                        </span>
                    </p>
                    <p><strong>Accreditation:</strong> {{ ucfirst(str_replace('_', ' ', $user->investorProfile->accreditation_status ?? 'n/a')) }}</p>
                    <p><strong>Onboarding Phase:</strong>
                        <span class="badge bg-{{ $user->investorProfile->onboarding_phase === 'approved' ? 'success' : 'secondary' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->investorProfile->onboarding_phase ?? 'initial')) }}
                        </span>
                    </p>
                    <p><strong>Max Commitment:</strong> {{ $user->investorProfile->preferred_currency ?? 'EUR' }} {{ number_format($user->investorProfile->max_commitment_amount ?? 0, 2) }}</p>
                    <p><strong>KYC Submitted:</strong> {{ $user->investorProfile->kyc_submitted_at?->format('d M Y') ?? '—' }}</p>
                    <p><strong>KYC Approved:</strong> {{ $user->investorProfile->kyc_approved_at?->format('d M Y') ?? '—' }}</p>
                    @else
                    <p class="text-muted">No investor profile found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: KYC Status Management + Reseller/Affiliate --}}
    <div class="row mb-4">
        @if($user->investorProfile)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header pb-0"><h5>KYC & Onboarding Management</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.villabit.investors.update-kyc-status', $user) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">KYC Status</label>
                            <select name="kyc_status" class="form-select form-select-sm">
                                @foreach(['pending','under_review','approved','rejected'] as $s)
                                <option value="{{ $s }}" {{ $user->investorProfile->kyc_status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">AML Status</label>
                            <select name="aml_status" class="form-select form-select-sm">
                                @foreach(['pending','under_review','approved','rejected'] as $s)
                                <option value="{{ $s }}" {{ $user->investorProfile->aml_status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Accreditation Status</label>
                            <select name="accreditation_status" class="form-select form-select-sm">
                                @foreach(['not_started','pending','verified','rejected'] as $s)
                                <option value="{{ $s }}" {{ $user->investorProfile->accreditation_status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Eligible Structure</label>
                            <select name="eligible_structure" class="form-select form-select-sm">
                                <option value="">— Not Set —</option>
                                @foreach(['usa_llc','uk_llp','pending_review'] as $s)
                                <option value="{{ $s }}" {{ $user->investorProfile->eligible_structure === $s ? 'selected' : '' }}>{{ strtoupper(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Onboarding Phase</label>
                            <select name="onboarding_phase" class="form-select form-select-sm">
                                @foreach(['initial','eligibility_review','kyc_portal','documents_review','approved','rejected'] as $s)
                                <option value="{{ $s }}" {{ $user->investorProfile->onboarding_phase === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header pb-0"><h5>Affiliate / Reseller Access</h5></div>
                <div class="card-body">
                    <div class="mb-3 p-3 rounded" style="background:#f8f9fa;border:1px solid #e9ecef;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <div class="fw-bold">Reseller Program</div>
                                <div class="text-muted small">Investor gets a referral link and earns 10% lifetime commissions on referrals</div>
                            </div>
                            <span class="badge bg-{{ $user->is_reseller_enabled ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                                {{ $user->is_reseller_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        @if($user->is_reseller_enabled && $user->referral_code)
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-1">Current Referral Link</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" readonly value="{{ url('/ref/' . $user->referral_code) }}">
                                <button class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText('{{ url('/ref/' . $user->referral_code) }}')">Copy</button>
                            </div>
                            <div class="text-muted mt-1" style="font-size:11px;">Code: <strong>{{ $user->referral_code }}</strong></div>
                        </div>
                        @endif
                    </div>

                    <form action="{{ route('admin.villabit.investors.update-reseller', $user) }}" method="POST">
                        @csrf
                        <div class="d-flex gap-2">
                            @if(!$user->is_reseller_enabled)
                            <input type="hidden" name="is_reseller_enabled" value="1">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-check me-1"></i> Enable Reseller Access
                            </button>
                            @else
                            <input type="hidden" name="is_reseller_enabled" value="0">
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Disable reseller access for this investor?')">
                                <i class="fa fa-times me-1"></i> Disable Reseller Access
                            </button>
                            @endif
                        </div>
                        <p class="text-muted mt-2 mb-0" style="font-size:11px;">
                            Enabling generates a unique referral code automatically if one does not exist yet.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Investments --}}
    @if($user->investorProfile && $user->investorProfile->investments->count())
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0"><h5>Investments</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Project</th><th>Committed</th><th>Funded</th>
                                    <th>Earnings Accrued</th><th>Status</th>
                                </tr>
                            </thead>
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
