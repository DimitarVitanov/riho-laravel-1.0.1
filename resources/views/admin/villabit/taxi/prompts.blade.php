@extends('layouts.simple.master')
@section('title', 'TAXI — Global Data AI Prompts')
@section('breadcrumb-title')<h3>Global Data AI Prompts</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.taxi.global-data') }}">TAXI · Global Data</a></li>
    <li class="breadcrumb-item active">AI Prompts</li>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

    <div class="alert alert-light border">
        These are the exact prompts used by the 30-day refresh cron. <code>GLOBAL-001</code> is prepended to every
        section call. <strong>Section id</strong> links a prompt to the matching block in the report HTML
        (for example <code>prices</code>, <code>rental</code>, <code>faq</code>).
    </div>

    <div class="accordion" id="promptAccordion">
        @foreach($prompts as $prompt)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#p{{ $prompt->id }}">
                        <span class="badge bg-primary me-2">{{ $prompt->key }}</span>
                        {{ $prompt->label }}
                        @if($prompt->section_id)
                            <code class="ms-2 small">#{{ $prompt->section_id }}</code>
                        @endif
                        @unless($prompt->is_active)
                            <span class="badge bg-secondary ms-2">inactive</span>
                        @endunless
                    </button>
                </h2>
                <div id="p{{ $prompt->id }}" class="accordion-collapse collapse" data-bs-parent="#promptAccordion">
                    <div class="accordion-body">
                        <form method="POST" action="{{ route('admin.villabit.taxi.prompts.update', $prompt) }}">
                            @csrf @method('PUT')
                            @if($prompt->placement)
                                <p class="small text-muted mb-2"><strong>HTML placement:</strong> {{ $prompt->placement }}</p>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Section id in HTML</label>
                                    <input type="text" name="section_id" class="form-control"
                                           value="{{ $prompt->section_id }}" placeholder="e.g. prices">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                               id="a{{ $prompt->id }}" @checked($prompt->is_active)>
                                        <label class="form-check-label" for="a{{ $prompt->id }}">Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Prompt</label>
                                <textarea name="prompt_text" rows="12" class="form-control font-monospace small">{{ $prompt->prompt_text }}</textarea>
                            </div>
                            <button class="btn btn-primary btn-sm mt-3">Save prompt</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
