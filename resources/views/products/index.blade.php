@extends('layouts.app')

@section('title', 'Die-Cast Collection - BSMF GARAGE')

@section('content')
<section class="section-padding section-garage-view">
    <div class="container-fluid container-garage">
        <div class="row g-5">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <span class="filter-section-title">Garage Filter</span>
                    
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Search Box -->
                        <div class="filter-group">
                            <label class="filter-label">Garage Search</label>
                            <input type="text" name="search" id="sidebarSearchInput" autocomplete="off"
                                class="filter-input"
                                placeholder="Search models..." value="{{ request('search') }}">
                            <div id="sidebarSearchSuggestions" class="search-suggestions-dropdown">
                            </div>
                        </div>

                        <!-- Packaging Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Packaging Type</label>
                            <div class="custom-checkbox-group">
                                <label class="custom-checkbox">
                                    <input type="checkbox" name="is_carded" value="1" {{ request('is_carded') ? 'checked' : '' }}>
                                    <span class="checkbox-box"></span>
                                    <span class="text-checkbox-label">Carded Collection</span>
                                </label>
                                <label class="custom-checkbox">
                                    <input type="checkbox" name="is_loose" value="1" {{ request('is_loose') ? 'checked' : '' }}>
                                    <span class="checkbox-box"></span>
                                    <span class="text-checkbox-label">Loose Collection</span>
                                </label>
                            </div>
                        </div>

                        <!-- Series Filter -->
                        <div class="filter-group">
                            <label class="filter-label">Collection Series</label>
                            <div class="custom-checkbox-group">
                                @foreach($series as $s)
                                    <label class="custom-checkbox">
                                        <input type="checkbox" name="series[]" value="{{ $s->id }}" {{ in_array($s->id, (array) request('series')) ? 'checked' : '' }}>
                                        <span class="checkbox-box"></span>
                                        <span class="text-checkbox-label">{{ $s->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="price-filter-group">
                            <label class="filter-label">Price (₱)</label>
                            <div class="d-flex gap-3">
                                <input type="number" name="min_price" step="any" class="filter-input filter-input-price" placeholder="Min" value="{{ request('min_price') }}">
                                <input type="number" name="max_price" step="any" class="filter-input filter-input-price" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <!-- Hidden Sort -->
                        <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort', 'newest') }}">

                        <button type="submit" class="btn btn-primary w-100 btn-filter-apply">APPLY FILTERS</button>
                        <a href="{{ route('products.index') }}" class="btn btn-filter-reset">RESET GARAGE</a>
                    </form>
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-lg-9">
                <div class="mb-5">
                    <h2 class="section-title fs-huge">THE <span>GARAGE</span></h2>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-garage-stats mb-0">{{ $products->total() }} pieces in your garage view</p>
                        
                        <div class="d-flex align-items-center gap-3">
                            <label class="label-sort-by mb-0">Sort By:</label>
                            <select id="headerSort" class="filter-input select-header-sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="alpha_asc" {{ request('sort') == 'alpha_asc' ? 'selected' : '' }}>Name: A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($products as $product)
                        <div class="col-md-4">
                            <div class="product-card product-card-listing">
                                <div class="product-card-img-wrapper">
                                    <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                                    @php
                                        $seriesName = optional($product->series)->name;
                                        $isSTH = $product->is_super_treasure_hunt || $seriesName === 'Super Treasure Hunt';
                                        $isPremium = $seriesName === 'Premium';
                                    @endphp

                                    @if($isSTH)
                                        <div class="sth-badge-overlay">
                                            <i class="fas fa-star"></i> SUPER TREASURE HUNT
                                        </div>
                                    @elseif($isPremium)
                                        <div class="premium-badge-overlay">
                                            <i class="fas fa-gem"></i> PREMIUM
                                        </div>
                                    @endif
                                </div>

                                <div class="product-info product-info-grid">
                                    <h3 class="text-product-title-grid">{{ $product->name }}</h3>
                                    
                                    <div class="mb-2 rating-stars-grid">
                                        @php
                                            $rating = $product->reviews_avg_rating ?? 0;
                                            $fullStars = floor($rating);
                                            $halfStar = ($rating - $fullStars) >= 0.5;
                                        @endphp
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @if($halfStar)
                                            <i class="fas fa-star-half-alt"></i>
                                            @for($i = 0; $i < 4 - $fullStars; $i++)
                                                <i class="far fa-star text-muted"></i>
                                            @endfor
                                        @else
                                            @for($i = 0; $i < 5 - $fullStars; $i++)
                                                <i class="far fa-star text-muted"></i>
                                            @endfor
                                        @endif
                                        <span class="text-muted ms-1 review-count-grid">({{ $product->reviews_count ?? 0 }})</span>
                                    </div>

                                    <div class="badge-container-grid">
                                        <span class="badge-brand-grid">{{ $product->brand->name }}</span>
                                        <span class="badge-scale-grid">{{ $product->scale->name }}</span>
                                        @if($product->is_carded)
                                            <span style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px;">CARDED</span>
                                        @endif
                                        @if($product->is_loose)
                                            <span style="background: rgba(230, 57, 70, 0.15); color: var(--color-brand-red); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.65rem; font-weight: 800; letter-spacing: 1px;">LOOSE</span>
                                        @endif
                                    </div>
                                    
                                    <p class="product-description-grid">{{ Str::limit($product->casting_name, 60) }}</p>

                                    <div class="product-actions-grid">
                                        <div class="product-price text-price-grid">₱{{ number_format($product->price, 2) }}</div>
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-view-product">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-results-container">
                                <i class="fas fa-search empty-results-icon"></i>
                                <h3 class="empty-results-title">No Pieces Found</h3>
                                <p class="empty-results-text">The garage did not yield any results for your current filters.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary mt-4 rounded-pill px-5">Reset All Filters</a>
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
