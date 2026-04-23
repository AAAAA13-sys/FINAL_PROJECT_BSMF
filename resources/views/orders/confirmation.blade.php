@extends('layouts.app')

@section('title', 'Order Confirmed - BFMSL')

@section('content')
<section class="section-padding" style="text-align: center;">
    <div class="auth-container fade-in" style="max-width: 800px; margin: 0 auto; padding: 5rem 3rem;">
        <div style="font-size: 5rem; color: var(--secondary); margin-bottom: 2rem;">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="auth-title">ORDER <span>CONFIRMED</span></h1>
        <p class="auth-subtitle" style="margin-top: 1rem; font-size: 1.1rem; color: var(--text-muted);">
            Thank you for your order, #BFMSL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}!
        </p>
        
        <div class="glass" style="margin-top: 4rem; padding: 2.5rem; text-align: left;">
            <h3 style="color: var(--secondary); margin-bottom: 2rem; font-style: italic; text-transform: uppercase;">Order Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                <div>
                    <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem;">Shipping Address</h4>
                    <p style="white-space: pre-line;">{{ $order->shipping_address }}</p>
                </div>
                <div>
                    <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.5rem;">Payment Method</h4>
                    <p>{{ $order->payment_method }}</p>
                </div>
            </div>

            <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 2rem 0;">

            @foreach($order->items as $item)
                <div style="display: flex; justify-content: space-between; margin-bottom: 1rem; font-weight: 600;">
                    <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                    <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach

            <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 2px solid var(--secondary); padding-top: 1.5rem;">
                <span style="font-size: 1.5rem; font-weight: 900; font-style: italic;">TOTAL PAID</span>
                <span style="font-size: 2rem; color: var(--secondary); font-weight: 900;">${{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        <div style="margin-top: 4rem; display: flex; gap: 2rem; justify-content: center;">
            <a href="{{ route('orders.index') }}" class="btn btn-primary" style="padding: 1rem 3rem;">View History</a>
            <a href="{{ route('products.index') }}" class="btn" style="background: var(--glass); color: white; padding: 1rem 3rem;">Continue Shopping</a>
        </div>
    </div>
</section>
@endsection
