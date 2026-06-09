@extends('layouts.simple.master')
@section('title', 'Capital Call Details')
@section('breadcrumb-title')<h3>Capital Call #{{ $capitalCall->call_number }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.capital-calls.index') }}">Capital Calls</a></li>
    <li class="breadcrumb-item active">#{{ $capitalCall->call_number }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Details</h5></div>
                <div class="card-body">
                    <p><strong>Project:</strong> {{ $capitalCall->project->project_name ?? '—' }}</p>
                    <p><strong>Investor:</strong> {{ $capitalCall->investorUser->first_name ?? '' }} {{ $capitalCall->investorUser->last_name ?? '' }}</p>
                    <p><strong>Requested:</strong> {{ number_format($capitalCall->requested_amount, 2) }}</p>
                    <p><strong>Due Date:</strong> {{ $capitalCall->due_date }}</p>
                    <p><strong>Reason:</strong> {{ $capitalCall->reason ?? '—' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $capitalCall->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($capitalCall->status) }}</span></p>
                    @if($capitalCall->paid_at)
                    <p><strong>Paid:</strong> {{ number_format($capitalCall->paid_amount, 2) }} on {{ $capitalCall->paid_at }}</p>
                    @endif
                </div>
            </div>
        </div>
        @if($capitalCall->status !== 'paid')
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Mark as Paid</h5></div>
                <div class="card-body">
                    <form action="{{ route('admin.villabit.capital-calls.mark-paid', $capitalCall) }}" method="POST">
                        @csrf
                        <div class="mb-3"><label class="form-label">Paid Amount</label><input type="number" step="0.01" name="paid_amount" class="form-control" value="{{ $capitalCall->requested_amount }}"></div>
                        <div class="mb-3"><label class="form-label">Payment Reference</label><input type="text" name="payment_reference" class="form-control"></div>
                        <button class="btn btn-success">Mark Paid</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
