@extends('layouts.simple.master')
@section('title', 'Generated Content Review')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Generated Content Review</h1>
            <p>Review, approve, or reject AI-generated content across all agencies.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <div class="vb-actions" style="margin-bottom: 20px;">
            <a href="{{ route('admin.villabit.content-review.index', ['status' => 'pending_review']) }}" class="vb-btn {{ request('status') === 'pending_review' ? '' : 'vb-btn-secondary' }}">Pending Review</a>
            <a href="{{ route('admin.villabit.content-review.index', ['status' => 'published']) }}" class="vb-btn {{ request('status') === 'published' ? 'vb-btn-success' : 'vb-btn-secondary' }}">Published</a>
            <a href="{{ route('admin.villabit.content-review.index', ['status' => 'draft']) }}" class="vb-btn {{ request('status') === 'draft' ? '' : 'vb-btn-secondary' }}">Drafts</a>
            <a href="{{ route('admin.villabit.content-review.index') }}" class="vb-btn vb-btn-secondary">All</a>
        </div>

        <table class="vb-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Agency</th>
                    <th>Feature</th>
                    <th>Uniqueness</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td><strong>{{ Str::limit($page->title, 40) }}</strong></td>
                    <td>{{ $page->agencyProfile->agency_name ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $page->feature_key)) }}</td>
                    <td>
                        @php
                            $uClass = match($page->content_uniqueness_status) {
                                'passed' => 'vb-badge-success',
                                'failed' => 'vb-badge-danger',
                                'checking' => 'vb-badge-warning',
                                default => 'vb-badge-info'
                            };
                        @endphp
                        <span class="vb-badge {{ $uClass }}">{{ strtoupper($page->content_uniqueness_status) }}</span>
                    </td>
                    <td>
                        @php
                            $sClass = match($page->status) {
                                'published' => 'vb-badge-success',
                                'pending_review' => 'vb-badge-warning',
                                default => 'vb-badge-muted'
                            };
                        @endphp
                        <span class="vb-badge {{ $sClass }}">{{ ucfirst(str_replace('_', ' ', $page->status)) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.villabit.content-review.show', $page) }}" class="vb-btn vb-btn-sm">Review</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No content to review</h3>
                            <p>No generated content matches the current filter.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px;">{{ $pages->links() }}</div>
    </div>
</div>
@endsection
