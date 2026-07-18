@extends('layouts.simple.master')
@section('title', 'Agencies')
@section('breadcrumb-title')<h3>Real Estate Agencies</h3>@endsection
@section('breadcrumb-items')
    <li class="breadcrumb-item"><a href="{{ route('admin.villabit.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Agencies</li>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5>All Agencies</h5>
                    <a href="{{ route('admin.villabit.users.create-agency') }}" class="btn btn-primary btn-sm">+ Add Agency</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>#</th><th>Agency</th><th>Owner</th><th>City</th><th>AI Status</th><th>Affiliate</th><th>Status</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @forelse($agencies as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>
                                    <td>{{ $a->agencyProfile->agency_name ?? $a->company_name ?? '—' }}</td>
                                    <td>{{ $a->first_name }} {{ $a->last_name }}</td>
                                    <td>{{ $a->agencyProfile->city ?? '—' }}</td>
                                    <td><span class="badge bg-{{ ($a->agencyProfile->ai_status ?? '') === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($a->agencyProfile->ai_status ?? 'n/a') }}</span></td>
                                    <td>
                                        @if($a->is_reseller_enabled)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <form action="{{ route('admin.villabit.users.enable-reseller', $a) }}" method="POST" class="d-inline">@csrf<button class="btn btn-outline-secondary btn-sm">Enable</button></form>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-{{ $a->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($a->status) }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.villabit.agencies.show', $a) }}" class="btn btn-outline-primary btn-sm">View</a>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="loginAsNewTab({{ $a->id }})">↗ Login As</button>
                                        <form action="{{ route('admin.villabit.users.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete {{ addslashes($a->agencyProfile->agency_name ?? $a->company_name ?? ($a->first_name.' '.$a->last_name)) }} and ALL related data? This cannot be undone.')">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">Delete</button></form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="8" class="text-center text-muted">No agencies found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $agencies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loginAsNewTab(userId) {
        var csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            csrfToken = '{{ csrf_token() }}';
        } else {
            csrfToken = csrfToken.content;
        }
        
        fetch('/admin/villabit/impersonate/' + userId + '/token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.log('Error response:', text);
                    throw new Error('HTTP ' + response.status + ': ' + text.substring(0, 200));
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.url) {
                // Show modal with link to copy for incognito window
                showImpersonateModal(data.url);
            } else {
                alert('Failed to generate login link: ' + JSON.stringify(data));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to generate login link: ' + error.message);
        });
    }

    function showImpersonateModal(url) {
        // Remove existing modal if any
        var existingModal = document.getElementById('impersonateModal');
        if (existingModal) existingModal.remove();

        var modalHtml = `
        <div class="modal fade" id="impersonateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-user-secret me-2"></i>Login As User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>To keep your admin session active:</strong></p>
                        <ol>
                            <li>Open an <strong>Incognito/Private window</strong> (Ctrl+Shift+N or Cmd+Shift+N)</li>
                            <li>Paste this link in the incognito window:</li>
                        </ol>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="impersonateUrl" value="${url}" readonly>
                            <button class="btn btn-primary" type="button" onclick="copyImpersonateUrl()">
                                <i class="fa fa-copy"></i> Copy
                            </button>
                        </div>
                        <div class="alert alert-warning mb-0">
                            <small><i class="fa fa-exclamation-triangle me-1"></i> Link expires in 60 seconds. If you open in a regular tab, your admin session will be replaced.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" onclick="window.open('${url}', '_blank'); bootstrap.Modal.getInstance(document.getElementById('impersonateModal')).hide();">
                            Open in New Tab (replaces admin session)
                        </button>
                    </div>
                </div>
            </div>
        </div>`;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        var modal = new bootstrap.Modal(document.getElementById('impersonateModal'));
        modal.show();
    }

    function copyImpersonateUrl() {
        var urlInput = document.getElementById('impersonateUrl');
        urlInput.select();
        document.execCommand('copy');
        
        var btn = urlInput.nextElementSibling;
        var originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-check"></i> Copied!';
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-success');
        
        setTimeout(function() {
            btn.innerHTML = originalHtml;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-primary');
        }, 2000);
    }
</script>
@endsection
