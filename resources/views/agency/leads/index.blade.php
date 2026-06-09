@extends('layouts.simple.master')
@section('title', 'Leads')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Leads</h1>
            <p>Leads collected through the Invisible Lead Magnet and other AI features.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Interest Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td><strong>{{ $lead->full_name }}</strong></td>
                    <td>{{ $lead->email }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</td>
                    <td>{{ $lead->interest_amount ? '€' . number_format($lead->interest_amount, 0) : '—' }}</td>
                    <td>
                        @php
                            $sClass = match($lead->status) {
                                'converted' => 'vb-badge-success',
                                'new' => 'vb-badge-info',
                                'contacted' => 'vb-badge-warning',
                                'lost' => 'vb-badge-danger',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $sClass }}">{{ ucfirst($lead->status) }}</span>
                    </td>
                    <td>{{ $lead->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('agency.leads.show', $lead) }}" class="vb-btn vb-btn-sm">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="vb-empty">
                            <h3>No leads yet</h3>
                            <p>Leads will appear here once the Invisible Lead Magnet is active.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($leads instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div style="margin-top: 20px;">{{ $leads->links() }}</div>
        @endif
    </div>
</div>
@endsection
