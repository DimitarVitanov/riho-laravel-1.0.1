@extends('layouts.simple.master')
@section('title', 'My Investments')
@section('breadcrumb-title')<h3>My Investments</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Investments</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Project</th><th>Committed</th><th>Funded</th><th>Pref Return</th><th>Total Earnings</th><th>Balance</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($investments as $inv)
                    <tr>
                        <td>{{ $inv->project->project_name ?? '—' }}</td>
                        <td>{{ number_format($inv->committed_amount, 2) }}</td>
                        <td>{{ number_format($inv->funded_amount, 2) }}</td>
                        <td>{{ number_format($inv->preferred_return_accrued_amount, 2) }}</td>
                        <td>{{ number_format($inv->total_earnings_accrued, 2) }}</td>
                        <td>{{ number_format($inv->current_balance, 2) }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($inv->investment_status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No investments yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $investments->links() }}
        </div>
    </div>
</div>
@endsection
