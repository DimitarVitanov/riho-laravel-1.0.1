<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@php
  $aiContent = $campaign->ai_generated_content ?? [];
  $heroText = $aiContent['hero_content'] ?? null;
  $areaDescriptions = $aiContent['area_descriptions'] ?? [];
  $faqContent = $aiContent['faq_content'] ?? [];
  $aboutText = $aiContent['about_content'] ?? null;
  $listings = $campaign->listings()->where('status', 'active')->latest()->get();
  $targetPlaces = $campaign->target_places ?? [];
  
  // Brand colors
  $primaryColor   = $profile->website_primary_color ?? $profile->brand_primary_color ?? '#0f0f0f';
  $secondaryColor = $profile->website_secondary_color ?? $profile->brand_secondary_color ?? '#374151';
  $accentColor    = $profile->website_accent_color ?? '#3b82f6';

  // Header settings
  $headerBg       = $profile->header_bg_color ?? $primaryColor;
  $headerTextClr  = $profile->header_text_color ?? '#ffffff';
  $topbarEnabled  = $profile->header_topbar_enabled ?? true;
  $topbarText     = $profile->header_topbar_text ?: 'Real Estate Taxi is your FREE rule through the global real estate market!';
  $logoType       = $profile->header_logo_type ?? 'image';
  $logoText       = $profile->header_logo_text ?: $profile->agency_name;
  $logoPath       = $profile->header_logo_path ? asset('storage/' . $profile->header_logo_path) : null;
  $logoUrl        = $profile->header_logo_url ?? '#';
  $ctaEnabled     = $profile->header_cta_enabled ?? true;
  $ctaText        = $profile->header_cta_text ?: 'Get Free Report';
  $ctaUrl         = $profile->header_cta_url ?? '#';
  $ctaBg          = $profile->header_cta_bg_color ?? '#f59e0b';
  $ctaClr         = $profile->header_cta_text_color ?? '#1a1a1a';
  $topbarColor    = $profile->header_topbar_color ?? '#ffffff';
  $topbarBg       = $profile->header_topbar_bg_color ?? '#0a0a0a';
  $defaultNav = [
    ['label' => 'Explore',        'url' => '#'],
    ['label' => 'Solutions',      'url' => '#'],
    ['label' => 'Market Routes',  'url' => '#'],
    ['label' => 'Top Areas',      'url' => '#'],
    ['label' => 'Expert Topics',  'url' => '#'],
    ['label' => 'Markets',        'url' => '#'],
  ];
  $navItems = (!empty($profile->header_nav_items) && count($profile->header_nav_items) > 0)
    ? $profile->header_nav_items
    : $defaultNav;

  // Footer settings
  $footerBg       = $profile->footer_bg_color ?? '#111827';
  $footerTextClr  = $profile->footer_text_color ?? '#ffffff';
  $col1Title      = $profile->footer_col1_title ?: 'WE GLAD TO OFFER';
  $defaultCol1Links = [
    ['label' => '24/7 Taxi Service To Any Where Around The City', 'url' => '#'],
    ['label' => 'Sending Taxi Booking Alert By SMS',               'url' => '#'],
    ['label' => 'GPS Tracking System For Location Guessing',       'url' => '#'],
  ];
  $col1Links      = (!empty($profile->footer_col1_links) && count($profile->footer_col1_links) > 0)
    ? $profile->footer_col1_links
    : $defaultCol1Links;
  $col2Title      = $profile->footer_col2_title ?: 'ABOUT US';
  $col2Text       = $profile->footer_col2_text ?: 'Hello we are ' . $profile->agency_name . '. We are here to provide you the best offers through our coupons and tools. We are here to provide you coupons.';
  $copyright      = $profile->footer_copyright_text ?: ('© ' . date('Y') . ' ' . $profile->agency_name . '. All rights reserved.');
  $termsUrl       = $profile->footer_terms_url ?? '';
  $privacyUrl     = $profile->footer_privacy_url ?? '';
