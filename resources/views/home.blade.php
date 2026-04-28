@extends('layouts.app')

@section('title', 'BSMF Garage')

@section('content')
<section class="hero">
    <div class="hero-content">
        <h1 class="fade-in text-uppercase italic fw-black ls-tight mb-2" style="font-size: 5.5rem;">BSMF <span>GARAGE</span></h1>
        <p class="fade-in delay-1" style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 6px; margin-bottom: 3.5rem; font-weight: 700; opacity: 0.8; color: var(--secondary);">Premium Die-Cast Collector Series</p>
        
        <div style="position: relative; width: 100%; max-width: 700px;" class="fade-in delay-2 search-container-hero">
            <form action="{{ route('products.index') }}" method="GET" id="searchForm" class="hero-search-form">
                <input type="text" name="search" id="searchInput" autocomplete="off" placeholder="Search for your dream car..." class="hero-search-input">
                <button type="submit" class="btn btn-primary hero-search-btn">Search</button>
            </form>
            <div id="searchSuggestions" class="glass" style="display: none; position: absolute; top: 110%; left: 0; right: 0; z-index: 1000; border-radius: 20px; overflow: hidden; border: 1px solid var(--glass-border);"></div>
        </div>



        <div class="fade-in delay-3 hero-badges">
            <span><i class="fas fa-shipping-fast"></i> Nationwide Shipping</span>
            <span><i class="fas fa-shield-alt"></i> 100% Authentic</span>
            <span><i class="fas fa-history"></i> 24/7 Support</span>
        </div>
    </div>
</section>

