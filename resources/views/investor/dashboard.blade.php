@extends('layouts.simple.master')

@section('title', 'Investor Dashboard')

@section('main_content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="vb-page-header">
            <div>
                <h1>Investor Panel</h1>
                <p>Investor panel shows all investment amounts, committed capital, capital calls, accrued interest, profit participation, payouts, documents, and reports.</p>
            </div>
            @if($profile && $profile->kyc_status === 'approved')
                <span class="vb-badge vb-badge-success" style="font-size:13px;padding:8px 14px;">KYC Verified</span>
            @else
                <span class="vb-badge vb-badge-warning" style="font-size:13px;padding:8px 14px;">KYC Pending</span>
            @endif
        </div>

        <!-- Usage Period Status Banner -->
        @include('components.villabit.usage-banner')

        <!-- Financial Metric Cards -->
        <div class="vb-grid">
            <div class="vb-card">
                <div class="vb-label">Total Committed</div>
                <div class="vb-metric">€{{ number_format($totalCommitted, 0) }}</div>
                <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Total Invested</div>
                <div class="vb-metric">€{{ number_format($totalFunded, 0) }}</div>
                <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Preferred Return Accrued</div>
                <div class="vb-metric">€{{ number_format($totalEarnings, 0) }}</div>
                <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
            </div>
            <div class="vb-card">
                <div class="vb-label">Payouts Received</div>
                <div class="vb-metric">€{{ number_format($totalPaid, 0) }}</div>
                <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
            </div>
        </div>

        <!-- Investments Table -->
        @if($investments->count())
        <div class="vb-card" style="margin-bottom: 28px;">
            <h2 class="vb-section-title">My Investments</h2>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Committed</th>
                        <th>Funded</th>
                        <th>Earnings</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investments as $inv)
                    <tr>
                        <td><strong>{{ $inv->project->project_name ?? 'N/A' }}</strong></td>
                        <td>€{{ number_format($inv->committed_amount, 0) }}</td>
                        <td>€{{ number_format($inv->funded_amount, 0) }}</td>
                        <td>€{{ number_format($inv->total_earnings_accrued, 0) }}</td>
                        <td>
                            @php
                                $iClass = match($inv->investment_status) {
                                    'active' => 'vb-badge-success',
                                    'pending' => 'vb-badge-warning',
                                    default => 'vb-badge-muted'
                                };
                            @endphp
                            <span class="vb-badge {{ $iClass }}">{{ ucfirst($inv->investment_status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Pending Capital Calls -->
        @if($pendingCalls->count())
        <div class="vb-card">
            <h2 class="vb-section-title">Pending Capital Calls</h2>
            <div class="vb-notice" style="margin-bottom:18px;">You have {{ $pendingCalls->count() }} capital call(s) awaiting payment.</div>
            <table class="vb-table">
                <thead>
                    <tr>
                        <th>Call #</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingCalls as $call)
                    <tr>
                        <td><strong>{{ $call->call_number }}</strong></td>
                        <td>€{{ number_format($call->requested_amount, 0) }}</td>
                        <td>{{ $call->due_date->format('M j, Y') }}</td>
                        <td>{{ $call->reason ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
