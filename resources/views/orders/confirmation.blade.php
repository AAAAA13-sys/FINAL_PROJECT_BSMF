@extends('layouts.app')

@section('title', 'Order Confirmed - BSMF Garage')

@section('content')
<section class="section-padding" style="text-align: center; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="fade-in" style="width: 100%; max-width: 950px; margin: 0 auto; padding: 2rem;">
        <div style="font-size: 6rem; color: var(--secondary); margin-bottom: 1.5rem; text-shadow: 0 0 30px rgba(251, 191, 36, 0.3);">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="text-uppercase italic fw-black mb-0" style="font-size: 4rem; letter-spacing: -2px;">ORDER <span>SUCCESSFUL</span></h1>
        <p style="margin-top: 0.5rem; font-size: 1.2rem; color: var(--secondary); font-weight: 800; text-transform: uppercase; letter-spacing: 4px;">
            Receipt #{{ $order->order_number }}
        </p>
        
        <div class="glass" style="margin-top: 3rem; padding: 4rem; text-align: left; border-radius: 40px; border: 1px solid rgba(255,255,255,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4rem;">
                <div>
                    <h3 style="color: white; margin-bottom: 1rem; font-style: italic; text-transform: uppercase; font-weight: 900; font-size: 1.8rem;">SHIPMENT <span>DETAILS</span></h3>
                    <div style="background: rgba(255,255,255,0.03); padding: 2rem; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05);">
                        <p style="white-space: pre-line; margin-bottom: 0; font-size: 1.1rem; line-height: 1.6; color: rgba(255,255,255,0.8);">{{ $order->shipping_address }}</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 1rem; letter-spacing: 2px; font-weight: 800;">PAYMENT MODE</h4>
                    <span style="background: var(--secondary); color: var(--bg-darker); padding: 8px 20px; border-radius: 50px; font-weight: 900; font-size: 0.9rem; text-transform: uppercase;">{{ $order->payment_method }}</span>
                </div>
            </div>

            <h3 style="color: white; margin-bottom: 2rem; font-style: italic; text-transform: uppercase; font-weight: 900; font-size: 1.5rem;">ORDER <span>SUMMARY</span></h3>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div style="display: flex; align-items: center; gap: 2rem;">
                            <div style="width: 80px; height: 80px; background: #000; border-radius: 15px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                                <img src="{{ $item->product->main_image ? asset($item->product->main_image) : asset('images/placeholder-car.webp') }}" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            <div>
                                <span style="display: block; color: white; font-weight: 800; font-size: 1.2rem;">{{ $item->product_name }}</span>
                                <span style="color: var(--secondary); font-size: 0.8rem; font-weight: 900; text-transform: uppercase;">Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                        <span style="font-size: 1.2rem; font-weight: 800; color: white;">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 3rem; display: flex; justify-content: space-between; align-items: center; background: rgba(251, 191, 36, 0.1); padding: 2.5rem 3rem; border-radius: 30px; border: 1px solid rgba(251, 191, 36, 0.2);">
                <span style="font-size: 1.8rem; font-weight: 900; font-style: italic; color: white; letter-spacing: -1px;">TOTAL <span>SETTLED</span></span>
                <span style="font-size: 2.5rem; color: var(--secondary); font-weight: 900; text-shadow: 0 0 20px rgba(251, 191, 36, 0.4);">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 4rem; display: flex; gap: 2rem; justify-content: center;">
            <a href="{{ route('orders.index') }}" class="btn btn-primary" style="padding: 1.2rem 4rem; font-weight: 900; border-radius: 50px;">MY GARAGE</a>
            <a href="{{ route('products.index') }}" class="btn" style="background: rgba(255,255,255,0.05); color: white; padding: 1.2rem 4rem; border-radius: 50px; font-weight: 900; border: 1px solid rgba(255,255,255,0.1);">BACK TO SHOP</a>
        </div>
    </div>
</section>
@endsection
