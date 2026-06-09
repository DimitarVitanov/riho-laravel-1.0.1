@extends('layouts.simple.master')
@section('title', 'Ticket #' . $ticket->id)

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ $ticket->subject }}</h1>
            <p>Ticket #{{ $ticket->id }} · Created {{ $ticket->created_at->format('M d, Y H:i') }}</p>
        </div>
        <a href="{{ route('agency.support.index') }}" class="vb-btn vb-btn-secondary">Back to Tickets</a>
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
                <div class="vb-message-meta">
                    {{ $msg->user_id === auth()->id() ? 'You' : ($msg->user->first_name ?? 'Support') . ' ' . ($msg->user->last_name ?? '') }}
                    · {{ $msg->created_at->format('M d, Y H:i') }}
                </div>
                <div class="vb-message-body">{{ $msg->message }}</div>
            </div>
            @endforeach

            <!-- Reply Form -->
            @if(in_array($ticket->status, ['open', 'in_progress', 'waiting']))
            <div class="vb-card" style="margin-top:20px;">
                <form action="{{ route('agency.support.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="vb-field" style="margin-bottom:14px;">
                        <label>Reply</label>
                        <textarea name="message" class="vb-textarea" rows="3" placeholder="Type your reply..." required></textarea>
                    </div>
                    <button type="submit" class="vb-btn vb-btn-sm">Send Reply</button>
                </form>
            </div>
            @else
            <div class="vb-card" style="margin-top:20px;padding:16px;text-align:center;">
                <p style="margin:0;color:#6b7280;">This ticket has been <strong>{{ $ticket->status }}</strong>.</p>
            </div>
            @endif
        </div>

        <!-- Ticket Info Sidebar -->
        <div class="vb-card" style="align-self:start;">
            <h2 class="vb-section-title">Ticket Info</h2>
            <div style="display:grid;gap:14px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</label>
                    @php
                        $tClass = match($ticket->status) {
                            'resolved' => 'vb-badge-success',
                            'open' => 'vb-badge-info',
                            'in_progress' => 'vb-badge-warning',
                            default => 'vb-badge-muted'
                        };
                    @endphp
                    <p style="margin:4px 0 0;"><span class="vb-badge {{ $tClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></p>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Priority</label>
                    <p style="margin:4px 0 0;font-weight:600;">{{ ucfirst($ticket->priority ?? 'normal') }}</p>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Messages</label>
                    <p style="margin:4px 0 0;font-weight:600;">{{ $ticket->messages->count() + 1 }}</p>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Created</label>
                    <p style="margin:4px 0 0;">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
