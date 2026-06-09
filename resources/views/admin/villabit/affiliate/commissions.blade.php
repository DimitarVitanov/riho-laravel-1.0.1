@extends('layouts.simple.master')
@section('title', 'Affiliate Commissions')
@section('breadcrumb-title')<h3>Affiliate Commissions</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Commissions</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Reseller</th><th>Referred</th><th>Payment</th><th>Commission</th><th>Status</th><th>Earned</th></tr></thead>
                    <tbody>
                    @forelse($commissions as $c)
                    <tr>
                        <td>{{ $c->resellerUser->first_name ?? '' }} {{ $c->resellerUser->last_name ?? '' }}</td>
                        <td>{{ $c->referredUser->first_name ?? '' }} {{ $c->referredUser->last_name ?? '' }}</td>
                        <td>{{ number_format($c->gross_payment_amount, 2) }} {{ $c->currency }}</td>
                        <td>{{ number_format($c->commission_amount, 2) }} ({{ $c->commission_percent }}%)</td>
                        <td><span class="badge bg-{{ $c->commission_status === 'paid' ? 'success' : ($c->commission_status === 'approved' ? 'primary' : 'warning') }}">{{ ucfirst($c->commission_status) }}</span></td>
                        <td>{{ $c->earned_at ? \Carbon\Carbon::parse($c->earned_at)->format('M d, Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No commissions.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $commissions->links() }}
        </div>
    </div>
</div>
@endsection
