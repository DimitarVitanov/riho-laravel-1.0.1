@extends('layouts.simple.master')
@section('title', 'My URLs & Commissions')
@section('breadcrumb-title')<h3>My URLs & Commissions</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Manager</a></li>
    <li class="breadcrumb-item active">URLs & Commissions</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Summary Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="text-white-50">Total URLs</h6>
                            <h3 class="mb-0">{{ $urls->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="text-white-50">Active Agencies</h6>
                            <h3 class="mb-0">{{ $urls->where('status', 'active')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <h6 class="text-dark opacity-75">Pending Commission</h6>
                            <h3 class="mb-0">${{ number_format($urls->where('commission_status', 'pending')->sum('commission_amount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- URLs Table --}}
            <div class="card">
                <div class="card-header pb-0">
                    <h5>My Assigned URLs</h5>
                    <p class="text-muted mb-0">These are the agency URLs assigned to you. You earn a 10% commission on each active agency.</p>
                </div>
                <div class="card-body">
                    @if($urls->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Agency</th>
                                    <th>Added</th>
                                    <th>Commission %</th>
                                    <th>Commission Amount</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urls as $url)
                                <tr>
                                    <td><code>{{ $url->url }}</code></td>
                                    <td>
                                        @if($url->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">On Hold</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($url->agencyProfile)
                                            <strong>{{ $url->agencyProfile->agency_name }}</strong>
                                            <br><small class="text-muted">{{ $url->agencyProfile->user->email ?? '' }}</small>
                                        @else
                                            <span class="text-muted">Waiting for signup...</span>
                                        @endif
                                    </td>
                                    <td>{{ $url->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($url->commission_percent, 0) }}%</span>
                                    </td>
                                    <td>
                                        @if($url->commission_amount)
                                            <span class="fw-bold">${{ number_format($url->commission_amount, 2) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($url->commission_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                            @if($url->commission_paid_at)
                                                <br><small class="text-muted">{{ $url->commission_paid_at->format('M j, Y') }}</small>
                                            @endif
                                        @elseif($url->commission_amount)
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-5">
                        <i data-feather="link" style="width:48px;height:48px;opacity:0.3;"></i>
                        <p class="mt-3">No URLs assigned to you yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
