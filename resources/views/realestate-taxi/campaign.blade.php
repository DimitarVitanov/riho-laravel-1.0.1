<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="{{ $page->meta_description ?? $campaign->positioning_note }}">
<title>{{ $page->seo_title ?? $campaign->name }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root {
  --ink: #0a0b0c;
  --soft: #f5f6f7;
  --line: #dde1e5;
  --muted: #69717a;
  --accent: {{ $profile->brand_primary_color ?? '#0a0b0c' }};
  --accent-dark: {{ $profile->brand_secondary_color ?? '#000000' }};
  --accent-soft: #f1f3f5;
  --pop: #eef1f4;
  --warn: #8a6100;
  --warn-soft: #fff5d8;
  --low: #eef1f4;
  --white: #ffffff;
}
* { box-sizing: border-box; }
body {
  margin: 0;
  background: #eef0f2;
  color: var(--ink);
  font-family: Inter, Montserrat, Arial, sans-serif;
  line-height: 1.45;
}
a { color: inherit; text-decoration: none; }
button, input { font: inherit; }

.page { max-width: 1560px; margin: 0 auto; padding: 24px 24px 64px; }

/* ── TOP BAR ── */
.topbar {
  display: flex; align-items: center; justify-content: space-between; gap: 16px;
  margin-bottom: 22px;
}
.brand { display: inline-flex; align-items: center; gap: 12px; }
.brand-icon {
  width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
  display: grid; place-items: center;
  background: linear-gradient(135deg, var(--accent-dark), var(--accent));
  color: #fff; font-weight: 900; font-size: 19px;
}
.brand b { display: block; font-size: 16px; font-weight: 900; letter-spacing: -.01em; }
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

  {{-- HERO --}}
  <section class="hero">
    <div class="eyebrow">Local Real Estate{{ $campaign->country ? ' — ' . $campaign->country : '' }}</div>
    <h1>{{ $campaign->name }}</h1>
    <p>{{ $campaign->positioning_note ?: 'Explore real estate opportunities in ' . $campaign->primary_city . ' and the surrounding area. We cover the places that matter most for buyers, sellers, and investors.' }}</p>
  </section>

  {{-- CONTEXT META BAR --}}
  @php $listings = $campaign->listings()->where('status', 'active')->latest()->get(); @endphp
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
            @foreach($targetPlaces as $place)
            <tr>
              <td><b>{{ $place['name'] ?? '' }}</b></td>
              <td>{{ $place['type'] ?? '—' }}</td>
              <td>{{ $place['distance'] ?? '—' }}</td>
              <td>{{ $place['reason'] ?? '—' }}</td>
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

    {{-- FEATURED LISTINGS --}}
    @if($listings->isNotEmpty())
    <article class="step">
      <div class="step-head">
        <div>
          <h2><span class="step-number">2</span>Featured properties in {{ $campaign->primary_city }}</h2>
          <p class="lead">A selection of properties from the {{ $campaign->name }} campaign.</p>
        </div>
        <div class="output">OUTPUT → {{ $listings->count() }} listings</div>
      </div>
      <div class="listing-grid">
        @foreach($listings as $listing)
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
        <p>{{ $profile->agency_name }} is a real estate agency focused on {{ $campaign->primary_city }} and the surrounding region. We track listings, market signals, and buyer interest across the wider market — helping you make better decisions with clear, local knowledge.</p>
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
  </div>

  <footer>
    &copy; {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.
  </footer>

</main>
</body>
</html>
