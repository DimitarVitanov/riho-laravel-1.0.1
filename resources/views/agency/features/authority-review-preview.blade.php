@extends('layouts.simple.master')
@section('title', $page->title)
@section('breadcrumb-title')
    <h3>{{ __('messages.authority_builder') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.features.show', 'ai_authority_builder') }}">{{ __('messages.authority_builder') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.preview') }}</li>
@endsection

@section('content')
<div class="container-fluid">

    {{-- Action Bar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
                    <div>
                        <span class="badge bg-{{ $page->status === 'published' ? 'success' : 'warning' }} me-2">
                            {{ ucfirst(str_replace('_', ' ', $page->status)) }}
                        </span>
                        <small class="text-muted">{{ __('messages.authority_builder') }} · {{ $page->created_at->format('d M Y') }}</small>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($page->status !== 'published')
                        <form action="{{ route('agency.authority.pages.publish', $page) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fa fa-check me-1"></i>{{ __('messages.publish') }}
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('agency.authority.pages.refresh', $page) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-refresh me-1"></i>{{ __('messages.refresh') }}
                            </button>
                        </form>
                        <a href="{{ route('agency.features.show', 'ai_authority_builder') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i>{{ __('messages.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Content --}}
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">{{ $page->title }}</h5>
                    @if($page->meta_description)
                    <small class="text-muted">{{ $page->meta_description }}</small>
                    @endif
                </div>
                <div class="card-body authority-review-content" style="line-height:1.8;">
                    {!! $page->content_html !!}
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-header bg-white border-bottom py-2">
                    <h6 class="fw-bold mb-0 small">{{ __('messages.page_details') }}</h6>
                </div>
                <div class="card-body p-3">
                    <div class="small mb-2">
                        <span class="text-muted">{{ __('messages.status') }}:</span>
                        <span class="badge bg-{{ $page->status === 'published' ? 'success' : 'warning' }} ms-1">{{ ucfirst(str_replace('_', ' ', $page->status)) }}</span>
                    </div>
                    <div class="small mb-2">
                        <span class="text-muted">{{ __('messages.feature') }}:</span>
                        <span class="ms-1">AI Authority Builder</span>
                    </div>
                    @if(isset($page->content_json['city']))
                    <div class="small mb-2">
                        <span class="text-muted">{{ __('messages.city') }}:</span>
                        <span class="ms-1">{{ $page->content_json['city'] }}</span>
                    </div>
                    @endif
                    @if(isset($page->content_json['layers']))
                    <div class="small mb-2">
                        <span class="text-muted">{{ __('messages.layers_count') }}:</span>
                        <span class="ms-1">{{ count($page->content_json['layers']) }} / 10</span>
                    </div>
                    @endif
                    <div class="small mb-2">
                        <span class="text-muted">{{ __('messages.created') }}:</span>
                        <span class="ms-1">{{ $page->created_at->format('d M Y') }}</span>
                    </div>
                    @if($page->published_at)
                    <div class="small">
                        <span class="text-muted">{{ __('messages.published_at') }}:</span>
                        <span class="ms-1">{{ $page->published_at->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white border-bottom py-2">
                    <h6 class="fw-bold mb-0 small">{{ __('messages.actions') }}</h6>
                </div>
                <div class="card-body p-3 d-flex flex-column gap-2">
                    @if($page->status !== 'published')
                    <form action="{{ route('agency.authority.pages.publish', $page) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm w-100">{{ __('messages.publish') }}</button>
                    </form>
                    @endif
                    <form action="{{ route('agency.authority.pages.refresh', $page) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">{{ __('messages.refresh') }}</button>
                    </form>
                    <form action="{{ route('agency.authority.pages.destroy', $page) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('{{ __('messages.confirm_delete') }}')">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('styles')
<style>
.authority-review-content h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 1rem; }
.authority-review-content h2 { font-size: 1.2rem; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; border-bottom: 1px solid #eee; padding-bottom: 0.4rem; }
.authority-review-content table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
.authority-review-content table th, .authority-review-content table td { padding: 0.5rem 0.75rem; border: 1px solid #dee2e6; font-size: 0.9rem; }
.authority-review-content table thead { background: #f8f9fa; }
.authority-review-content p { margin-bottom: 0.75rem; }
.authority-review-content ul { margin-bottom: 1rem; }
.authority-review-content li { margin-bottom: 0.3rem; }
</style>
@endpush
@endsection
