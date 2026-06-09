@extends('layouts.simple.master')
@section('title', 'Content Detail')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6"><h3>{{ $page->title }}</h3></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="content-preview">{!! $page->content_html !!}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h6>Details</h6></div>
                <div class="card-body">
                    <p><strong>Agency:</strong> {{ $page->agencyProfile->agency_name ?? '—' }}</p>
                    <p><strong>Feature:</strong> {{ $page->feature_key }}</p>
                    <p><strong>Uniqueness:</strong> {{ strtoupper($page->content_uniqueness_status) }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($page->status) }}</p>
                    <p><strong>Created:</strong> {{ $page->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
