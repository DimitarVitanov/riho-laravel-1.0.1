@extends('layouts.simple.master')
@section('title', 'Payouts')
@section('breadcrumb-title')<h3>Payouts</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Payouts</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Type</th><th>Amount</th><th>Method</th><th>Status</th><th>Scheduled</th><th>Paid</th></tr></thead>
                    <tbody>
                    @forelse($payouts as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($p->payout_type)) }}</td>
                        <td>{{ number_format($p->amount, 2) }} {{ $p->currency }}</td>
                        <td>{{ ucfirst($p->payout_method ?? '—') }}</td>
                        <td><span class="badge bg-{{ $p->payout_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($p->payout_status) }}</span></td>
                        <td>{{ $p->scheduled_for ?? '—' }}</td>
                        <td>{{ $p->paid_at ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No payouts yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payouts->links() }}
        </div>
    </div>
</div>
@endsection
