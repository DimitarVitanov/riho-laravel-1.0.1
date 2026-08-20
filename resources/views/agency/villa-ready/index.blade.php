@extends('layouts.simple.master')
@section('title', 'Villa Ready Croatia Affiliate')
@section('breadcrumb-title')<h3>Villa Ready Croatia Affiliate</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">Agency Panel</a></li>
    <li class="breadcrumb-item active">Villa Ready Affiliate</li>
@endsection
@section('content')
<style>
.vrc-section-title{font-size:18px;font-weight:800;margin:0 0 4px}
.vrc-help{color:#6c757d;font-size:12px;margin:0}
.vrc-status{display:inline-flex;border-radius:999px;padding:6px 10px;font-size:11px;font-weight:800}
.vrc-visited{background:#f3f4f6;color:#4b5563}
.vrc-viewed{background:#dbeafe;color:#1d4ed8}
.vrc-paid{background:#dcfce7;color:#166534}
.vrc-summary{font-size:24px;font-weight:900}
.vrc-small{font-size:12px;color:#6c757d}
.property-card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:16px}
.property-card img{width:100%;height:140px;object-fit:cover;border-radius:8px;margin-bottom:12px}
.page-body-wrapper a.vrc-page-link{font-size:12px;color:#2563eb !important;text-decoration:underline !important;word-break:break-all}
.page-body-wrapper a.vrc-page-link:hover{color:#1d4ed8 !important;text-decoration:none !important}
</style>

<div class="container-fluid">
    {{-- Summary Stats --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Total Visits</div>
                    <div class="vrc-summary">{{ number_format($stats['total_visits']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Viewed / Discussed</div>
                    <div class="vrc-summary">{{ number_format($stats['viewed']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Confirmed Sales</div>
                    <div class="vrc-summary">{{ number_format($stats['paid_sales']) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="vrc-small">Commission Earned</div>
                    <div class="vrc-summary text-success">€{{ number_format($stats['total_commission'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Published Properties --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="vrc-section-title">Your Published Properties</h5>
            <p class="vrc-help">These Villa Ready Croatia properties are published on your website. Visitors who view and later purchase earn you a 6% commission.</p>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($publications as $pub)
                <div class="col-md-4">
                    <div class="property-card">
                        @if($pub->property->featured_image)
                        <img src="{{ asset('storage/' . $pub->property->featured_image) }}" alt="{{ $pub->property->title }}">
                        @else
                        <div style="width:100%;height:140px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#9ca3af">No Image</div>
                        @endif
                        <h6 class="mb-1">{{ $pub->property->short_title ?? $pub->property->title }}</h6>
                        <p class="text-muted mb-2" style="font-size:13px">{{ $pub->property->location }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-dark">{{ $pub->property->price_display }}</span>
                            <code style="font-size:11px">{{ $pub->affiliate_code }}</code>
                        </div>
                        <div class="mt-2">
                            @php
                                $vrcDomain = $profile->custom_domain
                                    ? rtrim($profile->custom_domain, '/')
                                    : rtrim(parse_url(config('app.url'), PHP_URL_HOST) ?? config('app.url'), '/');
                                $vrcUrl = 'https://' . $vrcDomain . '/' . ltrim($pub->page_slug, '/');
                            @endphp
                            <a href="{{ $vrcUrl }}" target="_blank" rel="noopener noreferrer" class="vrc-page-link">{{ $vrcUrl }}</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted text-center py-4">No properties published to your website yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Referral Tracking --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">Your Referral Tracking</h5>
            <p class="vrc-help">Track visitors who viewed properties through your website. When a sale is confirmed by Villa Ready Croatia, your 6% commission is recorded here.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Visitor</th>
                            <th>First Visit</th>
                            <th>Status</th>
                            <th>Sale Amount</th>
                            <th>Your Commission (6%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referrals as $referral)
                        <tr>
                            <td>
                                <strong>{{ $referral->property->property_id ?? '—' }}</strong>
                                <br><small class="text-muted">{{ Str::limit($referral->property->title ?? '', 30) }}</small>
                            </td>
                            <td>
                                <code>{{ Str::limit($referral->cookie_id, 12) }}</code>
                                @if($referral->visitor_email)
                                <br><small>{{ $referral->visitor_email }}</small>
                                @endif
                            </td>
                            <td>{{ $referral->first_visit_at->format('d M Y') }}</td>
                            <td>
                                @if($referral->status === 'visited')
                                    <span class="vrc-status vrc-visited">VISITED</span>
                                @elseif($referral->status === 'viewed')
                                    <span class="vrc-status vrc-viewed">VIEWED</span>
                                @else
                                    <span class="vrc-status vrc-paid">PAID</span>
                                @endif
                            </td>
                            <td>{{ $referral->formatted_sale_amount }}</td>
                            <td>
                                @if($referral->status === 'paid')
                                <strong class="text-success">{{ $referral->formatted_commission }}</strong>
                                @else
                                <span class="text-muted">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No referrals tracked yet. Share your property pages to start earning commissions!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $referrals->links() }}
        </div>
    </div>

    {{-- How It Works --}}
    <div class="card">
        <div class="card-header">
            <h5 class="vrc-section-title">How It Works</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-center p-3">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px">
                            <i class="fa fa-globe fa-lg"></i>
                        </div>
                        <h6>1. Property Published</h6>
                        <p class="text-muted small">Villa Ready Croatia properties appear on your website with your branding.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px">
                            <i class="fa fa-eye fa-lg"></i>
                        </div>
                        <h6>2. Visitor Views</h6>
                        <p class="text-muted small">A 180-day tracking cookie is set when visitors view the property page.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px">
                            <i class="fa fa-handshake fa-lg"></i>
                        </div>
                        <h6>3. Sale Confirmed</h6>
                        <p class="text-muted small">When the buyer purchases, Villa Ready Croatia confirms the sale.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center p-3">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px">
                            <i class="fa fa-euro-sign fa-lg"></i>
                        </div>
                        <h6>4. Commission Paid</h6>
                        <p class="text-muted small">You earn 6% of the sale price as your referral commission.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
