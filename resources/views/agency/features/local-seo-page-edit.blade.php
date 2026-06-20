@extends('layouts.simple.master')
@section('title', __('messages.edit') . ': ' . $page->title)
@section('breadcrumb-title')
    <h3>{{ __('messages.edit') }}: {{ $page->title }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.features.show', 'local_seo_presence_boost') }}">{{ __('messages.local_seo') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.local-seo.pages.preview', $page) }}">{{ __('messages.preview') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.edit') }}</li>
@endsection

@section('content')
<div class="container-fluid local-seo-edit">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.edit_page') }}</h5>
                        <small class="text-muted">{{ __('messages.edit_page_content') }}</small>
                    </div>
                    <div>
                        <a href="{{ route('agency.local-seo.pages.preview', $page) }}" class="btn btn-outline-secondary btn-sm">{{ __('messages.cancel') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.local-seo.pages.update', $page) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.title') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ $page->title }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small fw-bold">{{ __('messages.seo_title') }}</label>
                                <input type="text" name="seo_title" class="form-control" value="{{ $page->seo_title ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.meta_description') }}</label>
                            <textarea name="meta_description" class="form-control" rows="2">{{ $page->meta_description ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">{{ __('messages.content') }}</label>
                            <textarea name="content_html" class="form-control" rows="20" id="pageContent">{{ $page->content_html }}</textarea>
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn btn-dark w-100">{{ __('messages.save_changes') }}</button>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="{{ route('agency.local-seo.pages.preview', $page) }}" class="btn btn-outline-secondary w-100">{{ __('messages.cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
