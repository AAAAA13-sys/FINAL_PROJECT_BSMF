@extends('layouts.app')

@section('title', 'Final Lap - Checkout')

@section('content')
<div class="auth-container fade-in" style="max-width: 800px;">
    <div class="auth-header">
        <h1 class="auth-title">FINAL <span>LAP</span></h1>
        <p class="auth-subtitle">Complete Your Order</p>
    </div>

    <!-- Coupon Section -->
    <div class="glass" style="padding: 1.5rem; margin-bottom: 2rem;">
        <h4 style="color: var(--secondary); margin-bottom: 1rem; font-size: 0.8rem; text-transform: uppercase;">Promotional Code</h4>
        @if($couponCode)
            <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; padding: 0.8rem 1.2rem; border-radius: 8px;">
                <span style="color: #10b981; font-weight: 800;"><i class="fas fa-tag"></i> {{ $couponCode }} APPLIED</span>
                <form action="{{ route('coupon.remove') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: none; border: none; color: var(--danger); cursor: pointer; font-size: 0.8rem; font-weight: 800;">REMOVE</button>
                </form>
            </div>
        @else
            <form action="{{ route('coupon.apply') }}" method="POST" style="display: flex; gap: 1rem;">
                @csrf
                <input type="text" name="code" class="form-control" placeholder="Enter coupon code..." style="margin-bottom: 0;">
                <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">APPLY</button>
            </form>
        @endif
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="shipping_address">SHIPPING ADDRESS</label>
            <textarea name="shipping_address" id="shipping_address" class="form-control" rows="4" placeholder="Enter your full delivery address..." required></textarea>
        </div>
        
        <div class="form-group">
            <label for="payment_method">PAYMENT METHOD</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>
        </div>

        <div class="glass" style="padding: 1.5rem; margin: 2rem 0;">
            <h4 style="color: var(--secondary); margin-bottom: 1rem;">ORDER SUMMARY</h4>
            @foreach($cartItems as $item)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
            
            <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 1rem 0;">
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-muted);">
                <span>Subtotal</span>
                <span>${{ number_format($total, 2) }}</span>
            </div>

            @if($discount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #10b981;">
                    <span>Discount ({{ $couponCode }})</span>
                    <span>-${{ number_format($discount, 2) }}</span>
                </div>
            @endif

            <div style="display: flex; justify-content: space-between; font-weight: 900; font-size: 1.5rem; margin-top: 1rem; border-top: 2px solid var(--secondary); padding-top: 1rem;">
                <span>TOTAL</span>
                <span style="color: var(--secondary);">${{ number_format($total - $discount, 2) }}</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.5rem; font-size: 1.2rem;">PLACE ORDER</button>
    </form>
</div>
@endsection
