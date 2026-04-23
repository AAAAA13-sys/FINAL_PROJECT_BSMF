@extends('layouts.app')

@section('title', 'Shop All Products - E-Commerce-BFMSL')

@section('content')
<section class="section-padding">
    <div class="section-header" style="flex-direction: column; align-items: flex-start; gap: 2rem;">
        <h2 class="section-title">ALL <span>COLLECTION</span></h2>
        
        <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('products.index', ['category' => 'all', 'search' => request('search')]) }}" 
                   class="btn" style="border-radius: 30px; padding: 0.6rem 1.5rem; font-size: 0.8rem; background: {{ request('category') == 'all' || !request('category') ? 'var(--primary)' : 'var(--glass)' }}; color: white; border: 1px solid var(--glass-border);">
                    ALL CARS
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id, 'search' => request('search')]) }}" 
                       class="btn" style="border-radius: 30px; padding: 0.6rem 1.5rem; font-size: 0.8rem; background: {{ request('category') == $category->id ? 'var(--primary)' : 'var(--glass)' }}; color: white; border: 1px solid var(--glass-border);">
                        {{ strtoupper($category->name) }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('products.index') }}" method="GET" style="display: flex; gap: 1rem; width: auto; align-items: center;">
                <input type="hidden" name="category" value="{{ request('category', 'all') }}">
                <input type="text" name="search" class="form-control" placeholder="Search collection..." value="{{ request('search') }}" style="background: var(--glass); color: white; border: 1px solid var(--glass-border); padding: 0.8rem 1.5rem; border-radius: 30px; width: 250px;">
                
                <select name="sort" class="form-control" onchange="this.form.submit()" style="background: var(--glass); color: white; border: 1px solid var(--glass-border); padding: 0.8rem 1.5rem; border-radius: 30px; width: 180px; font-weight: 800; text-transform: uppercase; font-size: 0.8rem;">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                </select>

                <button type="submit" class="btn btn-primary" style="border-radius: 30px; padding: 0.8rem 1.5rem;"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
    
    <div class="product-grid">
        @foreach($products as $product)
        <div class="product-card fade-in">
            @php
                $img_path = $product->image_url;
                if (!Str::startsWith($img_path, ['http', '/'])) {
                    $img_path = asset($img_path);
                }
            @endphp
            <img src="{{ $img_path }}" alt="{{ $product->name }}" class="product-image">
            <div class="product-info">
                <span class="detail-category" style="font-size: 0.7rem; margin-bottom: 0.5rem; display: block;">{{ $product->category->name ?? 'Uncategorized' }}</span>
                
                @php $avgRating = $product->reviews->avg('rating'); @endphp
                <div style="color: var(--secondary); font-size: 0.7rem; margin-bottom: 0.8rem;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                    <span style="color: var(--text-muted); margin-left: 5px;">({{ $product->reviews->count() }})</span>
                </div>

                <h3 style="margin-bottom: 1rem;">{{ $product->name }}</h3>
                <p>{{ Str::limit($product->description, 80) }}</p>
                <div class="product-price">${{ number_format($product->price, 2) }}</div>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary" style="width: 100%; text-align: center;">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
