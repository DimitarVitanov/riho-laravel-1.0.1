<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Account suspended — EST8ADS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('est8ads-assets/panel/panel.css') }}">
</head>
<body data-panel-role="user">
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:var(--bg,#0e1420)">
<div style="max-width:460px;width:100%">
<div style="text-align:center;margin-bottom:18px">
<img src="{{ asset('est8ads-assets/panel/est8ads-logo.svg') }}" alt="EST8ADS" style="height:32px"></div>
<div class="card">
<div class="card-body" style="text-align:center;padding:32px 28px">
<div class="subscription-badge" style="display:inline-flex;margin-bottom:14px">⛔ ACCOUNT SUSPENDED</div>
<h2 style="margin:0 0 10px">Your EST8ADS account is suspended</h2>
<p style="color:var(--muted);margin:0 0 20px">
Your {{ $subscription['name'] ?? 'EST8ADS' }} subscription is unpaid and it has been more than
{{ $subscription['grace_period_days'] ?? 10 }} days since invoice {{ $subscription['invoice_number'] ?? '' }}
was due on {{ $subscription['due_on'] ?? 'the due date' }}. Access to your workspace is paused until payment is confirmed.
</p>
<div class="subscription-price" style="justify-content:center;margin-bottom:20px">
<strong>${{ number_format($subscription['amount'] ?? 12, 2) }}</strong>
<span>/ {{ $subscription['billing_period'] ?? 30 }} days</span></div>
<a href="{{ route('est8ads.payment.paypal') }}" class="btn primary" style="width:100%;justify-content:center">Pay now via PayPal</a>
<p style="font-size:11px;color:var(--muted);margin-top:14px">
Payments are reconciled manually. Your account is reactivated as soon as our team confirms the PayPal transaction.
</p>
<form method="POST" action="{{ route('est8ads.logout') }}" style="margin-top:18px">
@csrf
<button type="submit" class="btn" style="width:100%;justify-content:center">Sign out</button>
</form>
</div></div></div></div>
</body>
</html>
