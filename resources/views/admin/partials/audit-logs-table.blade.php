@foreach($logs as $log)
<tr class="audit-row">
    <td>
        <div class="fw-bold text-white opacity-75">{{ $log->created_at->format('M d, Y') }}</div>
        <div class="text-muted smaller">{{ $log->created_at->format('H:i:s') }}</div>
    </td>
    <td>
        @php $red = '#ff4b5c'; @endphp
        <span class="badge text-uppercase px-3 py-2" style="background: {{ $red }}22; border: 1px solid {{ $red }}; color: {{ $red }}; letter-spacing: 1px; font-size: 0.65rem; font-weight: 900;">
            {{ $log->user ? $log->user->username : 'ADMIN' }}
        </span>
    </td>
    <td>
        <span class="text-white-50 fw-normal smaller text-uppercase ls-2">
            {{ str_replace('_', ' ', $log->action) }}
        </span>
    </td>
    <td>
        <div class="text-white small opacity-75">{{ $log->description }}</div>
    </td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2 detail-btn" onclick="toggleDetails('details-{{ $log->id }}')">
            DETAIL <i class="fas fa-chevron-down ms-1" style="font-size: 0.6rem;"></i>
        </button>
    </td>
</tr>
<tr id="details-{{ $log->id }}" class="d-none">
    <td colspan="5" class="p-3 bg-black bg-opacity-25">
        <div class="p-3 rounded bg-dark border border-secondary border-opacity-10 shadow-inner">
            <h6 class="text-white mb-3 smaller fw-normal text-uppercase ls-2 opacity-50">ENTRY #{{ $log->id }}</h6>
            
            <div class="row g-2">
                @if(is_array($log->new_values))
                    @foreach($log->new_values as $key => $value)
                        @if(!in_array($key, ['password', '_token', 'created_at', 'updated_at']))
                        <div class="col-md-4">
                            <div class="d-flex flex-column p-2 rounded bg-black bg-opacity-20 border border-secondary border-opacity-5">
                                <span class="text-muted smaller text-uppercase fw-bold mb-1" style="font-size: 0.6rem;">{{ str_replace('_', ' ', $key) }}</span>
                                <span class="text-success small fw-bold truncate">{{ is_array($value) ? 'Data Object' : $value }}</span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="col-12 text-muted italic smaller">No additional payload data recorded.</div>
                @endif
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted smaller">
                    <i class="fas fa-network-wired me-1 opacity-50"></i> IP: <span class="text-white-50">{{ $log->ip_address }}</span>
                </div>
                <div class="text-muted smaller opacity-50 italic">Log Reference: #{{ $log->id }}</div>
            </div>
        </div>
    </td>
</tr>
@endforeach

@if($logs->isEmpty())
<tr>
    <td colspan="5" class="text-center py-5 text-muted">
        <i class="fas fa-terminal fa-3x mb-3 opacity-25"></i>
        <p>No system logs found matching these filters.</p>
    </td>
</tr>
@endif

{{-- Custom Fragments for AJAX Updates --}}
<tr id="pagination-fragment" class="d-none">
    <td id="new-results-counter">
        SHOWING {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} OF {{ $logs->total() }} ENTRIES
    </td>
    <td id="new-pagination-content">
        {{ $logs->links() }}
    </td>
</tr>

<style>
.detail-btn {
    font-size: 0.65rem;
    font-weight: 900;
    letter-spacing: 1px;
    border-color: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.4);
    transition: all 0.2s ease;
}
.detail-btn:hover {
    background: rgba(255,255,255,0.05);
    color: white;
    border-color: rgba(255,255,255,0.3);
}
.truncate {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
