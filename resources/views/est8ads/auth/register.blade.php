<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create an EST8ADS account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('est8ads/panel/panel.css') }}">
</head>
<body>
<div class="login-page">
    <section class="login-visual">
        <img class="login-logo" src="{{ asset('est8ads/panel/est8ads-logo.svg') }}" alt="EST8ADS">
        <div class="login-copy"><span class="eyebrow">EST8ADS ACCOUNT</span><h1>One account.<br>Your complete property move.</h1><p>Create an EST8ADS-only private or agency account. This account does not grant access to the Villa Bit AI application.</p></div>
    </section>
    <section class="login-form-side">
        <div class="login-card">
            <h2>Create your EST8ADS account</h2>
            <p>Villa Bit AI agencies do not need another account—use the same Villa Bit credentials on the EST8ADS sign-in page.</p>
            <form method="POST" action="{{ route(request()->routeIs('est8ads.dev.*') ? 'est8ads.dev.register.store' : 'est8ads.register.store') }}">
                @csrf
                <div class="login-field"><label>Account type</label><select name="account_type" required><option value="individual">Private user</option><option value="agency">Real estate agency</option></select></div>
                <div class="login-field"><label>First name</label><input name="first_name" value="{{ old('first_name') }}" required></div>
                <div class="login-field"><label>Last name</label><input name="last_name" value="{{ old('last_name') }}" required></div>
                <div class="login-field"><label>Agency name</label><input name="company_name" value="{{ old('company_name') }}"></div>
                <div class="login-field"><label>Email address</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                <div class="login-field"><label>Phone</label><input name="phone" value="{{ old('phone') }}"></div>
                <div class="login-field"><label>Country</label><input name="country" value="{{ old('country') }}" required></div>
                <div class="login-field"><label>Password</label><input type="password" name="password" required></div>
                <div class="login-field"><label>Confirm password</label><input type="password" name="password_confirmation" required></div>
                <label style="display:flex;gap:9px;align-items:flex-start;font-size:11px;color:#666"><input type="checkbox" name="terms" value="1" required> I agree to the EST8ADS Terms of Use and Privacy Policy.</label>
                <button class="login-submit" type="submit">Create account</button>
            </form>
            @if($errors->any())<div class="account-notice" role="alert"><strong>Check your details</strong><p>{{ $errors->first() }}</p></div>@endif
            <a class="back-site" href="{{ route(request()->routeIs('est8ads.dev.*') ? 'est8ads.dev.login' : 'est8ads.login') }}">Already registered? Sign in</a>
        </div>
    </section>
</div>
</body>
</html>