@endphp
<meta name="description" content="{{ $aiContent['meta_description'] ?? $campaign->positioning_note ?? '' }}">
<title>{{ $campaign->name }} | {{ $profile->agency_name }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --ink: {{ $primaryColor }};
  --bg: #f4f5f6;
  --card: #ffffff;
  --line: #e4e6e9;
  --muted: {{ $secondaryColor }};
  --accent: {{ $accentColor }};
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); color: var(--ink); font-family: 'Inter', sans-serif; line-height: 1.6; }
a { color: var(--accent); text-decoration: none; }
a:hover { text-decoration: underline; }
img { max-width: 100%; }

.wrap { max-width: 1280px; margin: 0 auto; padding: 32px 24px 80px; }

/* Header */
.header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
.logo { display: flex; align-items: center; gap: 14px; }
.logo-icon { width: 48px; height: 48px; background: var(--ink); color: #fff; border-radius: 12px; display: grid; place-items: center; font-weight: 700; font-size: 20px; }
.logo-text { font-weight: 700; font-size: 18px; }
.logo-sub { font-size: 13px; color: var(--muted); font-weight: 400; }
.header-btn { background: var(--ink); color: #fff; padding: 14px 28px; border-radius: 50px; font-weight: 600; font-size: 14px; transition: all 0.2s; }
.header-btn:hover { background: var(--accent); text-decoration: none; }

/* Hero */
.hero { background: var(--ink); color: #fff; border-radius: 20px; padding: 48px; margin-bottom: 24px; }
.hero-label { font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; opacity: 0.6; margin-bottom: 16px; }
.hero h1 { font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.1; margin-bottom: 20px; letter-spacing: -0.02em; }
.hero-desc { font-size: 18px; opacity: 0.8; max-width: 700px; line-height: 1.7; }

/* Stats Row */
.stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-box { background: var(--card); border-radius: 16px; padding: 28px; text-align: center; border: 1px solid var(--line); transition: all 0.2s; }
.stat-box:hover { border-color: var(--accent); }
.stat-val { font-size: 32px; font-weight: 800; margin-bottom: 4px; color: var(--ink); }
.stat-label { font-size: 13px; color: var(--muted); font-weight: 500; }

/* Section */
.section { background: var(--card); border-radius: 20px; padding: 36px; margin-bottom: 24px; border: 1px solid var(--line); }
.section-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; gap: 20px; }
.section-title { font-size: 24px; font-weight: 700; margin-bottom: 8px; color: var(--ink); }
.section-desc { font-size: 15px; color: var(--muted); max-width: 600px; }
.section-badge { background: var(--accent); color: #fff; padding: 10px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; white-space: nowrap; }

/* Areas Grid - 3 per row */
.areas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.area-box { background: var(--bg); border-radius: 14px; padding: 24px; transition: all 0.2s; border: 2px solid transparent; }
.area-box:hover { border-color: var(--accent); }
.area-name { font-size: 17px; font-weight: 700; margin-bottom: 6px; color: var(--ink); }
.area-meta { font-size: 12px; color: var(--muted); margin-bottom: 12px; }
.area-desc { font-size: 14px; color: #444; line-height: 1.6; }
.area-priority { display: inline-block; margin-top: 12px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.area-priority.high { background: #e8f5e9; color: #2e7d32; }
.area-priority.medium { background: #fff8e1; color: #f57f17; }
.area-priority.low { background: #f5f5f5; color: #757575; }

/* Listings Grid - 3 per row */
.listings-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.listing-card { background: var(--bg); border-radius: 14px; overflow: hidden; transition: all 0.2s; border: 2px solid transparent; }
.listing-card:hover { border-color: var(--accent); transform: translateY(-4px); }
.listing-img { aspect-ratio: 16/10; background: #ddd; }
.listing-img img { width: 100%; height: 100%; object-fit: cover; }
.listing-body { padding: 20px; }
.listing-price { font-size: 22px; font-weight: 800; margin-bottom: 6px; color: var(--accent); }
.listing-title { font-size: 15px; font-weight: 600; margin-bottom: 4px; color: var(--ink); }
.listing-loc { font-size: 13px; color: var(--muted); }

/* FAQ Accordion - 2 columns */
.faq-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0 32px; }
.faq-item { border-bottom: 1px solid var(--line); }
.faq-q { 
  width: 100%; padding: 20px 0; background: none; border: none; 
  font: inherit; font-size: 15px; font-weight: 600; text-align: left; color: var(--ink);
  cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px;
  transition: color 0.2s;
}
.faq-q:hover { color: var(--accent); }
.faq-q::after { content: '+'; font-size: 20px; color: var(--accent); font-weight: 300; flex-shrink: 0; }
.faq-item.open .faq-q::after { content: '−'; }
.faq-a { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
.faq-item.open .faq-a { max-height: 300px; }
.faq-a-inner { padding-bottom: 20px; font-size: 14px; color: #555; line-height: 1.7; }

/* About - 4 mini cards */
.about-text { font-size: 16px; color: #444; line-height: 1.8; margin-bottom: 28px; max-width: 800px; }
.mini-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.mini-box { background: var(--bg); border-radius: 14px; padding: 20px; transition: all 0.2s; border: 2px solid transparent; }
.mini-box:hover { border-color: var(--accent); }
.mini-box strong { display: block; font-size: 15px; margin-bottom: 6px; color: var(--ink); }
.mini-box p { font-size: 13px; color: var(--muted); margin: 0; line-height: 1.5; }

/* CTA */
.cta { background: var(--ink); color: #fff; border-radius: 20px; padding: 48px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
.cta-content h3 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
.cta-content p { font-size: 16px; opacity: 0.7; max-width: 500px; }
.cta-btns { display: flex; gap: 12px; }
.btn-white { background: #fff; color: var(--ink); padding: 16px 32px; border-radius: 50px; font-weight: 700; font-size: 15px; transition: all 0.2s; }
.btn-white:hover { background: var(--accent); color: #fff; text-decoration: none; }
.btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.3); padding: 14px 30px; border-radius: 50px; font-weight: 600; font-size: 15px; transition: all 0.2s; }
.btn-outline:hover { border-color: var(--accent); background: var(--accent); text-decoration: none; }

/* Footer */
.footer { text-align: center; padding: 32px 0; color: var(--muted); font-size: 13px; }

@media (max-width: 1024px) {
  .stats { grid-template-columns: repeat(2, 1fr); }
  .areas-grid, .listings-grid { grid-template-columns: repeat(2, 1fr); }
  .mini-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 640px) {
  .wrap { padding: 20px 16px 60px; }
  .hero { padding: 32px 24px; border-radius: 16px; }
  .section { padding: 28px 20px; border-radius: 16px; }
  .stats, .areas-grid, .listings-grid, .mini-grid, .faq-grid { grid-template-columns: 1fr; }
  .section-head { flex-direction: column; }
  .cta { flex-direction: column; text-align: center; padding: 36px 24px; }
  .cta-btns { justify-content: center; }
}
</style>
</head>
<body>

{{-- Top Bar --}}
@if($topbarEnabled && $topbarText)
<div style="background:{{ $topbarBg }};border-bottom:1px solid rgba(0,0,0,0.08);">
  <div style="max-width:1280px;margin:0 auto;padding:8px 32px;color:{{ $topbarColor }};font-size:13px;font-weight:500;letter-spacing:0.01em;text-align:left;">{{ $topbarText }}</div>
</div>
@endif

{{-- Header - realestate.taxi style --}}
<header style="background:{{ $headerBg }};border-bottom:1px solid rgba(255,255,255,0.08);">
  <div style="max-width:1280px;margin:0 auto;padding:0 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;height:72px;">

    {{-- Logo --}}
    <a href="{{ $logoUrl }}" style="display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0;">
      @if($logoType === 'text')
        <span style="font-size:20px;font-weight:800;color:{{ $headerTextClr }};letter-spacing:-0.02em;">{{ $logoText }}</span>
      @elseif($logoPath)
        <img src="{{ $logoPath }}" alt="{{ $profile->agency_name }}" style="max-height:48px;">
      @elseif($profile->agency_logo_path)
        <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" style="max-height:48px;">
      @else
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:52px;height:48px;background:#f59e0b;border-radius:4px;flex-shrink:0;">
          <span style="font-size:9px;font-weight:800;color:#fff;letter-spacing:.05em;text-transform:uppercase;line-height:1;">REAL ESTATE</span>
          <span style="font-size:18px;font-weight:900;color:#fff;line-height:1.1;">{{ strtoupper(substr($profile->agency_name, 0, 3)) }}</span>
        </div>
        <span style="font-size:16px;font-weight:700;color:{{ $headerTextClr }};line-height:1.2;">{{ $profile->agency_name }}</span>
      @endif
    </a>

    {{-- Nav --}}
    @if(count($navItems) > 0)
    <nav style="display:flex;align-items:center;gap:4px;flex:1;justify-content:center;">
      @foreach($navItems as $nav)
        <a href="{{ $nav['url'] ?? '#' }}" style="font-size:14px;font-weight:500;color:{{ $headerTextClr }};text-decoration:none;padding:8px 14px;border-radius:4px;transition:background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ $nav['label'] ?? '' }}</a>
      @endforeach
    </nav>
    @endif

    {{-- CTA Button --}}
    @if($ctaEnabled)
      <a href="{{ $ctaUrl }}" style="background:{{ $ctaBg }};color:{{ $ctaClr }};padding:11px 22px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;white-space:nowrap;flex-shrink:0;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">{{ $ctaText }}</a>
    @endif

  </div>
</header>

<div class="wrap">

  <!-- Hero -->
  <section class="hero">
    <div class="hero-label">{{ $campaign->country ?? 'Real Estate' }} · Local Expert</div>
    <h1>{{ $campaign->name }}</h1>
    <p class="hero-desc">{{ $heroText ?? ($campaign->positioning_note ?? 'Explore real estate opportunities in ' . $campaign->primary_city . '. Expert local guidance for buyers, sellers, and investors.') }}</p>
  </section>

  <!-- Stats -->
  <div class="stats">
    <div class="stat-box">
      <div class="stat-val">{{ count($targetPlaces) }}</div>
      <div class="stat-label">Areas Covered</div>
    </div>
    <div class="stat-box">
      <div class="stat-val">{{ $listings->count() }}</div>
      <div class="stat-label">Active Listings</div>
    </div>
    <div class="stat-box">
      <div class="stat-val">{{ $campaign->coverage_area ?? '—' }}</div>
      <div class="stat-label">{{ $campaign->coverage_unit ?? 'km' }} Radius</div>
    </div>
    <div class="stat-box">
      <div class="stat-val">{{ $profile->ai_content_language ?? 'EN' }}</div>
      <div class="stat-label">Language</div>
    </div>
  </div>

  <!-- Areas -->
  @if(count($targetPlaces) > 0)
  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">Areas We Cover</h2>
        <p class="section-desc">Key locations around {{ $campaign->primary_city }} where we help buyers find their perfect property.</p>
      </div>
      <span class="section-badge">{{ count($targetPlaces) }} locations</span>
    </div>
    <div class="areas-grid">
      @foreach($targetPlaces as $index => $place)
      @php $aiDesc = $areaDescriptions[$index]['description'] ?? null; @endphp
      <div class="area-box">
        <div class="area-name">{{ $place['name'] ?? '' }}</div>
        <div class="area-meta">{{ $place['type'] ?? 'Location' }} · {{ $place['distance'] ?? '' }}</div>
        <div class="area-desc">{{ $aiDesc ?? ($place['reason'] ?? 'Strategic location with excellent real estate opportunities.') }}</div>
        @php $prio = strtolower($place['priority'] ?? 'medium'); @endphp
        <span class="area-priority {{ $prio }}">{{ strtoupper($prio) }}</span>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  <!-- Listings -->
  @if($listings->count() > 0)
  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">Featured Properties</h2>
        <p class="section-desc">Handpicked listings in {{ $campaign->primary_city }} and surrounding areas.</p>
      </div>
      <span class="section-badge">{{ $listings->count() }} listings</span>
    </div>
    <div class="listings-grid">
      @foreach($listings->take(6) as $listing)
      <div class="listing-card">
        <div class="listing-img">
          @if($listing->images && count($listing->images) > 0)
            <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}">
          @endif
        </div>
        <div class="listing-body">
          <div class="listing-price">{{ $listing->formatted_price ?? '—' }}</div>
          <div class="listing-title">{{ $listing->title }}</div>
          <div class="listing-loc">{{ $listing->location ?? '' }}</div>
        </div>
      </div>
      @endforeach
    </div>
  </section>
  @endif

  <!-- FAQ -->
  @php
    $faqs = !empty($faqContent) ? $faqContent : [
      ['question' => 'What types of properties are available?', 'answer' => 'We offer apartments, villas, houses, and land plots throughout ' . $campaign->primary_city . '.'],
      ['question' => 'Do you assist international buyers?', 'answer' => 'Yes, we provide full support including legal guidance and documentation.'],
      ['question' => 'How do I schedule a viewing?', 'answer' => 'Contact us via email or phone and we will arrange a convenient time.'],
      ['question' => 'What is the buying process?', 'answer' => 'We guide you through every step from property search to final purchase.'],
    ];
  @endphp
  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">Frequently Asked Questions</h2>
        <p class="section-desc">Common questions about buying property in {{ $campaign->primary_city }}.</p>
      </div>
      <span class="section-badge">{{ count($faqs) }} questions</span>
    </div>
    <div class="faq-grid">
      @foreach($faqs as $faq)
      <div class="faq-item">
        <button class="faq-q" onclick="this.parentElement.classList.toggle('open')">{{ $faq['question'] ?? '' }}</button>
        <div class="faq-a"><p class="faq-a-inner">{{ $faq['answer'] ?? '' }}</p></div>
      </div>
      @endforeach
    </div>
  </section>

  <!-- About -->
  <section class="section">
    <div class="section-head">
      <div>
        <h2 class="section-title">About {{ $profile->agency_name }}</h2>
        <p class="section-desc">Your trusted local real estate partner.</p>
      </div>
    </div>
    <p class="about-text">{{ $aboutText ?? ($profile->agency_name . ' is a trusted real estate agency serving ' . $campaign->primary_city . ' and the surrounding area. We combine local market expertise with personalized service to help you find the perfect property.') }}</p>
    <div class="mini-grid">
      <div class="mini-box">
        <strong>Local Expertise</strong>
        <p>Deep knowledge of {{ $campaign->primary_city }} market.</p>
      </div>
      <div class="mini-box">
        <strong>Trusted Guidance</strong>
        <p>Professional support at every step.</p>
      </div>
      <div class="mini-box">
        <strong>{{ count($targetPlaces) }}+ Areas</strong>
        <p>Coverage across key locations.</p>
      </div>
      <div class="mini-box">
        <strong>{{ $listings->count() }} Listings</strong>
        <p>Active properties ready for viewing.</p>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta">
    <div class="cta-content">
      <h3>Ready to Find Your Property?</h3>
      <p>Contact us today for expert guidance on real estate in {{ $campaign->primary_city }}.</p>
    </div>
    <div class="cta-btns">
      @if($profile->contact_email)
        <a href="mailto:{{ $profile->contact_email }}" class="btn-white">Get in Touch</a>
      @endif
      @if($profile->contact_phone)
        <a href="tel:{{ $profile->contact_phone }}" class="btn-outline">Call Us</a>
      @endif
    </div>
  </section>

</div>{{-- end .wrap --}}

{{-- Footer - realestate.taxi style --}}
<footer style="background:{{ $footerBg }};color:{{ $footerTextClr }};">

  {{-- Main footer columns --}}
  @if($col1Title || $col2Title || count($col1Links) > 0 || $col2Text)
  <div style="max-width:1280px;margin:0 auto;padding:56px 32px 40px;display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:48px;">

    {{-- Col 0: Logo + subscribe box (realestate.taxi style) --}}
    <div>
      <div style="margin-bottom:20px;">
        @if($logoPath)
          <img src="{{ $logoPath }}" alt="{{ $profile->agency_name }}" style="max-height:44px;">
        @elseif($profile->agency_logo_path)
          <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" style="max-height:44px;">
        @else
          <span style="font-size:20px;font-weight:900;color:{{ $footerTextClr }};">{{ $profile->agency_name }}</span>
        @endif
      </div>
      @if($col2Text)
        <p style="font-size:13px;opacity:0.6;line-height:1.7;margin:0 0 20px;">{{ $col2Text }}</p>
      @endif
    </div>

    {{-- Col 1: Links --}}
    @if($col1Title || count($col1Links) > 0)
    <div>
      @if($col1Title)
        <div style="font-size:12px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:{{ $footerTextClr }};opacity:0.5;margin-bottom:18px;">{{ $col1Title }}</div>
      @endif
      @foreach($col1Links as $link)
        <div style="margin-bottom:10px;">
          <a href="{{ $link['url'] ?? '#' }}" style="color:{{ $footerTextClr }};font-size:14px;opacity:0.8;text-decoration:underline;" onmouseover="this.style.textDecoration='none';this.style.opacity='1'" onmouseout="this.style.textDecoration='underline';this.style.opacity='0.8'">&rsaquo; {{ $link['label'] ?? '' }}</a>
        </div>
      @endforeach
    </div>
    @endif

    {{-- Col 2: About text --}}
    @if($col2Title)
    <div>
      <div style="font-size:12px;font-weight:800;letter-spacing:.07em;text-transform:uppercase;color:{{ $footerTextClr }};opacity:0.5;margin-bottom:18px;">{{ $col2Title }}</div>
      @if($col2Text)
        <p style="font-size:14px;opacity:0.75;line-height:1.75;margin:0;">{{ $col2Text }}</p>
      @endif
    </div>
    @endif

  </div>
  @endif

  {{-- Bottom bar --}}
  <div style="border-top:1px solid rgba(255,255,255,0.1);">
    <div style="max-width:1280px;margin:0 auto;padding:18px 32px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
      <span style="font-size:13px;opacity:0.45;">{{ $copyright }}</span>
      <div style="display:flex;gap:24px;align-items:center;">
        @if($termsUrl)
          <a href="{{ $termsUrl }}" style="font-size:13px;color:{{ $footerTextClr }};opacity:0.5;text-decoration:underline;" onmouseover="this.style.textDecoration='none';this.style.opacity='0.85'" onmouseout="this.style.textDecoration='underline';this.style.opacity='0.5'">Terms of Use</a>
        @endif
        @if($privacyUrl)
          <a href="{{ $privacyUrl }}" style="font-size:13px;color:{{ $footerTextClr }};opacity:0.5;text-decoration:underline;" onmouseover="this.style.textDecoration='none';this.style.opacity='0.85'" onmouseout="this.style.textDecoration='underline';this.style.opacity='0.5'">Privacy Policy</a>
        @endif
      </div>
    </div>
  </div>

</footer>
</body>
</html>
