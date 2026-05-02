@extends('layouts.app')

@section('title', 'Your Cart - BSMF GARAGE')

@section('content')
<section class="section-padding" style="background: var(--bg-darker); min-height: 100vh; padding-top: 4rem;">
    <div class="container">
        <div class="mb-5">
            <a href="javascript:history.back()" style="display: inline-flex; align-items: center; gap: 10px; color: var(--text-muted); text-decoration: none; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; padding: 10px 20px; background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 50px; box-shadow: var(--surface-raised); transition: 0.3s;" onmouseover="this.style.color='white'; this.style.borderColor='var(--secondary)'" onmouseout="this.style.color='var(--text-muted)'; this.style.borderColor='var(--glass-border)'">
                <i class="fas fa-chevron-left" style="font-size: 0.6rem;"></i> RETURN TO PREVIOUS
            </a>
        </div>
        <div class="d-flex align-items-center gap-3 mb-5">
            <h2 class="text-white text-uppercase italic fw-black mb-0">YOUR <span>GARAGE</span></h2>
            <span style="font-size: 0.7rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; background: rgba(117, 152, 185, 0.1); padding: 5px 15px; border-radius: 50px; border: 1px solid rgba(117, 152, 185, 0.2);">{{ $cart->items->count() }} MODELS</span>
        </div>

        @if($cart->items->count() > 0)
            <div class="row g-5">
                <div class="col-md-8">
                    <!-- Cart Items Table -->
                    <div style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 24px; overflow: hidden; box-shadow: var(--surface-raised); margin-bottom: 4rem;">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent;">
                                <thead>
                                    <tr style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); border-bottom: 1px solid var(--glass-border);">
                                        <th class="ps-4 py-4">Collector Model</th>
                                        <th class="py-4">Price</th>
                                        <th class="py-4">Quantity</th>
                                        <th class="py-4 text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr class="align-middle" style="border-bottom: 1px solid rgba(255,255,255,0.02);">
                                            <td class="ps-4 py-4">
                                                <div class="d-flex align-items-center gap-4">
                                                    <div style="background: var(--bg-darker); border: 1px solid var(--glass-border); border-radius: 12px; padding: 10px; width: 90px; box-shadow: var(--surface-inset);">
                                                        <img src="{{ $item->product->main_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div>
                                                        <h6 class="text-white mb-1" style="font-weight: 800; font-size: 1rem;">{{ $item->product->name }}</h6>
                                                        <span style="font-size: 0.6rem; color: var(--secondary); font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">{{ $item->product->brand->name }}</span>
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 0;">Remove Piece</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-white" style="font-weight: 600;">₱{{ number_format($item->price_at_time ?? $item->product->price, 2) }}</td>
                                            <td class="py-4">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" class="filter-input" style="width: 70px; padding: 0.5rem; text-align: center; font-size: 0.85rem; border-radius: 8px;">
                                                    <button type="submit" style="background: none; border: none; color: var(--secondary);"><i class="fas fa-sync-alt"></i></button>
                                                </form>
                                            </td>
                                            <td class="py-4 text-end pe-4 text-white" style="font-weight: 900; font-size: 1.1rem;">₱{{ number_format(($item->price_at_time ?? $item->product->price) * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="reveal">
                        <h3 class="text-white text-uppercase italic mb-4" style="font-weight: 900; font-size: 1.4rem; letter-spacing: 1px;">VAULT <span>RECOMMENDATIONS</span></h3>
                        <div class="row g-4">
                            @foreach($recommendedProducts as $rec)
                                <div class="col-md-3">
                                    <a href="{{ route('products.show', $rec->id) }}" class="text-decoration-none">
                                        <div class="product-card" style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 20px; padding: 1.5rem; box-shadow: var(--surface-raised); transition: 0.3s transform ease;">
                                            <div style="background: var(--bg-darker); border-radius: 12px; padding: 10px; margin-bottom: 1.25rem; box-shadow: var(--surface-inset);">
                                                <img src="{{ $rec->main_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="{{ $rec->name }}">
                                            </div>
                                            <h6 class="text-white mb-1" style="font-weight: 800; font-size: 0.85rem; height: 2.2rem; overflow: hidden;">{{ $rec->name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span style="color: var(--secondary); font-weight: 900; font-size: 0.8rem;">₱{{ number_format($rec->price, 2) }}</span>
                                                <span style="color: var(--text-muted); font-size: 0.7rem; font-weight: 800; text-transform: uppercase;">View <i class="fas fa-chevron-right ms-1"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 24px; padding: 2.5rem; box-shadow: var(--surface-raised); position: sticky; top: 120px;">
                        <h4 class="text-white text-uppercase italic mb-4" style="font-weight: 900; letter-spacing: 1px;">GARAGE <span>SUMMARY</span></h4>
                        
                        <!-- Shipping Progress (Persuasion Feature) -->
                        <div class="mb-5">
                            @php 
                                $percent = min(100, ($subtotal / $shippingThreshold) * 100);
                                $remaining = max(0, $shippingThreshold - $subtotal);
                            @endphp
                            <div class="d-flex justify-content-between align-items-end mb-2">
                                <span style="font-size: 0.65rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: var(--text-muted);">Shipment Optimization</span>
                                @if($remaining > 0)
                                    <span style="font-size: 0.65rem; font-weight: 900; color: var(--secondary);">₱{{ number_format($remaining, 0) }} TO FREE SHIPPING</span>
                                @else
                                    <span style="font-size: 0.65rem; font-weight: 900; color: #22c55e;">FREE SHIPPING UNLOCKED</span>
                                @endif
                            </div>
                            <div style="height: 8px; background: var(--bg-darker); border-radius: 10px; box-shadow: var(--surface-inset); overflow: hidden;">
                                <div style="width: {{ $percent }}%; height: 100%; background: linear-gradient(to right, var(--primary), var(--secondary)); transition: 1s ease-in-out;"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-3" style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">
                            <span>Archives Value</span>
                            <span style="color: white;">₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-3" style="font-size: 0.9rem; color: #ef4444; font-weight: 600;">
                                <div class="d-flex align-items-center gap-2">
                                    <span>Discount Applied</span>
                                    <form action="{{ route('coupon.remove') }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background: none; border: none; color: var(--text-muted); font-size: 0.6rem; text-decoration: underline; padding: 0;">Remove</button>
                                    </form>
                                </div>
                                <span>-₱{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-3" style="font-size: 0.9rem; color: var(--text-muted); font-weight: 600;">
                            <span>Secure Shipping</span>
                            <span style="color: {{ $shipping == 0 ? '#22c55e' : 'white' }};">{{ $shipping == 0 ? 'COMPLIMENTARY' : '₱' . number_format($shipping, 2) }}</span>
                        </div>

                        <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 2rem 0;">

                        <div class="d-flex justify-content-between mb-5 align-items-end">
                            <span style="font-weight: 900; font-style: italic; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Total</span>
                            <span style="font-size: 2rem; font-weight: 900; color: white; line-height: 1;">₱{{ number_format($total, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100 py-3" style="border-radius: 16px; font-weight: 900; letter-spacing: 2px; font-size: 0.9rem;">ACQUIRE ALL PIECES</a>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 8rem 2rem; background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 40px; box-shadow: var(--surface-inset);">
                <i class="fas fa-box-open" style="font-size: 5rem; color: var(--secondary); margin-bottom: 2.5rem; opacity: 0.2;"></i>
                <h2 class="text-white mb-2" style="font-weight: 900; text-transform: uppercase; font-style: italic;">Your Garage is Empty</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 3rem; font-weight: 500;">Start building your legendary collection today.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary" style="padding: 1rem 4rem; border-radius: 50px; font-weight: 900; letter-spacing: 2px;">DISCOVER MODELS</a>
            </div>
        @endif
    </div>
</section>
@endsection
