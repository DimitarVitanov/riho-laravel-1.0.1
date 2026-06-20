@extends('layouts.simple.master')
@section('title', $page->title)
@section('breadcrumb-title')
    <h3>{{ __('messages.preview') }}: {{ $page->title }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}">{{ __('messages.local_seo') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.preview') }}</li>
@endsection

@section('content')
<div class="container-fluid local-seo-preview">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $page->title }}</h5>
                        <small class="text-muted">{{ __('messages.status') }}: {{ ucfirst($page->status) }}</small>
                    </div>
                    <div>
                        <a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}" class="btn btn-outline-secondary btn-sm">{{ __('messages.back') }}</a>
                        <a href="{{ route('agency.local-seo.pages.edit', $page) }}" class="btn btn-outline-dark btn-sm">{{ __('messages.edit') }}</a>
                        @if($page->status !== 'published')
                        <form action="{{ route('agency.local-seo.pages.publish', $page) }}" method="POST" class="d-inline">
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

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">{{ __('messages.seo_details') }}</h6>
                </div>
                <div class="card-body">
                    <p><strong>{{ __('messages.seo_title') }}:</strong> {{ $page->seo_title ?? '—' }}</p>
                    <p><strong>{{ __('messages.meta_description') }}:</strong> {{ $page->meta_description ?? '—' }}</p>
                    <p><strong>{{ __('messages.slug') }}:</strong> {{ $page->slug ?? '—' }}</p>
                    <p><strong>{{ __('messages.target_city') }}:</strong> {{ $page->content_json['target_city'] ?? '—' }}</p>
                    <p><strong>{{ __('messages.target_keyword') }}:</strong> {{ $page->content_json['target_keyword'] ?? '—' }}</p>
                    <p><strong>{{ __('messages.uniqueness_status') }}:</strong> {{ ucfirst($page->content_uniqueness_status ?? 'pending') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0">{{ __('messages.target_subniches') }}</h6>
                </div>
                <div class="card-body">
                    @if(!empty($page->content_json['subniches']))
                        <ul class="list-group list-group-flush">
                            @foreach($page->content_json['subniches'] as $subniche)
                            <li class="list-group-item">{{ $subniche }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">{{ __('messages.no_subniches') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
