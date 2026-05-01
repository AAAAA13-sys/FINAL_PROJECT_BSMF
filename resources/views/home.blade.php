@extends('layouts.app')

@section('title', 'BSMF Garage - Premium Die-Cast')

@section('content')
<!-- Section 1: Cinematic Hero -->
<section class="hero">
    <div class="hero-content">
        <h1 class="fade-in text-uppercase italic fw-black ls-tight mb-2" style="font-size: 6rem;">BSMF <span>GARAGE</span></h1>
        <p class="fade-in delay-1" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 8px; margin-bottom: 4rem; font-weight: 800; opacity: 0.6;">Premium Die-Cast Collector Series</p>
        
        <div style="position: relative; width: 100%; max-width: 800px;" class="fade-in delay-2 search-container-hero">
            <form action="{{ route('products.index') }}" method="GET" id="searchForm" class="hero-search-form">
                <input type="text" name="search" id="searchInput" autocomplete="off" placeholder="Search the archives..." class="hero-search-input">
                <button type="submit" class="btn btn-primary hero-search-btn">Archive Search</button>
            </form>
            <div id="searchSuggestions" class="glass" style="display: none; position: absolute; top: 110%; left: 0; right: 0; z-index: 1000; border-radius: 20px; overflow: hidden; border: 1px solid var(--glass-border);"></div>
        </div>

        <div class="fade-in delay-3 hero-badges">
            <span><i class="fas fa-gem"></i> Curated Grails</span>
            <span><i class="fas fa-shipping-fast"></i> Secure Shipping</span>
            <span><i class="fas fa-shield-alt"></i> Authentic only</span>
        </div>
    </div>
</section>

<!-- Section 2: The Mission (Spacious Intro) -->
<section class="section-padding reveal" style="background: linear-gradient(to bottom, var(--bg-darker), #0a0e12);">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div style="position: relative;">
                    <div style="position: absolute; top: -30px; left: -30px; width: 120px; height: 120px; border-top: 2px solid var(--secondary); border-left: 2px solid var(--secondary); opacity: 0.3;"></div>
                    <img src="{{ asset('images/collector_garage_hero.png') }}" alt="BSMF Garage" style="width: 100%; border-radius: 40px; box-shadow: var(--surface-raised); border: 1px solid var(--glass-border);">
                    <div style="position: absolute; bottom: -30px; right: -30px; background: var(--primary); padding: 3rem; border-radius: 24px; box-shadow: var(--elv-4); border: 1px solid rgba(255,255,255,0.1);">
                        <span style="font-size: 4rem; font-weight: 900; line-height: 1; display: block; font-style: italic;">15+</span>
                        <span style="font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8;">Years in the hobby</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <span style="color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 5px; font-size: 0.8rem; display: block; margin-bottom: 2rem;">ESTABLISHED 2010</span>
                <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); font-style: italic; text-transform: uppercase; margin-bottom: 2.5rem; line-height: 1.1; font-weight: 900;">Built by <span>Collectors</span>, For Collectors</h2>
                <p style="color: var(--text-muted); font-size: 1.2rem; line-height: 2; margin-bottom: 3rem; font-weight: 500;">
                    BSMF Garage isn't just a store—it's a sanctuary for die-cast enthusiasts. We started with a passion for 1:64 scale precision and have grown into a global destination for finding the rarest "Grails" and latest releases.
                </p>
                <div class="row g-4">
                    <div class="col-sm-6">
                        <h4 style="color: white; margin-bottom: 1rem; font-weight: 800; font-style: italic;"><i class="fas fa-check-circle" style="color: var(--secondary); margin-right: 10px;"></i> Our Mission</h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">Preserving the culture of die-cast collecting through quality and community trust.</p>
                    </div>
                    <div class="col-sm-6">
                        <h4 style="color: white; margin-bottom: 1rem; font-weight: 800; font-style: italic;"><i class="fas fa-check-circle" style="color: var(--secondary); margin-right: 10px;"></i> Our Promise</h4>
                        <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">Every piece is hand-inspected for card health and blister clarity before it leaves our vault.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Latest Arrivals (Clean Grid) -->
