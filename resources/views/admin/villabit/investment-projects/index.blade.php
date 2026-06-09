@extends('layouts.simple.master')
@section('title', 'Investment Projects')
@section('breadcrumb-title')<h3>Investment Projects</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Investment Projects</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>All Projects</h5>
                    <a href="{{ route('admin.villabit.investment-projects.create') }}" class="btn btn-primary btn-sm">+ New Project</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Code</th><th>Name</th><th>Location</th><th>Status</th><th>Target</th><th>Actions</th></tr></thead>
                            <tbody>
                            @forelse($projects as $p)
                            <tr>
                                <td>{{ $p->project_code }}</td>
                                <td>{{ $p->project_name }}</td>
                                <td>{{ $p->project_location ?? '—' }}</td>
                                <td><span class="badge bg-info">{{ ucfirst($p->project_status) }}</span></td>
                                <td>{{ number_format($p->target_raise_amount, 2) }}</td>
                                <td><a href="{{ route('admin.villabit.investment-projects.show', $p) }}" class="btn btn-outline-primary btn-sm">View</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No projects found.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
