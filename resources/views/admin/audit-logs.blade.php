@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="card-title-premium" style="font-size: 2rem;">SYSTEM <span style="color: var(--secondary);">LOGS</span></h2>
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
                    <tr id="details-{{ $log->id }}" class="d-none" style="background: rgba(0,0,0,0.5);">
                        <td colspan="5" class="p-4" style="border-left: 4px solid var(--secondary);">
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="smaller text-muted fw-bold text-uppercase mb-3 ls-1">ACTION MODIFICATIONS</h6>
                                    
                                    @php
                                        $old = is_array($log->old_values) ? $log->old_values : [];
                                        $new = is_array($log->new_values) ? $log->new_values : [];
                                        $allKeys = array_unique(array_merge(array_keys($old), array_keys($new)));
                                        $changesFound = false;
                                    @endphp

                                    <div class="d-flex flex-column gap-2">
                                        @foreach($allKeys as $key)
                                            @php
                                                $oldVal = $old[$key] ?? 'null';
                                                $newVal = $new[$key] ?? 'null';
                                                // Skip timestamps and IDs unless they are the main focus
                                                if(in_array($key, ['updated_at', 'created_at', 'id'])) continue;
                                            @endphp

                                            @if($oldVal != $newVal)
                                                @php $changesFound = true; @endphp
                                                <div class="p-2 rounded-3 d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                                    <span class="text-secondary fw-black small text-uppercase" style="font-size: 0.65rem; width: 150px;">{{ str_replace('_', ' ', $key) }}</span>
                                                    <div class="flex-grow-1 d-flex align-items-center gap-3">
                                                        <span class="text-danger small text-decoration-line-through opacity-50">{{ is_array($oldVal) ? 'DATA' : $oldVal }}</span>
                                                        <i class="fas fa-long-arrow-alt-right text-warning"></i>
                                                        <span class="text-success small fw-bold">{{ is_array($newVal) ? 'DATA' : $newVal }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        @if(!$changesFound)
                                            <div class="text-muted small italic">Record created or no specific field changes tracked.</div>
                                            @if(!empty($new))
                                                <div class="mt-2 p-3 rounded-3 small text-white-50" style="background: rgba(0,0,0,0.3); font-family: monospace;">
                                                    @foreach($new as $k => $v)
                                                        @if(!is_array($v))
                                                            <div><span class="text-secondary">{{ $k }}:</span> {{ $v }}</div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Technical metadata removed for clarity --}}
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
