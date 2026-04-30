@extends('layouts.app')

@section('title', 'Order Confirmed - BSMF Garage')

@section('content')
<section class="section-padding" style="text-align: center; min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    <div class="fade-in" style="width: 100%; max-width: 600px; margin: 0 auto; padding: 1rem;">
        <div style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 0.5rem; text-shadow: 0 0 15px rgba(251, 191, 36, 0.2);">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="text-uppercase italic fw-black mb-0" style="font-size: 1.5rem; letter-spacing: -0.5px;">ORDER <span>SUCCESSFUL</span></h2>
        <p style="margin-top: 0.15rem; font-size: 0.85rem; color: var(--secondary); font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">
            Receipt #{{ $order->order_number }}
        </p>
        
        <div class="glass" style="margin-top: 1.5rem; padding: 1.5rem; text-align: left; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="color: white; margin-bottom: 0.5rem; font-style: italic; text-transform: uppercase; font-weight: 900; font-size: 1rem;">SHIPMENT <span>DETAILS</span></h3>
                    <div style="background: rgba(255,255,255,0.03); padding: 0.75rem 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                        <p style="white-space: pre-line; margin-bottom: 0; font-size: 0.8rem; line-height: 1.4; color: rgba(255,255,255,0.7);">{{ $order->shipping_address }}</p>
                    </div>
                </div>
                <div style="text-align: right;">
                    <h4 style="font-size: 0.6rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.4rem; letter-spacing: 1px; font-weight: 800;">PAYMENT MODE</h4>
                    <span style="background: var(--secondary); color: var(--bg-darker); padding: 4px 12px; border-radius: 50px; font-weight: 900; font-size: 0.7rem; text-transform: uppercase;">{{ $order->payment_method }}</span>
                </div>
            </div>

            <h3 style="color: white; margin-bottom: 1rem; font-style: italic; text-transform: uppercase; font-weight: 900; font-size: 0.9rem;">ORDER <span>SUMMARY</span></h3>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; background: #000; border-radius: 10px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                                <img src="{{ $item->product_image ? asset($item->product_image) : asset('images/placeholder-car.webp') }}" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            <div>
                                <span style="display: block; color: white; font-weight: 800; font-size: 0.85rem;">{{ $item->product_name }}</span>
                                <span style="color: var(--secondary); font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                        <span style="font-size: 0.9rem; font-weight: 800; color: white;">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; background: rgba(251, 191, 36, 0.08); padding: 1rem 1.5rem; border-radius: 15px; border: 1px solid rgba(251, 191, 36, 0.15);">
                <span style="font-size: 1rem; font-weight: 900; font-style: italic; color: white; letter-spacing: -0.2px;">TOTAL <span>SETTLED</span></span>
                <span style="font-size: 1.4rem; color: var(--secondary); font-weight: 900; text-shadow: 0 0 10px rgba(251, 191, 36, 0.3);">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('orders.index') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 900; border-radius: 50px; font-size: 0.8rem;">MY GARAGE</a>
            <a href="{{ route('products.index') }}" class="btn" style="background: rgba(255,255,255,0.05); color: white; padding: 0.75rem 2rem; border-radius: 50px; font-weight: 900; border: 1px solid rgba(255,255,255,0.1); font-size: 0.8rem;">BACK TO SHOP</a>
        </div>
    </div>
</section>
@endsection
