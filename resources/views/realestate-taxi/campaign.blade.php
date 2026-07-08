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
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --ink: #0f0f0f;
  --bg: #f4f5f6;
  --card: #ffffff;
  --line: #e4e6e9;
  --muted: #71767b;
  --accent: #0f0f0f;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--bg); color: var(--ink); font-family: 'Inter', sans-serif; line-height: 1.6; }
a { color: inherit; text-decoration: none; }
img { max-width: 100%; }

.wrap { max-width: 1280px; margin: 0 auto; padding: 32px 24px 80px; }

/* Header */
.header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
.logo { display: flex; align-items: center; gap: 14px; }
.logo-icon { width: 48px; height: 48px; background: var(--ink); color: #fff; border-radius: 12px; display: grid; place-items: center; font-weight: 700; font-size: 20px; }
.logo-text { font-weight: 700; font-size: 18px; }
.logo-sub { font-size: 13px; color: var(--muted); font-weight: 400; }
.header-btn { background: var(--ink); color: #fff; padding: 14px 28px; border-radius: 50px; font-weight: 600; font-size: 14px; }

/* Hero */
.hero { background: var(--ink); color: #fff; border-radius: 20px; padding: 48px; margin-bottom: 24px; }
.hero-label { font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; opacity: 0.6; margin-bottom: 16px; }
.hero h1 { font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.1; margin-bottom: 20px; letter-spacing: -0.02em; }
.hero-desc { font-size: 18px; opacity: 0.8; max-width: 700px; line-height: 1.7; }

/* Stats Row */
.stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-box { background: var(--card); border-radius: 16px; padding: 28px; text-align: center; border: 1px solid var(--line); }
.stat-val { font-size: 32px; font-weight: 800; margin-bottom: 4px; }
.stat-label { font-size: 13px; color: var(--muted); font-weight: 500; }

/* Section */
.section { background: var(--card); border-radius: 20px; padding: 36px; margin-bottom: 24px; border: 1px solid var(--line); }
.section-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; gap: 20px; }
.section-title { font-size: 24px; font-weight: 700; margin-bottom: 8px; }
.section-desc { font-size: 15px; color: var(--muted); max-width: 600px; }
.section-badge { background: var(--bg); padding: 10px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; white-space: nowrap; }

/* Areas Grid - 3 per row */
.areas-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.area-box { background: var(--bg); border-radius: 14px; padding: 24px; }
.area-name { font-size: 17px; font-weight: 700; margin-bottom: 6px; }
.area-meta { font-size: 12px; color: var(--muted); margin-bottom: 12px; }
.area-desc { font-size: 14px; color: #444; line-height: 1.6; }
.area-priority { display: inline-block; margin-top: 12px; padding: 4px 10px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.area-priority.high { background: #e8f5e9; color: #2e7d32; }
.area-priority.medium { background: #fff8e1; color: #f57f17; }
.area-priority.low { background: #f5f5f5; color: #757575; }

/* Listings Grid - 3 per row */
.listings-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.listing-card { background: var(--bg); border-radius: 14px; overflow: hidden; }
.listing-img { aspect-ratio: 16/10; background: #ddd; }
.listing-img img { width: 100%; height: 100%; object-fit: cover; }
.listing-body { padding: 20px; }
.listing-price { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
.listing-title { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
.listing-loc { font-size: 13px; color: var(--muted); }

/* FAQ Accordion - 2 columns */
.faq-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0 32px; }
.faq-item { border-bottom: 1px solid var(--line); }
.faq-q { 
  width: 100%; padding: 20px 0; background: none; border: none; 
  font: inherit; font-size: 15px; font-weight: 600; text-align: left; 
  cursor: pointer; display: flex; justify-content: space-between; align-items: center; gap: 16px;
}
.faq-q::after { content: '+'; font-size: 20px; color: var(--muted); font-weight: 300; flex-shrink: 0; }
.faq-item.open .faq-q::after { content: '−'; }
.faq-a { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
.faq-item.open .faq-a { max-height: 300px; }
.faq-a-inner { padding-bottom: 20px; font-size: 14px; color: #555; line-height: 1.7; }

/* About - 4 mini cards */
.about-text { font-size: 16px; color: #444; line-height: 1.8; margin-bottom: 28px; max-width: 800px; }
.mini-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.mini-box { background: var(--bg); border-radius: 14px; padding: 20px; }
.mini-box strong { display: block; font-size: 15px; margin-bottom: 6px; }
.mini-box p { font-size: 13px; color: var(--muted); margin: 0; line-height: 1.5; }

/* CTA */
.cta { background: var(--ink); color: #fff; border-radius: 20px; padding: 48px; display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap; }
.cta-content h3 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
.cta-content p { font-size: 16px; opacity: 0.7; max-width: 500px; }
.cta-btns { display: flex; gap: 12px; }
.btn-white { background: #fff; color: var(--ink); padding: 16px 32px; border-radius: 50px; font-weight: 700; font-size: 15px; }
.btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.3); padding: 14px 30px; border-radius: 50px; font-weight: 600; font-size: 15px; }

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
<div class="wrap">

  <!-- Header -->
  <header class="header">
    <a href="#" class="logo">
      @if($profile->agency_logo_path)
        <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" height="48" style="border-radius:12px;">
      @else
        <span class="logo-icon">{{ strtoupper(substr($profile->agency_name, 0, 1)) }}</span>
      @endif
      <div>
        <div class="logo-text">{{ $profile->agency_name }}</div>
        <div class="logo-sub">{{ $campaign->primary_city }} Real Estate</div>
      </div>
    </a>
    @if($profile->contact_email)
      <a href="mailto:{{ $profile->contact_email }}" class="header-btn">Contact Us</a>
    @endif
  </header>

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

  <footer class="footer">
    © {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.
  </footer>

</div>
</body>
</html>
