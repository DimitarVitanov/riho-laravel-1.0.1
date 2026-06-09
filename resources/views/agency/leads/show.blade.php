@extends('layouts.simple.master')
@section('title', 'Lead Detail')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6"><h3>Lead: {{ $lead->full_name }}</h3></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h6>Contact Info</h6></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $lead->full_name }}</p>
                    <p><strong>Email:</strong> {{ $lead->email }}</p>
                    <p><strong>Phone:</strong> {{ $lead->phone ?? '—' }}</p>
                    <p><strong>Country:</strong> {{ $lead->country ?? '—' }}</p>
                    <p><strong>Investor Type:</strong> {{ $lead->investor_type ?? '—' }}</p>
                    <p><strong>Interest Amount:</strong> {{ $lead->interest_amount ? number_format($lead->interest_amount, 2) : '—' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h6>Lead Details</h6></div>
                <div class="card-body">
                    <p><strong>Source:</strong> {{ ucfirst(str_replace('_', ' ', $lead->source)) }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($lead->status) }}</span></p>
                    <p><strong>Landing Page:</strong> {{ $lead->landing_page_url ?? '—' }}</p>
                    <p><strong>Created:</strong> {{ $lead->created_at->format('M d, Y H:i') }}</p>
                    @if($lead->message)
                    <p><strong>Message:</strong></p>
                    <p>{{ $lead->message }}</p>
                    @endif
                    <hr>
                    <form action="{{ route('agency.leads.update-status', $lead) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="input-group">
                            <select name="status" class="form-select">
                                @foreach(['new','contacted','qualified','converted','lost'] as $s)
                                <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
