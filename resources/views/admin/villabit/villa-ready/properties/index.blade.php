@extends('layouts.simple.master')
@section('title', 'Villa Ready Properties')
@section('breadcrumb-title')<h3>Villa Ready Properties</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Villa Ready Properties</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>All Properties</h5>
                    <a href="{{ route('admin.villabit.villa-ready.properties.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Property
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Images</th>
                                    <th>Units</th>
                                    <th>Agencies</th>
                                    <th>Referrals</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($properties as $property)
                                <tr>
                                    <td><code>{{ $property->property_id }}</code></td>
                                    <td>
                                        <strong>{{ Str::limit($property->title, 40) }}</strong>
                                        <br><small class="text-muted">{{ $property->slug }}</small>
                                    </td>
                                    <td>{{ $property->location }}</td>
                                    <td>
                                        @if($property->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @elseif($property->status === 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($property->status === 'reserved')
                                            <span class="badge bg-warning">Reserved</span>
                                        @else
                                            <span class="badge bg-danger">Sold</span>
                                        @endif
                                    </td>
                                    <td>{{ $property->images_count }}</td>
                                    <td>{{ $property->units_count }}</td>
                                    <td>{{ $property->publications_count }}</td>
                                    <td>{{ $property->referrals_count }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <a href="{{ url('/properties/' . $property->slug) }}" target="_blank" class="btn btn-sm btn-outline-success">Preview</a>
                                            <a href="{{ route('admin.villabit.villa-ready.properties.edit', $property) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('admin.villabit.villa-ready.properties.destroy', $property) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this property?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">No properties yet.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $properties->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
