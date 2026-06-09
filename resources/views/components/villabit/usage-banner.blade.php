@php
    $now = now();
    $periodStart = $now->copy()->startOfMonth();
    $periodEnd = $now->copy()->endOfMonth();
    $nextReset = $now->copy()->addMonth()->startOfMonth();
    $status = 'Active';
@endphp
<div class="vb-usage-status-title">Visible Usage Period Status</div>
<div class="vb-usage-status-banner">
    <div class="vb-item">
        <span>Usage month</span>
        <b>{{ $now->format('F Y') }}</b>
    </div>
    <div class="vb-item">
        <span>Current period</span>
        <b>{{ $periodStart->format('M j') }}–{{ $periodEnd->format('j, Y') }}</b>
    </div>
    <div class="vb-item">
        <span>Account / usage status</span>
        <b>{{ $status }}</b>
    </div>
    <div class="vb-item">
        <span>Next automatic reset</span>
        <b>{{ $nextReset->format('M j, Y') }}</b>
    </div>
</div>