<section class="section-padding reveal" style="background: #0a0e12;">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title stacked">LATEST <span>ARRIVALS</span></h2>
                <p style="color: var(--text-muted); font-size: 1rem; margin-top: 1rem; font-weight: 500;">Fresh out of the crate. Limited quantities available for the season.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius: 50px; padding: 1rem 3rem;">View Archive</a>
        </div>
        
        <div class="product-grid">
            @forelse($featuredProducts as $product)
                <div class="product-card">
                    <div style="position: relative; overflow: hidden; border-radius: 16px;">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="{{ $product->name }}" class="product-image" loading="lazy">
                        <div style="position: absolute; top: 15px; left: 15px; background: var(--secondary); color: var(--bg-dark); padding: 6px 16px; border-radius: 50px; font-size: 0.65rem; font-weight: 900; letter-spacing: 2px;">NEW DROP</div>
                    </div>
                    <div class="product-info" style="padding: 0.5rem 0.5rem 0;">
                        <h3 style="color: white; font-size: 1.2rem; margin-bottom: 0.5rem; font-weight: 800;">{{ $product->name }}</h3>
                        <div style="display: flex; gap: 0.6rem; margin-top: 0.8rem; flex-wrap: wrap;">
                            <span style="font-size: 0.6rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 1px; background: rgba(117, 152, 185, 0.1); padding: 4px 12px; border-radius: 50px; border: 1px solid rgba(117, 152, 185, 0.2);">{{ $product->brand->name }}</span>
                            <span style="font-size: 0.6rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 1px; background: rgba(255,255,255,0.05); padding: 4px 12px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.05);">{{ $product->scale->name }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
                            <div class="product-price" style="font-size: 1.5rem; color: white; font-weight: 900;">₱{{ number_format($product->price, 2) }}</div>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="padding: 0.6rem 1.8rem; font-size: 0.8rem; border-radius: 50px;">Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: span 3; text-align: center; padding: 6rem; color: var(--text-muted); background: var(--bg-darker); border-radius: 40px; border: 1px dashed var(--glass-border);">
                    <i class="fas fa-box-open" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.2;"></i>
                    <p style="font-size: 1.2rem; font-weight: 600;">The archives are being restocked. Check back at dawn.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Section 4: Collection Spotlights (Cinematic) -->
<section class="section-padding reveal" style="background: linear-gradient(to bottom, #0a0e12, var(--bg-darker));">
    <div class="container">
        <div style="text-align: center; margin-bottom: 6rem;">
            <h2 class="section-title stacked">THE <span>COLLECTION</span></h2>
            <p style="color: var(--text-muted); margin-top: 1.5rem; font-size: 1.1rem; font-weight: 500;">Specialized archives for the discerning collector.</p>
        </div>
        <div class="row g-5">
            <div class="col-md-6">
                <div class="glass" style="position: relative; height: 600px; border-radius: 40px; overflow: hidden; group">
                    <img src="{{ asset('images/jdm_spotlight.png') }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; transition: 0.8s;" class="spotlight-img">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(5, 8, 10, 0.95), transparent 70%); display: flex; flex-direction: column; justify-content: flex-end; padding: 4rem;">
                        <span style="color: var(--secondary); font-weight: 900; letter-spacing: 5px; font-size: 0.8rem; margin-bottom: 1.5rem; display: block;">JDM LEGENDS</span>
                        <h3 style="font-size: 3rem; font-style: italic; text-transform: uppercase; margin-bottom: 1.5rem; font-weight: 900; line-height: 1;">Rising Sun <span>Performance</span></h3>
                        <p style="color: rgba(255,255,255,0.6); max-width: 400px; margin-bottom: 2.5rem; font-size: 1rem; line-height: 1.6;">Precision-engineered 1:64 scale models of iconic Japanese legends.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary" style="width: fit-content; border-radius: 50px; padding: 1rem 2.5rem;">Explore JDM</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="glass" style="position: relative; height: 600px; border-radius: 40px; overflow: hidden;">
                    <img src="{{ asset('images/muscle_spotlight.png') }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.5; transition: 0.8s;" class="spotlight-img">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(5, 8, 10, 0.95), transparent 70%); display: flex; flex-direction: column; justify-content: flex-end; padding: 4rem;">
                        <span style="color: var(--primary); font-weight: 900; letter-spacing: 5px; font-size: 0.8rem; margin-bottom: 1.5rem; display: block;">AMERICAN MUSCLE</span>
                        <h3 style="font-size: 3rem; font-style: italic; text-transform: uppercase; margin-bottom: 1.5rem; font-weight: 900; line-height: 1;">V8 Heritage <span>Redefined</span></h3>
                        <p style="color: rgba(255,255,255,0.6); max-width: 400px; margin-bottom: 2.5rem; font-size: 1rem; line-height: 1.6;">From the classic '69 Charger to the latest modern powerhouses.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary" style="width: fit-content; border-radius: 50px; padding: 1rem 2.5rem;">Explore Muscle</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Trust & Services (Iconic) -->
