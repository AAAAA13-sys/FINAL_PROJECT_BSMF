@extends('layouts.app')

@section('title', $product->name . ' - BSMF GARAGE')

@section('content')
<section class="section-padding bg-surface-base min-vh-100 pt-5">
    <div class="container">
        <div class="mb-5">
            @auth
                <a href="{{ route('products.index') }}" class="text-muted text-decoration-none text-xs fw-black text-uppercase tracking-wider px-4 py-2 bg-glass border-glass rounded-pill shadow-raised transition-300 hover-text-white hover-border-slate d-inline-flex align-items-center gap-2">
                    <i class="fas fa-chevron-left icon-sm"></i> RETURN TO GARAGE
                </a>
            @else
                <a href="{{ route('home') }}" class="text-muted text-decoration-none text-xs fw-black text-uppercase tracking-wider px-4 py-2 bg-glass border-glass rounded-pill shadow-raised transition-300 hover-text-white hover-border-slate d-inline-flex align-items-center gap-2">
                    <i class="fas fa-chevron-left icon-sm"></i> RETURN TO HOME
                </a>
            @endauth
        </div>
        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-gallery sticky-top-120">
                    <!-- Main Image -->
                    <div class="main-image-container bg-surface-elevated border-glass rounded-24 overflow-hidden mb-4 shadow-inset">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" id="mainProductImage"
                            class="product-image-display" alt="{{ $product->name }}">
                    </div>

                    <!-- Thumbnails -->
                    <div class="row row-cols-5 g-3">
                        <div class="col">
                            <div class="thumbnail-switch-container bg-surface-elevated border-glass rounded-12 overflow-hidden cursor-pointer aspect-square d-flex align-items-center justify-content-center p-1 shadow-raised">
                                <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}"
                                    class="img-fluid thumbnail-switch active max-h-100 object-fit-contain" 
                                    alt="Main View"
                                    onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch-container').forEach(i => i.classList.remove('border-slate')); this.parentElement.classList.add('border-slate');">
                            </div>
                        </div>
                        @foreach($product->images as $image)
                            <div class="col">
                                <div class="thumbnail-switch-container bg-surface-elevated border-glass rounded-12 overflow-hidden cursor-pointer aspect-square d-flex align-items-center justify-content-center p-1 shadow-raised">
                                    <img src="{{ asset($image->image_path) }}"
                                        class="img-fluid thumbnail-switch max-h-100 object-fit-contain" 
                                        alt="Gallery Image"
                                        onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch-container').forEach(i => i.classList.remove('border-slate')); this.parentElement.classList.add('border-slate');">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="ps-md-4">
                    <div class="breadcrumb-minimal mb-4 text-xs text-uppercase tracking-widest fw-bolder">
                        <a href="{{ route('home') }}" class="text-cool-slate text-decoration-none opacity-60 transition-300 hover-opacity-100">HOME</a>
                        <span class="text-muted mx-3 opacity-30">/</span>
                        <a href="{{ route('products.index') }}" class="text-cool-slate text-decoration-none opacity-60 transition-300 hover-opacity-100">THE GARAGE</a>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="text-xs text-cool-slate fw-black text-uppercase tracking-wider bg-slate-subtle px-3 py-1 rounded-pill border-slate-subtle">{{ $product->brand->name }}</span>
                        <span class="text-xs text-muted fw-black text-uppercase tracking-wider bg-glass px-3 py-1 rounded-pill border-glass">{{ $product->scale->name }}</span>
                    </div>

                    <h1 class="text-white mb-2 lh-sm text-uppercase fw-black fst-italic">{{ $product->name }}</h1>
                    <p class="text-muted fs-5 fw-medium mb-5">{{ $product->casting_name }}</p>

                    @php
                        $seriesName = optional($product->series)->name;
                        $isSTH = $product->is_super_treasure_hunt || $seriesName === 'Super Treasure Hunt';
                        $isPremium = $seriesName === 'Premium';
                        $isRLC = $product->is_rlc_exclusive;
                    @endphp

                    @if($isSTH || $isPremium || $isRLC)
                        <div class="mb-5 p-4 surface-accent border-accent rounded-20 shadow-raised">
                            <h6 class="text-warm-bronze fw-black tracking-wider text-sm mb-4 text-uppercase"><i class="fas fa-crown me-2"></i> Collector Garage Status</h6>
                            <ul class="list-unstyled text-white-70 text-sm d-flex flex-column gap-3 mb-0">
                                @if($isSTH)
                                    <li><span class="text-warm-bronze fw-bolder">⭐ SUPER TREASURE HUNT:</span> Extremely rare spectraflame variant.</li>
                                @endif
                                @if($isPremium)
                                    <li><span class="text-secondary fw-bolder">💎 PREMIUM SERIES:</span> High-detail enthusiast grade release.</li>
                                @endif
                                @if($isRLC)
                                    <li><span class="text-cool-slate fw-bolder">🔥 RLC EXCLUSIVE:</span> Red Line Club membership piece.</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="mb-5 d-flex align-items-end gap-3">
                        <span class="price-text text-white fw-black lh-1">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->original_price > $product->price)
                            <span class="text-muted text-decoration-line-through fs-5 mb-1">₱{{ number_format($product->original_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="row mb-5 border-top-glass pt-5 gx-0">
                        <div class="col-4 mb-4">
                            <label class="text-muted text-xs fw-black text-uppercase tracking-wider d-block mb-2">Manufacturer</label>
                            <span class="text-white fw-bold fs-5">{{ $product->brand->name }}</span>
                        </div>
                        <div class="col-4 mb-4">
                            <label class="text-muted text-xs fw-black text-uppercase tracking-wider d-block mb-2">Scale Ratio</label>
                            <span class="text-white fw-bold fs-5">{{ $product->scale->name }}</span>
                        </div>
                        <div class="col-4 mb-4">
                            <label class="text-muted text-xs fw-black text-uppercase tracking-wider d-block mb-2">Condition</label>
                            <span class="text-white fw-bold fs-5">{{ ucfirst($product->card_condition) }}</span>
                        </div>
                        
                        <div class="col-4">
                            <label class="text-muted text-xs fw-black text-uppercase tracking-wider d-block mb-2">Release Year</label>
                            <span class="text-white-50 fw-bold">{{ $product->year ?? '2024' }}</span>
                        </div>
                        <div class="col-4">
                            <label class="text-muted text-xs fw-black text-uppercase tracking-wider d-block mb-2">Stock Status</label>
                            @php $status = $product->stock_status; @endphp
                            <span class="fw-bolder {{ $status == 'In Stock' ? 'text-forest-emerald' : (str_contains($status, 'Low Stock') ? 'text-warm-bronze' : 'text-brand-red') }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mb-5">
                        @if($product->inStock())
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="d-flex align-items-center bg-glass border-glass rounded-16 overflow-hidden w-150">
                                    <button type="button" class="btn btn-qty-adjust px-3 text-white border-0 bg-transparent" onclick="adjustQty(-1)">
                                        <i class="fas fa-minus fa-xs"></i>
                                    </button>
                                    <input type="number" 
                                           id="quantity_input" 
                                           name="quantity" 
                                           class="filter-input border-0 bg-transparent py-4 px-2 text-center fw-black w-100 no-spinners" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->stock_quantity }}"
                                           onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                           oninput="if(this.value > {{ $product->stock_quantity }}) this.value = {{ $product->stock_quantity }};"
                                           onblur="if(this.value < 1 || this.value === '') this.value = 1;">
                                    <button type="button" class="btn btn-qty-adjust px-3 text-white border-0 bg-transparent" onclick="adjustQty(1)">
                                        <i class="fas fa-plus fa-xs"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary flex-grow-1 rounded-16 fw-black tracking-wider p-4 text-sm">
                                    @auth ACQUIRE PIECE @else LOGIN TO ACQUIRE @endauth
                                </button>
                            </form>

                            <script>
                            function adjustQty(amount) {
                                const input = document.getElementById('quantity_input');
                                let val = parseInt(input.value) || 1;
                                const max = parseInt(input.max);
                                
                                val += amount;
                                if (val < 1) val = 1;
                                if (val > max) val = max;
                                
                                input.value = val;
                            }
                            </script>
                        @else
                            <button class="btn w-100 p-4 rounded-16 bg-glass text-muted fw-black tracking-wider" disabled>GARAGE EMPTY</button>
                        @endif
                    </div>

                    <div class="bg-glass border-glass rounded-24 p-5 shadow-inset">
                        <h6 class="text-white fw-black text-uppercase tracking-wider mb-4 text-sm">Casting Details</h6>
                        <div class="text-muted lh-lg text-sm">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5 pt-5 border-top-glass">
            <div class="col-12">
                <h3 class="mb-4 text-white fw-black text-uppercase tracking-wider">Collector Reviews</h3>
                
                @auth
                    @php
                        $user = Auth::user();
                        $hasBought = \App\Models\Order::where('user_id', $user->id)
                            ->where('status', \App\Models\Order::STATUS_DELIVERED)
                            ->whereHas('items', function ($query) use ($product) {
                                $query->where('product_id', $product->id);
                            })->exists();
                        $hasReviewed = $product->reviews()->where('user_id', $user->id)->exists();
                    @endphp

                    @if($hasBought && !$hasReviewed)
                        <div id="review-section" class="mb-5 p-4 bg-surface-elevated border-glass rounded-20">
                            <h5 class="text-white fw-bolder mb-3">Leave a Review</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted">Rating (1-5)</label>
                                    <select name="rating" class="filter-input w-100" required>
                                        <option value="5">⭐⭐⭐⭐⭐ 5 - Excellent</option>
                                        <option value="4">⭐⭐⭐⭐ 4 - Good</option>
                                        <option value="3">⭐⭐⭐ 3 - Average</option>
                                        <option value="2">⭐⭐ 2 - Poor</option>
                                        <option value="1">⭐ 1 - Terrible</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label text-muted">Comment</label>
                                    <textarea name="comment" rows="3" class="filter-input w-100" placeholder="Share your thoughts on this casting..."></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary fw-bolder tracking-wide">Submit Review</button>
                            </form>
                        </div>
                    @elseif($hasReviewed)
                        <div class="alert alert-success bg-forest-emerald-subtle border-forest-emerald-subtle text-forest-emerald">
                            <i class="fas fa-check-circle me-2"></i> You have already reviewed this item.
                        </div>
                    @endif
                @endauth

                <div class="reviews-list mt-4">
                    @forelse($product->reviews as $review)
                        <div class="review-item mb-4 p-4 bg-glass border-glass rounded-16">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-white fw-bolder fs-5">
                                    {{ $review->user->name }}
                                    @if($review->user->role === 'admin' || $review->user->role === 'staff')
                                        <span class="badge bg-primary ms-2 text-xs">STAFF</span>
                                    @endif
                                </div>
                                <div class="text-muted text-sm">
                                    {{ $review->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="mb-3 text-warm-bronze text-sm">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @for($i = 0; $i < 5 - $review->rating; $i++)
                                    <i class="far fa-star text-muted"></i>
                                @endfor
                            </div>
                            <p class="text-muted lh-base m-0">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-muted fst-italic">No reviews yet. Be the first to acquire and review this piece!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
