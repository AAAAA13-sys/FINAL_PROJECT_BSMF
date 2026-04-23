@extends('layouts.app')

@section('title', $product->name . ' - E-Commerce-BFMSL')

@section('content')
<div class="detail-container fade-in">
    <div class="detail-image-box">
        @php
            $img_path = $product->image_url;
            if (!Str::startsWith($img_path, ['http', '/'])) {
                $img_path = asset($img_path);
            }
        @endphp
        <img src="{{ $img_path }}" alt="{{ $product->name }}">
    </div>
    <div class="detail-info">
        <span class="detail-category">{{ $product->category->name ?? 'Collection' }}</span>
        <h1 class="detail-title">{{ $product->name }}</h1>
        <div class="detail-price-badge">${{ number_format($product->price, 2) }}</div>
        <p class="detail-desc">{{ $product->description }}</p>
        
        <div class="detail-stock">
            <div class="detail-stock-status">Availability: 
                <span class="detail-stock-count">{{ $product->stock > 0 ? $product->stock . ' IN STOCK' : 'OUT OF STOCK' }}</span>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center;">
            <form action="{{ route('cart.add') }}" method="POST" style="flex-grow: 1;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group" style="display: flex; gap: 1rem; align-items: center; margin-bottom: 2rem;">
                    <label for="quantity" style="margin-bottom: 0; color: white;">QTY:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control" style="width: 80px; background: rgba(255,255,255,0.1); border-color: var(--secondary); color: white;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem 3rem;" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    {{ $product->stock > 0 ? 'ADD TO CART' : 'OUT OF STOCK' }}
                </button>
            </form>

            @auth
                <form action="{{ route('wishlist.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn" style="padding: 1rem; background: var(--glass); color: var(--secondary); border: 2px solid var(--secondary); margin-top: 2rem; border-radius: 12px;" title="Add to Garage">
                        <i class="fas fa-heart" style="font-size: 1.5rem;"></i>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</div>

<!-- Reviews Section -->
<section class="section-padding" style="padding-top: 0;">
    <div class="glass fade-in" style="margin-top: 4rem; padding: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
            <h2 class="section-title">CUSTOMER <span>REVIEWS</span></h2>
            <div style="text-align: right;">
                <span style="font-size: 2rem; font-weight: 900; color: var(--secondary);">
                    {{ number_format($product->reviews->avg('rating'), 1) ?: '0.0' }}
                </span>
                <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase;">Average Rating</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 4rem;">
            <!-- Review List -->
            <div>
                @forelse($product->reviews()->with('user')->latest()->get() as $review)
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--glass-border); padding: 2rem; border-radius: 16px; margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                            <div>
                                <span style="font-weight: 800; color: white;">{{ $review->user->name }}</span>
                                <div style="color: var(--secondary); font-size: 0.8rem; margin-top: 0.3rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="color: var(--text-muted); line-height: 1.6;">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p style="color: var(--text-muted); padding: 2rem 0;">No reviews yet. Be the first to collector's review!</p>
                @endforelse
            </div>

            <!-- Review Form -->
            <div>
                @auth
                    <div class="glass" style="padding: 2.5rem; border: 2px solid var(--secondary); border-radius: 20px;">
                        <h3 style="margin-bottom: 2rem; font-style: italic; text-transform: uppercase;">Leave a <span>Review</span></h3>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="form-group">
                                <label>Rating</label>
                                <select name="rating" class="form-control" required style="background: rgba(0,0,0,0.3); color: white; border-color: var(--glass-border);">
                                    <option value="5">5 Stars - Perfection</option>
                                    <option value="4">4 Stars - Excellent</option>
                                    <option value="3">3 Stars - Good</option>
                                    <option value="2">2 Stars - Fair</option>
                                    <option value="1">1 Star - Poor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Your Comment</label>
                                <textarea name="comment" class="form-control" rows="5" required placeholder="Share your experience with this model..." style="background: rgba(0,0,0,0.3); color: white; border-color: var(--glass-border);"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; border-radius: 12px; margin-top: 1rem;">Post Review</button>
                        </form>
                    </div>
                @else
                    <div class="glass" style="padding: 3rem; text-align: center; border: 1px dashed var(--glass-border);">
                        <p style="margin-bottom: 1.5rem; color: var(--text-muted);">Please login to leave a review.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login Now</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