<section class="section-padding reveal">
    <div class="container">
        <div class="service-grid" style="gap: 4rem;">
            <div class="service-card" style="padding: 4rem 2rem; background: var(--bg-darker); border-radius: 32px; box-shadow: var(--surface-raised);">
                <i class="fas fa-gem service-icon" style="font-size: 3rem; margin-bottom: 2rem;"></i>
                <h3 style="margin-bottom: 1.5rem; font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Curated Archives</h3>
                <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6;">Every model is hand-picked by experts for rarity and collector value.</p>
            </div>
            <div class="service-card" style="padding: 4rem 2rem; background: var(--bg-darker); border-radius: 32px; box-shadow: var(--surface-raised);">
                <i class="fas fa-box-open service-icon" style="font-size: 3rem; margin-bottom: 2rem;"></i>
                <h3 style="margin-bottom: 1.5rem; font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Mint Obsession</h3>
                <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6;">We use triple-layer protection to ensure your grails arrive in museum condition.</p>
            </div>
            <div class="service-card" style="padding: 4rem 2rem; background: var(--bg-darker); border-radius: 32px; box-shadow: var(--surface-raised);">
                <i class="fas fa-users service-icon" style="font-size: 3rem; margin-bottom: 2rem;"></i>
                <h3 style="margin-bottom: 1.5rem; font-style: italic; text-transform: uppercase; font-weight: 900; letter-spacing: 1px;">Global Community</h3>
                <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6;">Join an elite network of collectors in over 50 countries worldwide.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 6: Brand Showcase -->
<section class="brand-ticker-container reveal" style="padding: 5rem 0;">
    <div class="brand-ticker">
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Tarmac Works</div>
        <div class="brand-item">Inno64</div>
        <div class="brand-item">MiniGT</div>
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <!-- Duplicates for loop -->
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Tarmac Works</div>
        <div class="brand-item">Inno64</div>
        <div class="brand-item">MiniGT</div>
    </div>
</section>

<!-- Section 7: Community & Newsletter (Consolidated) -->
<section class="section-padding reveal">
    <div class="container">
        <div class="glass-section-card">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <h2 style="font-size: clamp(2rem, 5vw, 3.5rem); font-style: italic; text-transform: uppercase; font-weight: 900; line-height: 1; margin-bottom: 1.5rem;">Join the <span>Inner Circle</span></h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2.5rem;">Get dawn-access to rare drops and member-only vault keys.</p>
                    <form class="newsletter-form" style="max-width: 100%;">
                        <input type="email" placeholder="Archive frequency email..." required style="background: var(--surface-inset); border-radius: 50px; padding: 1.2rem 2rem; color: white; border: 1px solid var(--glass-border);">
                        <button type="submit" class="btn btn-primary" style="border-radius: 50px; padding: 1.2rem 3rem;">Join Now</button>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div style="background: rgba(255,255,255,0.02); border-radius: 32px; padding: 3rem; border: 1px solid var(--glass-border);">
                        <h4 style="color: white; margin-bottom: 1.5rem; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">Collector Support</h4>
                        <div class="faq-container" style="margin: 0;">
                            <div class="faq-item">
                                <div class="faq-question">Shipping Protection? <i class="fas fa-chevron-down"></i></div>
                                <div class="faq-answer">Double-walled boxes, bubble wrap, and moisture-proof seals on every grail.</div>
                            </div>
                            <div class="faq-item">
                                <div class="faq-question">Authenticity Check? <i class="fas fa-chevron-down"></i></div>
                                <div class="faq-answer">Every piece is verified against our historical archives and supplier manifests.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
