@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="card-title-premium" style="font-size: 2rem;">SYSTEM <span style="color: var(--secondary);">AUDIT TRAIL</span></h2>
        <div class="text-end">
            <span class="badge bg-dark border border-secondary text-secondary px-3 py-2 fw-bold" style="font-size: 0.7rem; letter-spacing: 2px;">SECURED ACCESS</span>
        </div>
    </div>

    <div class="admin-table-container shadow-lg">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 15%">TIMESTAMP</th>
                    <th style="width: 15%">OPERATOR</th>
                    <th style="width: 15%">ACTION</th>
                    <th style="width: 40%">EVENT DESCRIPTION</th>
                    <th style="width: 15%" class="text-end">DIAGNOSTICS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>
                            <div class="fw-bold text-white small">{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-muted smaller fw-bold">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center">
                                    <span class="text-warning fw-black small" style="letter-spacing: 0.5px;">{{ strtoupper($log->user->username) }}</span>
                                </div>
                            @else
                                <span class="text-muted small italic">SYSTEM</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ str_contains($log->action, 'DELETE') ? 'bg-danger' : 'bg-primary' }} text-white px-2 py-1" style="font-size: 0.6rem; letter-spacing: 1px;">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-white-50 small italic">
                            {{ $log->description }}
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-warning rounded-pill px-3" onclick="toggleDetails('details-{{ $log->id }}')" style="font-size: 0.6rem; font-weight: 900;">
                                DATA <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                        </td>
                    </tr>
                    <tr id="details-{{ $log->id }}" class="d-none" style="background: rgba(0,0,0,0.4);">
                        <td colspan="5" class="p-4" style="border-left: 4px solid var(--secondary);">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="smaller text-muted fw-bold text-uppercase mb-2 d-block">Snapshot: PRE-ACTION</label>
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05);">
                                        <pre class="m-0 text-danger smaller" style="max-height: 150px; overflow: auto;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="smaller text-muted fw-bold text-uppercase mb-2 d-block">Snapshot: POST-ACTION</label>
                                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05);">
                                        <pre class="m-0 text-success smaller" style="max-height: 150px; overflow: auto;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-flex gap-3 text-muted" style="font-size: 0.65rem;">
                                        <span><i class="fas fa-network-wired me-1"></i> IP: {{ $log->ip_address }}</span>
                                        <span><i class="fas fa-cube me-1"></i> MODEL: {{ $log->model_type }} #{{ $log->model_id }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="admin-pagination-section mt-4 d-flex justify-content-center">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    el.classList.toggle('d-none');
}
</script>
@endsection
