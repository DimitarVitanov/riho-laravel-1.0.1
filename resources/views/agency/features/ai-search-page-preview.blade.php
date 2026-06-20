@extends('layouts.simple.master')
@section('title', $page->title)
@section('breadcrumb-title')
    <h3>{{ $page->title }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.features.show', 'ai_search_ranking') }}">{{ __('messages.ai_search_ranking') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.preview') }}</li>
@endsection

@section('content')
<div class="container-fluid ai-search-preview">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $page->title }}</h5>
                        <small class="text-muted">{{ __('messages.status') }}: {{ ucfirst($page->status) }}</small>
                    </div>
                    <div>
                        <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-secondary btn-sm">{{ __('messages.back') }}</a>
                        <a href="{{ route('agency.ai-search.pages.edit', $page) }}" class="btn btn-outline-dark btn-sm">{{ __('messages.edit') }}</a>
                        <form action="{{ route('agency.ai-search.pages.refresh', $page) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('messages.refresh') }}</button>
                        </form>
                        @if($page->status !== 'published')
                        <form action="{{ route('agency.ai-search.pages.publish', $page) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm">{{ __('messages.publish') }}</button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="border rounded p-4 bg-white">
                        {!! $page->content_html !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($page->seo_title || $page->meta_description)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-1 fw-bold">{{ __('messages.seo_details') }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>{{ __('messages.seo_title') }}:</strong> {{ $page->seo_title ?? '—' }}</p>
                    <p><strong>{{ __('messages.meta_description') }}:</strong> {{ $page->meta_description ?? '—' }}</p>
                    <p><strong>{{ __('messages.slug') }}:</strong> {{ $page->slug }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
