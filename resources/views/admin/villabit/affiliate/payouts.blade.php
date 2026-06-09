@extends('layouts.simple.master')
@section('title', 'Affiliate Payouts')
@section('breadcrumb-title')<h3>Affiliate Payouts</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Affiliate Payouts</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Reseller</th><th>Month</th><th>Amount</th><th>Method</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($payouts as $p)
                    <tr>
                        <td>{{ $p->resellerUser->first_name ?? '' }} {{ $p->resellerUser->last_name ?? '' }}</td>
                        <td>{{ $p->payout_batch_month }}</td>
                        <td>{{ number_format($p->amount, 2) }} {{ $p->currency }}</td>
                        <td>{{ ucfirst($p->payout_method) }}</td>
                        <td><span class="badge bg-{{ $p->payout_status === 'paid' ? 'success' : ($p->payout_status === 'approved' ? 'primary' : 'warning') }}">{{ ucfirst($p->payout_status) }}</span></td>
                        <td>
                            @if($p->payout_status === 'requested')
                            <form action="{{ route('admin.villabit.affiliate-payouts.approve', $p) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-success btn-sm">Approve</button></form>
                            @endif
                            @if($p->payout_status === 'approved')
                            <form action="{{ route('admin.villabit.affiliate-payouts.mark-paid', $p) }}" method="POST" class="d-inline">@csrf<input type="hidden" name="transaction_reference" value=""><button class="btn btn-outline-primary btn-sm">Mark Paid</button></form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No payouts.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection
