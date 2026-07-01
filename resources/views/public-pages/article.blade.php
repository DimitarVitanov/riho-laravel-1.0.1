<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $page->meta_description }}" />
  <title>{{ $page->seo_title ?? $page->title }}</title>
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
    a { color: var(--accent); text-decoration: none; }
    a:hover { text-decoration: underline; }

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
    .container { width: min(900px, 90%); margin: 0 auto; }
    .container-wide { width: min(1420px, 90%); margin: 0 auto; }
    .header-inner {
      min-height: var(--header-h);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 20px;
    }
    .brand { display: inline-flex; align-items: center; gap: 12px; text-decoration: none; }
    .brand-name { font-size: 16px; font-weight: 900; color: var(--ink); letter-spacing: -.02em; }

    /* ── ARTICLE ── */
    .page { padding: 40px 0 60px; }
    .article-card {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: var(--card);
      box-shadow: var(--shadow);
      overflow: hidden;
      padding: 42px 48px;
    }
    .article-meta {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
      font-size: 13px;
      color: var(--muted);
      font-weight: 700;
    }
    .article-meta .badge {
      background: var(--accent);
      color: #fff;
      padding: 3px 10px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: 900;
      letter-spacing: .04em;
      text-transform: uppercase;
    }
    .article-title {
      font-size: clamp(28px, 3.5vw, 42px);
      line-height: 1.12;
      letter-spacing: -1.2px;
      font-weight: 900;
      color: var(--ink);
      margin-bottom: 24px;
    }
    .article-content {
      font-size: 17px;
      line-height: 1.75;
      color: var(--body);
    }
    .article-content h2 {
      font-size: 24px;
      font-weight: 900;
      color: var(--ink);
      margin: 32px 0 14px;
      letter-spacing: -.5px;
    }
    .article-content h3 {
      font-size: 20px;
      font-weight: 800;
      color: var(--ink);
      margin: 26px 0 10px;
    }
    .article-content p { margin-bottom: 16px; }
    .article-content ul, .article-content ol { margin: 14px 0 14px 24px; }
    .article-content li { margin-bottom: 8px; }
    .article-content blockquote {
      margin: 20px 0;
      padding: 14px 18px;
      border-left: 3px solid var(--gold);
      background: #fff8e7;
      border-radius: 0 10px 10px 0;
      font-weight: 700;
      color: #1e3a5f;
    }
    .article-content img {
      max-width: 100%;
      border-radius: 12px;
      margin: 20px 0;
    }
    .article-content table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 15px;
    }
    .article-content th, .article-content td {
      padding: 10px 14px;
      border: 1px solid var(--line);
      text-align: left;
    }
    .article-content th {
      background: #f8fafc;
      font-weight: 800;
      color: var(--ink);
    }

    /* ── FOOTER ── */
    .footer {
      background: #111217;
      color: rgba(255,255,255,.55);
      padding: 28px 0;
      font-size: 13px;
      text-align: center;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 680px) {
      .article-card { padding: 24px 20px; }
      .article-title { font-size: 26px; letter-spacing: -.8px; }
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="container-wide header-inner">
      <a class="brand" href="/">
        <span class="brand-name">{{ $profile->agency_name }}</span>
      </a>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <article class="article-card">
        <div class="article-meta">
          <span class="badge">{{ str_replace('_', ' ', $page->feature_key) }}</span>
          @if($page->published_at)
            <time datetime="{{ $page->published_at->toDateString() }}">{{ $page->published_at->format('M j, Y') }}</time>
          @endif
        </div>
        <h1 class="article-title">{{ $page->title }}</h1>
        <div class="article-content">
          {!! $page->content_html !!}
        </div>
      </article>
    </div>
  </main>

  <footer class="footer">
    <div class="container-wide">
      <p>&copy; {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
