@extends('layouts.app')

@section('title', 'BSMF GARAGE - Premium Die-Cast Collector Series')

@section('content')
<section class="hero">
    <div class="hero-content fade-in">
        <h4 class="detail-category mb-2">ULTIMATE COLLECTOR SERIES</h4>
        <h1>BSMF <span>GARAGE</span></h1>
        <p class="detail-desc text-center" style="max-width: 600px;">
            The ultimate destination for premium die-cast enthusiasts. 
            Discover rare grails, carded legends, and loose selection collectibles.
        </p>
        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-primary px-5 py-3">ENTER THE GARAGE</a>
            @guest
                <a href="{{ route('register') }}" class="btn btn-outline-light px-5 py-3 rounded-pill fw-bold">JOIN THE CLUB</a>
            @endguest
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div class="glass-section-card p-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title mb-4">CURATED <span>SELECTION</span></h2>
                    <p class="description-box mb-5">
                        Every model in our garage is hand-picked for quality and rarity. From Super Treasure Hunts to custom LBWK builds, we ensure only the finest pieces reach your collection.
                    </p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="glass p-4">
                                <h5 class="text-secondary fw-black italic">CARDED</h5>
                                <p class="small text-muted mb-0">Mint condition blister packs for the serious investors.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="glass p-4">
                                <h5 class="text-warning fw-black italic">LOOSE</h5>
                                <p class="small text-muted mb-0">High-detail loose cars for tactile appreciation.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="detail-image-box">
                        <img src="{{ asset('images/collector_garage_hero.png') }}" alt="Featured Grail" class="img-fluid zoom-on-hover">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Specific overrides to keep the blade 100% clean of style attributes */
    .hero {
        background-attachment: fixed;
        min-height: 90vh;
    }
    .hero h1 {
        font-size: clamp(3rem, 10vw, 8rem);
        letter-spacing: -4px;
    }
</style>
@endpush
