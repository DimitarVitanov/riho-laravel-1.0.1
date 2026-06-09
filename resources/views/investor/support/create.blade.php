@extends('layouts.simple.master')
@section('title', 'New Support Ticket')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>New Support Ticket</h1>
            <p>Create a new support ticket to communicate with your account manager.</p>
        </div>
        <a href="{{ route('investor.support.index') }}" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-card" style="max-width:720px;">
        <form action="{{ route('investor.support.store') }}" method="POST">
            @csrf
            <div class="vb-field" style="margin-bottom:18px;">
                <label>Subject</label>
                <input type="text" name="subject" class="vb-input" value="{{ old('subject') }}" required placeholder="Brief description of your request">
            </div>
            <div class="vb-field" style="margin-bottom:22px;">
                <label>Message</label>
                <textarea name="message" class="vb-textarea" rows="6" required placeholder="Describe your issue or question in detail...">{{ old('message') }}</textarea>
            </div>
            <div class="vb-actions">
                <button type="submit" class="vb-btn">Submit Ticket</button>
                <a href="{{ route('investor.support.index') }}" class="vb-btn vb-btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
