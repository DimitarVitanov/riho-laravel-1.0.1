@extends('layouts.simple.master')
@section('title', 'Ticket #' . $ticket->id)

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ $ticket->subject }}</h1>
            <p>Ticket #{{ $ticket->id }} · Created {{ $ticket->created_at->format('M d, Y H:i') }}</p>
        </div>
        <a href="{{ route('investor.support.index') }}" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-grid-2">
        <div>
            <!-- Original Message -->
            <div class="vb-message vb-message-own" style="margin-bottom:16px;">
                <div class="vb-message-meta">You · {{ $ticket->created_at->format('M d, Y H:i') }}</div>
                <div class="vb-message-body">{{ $ticket->message }}</div>
            </div>

            <!-- Thread Messages -->
            @foreach($ticket->messages as $msg)
            <div class="vb-message {{ $msg->user_id === auth()->id() ? 'vb-message-own' : 'vb-message-other' }}">
                <div class="vb-message-meta">{{ $msg->user->full_name ?? 'System' }} · {{ $msg->created_at->format('M d, Y H:i') }}</div>
                <div class="vb-message-body">{{ $msg->message }}</div>
            </div>
            @endforeach

            <!-- Reply Form -->
            @if(in_array($ticket->status, ['open', 'in_progress', 'waiting']))
            <div class="vb-card" style="margin-top:20px;">
                <form action="{{ route('investor.support.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="vb-field" style="margin-bottom:14px;">
                        <label>Reply</label>
                        <textarea name="message" class="vb-textarea" rows="3" placeholder="Type your reply..." required></textarea>
                    </div>
                    <button type="submit" class="vb-btn vb-btn-sm">Send Reply</button>
                </form>
            </div>
            @endif
        </div>

        <!-- Ticket Info Sidebar -->
        <div class="vb-card" style="align-self:start;">
            <h2 class="vb-section-title">Ticket Info</h2>
            <div style="display:grid;gap:14px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</label>
                    <div>
                        @php
                            $tClass = match($ticket->status) {
                                'resolved' => 'vb-badge-success',
                                'open' => 'vb-badge-info',
                                'in_progress' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $tClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Priority</label>
                    <div style="font-size:14px;font-weight:600;color:#111827;">{{ ucfirst($ticket->priority) }}</div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Created</label>
                    <div style="font-size:14px;color:#111827;">{{ $ticket->created_at->format('M d, Y H:i') }}</div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Messages</label>
                    <div style="font-size:14px;color:#111827;">{{ $ticket->messages->count() + 1 }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
