@extends('layouts.simple.master')
@section('title', 'Article Detail')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ $page->title }}</h1>
            <p>
                <span class="vb-badge {{ $page->status === 'published' ? 'vb-badge-success' : 'vb-badge-muted' }}">{{ ucfirst($page->status) }}</span>
                &nbsp;·&nbsp; {{ ucfirst(str_replace('_', ' ', $page->feature_key)) }}
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('agency.generated-pages.preview', $page) }}" class="vb-btn vb-btn-outline" target="_blank">👁 Preview</a>
            <a href="{{ route('agency.generated-pages.edit', $page) }}" class="vb-btn vb-btn-outline">✏️ Edit</a>
            @if($page->status !== 'published')
                <form action="{{ route('agency.generated-pages.publish', $page) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="vb-btn vb-btn-primary">🚀 Publish</button>
                </form>
            @else
                <form action="{{ route('agency.generated-pages.unpublish', $page) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="vb-btn vb-btn-warning">⏸ Unpublish</button>
                </form>
            @endif
            <a href="{{ route('agency.generated-pages.index') }}" class="vb-btn vb-btn-outline">← Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="vb-card">
                <h6 class="mb-3" style="font-weight:800; color:#1e293b;">Content Preview</h6>
                <div class="content-preview" style="line-height:1.7; font-size:15px;">
                    {!! $page->content_html !!}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="vb-card">
                <h6 class="mb-3" style="font-weight:800; color:#1e293b;">Article Info</h6>
                <p><strong>SEO Title:</strong><br>{{ $page->seo_title ?? '—' }}</p>
                <p class="mt-2"><strong>Meta Description:</strong><br>{{ $page->meta_description ?? '—' }}</p>
                <p class="mt-2"><strong>Slug:</strong><br><code>/blog/{{ $page->slug }}</code></p>
                <p class="mt-2"><strong>Uniqueness:</strong><br>
                    @php
                        $uClass = match($page->content_uniqueness_status) {
                            'passed' => 'vb-badge-success',
                            'failed' => 'vb-badge-danger',
                            'checking' => 'vb-badge-warning',
                            default => 'vb-badge-info'
                        };
                    @endphp
                    <span class="vb-badge {{ $uClass }}">{{ strtoupper($page->content_uniqueness_status) }}</span>
                </p>
                <p class="mt-2"><strong>Published:</strong><br>{{ $page->published_at ? $page->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                @if($page->target_url)
                <p class="mt-2"><strong>Target URL:</strong><br><a href="{{ $page->target_url }}" target="_blank">{{ $page->target_url }}</a></p>
                @endif
            </div>

            <div class="vb-card mt-3">
                <h6 class="mb-3" style="font-weight:800; color:#dc2626;">Danger Zone</h6>
                <form action="{{ route('agency.generated-pages.destroy', $page) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="vb-btn vb-btn-danger vb-btn-sm">🗑 Delete Article</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
