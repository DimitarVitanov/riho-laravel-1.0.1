<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Competitor Intelligence Report — {{ $report->report_date->format('d M Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #202733; max-width: 900px; margin: 40px auto; line-height: 1.5; }
        h1, h2 { color: #111827; }
        .summary { background: #f4f6f8; border: 1px solid #dde2e7; border-radius: 8px; padding: 16px; }
        .metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin: 24px 0; }
        .metric { border: 1px solid #dde2e7; border-radius: 8px; padding: 14px; }
        .metric strong { display: block; font-size: 24px; }
        .metric span { color: #69707d; font-size: 12px; }
        .item { border-bottom: 1px solid #e5e7eb; padding: 12px 0; }
        .priority { font-weight: bold; margin-right: 8px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <h1>Daily Competitor Intelligence Report</h1>
    <p>{{ $report->report_date->format('d M Y') }}</p>

    @if($report->executive_summary)
    <div class="summary"><strong>Executive summary:</strong> {{ $report->executive_summary }}</div>
    @endif

    <div class="metrics">
        <div class="metric"><strong>{{ $report->metrics?->new_properties ?? 0 }}</strong><span>New properties</span></div>
        <div class="metric"><strong>{{ $report->metrics?->getPriceChangesCount() ?? 0 }}</strong><span>Price changes</span></div>
        <div class="metric"><strong>{{ $report->metrics?->new_seo_pages ?? 0 }}</strong><span>SEO pages</span></div>
        <div class="metric"><strong>{{ $report->metrics?->total_changes ?? 0 }}</strong><span>Total changes</span></div>
    </div>

    @foreach($report->items->groupBy('item_type') as $type => $items)
    <h2>{{ str($type)->replace('_', ' ')->title() }}</h2>
    @foreach($items as $item)
    <div class="item">
        @if($item->priority)<span class="priority">{{ strtoupper($item->priority) }}</span>@endif
        {{ $item->content }}
        @if($item->reason)<div><small>{{ $item->reason }}</small></div>@endif
    </div>
    @endforeach
    @endforeach
</body>
</html>
