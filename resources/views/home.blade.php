@extends('layouts.app')

@section('title', 'BSMF Garage - Premium Die-Cast')

@section('content')
<!-- Section 1: Cinematic Hero -->
<section class="hero storefront-hero">
    <div class="hero-content">
        <h1 class="hero-title-giant fade-in">BSMF <span>GARAGE</span></h1>
        <p class="hero-subtitle-caps fade-in delay-1">Premium Die-Cast Collector Series</p>
        
        <div class="hero-search-wrapper fade-in delay-2">
            <form action="{{ route('products.index') }}" method="GET" id="searchForm" class="hero-search-form">
                <input type="text" name="search" id="searchInput" autocomplete="off" placeholder="Search the garage..." class="hero-search-input">
                <button type="submit" class="btn btn-primary hero-search-btn px-4">Garage Search</button>
            </form>
            <div id="searchSuggestions" class="search-suggestions-dropdown"></div>
        </div>

        <div class="hero-badges-container fade-in delay-3">
            <span class="garage-badge"><i class="fas fa-gem"></i> Curated Collectibles</span>
            <span class="garage-badge"><i class="fas fa-shipping-fast"></i> Secure Shipping</span>
            <span class="garage-badge"><i class="fas fa-shield-alt"></i> Authentic only</span>
        </div>
    </div>
</section>

<!-- Section 2: The Mission (Spacious Intro) -->
<section class="section-padding reveal bg-gradient-dark">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="mission-image-wrapper">
                    <div class="decorative-corner-top"></div>
                    <img src="{{ asset('images/collector_garage_hero.png') }}" alt="BSMF Garage" class="mission-main-img">
                    <div class="mission-experience-card">
                        <span class="exp-number">15+</span>
                        <span class="exp-label">Years in the hobby</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <span class="text-accent-caps mb-4">ESTABLISHED 2010</span>
                <h2 class="section-title-large mb-4">Built by <span>Collectors</span>, For Collectors</h2>
                    BSMF Garage isn't just a store—it's a sanctuary for die-cast enthusiasts. We started with a passion for 1:64 scale precision and have grown into a global destination for finding the rarest "Collectibles" and latest releases.
                <div class="row g-4">
                    <div class="col-sm-6">
                        <h4 class="mission-sub-title"><i class="fas fa-check-circle me-2"></i> Our Mission</h4>
                        <p class="mission-sub-text">Preserving the culture of die-cast collecting through quality and community trust.</p>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="mission-sub-title"><i class="fas fa-check-circle me-2"></i> Our Promise</h4>
                        <p class="mission-sub-text">Every piece is hand-inspected for card health and blister clarity before it leaves our vault.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Latest Arrivals (Clean Grid) -->
<section class="section-padding reveal bg-dark-deep">
    <div class="container">
        <div class="section-header align-items-center">
            <div>
                <h2 class="section-title stacked">LATEST <span>ARRIVALS</span></h2>
                <p class="text-muted mt-2">Fresh out of the crate. Limited quantities available for the season.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-pill-large">View Garage</a>
        </div>
        
        <div class="product-grid">
            @forelse($featuredProducts as $product)
                <div class="product-card-diecast">
                    <div class="product-image-container">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="{{ $product->name }}" class="product-image-zoom" loading="lazy">
                        @if($product->is_super_treasure_hunt)
                            <div class="sth-badge-floating">
                                <i class="fas fa-star"></i> STH
                            </div>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <h3 class="product-card-title">{{ $product->name }}</h3>
                        <div class="mb-2" style="font-size: 0.8rem; color: var(--color-warm-bronze);">
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
                            <span class="text-muted ms-1" style="font-size: 0.7rem;">({{ $product->reviews_count ?? 0 }})</span>
                        </div>
                        <div class="product-card-tags">
                            <span class="tag-badge tag-secondary">{{ $product->brand->name }}</span>
                            <span class="tag-badge tag-muted">{{ $product->scale->name }}</span>
                        </div>
                        <div class="product-card-footer">
                            <div class="product-card-price">₱{{ number_format($product->price, 2) }}</div>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm-pill">Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-container">
                    <i class="fas fa-box-open empty-icon"></i>
                    <p class="empty-text">The garage is being restocked. Check back at dawn.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
