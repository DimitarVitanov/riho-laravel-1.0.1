@extends('layouts.simple.master')
@section('title', $project->project_name)
@section('breadcrumb-title')<h3>{{ $project->project_name }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('investor.dashboard') }}">Investor</a></li>
    <li class="breadcrumb-item"><a href="{{ route('investor.projects.index') }}">Projects</a></li>
    <li class="breadcrumb-item active">{{ $project->project_code }}</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p><strong>Location:</strong> {{ $project->project_location ?? '—' }}, {{ $project->project_country ?? '' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($project->project_status) }}</span></p>
                    <p><strong>Target Raise:</strong> {{ number_format($project->target_raise_amount, 2) }}</p>
                    <p><strong>Preferred Return:</strong> {{ $project->preferred_return_percent }}% ({{ $project->preferred_return_type }})</p>
                    <p><strong>Rental Profit Share:</strong> {{ $project->rental_profit_share_percent }}%</p>
                    <p><strong>Exit Profit Share:</strong> {{ $project->project_exit_profit_share_percent }}%</p>
                    <p><strong>Duration:</strong> {{ $project->estimated_duration_months ?? '—' }} months</p>
                    <hr>
                    <h5>Summary</h5>
                    <p>{{ $project->summary ?? 'No summary provided.' }}</p>
                    @if($project->full_description)
                    <h5>Description</h5>
                    <p>{{ $project->full_description }}</p>
                    @endif
                    @if($project->risk_notes)
                    <h5>Risk Notes</h5>
                    <p class="text-danger">{{ $project->risk_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
