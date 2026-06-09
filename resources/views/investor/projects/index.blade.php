@extends('layouts.simple.master')
@section('title', 'Investment Projects')
@section('breadcrumb-title')<h3>Investment Projects</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item active">Projects</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        @forelse($projects as $p)
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header pb-0"><h5>{{ $p->project_name }}</h5></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $p->project_code }}</p>
                    <p><strong>Location:</strong> {{ $p->project_location ?? '—' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($p->project_status) }}</span></p>
                    <p><strong>Min Investment:</strong> {{ number_format($p->minimum_investment_amount, 2) }}</p>
                    <p><strong>Preferred Return:</strong> {{ $p->preferred_return_percent }}%</p>
                    <a href="{{ route('investor.projects.show', $p) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12"><div class="alert alert-info">No projects available at this time.</div></div>
        @endforelse
    </div>
    {{ $projects->links() }}
</div>
@endsection
