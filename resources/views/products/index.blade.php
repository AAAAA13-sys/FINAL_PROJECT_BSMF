@extends('layouts.app')

@section('title', 'Die-Cast Collection - BSMF GARAGE')

@section('content')
<section class="section-padding" style="background: var(--bg-darker); min-height: 100vh; padding-top: 4rem;">
    <div class="container-fluid" style="padding: 0 5%;">
        <div class="row g-5">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <span class="filter-section-title">Archives Filter</span>
                    
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search Box -->
                        <div style="margin-bottom: 2.5rem;">
                            <label class="filter-label">Archive Search</label>
                            <input type="text" name="search" id="sidebarSearchInput" autocomplete="off"
                                class="filter-input"
                                placeholder="Search by model or series..." value="{{ request('search') }}">
                            <div id="sidebarSearchSuggestions" class="glass"
                                style="display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; border-radius: 12px; overflow: hidden; border: 1px solid var(--glass-border); background: var(--bg-darker);">
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div style="margin-bottom: 2.5rem;">
                            <label class="filter-label">Manufacturer</label>
                            <div class="custom-checkbox-group">
                                @foreach($brands as $brand)
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="brand[]" value="{{ $brand->id }}" {{ in_array($brand->id, (array) request('brand')) ? 'checked' : '' }}>
                                        <span class="checkbox-box"></span>
                                        <span style="font-size: 0.85rem; font-weight: 600;">{{ $brand->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Scale Filter -->
                        <div style="margin-bottom: 2.5rem;">
                            <label class="filter-label">Scale Ratio</label>
                            <div class="custom-checkbox-group">
                                @foreach($scales as $scale)
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="scale[]" value="{{ $scale->id }}" {{ in_array($scale->id, (array) request('scale')) ? 'checked' : '' }}>
                                        <span class="checkbox-box"></span>
                                        <span style="font-size: 0.85rem; font-weight: 600;">{{ $scale->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div style="margin-bottom: 3rem;">
                            <label class="filter-label">Valuation (₱)</label>
                            <div class="d-flex gap-3">
                                <input type="number" name="min_price" step="any" class="filter-input" placeholder="Min" value="{{ request('min_price') }}" style="padding: 0.8rem 1rem; font-size: 0.9rem;">
                                <input type="number" name="max_price" step="any" class="filter-input" placeholder="Max" value="{{ request('max_price') }}" style="padding: 0.8rem 1rem; font-size: 0.9rem;">
                            </div>
                        </div>

                        <!-- Hidden Sort -->
                        <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort', 'newest') }}">

                        <button type="submit" class="btn btn-primary w-100" style="padding: 1rem; border-radius: 12px; font-weight: 900; letter-spacing: 2px; margin-bottom: 1rem;">APPLY FILTERS</button>
                        <a href="{{ route('products.index') }}" class="btn" style="width: 100%; border: 1px solid var(--glass-border); color: var(--text-muted); padding: 0.8rem; border-radius: 12px; font-size: 0.8rem; font-weight: 700;">RESET ARCHIVE</a>
                    </form>
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-end mb-5">
                    <div>
                        <h2 class="section-title" style="font-size: 3rem;">THE <span>GARAGE</span></h2>
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 2px;">{{ $products->total() }} pieces discovered in current view</p>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <label style="font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted);">Sort By:</label>
                        <select id="headerSort" class="filter-input" style="width: 200px; padding: 0.6rem 1rem; font-size: 0.8rem; cursor: pointer;">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="alpha_asc" {{ request('sort') == 'alpha_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-4">
                            <div class="product-card" style="background: var(--bg-dark); border: 1px solid var(--glass-border); color: white; height: 100%; display: flex; flex-direction: column;">
                                <div style="position: relative; overflow: hidden; border-radius: 12px;">
                                    @if($product->is_super_treasure_hunt)
                                        <div style="position: absolute; top: 15px; left: 15px; background: #fbbf24; color: black; padding: 4px 12px; border-radius: 50px; font-size: 0.6rem; font-weight: 900; z-index: 5; letter-spacing: 1px;">SUPER TH</div>
                                    @endif
                                    <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}"
                                        class="product-image" alt="{{ $product->name }}" loading="lazy" style="margin-bottom: 0;">
                                </div>

                                <div class="product-info" style="flex-grow: 1; display: flex; flex-direction: column; padding-top: 1.5rem;">
                                    <h3 style="color: white; font-size: 1.15rem; margin-bottom: 0.5rem; font-weight: 800;">{{ $product->name }}</h3>
                                    
                                    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                                        <span style="font-size: 0.55rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 1px; background: rgba(117, 152, 185, 0.1); padding: 3px 10px; border-radius: 50px;">{{ $product->brand->name }}</span>
                                        <span style="font-size: 0.55rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 1px; background: rgba(255,255,255,0.05); padding: 3px 10px; border-radius: 50px;">{{ $product->scale->name }}</span>
                                    </div>
                                    
                                    <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.5rem; flex-grow: 1;">{{ Str::limit($product->casting_name, 60) }}</p>

                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
                                        <div class="product-price" style="font-size: 1.4rem; color: white; font-weight: 900;">₱{{ number_format($product->price, 2) }}</div>
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="padding: 0.5rem 1.5rem; font-size: 0.75rem; border-radius: 50px;">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div style="text-align: center; padding: 8rem 2rem; color: var(--text-muted); background: var(--bg-dark); border-radius: 40px; border: 1px dashed var(--glass-border);">
                                <i class="fas fa-search" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.2;"></i>
                                <h3 style="color: white; margin-bottom: 1rem; font-weight: 800;">No Pieces Found</h3>
                                <p style="font-size: 1.1rem; font-weight: 500;">The archives did not yield any results for your current filters.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary mt-4" style="border-radius: 50px; padding: 0.8rem 2.5rem;">Reset All Filters</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('headerSort').addEventListener('change', function() {
        document.getElementById('hiddenSort').value = this.value;
        this.closest('form') ? this.closest('form').submit() : document.querySelector('form').submit();
    });
</script>
@endpush
@endsection
