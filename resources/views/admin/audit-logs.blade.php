@extends('layouts.admin')

@section('content')
<div class="fade-in">
    {{-- HEADER SECTION: Title and Filters ONLY --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="card-title-premium" style="font-size: 2rem;">SYSTEM <span style="color: var(--secondary);">LOGS</span></h2>
        
        <form id="audit-filter-form" action="{{ route('admin.audit-logs') }}" method="GET" class="d-flex gap-3 align-items-end">
            <div class="filter-group">
                <label class="text-muted smaller fw-bold mb-1 d-block">FILTER BY ACTION</label>
                <select name="action_filter" id="action_filter" class="form-select form-select-sm bg-dark text-white border-secondary" style="min-width: 150px;">
                    <option value="">ALL ACTIONS</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action_filter') == $action ? 'selected' : '' }}>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="text-muted smaller fw-bold mb-1 d-block">FILTER BY OPERATOR</label>
                <select name="user_filter" id="user_filter" class="form-select form-select-sm bg-dark text-white border-secondary" style="min-width: 150px;">
                    <option value="">ALL OPERATORS</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_filter') == $user->id ? 'selected' : '' }}>{{ strtoupper($user->username) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group d-flex gap-2">
                <div>
                    <label class="text-muted smaller fw-bold mb-1 d-block">&nbsp;</label>
                    <button type="button" id="refresh-logs" class="btn btn-sm btn-outline-warning rounded-pill px-3" title="Refresh Logs">
                        <i class="fas fa-sync-alt" id="refresh-icon"></i>
                    </button>
                </div>
                <div>
                    <label class="text-muted smaller fw-bold mb-1 d-block">&nbsp;</label>
                    <a href="{{ route('admin.audit-logs') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Reset Filters">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE SECTION --}}
    <div class="admin-table-container shadow-lg position-relative">
        <div id="table-loader" class="position-absolute w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(0,0,0,0.4); z-index: 100; border-radius: 15px;">
            <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
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
            <tbody id="audit-logs-body">
                @include('admin.partials.audit-logs-table')
            </tbody>
        </table>
    </div>

    {{-- PAGINATION AND STATUS SECTION --}}
    <div class="mt-4 d-flex flex-column align-items-center">
        {{-- SHOWING X-Y OF Z ENTRIES --}}
        <div id="results-counter" class="text-muted smaller fw-normal text-uppercase ls-2 mb-2 opacity-50">
            SHOWING {{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }} OF {{ $logs->total() }} ENTRIES
        </div>

        {{-- NAVIGATION CAPSULE --}}
        <div id="pagination-container">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<script>
function toggleDetails(id) {
    const el = document.getElementById(id);
    el.classList.toggle('d-none');
}

document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('audit-filter-form');
    const logsBody = document.getElementById('audit-logs-body');
    const paginationContainer = document.getElementById('pagination-container');
    const resultsCounter = document.getElementById('results-counter');
    const loader = document.getElementById('table-loader');
    const refreshBtn = document.getElementById('refresh-logs');
    const refreshIcon = document.getElementById('refresh-icon');

    async function updateLogs(url = null) {
        if (!url) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams(formData);
            url = `{{ route('admin.audit-logs') }}?${params.toString()}`;
        }

        loader.classList.remove('d-none');
        loader.classList.add('d-flex');
        refreshIcon.classList.add('fa-spin');

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(`<table>${html}</table>`, 'text/html');
            
            // 1. Update Table Body
            const rows = Array.from(doc.querySelectorAll('tr:not(#pagination-fragment)'));
            logsBody.innerHTML = rows.map(r => r.outerHTML).join('');
            
            // 2. Update Pagination Capsule
            const newPagination = doc.querySelector('#new-pagination-content');
            if (newPagination && paginationContainer) {
                paginationContainer.innerHTML = newPagination.innerHTML;
            }

            // 3. Update Results Counter
            const newCounter = doc.querySelector('#new-results-counter');
            if (newCounter && resultsCounter) {
                resultsCounter.innerHTML = newCounter.innerHTML;
            }

            // Update URL in browser without reload
            window.history.pushState({}, '', url);
            
            // Scroll to top of table smoothly
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
        } catch (error) {
            console.error('Error fetching logs:', error);
        } finally {
            loader.classList.add('d-none');
            loader.classList.remove('d-flex');
            refreshIcon.classList.remove('fa-spin');
        }
    }

    // Auto-update on select change
    filterForm.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => updateLogs());
    });

    // Refresh button
    refreshBtn.addEventListener('click', () => updateLogs());

    // Handle pagination clicks
    paginationContainer.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            e.preventDefault();
            updateLogs(link.href);
        }
    });

    // Handle Page Jump (Interactive Input)
    window.addEventListener('page-jump', function(e) {
        e.preventDefault();
        updateLogs(e.detail.url);
    });
});
</script>
@endsection
