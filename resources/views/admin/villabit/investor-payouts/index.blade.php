@extends('layouts.simple.master')
@section('title', 'Investor Payouts')
@section('breadcrumb-title')<h3>Investor Payouts</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Investor Payouts</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Investor</th><th>Type</th><th>Amount</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($payouts as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ $p->investorUser->first_name ?? '' }} {{ $p->investorUser->last_name ?? '' }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($p->payout_type)) }}</td>
                        <td>{{ number_format($p->amount, 2) }} {{ $p->currency }}</td>
                        <td><span class="badge bg-{{ $p->payout_status === 'paid' ? 'success' : ($p->payout_status === 'approved' ? 'primary' : 'warning') }}">{{ ucfirst($p->payout_status) }}</span></td>
                        <td>
                            @if($p->payout_status === 'pending')
                            <form action="{{ route('admin.villabit.investor-payouts.approve', $p) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-success btn-sm">Approve</button></form>
                            @endif
                            @if($p->payout_status === 'approved')
                            <form action="{{ route('admin.villabit.investor-payouts.mark-paid', $p) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-primary btn-sm">Mark Paid</button></form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No payouts found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection
