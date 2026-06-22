@extends('layouts.simple.master')
@section('title', 'New Support Ticket')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>New Support Ticket</h1>
            <p>Create a new support ticket to communicate with Villa Bit AI team.</p>
        </div>
        <a href="{{ route('agency.support.index') }}" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-card" style="max-width:720px;">
        <form action="{{ route('agency.support.store') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:1fr auto;gap:16px;margin-bottom:18px;align-items:end;">
                <div class="vb-field">
                    <label>Subject</label>
                    <input type="text" name="subject" class="vb-input" value="{{ old('subject') }}" required placeholder="Brief description of your request">
                </div>
                <div class="vb-field" style="min-width:160px;">
                    <label>Urgency</label>
                    <select name="priority" class="form-select" style="background:#f9fafb;border:1px solid #d9dde3;border-radius:10px;padding:10px;" required>
                        <option value="medium" {{ old('priority','medium')==='medium' ? 'selected' : '' }}>Normal</option>
                        <option value="high"   {{ old('priority')==='high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority')==='urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>
            <div class="vb-field" style="margin-bottom:22px;">
                <label>Message</label>
                <textarea name="message" class="vb-textarea" rows="6" required placeholder="Describe your issue or question in detail...">{{ old('message') }}</textarea>
            </div>
            <div class="vb-actions">
                <button type="submit" class="vb-btn">Submit Ticket</button>
                <a href="{{ route('agency.support.index') }}" class="vb-btn vb-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
