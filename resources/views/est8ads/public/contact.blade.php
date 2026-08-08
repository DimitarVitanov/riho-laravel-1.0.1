<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{{ __('Contact EST8ADS about property chains, Missing Link Ads, agency access, privacy rights, billing, partnerships or legal notices.') }}">
  <title>{{ __('Contact EST8ADS — Support, Agency, Privacy and Legal Requests') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/legal.css') }}">
    @include('est8ads.partials.favicon')
</head>
<body>

@include('est8ads.public.partials.nav')

<main class="contact-page">
<section class="legal-hero"><div class="section-shell legal-hero-inner"><span class="section-kicker">{{ __('CONTACT EST8ADS') }}</span><h1>{{ __('How can we help?') }}</h1><p>{{ __('Contact us about a property move, Missing Link, account, agency integration, Villa Bit AI access, privacy request, partnership, billing issue or legal notice.') }}</p></div></section>
<div class="section-shell contact-layout">
  <section class="contact-card">
    <h2>{{ __('Send a message') }}</h2>
    <p>{{ __('Choose the most relevant subject so the request can be routed correctly.') }}</p>
    <form id="contactForm" method="POST" action="{{ \App\Support\Est8adsRoute::to('contact.store') }}" novalidate>
      @csrf
      <div class="contact-form-grid">
        <label class="contact-field"><span>{{ __('Full name *') }}</span><input type="text" name="full_name" autocomplete="name" required></label>
        <label class="contact-field"><span>{{ __('Email address *') }}</span><input type="email" name="email" autocomplete="email" required></label>
        <label class="contact-field"><span>{{ __('Phone number') }}</span><input type="tel" name="phone" autocomplete="tel" placeholder="+385 ..."></label>
        <label class="contact-field"><span>{{ __('Country *') }}</span><input type="text" name="country" autocomplete="country-name" required></label>
        <label class="contact-field"><span>{{ __('I am *') }}</span><select name="role" required><option value="">{{ __('Select') }}</option><option value="A buyer">{{ __('A buyer') }}</option><option value="A seller">{{ __('A seller') }}</option><option value="Both buying and selling">{{ __('Both buying and selling') }}</option><option value="A property owner considering a sale">{{ __('A property owner considering a sale') }}</option><option value="A real estate agent">{{ __('A real estate agent') }}</option><option value="A real estate agency">{{ __('A real estate agency') }}</option><option value="A developer">{{ __('A developer') }}</option><option value="A professional service provider">{{ __('A professional service provider') }}</option><option value="A media representative">{{ __('A media representative') }}</option><option value="A technology or data partner">{{ __('A technology or data partner') }}</option><option value="Other">{{ __('Other') }}</option></select></label>
        <label class="contact-field"><span>{{ __('Subject *') }}</span><select name="subject" required><option value="">{{ __('Select') }}</option><option value="Start a property chain">{{ __('Start a property chain') }}</option><option value="Submit a possible Missing Link">{{ __('Submit a possible Missing Link') }}</option><option value="Agency registration or platform presentation">{{ __('Agency registration or platform presentation') }}</option><option value="Villa Bit AI integration">{{ __('Villa Bit AI integration') }}</option><option value="Account or technical support">{{ __('Account or technical support') }}</option><option value="Billing or refund request">{{ __('Billing or refund request') }}</option><option value="Privacy or data rights request">{{ __('Privacy or data rights request') }}</option><option value="Privacy opt-out request">{{ __('Privacy opt-out request') }}</option><option value="Verification question">{{ __('Verification question') }}</option><option value="Partnership">{{ __('Partnership') }}</option><option value="Technical integration">{{ __('Technical integration') }}</option><option value="Media enquiry">{{ __('Media enquiry') }}</option><option value="Legal notice">{{ __('Legal notice') }}</option><option value="Other">{{ __('Other') }}</option></select></label>
        <label class="contact-field full"><span>{{ __('Message *') }}</span><textarea name="message" required placeholder="{{ __('Explain the property move, question or request. Do not include passwords, full payment-card details or unnecessary identity documents.') }}"></textarea></label>
      </div>
      <div class="contact-submit"><button class="button primary" type="submit">{{ __('Send message') }} <span>→</span></button></div>
      <div class="contact-success{{ session('est8ads_contact_success') ? ' show' : '' }}" id="contactSuccess" role="status">{{ session('est8ads_contact_success') ? __('Thank you. Your message has been sent to EST8ADS.') : __('Complete the form and your message will be sent securely to EST8ADS.') }}</div>
    </form>
  </section>
</div>
</main>

@include('est8ads.public.partials.footer')
<script src="{{ asset('est8ads-assets/page.js') }}"></script></body></html>
