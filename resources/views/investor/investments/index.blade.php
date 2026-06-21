@extends('layouts.simple.master')
@section('title', 'My Investments')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>My Investment Positions</h1>
            <p>Your investment commitments, called capital, funded amounts, and current status across all projects.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Structure</th>
                    <th>Max Commitment</th>
                    <th>Capital Called</th>
                    <th>Invested</th>
                    <th>Pref. Return</th>
                    <th>Total Earnings</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($investments as $inv)
            <tr>
                <td><strong>{{ $inv->project->project_name ?? '—' }}</strong></td>
                <td>{{ $inv->project->legal_structure ?? '—' }}</td>
                <td>€{{ number_format($inv->committed_amount, 0) }}</td>
                <td>€{{ number_format($inv->funded_amount, 0) }}</td>
                <td>€{{ number_format($inv->funded_amount, 0) }}</td>
                <td>€{{ number_format($inv->preferred_return_accrued_amount, 0) }}</td>
                <td>€{{ number_format($inv->total_earnings_accrued, 0) }}</td>
                <td>
                    @php
                        $cls = match($inv->investment_status) {
                            'active' => 'vb-badge-success',
                            'pending' => 'vb-badge-warning',
                            default => 'vb-badge-muted'
                        };
                    @endphp
                    <span class="vb-badge {{ $cls }}">{{ ucfirst($inv->investment_status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="vb-empty">
                        <h3>No investments yet</h3>
                        <p>Your investment positions will appear here once your capital has been committed.</p>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">{{ $investments->links() }}</div>
    </div>
</div>
@endsection
