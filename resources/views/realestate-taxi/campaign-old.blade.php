<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ $campaign->ai_generated_content['meta_description'] ?? $campaign->positioning_note ?? '' }}">
<title>{{ $campaign->name }} | {{ $profile->agency_name }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
@php
  $aiContent = $campaign->ai_generated_content ?? [];
  $heroText = $aiContent['hero_content'] ?? null;
  $areaDescriptions = $aiContent['area_descriptions'] ?? [];
  $faqContent = $aiContent['faq_content'] ?? [];
  $aboutText = $aiContent['about_content'] ?? null;
  $listings = $campaign->listings()->where('status', 'active')->latest()->get();
  $targetPlaces = $campaign->target_places ?? [];
@endphp
:root {
  --black: #000000;
  --white: #ffffff;
  --gray-50: #fafafa;
  --gray-100: #f5f5f5;
  --gray-200: #e5e5e5;
  --gray-300: #d4d4d4;
  --gray-400: #a3a3a3;
  --gray-500: #737373;
  --gray-600: #525252;
  --gray-700: #404040;
  --gray-800: #262626;
  --gray-900: #171717;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background: var(--white);
  color: var(--gray-900);
  line-height: 1.6;
  font-size: 16px;
  -webkit-font-smoothing: antialiased;
}
a { color: inherit; text-decoration: none; }
img { max-width: 100%; height: auto; display: block; }

/* Container */
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
@media (max-width: 768px) { .container { padding: 0 16px; } }

/* Header */
header {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--gray-200);
}
.header-inner {
  display: flex; align-items: center; justify-content: space-between;
  height: 72px;
}
.logo { font-weight: 700; font-size: 20px; letter-spacing: -0.02em; }
.logo span { font-weight: 400; color: var(--gray-500); }
.header-cta {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--black); color: var(--white);
  padding: 12px 24px; border-radius: 100px;
  font-weight: 600; font-size: 14px;
  transition: all 0.2s;
}
.header-cta:hover { background: var(--gray-800); transform: translateY(-1px); }

/* Hero */
.hero {
  padding: 140px 0 80px;
  background: linear-gradient(180deg, var(--gray-50) 0%, var(--white) 100%);
}
.hero-badge {
  display: inline-block;
  background: var(--black); color: var(--white);
  padding: 6px 16px; border-radius: 100px;
  font-size: 12px; font-weight: 600; letter-spacing: 0.05em;
  text-transform: uppercase; margin-bottom: 24px;
}
.hero h1 {
  font-size: clamp(40px, 6vw, 72px);
  font-weight: 800; letter-spacing: -0.03em;
  line-height: 1.1; margin-bottom: 24px;
  max-width: 800px;
}
.hero-text {
  font-size: 20px; line-height: 1.7;
  color: var(--gray-600); max-width: 600px;
}

/* Stats Bar */
.stats-bar {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 1px; background: var(--gray-200);
  border: 1px solid var(--gray-200); border-radius: 16px;
  overflow: hidden; margin: 60px 0;
}
.stat {
  background: var(--white); padding: 32px;
  text-align: center;
}
.stat-value { font-size: 36px; font-weight: 800; letter-spacing: -0.02em; }
.stat-label { font-size: 14px; color: var(--gray-500); margin-top: 4px; }
@media (max-width: 768px) {
  .stats-bar { grid-template-columns: repeat(2, 1fr); }
  .stat { padding: 24px 16px; }
  .stat-value { font-size: 28px; }
}

/* Section */
.section { padding: 80px 0; }
.section-dark { background: var(--gray-900); color: var(--white); }
.section-light { background: var(--gray-50); }
.section-title {
  font-size: 14px; font-weight: 600; letter-spacing: 0.1em;
  text-transform: uppercase; color: var(--gray-400);
  margin-bottom: 16px;
}
.section-heading {
  font-size: clamp(32px, 4vw, 48px);
  font-weight: 700; letter-spacing: -0.02em;
  margin-bottom: 24px;
}
.section-text { font-size: 18px; color: var(--gray-600); max-width: 600px; }
.section-dark .section-text { color: var(--gray-400); }

