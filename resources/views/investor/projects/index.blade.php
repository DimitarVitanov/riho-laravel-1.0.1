@extends('layouts.simple.master')
@section('title', 'Project Reports')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Project Reports</h1>
            <p>Monthly project updates, capital usage, construction progress, expected next capital call, and payout status for your investment projects.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    @forelse($projects as $p)
    <div class="vb-card" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h2 class="vb-section-title" style="margin-bottom: 4px;">{{ $p->project_name }}</h2>
                <div class="vb-period">{{ $p->project_code }} · {{ $p->project_location ?? '' }}{{ $p->project_country ? ', ' . $p->project_country : '' }}</div>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                @php
                    $pCls = match($p->project_status) {
                        'active' => 'vb-badge-success',
                        'completed' => 'vb-badge-info',
                        'on_hold' => 'vb-badge-warning',
                        default => 'vb-badge-muted'
                    };
                @endphp
                <span class="vb-badge {{ $pCls }}">{{ ucfirst(str_replace('_', ' ', $p->project_status)) }}</span>
                <a href="{{ route('investor.projects.show', $p) }}" class="vb-btn vb-btn-sm">View Details</a>
            </div>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 16px;">
            <div>
                <div class="vb-label">Target Raise</div>
                <div style="font-size: 18px; font-weight: 800;">€{{ number_format($p->target_raise_amount, 0) }}</div>
            </div>
            <div>
                <div class="vb-label">Preferred Return</div>
                <div style="font-size: 18px; font-weight: 800;">{{ $p->preferred_return_percent }}%</div>
            </div>
            <div>
                <div class="vb-label">Rental Profit Share</div>
                <div style="font-size: 18px; font-weight: 800;">{{ $p->rental_profit_share_percent ?? '—' }}%</div>
            </div>
            <div>
                <div class="vb-label">Exit Profit Share</div>
                <div style="font-size: 18px; font-weight: 800;">{{ $p->project_exit_profit_share_percent ?? '—' }}%</div>
            </div>
        </div>
        @if($p->summary)
        <div class="vb-notice">{{ $p->summary }}</div>
        @endif
    </div>
    @empty
    <div class="vb-card">
        <div class="vb-empty">
            <h3>No project reports available</h3>
            <p>Project reports will appear here once you have active investments.</p>
        </div>
    </div>
    @endforelse

    <div style="margin-top: 20px;">{{ $projects->links() }}</div>
</div>
@endsection
