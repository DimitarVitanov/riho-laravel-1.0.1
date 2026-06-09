@extends('layouts.simple.master')
@section('title', 'Ticket #' . $ticket->id)

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>{{ $ticket->subject }}</h1>
            <p>Ticket #{{ $ticket->id }} · From: {{ $ticket->user->first_name }} {{ $ticket->user->last_name }} ({{ ucfirst(str_replace('_', ' ', $ticket->user->role ?? '')) }}) · Created {{ $ticket->created_at->format('M d, Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.villabit.support-tickets.index') }}" class="vb-btn vb-btn-secondary">Back to Tickets</a>
    </div>

    <div class="vb-grid-2">
        <div>
            <!-- Original Message -->
            <div class="vb-message vb-message-other" style="margin-bottom:16px;">
                <div class="vb-message-meta">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }} · {{ $ticket->created_at->format('M d, Y H:i') }}</div>
                <div class="vb-message-body">{{ $ticket->message }}</div>
            </div>

            <!-- Thread Messages -->
            @foreach($ticket->messages as $msg)
            <div class="vb-message {{ $msg->user_id === $ticket->user_id ? 'vb-message-other' : 'vb-message-own' }}">
                <div class="vb-message-meta">
                    {{ $msg->user->first_name ?? 'System' }} {{ $msg->user->last_name ?? '' }}
                    @if($msg->user_id !== $ticket->user_id)
                        <span class="vb-badge vb-badge-info" style="font-size:10px;padding:2px 6px;">Staff</span>
                    @endif
                    · {{ $msg->created_at->format('M d, Y H:i') }}
                </div>
                <div class="vb-message-body">{{ $msg->message }}</div>
            </div>
            @endforeach

            <!-- Reply Form -->
            @if(in_array($ticket->status, ['open', 'in_progress', 'waiting']))
            <div class="vb-card" style="margin-top:20px;">
                <form action="{{ route('admin.villabit.support-tickets.reply', $ticket) }}" method="POST">
                    @csrf
                    <div class="vb-field" style="margin-bottom:14px;">
                        <label>Reply to {{ $ticket->user->first_name }}</label>
                        <textarea name="message" class="vb-textarea" rows="4" placeholder="Type your reply..." required></textarea>
                    </div>
                    <button type="submit" class="vb-btn">Send Reply</button>
                </form>
            </div>
            @else
            <div class="vb-card" style="margin-top:20px;padding:16px;text-align:center;">
                <p style="margin:0;color:#6b7280;">This ticket is <strong>{{ $ticket->status }}</strong>. Reopen it to reply.</p>
            </div>
            @endif
        </div>

        <!-- Ticket Info Sidebar -->
        <div class="vb-card" style="align-self:start;">
            <h2 class="vb-section-title">Ticket Info</h2>
            <div style="display:grid;gap:14px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</label>
                    <form action="{{ route('admin.villabit.support-tickets.update-status', $ticket) }}" method="POST" style="display:flex;gap:8px;margin-top:6px;">
                        @csrf
                        <select name="status" class="vb-input" style="width:auto;min-width:130px;">
                            <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit" class="vb-btn vb-btn-sm">Update</button>
                    </form>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Priority</label>
                    <p style="margin:4px 0 0;font-weight:600;">{{ ucfirst($ticket->priority ?? 'normal') }}</p>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Submitted by</label>
                    <p style="margin:4px 0 0;">{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}<br><span style="color:#6b7280;font-size:12px;">{{ $ticket->user->email }}</span></p>
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