/* Areas Grid */
.areas-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 24px; margin-top: 48px;
}
.area-card {
  background: var(--white); border: 1px solid var(--gray-200);
  border-radius: 16px; padding: 32px;
  transition: all 0.3s;
}
.area-card:hover { border-color: var(--black); transform: translateY(-4px); }
.area-name { font-size: 20px; font-weight: 700; margin-bottom: 8px; }
.area-type { font-size: 13px; color: var(--gray-500); margin-bottom: 16px; }
.area-desc { font-size: 15px; color: var(--gray-600); line-height: 1.6; }
@media (max-width: 900px) { .areas-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .areas-grid { grid-template-columns: 1fr; } }

/* Listings */
.listings-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 24px; margin-top: 48px;
}
.listing-card {
  background: var(--white); border-radius: 16px;
  overflow: hidden; border: 1px solid var(--gray-200);
  transition: all 0.3s;
}
.listing-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
.listing-image {
  aspect-ratio: 4/3; background: var(--gray-100);
  position: relative; overflow: hidden;
}
.listing-image img { width: 100%; height: 100%; object-fit: cover; }
.listing-content { padding: 24px; }
.listing-price { font-size: 24px; font-weight: 800; margin-bottom: 8px; }
.listing-title { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
.listing-location { font-size: 14px; color: var(--gray-500); }
@media (max-width: 900px) { .listings-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 600px) { .listings-grid { grid-template-columns: 1fr; } }

/* FAQ */
.faq-list { margin-top: 48px; max-width: 800px; }
.faq-item {
  border-bottom: 1px solid var(--gray-200);
  padding: 24px 0;
}
.faq-question {
  font-size: 18px; font-weight: 600;
  cursor: pointer; display: flex;
  justify-content: space-between; align-items: center;
}
.faq-question::after { content: '+'; font-size: 24px; font-weight: 300; }
.faq-item[open] .faq-question::after { content: '−'; }
.faq-answer {
  padding-top: 16px; font-size: 16px;
  color: var(--gray-600); line-height: 1.7;
}

/* CTA Section */
.cta-section {
  text-align: center; padding: 100px 0;
}
.cta-heading {
  font-size: clamp(36px, 5vw, 56px);
  font-weight: 800; letter-spacing: -0.02em;
  margin-bottom: 24px;
}
.cta-text { font-size: 18px; color: var(--gray-400); margin-bottom: 40px; }
.cta-buttons { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
.btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 16px 32px; border-radius: 100px;
  font-weight: 600; font-size: 16px;
  transition: all 0.2s; border: none; cursor: pointer;
}
.btn-primary { background: var(--white); color: var(--black); }
.btn-primary:hover { background: var(--gray-100); transform: translateY(-2px); }
.btn-outline {
  background: transparent; color: var(--white);
  border: 2px solid var(--gray-600);
}
.btn-outline:hover { border-color: var(--white); }

/* Footer */
footer {
  background: var(--black); color: var(--white);
  padding: 48px 0; text-align: center;
}
.footer-text { color: var(--gray-500); font-size: 14px; }
</style>
</head>
<body>

<!-- Header -->
<header>
  <div class="container header-inner">
    <a href="#" class="logo">{{ $profile->agency_name }} <span>Real Estate</span></a>
    @if($profile->contact_email)
    <a href="mailto:{{ $profile->contact_email }}" class="header-cta">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h8c.6 0 1 .4 1 1v6c0 .6-.4 1-1 1H4c-.6 0-1-.4-1-1V5c0-.6.4-1 1-1z"/><path d="m3 5 5 3 5-3"/></svg>
      Contact Us
    </a>
    @endif
  </div>
</header>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <div class="hero-badge">{{ $campaign->country ?? 'Real Estate' }}</div>
    <h1>{{ $campaign->name }}</h1>
    <p class="hero-text">{{ $heroText ?? ($campaign->positioning_note ?? 'Discover exceptional real estate opportunities in ' . $campaign->primary_city . '. Expert local guidance for buyers, sellers, and investors.') }}</p>
  </div>
</section>

<!-- Stats -->
<div class="container">
  <div class="stats-bar">
    <div class="stat">
      <div class="stat-value">{{ count($targetPlaces) }}</div>
      <div class="stat-label">Areas Covered</div>
    </div>
    <div class="stat">
      <div class="stat-value">{{ $listings->count() }}</div>
      <div class="stat-label">Active Listings</div>
    </div>
    <div class="stat">
      <div class="stat-value">{{ $campaign->coverage_area ?? '—' }}</div>
      <div class="stat-label">{{ $campaign->coverage_unit ?? 'km' }} Coverage</div>
    </div>
    <div class="stat">
      <div class="stat-value">24/7</div>
      <div class="stat-label">Support</div>
    </div>
  </div>
</div>
.brand span { display: block; font-size: 11.5px; color: var(--muted); font-weight: 700; }
.topbar .cta {
  background: var(--accent); color: #fff; border-radius: 9px;
  padding: 10px 16px; font-weight: 800; font-size: 13.5px;
}
.topbar .cta:hover { background: var(--accent-dark); }

/* ── HERO ── */
.hero {
  padding: 30px 32px; border-radius: 16px; background: var(--ink); color: #fff;
  box-shadow: 0 14px 38px rgba(0,0,0,.13); position: relative; overflow: hidden;
}
.hero::after { content: ""; position: absolute; right: -60px; top: -60px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,.05), transparent 70%); pointer-events: none; }
.eyebrow { display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,.6); font-weight: 800; font-size: 12px; letter-spacing: .11em; text-transform: uppercase; }
.eyebrow::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: #fff; }
.hero h1 { margin: 12px 0 12px; font-size: clamp(30px,4vw,52px); line-height: 1.05; letter-spacing: -.8px; font-weight: 900; }
.hero h1::after { content: ""; display: block; width: 64px; height: 4px; border-radius: 3px; background: #fff; margin-top: 16px; }
.hero p { margin: 0; color: #d7dde0; max-width: 900px; font-size: 17px; position: relative; z-index: 1; }

/* ── CONTEXT META BAR ── */
.context {
  margin-top: 18px; padding: 13px 15px; background: #fff; border: 1px solid var(--line);
  border-radius: 11px; display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: 9px;
}
.context .meta { padding: 9px 11px; background: var(--soft); border-radius: 8px; min-width: 0; }
.meta b { display: block; font-size: 11px; letter-spacing: .05em; color: var(--muted); text-transform: uppercase; margin-bottom: 3px; }
.meta span { font-weight: 800; font-size: 14px; overflow-wrap: anywhere; }

/* ── STEP CARDS ── */
.steps { margin-top: 24px; display: grid; gap: 20px; }
.step {
  background: var(--white); border: 1px solid var(--line); border-radius: 16px; padding: 24px;
  box-shadow: 0 7px 18px rgba(22,28,35,.04);
}
.step-head { display: flex; justify-content: space-between; gap: 16px; align-items: flex-start; border-bottom: 1px solid var(--line); padding-bottom: 16px; margin-bottom: 20px; }
.step-number { display: inline-grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: var(--ink); color: #fff; font-weight: 900; font-size: 14px; margin-right: 11px; flex-shrink: 0; box-shadow: 0 6px 14px rgba(10,11,12,.18); }
.step h2 { margin: 0; font-size: 22px; line-height: 1.2; display: flex; align-items: center; }
.step .lead { margin: 9px 0 0; color: var(--muted); font-size: 14.5px; max-width: 820px; }
.output { padding: 10px 13px; background: var(--ink); color: #fff; border-radius: 8px; font-size: 12.5px; font-weight: 800; white-space: nowrap; }

.copy-stack p { color: #3e4348; font-size: 15.5px; line-height: 1.7; }
.copy-stack p + p { margin-top: 12px; }

/* ── ASK AI ── */
.query-box {
  display: grid; grid-template-columns: minmax(0,1fr) auto; gap: 10px;
  padding: 8px; border: 1.5px solid #cfd4d9; border-radius: 11px; background: #fbfcfc;
}
.query-box input { min-width: 0; height: 44px; padding: 0 14px; border: 0; outline: 0; background: transparent; font-size: 15px; }
.query-box input::placeholder { color: #9aa2ab; }
.submit-arrow { width: 46px; height: 46px; display: grid; place-items: center; border: 0; border-radius: 9px; background: var(--accent); color: #fff; font-size: 20px; font-weight: 900; cursor: pointer; }
.submit-arrow:hover { background: var(--accent-dark); }

/* ── TABLE ── */
.table-wrap { overflow: auto; border: 1px solid var(--line); border-radius: 10px; margin-top: 4px; }
table { width: 100%; border-collapse: collapse; font-size: 13.5px; min-width: 720px; }
th { text-align: left; color: #58616a; font-size: 10px; letter-spacing: .05em; text-transform: uppercase; background: #f7f8f9; }
th, td { padding: 12px; border-bottom: 1px solid #edf0f2; vertical-align: top; }
tr:last-child td { border-bottom: 0; }
.badge { display: inline-block; border-radius: 999px; padding: 3px 9px; font-size: 10px; font-weight: 800; letter-spacing: .03em; }
.badge.high { background: var(--accent-soft); color: var(--accent-dark); }
.badge.medium { background: var(--warn-soft); color: var(--warn); }
.badge.low { background: var(--low); color: #5c656d; }

/* ── INLINE MINI CARDS ── */
.inline-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-top: 18px; }
.mini { border: 1px solid var(--line); border-radius: 10px; padding: 14px; background: #fbfcfc; }
.mini strong { display: block; font-size: 14px; margin-bottom: 5px; }
.mini p { margin: 0; font-size: 12.5px; color: var(--muted); }
.mini .tiny { display: block; font-size: 11px; color: var(--accent-dark); font-weight: 800; margin-top: 10px; }

/* ── LISTINGS ── */
.listing-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; }
.listing-card { border: 1px solid var(--line); border-radius: 12px; overflow: hidden; background: #fff; display: flex; flex-direction: column; transition: box-shadow .18s ease, transform .18s ease; }
.listing-card:hover { transform: translateY(-3px); box-shadow: 0 14px 28px rgba(22,28,35,.10); }
.listing-img { width: 100%; aspect-ratio: 3/2; object-fit: cover; display: block; background: #eef2f5; }
.listing-img.empty { display: grid; place-items: center; gap: 8px; background: linear-gradient(135deg, #16181c, #0a0b0c); }
.listing-img.empty svg { width: 32px; height: 32px; fill: none; stroke: rgba(255,255,255,.55); stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
.listing-img.empty span { color: rgba(255,255,255,.55); font-size: 11px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
.listing-body { padding: 18px; flex: 1; display: flex; flex-direction: column; }
.listing-body h3 { font-size: 16px; font-weight: 800; margin: 0 0 8px; line-height: 1.25; }
.listing-meta { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: 12.5px; color: var(--muted); font-weight: 700; flex-wrap: wrap; }
.listing-body .desc { font-size: 13.5px; line-height: 1.6; color: #3e4348; margin: 0 0 14px; flex: 1; }
.listing-price { font-size: 17px; font-weight: 900; margin-bottom: 12px; }
.listing-btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 16px; border-radius: 8px; background: var(--accent); color: #fff; font-size: 13px; font-weight: 800; }
.listing-btn:hover { background: var(--accent-dark); }

/* ── TWO-COLUMN GRID ── */
.grid { display: grid; grid-template-columns: repeat(12,1fr); gap: 20px; }
.col-8 { grid-column: span 8; }
.col-6 { grid-column: span 6; }
.col-4 { grid-column: span 4; }

/* ── CTA BAND ── */
.cta-band { background: var(--ink); border-color: var(--ink); position: relative; overflow: hidden; }
.cta-band::after { content: ""; position: absolute; right: -50px; bottom: -70px; width: 220px; height: 220px; border-radius: 50%; background: radial-gradient(circle, rgba(255,255,255,.06), transparent 70%); pointer-events: none; }
.cta-band-inner { display: flex; align-items: center; justify-content: space-between; gap: 24px; flex-wrap: wrap; position: relative; z-index: 1; }
.cta-title { color: #fff; margin: 10px 0 8px; font-size: clamp(22px,2.5vw,32px); line-height: 1.15; letter-spacing: -.4px; font-weight: 900; }
.cta-sub { color: #d7dde0; margin: 0; font-size: 15px; max-width: 620px; }
.cta-actions { display: flex; gap: 12px; flex-wrap: wrap; }
.cta-btn { display: inline-flex; align-items: center; justify-content: center; padding: 13px 22px; border-radius: 10px; font-weight: 800; font-size: 14px; white-space: nowrap; }
.cta-btn.gold { background: #fff; color: #0a0a0a; }
.cta-btn.gold:hover { background: #e9edf1; }
.cta-btn.ghost { background: rgba(255,255,255,.08); color: #fff; border: 1px solid rgba(255,255,255,.2); }
.cta-btn.ghost:hover { background: rgba(255,255,255,.16); }

/* ── FOOTER NOTE ── */
.footer-note { margin-top: 26px; padding: 18px 20px; border-left: 4px solid var(--ink); background: #f5f6f7; font-size: 14px; border-radius: 0 10px 10px 0; }
.footer-note b { color: var(--ink); }

footer { margin-top: 30px; padding: 22px 0; text-align: center; color: var(--muted); font-size: 13px; border-top: 1px solid var(--line); }

@media (max-width: 980px) {
  .context { grid-template-columns: 1fr 1fr; }
  .inline-cards { grid-template-columns: 1fr 1fr; }
  .listing-grid { grid-template-columns: 1fr 1fr; }
  .col-8, .col-6, .col-4 { grid-column: span 12; }
}
@media (max-width: 560px) {
  .page { padding: 14px 12px 40px; }
  .hero, .step { padding: 18px; border-radius: 12px; }
  .context, .inline-cards, .listing-grid { grid-template-columns: 1fr; }
  .step-head { flex-direction: column; }
  .output { white-space: normal; }
}
</style>
</head>
<body>
<main class="page" id="top">

  {{-- TOP BAR --}}
  <div class="topbar">
    <a class="brand" href="/">
      @if($profile->agency_logo_path)
        <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" height="42">
      @else
        <span class="brand-icon">{{ strtoupper(substr($profile->agency_name, 0, 1)) }}</span>
      @endif
      <span>
        <b>{{ $profile->agency_name }}</b>
        <span>{{ $profile->city ?? $campaign->primary_city }} Real Estate</span>
      </span>
    </a>
    @if($profile->contact_email)
      <a href="mailto:{{ $profile->contact_email }}" class="cta">Contact Agency</a>
    @endif
  </div>

  {{-- AI Content --}}
  @php
    $aiContent = $campaign->ai_generated_content ?? [];
    $heroText = $aiContent['hero_content'] ?? null;
    $areaDescriptions = $aiContent['area_descriptions'] ?? [];
    $faqContent = $aiContent['faq_content'] ?? [];
    $aboutText = $aiContent['about_content'] ?? null;
  @endphp

  {{-- HERO --}}
  <section class="hero">
    <div class="eyebrow">Local Real Estate{{ $campaign->country ? ' — ' . $campaign->country : '' }}</div>
    <h1>{{ $campaign->name }}</h1>
    <p>{{ $heroText ?: ($campaign->positioning_note ?: 'Explore real estate opportunities in ' . $campaign->primary_city . ' and the surrounding area. We cover the places that matter most for buyers, sellers, and investors.') }}</p>
  </section>

  {{-- CONTEXT META BAR --}}
  @php 
    $listings = $campaign->listings()->where('status', 'active')->latest()->get();
    $pageSettings = $campaign->page_settings ?? [];
    $showLeadMagnet = $pageSettings['show_lead_magnet'] ?? true;
    $showFaq = $pageSettings['show_faq'] ?? true;
    $showListings = $pageSettings['show_listings'] ?? true;
    $featuredPercent = $pageSettings['featured_listings_percent'] ?? 10;
    $regularPercent = $pageSettings['regular_listings_percent'] ?? 6;
    $approvalStatus = $pageSettings['approval_status'] ?? 'pending';
  @endphp
  <section class="context">
    <div class="meta"><b>Market</b><span>{{ $campaign->primary_city ?? '—' }}</span></div>
    <div class="meta"><b>Coverage</b><span>{{ $campaign->coverage_area ? $campaign->coverage_area . ' ' . $campaign->coverage_unit : '—' }}</span></div>
    <div class="meta"><b>Listings</b><span>{{ $listings->count() }} active</span></div>
    <div class="meta"><b>Language</b><span>{{ $profile->ai_content_language ?? 'English' }}</span></div>
    <div class="meta"><b>Status</b><span>{{ $campaign->isPublished() ? 'Published' : ucfirst($campaign->status) }}</span></div>
  </section>

  <section class="steps">

    {{-- ASK AI --}}
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">AI</span>Ask anything about {{ $campaign->primary_city }} real estate</h2>
          <p class="lead">Villa Bit AI analyzes your question and prepares a complete report based on this market.</p>
        </div>
        <div class="output">OUTPUT → Instant AI report</div>
      </div>
      <form class="query-box" onsubmit="event.preventDefault(); alert('AI agent coming soon.');">
        <input type="text" placeholder="What property types are available in {{ $campaign->primary_city }}?" aria-label="Ask about {{ $campaign->primary_city }} real estate">
        <button class="submit-arrow" type="submit" aria-label="Submit">↗</button>
      </form>
    </article>

    {{-- AREAS WE COVER --}}
    @php $targetPlaces = $campaign->target_places ?? []; @endphp
    @if(count($targetPlaces) > 0)
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">1</span>Areas we cover around {{ $campaign->primary_city }}</h2>
          <p class="lead">These nearby places are part of our local campaign — the locations that matter most for buyers, sellers, and investors.</p>
        </div>
        <div class="output">OUTPUT → Coverage map</div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Location</th><th>Type</th><th>Distance</th><th>Why it matters</th><th>Priority</th></tr>
          </thead>
          <tbody>
            @foreach($targetPlaces as $index => $place)
            @php
              $aiDesc = $areaDescriptions[$index]['description'] ?? null;
            @endphp
            <tr>
              <td><b>{{ $place['name'] ?? '' }}</b></td>
              <td>{{ $place['type'] ?? '—' }}</td>
              <td>{{ $place['distance'] ?? '—' }}</td>
              <td>{{ $aiDesc ?: ($place['reason'] ?? '—') }}</td>
              <td>
                @php $prio = strtoupper($place['priority'] ?? 'MEDIUM'); @endphp
                <span class="badge {{ $prio === 'HIGH' ? 'high' : ($prio === 'LOW' ? 'low' : 'medium') }}">{{ $prio }}</span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </article>
    @endif

    {{-- INVISIBLE LEAD MAGNET --}}
    @if($showLeadMagnet)
    <article class="step" style="background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);">
      <div class="step-head">
        <div>
          <h2><span class="step-number">📧</span>Get exclusive {{ $campaign->primary_city }} market insights</h2>
          <p class="lead">Subscribe to receive property alerts, market reports, and investment opportunities directly to your inbox.</p>
        </div>
        <div class="output">OUTPUT → Personalized alerts</div>
      </div>
      <form class="query-box" style="max-width: 600px;" onsubmit="event.preventDefault(); alert('Lead magnet form submitted!');">
        <input type="email" name="email" placeholder="Enter your email address" aria-label="Email for property alerts" required>
        <button class="submit-arrow" type="submit" aria-label="Subscribe">→</button>
      </form>
      <p style="margin-top: 12px; font-size: 12px; color: var(--muted);">
        <i>We respect your privacy. Unsubscribe anytime.</i>
      </p>
    </article>
    @endif

    {{-- FAQ SECTION --}}
    @if($showFaq)
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">?</span>Frequently asked questions about {{ $campaign->primary_city }}</h2>
          <p class="lead">Common questions from buyers and investors about this market.</p>
        </div>
        <div class="output">OUTPUT → Expert answers</div>
      </div>
      <div class="faq-list" style="display: grid; gap: 12px;">
        @php
          // Use AI-generated FAQs if available, otherwise fallback to defaults
          $faqs = !empty($faqContent) ? $faqContent : [
            ['question' => 'What types of properties are available in ' . $campaign->primary_city . '?', 'answer' => 'The ' . $campaign->primary_city . ' market offers a diverse range of properties including apartments, villas, houses, and land plots. Our agency specializes in matching buyers with the right property type for their needs.'],
            ['question' => 'What is the average property price in this area?', 'answer' => 'Property prices vary significantly based on location, size, and condition. Contact our agency for current market analysis and personalized price guidance.'],
            ['question' => 'Is ' . $campaign->primary_city . ' a good area for real estate investment?', 'answer' => 'Yes, ' . $campaign->primary_city . ' offers strong investment potential with growing demand, tourism appeal, and infrastructure development. We can provide detailed ROI analysis.'],
            ['question' => 'What is the buying process for foreign buyers?', 'answer' => 'Foreign buyers can purchase property with proper documentation. Our agency guides you through the entire process including legal requirements and paperwork.'],
            ['question' => 'How long does a typical property transaction take?', 'answer' => 'A standard transaction takes 30-60 days from offer acceptance to completion. We ensure all steps are handled efficiently.'],
            ['question' => 'Do you offer property management services?', 'answer' => 'Yes, we offer comprehensive property management including rental management, maintenance, and tenant relations for investment properties.'],
          ];
        @endphp
        @foreach($faqs as $index => $faq)
        <details class="faq-item" style="border: 1px solid var(--line); border-radius: 10px; padding: 0;">
          <summary style="padding: 16px 18px; cursor: pointer; font-weight: 700; font-size: 15px; list-style: none; display: flex; justify-content: space-between; align-items: center;">
            {{ $faq['question'] ?? $faq['q'] ?? '' }}
            <span style="font-size: 18px; color: var(--muted);">+</span>
          </summary>
          <div style="padding: 0 18px 16px; color: #3e4348; font-size: 14px; line-height: 1.7;">
            {{ $faq['answer'] ?? $faq['a'] ?? '' }}
          </div>
        </details>
        @endforeach
      </div>
    </article>
    @endif

    {{-- FEATURED LISTINGS --}}
    @if($showListings && $listings->isNotEmpty())
    @php
      $totalListings = $listings->count();
      $featuredCount = max(1, (int) ceil($totalListings * $featuredPercent / 100));
      $regularCount = max(1, (int) ceil($totalListings * $regularPercent / 100));
      $featuredListings = $listings->take($featuredCount);
      $regularListings = $listings->skip($featuredCount)->take($regularCount);
    @endphp
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">2</span>Featured properties in {{ $campaign->primary_city }}</h2>
          <p class="lead">A selection of properties from the {{ $campaign->name }} campaign.</p>
        </div>
        <div class="output">OUTPUT → {{ $listings->count() }} listings</div>
      </div>
      
      {{-- Featured listings (larger cards) --}}
      @if($featuredListings->isNotEmpty())
      <h4 style="margin: 0 0 16px; font-size: 14px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;">Featured Properties</h4>
      <div class="listing-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px;">
        @foreach($featuredListings as $listing)
        <div class="listing-card" style="border: 2px solid var(--accent);">
          @if(!empty($listing->images[0]))
            <img class="listing-img" src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" loading="lazy">
          @else
            <div class="listing-img empty">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>
              <span>No image</span>
            </div>
          @endif
          <div class="listing-body">
            <span class="badge high" style="margin-bottom: 8px;">⭐ Featured</span>
            <h3>{{ $listing->title }}</h3>
            <div class="listing-meta">
              <span class="badge high">{{ $listing->property_type ?: 'Property' }}</span>
              <span>{{ $listing->location ?? $campaign->primary_city }}</span>
            </div>
            <p class="desc">{{ \Illuminate\Support\Str::limit($listing->description, 180) }}</p>
            @if($listing->formatted_price)
              <div class="listing-price">{{ $listing->formatted_price }}</div>
            @endif
            <a href="mailto:{{ $profile->contact_email }}?subject=Inquiry: {{ urlencode($listing->title) }}" class="listing-btn">Request details</a>
          </div>
        </div>
        @endforeach
      </div>
      @endif

      {{-- Regular listings (smaller grid) --}}
      @if($regularListings->isNotEmpty())
      <h4 style="margin: 0 0 16px; font-size: 14px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.05em;">More Properties</h4>
      <div class="listing-grid">
        @foreach($regularListings as $listing)
        <div class="listing-card">
          @if(!empty($listing->images[0]))
            <img class="listing-img" src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" loading="lazy">
          @else
            <div class="listing-img empty">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>
              <span>No image</span>
            </div>
          @endif
          <div class="listing-body">
            <h3>{{ $listing->title }}</h3>
            <div class="listing-meta">
              <span class="badge high">{{ $listing->property_type ?: 'Property' }}</span>
              <span>{{ $listing->location ?? $campaign->primary_city }}</span>
            </div>
            <p class="desc">{{ \Illuminate\Support\Str::limit($listing->description, 130) }}</p>
            @if($listing->formatted_price)
              <div class="listing-price">{{ $listing->formatted_price }}</div>
            @endif
            <a href="mailto:{{ $profile->contact_email }}?subject=Inquiry: {{ urlencode($listing->title) }}" class="listing-btn">Request details</a>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </article>
    @endif

    {{-- ABOUT (full width) --}}
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">3</span>About {{ $profile->agency_name }}</h2>
          <p class="lead">Local market expertise combined with AI-powered tools.</p>
        </div>
        <div class="output">OUTPUT → Trusted guidance</div>
      </div>
      <div class="copy-stack">
        <p>{{ $aboutText ?: ($profile->agency_name . ' is a real estate agency focused on ' . $campaign->primary_city . ' and the surrounding region. We track listings, market signals, and buyer interest across the wider market — helping you make better decisions with clear, local knowledge.') }}</p>
      </div>
      <div class="inline-cards">
        <div class="mini"><strong>Local Coverage</strong><p>{{ $campaign->coverage_area }} {{ $campaign->coverage_unit }} around {{ $campaign->primary_city }}.</p><span class="tiny">→ Wider market reach</span></div>
        <div class="mini"><strong>Active Listings</strong><p>{{ $listings->count() }} properties in this campaign.</p><span class="tiny">→ Real inventory</span></div>
        <div class="mini"><strong>AI Reports</strong><p>Instant market answers for buyers.</p><span class="tiny">→ Faster decisions</span></div>
        <div class="mini"><strong>Direct Contact</strong><p>Talk to the agency, no middleman.</p><span class="tiny">→ Trusted guidance</span></div>
      </div>
    </article>

    {{-- CONTACT (full-width dark CTA band) --}}
    <article class="step cta-band">
      <div class="cta-band-inner">
        <div>
          <span class="eyebrow">Get in touch</span>
          <h2 class="cta-title">Ready to explore {{ $campaign->primary_city }} real estate?</h2>
          <p class="cta-sub">Reach out and we will guide you through the market — no middleman.</p>
        </div>
        <div class="cta-actions">
          @if($profile->contact_email)
            <a href="mailto:{{ $profile->contact_email }}" class="cta-btn gold">Email {{ $profile->agency_name }}</a>
          @endif
          @if($profile->contact_phone)
            <a href="tel:{{ $profile->contact_phone }}" class="cta-btn ghost">{{ $profile->contact_phone }}</a>
          @endif
        </div>
      </div>
    </article>

  </section>

  <div class="footer-note">
    <b>{{ $profile->agency_name }}</b> — Local real estate expertise in {{ $campaign->primary_city }} and nearby areas. Every property and figure shown here comes from real agency data.
    @if($approvalStatus === 'villa_bit_approved')
      <br><span style="display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 6px 12px; background: #e8f5e9; border-radius: 6px; font-size: 12px; font-weight: 700; color: #2e7d32;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
        Villa Bit AI checked & manually approved
      </span>
    @endif
  </div>

  <footer>
    &copy; {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.
  </footer>

</main>
</body>
</html>
