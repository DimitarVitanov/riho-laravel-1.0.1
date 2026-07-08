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
:root {
  --ink: #111111;
  --soft: #f8f9fa;
  --line: #e9ecef;
  --muted: #6c757d;
  --accent: {{ $profile->brand_primary_color ?? '#000000' }};
  --accent-soft: #f1f3f5;
  --white: #ffffff;
}
* { box-sizing: border-box; }
body {
  margin: 0;
  background: #eef0f2;
  color: var(--ink);
  font-family: Inter, -apple-system, sans-serif;
  line-height: 1.5;
}
a { color: inherit; text-decoration: none; }
img { max-width: 100%; height: auto; }

.page { max-width: 1220px; margin: 0 auto; padding: 28px 20px 60px; }

/* Top Bar */
.topbar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px; gap: 16px;
}
.brand { display: flex; align-items: center; gap: 12px; }
.brand-icon {
  width: 44px; height: 44px; border-radius: 10px;
  display: grid; place-items: center;
  background: var(--ink); color: #fff;
  font-weight: 800; font-size: 18px;
}
.brand-text b { display: block; font-size: 16px; font-weight: 800; }
.brand-text span { font-size: 12px; color: var(--muted); }
.cta-btn {
  background: var(--accent); color: #fff;
  padding: 11px 18px; border-radius: 8px;
  font-weight: 700; font-size: 14px;
}
.cta-btn:hover { opacity: 0.9; }

