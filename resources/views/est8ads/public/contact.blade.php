<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Contact EST8ADS about property chains, Missing Link Ads, agency access, privacy rights, billing, partnerships or legal notices.">
  <title>Contact EST8ADS — Support, Agency, Privacy and Legal Requests</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('est8ads-assets/legal.css') }}">
</head>
<body>

<header class="site-header" id="top">
  <div class="nav-shell">
    <a class="brand" href="{{ \App\Support\Est8adsRoute::to('home') }}" aria-label="EST8ADS home"><img src="{{ asset('est8ads-assets/est8ads-logo.svg') }}" alt="EST8ADS logo"></a>
    <nav class="desktop-nav" aria-label="Primary navigation">
      <a href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Create your move</a>
      <a href="{{ \App\Support\Est8adsRoute::to('home') }}#what">What is EST8ADS?</a>
      <a href="{{ \App\Support\Est8adsRoute::to('home') }}#how">How it works</a>
      <a href="{{ \App\Support\Est8adsRoute::to('home') }}#faq">FAQ</a>
      <a href="{{ \App\Support\Est8adsRoute::to('contact') }}">Contact</a>
    </nav>
    <div class="nav-actions">
      <button class="language-button" type="button" aria-label="Choose language">EN</button>
      <a class="sign-in" href="{{ \App\Support\Est8adsRoute::to('login') }}">Sign in</a>
      <a class="primary-small" href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Get started</a>
    </div>
    <button class="menu-button" type="button" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
  </div>
  <div class="mobile-menu" hidden>
    <a href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Create your move</a>
    <a href="{{ \App\Support\Est8adsRoute::to('home') }}#what">What is EST8ADS?</a>
    <a href="{{ \App\Support\Est8adsRoute::to('home') }}#how">How it works</a>
    <a href="{{ \App\Support\Est8adsRoute::to('home') }}#faq">FAQ</a>
    <a href="{{ \App\Support\Est8adsRoute::to('contact') }}">Contact</a>
    <a href="{{ \App\Support\Est8adsRoute::to('login') }}">Sign in</a>
  </div>
</header>

<main class="contact-page">
<section class="legal-hero"><div class="section-shell legal-hero-inner"><span class="section-kicker">CONTACT EST8ADS</span><h1>How can we help?</h1><p>Contact us about a property move, Missing Link, account, agency integration, Villa Bit AI access, privacy request, partnership, billing issue or legal notice.</p></div></section>
<div class="section-shell contact-layout">
  <section class="contact-card">
    <h2>Send a message</h2>
    <p>Choose the most relevant subject so the request can be routed correctly.</p>
    <form id="contactForm" method="POST" action="{{ \App\Support\Est8adsRoute::to('contact.store') }}" novalidate>
      @csrf
      <div class="contact-form-grid">
        <label class="contact-field"><span>Full name *</span><input type="text" name="full_name" autocomplete="name" required></label>
        <label class="contact-field"><span>Email address *</span><input type="email" name="email" autocomplete="email" required></label>
        <label class="contact-field"><span>Phone number</span><input type="tel" name="phone" autocomplete="tel" placeholder="+385 ..."></label>
        <label class="contact-field"><span>Country *</span><input type="text" name="country" autocomplete="country-name" required></label>
        <label class="contact-field"><span>I am *</span><select name="role" required><option value="">Select</option><option>A buyer</option><option>A seller</option><option>Both buying and selling</option><option>A property owner considering a sale</option><option>A real estate agent</option><option>A real estate agency</option><option>A developer</option><option>A professional service provider</option><option>A media representative</option><option>A technology or data partner</option><option>Other</option></select></label>
        <label class="contact-field"><span>Subject *</span><select name="subject" required><option value="">Select</option><option>Start a property chain</option><option>Submit a possible Missing Link</option><option>Agency registration or platform presentation</option><option>Villa Bit AI integration</option><option>Account or technical support</option><option>Billing or refund request</option><option>Privacy or data rights request</option><option>Privacy opt-out request</option><option>Verification question</option><option>Partnership</option><option>Technical integration</option><option>Media enquiry</option><option>Legal notice</option><option>Other</option></select></label>
        <label class="contact-field full"><span>Message *</span><textarea name="message" required placeholder="Explain the property move, question or request. Do not include passwords, full payment-card details or unnecessary identity documents."></textarea></label>
      </div>
      <div class="contact-submit"><button class="button primary" type="submit">Send message <span>→</span></button></div>
      <div class="contact-success{{ session('est8ads_contact_success') ? ' show' : '' }}" id="contactSuccess" role="status">{{ session('est8ads_contact_success', 'Complete the form and your message will be sent securely to EST8ADS.') }}</div>
    </form>
  </section>
</div>
</main>

<footer class="site-footer">
  <div class="section-shell footer-grid">
    <div><p>EST8ADS — Property Chain Intelligence based on Villa Bit AI technology.</p></div>
    <div><h4>Platform</h4><a href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Create your move</a><a href="{{ \App\Support\Est8adsRoute::to('home') }}#what">What is EST8ADS?</a><a href="{{ \App\Support\Est8adsRoute::to('home') }}#how">How it works</a><a href="{{ \App\Support\Est8adsRoute::to('home') }}#faq">FAQ</a></div>
    <div><h4>For users</h4><a href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Private buyers and sellers</a><a href="{{ \App\Support\Est8adsRoute::to('home') }}#create">Real estate agents</a><a href="{{ \App\Support\Est8adsRoute::to('login') }}">Sign in</a></div>
    <div><h4>Legal</h4><a href="{{ \App\Support\Est8adsRoute::to('privacy') }}">Privacy Policy</a><a href="{{ \App\Support\Est8adsRoute::to('terms') }}">Terms of Use</a><a href="{{ \App\Support\Est8adsRoute::to('contact') }}">Contact</a></div>
  </div>
  <div class="section-shell footer-bottom"><span>© 2026 EST8ADS. All rights reserved.</span><span>Powered by Villa Bit AI</span></div>
</footer>
<script src="{{ asset('est8ads-assets/page.js') }}"></script></body></html>
