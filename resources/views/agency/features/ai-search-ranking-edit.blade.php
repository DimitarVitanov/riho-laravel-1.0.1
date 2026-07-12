@extends('layouts.simple.master')

@section('title', 'Edit AI Search Page')

@section('main_content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="vb-page-header">
        <div>
            <h1>Edit: {{ $page->name }}</h1>
            <p class="text-muted mb-0">Edit AI-generated content for this page</p>
        </div>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('agency.ai-search-ranking.preview', $page->id) }}" class="btn btn-outline-secondary" target="_blank">
                <i class="fa fa-eye me-1"></i> Preview
            </a>
            <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-dark">
                <i class="fa fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

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

    {{-- Page Info Card --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <div style="padding:20px;display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
            <div>
                <div class="vb-label">Location</div>
                <div style="font-weight:600;">{{ $page->target_neighborhood ? $page->target_neighborhood . ', ' : '' }}{{ $page->target_city }}, {{ $page->country }}</div>
            </div>
            <div>
                <div class="vb-label">Property Type</div>
                <div style="font-weight:600;">{{ ucfirst($page->property_type ?? 'All Types') }}</div>
            </div>
            <div>
                <div class="vb-label">Status</div>
                <div>
                    @if($page->status === 'published')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </div>
            </div>
            <div>
                <div class="vb-label">Slug</div>
                <div style="font-weight:600;font-family:monospace;font-size:13px;">/{{ $page->slug }}</div>
            </div>
        </div>
    </div>

    {{-- Connected Listing --}}
    <div class="vb-card" style="margin-bottom:24px;">
        <div style="padding:20px;">
            <div class="vb-label" style="margin-bottom:10px;">Connected Listing</div>
            <form action="{{ route('agency.ai-search-ranking.update-listing', $page->id) }}" method="POST" style="display:flex;gap:12px;align-items:end;">
                @csrf
                @method('PUT')
                <select name="agency_listing_id" class="form-select" style="max-width:400px;">
                    <option value="">— No listing connected —</option>
                    @foreach($listings ?? [] as $listing)
                        <option value="{{ $listing->id }}" {{ $page->agency_listing_id == $listing->id ? 'selected' : '' }}>
                            {{ $listing->title }} — {{ $listing->location }} (€{{ number_format($listing->price ?? 0, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark">Save Listing</button>
            </form>
            @if($page->listing)
            <div style="margin-top:12px;padding:12px;background:#f8f9fa;border-radius:8px;font-size:13px;">
                <strong>Current:</strong> {{ $page->listing->title }} | 
                <strong>Price:</strong> €{{ number_format($page->listing->price ?? 0, 0, ',', '.') }} | 
                <strong>Bedrooms:</strong> {{ $page->listing->bedrooms ?? '—' }} | 
                <strong>Bathrooms:</strong> {{ $page->listing->bathrooms ?? '—' }} |
                <strong>Living Area:</strong> {{ $page->listing->living_area ?? '—' }} m² |
                <strong>Plot:</strong> {{ $page->listing->plot_size ?? '—' }} m²
            </div>
            @endif
        </div>
    </div>

    <form action="{{ route('agency.ai-search-ranking.update-content', $page->id) }}" method="POST">
        @csrf
        @method('PUT')

        @php
            $content = $page->ai_generated_content ?? [];
            $heroArticle = $content['hero_article'] ?? [];
            $propertySummary = $content['property_summary'] ?? [];
            $quickAnswers = $content['quick_answers'] ?? [];
            $faqContent = $content['faq_content'] ?? [];
            $locationData = $content['location_data'] ?? [];
            $marketData = $content['market_data'] ?? [];
            $comparisonData = $content['comparison_data'] ?? [];
            $trustSection = $content['trust_section'] ?? [];
            $investorSection = $content['investor_section'] ?? [];
        @endphp

        {{-- Meta Information --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;">
                <h5 style="margin:0;font-weight:700;"><i class="fa fa-search me-2"></i>SEO Meta Information</h5>
            </div>
            <div style="padding:20px;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ $page->meta_title }}" maxlength="70">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" maxlength="160">{{ $page->meta_description }}</textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 1: Hero Article --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">1</span>
                <h5 style="margin:0;font-weight:700;">Main Article</h5>
            </div>
            <div style="padding:20px;">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Paragraphs (one per line)</label>
                    <textarea name="ai_generated_content[hero_article][paragraphs]" class="form-control" rows="8" style="font-size:14px;">{{ is_array($heroArticle['paragraphs'] ?? null) ? implode("\n\n", $heroArticle['paragraphs']) : ($heroArticle['paragraphs'] ?? '') }}</textarea>
                    <small class="text-muted">Separate paragraphs with blank lines</small>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Key Benefits (one per line)</label>
                    <textarea name="ai_generated_content[hero_article][key_benefits_text]" class="form-control" rows="4">@foreach($heroArticle['key_benefits'] ?? [] as $b){{ is_array($b) ? ($b['title'] ?? '') : $b }}{{ "\n" }}@endforeach</textarea>
                </div>
                <div>
                    <label class="form-label text-muted small fw-bold">Note Strip</label>
                    <input type="text" name="ai_generated_content[hero_article][note]" class="form-control" value="{{ $heroArticle['note'] ?? '' }}">
                </div>
            </div>
        </div>

        {{-- Section 2: Property Summary --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">2</span>
                <h5 style="margin:0;font-weight:700;">Property Summary</h5>
            </div>
            <div style="padding:20px;">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Bullet Points (one per line)</label>
                    <textarea name="ai_generated_content[property_summary][bullets_text]" class="form-control" rows="4">{{ implode("\n", $propertySummary['bullets'] ?? []) }}</textarea>
                </div>
                <label class="form-label text-muted small fw-bold">Stats</label>
                <div class="row g-2">
                    @for($i = 0; $i < 6; $i++)
                    <div class="col-md-2">
                        <input type="text" name="ai_generated_content[property_summary][stats][{{ $i }}][label]" class="form-control form-control-sm mb-1" placeholder="Label" value="{{ $propertySummary['stats'][$i]['label'] ?? '' }}">
                        <input type="text" name="ai_generated_content[property_summary][stats][{{ $i }}][value]" class="form-control form-control-sm" placeholder="Value" value="{{ $propertySummary['stats'][$i]['value'] ?? '' }}">
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Section 3: Quick Answers --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">3</span>
                <h5 style="margin:0;font-weight:700;">Quick Answers for AI</h5>
            </div>
            <div style="padding:20px;">
                @for($i = 0; $i < 4; $i++)
                <div class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="ai_generated_content[quick_answers][{{ $i }}][question]" class="form-control" placeholder="Question" value="{{ $quickAnswers[$i]['question'] ?? '' }}">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="ai_generated_content[quick_answers][{{ $i }}][answer]" class="form-control" placeholder="Answer" value="{{ $quickAnswers[$i]['answer'] ?? '' }}">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Section 4: FAQ --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">4</span>
                <h5 style="margin:0;font-weight:700;">Frequently Asked Questions</h5>
            </div>
            <div style="padding:20px;">
                @for($i = 0; $i < 6; $i++)
                <div class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="ai_generated_content[faq_content][{{ $i }}][question]" class="form-control" placeholder="Question" value="{{ $faqContent[$i]['question'] ?? '' }}">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="ai_generated_content[faq_content][{{ $i }}][answer]" class="form-control" placeholder="Answer" value="{{ $faqContent[$i]['answer'] ?? '' }}">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Section 5: Location --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">5</span>
                <h5 style="margin:0;font-weight:700;">Location & Distances</h5>
            </div>
            <div style="padding:20px;">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Location Description</label>
                    <textarea name="ai_generated_content[location_data][description]" class="form-control" rows="2">{{ $locationData['description'] ?? '' }}</textarea>
                </div>
                <label class="form-label text-muted small fw-bold">Distances</label>
                <div class="row g-2">
                    @for($i = 0; $i < 5; $i++)
                    <div class="col-md-4 col-lg-2">
                        <input type="text" name="ai_generated_content[location_data][distances][{{ $i }}][place]" class="form-control form-control-sm mb-1" placeholder="Place" value="{{ $locationData['distances'][$i]['place'] ?? '' }}">
                        <input type="text" name="ai_generated_content[location_data][distances][{{ $i }}][distance]" class="form-control form-control-sm" placeholder="Distance" value="{{ $locationData['distances'][$i]['distance'] ?? '' }}">
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Section 6: Market Data --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">6</span>
                <h5 style="margin:0;font-weight:700;">Market & Investment Data</h5>
            </div>
            <div style="padding:20px;">
                <label class="form-label text-muted small fw-bold">Metrics</label>
                <div class="row g-3">
                    @for($i = 0; $i < 3; $i++)
                    <div class="col-md-4">
                        <input type="text" name="ai_generated_content[market_data][metrics][{{ $i }}][label]" class="form-control form-control-sm mb-1" placeholder="Label" value="{{ $marketData['metrics'][$i]['label'] ?? '' }}">
                        <input type="text" name="ai_generated_content[market_data][metrics][{{ $i }}][value]" class="form-control form-control-sm mb-1" placeholder="Value" value="{{ $marketData['metrics'][$i]['value'] ?? '' }}">
                        <input type="text" name="ai_generated_content[market_data][metrics][{{ $i }}][source]" class="form-control form-control-sm" placeholder="Source" value="{{ $marketData['metrics'][$i]['source'] ?? '' }}">
                    </div>
                    @endfor
                </div>
                <div class="mt-3">
                    <label class="form-label text-muted small fw-bold">Market Notes (one per line)</label>
                    <textarea name="ai_generated_content[market_data][notes_text]" class="form-control" rows="3">{{ implode("\n", $marketData['notes'] ?? []) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 7: Comparison --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">7</span>
                <h5 style="margin:0;font-weight:700;">Area Comparison</h5>
            </div>
            <div style="padding:20px;">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Comparison Criteria (one per line)</label>
                    <textarea name="ai_generated_content[comparison_data][criteria_text]" class="form-control" rows="3">{{ implode("\n", $comparisonData['criteria'] ?? []) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">This Property Values (one per line, matching criteria)</label>
                    <textarea name="ai_generated_content[comparison_data][this_property_text]" class="form-control" rows="3">{{ implode("\n", $comparisonData['this_property'] ?? []) }}</textarea>
                </div>
                <div>
                    <label class="form-label text-muted small fw-bold">Why Choose This (one per line)</label>
                    <textarea name="ai_generated_content[comparison_data][why_choose_text]" class="form-control" rows="3">{{ implode("\n", $comparisonData['why_choose'] ?? []) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 8: Trust --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">8</span>
                <h5 style="margin:0;font-weight:700;">Trust & Expertise</h5>
            </div>
            <div style="padding:20px;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Agency Name</label>
                        <input type="text" name="ai_generated_content[trust_section][agency_name]" class="form-control" value="{{ $trustSection['agency_name'] ?? $profile->agency_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Tagline</label>
                        <input type="text" name="ai_generated_content[trust_section][tagline]" class="form-control" value="{{ $trustSection['tagline'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Contact Name</label>
                        <input type="text" name="ai_generated_content[trust_section][contact_name]" class="form-control" value="{{ $trustSection['contact_name'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Contact Phone</label>
                        <input type="text" name="ai_generated_content[trust_section][contact_phone]" class="form-control" value="{{ $trustSection['contact_phone'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Contact Email</label>
                        <input type="text" name="ai_generated_content[trust_section][contact_email]" class="form-control" value="{{ $trustSection['contact_email'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Rating</label>
                        <input type="text" name="ai_generated_content[trust_section][rating]" class="form-control" value="{{ $trustSection['rating'] ?? '4.9' }}">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label text-muted small fw-bold">Credentials (one per line)</label>
                        <textarea name="ai_generated_content[trust_section][credentials_text]" class="form-control" rows="3">{{ implode("\n", $trustSection['credentials'] ?? []) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 9: Investor Options --}}
        <div class="vb-card" style="margin-bottom:24px;">
            <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;">
                <span style="width:32px;height:32px;border-radius:8px;background:#111827;color:#fff;display:grid;place-items:center;font-weight:700;">9</span>
                <h5 style="margin:0;font-weight:700;">Investor Options</h5>
            </div>
            <div style="padding:20px;">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label text-muted small fw-bold">Headline</label>
                        <input type="text" name="ai_generated_content[investor_section][headline]" class="form-control" value="{{ $investorSection['headline'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold">Minimum Investment</label>
                        <input type="text" name="ai_generated_content[investor_section][minimum_investment]" class="form-control" value="{{ $investorSection['minimum_investment'] ?? 'USD 30,000+' }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Introduction</label>
                        <textarea name="ai_generated_content[investor_section][intro]" class="form-control" rows="3">{{ $investorSection['intro'] ?? '' }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Disclaimer</label>
                        <textarea name="ai_generated_content[investor_section][disclaimer]" class="form-control" rows="2">{{ $investorSection['disclaimer'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:12px;justify-content:flex-end;margin-bottom:40px;">
            <a href="{{ route('agency.features.show', 'ai_search_ranking') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-dark">
                <i class="fa fa-save me-1"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
