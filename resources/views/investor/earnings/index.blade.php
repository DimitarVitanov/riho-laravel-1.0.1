@extends('layouts.simple.master')
@section('title', 'Interest Earnings')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Interest / Preferred Return Earnings</h1>
            <p>Your accrued preferred return, rental profit participation, and project exit participation across all investments.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-grid">
        <div class="vb-card">
            <div class="vb-label">Preferred Return Accrued</div>
            <div class="vb-metric">€{{ number_format($totalPreferredReturn, 0) }}</div>
            <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
        </div>
        <div class="vb-card">
            <div class="vb-label">Rental Profit Share</div>
            <div class="vb-metric">€{{ number_format($totalRentalProfit, 0) }}</div>
            <div class="vb-period">50% participation if applicable</div>
        </div>
        <div class="vb-card">
            <div class="vb-label">Exit Profit Share</div>
            <div class="vb-metric">€{{ number_format($totalExitProfit, 0) }}</div>
            <div class="vb-period">25% participation if applicable</div>
        </div>
        <div class="vb-card">
            <div class="vb-label">Total Earnings</div>
            <div class="vb-metric">€{{ number_format($totalEarnings, 0) }}</div>
            <div class="vb-period">Financial status date: {{ now()->format('F Y') }} · Updated monthly</div>
        </div>
    </div>

    <div class="vb-card">
        <h2 class="vb-section-title">Earnings by Project</h2>
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Preferred Return</th>
                    <th>Accrued</th>
                    <th>Rental Share Estimate</th>
                    <th>Exit Profit Estimate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            @forelse($investments as $inv)
            <tr>
                <td><strong>{{ $inv->project->project_name ?? '—' }}</strong></td>
                <td>{{ $inv->project->preferred_return_percent ?? 10 }}% cumulative</td>
                <td>€{{ number_format($inv->preferred_return_accrued_amount, 0) }}</td>
                <td>{{ $inv->project->rental_profit_share_percent ?? 50 }}% participation if applicable</td>
                <td>{{ $inv->project->project_exit_profit_share_percent ?? 25 }}% participation if applicable</td>
                <td><strong>€{{ number_format($inv->total_earnings_accrued, 0) }}</strong></td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="vb-empty">
                        <h3>No earnings yet</h3>
                        <p>Earnings will appear once your capital is invested and the preferred return begins accruing.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        @if($investments instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div style="margin-top: 20px;">{{ $investments->links() }}</div>
        @endif
    </div>
</div>
@endsection
