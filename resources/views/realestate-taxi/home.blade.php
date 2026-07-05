<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Real Estate Taxi helps anyone profit from real estate, even without owning property." />
  <title>Real Estate Taxi — Free Global Real Estate Market Tools</title>
  <style>
    :root {
      --bg: #f5f7fb;
      --card: #ffffff;
      --ink: #0f172a;
      --body: #3e4348;
      --muted: #74839a;
      --line: #e5eaf1;
      --teal: #0d8d8c;
      --teal-dark: #086f70;
      --teal-soft: #eef9f9;
      --gold: #ffb31a;
      --black: #0a0a0a;
      --shadow: 0 10px 30px rgba(15,23,42,.06);
      --radius: 14px;
      --header-h: 84px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: Nunito, Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      color: var(--body);
      background: var(--bg);
      line-height: 1.62;
      font-size: 17px;
    }
    a { color: inherit; text-decoration: none; }
    button, input { font: inherit; }

    /* ── TOP STRIP ── */
    .top-strip {
      background: var(--black);
      color: rgba(255,255,255,0.75);
      font-size: 12.5px;
      font-weight: 600;
      border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .top-strip-inner {
      width: min(1420px, calc(100% - 36px));
      margin: 0 auto;
      min-height: 36px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }
    .top-strip-left { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .top-strip-left .badge {
      background: var(--gold);
      color: #0a0a0a;
      font-size: 11px;
      font-weight: 900;
      padding: 2px 8px;
      border-radius: 5px;
      letter-spacing: .04em;
    }
    .top-strip-right { display: flex; align-items: center; gap: 18px; white-space: nowrap; }
    .top-strip-right a { color: rgba(255,255,255,0.65); font-size: 12px; font-weight: 600; }
    .top-strip-right a:hover { color: #fff; }
    .strip-sep { color: rgba(255,255,255,.2); }
    .strip-select {
      background: rgba(255,255,255,.08);
      color: rgba(255,255,255,.85);
      border: 1px solid rgba(255,255,255,.14);
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      padding: 3px 6px;
      cursor: pointer;
      outline: none;
    }
    .strip-select:hover { background: rgba(255,255,255,.14); color: #fff; }
    .strip-select option { color: #0a0a0a; }

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
    .header-inner {
      min-height: var(--header-h);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .brand { display: inline-flex; align-items: center; gap: 14px; min-width: max-content; }
    .brand-icon {
      width: 46px; height: 46px;
      background: linear-gradient(135deg, #0f2027, #1a3a3a);
      border-radius: 12px;
      display: grid; place-items: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .brand-text { display: flex; flex-direction: column; line-height: 1.1; }
    .brand-text b { font-size: 17px; letter-spacing: -.02em; color: #0f172a; font-weight: 900; }
    .brand-text span { font-size: 11.5px; color: var(--muted); font-weight: 700; }
    .nav { display: flex; align-items: center; gap: 6px; flex: 1; justify-content: center; }
    .nav a {
      color: #1f2937;
      font-size: 13.5px;
      font-weight: 800;
      white-space: nowrap;
      padding: 6px 12px;
      border-radius: 8px;
      display: inline-flex; align-items: center; gap: 4px;
      transition: background .15s;
    }
    .nav a:hover { background: #f1f5f9; color: var(--teal-dark); }
    .nav a .arr { font-size: 10px; color: #94a3b8; }
    .header-actions { display: flex; align-items: center; gap: 10px; }
    .ghost-btn, .primary-btn, .menu-btn {
      min-height: 42px;
      display: inline-flex; align-items: center; justify-content: center;
      padding: 0 18px;
      border-radius: 11px;
      border: 1.5px solid var(--line);
      background: #fff;
      color: var(--ink);
      font-weight: 800;
      font-size: 13.5px;
      cursor: pointer;
      white-space: nowrap;
    }
    .primary-btn { border-color: var(--teal); background: var(--teal); color: #fff; }
    .primary-btn:hover { background: var(--teal-dark); border-color: var(--teal-dark); }
    .ghost-btn:hover { border-color: #cbd5e1; background: #f8fafc; }
    .menu-btn { display: none; width: 42px; padding: 0; font-size: 20px; border: 1.5px solid var(--line); }
    .drawer { display: none; }

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
    .span-8  { grid-column: span 8; }
    .span-4  { grid-column: span 4; }
    .span-6  { grid-column: span 6; }
    .span-5  { grid-column: span 5; }
    .span-7  { grid-column: span 7; }

    .number-label {
      color: var(--teal-dark);
      font-size: 11px;
      font-weight: 900;
      letter-spacing: .13em;
      display: inline-flex; align-items: center; gap: 7px;
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
      font-size: clamp(34px, 4vw, 58px);
      line-height: 1.06;
      letter-spacing: -1.8px;
      font-weight: 900;
      color: #0f172a;
    }

    .hero-title-2{
      margin: 12px 0 14px;
      font-size: clamp(30px, 3.4vw, 48px);
      line-height: 1.08;
      letter-spacing: -1.6px;
      font-weight: 900;
      color:#0f172a;
    }
    .hero-copy {
      font-size: 18px;
      color: #3e4348;
      max-width: 820px;
      line-height: 1.7;
    }

    /* ── ASK CARD ── */
    .ask-card h2 { margin: 12px 0 20px; font-size: 28px; line-height: 1.14; letter-spacing: -.7px; color: #0f172a; }
    .query-box {
      display: grid;
      grid-template-columns: minmax(0,1fr) auto;
      gap: 10px;
      padding: 8px;
      border: 1.5px solid #dfe7ef;
      border-radius: 13px;
      background: #fbfcfe;
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
      background: var(--teal); color: #fff;
      font-size: 20px; font-weight: 900; cursor: pointer;
    }
    .submit-arrow:hover { background: var(--teal-dark); }
    .report-note { margin: 14px 0 0; color: #3e4348; font-size: 14px; line-height: 1.6; }

    /* ── AI IMAGE CARD ── */
    .ai-img-card {
      min-height: 100%;
      border: 0;
      overflow: hidden;
      background: #0a0a0a;
    }
    .ai-img-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      min-height: 300px;
    }

    /* ── COPY STACK ── */
    .copy-stack p { color: #3e4348; font-size: 17px; line-height: 1.72; }
    .copy-stack p + p { margin-top: 14px; }

    /* ── PURPOSE CARD ── */
    .purpose-card { background: #0f172a; }
    .purpose-card::after {
      content: "+";
      position: absolute; right: 14px; top: -16px;
      color: rgba(255,255,255,.05);
      font-size: 145px; line-height: 1; font-weight: 900;
    }
    .purpose-card p {
      position: relative; z-index: 1;
      color: #f1f5f9;
      font-size: 19px; font-weight: 900; line-height: 1.45;
    }
    .purpose-card p strong { color: var(--gold); }

    /* ── FOCUS CARD ── */
    .focus-card h2 { margin: 12px 0 0; font-size: 30px; line-height: 1.14; letter-spacing: -.7px; color: #0f172a; }
    .focus-copy { color: #3e4348; font-size: 17px; margin-top: 10px; line-height: 1.72; }
    .areas-list {
      margin: 22px 0 0; padding: 0; list-style: none;
      display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 14px;
    }
    .areas-list li {
      padding: 18px;
      border: 1.5px solid var(--line);
      border-radius: 12px;
      background: #fcfdfe;
      min-height: 120px;
    }
    .areas-list b { display: block; margin-bottom: 10px; color: var(--teal-dark); font-size: 11px; letter-spacing: .07em; font-weight: 900; }
    .areas-list span { display: block; color: #1e3a5f; font-size: 13.5px; line-height: 1.48; font-weight: 900; }

    /* ── TOPIC CARDS ── */
    .topic-card::after {
      content: attr(data-no);
      position: absolute; right: 16px; bottom: -36px;
      color: #f0f4f8;
      font-size: 142px; line-height: 1; font-weight: 900; letter-spacing: -8px;
      pointer-events: none;
    }
    .topic-head {
      display: flex; align-items: flex-start; gap: 14px;
      padding-bottom: 18px;
      border-bottom: 1px solid var(--line);
    }
    .topic-number {
      width: 40px; height: 40px; flex: 0 0 40px;
      display: grid; place-items: center;
      border-radius: 10px; color: #fff;
      background: var(--teal);
      font-size: 13px; font-weight: 900;
    }
    .topic-head h2 { margin: 2px 0 0; font-size: 23px; line-height: 1.2; letter-spacing: -.5px; color: #0f172a; }
    .topic-card .copy-stack { padding-top: 20px; }
    .question-line {
      margin: 18px 0 0; padding: 13px 15px;
      border-left: 3px solid var(--gold);
      border-radius: 0 9px 9px 0;
      color: #1e3a5f; background: #fff8e7;
      font-size: 14.5px; font-weight: 900; line-height: 1.5;
    }
    .topic-list-title { margin: 18px 0 10px; color: #1e3a5f; font-size: 14px; font-weight: 900; }
    .check-list { margin: 0; padding: 0; list-style: none; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 9px 18px; }
    .check-list li { position: relative; padding-left: 22px; font-size: 14.5px; color: var(--body); }
    .check-list li::before { content: "✓"; position: absolute; left: 0; top: 0; color: var(--teal); font-weight: 900; }

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
      padding: 56px 0 40px;
      border-bottom: 1px solid rgba(255,255,255,.08);
      position: relative;
      z-index: 1;
    }
    .footer-grid {
      display: grid;
      grid-template-columns: 1.2fr 1fr 1.2fr;
      gap: 50px;
    }
    .footer-brand { }
    .footer-brand .brand-icon {
      width: 48px; height: 48px;
      background: linear-gradient(135deg, #0d8d8c, #086f70);
      border-radius: 12px;
      display: grid; place-items: center;
      font-size: 24px; margin-bottom: 14px;
    }
    .footer-brand p { font-size: 13.5px; line-height: 1.65; max-width: 260px; color: rgba(255,255,255,.55); margin-top: 10px; }
    .footer-col h4 { color: #fff; font-size: 13px; font-weight: 900; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 20px; }
    .footer-col ul { list-style: none; padding: 0; display: grid; gap: 12px; }
    .footer-col ul li { display: flex; align-items: center; gap: 10px; }
    .footer-col ul li::before { content: ">"; color: rgba(255,255,255,.35); font-size: 11px; font-weight: 900; }
    .footer-col ul li a { color: rgba(255,255,255,.55); font-size: 13.5px; font-weight: 600; }
    .footer-col ul li a:hover { color: var(--gold); }
    .footer-subscribe h4 { color: #fff; font-size: 15px; font-weight: 900; letter-spacing: .04em; margin-bottom: 14px; }
    .footer-subscribe p { color: rgba(255,255,255,.5); font-size: 13px; margin-bottom: 18px; line-height: 1.6; }
    .sub-form { display: flex; gap: 0; border-radius: 10px; overflow: hidden; border: 1px solid rgba(255,255,255,.12); }
    .sub-form input {
      flex: 1; min-width: 0;
      height: 44px; padding: 0 14px;
      border: 0; outline: 0;
      background: rgba(255,255,255,.07);
      color: #fff; font-size: 13px;
    }
    .sub-form input::placeholder { color: rgba(255,255,255,.35); }
    .sub-form button {
      height: 44px; padding: 0 22px;
      border: 0; border-radius: 0;
      background: #f5a623; color: #0a0a0a;
      font-size: 12px; font-weight: 900; letter-spacing: .05em; text-transform: uppercase;
      cursor: pointer;
    }
    .sub-form button:hover { background: #e09418; }
    .footer-bottom {
      padding: 18px 0;
      display: flex; align-items: center; justify-content: space-between;
      gap: 16px; flex-wrap: wrap;
      position: relative;
      z-index: 1;
    }
    .footer-bottom p { color: rgba(255,255,255,.35); font-size: 12.5px; }
    .footer-bottom-links { display: flex; gap: 20px; }
    .footer-bottom-links a { color: rgba(255,255,255,.35); font-size: 12.5px; font-weight: 600; }
    .footer-bottom-links a:hover { color: rgba(255,255,255,.7); }

    /* ── CHECKERBOARD FOOTER STRIP ── */
    .taxi-strip {
      height: 16px;
      background-image:
        linear-gradient(45deg, #000 25%, transparent 25%),
        linear-gradient(-45deg, #000 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #000 75%),
        linear-gradient(-45deg, transparent 75%, #000 75%);
      background-color: #fff;
      background-size: 32px 32px;
      background-position: 0 0, 0 16px, 16px -16px, -16px 0px;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1200px) {
      .nav { gap: 2px; }
      .brand-text { display: none; }
      .areas-list { grid-template-columns: repeat(2,minmax(0,1fr)); }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 1024px) {
      .nav, .header-actions .ghost-btn, .header-actions .primary-btn { display: none; }
      .menu-btn { display: inline-flex; }
      .drawer {
        position: fixed; inset: 95px 18px auto 18px; z-index: 60;
        padding: 16px;
        border: 1px solid var(--line); border-radius: 16px;
        background: #fff; box-shadow: 0 18px 40px rgba(15,23,42,.14);
      }
      .drawer.open { display: block; }
      .drawer nav { display: grid; gap: 10px; }
      .drawer a {
        min-height: 44px; display: flex; align-items: center;
        padding: 0 14px; border-radius: 10px; background: #f8fafc;
        font-weight: 800; font-size: 14px; color: #1f2937;
      }
      .span-8, .span-4, .span-6, .span-5, .span-7 { grid-column: span 12; }
    }
    @media (max-width: 768px) {
      .footer-grid { grid-template-columns: 1fr; gap: 28px; }
      .top-strip-right { display: none; }
    }
    @media (max-width: 680px) {
      .container { width: min(100% - 22px, 1420px); }
      .page { padding-top: 18px; }
      .header-inner { min-height: 72px; }
      .card-pad { padding: 22px; }
      .hero-title { font-size: 33px; letter-spacing: -1.2px; }
      .ask-card h2, .focus-card h2, .topic-head h2 { font-size: 24px; }
      .areas-list, .check-list { grid-template-columns: 1fr; }
      .query-box { grid-template-columns: 1fr; }
      .submit-arrow { width: 100%; border-radius: 10px; }
      .ai-img-card img { min-height: 220px; }
    }
  </style>
</head>
<body>

  {{-- TOP STRIP --}}
  <div class="top-strip">
    <div class="top-strip-inner">
      <div class="top-strip-left">
        <!-- 
        <span class="badge">FREE</span>
        -->
        Real Estate Taxi is your FREE rule through the global real estate market!
      </div>
      <div class="top-strip-right">
        <a href="#ask">Ask AI</a>
        <span class="strip-sep">|</span>
        <a href="#focus">Solutions</a>
        <span class="strip-sep">|</span>
        <select class="strip-select" aria-label="Currency" onchange="taxiSwitch('currency', this.value)">
          @foreach($currencies as $code => $label)
            <option value="{{ $code }}" {{ $currency === $code ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
        <select class="strip-select" aria-label="Language" onchange="taxiSwitch('lang', this.value)">
          @foreach($languages as $code => $name)
            <option value="{{ $code }}" {{ $locale === $code ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  {{-- HEADER --}}
  <header class="header">
    <div class="container header-inner">
      <a class="brand" href="#top" aria-label="Real Estate Taxi">
        <img src="/assets/images/logo-small.png" alt="Real Estate Taxi" style="height:48px;width:auto;display:block;">
      </a>

      <nav class="nav" aria-label="Main navigation">
        <a href="#top">Home <span class="arr">▾</span></a>
        <a href="#focus">Solutions <span class="arr">▾</span></a>
        <a href="#earn">Market Routes <span class="arr">▾</span></a>
        <a href="#comparison">Pricing View <span class="arr">▾</span></a>
        <a href="#why">About</a>
        <a href="#ask">Ask AI</a>
      </nav>

      <div class="header-actions">
        <a class="ghost-btn" href="#why">Why Real Estate</a>
        <a class="primary-btn" href="#ask">Get Report</a>
        <button class="menu-btn" id="menuBtn" type="button" aria-label="Open menu">☰</button>
      </div>
    </div>

    <div class="drawer" id="drawer">
      <nav aria-label="Mobile navigation">
        <a href="#top">Home</a>
        <a href="#focus">Solutions</a>
        <a href="#earn">Market Routes</a>
        <a href="#comparison">Pricing View</a>
        <a href="#why">About</a>
        <a href="#ask">Ask AI</a>
      </nav>
    </div>
  </header>

  {{-- MAIN --}}
  <main class="page" id="top">
    <div class="container">
      <div class="grid">

        {{-- 01 + 02 HERO + ASK AI --}}
        <section class="card ask-card span-12" id="ask">
          <div class="card-pad">
            <span class="number-label">01</span>
            <h1 class="hero-title">Real Estate Taxi is your FREE ride through the global real estate market!</h1>
            <p class="hero-copy">Real Estate Taxi helps anyone profit from real estate, even without owning property. It gives regular people a way to understand markets, compare prices, ask questions, and find practical ways to create value.</p>
            <span class="number-label" style="margin-top:32px;display:block;">02</span>
            <h2 class="hero-title-2">Ask anything about real estate, anywhere!</h2>
            <form class="query-box" id="askForm">
              <input type="text" aria-label="Ask anything about real estate" placeholder="Type your question here." />
              <button class="submit-arrow" type="submit" aria-label="Submit question">↗</button>
            </form>
            <p class="report-note">Real Estate Taxi AI analyzes your question and gives you a complete report.<br>Creating the report can take 30–45 seconds.</p>
          </div>
        </section>

         {{-- 03 WHY --}}
        <section class="card span-6" id="why">
          <div class="card-pad">
            <span class="number-label">03</span>
            <div class="copy-stack" style="padding-top:16px;">
              <p>Everyone, whatever your profession is, must understand that they need to be involved in some part of the real estate business.</p>
              <p>It is not important whether you have money to buy and invest in real estate or not.</p>
              <p>Real estate is real value that always remains a wealth factor. And at the end of the day, everyone must live in some house.</p>
              <p>Ignoring that, or believing that it is not your kind of expertise, can damage you financially in one way or another.</p>
            </div>
          </div>
        </section>

        {{-- AI IMAGE --}}
        <section class="card ai-img-card span-6" aria-hidden="true">
          <img src="/realestate-taxi/home1.png" alt="Real Estate Taxi AI" loading="lazy" />
        </section>

       

        {{-- 05 FOCUS --}}
        <section class="card focus-card span-12" id="focus">
          <div class="card-pad">
            <span class="number-label">04</span>
            <h2 class="hero-title-2">Smart Real Estate Decisions Made Simple</h2>
            <p class="focus-copy">Real Estate Taxi helps regular people understand real estate and find practical ways to benefit from it, even without owning property or having money to invest.</p>
            <div class="copy-stack" style="margin-top:18px;">
              <p>We focus on four important areas that can help you make smarter real estate decisions:</p>
            </div>
            <ol class="areas-list">
              <li><b>01</b><span>How to earn from real estate without buying property</span></li>
              <li><b>02</b><span>Best real estate software and AI solutions</span></li>
              <li><b>03</b><span>Global residential property market analysis</span></li>
              <li><b>04</b><span>Worldwide property prices comparison</span></li>
            </ol>
            <div class="copy-stack" style="margin-top:22px;">
              <p>Through these four areas, we help you find useful information, understand where opportunities may exist, compare markets, use better tools, and make more informed decisions.</p>
              <p>Our goal is to make real estate knowledge simpler and more useful for everyone. You do not need to be a real estate agent, investor, or property owner to understand how the market works and how you may benefit from it.</p>
              <p>We provide practical guides, market research, useful tools, AI solutions, property comparisons, and real estate ideas from around the world.</p>
              <p>Whether you want to earn by connecting buyers and sellers, find better investment locations, compare property prices, understand rental yields, or simply learn how real estate affects your financial future, Real Estate Taxi gives you a faster and clearer way to start.</p>
              <p>Real estate is a real value that always remains important. At the end of the day, everyone needs a place to live, rent, buy, sell, build, or invest in. Understanding real estate can help you make better financial decisions in many different ways.</p>
            </div>
          </div>
        </section>

        {{-- TOPIC 01 --}}
        <section class="card topic-card span-6" id="earn" data-no="01">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">01</span>
              <h2>How to Earn From Real Estate Without Buying Property</h2>
            </div>
            <div class="copy-stack">
              <p>You do not need to own a villa, apartment, or a large investment portfolio to earn from real estate.</p>
              <p>Learn practical ways regular people can create income by finding buyers, referring investors, helping owners rent properties, generating leads, creating property content, or simply connecting the right people with the right opportunity.</p>
              <p>Real estate is not only for people who already have money to buy property. Even without owning anything, you can become useful in the process and earn from the value you create.</p>
            </div>
          </div>
        </section>

        {{-- TOPIC 02 --}}
        <section class="card topic-card span-6" id="software" data-no="02">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">02</span>
              <h2>Best Real Estate Software and AI Solutions</h2>
            </div>
            <div class="copy-stack">
              <p>Discover useful real estate software, AI tools, websites, and services that can help regular people, agents, investors, owners, and property businesses work smarter.</p>
              <p>Find tools for property research, market analysis, price comparison, rental yield calculation, lead generation, content creation, AI property reports, buyer searches, and more.</p>
              <p>Real Estate Taxi helps you understand which tools are useful, what they do, and how they can help you find opportunities or make better real estate decisions.</p>
              <p>You do not need to be a real estate expert. The right tools can help anyone understand the market faster and find practical ways to earn from it.</p>
            </div>
          </div>
        </section>

        {{-- TOPIC 03 --}}
        <section class="card topic-card span-6" id="market" data-no="03">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">03</span>
              <h2>Global Residential Property Market Analysis</h2>
            </div>
            <div class="copy-stack">
              <p>Get access to detailed and up-to-date residential property market reports, key metrics, and useful insights from countries around the world.</p>
            </div>
            <p class="question-line">Where could it make sense to look for a real estate investment?</p>
            <div class="copy-stack" style="margin-top:16px;">
              <p>If you want to know where real estate may produce better rental yield, where prices may still be affordable, or where a market may become interesting in the future, this is one of the fastest useful places to check.</p>
              <p>Compare important market data such as property prices, rental yields, income levels, affordability, price growth, mortgage rates, taxes, and market trends.</p>
              <p>It helps regular people get a clearer first picture before spending money, travelling to a location, or speaking with real estate agents and investors.</p>
            </div>
          </div>
        </section>

        {{-- TOPIC 04 --}}
        <section class="card topic-card span-6" id="comparison" data-no="04">
          <div class="card-pad">
            <div class="topic-head">
              <span class="topic-number">04</span>
              <h2>Worldwide Property Prices Comparison</h2>
            </div>
            <p class="question-line">Is this city expensive, or could it still be interesting compared with other cities and countries?</p>
            <div class="copy-stack" style="margin-top:16px;">
              <p>Use the property prices comparison tool to compare real estate prices, apartment prices, rental prices, and affordability between different locations worldwide.</p>
              <p>This is very useful for a quick comparison between cities and countries.</p>
            </div>
            <p class="topic-list-title">Useful for:</p>
            <ul class="check-list">
              <li>Property price comparison</li>
              <li>Price-to-income ratio</li>
              <li>Rental yield estimates</li>
              <li>Affordability</li>
              <li>City comparison</li>
              <li>Quick first market feeling</li>
            </ul>
            <div class="copy-stack" style="padding-top:18px;">
              <p>It helps you quickly see whether a city looks overpriced, affordable, or potentially interesting when compared with local income and possible rental returns.</p>
            </div>
          </div>
        </section>

      </div>
    </div>
  </main>

  {{-- CHECKERBOARD TAXI STRIP --}}
  <div class="taxi-strip"></div>

  {{-- FOOTER --}}
  <footer>
    <div class="footer-top">
      <div class="container">
        <div class="footer-grid">

          <div class="footer-subscribe">
            <h4>SUBSCRIBE TO MAIL!</h4>
            <p>Get our Daily email newsletter with Special Services, Updates, Offers and more.</p>
            <form class="sub-form" id="subForm">
              <input type="email" placeholder="EMAIL ADDRESS" aria-label="Email address" />
              <button type="submit">SIGNUP</button>
            </form>
          </div>

          <div class="footer-col">
            <h4>WE GLAD TO OFFER</h4>
            <ul>
              <li><a href="#ask">24 / 7 Taxi Service To Any Where Around The City</a></li>
              <li><a href="#market">Sending Taxi Booking Alert By SMS</a></li>
              <li><a href="#software">GPS Tracking System For Location Guessing</a></li>
            </ul>
          </div>

          <div class="footer-col">
            <h4>ABOUT US</h4>
            <p style="font-size:13.5px;line-height:1.65;color:rgba(255,255,255,.55);margin-bottom:18px;">Hello we are Real Estate Taxi. We are here to provide you the best offers through our coupons and tools. We are here to provide you coupons.</p>
            <div style="display:flex;align-items:flex-start;gap:12px;color:rgba(255,255,255,.55);font-size:13.5px;line-height:1.6;">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.55)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex:0 0 18px;margin-top:3px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <span>A12 - Design Street,<br>Omaha, United States</span>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="footer-bottom">
        <p>© {{ date('Y') }} Real Estate Taxi. All rights reserved.</p>
        <div class="footer-bottom-links">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Use</a>
          <a href="#ask">AI Report</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    (function () {
      var btn = document.getElementById('menuBtn');
      var drawer = document.getElementById('drawer');
      if (btn) {
        btn.addEventListener('click', function () { drawer.classList.toggle('open'); });
      }
      document.querySelectorAll('#drawer a, .nav a, .ghost-btn, .primary-btn').forEach(function (link) {
        link.addEventListener('click', function () { drawer.classList.remove('open'); });
      });
      document.addEventListener('click', function (e) {
        if (drawer.classList.contains('open') && !drawer.contains(e.target) && !btn.contains(e.target)) {
          drawer.classList.remove('open');
        }
      });
      document.getElementById('askForm').addEventListener('submit', function (e) { e.preventDefault(); });
      document.getElementById('subForm').addEventListener('submit', function (e) { e.preventDefault(); });
    })();

    function taxiSwitch(key, value) {
      var params = new URLSearchParams(window.location.search);
      params.set(key, value);
      window.location.search = params.toString();
    }
  </script>
</body>
</html>
