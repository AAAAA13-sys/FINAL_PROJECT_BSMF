@extends('layouts.app')

@section('title', 'Your Cart - BSMF')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-5">
        <h1 class="text-white text-uppercase italic fw-black mb-0">YOUR <span>GARAGE</span></h1>
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ $cart->items->count() }} MODELS</span>
    </div>

    @if($cart->items->count() > 0)
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card bg-dark border-secondary rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="bg-black">
                                    <tr class="text-muted small text-uppercase ls-1">
                                        <th class="ps-4 py-3">Collector Model</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3">Quantity</th>
                                        <th class="py-3 text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr class="align-middle">
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-black p-2 rounded" style="width: 100px;">
                                                        <img src="{{ $item->product->main_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div>
                                                        <h6 class="text-white mb-0">{{ $item->product->name }}</h6>
                                                        <small class="text-warning text-uppercase">{{ $item->product->brand->name }}</small>
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link btn-sm p-0 text-danger text-decoration-none text-uppercase small fw-bold">Remove</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-white">₱{{ number_format($item->price_at_time ?? $item->product->price, 2) }}</td>
                                            <td class="py-4">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="form-control bg-transparent text-white border-secondary form-control-sm" style="width: 60px;">
                                                    <button type="submit" class="btn btn-outline-warning btn-sm border-0"><i class="fas fa-sync-alt"></i></button>
                                                </form>
                                            </td>
                                            <td class="py-4 text-end pe-4 text-white fw-bold h5">₱{{ number_format(($item->price_at_time ?? $item->product->price) * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-dark border-secondary rounded-4 shadow-lg sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="text-white text-uppercase italic mb-4">ORDER <span>SUMMARY</span></h5>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Subtotal</span>
                            <span class="text-white">₱{{ number_format($cart->subtotal(), 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Shipping</span>
                            <span class="text-success">{{ $cart->subtotal() >= 50 ? 'FREE' : '₱5.00' }}</span>
                        </div>

                        <hr class="border-secondary my-4">

                        <div class="d-flex justify-content-between mb-5 h4 text-white">
                            <span class="fw-black italic">TOTAL</span>
                            <span class="fw-black text-warning">₱{{ number_format($cart->subtotal() >= 50 ? $cart->subtotal() : $cart->subtotal() + 5, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn btn-warning w-100 py-3 fw-black ls-1">PROCEED TO CHECKOUT</a>
                        <p class="text-center mt-3 small text-muted"><i class="fas fa-lock me-1"></i> SECURE COLLECTOR CHECKOUT</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 glass border-secondary rounded-4">
            <i class="fas fa-shopping-basket text-secondary display-1 mb-4"></i>
            <h3 class="text-white">YOUR GARAGE IS EMPTY</h3>
            <p class="text-muted mb-4">Start adding some legendary castings to your collection.</p>
            <a href="{{ route('products.index') }}" class="btn btn-warning px-5 py-3 fw-bold">GO TO SHOP</a>
        </div>
    @endif
</div>
@endsection
