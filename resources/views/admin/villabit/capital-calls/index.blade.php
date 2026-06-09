@extends('layouts.simple.master')
@section('title', 'Capital Calls')
@section('breadcrumb-title')<h3>Capital Calls</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Capital Calls</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header pb-0"><h5>All Capital Calls</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Project</th><th>Investor</th><th>Amount</th><th>Due</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($capitalCalls as $cc)
                    <tr>
                        <td>{{ $cc->call_number }}</td>
                        <td>{{ $cc->project->project_name ?? '—' }}</td>
                        <td>{{ $cc->investorUser->first_name ?? '' }} {{ $cc->investorUser->last_name ?? '' }}</td>
                        <td>{{ number_format($cc->requested_amount, 2) }}</td>
                        <td>{{ $cc->due_date }}</td>
                        <td><span class="badge bg-{{ $cc->status === 'paid' ? 'success' : ($cc->status === 'overdue' ? 'danger' : 'warning') }}">{{ ucfirst($cc->status) }}</span></td>
                        <td><a href="{{ route('admin.villabit.capital-calls.show', $cc) }}" class="btn btn-outline-primary btn-sm">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">No capital calls.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $capitalCalls->links() }}
        </div>
    </div>
</div>
@endsection
