@extends('layouts.simple.master')
@section('title', 'Review Content')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Review Content</h1>
            <p>{{ $page->title }}</p>
        </div>
        <a href="{{ route('admin.villabit.content-review.index') }}" class="vb-btn vb-btn-secondary">Back to List</a>
    </div>

    <div class="vb-grid-2">
        <div class="vb-card">
            <h2 class="vb-section-title">{{ $page->seo_title ?? $page->title }}</h2>
            <p style="color:#6b7280;font-size:13px;margin-bottom:18px;">{{ $page->meta_description }}</p>
            <div style="border-top:1px solid #e5e7eb;padding-top:18px;line-height:1.8;font-size:14px;color:#374151;">
                {!! $page->content_html !!}
            </div>
        </div>

        <div class="vb-card">
            <h2 class="vb-section-title">Details</h2>
            <div style="display:grid;gap:14px;">
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Agency</label>
                    <div style="font-size:14px;font-weight:600;color:#111827;">{{ $page->agencyProfile->agency_name ?? '—' }}</div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Feature</label>
                    <div style="font-size:14px;font-weight:600;color:#111827;">{{ ucfirst(str_replace('_', ' ', $page->feature_key)) }}</div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Uniqueness</label>
                    <div>
                        @php
                            $uClass = match($page->content_uniqueness_status) {
                                'passed' => 'vb-badge-success',
                                'failed' => 'vb-badge-danger',
                                'checking' => 'vb-badge-warning',
                                default => 'vb-badge-info'
                            };
                        @endphp
                        <span class="vb-badge {{ $uClass }}">{{ strtoupper($page->content_uniqueness_status) }}</span>
                    </div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</label>
                    <div><span class="vb-badge vb-badge-warning">{{ ucfirst(str_replace('_', ' ', $page->status)) }}</span></div>
                </div>
                <div>
                    <label style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Created</label>
                    <div style="font-size:14px;color:#111827;">{{ $page->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>

            @if($page->status === 'pending_review')
            <div style="border-top:1px solid #e5e7eb;margin-top:22px;padding-top:18px;">
                <div class="vb-actions">
                    <form action="{{ route('admin.villabit.content-review.approve', $page) }}" method="POST">
                        @csrf
                        <button class="vb-btn vb-btn-success">Approve & Publish</button>
                    </form>
                    <form action="{{ route('admin.villabit.content-review.reject', $page) }}" method="POST">
                        @csrf
                        <button class="vb-btn vb-btn-danger">Reject</button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
