@extends('layouts.simple.master')
@section('title', 'Content Review')

@section('main_content')
<div class="container-fluid">
    <div class="vb-page-header">
        <div>
            <h1>Content Review</h1>
            <p>Review AI-generated content for your assigned agencies before publication.</p>
        </div>
    </div>

    @include('components.villabit.usage-banner')

    <div class="vb-card">
        <table class="vb-table">
            <thead>
                <tr>
                    <th>Draft</th>
                    <th>Agency</th>
                    <th>Feature</th>
                    <th>Uniqueness</th>
                    <th>Status</th>
                    <th>Action</th>
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
                    <td><span class="vb-badge vb-badge-warning">{{ ucfirst($page->status) }}</span></td>
                    <td><a href="{{ route('manager.content-review.show', $page) }}" class="vb-btn vb-btn-sm">Review</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="vb-empty">
                            <h3>No content pending review</h3>
                            <p>All content has been reviewed or no new drafts are available.</p>
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
