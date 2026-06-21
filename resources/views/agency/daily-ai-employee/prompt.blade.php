@extends('layouts.simple.master')
@section('title', 'Daily AI Employee - Prompt Editor')
@section('breadcrumb-title')
    <h3>AI Prompt Editor</h3>
@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('agency.dashboard') }}">{{ __('messages.agency_panel') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('agency.daily-ai-employee.index') }}">Daily AI Employee</a></li>
    <li class="breadcrumb-item active">Prompt Editor</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">Customize AI Prompt</h5>
                        <small class="text-muted">Modify how the AI generates content for your agency</small>
                    </div>
                    <a href="{{ route('agency.daily-ai-employee.index') }}" class="btn btn-outline-secondary btn-sm">← Back to Inbox</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('agency.daily-ai-employee.save-settings') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Custom Prompt</label>
                            <textarea name="custom_prompt" class="form-control" rows="15" style="font-family: monospace;">{{ $customPrompt }}</textarea>
                            <small class="text-muted">This prompt instructs the AI how to generate content for your agency.</small>
                        </div>
                        
                        <div class="d-flex gap-3 mb-4">
                            <button type="submit" class="btn btn-dark px-3">
                                <i class="fas fa-save me-2"></i>Save Prompt
                            </button>
                            <button type="button" class="btn btn-outline-secondary px-3" data-bs-toggle="modal" data-bs-target="#resetModal">
                                <i class="fas fa-undo me-2"></i>Reset to Default
                            </button>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <div class="mt-4">
                        <h6 class="fw-bold">Prompt Tips:</h6>
                        <ul class="text-muted">
                            <li>Be specific about your real estate market and location</li>
                            <li>Include your target audience (buyers, sellers, investors)</li>
                            <li>Mention your unique selling propositions</li>
                            <li>Specify tone (professional, friendly, luxury, etc.)</li>
                            <li>Add any keywords you want the AI to focus on</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reset to Default Modal --}}
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('agency.daily-ai-employee.save-settings') }}" method="POST">
                @csrf
                <input type="hidden" name="reset_prompt" value="1">
                <div class="modal-header">
                    <h5 class="modal-title">Reset to Default?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will replace your custom prompt with the default template.</p>
                    <div class="bg-light p-3 rounded">
                        <small class="text-muted" style="white-space: pre-wrap;">{{ $defaultPrompt }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset to Default</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
