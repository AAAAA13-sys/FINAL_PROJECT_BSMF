@if ($paginator->total() > 0)
    <div class="custom-pagination-bar d-flex align-items-center gap-4">
        {{-- << FIRST --}}
        @if ($paginator->onFirstPage())
            <span class="nav-btn disabled"><i class="fas fa-angle-double-left"></i> FIRST</span>
        @else
            <a href="{{ $paginator->url(1) }}" class="nav-btn" data-page="1">
                <i class="fas fa-angle-double-left"></i> FIRST
            </a>
        @endif

        {{-- - PREVIOUS --}}
        @if ($paginator->onFirstPage())
            <span class="nav-btn disabled"><i class="fas fa-minus"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="nav-btn" data-page="prev">
                <i class="fas fa-minus"></i>
            </a>
        @endif

        {{-- PAGE [X] OF Y --}}
        <div class="pagination-info px-4 py-1 d-flex align-items-center gap-2">
            <span>PAGE</span>
            <input type="number" 
                   class="page-jump-input" 
                   value="{{ $paginator->currentPage() }}" 
                   min="1" 
                   max="{{ $paginator->lastPage() }}"
                   data-url="{{ $paginator->url('__PAGE__') }}"
                   onkeydown="if(event.key === 'Enter') { 
                       const page = this.value;
                       const max = this.max;
                       if(page >= 1 && page <= parseInt(max)) {
                           window.dispatchEvent(new CustomEvent('page-jump', { detail: { page: page, url: this.getAttribute('data-url').replace('__PAGE__', page) } }));
                       }
                   }">
            <span>OF <span class="num-highlight">{{ $paginator->lastPage() }}</span></span>
        </div>

        {{-- + NEXT --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="nav-btn" data-page="next">
                <i class="fas fa-plus"></i>
            </a>
        @else
            <span class="nav-btn disabled"><i class="fas fa-plus"></i></span>
        @endif

        {{-- >> LAST --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="nav-btn" data-page="last">
                LAST <i class="fas fa-angle-double-right"></i>
            </a>
        @else
            <span class="nav-btn disabled">LAST <i class="fas fa-angle-double-right"></i></span>
        @endif
    </div>
@endif

<style>
.custom-pagination-bar {
    background: rgba(20, 22, 28, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    padding: 8px 30px;
    border-radius: 50px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    margin: 10px auto;
    width: fit-content;
}

.nav-btn {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    font-weight: 400;
    font-size: 0.7rem;
    letter-spacing: 2px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.nav-btn:hover:not(.disabled) {
    color: #ff4b5c;
    transform: translateY(-1px);
}

.nav-btn.disabled {
    opacity: 0.1;
    pointer-events: none;
    cursor: default;
}

.pagination-info {
    border-left: 1px solid rgba(255, 255, 255, 0.05);
    border-right: 1px solid rgba(255, 255, 255, 0.05);
    font-weight: 400;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: 2px;
    white-space: nowrap;
}

.page-jump-input {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    color: #ff4b5c; /* Racing Red for the input text */
    width: 45px;
    text-align: center;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 2px 0;
    outline: none;
    transition: all 0.3s ease;
    -moz-appearance: textfield;
}

.page-jump-input::-webkit-outer-spin-button,
.page-jump-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.page-jump-input:focus {
    border-color: #ff4b5c;
    background: rgba(255, 75, 92, 0.1);
}

.num-highlight {
    color: white;
    font-weight: 400;
}
</style>
