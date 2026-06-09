@extends('layouts.simple.master')
@section('title', 'Earnings')
@section('breadcrumb-title')<h3>Earnings</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Earnings</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Preferred Return</h6><h3>{{ number_format($totalPreferredReturn, 2) }}</h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Rental Profit</h6><h3>{{ number_format($totalRentalProfit, 2) }}</h3></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body text-center"><h6>Exit Profit</h6><h3>{{ number_format($totalExitProfit, 2) }}</h3></div></div></div>
        <div class="col-md-3"><div class="card bg-success text-white"><div class="card-body text-center"><h6>Total Earnings</h6><h3>{{ number_format($totalEarnings, 2) }}</h3></div></div></div>
    </div>
    <div class="card">
        <div class="card-header pb-0"><h5>Earnings by Project</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Project</th><th>Pref Return</th><th>Rental</th><th>Exit</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($investments as $inv)
                    <tr>
                        <td>{{ $inv->project->project_name ?? '—' }}</td>
                        <td>{{ number_format($inv->preferred_return_accrued_amount, 2) }}</td>
                        <td>{{ number_format($inv->rental_profit_accrued_amount, 2) }}</td>
                        <td>{{ number_format($inv->project_exit_profit_accrued_amount, 2) }}</td>
                        <td><strong>{{ number_format($inv->total_earnings_accrued, 2) }}</strong></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
