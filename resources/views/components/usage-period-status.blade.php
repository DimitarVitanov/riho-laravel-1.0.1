{{-- Usage Period Status Block (June 2026 spec) --}}
<div class="card bg-light border-primary mb-4">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1"><i class="icofont icofont-calendar me-1"></i> Usage Period: {{ now()->format('F Y') }}</h6>
                <small class="text-muted">{{ now()->startOfMonth()->format('M j') }} — {{ now()->endOfMonth()->format('M j, Y') }}</small>
            </div>
            <div class="text-end">
                <span class="badge bg-success">Active</span>
                <br><small class="text-muted">Resets: {{ now()->addMonth()->startOfMonth()->format('M j, Y') }}</small>
            </div>
        </div>
    </div>
</div>