<!-- Immediate Product Grid (Latest Drops) -->
<section class="section-padding reveal" style="padding-top: 4rem; margin-top: 0; position: relative; z-index: 10;">
    <div class="glass-section-card">
        <div class="section-header">
            <div>
                <h2 class="section-title">LATEST <span>ARRIVALS</span></h2>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">Fresh out of the crate. Limited quantities available.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius: 30px;">Shop New Drops</a>
        </div>
        
        <div class="product-grid">
            @forelse($featuredProducts as $product)
                <div class="product-card" style="background: var(--bg-darker); border: 1px solid var(--glass-border); color: white;">
                    <div style="position: relative; overflow: hidden; border-radius: 8px;">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" alt="{{ $product->name }}" class="product-image" style="aspect-ratio: 1; object-fit: contain; background: #000;" loading="lazy">
                        <div style="position: absolute; top: 10px; left: 10px; background: var(--secondary); color: var(--bg-dark); padding: 4px 12px; border-radius: 20px; font-size: 0.65rem; font-weight: 900; letter-spacing: 1px;">NEW ARRIVAL</div>
                    </div>
                    <div class="product-info">
                        <h3 style="margin-top: 0.5rem; color: white; font-size: 1.1rem; margin-bottom: 0;">{{ $product->name }}</h3>
                        <div style="display: flex; gap: 0.4rem; margin-top: 0.5rem; flex-wrap: wrap;">
                            <span style="font-size: 0.55rem; color: var(--secondary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(251, 191, 36, 0.3); padding: 2px 8px; border-radius: 4px;">{{ $product->brand->name }}</span>
                            <span style="font-size: 0.55rem; color: rgba(255,255,255,0.5); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 4px;">{{ $product->scale->name }}</span>
                            <span style="font-size: 0.55rem; color: rgba(255,255,255,0.5); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(255,255,255,0.1); padding: 2px 8px; border-radius: 4px;">{{ strtoupper($product->card_condition) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                            <div class="product-price" style="font-size: 1.4rem; color: white;">₱{{ number_format($product->price, 2) }}</div>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.75rem; border-radius: 20px;">Buy Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: span 3; text-align: center; padding: 4rem; color: var(--text-muted); border: 2px dashed var(--glass-border); border-radius: 20px;">
                    <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1.5rem; opacity: 0.3;"></i>
                    <p>The garage is currently being restocked. Check back soon for new grails!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Collection Spotlight (Visual Richness) -->
<section class="section-padding reveal">
    <div style="text-align: center; margin-bottom: 4rem;">
        <p style="color: var(--text-muted); margin-top: 1rem;">Dive deep into our most sought-after categories.</p>
    </div>
    <div class="grid-2">
        <div class="glass" style="position: relative; height: 500px; border-radius: 30px; overflow: hidden; border: 1px solid var(--glass-border); group">
            <img src="{{ asset('images/jdm_spotlight.png') }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6; transition: 0.5s;" class="spotlight-img">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem;">
                <span style="color: var(--secondary); font-weight: 800; letter-spacing: 3px; font-size: 0.8rem;">THE JDM LEGENDS</span>
                <h3 style="font-size: 2.5rem; font-style: italic; text-transform: uppercase; margin: 1rem 0;">Skyline, Supra & <span>Beyond</span></h3>
                <p style="color: rgba(255,255,255,0.7); max-width: 400px; margin-bottom: 2rem;">Discover precision-engineered 1:64 scale models of the most iconic Japanese performance cars ever built.</p>
                <a href="{{ route('products.index') }}" class="btn" style="border: 1px solid white; color: white; border-radius: 30px; width: fit-content;">Browse JDM Collection</a>
            </div>
        </div>
        <div class="glass" style="position: relative; height: 500px; border-radius: 30px; overflow: hidden; border: 1px solid var(--glass-border);">
            <img src="{{ asset('images/muscle_spotlight.png') }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6; transition: 0.5s;" class="spotlight-img">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); display: flex; flex-direction: column; justify-content: flex-end; padding: 3rem;">
                <span style="color: var(--primary); font-weight: 800; letter-spacing: 3px; font-size: 0.8rem;">AMERICAN MUSCLE</span>
                <h3 style="font-size: 2.5rem; font-style: italic; text-transform: uppercase; margin: 1rem 0;">V8 Power <span>Redefined</span></h3>
                <p style="color: rgba(255,255,255,0.7); max-width: 400px; margin-bottom: 2rem;">From the 1969 Mustang to the latest Widebody Chargers, find the heart of Detroit in die-cast form.</p>
                <a href="{{ route('products.index') }}" class="btn" style="border: 1px solid white; color: white; border-radius: 30px; width: fit-content;">Browse Muscle Collection</a>
            </div>
        </div>
    </div>
</section>

<!-- Store Introduction -->
<section class="section-padding reveal">
    <div class="grid-2 align-items-center" style="gap: 5rem;">
        <div style="position: relative;">
            <div style="position: absolute; top: -20px; left: -20px; width: 100px; height: 100px; border-top: 5px solid var(--secondary); border-left: 5px solid var(--secondary);"></div>
            <img src="https://images.unsplash.com/photo-1594731802114-039942f35f29?auto=format&fit=crop&q=80&w=800" alt="Collector Garage" style="width: 100%; border-radius: 20px; box-shadow: 0 30px 60px rgba(0,0,0,0.5);">
            <div style="position: absolute; bottom: 30px; right: -20px; background: var(--primary); padding: 2rem; border-radius: 15px; box-shadow: 0 20px 40px rgba(225, 29, 72, 0.3);">
                <span style="font-size: 3rem; font-weight: 900; line-height: 1; display: block;">15+</span>
                <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase;">Years in Hobby</span>
            </div>
        </div>
        <div>
            <span style="color: var(--secondary); font-weight: 800; text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem;">ESTABLISHED 2010</span>
            <h2 style="font-size: 3.5rem; font-style: italic; text-transform: uppercase; margin: 1rem 0 2rem; line-height: 1;">Built by <span>Collectors</span>, For Collectors</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem;">
                BSMF Garage isn't just a store—it's a sanctuary for die-cast enthusiasts. We started in a small home garage with a passion for 1:64 scale precision. Today, we've grown into a global destination for finding the rarest "Grails" and latest releases.
            </p>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="color: white; margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--secondary);"></i> Our Mission</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">To preserve the culture of die-cast collecting through quality and community.</p>
                </div>
                <div>
                    <h4 style="color: white; margin-bottom: 0.5rem;"><i class="fas fa-check-circle" style="color: var(--secondary);"></i> Our Promise</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Every piece is inspected for card health and blister clarity before shipping.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- The Grail Vault (Star Products) - Move to a condensed footer-like section -->
<section class="section-padding reveal" style="background: rgba(255,255,255,0.01);">
    <div style="text-align: center; margin-bottom: 3rem;">
        <h2 class="section-title">THE GRAIL <span>VAULT</span></h2>
        <p style="color: var(--text-muted);">Our most exclusive, limited-edition acquisitions.</p>
    </div>
    <div class="grid-4">
        @foreach($featuredProducts as $product)
            <div style="background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); padding: 1.5rem; border-radius: 20px; text-align: center;">
                <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                    <img src="{{ $product->main_image ?? asset('images/placeholder-car.webp') }}" alt="Grail" style="max-height: 100%; object-fit: contain;">
                </div>
                <h4 style="color: white; font-size: 0.9rem; margin-bottom: 0.2rem;">{{ $product->name }}</h4>
                <div style="font-size: 0.5rem; color: rgba(255,255,255,0.4); font-weight: 700; text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">
                    {{ $product->brand->name }} • {{ $product->scale->name }} • {{ $product->card_condition }}
                </div>
                <div style="color: var(--secondary); font-weight: 900;">₱{{ number_format($product->price, 2) }}</div>
            </div>
        @endforeach
    </div>
</section>

<!-- Trust / Service Section -->
<section class="section-padding reveal">
    <div class="service-grid">
        <div class="service-card">
            <i class="fas fa-gem service-icon"></i>
            <h3 style="margin-bottom: 1rem; font-style: italic; text-transform: uppercase;">Curated Selection</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Every model is hand-picked for quality and rarity.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-box-open service-icon"></i>
            <h3 style="margin-bottom: 1rem; font-style: italic; text-transform: uppercase;">Mint Condition</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Premium packaging to ensure perfect arrival.</p>
        </div>
        <div class="service-card">
            <i class="fas fa-users service-icon"></i>
            <h3 style="margin-bottom: 1rem; font-style: italic; text-transform: uppercase;">Collector Community</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Join thousands of enthusiasts in our active community.</p>
        </div>
    </div>
</section>


<!-- Brand Ticker Showcase -->
<section class="brand-ticker-container reveal">
    <div class="brand-ticker">
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <!-- Duplicate for seamless loop -->
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
        <div class="brand-item">Mattel</div>
        <div class="brand-item">Hot Wheels</div>
        <div class="brand-item">Matchbox</div>
    </div>
</section>

<!-- Combined Star Products & Latest Picks -->
<section class="section-padding reveal">
    <div class="section-header">
        <h2 class="section-title">THE <span>SPEED SHOP</span> PICKS</h2>
        <a href="{{ route('products.index') }}" class="btn btn-primary" style="border-radius: 30px;">Shop All</a>
    </div>
    <div class="product-grid">
        @foreach($featuredProducts as $product)
        <div class="product-card" style="background: var(--bg-dark); border: 1px solid var(--glass-border); color: white; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div style="position: relative; overflow: hidden; border-radius: 8px;">
                <img src="{{ $product->main_image ?? asset('images/placeholder-car.webp') }}" alt="{{ $product->name }}" class="product-image" style="background: #000814; border: none; margin-bottom: 0;">
                <div style="position: absolute; top: 10px; right: 10px; background: var(--primary); padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 900;">STAR</div>
                
            </div>
            <div class="product-info" style="padding-top: 1.5rem;">
                <h3 style="margin-top: 0.5rem; color: white; margin-bottom: 0;">{{ $product->name }}</h3>
                <div style="display: flex; gap: 0.4rem; margin-top: 0.8rem; flex-wrap: wrap;">
                    <span style="font-size: 0.6rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">{{ $product->brand->name }}</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,0.3); font-weight: 900;">/</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,0.6); font-weight: 900; text-transform: uppercase;">{{ $product->scale->name }}</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,0.3); font-weight: 900;">/</span>
                    <span style="font-size: 0.6rem; color: rgba(255,255,255,0.6); font-weight: 900; text-transform: uppercase;">{{ $product->card_condition }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 1rem;">
                    <div class="product-price" style="margin-bottom: 0; font-size: 1.5rem; text-shadow: none; color: white;">₱{{ number_format($product->price, 2) }}</div>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="padding: 0.6rem 1.5rem; font-size: 0.8rem; border-radius: 30px;">View</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<!-- Testimonials Slider -->
<section class="section-padding reveal" style="background: rgba(255,255,255,0.01);">
    <div class="testimonial-slider">
        <div class="testimonial-track">
            <div class="testimonial-slide">
                <div class="testimonial-card" style="text-align: center; border: none; background: transparent;">
                    <p style="font-size: 1.5rem; font-style: italic; margin-bottom: 2rem;">"The packaging was bulletproof. My RLC Skyline arrived in absolute mint condition. Definitely my new go-to shop."</p>
                    <div class="testimonial-user" style="justify-content: center;">
                        <div class="user-avatar">JD</div>
                        <div style="text-align: left;">
                            <h4 style="font-size: 1rem;">John Doe</h4>
                            <p style="font-size: 0.8rem; color: var(--secondary);">Verified Collector</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-slide">
                <div class="testimonial-card" style="text-align: center; border: none; background: transparent;">
                    <p style="font-size: 1.5rem; font-style: italic; margin-bottom: 2rem;">"Found a rare chase piece I've been hunting for months. Shipping was incredibly fast even for international."</p>
                    <div class="testimonial-user" style="justify-content: center;">
                        <div class="user-avatar" style="background: var(--secondary); color: var(--bg-darker);">SM</div>
                        <div style="text-align: left;">
                            <h4 style="font-size: 1rem;">Sarah Miller</h4>
                            <p style="font-size: 0.8rem; color: var(--secondary);">Elite Member</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider-dots">
            <div class="dot active" onclick="moveSlider(0)"></div>
            <div class="dot" onclick="moveSlider(1)"></div>
        </div>
    </div>
</section>

<!-- Community & FAQ Side-by-Side (Condensed) -->
<section class="section-padding reveal">
    <div class="grid-2" style="gap: 4rem;">
        <div class="community-section" style="padding: 3rem; flex-direction: column; align-items: flex-start; text-align: left; gap: 2rem;">
            <h2 style="font-size: 2rem; font-style: italic; text-transform: uppercase;">Join the <span>Community</span></h2>
            <div style="display: flex; gap: 1rem;">
                <a href="#" class="btn btn-primary" style="border-radius: 50px; background: #5865F2; box-shadow: none; font-size: 0.8rem;"><i class="fab fa-discord"></i> Discord</a>
                <a href="#" class="btn btn-primary" style="border-radius: 50px; background: #E1306C; box-shadow: none; font-size: 0.8rem;"><i class="fab fa-instagram"></i> Insta</a>
            </div>
        </div>
        <div>
            <div class="faq-container" style="margin: 0;">
                <div class="faq-item">
                    <div class="faq-question">Shipping Safety? <i class="fas fa-chevron-down"></i></div>
                    <div class="faq-answer">Double-walled boxes & bubble wrap. All carded models in protective cases.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Authenticity? <i class="fas fa-chevron-down"></i></div>
                    <div class="faq-answer">100% genuine sourced from official distributors.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="section-padding reveal">
    <div class="newsletter-box">
        <h2 style="font-size: 2.5rem; font-style: italic; text-transform: uppercase; margin-bottom: 1rem;">Join the <span>Inner Circle</span></h2>
        <p style="opacity: 0.9; font-weight: 500;">Get early access to drops, exclusive discounts, and community events.</p>
        <form class="newsletter-form">
            <input type="email" placeholder="Enter your email address" required>
            <button type="submit" class="btn btn-primary" style="background: var(--bg-darker); box-shadow: none; border-radius: 50px;">Subscribe</button>
        </form>
    </div>
</section>
@endsection
