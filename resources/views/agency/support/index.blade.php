@extends('layouts.simple.master')
@section('title', 'Support')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Messages & Support</h1>
            <p>Create and manage support tickets. Communicate with Villa Bit AI team.</p>
        </div>
        <a href="{{ route('agency.support.create') }}" class="vb-btn">New Ticket</a>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>#</th>
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
                    <td><strong>{{ $ticket->subject }}</strong></td>
                    <td>
                        @php
                            $tClass = match($ticket->status) {
                                'resolved' => 'vb-badge-success',
                                'open' => 'vb-badge-info',
                                'in_progress' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $tClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                    </td>
                    <td>{{ ucfirst($ticket->priority ?? 'normal') }}</td>
                    <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('agency.support.show', $ticket) }}" class="vb-btn vb-btn-sm">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No support tickets</h3>
                            <p>Click "New Ticket" to create your first support request.</p>
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
