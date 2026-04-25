@extends('layouts.app')

@section('title', 'Die-Cast Collection - BSMF Garage')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card bg-dark text-white border-secondary mb-4">
                <div class="card-header border-secondary py-3">
                    <h5 class="mb-0 text-warning" style="font-weight: 800; letter-spacing: 1px;">FILTERS</h5>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search -->
                        <div class="mb-4" style="position: relative;">
                            <label class="form-label small text-muted text-uppercase mb-2" style="font-weight: 700;">Search</label>
                            <input type="text" name="search" id="sidebarSearchInput" autocomplete="off" class="form-control bg-transparent text-white border-secondary" placeholder="Try 'Skyline' or 'Camaro'..." value="{{ request('search') }}">
                            <div id="sidebarSearchSuggestions" class="glass" style="display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border); background: var(--bg-darker);"></div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase mb-2" style="font-weight: 700;">Brand</label>
                            <div class="row g-2">
                                @foreach($brands as $brand)
                                    <div class="col-6">
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand_{{ $brand->id }}" {{ in_array($brand->id, (array)request('brand')) ? 'checked' : '' }}>
                                            <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="brand_{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Scale Filter -->
                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase mb-2" style="font-weight: 700;">Scale</label>
                            <div class="row g-2">
                                @foreach($scales as $scale)
                                    <div class="col-6">
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" name="scale[]" value="{{ $scale->id }}" id="scale_{{ $scale->id }}" {{ in_array($scale->id, (array)request('scale')) ? 'checked' : '' }}>
                                            <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="scale_{{ $scale->id }}">
                                                {{ $scale->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rarity Filter -->
                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase mb-2" style="font-weight: 700;">Rarity</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="rarity[]" value="sth" id="sth" {{ in_array('sth', (array)request('rarity')) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="sth">STH</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="rarity[]" value="th" id="th" {{ in_array('th', (array)request('rarity')) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="th">TH</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="rarity[]" value="rlc" id="rlc" {{ in_array('rlc', (array)request('rarity')) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="rlc">RLC</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="rarity[]" value="chase" id="chase" {{ in_array('chase', (array)request('rarity')) ? 'checked' : '' }}>
                                        <label class="form-check-label" style="font-size: 0.8rem; cursor: pointer;" for="chase">Chase</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label small text-muted text-uppercase mb-2" style="font-weight: 700;">Price Range</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_price" step="any" class="form-control bg-transparent text-white border-secondary form-control-sm no-spinner" placeholder="Min" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" step="any" class="form-control bg-transparent text-white border-secondary form-control-sm no-spinner" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Hidden Sort (Updated by header select) -->
                        <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort', 'newest') }}">

                        <button type="submit" class="btn btn-warning w-100 btn-sm mb-2" style="font-weight: 800; font-size: 0.85rem;">APPLY FILTERS</button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 btn-sm" style="font-size: 0.85rem;">RESET</a>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const sidebarSearchInput = document.getElementById('sidebarSearchInput');
            const sidebarSuggestionsBox = document.getElementById('sidebarSearchSuggestions');

            if (sidebarSearchInput) {
                sidebarSearchInput.addEventListener('input', async (e) => {
                    const query = e.target.value;
                    if (query.length < 2) {
                        sidebarSuggestionsBox.style.display = 'none';
                        return;
                    }

                    try {
                        const response = await fetch(`/api/search-suggestions?query=${encodeURIComponent(query)}`);
                        const products = await response.json();

                        if (products.length > 0) {
                            sidebarSuggestionsBox.innerHTML = products.map(p => `
                                <a href="/products/${p.id}" style="display: flex; align-items: center; gap: 0.8rem; padding: 0.8rem; text-decoration: none; border-bottom: 1px solid var(--glass-border); transition: 0.3s;" class="suggestion-item">
                                    <img src="${p.main_image || '/images/placeholder-car.webp'}" style="width: 40px; height: 30px; object-fit: contain; background: #000; border-radius: 4px;">
                                    <div>
                                        <div style="color: white; font-weight: 700; font-size: 0.8rem;">${p.name}</div>
                                        <div style="color: var(--secondary); font-weight: 800; font-size: 0.75rem;">$${parseFloat(p.price).toFixed(2)}</div>
                                    </div>
                                </a>
                            `).join('');
                            sidebarSuggestionsBox.style.display = 'block';
                        } else {
                            sidebarSuggestionsBox.style.display = 'none';
                        }
                    } catch (err) {
                        console.error('Search error:', err);
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!sidebarSearchInput.contains(e.target) && !sidebarSuggestionsBox.contains(e.target)) {
                        sidebarSuggestionsBox.style.display = 'none';
                    }
                });
            }
        </script>

        <!-- Product Listing -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-white" style="font-weight: 900; font-style: italic; letter-spacing: 1px;">THE GARAGE ({{ $products->total() }})</h5>
                
                <div class="d-flex align-items-center gap-2">
                    <label class="text-uppercase small mb-0" style="font-size: 0.7rem; font-weight: 800; white-space: nowrap; color: rgba(255,255,255,0.5); letter-spacing: 1px;">Sort By:</label>
                    <select id="headerSort" class="form-select bg-dark text-white border-secondary form-select-sm" style="width: 160px; font-size: 0.8rem; cursor: pointer; border-color: rgba(255,255,255,0.2) !important;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low-High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High-Low</option>
                        <option value="alpha_asc" {{ request('sort') == 'alpha_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        <option value="alpha_desc" {{ request('sort') == 'alpha_desc' ? 'selected' : '' }}>Name: Z-A</option>
                    </select>
                </div>
            </div>

            <script>
                document.getElementById('headerSort')?.addEventListener('change', function() {
                    const hiddenSort = document.getElementById('hiddenSort');
                    const filterForm = hiddenSort.closest('form');
                    hiddenSort.value = this.value;
                    filterForm.submit();
                });
            </script>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-4">
                        <div class="card h-100 bg-dark text-white border-secondary product-card-diecast">
                            <!-- Rarity Badge -->
                            @if($product->is_super_treasure_hunt)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2" title="Super Treasure Hunt">⭐ STH</span>
                            @elseif($product->is_treasure_hunt)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2" title="Treasure Hunt">🔥 TH</span>
                            @endif

                            @if($product->is_chase)
                                <span class="badge bg-purple position-absolute top-0 end-0 m-2" style="background-color: #6f42c1;">CHASE</span>
                            @endif

                            <div class="product-image-container overflow-hidden">
                                <img src="{{ $product->main_image ?? asset('images/placeholder-car.webp') }}" class="card-img-top zoom-on-hover" alt="{{ $product->name }}" loading="lazy">
                            </div>

                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-warning text-uppercase">{{ $product->brand->name }}</small>
                                    <small class="text-muted">{{ $product->scale->name }}</small>
                                </div>
                                <h6 class="card-title mb-2">{{ $product->name }}</h6>
                                <p class="card-text small text-muted mb-3">{{ Str::limit($product->casting_name, 50) }}</p>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                                    
                                    @php $status = $product->stock_status; @endphp
                                    <span class="badge {{ $status == 'In Stock' ? 'bg-success' : ($status == 'Low Stock' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ $status }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer border-secondary bg-transparent p-0">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-warning w-100 rounded-0">VIEW GARAGE</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No cars matching your filters were found in the garage.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .product-card-diecast {
        transition: transform 0.3s ease;
    }
    .product-card-diecast:hover {
        transform: translateY(-5px);
        border-color: #ffc107 !important;
    }
    .zoom-on-hover {
        transition: transform 0.5s ease;
    }
    .zoom-on-hover:hover {
        transform: scale(1.1);
    }
    .product-image-container {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #1a1a1a;
    }
    /* Perfect alignment for checkboxes */
    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 1.5rem;
        margin-bottom: 4px;
    }
    .form-check-input {
        margin-top: 0 !important;
        cursor: pointer;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }
    .form-check-input:checked {
        background-color: var(--secondary) !important;
        border-color: var(--secondary) !important;
    }
    .form-check-label {
        cursor: pointer;
        user-select: none;
        line-height: 1;
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
    }

    /* Section Headings */
    .form-label.text-muted {
        color: rgba(255, 255, 255, 0.5) !important;
        letter-spacing: 1px;
    }
</style>
@endsection
