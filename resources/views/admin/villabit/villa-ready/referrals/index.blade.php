@extends('layouts.simple.master')
@section('title', 'Affiliate Property Tracking')
@section('breadcrumb-title')<h3>Affiliate Property Tracking</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Affiliate Tracking</li>
@endsection
@section('content')
<style>
.vrc-section-title{font-size:18px;font-weight:800;margin:0 0 4px}
.vrc-help{color:#6c757d;font-size:12px;margin:0}
.vrc-card-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap}
.vrc-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.vrc-field label{display:block;font-weight:700;font-size:12px;margin-bottom:7px}
.vrc-field input,.vrc-field select{width:100%;border:1px solid #dee2e6;border-radius:8px;padding:11px 12px;background:#fff}
.vrc-status{display:inline-flex;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:800}
.vrc-visited{background:#f3f4f6;color:#4b5563}
.vrc-viewed{background:#dbeafe;color:#1d4ed8}
.vrc-paid{background:#dcfce7;color:#166534}
.vrc-summary{font-size:24px;font-weight:900}
.vrc-small{font-size:12px;color:#6c757d}
</style>

<div class="container-fluid">
    {{-- Summary Stats --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Tracked Visits</div>
                    <div class="vrc-summary">{{ number_format($stats['total_visits']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Paid Sales</div>
                    <div class="vrc-summary">{{ number_format($stats['paid_sales']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Confirmed Sale Value</div>
                    <div class="vrc-summary">€{{ number_format($stats['total_sale_value'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Agency Commission Owed</div>
                    <div class="vrc-summary">€{{ number_format($stats['total_commission'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tracking Table --}}
    <div class="card">
        <div class="card-header vrc-card-head">
            <div>
                <h5 class="vrc-section-title">Property Affiliate Tracking</h5>
                <p class="vrc-help">Admin can view automatically recorded visits or manually assign VIEWED and PAID status.</p>
            </div>
            <a href="{{ route('admin.villabit.villa-ready.referrals.create') }}" class="btn btn-primary btn-sm">Add Manual Referral</a>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Filters --}}
            <form method="GET" class="mb-4">
                <div class="vrc-grid-3">
                    <div class="vrc-field">
                        <label>Filter by Agency</label>
                        <select name="agency_id" onchange="this.form.submit()">
                            <option value="">All Agencies</option>
                            @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}" @selected(request('agency_id') == $agency->id)>{{ $agency->agency_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vrc-field">
                        <label>Filter by Status</label>
                        <select name="status" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            <option value="visited" @selected(request('status') === 'visited')>VISITED</option>
                            <option value="viewed" @selected(request('status') === 'viewed')>VIEWED</option>
                            <option value="paid" @selected(request('status') === 'paid')>PAID</option>
                        </select>
                    </div>
                    <div class="vrc-field">
                        <label>Search Visitor / Property</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, cookie ID or property">
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Agency</th>
                            <th>Property</th>
                            <th>Visitor / Cookie</th>
                            <th>First Visit</th>
                            <th>Status</th>
                            <th>Sale Amount</th>
                            <th>6% Commission</th>
                            <th>Admin Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                        <tr>
                            <td>
                                <strong>{{ $referral->agencyProfile->agency_name ?? '—' }}</strong>
                                <br><small class="text-muted">{{ $referral->agencyProfile->user->email ?? '' }}</small>
                            </td>
                            <td>
                                {{ $referral->property->property_id ?? '—' }}
                                <br><small class="text-muted">{{ Str::limit($referral->property->title ?? '', 30) }}</small>
                            </td>
                            <td>
                                <code>{{ $referral->cookie_id }}</code>
                                <br><small>{{ $referral->visitor_email ?? 'No enquiry yet' }}</small>
                            </td>
                            <td>{{ $referral->first_visit_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($referral->status === 'visited')
                                    <span class="vrc-status vrc-visited">VISITED</span>
                                @elseif($referral->status === 'viewed')
                                    <span class="vrc-status vrc-viewed">VIEWED</span>
                                @else
                                    <span class="vrc-status vrc-paid">PAID</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.villabit.villa-ready.referrals.set-paid', $referral) }}" method="POST" class="d-inline sale-form">
                                    @csrf
                                    <input type="number" name="sale_amount" step="0.01" class="form-control form-control-sm" style="width:120px" value="{{ $referral->sale_amount }}" placeholder="Enter amount">
                                </form>
                            </td>
                            <td>€{{ number_format($referral->commission_amount ?? 0, 2) }}</td>
                            <td>
                                <div class="btn-group">
                                    <form action="{{ route('admin.villabit.villa-ready.referrals.set-viewed', $referral) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Set VIEWED</button>
                                    </form>
                                    <button type="button" class="btn btn-success btn-sm" onclick="submitPaid(this)">Set PAID</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No referrals tracked yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $referrals->withQueryString()->links() }}
        </div>
    </div>

    {{-- Status Logic Card --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Required Status Logic</h5>
        </div>
        <div class="card-body">
            <div class="vrc-grid-3">
                <div class="border rounded p-3">
                    <span class="vrc-status vrc-visited">VISITED</span>
                    <p class="mb-0 mt-2">Cookie/referral detected and the visitor reached a tracked Villa Ready Croatia page.</p>
                </div>
                <div class="border rounded p-3">
                    <span class="vrc-status vrc-viewed">VIEWED</span>
                    <p class="mb-0 mt-2">Admin can confirm manually that the referred buyer viewed or discussed the property.</p>
                </div>
                <div class="border rounded p-3">
                    <span class="vrc-status vrc-paid">PAID</span>
                    <p class="mb-0 mt-2">Admin enters the confirmed sale amount and the system records a one-time 6% agency commission.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function submitPaid(btn) {
    const row = btn.closest('tr');
    const form = row.querySelector('.sale-form');
    const input = form.querySelector('input[name="sale_amount"]');
    
    if (!input.value || parseFloat(input.value) <= 0) {
        alert('Enter the confirmed sale amount before setting PAID.');
        input.focus();
        return;
    }
    
    form.submit();
}
</script>
@endsection
