@extends('layouts.simple.master')

@section('title', 'Feature Toggles')

@section('breadcrumb-title')
    <h3>Feature ON/OFF Toggles</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">Feature Toggles</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>AI Feature ON/OFF Controls</h5>
                        <p class="text-muted">Turn individual AI features ON or OFF. Changes take effect immediately.</p>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if($features->count())
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Feature</th>
                                            <th>Status</th>
                                            <th>Frequency</th>
                                            <th>Last Run</th>
                                            <th>Toggle</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($features as $feature)
                                            <tr>
                                                <td>
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $feature->feature_key)) }}</strong>
                                                    @if($feature->ai_model_name)
                                                        <br><small class="text-muted">Model: {{ $feature->ai_model_name }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($feature->is_enabled)
                                                        <span class="badge bg-success">ON</span>
                                                    @else
                                                        <span class="badge bg-secondary">OFF</span>
                                                    @endif
                                                </td>
                                                <td>{{ ucfirst($feature->frequency) }}</td>
                                                <td>{{ $feature->last_run_at ? $feature->last_run_at->diffForHumans() : 'Never' }}</td>
                                                <td>
                                                    <form action="{{ route('agency.settings.features.toggle') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                                                        <input type="hidden" name="is_enabled" value="{{ $feature->is_enabled ? '0' : '1' }}">
                                                        <button type="submit" class="btn btn-sm btn-{{ $feature->is_enabled ? 'warning' : 'success' }}">
                                                            {{ $feature->is_enabled ? 'Turn OFF' : 'Turn ON' }}
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i data-feather="settings" style="width:48px;height:48px;opacity:0.3;"></i>
                                <p class="mt-3">No AI features have been configured for your agency yet.<br>Your account manager will set these up.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
