@extends('layouts.simple.master')
@section('title', 'Payout Preparation')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Payout Preparation</h1>
            <p>Prepare and review pending investor and affiliate payouts for processing.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card" style="margin-bottom: 28px;">
        <h2 class="vb-section-title">Pending Investor Payouts</h2>
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Investor</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($investorPayouts as $payout)
                <tr>
                    <td><strong>{{ $payout->investorUser->full_name ?? '—' }}</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payout->payout_type)) }}</td>
                    <td>€{{ number_format($payout->amount, 2) }}</td>
                    <td>{{ ucfirst($payout->payout_method) }}</td>
                    <td><span class="vb-badge vb-badge-warning">{{ ucfirst($payout->payout_status) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="vb-empty">
                            <h3>No pending investor payouts</h3>
                            <p>All investor payouts have been processed.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="vb-card">
        <h2 class="vb-section-title">Requested Affiliate Payouts</h2>
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Reseller</th>
                    <th>Batch Month</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($affiliatePayouts as $payout)
                <tr>
                    <td><strong>{{ $payout->resellerUser->full_name ?? '—' }}</strong></td>
                    <td>{{ $payout->payout_batch_month }}</td>
                    <td>€{{ number_format($payout->amount, 2) }}</td>
                    <td>{{ ucfirst($payout->payout_method) }}</td>
                    <td><span class="vb-badge vb-badge-warning">{{ ucfirst($payout->payout_status) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="vb-empty">
                            <h3>No requested affiliate payouts</h3>
                            <p>No affiliate payouts are awaiting processing.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
