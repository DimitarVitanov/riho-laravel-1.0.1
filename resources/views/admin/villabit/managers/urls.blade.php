@extends('layouts.simple.master')
@section('title', 'Manager URLs')
@section('breadcrumb-title')<h3>Manager URLs</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.managers.index') }}">Managers</a></li>
    <li class="breadcrumb-item active">URLs</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Manager: {{ $manager->first_name }} {{ $manager->last_name }}</h5>
                    <p class="text-muted mb-0">Add agency URLs that this manager is responsible for. When an agency signs up with one of these URLs, they will be attributed to this manager.</p>
                </div>
                <div class="card-body">
                    {{-- Add URL Form --}}
                    <form action="{{ route('admin.villabit.managers.urls.store', $manager) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">URLs</label>
                            <textarea name="urls" class="form-control" rows="8" placeholder="Enter agency URLs, one per line. Example:&#10;villareadycroatia.com&#10;luxuryvillas-split.com&#10;realestate-dubrovnik.hr">{{ old('urls') }}</textarea>
                            <small class="text-muted">Enter one URL per line. Domain only, no http:// prefix needed.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Existing URLs --}}
                    @if($urls->count() > 0)
                    <hr>
                    <h6 class="mb-3">Assigned URLs ({{ $urls->count() }})</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>Status</th>
                                    <th>Matched Agency</th>
                                    <th>Added</th>
                                    <th>Commission %</th>
                                    <th>Commission Amount</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($urls as $url)
                                <tr>
                                    <td><code>{{ $url->url }}</code></td>
                                    <td>
                                        @if($url->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($url->status === 'on_hold')
                                            <span class="badge bg-warning">On Hold</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($url->status ?? 'pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($url->agencyProfile)
                                            <a href="{{ route('admin.villabit.agencies.show', $url->agencyProfile->user) }}">
                                                <strong>{{ $url->agencyProfile->agency_name }}</strong>
                                            </a>
                                            <br><small class="text-muted">Matched {{ $url->updated_at->diffForHumans() }}</small>
                                        @else
                                            <span class="text-muted">Waiting for signup...</span>
                                        @endif
                                    </td>
                                    <td>{{ $url->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <span class="fw-bold">{{ number_format($url->commission_percent ?? 10, 0) }}%</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.villabit.managers.urls.update', [$manager, $url]) }}" method="POST" class="d-flex align-items-center gap-1">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="{{ $url->status }}">
                                            <div class="input-group input-group-sm" style="width:120px;">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.01" name="commission_amount" value="{{ $url->commission_amount }}" class="form-control form-control-sm" placeholder="0.00">
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.villabit.managers.urls.update', [$manager, $url]) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="{{ $url->status }}">
                                            <input type="hidden" name="commission_amount" value="{{ $url->commission_amount }}">
                                            <select name="commission_status" class="form-select form-select-sm" style="min-width:100px;" onchange="this.form.submit()">
                                                <option value="pending" @selected($url->commission_status === 'pending' || !$url->commission_status)>Pending</option>
                                                <option value="paid" @selected($url->commission_status === 'paid')>Paid</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <form action="{{ route('admin.villabit.managers.urls.update', [$manager, $url]) }}" method="POST">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="commission_amount" value="{{ $url->commission_amount }}">
                                                <input type="hidden" name="commission_status" value="{{ $url->commission_status }}">
                                                <select name="status" class="form-select form-select-sm" style="min-width:100px;" onchange="this.form.submit()">
                                                    <option value="active" @selected($url->status === 'active')>Active</option>
                                                    <option value="on_hold" @selected($url->status === 'on_hold' || !$url->status)>On Hold</option>
                                                </select>
                                            </form>
                                            <form action="{{ route('admin.villabit.managers.urls.destroy', [$manager, $url]) }}" method="POST" onsubmit="return confirm('Remove this URL?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center text-muted py-4">
                        <p>No URLs assigned to this manager yet.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
