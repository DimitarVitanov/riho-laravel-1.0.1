@extends('layouts.simple.master')
@section('title', __('messages.authority_builder'))
@section('breadcrumb-title')
    <h3>{{ __('messages.authority_builder') }}</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.authority_builder') }}</li>
@endsection

@section('content')
<div class="container-fluid ai-authority-builder-feature">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Feature Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">{{ __('messages.authority_builder') }}</h5>
                        <small class="text-muted">{{ __('messages.authority_builder_subtitle') }}</small>
                    </div>
                    <span class="badge {{ $featureSetting && $featureSetting->is_enabled ? 'bg-success' : 'bg-secondary' }} fs-6">
                        {{ $featureSetting && $featureSetting->is_enabled ? __('messages.active') : __('messages.inactive') }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ __('messages.authority_builder_summary') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">
         <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-primary">
                        {{ $usageLimit ? $usageLimit->authority_review_updates_used . ' / ' . $usageLimit->authority_review_updates_limit : '— / —' }}
                    </div>
                    <div class="text-muted small">{{ __('messages.authority_reviews') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    <div class="fs-3 fw-bold text-dark">{{ $reviewPages->total() }}</div>
                    <div class="text-muted small">{{ __('messages.authority_reviews_generated') }}</div>
                </div>
            </div>
        </div>
       
    </div>

    <div class="row">


        {{-- RIGHT: Suggestions & Review Pages --}}
        <div class="col-lg-12">
            
            {{-- Pending Suggestions --}}
            <div class="card mb-4">
                <div class="card-header bg-warning bg-opacity-10 border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0"><i class="fa fa-lightbulb-o me-2"></i>{{ __('messages.pending_urls') }}</h6>
                    <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-inbox me-1"></i>{{ __('messages.daily_ai_employee') }}
                    </a>
                </div>
                <div class="card-body p-0">
                    @php
                        $pendingSuggestions = $profile ? $profile->aiSuggestions()
                            ->where('feature_key', 'ai_authority_builder')
                            ->where('status', 'pending')
                            ->latest()
                            ->get() : collect();
                    @endphp
                    @if($pendingSuggestions->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="fa fa-lightbulb-o fa-2x mb-2 d-block text-muted opacity-50"></i>
                        <p class="mb-0 small">{{ __('messages.no_pending_urls') }}</p>
                    </div>
                    @else
                    <div class="p-3">
                        @foreach($pendingSuggestions as $suggestion)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $suggestion->title }}</h6>
                                    <small class="text-muted">{{ $suggestion->ai_summary }}</small>
                                </div>
                                <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                            </div>
                            <div class="d-flex gap-3 mt-3">
                                <form action="{{ route('agency.authority.suggestions.accept', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm px-3">
                                        <i class="fas fa-check-circle me-2"></i>{{ __('messages.accept') }}
                                    </button>
                                </form>
                                <form action="{{ route('agency.authority.suggestions.skip', $suggestion) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-warning btn-sm px-3">
                                        <i class="fas fa-forward me-2"></i>{{ __('messages.skip') }}
                                    </button>
                                </form>
                                <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-primary btn-sm px-3">
                                    <i class="fas fa-inbox me-2"></i>{{ __('messages.review_in_ai_employee') }}
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Generated Review Pages --}}
            <div class="card">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-file-text me-2"></i>{{ __('messages.authority_review_pages') }}</h6>
                </div>
                <div class="card-body p-0">
                    @if($reviewPages->isEmpty())
                    <div class="p-5 text-center text-muted">
                        <i class="fa fa-star-o fa-3x mb-3 d-block text-muted opacity-50"></i>
                        <p class="mb-1">{{ __('messages.no_authority_reviews_yet') }}</p>
                        <small>{{ __('messages.no_authority_reviews_help') }}</small>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 small">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.layers_count') }}</th>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviewPages as $page)
                                <tr>
                                    <td class="fw-bold">{{ Str::limit($page->title, 55) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $page->status === 'published' ? 'success' : ($page->status === 'pending_review' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $page->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        {{ isset($page->content_json['layers']) ? count($page->content_json['layers']) : '10' }} / 10
                                    </td>
                                    <td class="text-muted">{{ $page->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ route('agency.authority.pages.preview', $page) }}" class="btn btn-outline-dark btn-sm px-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($page->status !== 'published')
                                            <form action="{{ route('agency.authority.pages.publish', $page) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm px-2" title="{{ __('messages.publish') }}">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                            @endif
                                            <form action="{{ route('agency.authority.pages.refresh', $page) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm px-2" title="{{ __('messages.refresh') }}">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('agency.authority.pages.destroy', $page) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm px-2" onclick="return confirm('{{ __('messages.confirm_delete') }}')" title="{{ __('messages.delete') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        @include('partials.pagination', ['paginator' => $reviewPages])
                    </div>
                    @endif
                </div>
            </div>

            {{-- 10 Layers Info Cards --}}
            <div class="card mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0"><i class="fa fa-layers me-2"></i>{{ __('messages.ten_layers_title') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach([
                            ['num' => '1', 'color' => 'primary',   'title' => __('messages.layer_entity'),           'desc' => __('messages.layer_entity_desc')],
                            ['num' => '2', 'color' => 'info',      'title' => __('messages.layer_service'),          'desc' => __('messages.layer_service_desc')],
                            ['num' => '3', 'color' => 'success',   'title' => __('messages.layer_local_market'),     'desc' => __('messages.layer_local_market_desc')],
                            ['num' => '4', 'color' => 'warning',   'title' => __('messages.layer_buyer_questions'),  'desc' => __('messages.layer_buyer_questions_desc')],
                            ['num' => '5', 'color' => 'secondary', 'title' => __('messages.layer_property_data'),   'desc' => __('messages.layer_property_data_desc')],
                            ['num' => '6', 'color' => 'danger',    'title' => __('messages.layer_trust_signals'),   'desc' => __('messages.layer_trust_signals_desc')],
                            ['num' => '7', 'color' => 'dark',      'title' => __('messages.layer_competitor_context'),'desc' => __('messages.layer_competitor_context_desc')],
                            ['num' => '8', 'color' => 'primary',   'title' => __('messages.layer_ai_readability'),  'desc' => __('messages.layer_ai_readability_desc')],
                            ['num' => '9', 'color' => 'success',   'title' => __('messages.layer_freshness'),       'desc' => __('messages.layer_freshness_desc')],
                            ['num' => '10','color' => 'info',      'title' => __('messages.layer_structured_data'), 'desc' => __('messages.layer_structured_data_desc')],
                        ] as $layer)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <span class="badge bg-{{ $layer['color'] }} rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;min-width:28px;font-size:11px;">{{ $layer['num'] }}</span>
                                <div>
                                    <div class="fw-bold small">{{ $layer['title'] }}</div>
                                    <div class="text-muted" style="font-size:12px;">{{ $layer['desc'] }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
