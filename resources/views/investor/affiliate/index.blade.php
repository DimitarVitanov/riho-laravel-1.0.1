@extends('layouts.simple.master')
@section('title', __('messages.affiliate'))

@section('main_content')
<div class="container-fluid affiliate-page">

    {{-- Hero Header --}}
    <div class="vb-page-header">
        <div>
            <h1>{{ __('messages.affiliate_hero_title') }}</h1>
            <p>{{ __('messages.affiliate_hero_subtitle') }}</p>
        </div>
        <span class="vb-badge vb-badge-success" style="font-size:13px;padding:8px 14px;">Reseller Active</span>
    </div>

    @include('components.villabit.usage-banner')

    {{-- Hero Card --}}
    <div class="vb-card" style="margin-bottom:24px;background:linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);color:#fff;text-align:center;">
        <p style="margin-bottom:4px;opacity:.75;font-size:15px;">{{ __('messages.affiliate_hero_subtitle') }}</p>
        <p style="margin-bottom:20px;opacity:.5;font-size:12px;">{{ __('messages.affiliate_hero_terms') }}</p>
        <div style="display:flex;justify-content:center;gap:40px;flex-wrap:wrap;">
            <div><div style="font-size:26px;font-weight:700;">10%</div><div style="font-size:12px;opacity:.75;">{{ __('messages.lifetime_commission') }}</div></div>
            <div><div style="font-size:26px;font-weight:700;">180</div><div style="font-size:12px;opacity:.75;">{{ __('messages.day_cookie') }}</div></div>
            <div><div style="font-size:26px;font-weight:700;">$10</div><div style="font-size:12px;opacity:.75;">{{ __('messages.min_payout') }}</div></div>
            <div><div style="font-size:26px;font-weight:700;">1st</div><div style="font-size:12px;opacity:.75;">{{ __('messages.monthly_payout') }}</div></div>
        </div>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
        <div class="vb-card" style="text-align:center;">
            <div style="font-size:28px;font-weight:700;color:#111827;">{{ $totalClicks }}</div>
            <div class="vb-label">{{ __('messages.total_clicks') }}</div>
        </div>
        <div class="vb-card" style="text-align:center;">
            <div style="font-size:28px;font-weight:700;color:#2563eb;">{{ $totalSignups }}</div>
            <div class="vb-label">{{ __('messages.total_signups') }}</div>
        </div>
        <div class="vb-card" style="text-align:center;">
            <div style="font-size:28px;font-weight:700;color:#d97706;">${{ number_format($pendingCommissions, 2) }}</div>
            <div class="vb-label">{{ __('messages.pending_commissions') }}</div>
        </div>
        <div class="vb-card" style="text-align:center;">
            <div style="font-size:28px;font-weight:700;color:#16a34a;">${{ number_format($paidCommissions, 2) }}</div>
            <div class="vb-label">{{ __('messages.paid_commissions') }}</div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:24px;">

        {{-- Left: Referral Link + How It Works --}}
        <div>
            <div class="vb-card" style="margin-bottom:20px;">
                <h2 class="vb-section-title">{{ __('messages.your_referral_link') }}</h2>
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input type="text" id="referralLink" class="vb-input" readonly
                        value="{{ url('/ref/' . ($user->referral_code ?? 'N/A')) }}"
                        style="flex:1;">
                    <button class="vb-btn vb-btn-primary" onclick="copyReferralLink()" style="white-space:nowrap;">
                        {{ __('messages.copy') }}
                    </button>
                </div>
                <div style="font-size:12px;color:#6b7280;">{{ __('messages.referral_link_help') }}</div>
            </div>

            <div class="vb-card">
                <h2 class="vb-section-title">{{ __('messages.how_it_works') }}</h2>
                @foreach([
                    ['1', __('messages.aff_step1_title'), __('messages.aff_step1_desc')],
                    ['2', __('messages.aff_step2_title'), __('messages.aff_step2_desc')],
                    ['3', __('messages.aff_step3_title'), __('messages.aff_step3_desc')],
                    ['4', __('messages.aff_step4_title'), __('messages.aff_step4_desc')],
                ] as [$n, $title, $desc])
                <div style="display:flex;gap:12px;margin-bottom:14px;">
                    <div style="width:28px;height:28px;min-width:28px;border-radius:50%;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">{{ $n }}</div>
                    <div>
                        <div style="font-weight:700;font-size:13px;">{{ $title }}</div>
                        <div style="font-size:12px;color:#6b7280;">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Recent Referrals + Why Join --}}
        <div>
            <div class="vb-card" style="margin-bottom:20px;">
                <h2 class="vb-section-title">{{ __('messages.recent_referrals') }}</h2>
                @if($referrals->isEmpty())
                <div style="text-align:center;padding:24px;color:#6b7280;">
                    <div style="font-size:32px;margin-bottom:8px;">👥</div>
                    <div>{{ __('messages.no_referrals_yet') }}</div>
                    <div style="font-size:12px;">{{ __('messages.no_referrals_help') }}</div>
                </div>
                @else
                <table style="width:100%;border-collapse:collapse;font-size:13px;">
                    <thead>
                        <tr style="border-bottom:1px solid #e5e7eb;">
                            <th style="padding:8px 4px;text-align:left;color:#6b7280;font-weight:600;">{{ __('messages.date') }}</th>
                            <th style="padding:8px 4px;text-align:left;color:#6b7280;font-weight:600;">{{ __('messages.status') }}</th>
                            <th style="padding:8px 4px;text-align:left;color:#6b7280;font-weight:600;">{{ __('messages.converted') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($referrals as $r)
                    <tr style="border-bottom:1px solid #f3f4f6;">
                        <td style="padding:8px 4px;">{{ $r->created_at->format('d M Y') }}</td>
                        <td style="padding:8px 4px;">
                            <span class="vb-badge {{ $r->status === 'converted' ? 'vb-badge-success' : 'vb-badge-warning' }}">
                                {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                            </span>
                        </td>
                        <td style="padding:8px 4px;">{{ $r->converted_at ? \Carbon\Carbon::parse($r->converted_at)->format('d M Y') : '—' }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="padding:12px 0;">{{ $referrals->links() }}</div>
                @endif
            </div>

            <div class="vb-card">
                <h2 class="vb-section-title">{{ __('messages.why_join') }}</h2>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                    @foreach([
                        ['#16a34a', __('messages.aff_why1_title'), __('messages.aff_why1_desc')],
                        ['#2563eb', __('messages.aff_why2_title'), __('messages.aff_why2_desc')],
                        ['#d97706', __('messages.aff_why3_title'), __('messages.aff_why3_desc')],
                        ['#7c3aed', __('messages.aff_why4_title'), __('messages.aff_why4_desc')],
                        ['#374151', __('messages.aff_why5_title'), __('messages.aff_why5_desc')],
                    ] as [$color, $title, $desc])
                    <div style="display:flex;gap:8px;align-items:flex-start;">
                        <div style="width:10px;height:10px;min-width:10px;border-radius:50%;background:{{ $color }};margin-top:4px;"></div>
                        <div>
                            <div style="font-weight:700;font-size:13px;">{{ $title }}</div>
                            <div style="font-size:11px;color:#6b7280;">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="vb-notice" style="font-size:12px;">{{ __('messages.aff_pro_tip') }}</div>
            </div>
        </div>
    </div>

</div>

<script>
function copyReferralLink() {
    const input = document.getElementById('referralLink');
    navigator.clipboard.writeText(input.value).then(() => {
        const btn = input.nextElementSibling;
        const original = btn.textContent;
        btn.textContent = 'Copied!';
        btn.style.background = '#16a34a';
        setTimeout(() => { btn.textContent = original; btn.style.background = ''; }, 2000);
    });
}
</script>
@endsection
