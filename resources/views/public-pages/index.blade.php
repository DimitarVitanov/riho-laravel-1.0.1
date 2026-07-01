<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="{{ $profile->agency_name }} — Published articles, market analysis, and real estate insights." />
  <title>{{ $profile->agency_name }} — Blog</title>
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
      --shadow: 0 10px 30px rgba(15,23,42,.06);
      --radius: 14px;
      --header-h: 72px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Nunito', 'Inter', ui-sans-serif, system-ui, sans-serif;
      color: var(--body);
      background: var(--bg);
      line-height: 1.62;
      font-size: 17px;
    }
    a { color: inherit; text-decoration: none; }

    .header {
      position: sticky; top: 0; z-index: 50;
      background: rgba(255,255,255,.96);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--line);
      box-shadow: 0 2px 12px rgba(15,23,42,.04);
    }
    .container { width: min(1420px, 90%); margin: 0 auto; }
    .header-inner {
      min-height: var(--header-h);
      display: flex; align-items: center; justify-content: space-between; gap: 20px;
    }
    .brand { display: inline-flex; align-items: center; gap: 12px; }
    .brand-name { font-size: 16px; font-weight: 900; color: var(--ink); letter-spacing: -.02em; }

    .page { padding: 40px 0 60px; }
    .page-title {
      font-size: 32px; font-weight: 900; color: var(--ink);
      letter-spacing: -.8px; margin-bottom: 28px;
    }

    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
    .post-card {
      border: 1px solid var(--line);
      border-radius: var(--radius);
      background: var(--card);
      box-shadow: var(--shadow);
      padding: 28px;
      display: flex; flex-direction: column;
      transition: box-shadow .15s, transform .15s;
    }
    .post-card:hover { box-shadow: 0 14px 36px rgba(15,23,42,.1); transform: translateY(-2px); }
    .post-badge {
      display: inline-block;
      background: var(--accent); color: #fff;
      padding: 2px 8px; border-radius: 5px;
      font-size: 10px; font-weight: 900; letter-spacing: .04em;
      text-transform: uppercase; margin-bottom: 12px;
    }
    .post-title {
      font-size: 19px; font-weight: 900; color: var(--ink);
      line-height: 1.25; letter-spacing: -.4px; margin-bottom: 10px;
    }
    .post-excerpt {
      font-size: 14px; color: var(--muted); line-height: 1.55;
      flex: 1; margin-bottom: 14px;
    }
    .post-date { font-size: 12px; color: var(--muted); font-weight: 700; }

    .pagination-wrap {
      margin-top: 32px; display: flex; justify-content: center; gap: 8px;
    }
    .pagination-wrap a, .pagination-wrap span {
      display: inline-flex; align-items: center; justify-content: center;
      min-width: 36px; height: 36px; padding: 0 10px;
      border-radius: 8px; font-size: 13px; font-weight: 800;
      border: 1px solid var(--line); background: var(--card); color: var(--ink);
    }
    .pagination-wrap span.current { background: var(--accent); color: #fff; border-color: var(--accent); }

    .footer {
      background: #111217; color: rgba(255,255,255,.55);
      padding: 28px 0; font-size: 13px; text-align: center;
    }

    @media (max-width: 680px) {
      .grid { grid-template-columns: 1fr; }
      .page-title { font-size: 26px; }
    }
  </style>
</head>
<body>

  <header class="header">
    <div class="container header-inner">
      <a class="brand" href="/">
        <span class="brand-name">{{ $profile->agency_name }}</span>
      </a>
    </div>
  </header>

  <main class="page">
    <div class="container">
      <h1 class="page-title">Latest Articles</h1>
      <div class="grid">
        @foreach($pages as $post)
          <a class="post-card" href="/blog/{{ $post->slug }}">
            <span class="post-badge">{{ str_replace('_', ' ', $post->feature_key) }}</span>
            <h2 class="post-title">{{ $post->title }}</h2>
            <p class="post-excerpt">{{ Str::limit(strip_tags($post->content_html), 140) }}</p>
            @if($post->published_at)
              <time class="post-date" datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('M j, Y') }}</time>
            @endif
          </a>
        @endforeach
      </div>

      @if($pages->hasPages())
        <div class="pagination-wrap">
          @foreach($pages->getUrlRange(1, $pages->lastPage()) as $pageNum => $url)
            @if($pageNum == $pages->currentPage())
              <span class="current">{{ $pageNum }}</span>
            @else
              <a href="{{ $url }}">{{ $pageNum }}</a>
            @endif
          @endforeach
        </div>
      @endif
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <p>&copy; {{ date('Y') }} {{ $profile->agency_name }}. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
