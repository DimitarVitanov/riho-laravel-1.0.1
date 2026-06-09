@extends('layouts.simple.master')
@section('title', 'AI Daily Reports')
@section('breadcrumb-title')<h3>AI Daily Reports</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency</a></li>
    <li class="breadcrumb-item active">AI Reports</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            @if($reports instanceof \Illuminate\Pagination\LengthAwarePaginator && $reports->count())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Date</th><th>Feature</th><th>Summary</th><th>Status</th><th>Uniqueness</th></tr></thead>
                    <tbody>
                    @foreach($reports as $r)
                    <tr>
                        <td>{{ $r->report_date->format('M d, Y') }}</td>
                        <td>{{ str_replace('_', ' ', ucfirst($r->feature_key)) }}</td>
                        <td class="text-truncate" style="max-width:300px">{{ $r->ai_actions_summary ?? '—' }}</td>
                        <td><span class="badge bg-{{ $r->status === 'completed' ? 'success' : 'warning' }}">{{ ucfirst($r->status) }}</span></td>
                        <td><span class="badge bg-secondary">{{ strtoupper($r->content_uniqueness_status ?? 'N/A') }}</span></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
            @else
            <p class="text-muted text-center">No AI reports yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
