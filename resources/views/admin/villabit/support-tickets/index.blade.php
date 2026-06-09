@extends('layouts.simple.master')
@section('title', 'Support Tickets')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Support Tickets</h1>
            <p>Manage all support tickets from investors and agencies.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <!-- Filter -->
    <div class="vb-card" style="margin-bottom:20px;padding:16px;">
        <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <select name="status" class="vb-input" style="width:auto;min-width:160px;">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <button type="submit" class="vb-btn vb-btn-sm">Filter</button>
            @if(request('status'))
                <a href="{{ route('admin.villabit.support-tickets.index') }}" class="vb-btn vb-btn-sm vb-btn-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->user->first_name ?? '' }} {{ $ticket->user->last_name ?? '' }}</td>
                    <td><span class="vb-badge vb-badge-muted">{{ ucfirst(str_replace('_', ' ', $ticket->user->role ?? '')) }}</span></td>
                    <td><strong>{{ $ticket->subject }}</strong></td>
                    <td>
                        @php
                            $tClass = match($ticket->status) {
                                'resolved' => 'vb-badge-success',
                                'open' => 'vb-badge-info',
                                'in_progress' => 'vb-badge-warning',
                                'closed' => 'vb-badge-muted',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $tClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </td>
                    <td>{{ ucfirst($ticket->priority ?? 'normal') }}</td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('admin.villabit.support-tickets.show', $ticket) }}" class="vb-btn vb-btn-sm">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="vb-empty">
                            <h3>No support tickets</h3>
                            <p>No tickets have been submitted yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($tickets->hasPages())
        <div style="margin-top:16px;">
            {{ $tickets->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
