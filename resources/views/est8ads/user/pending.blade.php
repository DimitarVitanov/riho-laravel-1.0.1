<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Waiting for payment — EST8ADS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('est8ads-assets/panel/panel.css') }}">
<style>
/* The waiting-for-payment card sits on a light background, so the badge and
   progress chain (both styled for dark panels by default) are re-skinned here. */
.pending-screen .subscription-badge{background:#fef3c7;color:#92400e;backdrop-filter:none}
.pending-screen .login-chain{justify-content:center;gap:8px}
.pending-screen .login-chain span{border-color:var(--line);color:var(--muted);background:#fff;font-weight:700}
.pending-screen .login-chain span.done{border-color:#16a34a;color:#16a34a}
.pending-screen .login-chain span.active{border-color:var(--navy);background:var(--navy);color:#fff}
.pending-screen .login-chain i{background:var(--line)}
</style>
    @include('est8ads.partials.favicon')
</head>
<body data-panel-role="user">
<div class="pending-screen" style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:var(--bg,#0e1420)">
<div style="max-width:480px;width:100%">
<div style="text-align:center;margin-bottom:18px">
<img src="{{ asset('est8ads-assets/panel/est8ads-logo.svg') }}" alt="EST8ADS" style="height:32px"></div>
<div class="card">
<div class="card-body" style="text-align:center;padding:32px 28px">
<div class="subscription-badge" style="display:inline-flex;margin-bottom:14px">⏳ WAITING FOR PAYMENT</div>
<h2 style="margin:0 0 10px">Almost there — activate your workspace</h2>
<p style="color:var(--muted);margin:0 0 20px">
Your email is confirmed. To unlock your EST8ADS workspace and start property-chain analysis,
please complete your first payment. We sent the payment details to your inbox as well.
</p>
<div class="login-chain" style="margin:0 0 22px">
<span class="done">SIGN UP</span><i></i><span class="done">VERIFY EMAIL</span><i></i><span class="active">PAYMENT</span><i></i><span>FULL ACCESS</span>
</div>
<div class="subscription-price" style="justify-content:center;margin-bottom:20px">
<strong>{{ $subscription['currency'] ?? 'USD' }} {{ number_format($subscription['amount'] ?? 12, 2) }}</strong>
<span>/ {{ $subscription['billing_period'] ?? 30 }} days</span></div>
<a href="{{ \App\Support\Est8adsRoute::to('payment.paypal') }}" class="btn primary" style="width:100%;justify-content:center">Pay now via PayPal</a>
<p style="font-size:11px;color:var(--muted);margin-top:14px">
Payments are reconciled manually. Your workspace unlocks automatically as soon as our team confirms your PayPal transaction
@if(!empty($subscription['invoice_number'])) for invoice {{ $subscription['invoice_number'] }}@endif.
</p>
<form method="POST" action="{{ \App\Support\Est8adsRoute::to('logout') }}" style="margin-top:18px">
@csrf
<button type="submit" class="btn" style="width:100%;justify-content:center">Sign out</button>
</form>
</div></div></div></div>
</body>
</html>
