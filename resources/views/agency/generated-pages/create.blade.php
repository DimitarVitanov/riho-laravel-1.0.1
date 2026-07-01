@extends('layouts.simple.master')
@section('title', 'Create Article')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Create New Article</h1>
            <p>Write a new article for your public blog / authority pages.</p>
        </div>
        <a href="{{ route('agency.generated-pages.index') }}" class="vb-btn vb-btn-outline">← Back to Articles</a>
    </div>

    <div class="vb-card">
        <form action="{{ route('agency.generated-pages.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Article Type</label>
                    <select name="feature_key" class="form-select" required>
                        <option value="authority_review">Authority Review</option>
                        <option value="local_seo">Local SEO Page</option>
                        <option value="ai_search_freshness">AI Search Freshness</option>
                        <option value="market_analysis">Market Analysis</option>
                        <option value="property_guide">Property Guide</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">SEO Title</label>
                    <input type="text" name="seo_title" class="form-control" value="{{ old('seo_title') }}" placeholder="Leave blank to use article title">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Meta Description</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}" maxlength="500">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Content (HTML)</label>
                <textarea name="content_html" class="form-control @error('content_html') is-invalid @enderror" rows="20" required>{{ old('content_html') }}</textarea>
                @error('content_html')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">You can use HTML tags: &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;blockquote&gt;, &lt;table&gt;, etc.</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="vb-btn vb-btn-primary">Create Article</button>
                <a href="{{ route('agency.generated-pages.index') }}" class="vb-btn vb-btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
