@extends('layouts.simple.master')
@section('title', 'Project Details')
@section('breadcrumb-title')<h3>{{ $investmentProject->project_name }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.investment-projects.index') }}">Projects</a></li>
    <li class="breadcrumb-item active">{{ $investmentProject->project_code }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Project Info</h5></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $investmentProject->project_code }}</p>
                    <p><strong>Location:</strong> {{ $investmentProject->project_location ?? '—' }}, {{ $investmentProject->project_country ?? '' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($investmentProject->project_status) }}</span></p>
                    <p><strong>Target Raise:</strong> {{ number_format($investmentProject->target_raise_amount, 2) }}</p>
                    <p><strong>Preferred Return:</strong> {{ $investmentProject->preferred_return_percent }}%</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0"><h5>Capital Calls ({{ $investmentProject->capitalCalls->count() }})</h5></div>
                <div class="card-body">
                    @forelse($investmentProject->capitalCalls as $cc)
                    <div class="mb-2 p-2 border rounded">
                        <strong>Call #{{ $cc->call_number }}</strong> — {{ number_format($cc->requested_amount, 2) }}
                        <span class="badge bg-{{ $cc->status === 'paid' ? 'success' : 'warning' }} ms-2">{{ ucfirst($cc->status) }}</span>
                    </div>
                    @empty
                    <p class="text-muted">No capital calls.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
