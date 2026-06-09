@extends('layouts.simple.master')
@section('title', 'Page Detail')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6"><h3>{{ $page->title }}</h3></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="content-preview">{!! $page->content_html !!}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h6>Page Info</h6></div>
                <div class="card-body">
                    <p><strong>SEO Title:</strong> {{ $page->seo_title ?? '—' }}</p>
                    <p><strong>Meta Description:</strong> {{ $page->meta_description ?? '—' }}</p>
                    <p><strong>Feature:</strong> {{ $page->feature_key }}</p>
                    <p><strong>Uniqueness:</strong> {{ strtoupper($page->content_uniqueness_status) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($page->status) }}</p>
                    <p><strong>Published:</strong> {{ $page->published_at ? $page->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                    @if($page->target_url)
                    <p><strong>Target URL:</strong> <a href="{{ $page->target_url }}" target="_blank">{{ $page->target_url }}</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
