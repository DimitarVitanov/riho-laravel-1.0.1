@extends('layouts.simple.master')
@section('title', 'Affiliate / Reseller')
@section('breadcrumb-title')<h3>Affiliate Dashboard</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Affiliate</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Total Clicks</h6><h3>{{ $totalClicks }}</h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Signups</h6><h3>{{ $totalSignups }}</h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Pending</h6><h3>{{ number_format($pendingCommissions, 2) }}</h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Paid</h6><h3>{{ number_format($paidCommissions, 2) }}</h3></div></div></div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h5>Your Referral Link</h5></div>
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" readonly value="{{ url('/ref/' . ($user->referral_code ?? 'N/A')) }}">
                <button class="btn btn-outline-primary" onclick="navigator.clipboard.writeText(this.previousElementSibling.value)">Copy</button>
            </div>
            <small class="text-muted">180-day cookie. 10% lifetime commission. Minimum payout: $10. Payouts on the 1st of each month.</small>
        </div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h5>Recent Referrals</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Date</th><th>Status</th><th>Converted</th></tr></thead>
                    <tbody>
                    @forelse($referrals as $r)
                    <tr>
                        <td>{{ $r->created_at->format('M d, Y') }}</td>
                        <td><span class="badge bg-{{ $r->status === 'active_client' ? 'success' : 'secondary' }}">{{ ucfirst($r->status) }}</span></td>
                        <td>{{ $r->converted_at ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted">No referrals yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $referrals->links() }}
        </div>
    </div>
</div>
@endsection
