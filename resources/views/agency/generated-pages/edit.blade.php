@extends('layouts.simple.master')
@section('title', 'Edit Article')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Edit Article</h1>
            <p>{{ $page->title }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('agency.generated-pages.preview', $page) }}" class="vb-btn vb-btn-outline" target="_blank">👁 Preview</a>
            <a href="{{ route('agency.generated-pages.show', $page) }}" class="vb-btn vb-btn-outline">← Back</a>
        </div>
    </div>

    <div class="vb-card">
        <form action="{{ route('agency.generated-pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $page->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Article Type</label>
                    <select name="feature_key" class="form-select" required>
                        <option value="authority_review" {{ $page->feature_key === 'authority_review' ? 'selected' : '' }}>Authority Review</option>
                        <option value="local_seo" {{ $page->feature_key === 'local_seo' ? 'selected' : '' }}>Local SEO Page</option>
                        <option value="ai_search_freshness" {{ $page->feature_key === 'ai_search_freshness' ? 'selected' : '' }}>AI Search Freshness</option>
                        <option value="market_analysis" {{ $page->feature_key === 'market_analysis' ? 'selected' : '' }}>Market Analysis</option>
                        <option value="property_guide" {{ $page->feature_key === 'property_guide' ? 'selected' : '' }}>Property Guide</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">SEO Title</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title', $page->seo_title) }}" placeholder="Leave blank to use article title">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Meta Description</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}" maxlength="500">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Content (HTML)</label>
                <textarea name="content_html" class="form-control @error('content_html') is-invalid @enderror" rows="20" required>{{ old('content_html', $page->content_html) }}</textarea>
                @error('content_html')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">You can use HTML tags: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;, &lt;table&gt;, etc.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="vb-btn vb-btn-primary">Save Changes</button>
                <a href="{{ route('agency.generated-pages.show', $page) }}" class="vb-btn vb-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
