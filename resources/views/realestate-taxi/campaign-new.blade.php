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
@endphp
<meta name="description" content="{{ $aiContent['meta_description'] ?? $campaign->positioning_note ?? '' }}">
<title>{{ $campaign->name }} | {{ $profile->agency_name }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root{--black:#000;--white:#fff;--gray-50:#fafafa;--gray-100:#f5f5f5;--gray-200:#e5e5e5;--gray-300:#d4d4d4;--gray-400:#a3a3a3;--gray-500:#737373;--gray-600:#525252;--gray-700:#404040;--gray-800:#262626;--gray-900:#171717}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif;background:var(--white);color:var(--gray-900);line-height:1.6;font-size:16px;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
img{max-width:100%;height:auto;display:block}
.container{max-width:1200px;margin:0 auto;padding:0 24px}
@media(max-width:768px){.container{padding:0 16px}}

header{position:fixed;top:0;left:0;right:0;z-index:100;background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);border-bottom:1px solid var(--gray-200)}
.header-inner{display:flex;align-items:center;justify-content:space-between;height:72px}
.logo{font-weight:700;font-size:20px;letter-spacing:-0.02em}
.logo span{font-weight:400;color:var(--gray-500)}
.header-cta{display:inline-flex;align-items:center;gap:8px;background:var(--black);color:var(--white);padding:12px 24px;border-radius:100px;font-weight:600;font-size:14px;transition:all 0.2s}
.header-cta:hover{background:var(--gray-800);transform:translateY(-1px)}

.hero{padding:140px 0 80px;background:linear-gradient(180deg,var(--gray-50) 0%,var(--white) 100%)}
.hero-badge{display:inline-block;background:var(--black);color:var(--white);padding:6px 16px;border-radius:100px;font-size:12px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;margin-bottom:24px}
.hero h1{font-size:clamp(40px,6vw,72px);font-weight:800;letter-spacing:-0.03em;line-height:1.1;margin-bottom:24px;max-width:800px}
.hero-text{font-size:20px;line-height:1.7;color:var(--gray-600);max-width:600px}

.stats-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--gray-200);border:1px solid var(--gray-200);border-radius:16px;overflow:hidden;margin:60px 0}
.stat{background:var(--white);padding:32px;text-align:center}
.stat-value{font-size:36px;font-weight:800;letter-spacing:-0.02em}
.stat-label{font-size:14px;color:var(--gray-500);margin-top:4px}
@media(max-width:768px){.stats-bar{grid-template-columns:repeat(2,1fr)}.stat{padding:24px 16px}.stat-value{font-size:28px}}

.section{padding:80px 0}
.section-dark{background:var(--gray-900);color:var(--white)}
.section-light{background:var(--gray-50)}
.section-title{font-size:14px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:var(--gray-400);margin-bottom:16px}
.section-heading{font-size:clamp(32px,4vw,48px);font-weight:700;letter-spacing:-0.02em;margin-bottom:24px}
.section-text{font-size:18px;color:var(--gray-600);max-width:600px}
.section-dark .section-text{color:var(--gray-400)}

.areas-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px}
.area-card{background:var(--white);border:1px solid var(--gray-200);border-radius:16px;padding:32px;transition:all 0.3s}
.area-card:hover{border-color:var(--black);transform:translateY(-4px)}
.area-name{font-size:20px;font-weight:700;margin-bottom:8px}
.area-type{font-size:13px;color:var(--gray-500);margin-bottom:16px}
.area-desc{font-size:15px;color:var(--gray-600);line-height:1.6}
@media(max-width:900px){.areas-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.areas-grid{grid-template-columns:1fr}}

.listings-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px}
.listing-card{background:var(--white);border-radius:16px;overflow:hidden;border:1px solid var(--gray-200);transition:all 0.3s}
.listing-card:hover{transform:translateY(-4px);box-shadow:0 20px 40px rgba(0,0,0,0.1)}
.listing-image{aspect-ratio:4/3;background:var(--gray-100);position:relative;overflow:hidden}
.listing-image img{width:100%;height:100%;object-fit:cover}
.listing-content{padding:24px}
.listing-price{font-size:24px;font-weight:800;margin-bottom:8px}
.listing-title{font-size:16px;font-weight:600;margin-bottom:8px}
.listing-location{font-size:14px;color:var(--gray-500)}
@media(max-width:900px){.listings-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.listings-grid{grid-template-columns:1fr}}

.faq-list{margin-top:48px;max-width:800px}
.faq-item{border-bottom:1px solid var(--gray-200)}
.faq-question{width:100%;padding:24px 0;background:none;border:none;font-size:18px;font-weight:600;text-align:left;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-family:inherit}
.faq-question::after{content:'+';font-size:24px;font-weight:300}
.faq-item.open .faq-question::after{content:'−'}
.faq-answer{max-height:0;overflow:hidden;transition:max-height 0.3s}
.faq-item.open .faq-answer{max-height:500px}
.faq-answer-inner{padding-bottom:24px;font-size:16px;color:var(--gray-600);line-height:1.7}

