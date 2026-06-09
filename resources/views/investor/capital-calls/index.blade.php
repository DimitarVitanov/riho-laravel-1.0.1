@extends('layouts.simple.master')
@section('title', 'Capital Calls')
@section('breadcrumb-title')<h3>Capital Calls</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Capital Calls</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Call #</th><th>Project</th><th>Amount</th><th>Due Date</th><th>Phase</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($capitalCalls as $cc)
                    <tr>
                        <td>{{ $cc->call_number }}</td>
                        <td>{{ $cc->project->project_name ?? '—' }}</td>
                        <td>{{ number_format($cc->requested_amount, 2) }}</td>
                        <td>{{ $cc->due_date }}</td>
                        <td>{{ $cc->construction_phase ?? '—' }}</td>
                        <td><span class="badge bg-{{ $cc->status === 'paid' ? 'success' : ($cc->status === 'overdue' ? 'danger' : 'warning') }}">{{ ucfirst($cc->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted">No capital calls.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $capitalCalls->links() }}
        </div>
    </div>
</div>
@endsection
