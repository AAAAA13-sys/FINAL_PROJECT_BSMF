@extends('layouts.app')

@section('title', $product->name . ' - BSMF Garage')

@section('content')
<div class="container py-5">


    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-container bg-dark border border-secondary rounded overflow-hidden mb-3 text-center p-4">
                    <img src="{{ $product->main_image ?? asset('images/placeholder-car.webp') }}" id="mainProductImage" class="img-fluid" alt="{{ $product->name }}">
                </div>
                
                <!-- Thumbnails (Multiple Angles) -->
                <div class="row g-2">
                    @if($product->card_image)
                        <div class="col-3">
                            <img src="{{ $product->card_image }}" class="img-thumbnail bg-dark border-secondary cursor-pointer thumbnail-switch" alt="Carded">
                        </div>
                    @endif
                    @if($product->loose_image)
                        <div class="col-3">
                            <img src="{{ $product->loose_image }}" class="img-thumbnail bg-dark border-secondary cursor-pointer thumbnail-switch" alt="Loose">
                        </div>
                    @endif
                    @foreach($product->additional_images ?? [] as $img)
                        <div class="col-3">
                            <img src="{{ $img }}" class="img-thumbnail bg-dark border-secondary cursor-pointer thumbnail-switch" alt="Angle">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-md-6">
            <div class="ps-md-4">
                <div class="breadcrumb-minimal mb-3" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; font-weight: 800; opacity: 0.8;">
                    <a href="{{ route('home') }}" style="color: var(--secondary); text-decoration: none;">HOME</a>
                    <span style="color: var(--text-muted); margin: 0 8px;">/</span>
                    <a href="{{ route('products.index') }}" style="color: var(--secondary); text-decoration: none;">COLLECTION</a>
                    <span style="color: var(--text-muted); margin: 0 8px;">/</span>
                    <span style="color: white;">{{ $product->category->name ?? 'MODELS' }}</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-dark">{{ $product->brand->name }}</span>
                    <span class="badge bg-outline-secondary border border-secondary text-white">{{ $product->scale->name }}</span>
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
                                <li><span class="text-warning">⭐ Super Treasure Hunt:</span> Extremely rare variant with Spectraflame paint.</li>
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
                    <span class="h2 text-white">${{ number_format($product->price, 2) }}</span>
                    @if($product->original_price > $product->price)
                        <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->original_price, 2) }}</span>
                    @endif
                </div>

                <!-- Attributes Table -->
                <div class="row mb-4 border-top border-secondary pt-4">
                    <div class="col-6 mb-3">
                        <label class="text-muted small text-uppercase d-block">Year</label>
                        <span class="text-white">{{ $product->year ?? 'N/A' }}</span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="text-muted small text-uppercase d-block">Condition</label>
                        <span class="text-white">{{ ucfirst($product->card_condition) }} {{ $product->is_carded ? '(Carded)' : '(Loose)' }}</span>
                    </div>
                    @if($product->color)
                    <div class="col-6">
                        <label class="text-muted small text-uppercase d-block">Color</label>
                        <span class="text-white">{{ $product->color }}</span>
                    </div>
                    @endif
                    <div class="col-6">
                        <label class="text-muted small text-uppercase d-block">Stock Status</label>
                        @php $status = $product->stock_status; @endphp
                        <span class="text-{{ $status == 'In Stock' ? 'success' : ($status == 'Low Stock' ? 'warning' : 'danger') }}">
                            {{ $status }} ({{ $product->stock_quantity }} available)
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
                                <input type="number" name="quantity" class="form-control bg-dark text-white border-secondary" value="1" min="1" max="{{ $product->stock_quantity }}">
                            </div>
                            <button type="submit" class="btn btn-warning flex-grow-1 py-3 fw-bold">ADD TO GARAGE</button>
                        </form>
                    @else
                        <button class="btn btn-secondary w-100 py-3 mb-2" disabled>OUT OF STOCK</button>
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">NOTIFY WHEN RESTOCKED</button>
                        </form>
                    @endif
                </div>

                <div class="description-box text-muted">
                    <h6 class="text-white text-uppercase mb-3">Casting Details</h6>
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-5 pt-5 border-top border-secondary">
        <h4 class="text-white mb-4">COLLECTORS ALSO BOUGHT</h4>
        <!-- This would normally be a separate query/partial -->
    </div>
</div>

<script>
    document.querySelectorAll('.thumbnail-switch').forEach(thumb => {
        thumb.addEventListener('click', function() {
            document.getElementById('mainProductImage').src = this.src;
        });
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .thumbnail-switch:hover { border-color: #ffc107 !important; }
    .breadcrumb-item + .breadcrumb-item::before { color: #6c757d; }
</style>
@endsection