/* Hero */
.hero {
  padding: 28px 30px; border-radius: 16px;
  background: var(--ink); color: #fff;
  box-shadow: 0 14px 38px rgba(0,0,0,.13);
  position: relative; overflow: hidden;
}
.hero::after {
  content: ""; position: absolute; right: -60px; top: -60px;
  width: 240px; height: 240px; border-radius: 50%;
  background: radial-gradient(circle, rgba(255,255,255,.06), transparent 70%);
}
.eyebrow {
  color: rgba(255,255,255,.6); font-weight: 800;
  font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
}
.hero h1 {
  margin: 10px 0 14px; font-size: clamp(28px, 4vw, 44px);
  line-height: 1.1; font-weight: 800;
}
.hero p { margin: 0; color: #d7dde0; max-width: 800px; font-size: 16px; }

/* Context Bar */
.context {
  margin-top: 18px; padding: 14px 16px;
  background: #fff; border: 1px solid var(--line);
  border-radius: 12px;
  display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px;
}
.meta {
  padding: 10px 12px; background: var(--soft);
  border-radius: 8px;
}
.meta b {
  display: block; font-size: 11px; letter-spacing: .05em;
  color: var(--muted); text-transform: uppercase; margin-bottom: 4px;
}
.meta span { font-weight: 700; font-size: 14px; }

/* Cards */
.cards { margin-top: 24px; display: grid; gap: 20px; }
.card {
  background: var(--white); border: 1px solid var(--line);
  border-radius: 16px; padding: 24px;
  box-shadow: 0 6px 16px rgba(22,28,35,.04);
}
.card-head {
  display: flex; justify-content: space-between; align-items: flex-start;
  border-bottom: 1px solid var(--line);
  padding-bottom: 16px; margin-bottom: 20px; gap: 16px;
}
.card-number {
  display: inline-grid; place-items: center;
  width: 32px; height: 32px; border-radius: 50%;
  background: var(--ink); color: #fff;
  font-weight: 800; font-size: 14px; margin-right: 10px;
}
.card h2 { margin: 0; font-size: 20px; display: flex; align-items: center; }
.card .lead { margin: 6px 0 0; color: var(--muted); font-size: 14px; }
.output {
  padding: 9px 12px; background: var(--accent-soft);
  color: #176347; border-radius: 8px;
  font-size: 12px; font-weight: 700; white-space: nowrap;
}

/* Table */
.table-wrap {
  overflow-x: auto; border: 1px solid var(--line);
  border-radius: 10px; margin-top: 16px;
}
table { width: 100%; border-collapse: collapse; font-size: 13px; min-width: 600px; }
th {
  text-align: left; color: #58616a; font-size: 10px;
  letter-spacing: .05em; text-transform: uppercase;
  background: #f7f8f9;
}
th, td { padding: 12px; border-bottom: 1px solid #edf0f2; }
tr:last-child td { border-bottom: 0; }
.badge {
  display: inline-block; border-radius: 999px;
  padding: 4px 10px; font-size: 10px; font-weight: 800;
}
.badge.high { background: #e7f6ef; color: #176347; }
.badge.medium { background: #fff5d8; color: #765303; }
.badge.low { background: #eef1f4; color: #5c656d; }

/* Inline Cards Grid */
.inline-cards {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 12px; margin-top: 20px;
}
.mini {
  border: 1px solid var(--line); border-radius: 10px;
  padding: 16px; background: #fbfcfc;
}
.mini strong { display: block; font-size: 15px; margin-bottom: 6px; }
.mini p { margin: 0; font-size: 13px; color: var(--muted); line-height: 1.5; }

/* Listings Grid */
.listings-grid {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 16px; margin-top: 20px;
}
.listing-card {
  border: 1px solid var(--line); border-radius: 12px;
  overflow: hidden; background: #fff;
  transition: transform .2s, box-shadow .2s;
}
.listing-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 28px rgba(22,28,35,.1);
}
.listing-img {
  width: 100%; aspect-ratio: 3/2;
  object-fit: cover; background: var(--soft);
}
.listing-body { padding: 16px; }
.listing-body h3 { margin: 0 0 6px; font-size: 15px; font-weight: 700; }
.listing-meta {
  display: flex; gap: 10px; font-size: 12px;
  color: var(--muted); margin-bottom: 10px;
}
.listing-price { font-size: 17px; font-weight: 800; }

/* FAQ */
.faq-list { margin-top: 16px; }
.faq-item { border-bottom: 1px solid var(--line); }
.faq-q {
  width: 100%; padding: 16px 0; background: none; border: none;
  font: inherit; font-size: 15px; font-weight: 600;
  text-align: left; cursor: pointer;
  display: flex; justify-content: space-between; align-items: center;
}
.faq-q::after { content: '+'; font-size: 20px; color: var(--muted); }
.faq-item.open .faq-q::after { content: '−'; }
.faq-a { max-height: 0; overflow: hidden; transition: max-height .3s; }
.faq-item.open .faq-a { max-height: 300px; }
.faq-a-inner { padding-bottom: 16px; font-size: 14px; color: var(--muted); line-height: 1.6; }

/* CTA Band */
.cta-band {
  margin-top: 24px; padding: 28px 30px;
  background: var(--ink); border-radius: 16px;
  display: flex; align-items: center; justify-content: space-between;
  gap: 24px; flex-wrap: wrap;
}
.cta-band h3 { color: #fff; margin: 0 0 8px; font-size: 24px; }
.cta-band p { color: #d7dde0; margin: 0; font-size: 15px; max-width: 600px; }
.cta-actions { display: flex; gap: 12px; }
.btn-white {
  background: #fff; color: var(--ink);
  padding: 12px 20px; border-radius: 8px;
  font-weight: 700; font-size: 14px;
}
.btn-ghost {
  background: rgba(255,255,255,.1); color: #fff;
  border: 1px solid rgba(255,255,255,.25);
  padding: 12px 20px; border-radius: 8px;
  font-weight: 700; font-size: 14px;
}

/* Footer */
footer {
  margin-top: 30px; padding: 20px 0;
  text-align: center; color: var(--muted);
  font-size: 13px; border-top: 1px solid var(--line);
}

@media (max-width: 900px) {
  .context { grid-template-columns: repeat(3, 1fr); }
  .inline-cards { grid-template-columns: repeat(2, 1fr); }
  .listings-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .page { padding: 16px 12px 40px; }
  .hero, .card { padding: 18px; border-radius: 12px; }
  .context, .inline-cards, .listings-grid { grid-template-columns: 1fr; }
  .card-head { flex-direction: column; }
  .output { white-space: normal; }
  .cta-band { flex-direction: column; text-align: center; }
}
</style>
</head>
<body>
<main class="page">

  <!-- Top Bar -->
  <div class="topbar">
    <a href="#" class="brand">
      @if($profile->agency_logo_path)
        <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" height="44" style="border-radius:10px;">
      @else
        <span class="brand-icon">{{ strtoupper(substr($profile->agency_name, 0, 1)) }}</span>
      @endif
      <span class="brand-text">
        <b>{{ $profile->agency_name }}</b>
        <span>{{ $campaign->primary_city ?? '' }} Real Estate</span>
      </span>
    </a>
    @if($profile->contact_email)
      <a href="mailto:{{ $profile->contact_email }}" class="cta-btn">Contact Agency</a>
    @endif
  </div>

  <!-- Hero -->
  <section class="hero">
    <div class="eyebrow">{{ $campaign->country ?? 'Real Estate' }} · Local Expert</div>
    <h1>{{ $campaign->name }}</h1>
    <p>{{ $heroText ?? ($campaign->positioning_note ?? 'Explore real estate opportunities in ' . $campaign->primary_city . ' and the surrounding area. Expert local guidance for buyers, sellers, and investors.') }}</p>
  </section>

  <!-- Context Bar -->
  <section class="context">
    <div class="meta"><b>Market</b><span>{{ $campaign->primary_city ?? '—' }}</span></div>
    <div class="meta"><b>Coverage</b><span>{{ $campaign->coverage_area ? $campaign->coverage_area . ' ' . $campaign->coverage_unit : '—' }}</span></div>
    <div class="meta"><b>Areas</b><span>{{ count($targetPlaces) }} locations</span></div>
    <div class="meta"><b>Listings</b><span>{{ $listings->count() }} active</span></div>
    <div class="meta"><b>Language</b><span>{{ $profile->ai_content_language ?? 'English' }}</span></div>
  </section>

  <div class="cards">

    <!-- Areas Card -->
    @if(count($targetPlaces) > 0)
    <article class="card">
      <div class="card-head">
        <div>
          <h2><span class="card-number">1</span>Areas We Cover</h2>
          <p class="lead">Key locations around {{ $campaign->primary_city }} where we help buyers find their perfect property.</p>
        </div>
        <span class="output">{{ count($targetPlaces) }} locations</span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Location</th><th>Type</th><th>Distance</th><th>Why It Matters</th><th>Priority</th></tr>
          </thead>
          <tbody>
            @foreach($targetPlaces as $index => $place)
            @php $aiDesc = $areaDescriptions[$index]['description'] ?? null; @endphp
            <tr>
              <td><b>{{ $place['name'] ?? '' }}</b></td>
              <td>{{ $place['type'] ?? '—' }}</td>
              <td>{{ $place['distance'] ?? '—' }}</td>
              <td>{{ $aiDesc ?? ($place['reason'] ?? '—') }}</td>
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

    <!-- Listings Card -->
    @if($listings->count() > 0)
    <article class="card">
      <div class="card-head">
        <div>
          <h2><span class="card-number">2</span>Featured Properties</h2>
          <p class="lead">Handpicked listings in {{ $campaign->primary_city }} and surrounding areas.</p>
        </div>
        <span class="output">{{ $listings->count() }} listings</span>
      </div>
      <div class="listings-grid">
        @foreach($listings->take(6) as $listing)
        <div class="listing-card">
          @if($listing->images && count($listing->images) > 0)
            <img src="{{ asset('storage/' . $listing->images[0]) }}" alt="{{ $listing->title }}" class="listing-img">
          @else
            <div class="listing-img" style="display:grid;place-items:center;background:var(--ink);color:rgba(255,255,255,.5);font-size:12px;font-weight:700;">NO IMAGE</div>
          @endif
          <div class="listing-body">
            <h3>{{ $listing->title }}</h3>
            <div class="listing-meta">
              <span>{{ $listing->location ?? '' }}</span>
              @if($listing->bedrooms)<span>{{ $listing->bedrooms }} bed</span>@endif
            </div>
            <div class="listing-price">{{ $listing->formatted_price ?? '—' }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </article>
    @endif

    <!-- FAQ Card -->
    @php
      $faqs = !empty($faqContent) ? $faqContent : [
        ['question' => 'What types of properties are available in ' . $campaign->primary_city . '?', 'answer' => 'We offer apartments, villas, houses, and land plots throughout the area.'],
        ['question' => 'How do I schedule a property viewing?', 'answer' => 'Contact us via email or phone and we will arrange a convenient time.'],
        ['question' => 'Do you assist international buyers?', 'answer' => 'Yes, we provide full support including legal guidance and documentation.'],
      ];
    @endphp
    <article class="card">
      <div class="card-head">
        <div>
          <h2><span class="card-number">3</span>Frequently Asked Questions</h2>
          <p class="lead">Common questions about buying property in {{ $campaign->primary_city }}.</p>
        </div>
        <span class="output">{{ count($faqs) }} questions</span>
      </div>
      <div class="faq-list">
        @foreach($faqs as $faq)
        <div class="faq-item">
          <button class="faq-q" onclick="this.parentElement.classList.toggle('open')">{{ $faq['question'] ?? '' }}</button>
          <div class="faq-a"><p class="faq-a-inner">{{ $faq['answer'] ?? '' }}</p></div>
        </div>
        @endforeach
      </div>
    </article>

    <!-- About Card -->
    <article class="card">
      <div class="card-head">
        <div>
          <h2><span class="card-number">4</span>About {{ $profile->agency_name }}</h2>
          <p class="lead">Your trusted local real estate partner.</p>
        </div>
        <span class="output">Local Expert</span>
      </div>
      <p style="font-size:15px;color:var(--muted);line-height:1.7;margin:0 0 20px;">
        {{ $aboutText ?? ($profile->agency_name . ' is a trusted real estate agency serving ' . $campaign->primary_city . ' and the surrounding ' . ($campaign->coverage_area ?? '') . ' ' . ($campaign->coverage_unit ?? 'km') . ' area. We combine local market expertise with personalized service to help you find the perfect property.') }}
      </p>
      <div class="inline-cards">
        <div class="mini">
          <strong>Local Expertise</strong>
          <p>Deep knowledge of {{ $campaign->primary_city }} real estate market and trends.</p>
        </div>
        <div class="mini">
          <strong>Trusted Guidance</strong>
          <p>Professional support through every step of your property journey.</p>
        </div>
        <div class="mini">
          <strong>{{ count($targetPlaces) }}+ Areas</strong>
          <p>Coverage across key locations in the region.</p>
        </div>
        <div class="mini">
          <strong>{{ $listings->count() }} Listings</strong>
          <p>Active properties ready for viewing.</p>
        </div>
      </div>
    </article>

  </div>

  <!-- CTA Band -->
  <section class="cta-band">
    <div>
      <h3>Ready to Find Your Property?</h3>
      <p>Contact {{ $profile->agency_name }} today for expert guidance on real estate in {{ $campaign->primary_city }}.</p>
    </div>
    <div class="cta-actions">
      @if($profile->contact_email)
        <a href="mailto:{{ $profile->contact_email }}" class="btn-white">Get in Touch</a>
      @endif
      @if($profile->contact_phone)
        <a href="tel:{{ $profile->contact_phone }}" class="btn-ghost">Call Us</a>
      @endif
    </div>
  </section>

  <footer>
    © {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.
  </footer>

</main>
</body>
</html>
