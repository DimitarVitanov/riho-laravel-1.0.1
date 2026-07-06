<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $page->meta_description ?? $campaign->positioning_note }}" />
  <title>{{ $page->seo_title ?? $campaign->name }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f5f7fb;
      --card: #ffffff;
      --ink: #0f172a;
      --body: #3e4348;
      --muted: #74839a;
      --line: #e5eaf1;
      --accent: {{ $profile->brand_primary_color ?? '#0d8d8c' }};
      --accent-dark: {{ $profile->brand_secondary_color ?? '#086f70' }};
      --accent-soft: {{ $profile->brand_primary_color ? $profile->brand_primary_color . '14' : '#eef9f9' }};
      --gold: #ffb31a;
      --black: #0a0a0a;
      --shadow: 0 10px 30px rgba(15,23,42,.06);
      --radius: 14px;
      --header-h: 72px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Nunito', 'Inter', ui-sans-serif, system-ui, sans-serif;
      color: var(--body);
      background: var(--bg);
      line-height: 1.62;
      font-size: 17px;
    }
    a { color: inherit; text-decoration: none; }
    button, input { font: inherit; }

    /* ── HEADER ── */
    .header {
      position: sticky;
      top: 0;
      z-index: 50;
      background: rgba(255,255,255,.96);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.04);
    }
    .container { width: min(1800px, calc(100% - 36px)); margin: 0 auto; }
    .container-wide { width: min(1800px, calc(100% - 36px)); margin: 0 auto; }
    .header-inner {
      min-height: var(--header-h);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .brand { display: inline-flex; align-items: center; gap: 14px; }
    .brand-icon {
      width: 44px; height: 44px;
      background: linear-gradient(135deg, var(--accent-dark), var(--accent));
      border-radius: 12px;
      display: grid; place-items: center;
      color: #fff;
      font-size: 20px;
      font-weight: 900;
      flex-shrink: 0;
    }
    .brand-text { display: flex; flex-direction: column; line-height: 1.1; }
    .brand-text b { font-size: 16px; letter-spacing: -.02em; color: var(--ink); font-weight: 900; }
    .brand-text span { font-size: 11px; color: var(--muted); font-weight: 700; }
    .header-actions { display: flex; align-items: center; gap: 10px; }
    .primary-btn {
      min-height: 42px;
      display: inline-flex; align-items: center; justify-content: center;
      padding: 0 18px;
      border-radius: 11px;
      border: 1.5px solid var(--accent);
      background: var(--accent);
      color: #fff;
      font-weight: 800;
      font-size: 13.5px;
      cursor: pointer;
      white-space: nowrap;
    }
    .primary-btn:hover { background: var(--accent-dark); border-color: var(--accent-dark); }

    /* ── MAIN GRID ── */
    .page { padding: 28px 0 48px; }
    .grid { display: grid; grid-template-columns: repeat(12, minmax(0,1fr)); gap: 20px; }
    .card {
      position: relative;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: var(--card);
      box-shadow: var(--shadow);
      overflow: hidden;
    }
    .card-pad { padding: 30px; }
    .span-12 { grid-column: span 12; }
    .span-6 { grid-column: span 6; }
    .span-4 { grid-column: span 4; }
    .span-3 { grid-column: span 3; }

    .number-label {
      color: var(--accent-dark);
      font-size: 11px;
      font-weight: 900;
      letter-spacing: .13em;
      display: inline-flex; align-items: center; gap: 7px;
      text-transform: uppercase;
    }
    .number-label::before {
      content: "";
      display: inline-block;
      width: 7px; height: 7px;
      border-radius: 50%;
      background: var(--gold);
    }

    /* ── HERO ── */
    .hero-title {
      margin: 12px 0 14px;
      font-size: clamp(32px, 4vw, 54px);
      line-height: 1.06;
      letter-spacing: -1.6px;
      font-weight: 900;
      color: var(--ink);
    }
    .hero-copy {
      font-size: 18px;
      color: var(--body);
      max-width: 820px;
      line-height: 1.7;
    }
    .query-box {
      display: grid;
      grid-template-columns: minmax(0,1fr) auto;
      gap: 10px;
      padding: 8px;
      border: 1.5px solid #dfe7ef;
      border-radius: 13px;
      background: #fbfcfe;
      margin-top: 22px;
    }
    .query-box input {
      min-width: 0;
      height: 46px;
      padding: 0 14px;
      border: 0; outline: 0;
      background: transparent;
      color: var(--ink);
      font-size: 15px;
    }
    .query-box input::placeholder { color: #94a3b8; }
    .submit-arrow {
      width: 46px; height: 46px;
      display: grid; place-items: center;
      border: 0; border-radius: 10px;
      background: var(--accent); color: #fff;
      font-size: 20px; font-weight: 900; cursor: pointer;
    }
    .submit-arrow:hover { background: var(--accent-dark); }

    /* ── COPY STACK ── */
    .copy-stack p { color: var(--body); font-size: 17px; line-height: 1.72; }
    .copy-stack p + p { margin-top: 14px; }

    /* ── FOCUS AREAS ── */
    .focus-card h2 { margin: 12px 0 0; font-size: 28px; line-height: 1.14; letter-spacing: -.6px; color: var(--ink); }
    .focus-copy { color: var(--body); font-size: 17px; margin-top: 10px; line-height: 1.72; }
    .areas-list {
      margin-top: 22px;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 14px;
    }
    .area-card {
      position: relative;
      min-height: 190px;
      padding: 20px;
      display: flex;
      align-items: flex-start;
      gap: 15px;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      background: #ffffff;
      overflow: hidden;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
      text-decoration: none;
      color: inherit;
    }
    .area-card:hover {
      transform: translateY(-4px);
      border-color: var(--black);
      box-shadow: 0 14px 28px rgba(10,10,10,.12);
    }
    .area-card::after {
      content: "";
      position: absolute;
      right: -28px;
      bottom: -30px;
      width: 110px;
      height: 110px;
      border: 18px solid rgba(10,10,10,.035);
      border-radius: 50%;
    }
    .area-icon {
      width: 54px; height: 54px;
      flex: 0 0 54px;
      display: grid; place-items: center;
      border-radius: 14px;
      background: var(--black);
      color: var(--gold);
      box-shadow: 0 7px 16px rgba(10,10,10,.15);
    }
    .area-icon svg { width: 26px; height: 26px; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }
    .area-content { position: relative; z-index: 1; }
    .area-kicker {
      display: block;
      margin-bottom: 8px;
      color: var(--black);
      font-size: 10px;
      font-weight: 900;
      letter-spacing: .1em;
      text-transform: uppercase;
    }
    .area-content h3 {
      margin: 0;
      color: var(--ink);
      font-size: 15px;
      line-height: 1.22;
      font-weight: 900;
      letter-spacing: -.25px;
    }
    .area-content p {
      margin: 10px 0 15px;
      color: #64748b;
      font-size: 13px;
      line-height: 1.45;
    }
    .area-link {
      color: var(--black);
      font-size: 13px;
      font-weight: 900;
    }
    .area-link b { margin-left: 5px; color: var(--gold); font-size: 17px; }

    /* ── LISTING CARDS ── */
    .listing-card {
      position: relative;
      border: 1px solid #e2e8f0;
      border-radius: var(--radius);
      background: var(--card);
      overflow: hidden;
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    }
    .listing-card:hover {
      transform: translateY(-4px);
      border-color: var(--black);
      box-shadow: 0 14px 28px rgba(10,10,10,.12);
    }
    .listing-card::after {
      content: "";
      position: absolute;
      right: -28px;
      bottom: -30px;
      width: 110px;
      height: 110px;
      border: 18px solid rgba(10,10,10,.035);
      border-radius: 50%;
      pointer-events: none;
    }
    .listing-img {
      width: 100%;
      aspect-ratio: 16 / 10;
      background: #e5eaf1;
      object-fit: cover;
      display: block;
    }
    .listing-img.empty {
      display: grid; place-items: center;
      color: var(--muted);
      font-size: 13px;
      font-weight: 700;
    }
    .listing-body { position: relative; z-index: 1; padding: 20px; flex: 1; display: flex; flex-direction: column; }
    .listing-body h3 {
      font-size: 17px;
      font-weight: 900;
      color: var(--ink);
      margin-bottom: 8px;
      line-height: 1.25;
    }
    .listing-meta {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 12px;
      font-size: 13px;
      color: var(--muted);
      font-weight: 700;
    }
    .listing-meta .badge {
      background: var(--accent-soft);
      color: var(--accent-dark);
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 900;
      text-transform: uppercase;
      letter-spacing: .04em;
    }
    .listing-body p {
      font-size: 14.5px;
      line-height: 1.6;
      color: var(--body);
      margin-bottom: 14px;
      flex: 1;
    }
    .listing-price {
      font-size: 18px;
      font-weight: 900;
      color: var(--ink);
      margin-bottom: 12px;
    }
    .listing-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 16px;
      border-radius: 10px;
      background: var(--accent);
      color: #fff;
      font-size: 13px;
      font-weight: 800;
      transition: background .15s;
    }
    .listing-btn:hover { background: var(--accent-dark); }

    /* ── TOPIC CARDS ── */
    .topic-card::after {
      content: attr(data-no);
      position: absolute; right: 16px; bottom: -36px;
      color: #f0f4f8;
      font-size: 142px; line-height: 1; font-weight: 900; letter-spacing: -8px;
      pointer-events: none;
    }
    .topic-head {
      display: flex; align-items: center; gap: 14px;
      padding-bottom: 18px;
      border-bottom: 1px solid var(--line);
    }
    .topic-number {
      width: 48px; height: 48px; flex: 0 0 48px;
      display: grid; place-items: center;
      border-radius: 13px;
      background: var(--black);
      color: var(--gold);
      box-shadow: 0 7px 16px rgba(10,10,10,.15);
    }
    .topic-number svg { width: 24px; height: 24px; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }
    .topic-head h2 { margin: 2px 0 0; font-size: 22px; line-height: 1.2; letter-spacing: -.5px; color: var(--ink); }
    .topic-card .copy-stack { padding-top: 20px; }
    .question-line {
      margin: 18px 0 0; padding: 13px 15px;
      border-left: 3px solid var(--gold);
      border-radius: 0 9px 9px 0;
      color: #1e3a5f; background: #fff8e7;
      font-size: 14.5px; font-weight: 900; line-height: 1.5;
    }

    /* ── FOOTER ── */
    footer {
      background: #12141c;
      color: rgba(255,255,255,0.72);
      font-size: 16px;
      position: relative;
      overflow: hidden;
    }
    footer::before {
      content: "";
      position: absolute;
      inset: 0;
      opacity: .25;
      pointer-events: none;
      background: radial-gradient(circle at 20% 20%, rgba(255,255,255,.03) 0%, transparent 25%),
                  radial-gradient(circle at 80% 80%, rgba(255,255,255,.03) 0%, transparent 25%);
      background-size: 60px 60px;
    }
    .footer-top {
      padding: 48px 0 36px;
      border-bottom: 1px solid rgba(255,255,255,.08);
      position: relative;
      z-index: 1;
    }
    .footer-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1.2fr;
      gap: 40px;
    }
    .footer-brand .brand-icon {
      width: 44px; height: 44px;
      background: linear-gradient(135deg, var(--accent), var(--accent-dark));
      border-radius: 12px;
      display: grid; place-items: center;
      color: #fff;
      font-size: 22px; margin-bottom: 14px;
    }
    .footer-brand p { font-size: 13.5px; line-height: 1.65; max-width: 280px; color: rgba(255,255,255,.55); margin-top: 10px; }
    .footer-col h4 { color: #fff; font-size: 13px; font-weight: 900; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 18px; }
    .footer-col ul { list-style: none; padding: 0; display: grid; gap: 10px; }
    .footer-col ul li { display: flex; align-items: center; gap: 10px; }
    .footer-col ul li::before { content: ">"; color: rgba(255,255,255,.35); font-size: 11px; font-weight: 900; }
    .footer-col ul li a { color: rgba(255,255,255,.55); font-size: 13.5px; font-weight: 600; }
    .footer-col ul li a:hover { color: var(--gold); }
    .footer-bottom {
      padding: 18px 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px; flex-wrap: wrap;
      position: relative;
      z-index: 1;
    }
    .footer-bottom p { color: rgba(255,255,255,.35); font-size: 12.5px; }
    .footer-bottom-links { display: flex; gap: 20px; }
    .footer-bottom-links a { color: rgba(255,255,255,.35); font-size: 12.5px; font-weight: 600; }
    .footer-bottom-links a:hover { color: rgba(255,255,255,.7); }

    .taxi-checker-line {
      width: 100%;
      height: 12px;
      background:
        conic-gradient(
          #ffffff 25%,
          #0a0b0c 0 50%,
          #ffffff 0 75%,
          #0a0b0c 0
        ) 0 0 / 12px 12px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1200px) {
      .areas-list { grid-template-columns: repeat(2,minmax(0,1fr)); }
      .area-card { min-height: auto; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 1024px) {
      .span-6, .span-4, .span-3 { grid-column: span 12; }
      .listing-img { aspect-ratio: 16 / 9; }
    }
    @media (max-width: 768px) {
      .footer-grid { grid-template-columns: 1fr; gap: 28px; }
      .header-actions { display: none; }
    }
    @media (max-width: 680px) {
      .container { width: min(100% - 22px, 1200px); }
      .page { padding-top: 18px; }
      .card-pad { padding: 22px; }
      .hero-title { font-size: 30px; letter-spacing: -1px; }
      .query-box { grid-template-columns: 1fr; }
      .submit-arrow { width: 100%; border-radius: 10px; }
      .areas-list { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  {{-- HEADER --}}
  <header class="header">
    <div class="container-wide header-inner">
      <a class="brand" href="/">
        @if($profile->agency_logo_path)
          <img src="{{ asset('storage/' . $profile->agency_logo_path) }}" alt="{{ $profile->agency_name }}" height="44">
        @else
          <span class="brand-icon">{{ strtoupper(substr($profile->agency_name, 0, 1)) }}</span>
        @endif
        <span class="brand-text">
          <b>{{ $profile->agency_name }}</b>
          <span>{{ $profile->city ?? $campaign->primary_city }} Real Estate</span>
        </span>
      </a>
      <div class="header-actions">
        <a href="mailto:{{ $profile->contact_email }}" class="primary-btn">Contact Agency</a>
      </div>
    </div>
  </header>

  {{-- MAIN --}}
  <main class="page" id="top">
    <div class="container">
      <div class="grid">

        {{-- HERO + ASK AI --}}
        <section class="card ask-card span-12">
          <div class="card-pad">
            <span class="number-label">Local Market</span>
            <h1 class="hero-title">{{ $campaign->name }}</h1>
            <p class="hero-copy">
              {{ $campaign->positioning_note ?: 'Explore real estate opportunities in ' . $campaign->primary_city . ' and the surrounding area. We cover the places that matter most for buyers, sellers, and investors.' }}
            </p>

            <span class="number-label" style="margin-top:32px;display:block;">Ask AI</span>
            <h2 style="font-size: clamp(24px, 3vw, 34px); font-weight: 900; color: var(--ink); margin: 12px 0 16px;">Ask anything about {{ $campaign->primary_city }} real estate</h2>
            <form class="query-box" onsubmit="event.preventDefault(); alert('AI agent coming soon.');">
              <input type="text" placeholder="What property types are available in {{ $campaign->primary_city }}?" aria-label="Ask about {{ $campaign->primary_city }} real estate" />
              <button class="submit-arrow" type="submit" aria-label="Submit">↗</button>
            </form>
            <p class="report-note" style="margin-top:14px; color: var(--body); font-size: 14px; line-height: 1.6;">
              Villa Bit AI will analyze your question and prepare a report based on this market.
            </p>
          </div>
        </section>

        {{-- WHY THIS MARKET --}}
        <section class="card span-6">
          <div class="card-pad">
            <span class="number-label">Why {{ $campaign->primary_city }}</span>
            <div class="copy-stack" style="padding-top:16px;">
              <p>{{ $campaign->primary_city }} is the center of this campaign. We track listings, market signals, and buyer interest across the surrounding region.</p>
              <p>Whether you are looking for a family home, a rental investment, or a holiday property, we focus on the locations that match your goals.</p>
            </div>
          </div>
        </section>

        {{-- COVERAGE MAP CARD --}}
        <section class="card span-6">
          <div class="card-pad" style="min-height: 100%; display: flex; flex-direction: column; justify-content: center;">
            <span class="number-label">Coverage Area</span>
            <h2 style="font-size: 24px; font-weight: 900; color: var(--ink); margin: 12px 0 14px;">{{ $campaign->coverage_area }} {{ $campaign->coverage_unit }} around {{ $campaign->primary_city }}</h2>
            <div class="copy-stack">
              <p>Our campaign targets places within {{ $campaign->coverage_area }} {{ $campaign->coverage_unit }} of {{ $campaign->primary_city }}. This helps us reach buyers and sellers searching in the wider market, not only the city center.</p>
            </div>
          </div>
        </section>

        {{-- FOCUS AREAS --}}
        @php $targetPlaces = $campaign->target_places ?? []; @endphp
        @if(count($targetPlaces) > 0)
        <section class="card focus-card span-12">
          <div class="card-pad">
            <span class="number-label">Nearby Areas</span>
            <h2>Areas we cover around {{ $campaign->primary_city }}</h2>
            <p class="focus-copy">These nearby places are included in our local SEO campaign. Click any area to explore relevant properties and market information.</p>
            <div class="areas-list">
              @foreach($targetPlaces as $place)
              <div class="area-card">
                <div class="area-icon">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                  </svg>
                </div>
                <div class="area-content">
                  <span class="area-kicker">{{ strtoupper($place['type'] ?? 'Area') }}</span>
                  <h3>{{ $place['name'] ?? '' }}</h3>
                  <p>{{ $place['reason'] ?? ('Approximately ' . ($place['distance'] ?? '') . ' from ' . $campaign->primary_city) }}</p>
                  <span class="area-link">Explore <b>→</b></span>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </section>
        @endif

        {{-- FEATURED LISTINGS --}}
        @php $listings = $campaign->listings()->where('status', 'active')->latest()->get(); @endphp
        @if($listings->isNotEmpty())
        <section class="span-12" style="margin-top: 10px;">
          <span class="number-label" style="margin-bottom: 14px; display: inline-block;">Featured Listings</span>
          <div class="grid">
            @foreach($listings as $listing)
            <article class="listing-card span-4">
              @if(!empty($listing->images[0]))
                <img class="listing-img" src="{{ $listing->images[0] }}" alt="{{ $listing->title }}" loading="lazy">
              @else
                <div class="listing-img empty">No image</div>
              @endif
              <div class="listing-body">
                <h3>{{ $listing->title }}</h3>
                <div class="listing-meta">
                  <span class="badge">{{ $listing->property_type ?: 'Property' }}</span>
                  <span>{{ $listing->location ?? $campaign->primary_city }}</span>
                </div>
                <p>{{ \Illuminate\Support\Str::limit($listing->description, 140) }}</p>
                @if($listing->formatted_price)
                  <div class="listing-price">{{ $listing->formatted_price }}</div>
                @endif
                <a href="mailto:{{ $profile->contact_email }}?subject=Inquiry: {{ urlencode($listing->title) }}" class="listing-btn">Request details</a>
              </div>
            </article>
            @endforeach
          </div>
        </section>
        @endif

        {{-- ABOUT THE AGENCY --}}
        <section class="card topic-card span-6" data-no="01">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
              </span>
              <h2>About {{ $profile->agency_name }}</h2>
            </div>
            <div class="copy-stack">
              <p>{{ $profile->agency_name }} is a real estate agency focused on {{ $campaign->primary_city }} and the surrounding region.</p>
              <p>We combine local market knowledge with AI-powered tools to help buyers and sellers make better decisions.</p>
            </div>
            <p class="question-line">How can we help you find the right property in {{ $campaign->primary_city }}?</p>
          </div>
        </section>

        {{-- CONTACT TOPIC --}}
        <section class="card topic-card span-6" data-no="02">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v16H4z"/><path d="M4 8l8 5 8-5"/></svg>
              </span>
              <h2>Get in Touch</h2>
            </div>
            <div class="copy-stack">
              <p>Ready to explore {{ $campaign->primary_city }} real estate? Reach out and we will guide you through the market.</p>
            </div>
            <p class="question-line">{{ $profile->contact_email ? 'Email: ' . $profile->contact_email : '' }} {{ $profile->contact_phone ? '· Phone: ' . $profile->contact_phone : '' }}</p>
          </div>
        </section>

      </div>
    </div>
  </main>

  {{-- CHECKERBOARD TAXI STRIP --}}
  <div class="taxi-checker-line"></div>

  {{-- FOOTER --}}
  <footer>
    <div class="footer-top">
      <div class="container-wide">
        <div class="footer-grid">
          <div class="footer-brand">
            <div class="brand-icon">{{ strtoupper(substr($profile->agency_name, 0, 1)) }}</div>
            <p>{{ $profile->agency_name }} — Local real estate expertise in {{ $campaign->primary_city }} and nearby areas.</p>
          </div>
          <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
              <li><a href="#top">Home</a></li>
              <li><a href="#" onclick="alert('Listings page coming soon.'); return false;">Listings</a></li>
              <li><a href="#" onclick="alert('AI Report coming soon.'); return false;">AI Report</a></li>
            </ul>
          </div>
          <div class="footer-col">
            <h4>Contact</h4>
            <ul>
              @if($profile->contact_email)<li><a href="mailto:{{ $profile->contact_email }}">{{ $profile->contact_email }}</a></li>@endif
              @if($profile->contact_phone)<li><a href="tel:{{ $profile->contact_phone }}">{{ $profile->contact_phone }}</a></li>@endif
              @if($profile->city || $profile->country)<li><a href="#">{{ $profile->city }}{{ $profile->city && $profile->country ? ', ' : '' }}{{ $profile->country }}</a></li>@endif
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container-wide">
      <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Use</a>
        </div>
      </div>
    </div>
  </footer>

</body>
</html>
