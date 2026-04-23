@extends('layouts.app')

@section('title', 'Dream Garage - BFMSL')

@section('content')
<section class="section-padding">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem;">
        <h1 class="section-title">YOUR DREAM <span>GARAGE</span></h1>
        <p style="color: var(--text-muted); font-style: italic; text-transform: uppercase;">{{ $wishlistItems->count() }} MODELS SAVED</p>
    </div>

    @if($wishlistItems->count() > 0)
        <div class="product-grid">
            @foreach($wishlistItems as $item)
                @php $product = $item->product; @endphp
                <div class="product-card fade-in">
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="product-image">
                    <div class="product-info">
                        <span style="font-size: 0.7rem; color: var(--primary); font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">{{ $product->category->name ?? 'Collection' }}</span>
                        <h3>{{ $product->name }}</h3>
                        <div class="product-price">${{ number_format($product->price, 2) }}</div>
                        
                        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; margin-top: auto;">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="font-size: 0.75rem;">View Model</a>
                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid var(--danger); padding: 0.5rem 0.8rem;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass" style="padding: 10rem 2rem; text-align: center; border: 1px dashed var(--glass-border);">
            <i class="fas fa-car" style="font-size: 4rem; color: var(--glass-border); margin-bottom: 2rem;"></i>
            <h2 style="margin-bottom: 1rem;">Your garage is empty!</h2>
            <p style="color: var(--text-muted); margin-bottom: 3rem;">Start adding your favorite die-cast models to your dream collection.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Go to Shop</a>
        </div>
    @endif
</section>
@endsection