.cta-section{text-align:center;padding:100px 0}
.cta-heading{font-size:clamp(36px,5vw,56px);font-weight:800;letter-spacing:-0.02em;margin-bottom:24px}
.cta-text{font-size:18px;color:var(--gray-400);margin-bottom:40px;max-width:600px;margin-left:auto;margin-right:auto}
.cta-buttons{display:flex;gap:16px;justify-content:center;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:8px;padding:16px 32px;border-radius:100px;font-weight:600;font-size:16px;transition:all 0.2s;border:none;cursor:pointer;font-family:inherit}
.btn-primary{background:var(--white);color:var(--black)}
.btn-primary:hover{background:var(--gray-100);transform:translateY(-2px)}
.btn-outline{background:transparent;color:var(--white);border:2px solid var(--gray-600)}
.btn-outline:hover{border-color:var(--white)}

footer{background:var(--black);color:var(--white);padding:48px 0;text-align:center}
.footer-text{color:var(--gray-500);font-size:14px}
</style>
</head>
<body>

<header>
  <div class="container header-inner">
    <a href="#" class="logo">{{ $profile->agency_name }} <span>Real Estate</span></a>
    @if($profile->contact_email)
    <a href="mailto:{{ $profile->contact_email }}" class="header-cta">Contact Us</a>
    @endif
  </div>
</header>

<section class="hero">
  <div class="container">
    <div class="hero-badge">{{ $campaign->country ?? 'Real Estate' }}</div>
    <h1>{{ $campaign->name }}</h1>
    <p class="hero-text">{{ $heroText ?? ($campaign->positioning_note ?? 'Discover exceptional real estate opportunities in ' . $campaign->primary_city . '. Expert local guidance for buyers, sellers, and investors.') }}</p>
  </div>
</section>

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

@if(count($targetPlaces) > 0)
<section class="section section-light">
  <div class="container">
    <p class="section-title">Coverage</p>
    <h2 class="section-heading">Areas We Cover</h2>
    <p class="section-text">Key locations around {{ $campaign->primary_city }} where we help buyers find their perfect property.</p>
    <div class="areas-grid">
      @foreach($targetPlaces as $index => $place)
      <div class="area-card">
        <h3 class="area-name">{{ $place['name'] ?? '' }}</h3>
        <p class="area-type">{{ $place['type'] ?? 'Location' }} · {{ $place['distance'] ?? '' }}</p>
        <p class="area-desc">{{ $areaDescriptions[$index]['description'] ?? ($place['reason'] ?? 'Strategic location with excellent real estate opportunities.') }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@if($listings->count() > 0)
<section class="section">
  <div class="container">
    <p class="section-title">Properties</p>
    <h2 class="section-heading">Featured Listings</h2>
    <p class="section-text">Handpicked properties in {{ $campaign->primary_city }} and surrounding areas.</p>
    <div class="listings-grid">
      @foreach($listings->take(6) as $listing)
      <div class="listing-card">
        <div class="listing-image">
          @if($listing->images && count($listing->images) > 0)
          <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}">
          @endif
        </div>
        <div class="listing-content">
          <div class="listing-price">{{ $listing->formatted_price ?? '—' }}</div>
          <h3 class="listing-title">{{ $listing->title }}</h3>
          <p class="listing-location">{{ $listing->location ?? '' }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

@php
  $faqs = !empty($faqContent) ? $faqContent : [
    ['question' => 'What types of properties are available?', 'answer' => 'We offer apartments, villas, houses, and land plots throughout ' . $campaign->primary_city . ' and surrounding areas.'],
    ['question' => 'How do I schedule a viewing?', 'answer' => 'Contact us via email or phone and we will arrange a convenient time for property viewings.'],
    ['question' => 'Do you assist foreign buyers?', 'answer' => 'Yes, we provide full support for international buyers including legal guidance and documentation.'],
  ];
@endphp
<section class="section section-light">
  <div class="container">
    <p class="section-title">FAQ</p>
    <h2 class="section-heading">Common Questions</h2>
    <div class="faq-list">
      @foreach($faqs as $faq)
      <div class="faq-item">
        <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">{{ $faq['question'] ?? '' }}</button>
        <div class="faq-answer"><p class="faq-answer-inner">{{ $faq['answer'] ?? '' }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section section-dark cta-section">
  <div class="container">
    <h2 class="cta-heading">Ready to Find Your Property?</h2>
    <p class="cta-text">{{ $aboutText ?? ('Contact ' . $profile->agency_name . ' today for expert guidance on real estate in ' . $campaign->primary_city . '.') }}</p>
    <div class="cta-buttons">
      @if($profile->contact_email)
      <a href="mailto:{{ $profile->contact_email }}" class="btn btn-primary">Get in Touch</a>
      @endif
      @if($profile->contact_phone)
      <a href="tel:{{ $profile->contact_phone }}" class="btn btn-outline">Call Us</a>
      @endif
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <p class="footer-text">© {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
