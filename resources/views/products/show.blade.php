@extends('layouts.app')

@section('title', $product->name . ' - BSMF Garage')

@section('content')
    <div class="container py-5">


        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-gallery" style="position: sticky; top: 100px;">
                    <!-- Main Image -->
                    <div
                        class="main-image-container bg-dark border border-secondary rounded overflow-hidden mb-3 text-center p-4">
                        <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}" id="mainProductImage"
                            class="img-fluid" alt="{{ $product->name }}">
                    </div>

                    <!-- Thumbnails (Multiple Angles) -->
                    <div class="row row-cols-5 g-2">
                        <div class="col">
                            <img src="{{ $product->main_image ? asset($product->main_image) : asset('images/placeholder-car.webp') }}"
                                class="img-thumbnail bg-dark border-secondary cursor-pointer thumbnail-switch active" 
                                alt="Main View"
                                onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch').forEach(i => i.classList.remove('active')); this.classList.add('active');">
                        </div>
                        @foreach($product->images as $image)
                            <div class="col">
                                <img src="{{ asset($image->image_path) }}"
                                    class="img-thumbnail bg-dark border-secondary cursor-pointer thumbnail-switch" 
                                    alt="Gallery Image"
                                    onclick="document.getElementById('mainProductImage').src = this.src; document.querySelectorAll('.thumbnail-switch').forEach(i => i.classList.remove('active')); this.classList.add('active');">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="ps-md-4">
                    <div class="breadcrumb-minimal mb-3"
                        style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; opacity: 0.8;">
                        <a href="{{ route('home') }}" style="color: var(--secondary); text-decoration: none;">HOME</a>
                        <span style="color: var(--text-muted); margin: 0 8px;">/</span>
                        <a href="{{ route('products.index') }}"
                            style="color: var(--secondary); text-decoration: none;">COLLECTION</a>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark">{{ $product->brand->name }}</span>
                        <span
                            class="badge bg-outline-secondary border border-secondary text-white">{{ $product->scale->name }}</span>
                        @if($product->series)
                            <span class="badge bg-info text-dark">{{ $product->series->name }}</span>
                        @endif
                    </div>

                    <h1 class="text-white display-5 mb-1">{{ $product->name }}</h1>
                    <p class="text-muted lead mb-4">{{ $product->casting_name }}</p>

                    <!-- Rarity Indicators -->
                    @if($product->is_super_treasure_hunt || $product->is_treasure_hunt || $product->is_rlc_exclusive)
                        <div class="mb-4 p-3 bg-dark border border-warning rounded">
                            <h6 class="text-warning mb-2"><i class="fas fa-certificate me-2"></i> COLLECTOR STATUS</h6>
                            <ul class="list-unstyled mb-0 small text-white">
                                @if($product->is_super_treasure_hunt)
                                    <li><span class="text-warning">⭐ Super Treasure Hunt:</span> Extremely rare variant with
                                        Spectraflame paint.</li>
                                @endif
                                @if($product->is_treasure_hunt)
                                    <li><span class="text-danger">🔥 Treasure Hunt:</span> Limited production run variant.</li>
                                @endif
                                @if($product->is_rlc_exclusive)
                                    <li><span class="text-info">💎 RLC Exclusive:</span> Red Line Club membership exclusive.</li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    <div class="mb-4">
                        <span class="h2 text-white">₱{{ number_format($product->price, 2) }}</span>
                        @if($product->original_price > $product->price)
                            <span
                                class="text-muted text-decoration-line-through ms-2">₱{{ number_format($product->original_price, 2) }}</span>
                        @endif
                    </div>

                    <div class="row mb-4 border-top border-secondary pt-4">
                        <div class="col-4 mb-3">
                            <label class="text-secondary small text-uppercase d-block fw-black ls-1">Brand</label>
                            <span class="text-white fw-bold">{{ $product->brand->name }}</span>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="text-secondary small text-uppercase d-block fw-black ls-1">Scale</label>
                            <span class="text-white fw-bold">{{ $product->scale->name }}</span>
                        </div>
                        <div class="col-4 mb-3">
                            <label class="text-secondary small text-uppercase d-block fw-black ls-1">Condition</label>
                            <span class="text-white fw-bold">{{ ucfirst($product->card_condition) }} {{ $product->is_carded ? 'Card' : 'Loose' }}</span>
                        </div>
                        
                        <div class="col-4 mt-2">
                            <label class="text-muted small text-uppercase d-block">Year</label>
                            <span class="text-white-50">{{ $product->year ?? 'N/A' }}</span>
                        </div>
                        @if($product->color)
                            <div class="col-4 mt-2">
                                <label class="text-muted small text-uppercase d-block">Color</label>
                                <span class="text-white-50">{{ $product->color }}</span>
                            </div>
                        @endif
                        <div class="col-4 mt-2">
                            <label class="text-muted small text-uppercase d-block">Stock Status</label>
                            @php $status = $product->stock_status; @endphp
                            <span class="small text-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                                {{ $status }} ({{ $product->stock_quantity }})
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mb-5">
                        @if($product->inStock())
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex gap-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div style="width: 100px;">
                                    <input type="number" name="quantity"
                                        class="form-control bg-dark text-white border-secondary" value="1" min="1"
                                        max="{{ $product->stock_quantity }}">
                                </div>
                                    <button type="submit" class="btn btn-warning flex-grow-1 py-3 fw-bold">@auth ADD TO CART @else LOGIN TO ADD TO CART @endauth</button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100 py-3 mb-2" disabled>OUT OF STOCK</button>
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning w-100">NOTIFY WHEN RESTOCKED</button>
                            </form>
                        @endif
                    </div>

                    <div class="description-box">
                        <h6 class="text-white text-uppercase mb-3">Casting Details</h6>
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>




@endsection