@extends('layouts.simple.master')
@section('title', 'Payouts')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Investor Payouts</h1>
            <p>Your scheduled and completed payouts including preferred return partial payouts, rental profit distributions, and exit proceeds.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Scheduled</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payouts as $p)
            <tr>
                <td>{{ $p->created_at->format('Y-m-d') }}</td>
                <td><strong>€{{ number_format($p->amount, 0) }}</strong> {{ $p->currency !== 'EUR' ? $p->currency : '' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $p->payout_type)) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $p->payout_method ?? '—')) }}</td>
                <td>
                    @php
                        $cls = match($p->payout_status) {
                            'paid' => 'vb-badge-success',
                            'pending' => 'vb-badge-warning',
                            'approved' => 'vb-badge-info',
                            default => 'vb-badge-muted'
                        };
                    @endphp
                    <span class="vb-badge {{ $cls }}">{{ ucfirst($p->payout_status) }}</span>
                </td>
                <td>{{ $p->scheduled_for ? \Carbon\Carbon::parse($p->scheduled_for)->format('M j, Y') : '—' }}</td>
                <td>{{ $p->paid_at ? \Carbon\Carbon::parse($p->paid_at)->format('M j, Y') : '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="vb-empty">
                        <h3>No payouts yet</h3>
                        <p>Your payout history will appear here once distributions have been processed.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">{{ $payouts->links() }}</div>
    </div>
</div>
@endsection
