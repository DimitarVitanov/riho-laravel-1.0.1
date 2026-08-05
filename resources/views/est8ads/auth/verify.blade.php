<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Confirm your email — EST8ADS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('est8ads-assets/panel/panel.css') }}">
</head>
<body>
<div class="login-page">
    <section class="login-visual">
        <a href="{{ \App\Support\Est8adsRoute::to('home') }}"><img class="login-logo" src="{{ asset('est8ads-assets/panel/est8ads-logo.svg') }}" alt="EST8ADS"></a>
        <div class="login-copy">
            <span class="eyebrow">PROPERTY CHAIN INTELLIGENCE</span>
            <h1>Almost there.<br>Confirm your email.</h1>
            <p>We sent a confirmation link to your inbox. Verifying your email is the first step to unlocking your EST8ADS workspace.</p>
        </div>
        <div class="login-chain"><span>SIGN UP</span><i></i><span>VERIFY EMAIL</span><i></i><span>PAYMENT</span><i></i><span>FULL ACCESS</span></div>
    </section>
    <section class="login-form-side">
        <div class="login-card">
            <h2>Verify your email address</h2>
            <p>Check your inbox (and your spam or junk folder) for a message from EST8ADS, then click the confirmation link. You can request a new one below if you did not receive it.</p>

            @if (session('resent'))
                <div class="account-notice" role="alert">
                    <strong>Email sent</strong>
                    <p>A fresh verification link has been sent to your email address.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
                @csrf
                <button class="login-submit" type="submit">Resend verification email</button>
            </form>

            <form method="POST" action="{{ \App\Support\Est8adsRoute::to('logout') }}" style="margin-top:12px">
                @csrf
                <button class="login-submit" type="submit" style="background:transparent;color:#151515;border:1px solid #e5e7eb">Sign out</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
