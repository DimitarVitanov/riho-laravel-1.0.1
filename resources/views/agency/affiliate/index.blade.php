@extends('layouts.simple.master')
@section('title', __('messages.affiliate'))
@section('breadcrumb-title')<h3>{{ __('messages.affiliate') }}</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.affiliate') }}</li>
@endsection

@section('content')
<div class="container-fluid affiliate-page">

    {{-- Hero Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); color: white;">
                <div class="card-body py-5 px-4 text-center">
                    <h2 class="fw-bold mb-2">{{ __('messages.affiliate_hero_title') }}</h2>
                    <p class="mb-1 opacity-75 fs-5">{{ __('messages.affiliate_hero_subtitle') }}</p>
                    <p class="mb-4 opacity-50 small">{{ __('messages.affiliate_hero_terms') }}</p>
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="text-center">
                            <div class="fw-bold fs-3">10%</div>
                            <div class="small opacity-75">{{ __('messages.lifetime_commission') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold fs-3">180</div>
                            <div class="small opacity-75">{{ __('messages.day_cookie') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold fs-3">$10</div>
                            <div class="small opacity-75">{{ __('messages.min_payout') }}</div>
                        </div>
                        <div class="text-center">
                            <div class="fw-bold fs-3">1st</div>
                            <div class="small opacity-75">{{ __('messages.monthly_payout') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-2 fw-bold text-dark">{{ $totalClicks }}</div>
                    <div class="text-muted small">{{ __('messages.total_clicks') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-2 fw-bold text-primary">{{ $totalSignups }}</div>
                    <div class="text-muted small">{{ __('messages.total_signups') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-2 fw-bold text-warning">${{ number_format($pendingCommissions, 2) }}</div>
                    <div class="text-muted small">{{ __('messages.pending_commissions') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-2 fw-bold text-success">${{ number_format($paidCommissions, 2) }}</div>
                    <div class="text-muted small">{{ __('messages.paid_commissions') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- LEFT: Your Referral Link + How It Works --}}
        <div class="col-lg-5 mb-4">

            {{-- Referral Link --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-link me-2"></i>{{ __('messages.your_referral_link') }}</h6>
                </div>
                <div class="card-body">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="referralLink" readonly
                            value="{{ url('/ref/' . ($user->referral_code ?? 'N/A')) }}">
                        <button class="btn btn-dark" onclick="copyReferralLink()">
                            <i class="fa fa-copy me-1"></i>{{ __('messages.copy') }}
                        </button>
                    </div>
                    <small class="text-muted">{{ __('messages.referral_link_help') }}</small>
                </div>
            </div>

            {{-- How It Works --}}
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-info-circle me-2"></i>{{ __('messages.how_it_works') }}</h6>
                </div>
                <div class="card-body">
                    @foreach([
                        ['step' => '1', 'title' => __('messages.aff_step1_title'), 'desc' => __('messages.aff_step1_desc')],
                        ['step' => '2', 'title' => __('messages.aff_step2_title'), 'desc' => __('messages.aff_step2_desc')],
                        ['step' => '3', 'title' => __('messages.aff_step3_title'), 'desc' => __('messages.aff_step3_desc')],
                        ['step' => '4', 'title' => __('messages.aff_step4_title'), 'desc' => __('messages.aff_step4_desc')],
                    ] as $item)
                    <div class="d-flex gap-3 mb-3">
                        <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                            style="width:32px;height:32px;font-size:13px;">{{ $item['step'] }}</div>
                        <div>
                            <div class="fw-bold small">{{ $item['title'] }}</div>
                            <div class="text-muted" style="font-size:12px;">{{ $item['desc'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- RIGHT: Recent Referrals + Why Join --}}
        <div class="col-lg-7">

            {{-- Recent Referrals --}}
            <div class="card mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-users me-2"></i>{{ __('messages.recent_referrals') }}</h6>
                </div>
                <div class="card-body p-0">
                    @if($referrals->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="fa fa-user-plus fa-2x mb-2 d-block opacity-50"></i>
                        <p class="mb-0">{{ __('messages.no_referrals_yet') }}</p>
                        <small>{{ __('messages.no_referrals_help') }}</small>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.converted') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($referrals as $r)
                            <tr>
                                <td>{{ $r->created_at->format('d M Y') }}</td>
                                <td><span class="badge bg-{{ $r->status === 'active_client' ? 'success' : 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span></td>
                                <td>{{ $r->converted_at ? \Carbon\Carbon::parse($r->converted_at)->format('d M Y') : '—' }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $referrals->links() }}</div>
                    @endif
                </div>
            </div>

            {{-- Why Join --}}
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-star me-2"></i>{{ __('messages.why_join') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach([
                            ['num' => '1', 'color' => 'success',  'title' => __('messages.aff_why1_title'), 'desc' => __('messages.aff_why1_desc')],
                            ['num' => '2', 'color' => 'primary',  'title' => __('messages.aff_why2_title'), 'desc' => __('messages.aff_why2_desc')],
                            ['num' => '3', 'color' => 'warning',  'title' => __('messages.aff_why3_title'), 'desc' => __('messages.aff_why3_desc')],
                            ['num' => '4', 'color' => 'info',     'title' => __('messages.aff_why4_title'), 'desc' => __('messages.aff_why4_desc')],
                            ['num' => '5', 'color' => 'dark',     'title' => __('messages.aff_why5_title'), 'desc' => __('messages.aff_why5_desc')],
                        ] as $w)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex gap-2 align-items-start">
                                <span class="badge bg-{{ $w['color'] }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:26px;height:26px;min-width:26px;font-size:11px;">{{ $w['num'] }}</span>
                                <div>
                                    <div class="fw-bold small">{{ $w['title'] }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $w['desc'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <hr class="my-3">

                    <p class="small fw-bold mb-2">{{ __('messages.who_is_it_for') }}</p>
                    <div class="row">
                        @foreach([
                            ['title' => __('messages.aff_who1'), 'desc' => __('messages.aff_who1_desc')],
                            ['title' => __('messages.aff_who2'), 'desc' => __('messages.aff_who2_desc')],
                            ['title' => __('messages.aff_who3'), 'desc' => __('messages.aff_who3_desc')],
                            ['title' => __('messages.aff_who4'), 'desc' => __('messages.aff_who4_desc')],
                        ] as $who)
                        <div class="col-md-6 mb-2">
                            <div class="fw-bold small">{{ $who['title'] }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $who['desc'] }}</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="alert alert-dark mt-3 mb-0 py-2 small">
                        <i class="fa fa-lightbulb-o me-1"></i>{{ __('messages.aff_pro_tip') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
function copyReferralLink() {
    const input = document.getElementById('referralLink');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = input.nextElementSibling;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check me-1"></i>Copied!';
        btn.classList.replace('btn-dark', 'btn-success');
        setTimeout(() => { btn.innerHTML = original; btn.classList.replace('btn-success', 'btn-dark'); }, 2000);
    });
}
</script>
@endpush
@endsection
