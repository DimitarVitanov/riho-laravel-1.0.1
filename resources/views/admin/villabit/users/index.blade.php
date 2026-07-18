@extends('layouts.simple.master')

@section('title', 'User Management')

@section('breadcrumb-title')
    <h3>User Management</h3>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>All Users</h5>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    + Add User
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-manager') }}">Add Manager</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-agency') }}">Add Agency</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.villabit.users.create-investor') }}">Add Investor</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-auto">
                                <select name="role" class="form-select form-select-sm">
                                    <option value="">All Roles</option>
                                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="manager" {{ request('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="real_estate_agency" {{ request('role') === 'real_estate_agency' ? 'selected' : '' }}>Agency</option>
                                    <option value="investor" {{ request('role') === 'investor' ? 'selected' : '' }}>Investor</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="waitlist" {{ request('status') === 'waitlist' ? 'selected' : '' }}>Waitlist</option>
                                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                            </div>
                        </form>

                        <div class="table-responsive" style="overflow: visible;">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Server Type</th>
                                        <th>Reseller</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $u)
                                        <tr>
                                            <td>{{ $u->id }}</td>
                                            <td>
                                                <a href="#" class="text-primary fw-semibold" style="text-decoration:none; color:black !important;" 
                                                   data-bs-toggle="modal" data-bs-target="#userModal{{ $u->id }}">
                                                    {{ $u->first_name }} {{ $u->last_name }}
                                                </a>
                                            </td>
                                            <td>{{ $u->email }}</td>
                                            <td>
                                                @php
                                                    $roleColors = [
                                                        'super_admin'       => 'bg-danger',
                                                        'admin'             => 'bg-warning text-dark',
                                                        'manager'           => 'bg-primary text-white',
                                                        'real_estate_agency'=> 'bg-primary text-white',
                                                        'investor'          => 'bg-success',
                                                    ];
                                                    $roleLabels = [
                                                        'super_admin'       => 'Super Admin',
                                                        'admin'             => 'Admin',
                                                        'manager'           => 'Manager',
                                                        'real_estate_agency'=> 'Real Estate Agency',
                                                        'investor'          => 'Investor',
                                                    ];
                                                    $roleColor = $roleColors[$u->role] ?? 'bg-secondary';
                                                    $roleLabel = $roleLabels[$u->role] ?? ucfirst(str_replace('_', ' ', $u->role));
                                                @endphp
                                                <span class="badge {{ $roleColor }}">{{ $roleLabel }}</span>
                                            </td>
                                            <td>
                                                @if($u->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($u->status === 'waitlist')
                                                    <span class="badge bg-warning">Waitlist</span>
                                                @elseif($u->status === 'suspended')
                                                    <span class="badge bg-danger">Suspended</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($u->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($u->agency_server_type === 'subdomain_ai_server')
                                                    <span class="badge bg-dark">Subdomain</span>
                                                @elseif($u->agency_server_type === 'domain_folder_ai_server')
                                                    <span class="badge bg-secondary">Domain Folder</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($u->is_reseller_enabled)
                                                    <span class="badge bg-info">{{ $u->referral_code }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $u->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-strategy="fixed" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" style="background-color:#fff !important; z-index:9999 !important; box-shadow:0 4px 16px rgba(0,0,0,0.15);">
                                                        @if(!$u->isAdmin())
                                                            @if($u->status === 'waitlist')
                                                                <li>
                                                                    <form action="{{ route('admin.villabit.users.approve-waitlist', $u) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item text-success">
                                                                            ✔ Approve Access
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <form action="{{ route('admin.villabit.users.toggle-status', $u) }}" method="POST">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item {{ $u->status === 'active' ? 'text-warning' : 'text-success' }}">
                                                                            {{ $u->status === 'active' ? '⏸ Suspend' : '▶ Activate' }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                            <li>
                                                                <form action="{{ route('admin.villabit.impersonate.start', $u) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item text-info">
                                                                        → Login As
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('admin.villabit.users.destroy', $u) }}" method="POST"
                                                                      onsubmit="return confirm('Delete {{ addslashes($u->first_name . ' ' . $u->last_name) }}? This cannot be undone.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="dropdown-item text-danger">
                                                                        🗑 Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @else
                                                            <li><span class="dropdown-item text-muted">No actions</span></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">No users found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $users->links() }}
                    </div>
                </div>

                {{-- User Details Modals (outside table) --}}
                @foreach($users as $u)
                @php
                    $roleColors = [
                        'super_admin'       => 'bg-danger',
                        'admin'             => 'bg-warning text-dark',
                        'manager'           => 'bg-primary text-white',
                        'real_estate_agency'=> 'bg-primary text-white',
                        'investor'          => 'bg-success',
                    ];
                    $roleLabels = [
                        'super_admin'       => 'Super Admin',
                        'admin'             => 'Admin',
                        'manager'           => 'Manager',
                        'real_estate_agency'=> 'Real Estate Agency',
                        'investor'          => 'Investor',
                    ];
                @endphp
                <div class="modal fade" id="userModal{{ $u->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $u->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="userModalLabel{{ $u->id }}">
                                    User Details: {{ $u->first_name }} {{ $u->last_name }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Basic Information</h6>
                                        <table class="table table-sm">
                                            <tr><th style="width:40%">ID</th><td>{{ $u->id }}</td></tr>
                                            <tr><th>First Name</th><td>{{ $u->first_name ?? '—' }}</td></tr>
                                            <tr><th>Last Name</th><td>{{ $u->last_name ?? '—' }}</td></tr>
                                            <tr><th>Email</th><td><a style="color:black !important;" href="mailto:{{ $u->email }}">{{ $u->email }}</a></td></tr>
                                            <tr><th>Phone</th><td>
                                                @if($u->phone)
                                                    @php
                                                        $phoneCountryCode = match($u->country) {
                                                            'Republic of Croatia', 'Croatia', 'HR' => '+385',
                                                            'Slovenia', 'SI' => '+386',
                                                            'Serbia', 'RS' => '+381',
                                                            'Bosnia and Herzegovina', 'BA' => '+387',
                                                            'Montenegro', 'ME' => '+382',
                                                            'North Macedonia', 'MK' => '+389',
                                                            'Austria', 'AT' => '+43',
                                                            'Germany', 'DE' => '+49',
                                                            'Italy', 'IT' => '+39',
                                                            'Hungary', 'HU' => '+36',
                                                            'United States', 'US' => '+1',
                                                            'United Kingdom', 'UK', 'GB' => '+44',
                                                            default => ''
                                                        };
                                                    @endphp
                                                    {{ $phoneCountryCode }} {{ $u->phone }}
                                                @else
                                                    —
                                                @endif
                                            </td></tr>
                                            <tr><th>Role</th><td><span class="badge {{ $roleColors[$u->role] ?? 'bg-secondary' }}">{{ $roleLabels[$u->role] ?? ucfirst($u->role) }}</span></td></tr>
                                            <tr><th>Status</th><td>
                                                @if($u->status === 'active')<span class="badge bg-success">Active</span>
                                                @elseif($u->status === 'waitlist')<span class="badge bg-warning">Waitlist</span>
                                                @else<span class="badge bg-secondary">{{ ucfirst($u->status) }}</span>@endif
                                            </td></tr>
                                            <tr><th>Joined</th><td>{{ $u->created_at->format('M j, Y H:i') }}</td></tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">Additional Information</h6>
                                        <table class="table table-sm">
                                            <tr><th style="width:40%">Company</th><td>{{ $u->company_name ?? '—' }}</td></tr>
                                            <tr><th>Country</th><td>{{ $u->country ?? '—' }}</td></tr>
                                            <tr><th>Timezone</th><td>{{ $u->timezone ?? '—' }}</td></tr>
                                            <tr><th>Language</th><td>{{ $u->preferred_language ?? 'en' }}</td></tr>
                                            <tr><th>Referral Code</th><td>{{ $u->referral_code ?? '—' }}</td></tr>
                                            <tr><th>Reseller</th><td>{{ $u->is_reseller_enabled ? 'Yes' : 'No' }}</td></tr>
                                            <tr><th>Last Login</th><td>{{ $u->last_login_at ? $u->last_login_at->format('M j, Y H:i') : '—' }}</td></tr>
                                            <tr><th>Internal Notes</th><td>{{ Str::limit($u->notes_internal, 50) ?? '—' }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                                @if($u->role === 'real_estate_agency' && $u->agencyProfile)
                                <hr>
                                <h6 class="text-muted mb-3">Agency Profile</h6>
                                <table class="table table-sm">
                                    <tr><th style="width:20%">Agency Name</th><td>{{ $u->agencyProfile->agency_name ?? '—' }}</td></tr>
                                    <tr><th>Subdomain</th><td>{{ $u->agencyProfile->subdomain ?? '—' }}</td></tr>
                                    <tr><th>Contact Email</th><td>{{ $u->agencyProfile->contact_email ?? '—' }}</td></tr>
                                    <tr><th>Contact Phone</th><td>{{ $u->agencyProfile->contact_phone ?? '—' }}</td></tr>
                                    <tr><th>Website</th><td>{{ $u->agencyProfile->website ?? '—' }}</td></tr>
                                </table>
                                @endif
                                @if($u->role === 'investor' && $u->investorProfile)
                                <hr>
                                <h6 class="text-muted mb-3">Investor Profile</h6>
                                <table class="table table-sm">
                                    <tr><th style="width:20%">Investor Type</th><td>{{ $u->investorProfile->investor_type ?? '—' }}</td></tr>
                                    <tr><th>Accreditation</th><td>{{ $u->investorProfile->accreditation_status ?? '—' }}</td></tr>
                                    <tr><th>Investment Range</th><td>{{ $u->investorProfile->investment_range ?? '—' }}</td></tr>
                                </table>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                @if(!$u->isAdmin())
                                <form action="{{ route('admin.villabit.impersonate.start', $u) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info">Login As User</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (el) {
            el.addEventListener('shown.bs.dropdown', function () {
                if (typeof feather !== 'undefined') feather.replace();
            });
        });
    });
</script>
@endpush
