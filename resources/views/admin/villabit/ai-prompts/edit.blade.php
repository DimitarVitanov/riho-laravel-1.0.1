@extends('layouts.simple.master')
@section('title', 'Edit AI Prompt')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6"><h3>Edit AI Prompt: {{ $prompt->label }}</h3></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.villabit.ai-prompts.update', $prompt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" class="form-control" value="{{ old('label', $prompt->label) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Feature Key</label>
                            <input type="text" class="form-control" value="{{ $prompt->feature_key }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">AI Model Provider</label>
                            <input type="text" name="ai_model_provider" class="form-control" value="{{ old('ai_model_provider', $prompt->ai_model_provider) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">AI Model Name</label>
                            <input type="text" name="ai_model_name" class="form-control" value="{{ old('ai_model_name', $prompt->ai_model_name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prompt Text</label>
                            <textarea name="prompt_text" class="form-control" rows="12" required>{{ old('prompt_text', $prompt->prompt_text) }}</textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ $prompt->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Prompt</button>
                        <a href="{{ route('admin.villabit.ai-prompts.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
