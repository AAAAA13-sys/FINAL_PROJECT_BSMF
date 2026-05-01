@extends('layouts.app')

@section('title', $product->name . ' - BSMF GARAGE')

@section('content')
<section class="section-padding" style="background: var(--bg-darker); min-height: 100vh; padding-top: 4rem;">
    <div class="container">
        <div class="mb-5">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; padding: 10px 20px; background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 50px; box-shadow: var(--surface-raised); transition: 0.3s;" onmouseover="this.style.color='white'; this.style.borderColor='var(--secondary)'" onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-chevron-left" style="font-size: 0.6rem;"></i> RETURN TO ARCHIVE
            </a>
        </div>
        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-gallery" style="position: sticky; top: 120px;">
                    <!-- Main Image -->
                    <div class="main-image-container" style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 24px; overflow: hidden; margin-bottom: 1.5rem; text-align: center; padding: 3rem; box-shadow: var(--surface-inset);">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" id="mainProductImage"
                            class="img-fluid" alt="{{ $product->name }}" style="max-height: 400px; object-fit: contain;">
                    </div>

                    <!-- Thumbnails -->
                    <div class="row row-cols-5 g-3">
                        <div class="col">
                            <div class="thumbnail-switch-container" style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden; cursor: pointer; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; padding: 5px; box-shadow: var(--surface-raised);">
                                <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}"
                                    class="img-fluid thumbnail-switch active" 
                                    alt="Main View"
                                    onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch-container').forEach(i => i.style.borderColor = 'var(--glass-border)'); this.parentElement.style.borderColor = 'var(--secondary)';"
                                    style="max-height: 100%; object-fit: contain;">
                            </div>
                        </div>
                        @foreach($product->images as $image)
                            <div class="col">
                                <div class="thumbnail-switch-container" style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 12px; overflow: hidden; cursor: pointer; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; padding: 5px; box-shadow: var(--surface-raised);">
                                    <img src="{{ asset($image->image_path) }}"
                                        class="img-fluid thumbnail-switch" 
                                        alt="Gallery Image"
                                        onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch-container').forEach(i => i.style.borderColor = 'var(--glass-border)'); this.parentElement.style.borderColor = 'var(--secondary)';"
                                        style="max-height: 100%; object-fit: contain;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="ps-md-4">
                    <div class="breadcrumb-minimal mb-4" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 3px; font-weight: 800;">
                        <a href="{{ route('home') }}" style="color: var(--secondary); text-decoration: none; opacity: 0.6; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">HOME</a>
                        <span style="color: var(--text-muted); margin: 0 12px; opacity: 0.3;">/</span>
                        <a href="{{ route('products.index') }}" style="color: var(--secondary); text-decoration: none; opacity: 0.6; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">THE GARAGE</a>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span style="font-size: 0.65rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; background: rgba(117, 152, 185, 0.1); padding: 5px 15px; border-radius: 50px; border: 1px solid rgba(117, 152, 185, 0.2);">{{ $product->brand->name }}</span>
                        <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; background: rgba(255,255,255,0.05); padding: 5px 15px; border-radius: 50px; border: 1px solid var(--glass-border);">{{ $product->scale->name }}</span>
                    </div>

                    <h1 class="text-white mb-2" style="font-size: clamp(2rem, 5vw, 3.5rem); font-style: italic; font-weight: 900; line-height: 1.1; text-transform: uppercase;">{{ $product->name }}</h1>
                    <p style="color: var(--text-muted); font-size: 1.15rem; font-weight: 500; margin-bottom: 2.5rem;">{{ $product->casting_name }}</p>

                    <!-- Rarity Indicators -->
                    @if($product->is_super_treasure_hunt || $product->is_treasure_hunt || $product->is_rlc_exclusive)
                        <div class="mb-5 p-4" style="background: rgba(251, 191, 36, 0.03); border: 1px solid rgba(251, 191, 36, 0.2); border-radius: 20px; box-shadow: var(--surface-raised);">
                            <h6 style="color: #fbbf24; font-weight: 900; letter-spacing: 2px; font-size: 0.8rem; margin-bottom: 1.5rem; text-transform: uppercase;"><i class="fas fa-crown me-2"></i> Collector Archive Status</h6>
                            <ul class="list-unstyled mb-0" style="font-size: 0.9rem; color: rgba(255,255,255,0.7); display: flex; flex-direction: column; gap: 0.8rem;">
                                @if($product->is_super_treasure_hunt)
                                    <li><span style="color: #fbbf24; font-weight: 800;">⭐ SUPER TH:</span> Extremely rare spectraflame variant.</li>
                                @endif
                                @if($product->is_treasure_hunt)
                                    <li><span style="color: #ef4444; font-weight: 800;">🔥 TREASURE HUNT:</span> Limited production run edition.</li>
                                @endif
                                @if($product->is_rlc_exclusive)
                                    <li><span style="color: var(--secondary); font-weight: 800;">💎 RLC EXCLUSIVE:</span> Red Line Club membership piece.</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="mb-5 d-flex align-items-end gap-3">
                        <span style="font-size: 2.5rem; font-weight: 900; color: white; line-height: 1;">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->original_price > $product->price)
                            <span style="color: var(--text-muted); text-decoration: line-through; font-size: 1.2rem; margin-bottom: 0.4rem;">₱{{ number_format($product->original_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="row mb-5" style="border-top: 1px solid var(--glass-border); padding-top: 3rem; gap: 0 0;">
                        <div class="col-4 mb-4">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Manufacturer</label>
                            <span style="color: white; font-weight: 700; font-size: 1.1rem;">{{ $product->brand->name }}</span>
                        </div>
                        <div class="col-4 mb-4">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Scale Ratio</label>
                            <span style="color: white; font-weight: 700; font-size: 1.1rem;">{{ $product->scale->name }}</span>
                        </div>
                        <div class="col-4 mb-4">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Condition</label>
                            <span style="color: white; font-weight: 700; font-size: 1.1rem;">{{ ucfirst($product->card_condition) }}</span>
                        </div>
                        
                        <div class="col-4">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Release Year</label>
                            <span style="color: rgba(255,255,255,0.5); font-weight: 700;">{{ $product->year ?? '2024' }}</span>
                        </div>
                        <div class="col-4">
                            <label style="color: var(--text-muted); font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 0.75rem;">Stock Status</label>
                            @php $status = $product->stock_status; @endphp
                            <span style="font-weight: 800; color: {{ $status == 'In Stock' ? '#22c55e' : ($status == 'Low Stock' ? '#fbbf24' : '#ef4444') }}">
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
                                <div style="width: 120px;">
                                    <input type="number" name="quantity" class="filter-input" value="1" min="1" max="{{ $product->stock_quantity }}" style="padding: 1.2rem; border-radius: 16px; text-align: center; font-weight: 900;">
                                </div>
                                <button type="submit" class="btn btn-primary flex-grow-1" style="border-radius: 16px; font-weight: 900; letter-spacing: 2px; padding: 1.2rem; font-size: 0.9rem;">
                                    @auth ACQUIRE PIECE @else LOGIN TO ACQUIRE @endauth
                                </button>
                            </form>
                        @else
                            <button class="btn" style="width: 100%; padding: 1.5rem; border-radius: 16px; background: rgba(255,255,255,0.05); color: var(--text-muted); font-weight: 900; letter-spacing: 2px;" disabled>ARCHIVE EMPTY</button>
                        @endif
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 24px; padding: 2.5rem; box-shadow: var(--surface-inset);">
                        <h6 style="color: white; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1.5rem; font-size: 0.8rem;">Casting Dossier</h6>
                        <div style="color: var(--text-muted); line-height: 1.8; font-size: 0.95rem;">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
