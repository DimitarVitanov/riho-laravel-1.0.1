<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>EST8ADS Sign in</title>
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
            <h1>Connect one move.<br>Unlock several sales.</h1>
            <p>Sign in to manage properties, connect buyers and sellers, analyze complete transaction chains and identify the missing link stopping multiple sales.</p>
        </div>
        <div class="login-chain"><span>SELL PROPERTY</span><i></i><span>AI CHAIN</span><i></i><span>BUY NEXT</span><i></i><span>MISSING LINK</span></div>
    </section>
    <section class="login-form-side">
        <div class="login-card">
            <h2>Sign in to EST8ADS</h2>
            <p>Choose the workspace associated with your EST8ADS account.</p>
            <div class="login-tabs">
                <button class="active" type="button" data-login-role="user">Private user</button>
                <button type="button" data-login-role="agency">Agency</button>
                <button type="button" data-login-role="admin">Administrator</button>
            </div>
            <form id="loginForm" method="POST" action="{{ \App\Support\Est8adsRoute::to('login.store') }}">
                @csrf
                <input id="loginRole" type="hidden" name="role" value="{{ old('role', 'user') }}">
                <div class="login-field">
                    <label for="loginEmail">Email address</label>
                    <input id="loginEmail" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                </div>
                <div class="login-field">
                    <label for="loginPassword">Password</label>
                    <input id="loginPassword" name="password" type="password" autocomplete="current-password" required>
                </div>
                <label style="display:flex;gap:9px;align-items:center;font-size:10px;color:#666">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))> Keep me signed in
                </label>
                <button class="login-submit" type="submit">Sign in</button>
            </form>
            <div class="account-notice" @if ($errors->any()) role="alert" @endif>
                <strong>{{ $errors->any() ? 'Unable to sign in' : 'Account access' }}</strong>
                <p>{{ $errors->any() ? $errors->first() : 'Use the account credentials provided by your EST8ADS administrator.' }}</p>
            </div>
            <a class="back-site" href="{{ \App\Support\Est8adsRoute::to('register') }}">Create an EST8ADS-only account</a>
            <a class="back-site" href="{{ \App\Support\Est8adsRoute::to('home') }}">← Back to public website</a>
        </div>
    </section>
</div>
<script>
(() => {
    const roleInput = document.getElementById('loginRole');
    document.querySelectorAll('[data-login-role]').forEach((button) => {
        button.classList.toggle('active', button.dataset.loginRole === roleInput.value);
        button.addEventListener('click', () => {
            roleInput.value = button.dataset.loginRole;
            document.querySelectorAll('[data-login-role]').forEach((tab) => tab.classList.toggle('active', tab === button));
        });
    });
})();
</script>
</body>
</html>
