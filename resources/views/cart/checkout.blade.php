@extends('layouts.app')

@section('title', 'Final Lap - Checkout')

@section('content')
<div class="auth-container fade-in checkout-container-large">
    <div class="auth-header">
        <h1 class="auth-title">FINAL <span>LAP</span></h1>
        <p class="auth-subtitle">Complete Your Order</p>
    </div>

    <!-- Coupon Section -->
    <div class="glass promo-box">
        <h4 class="promo-title">Promotional Code</h4>
        @if($couponCode)
            <div class="promo-applied-box">
                <span class="promo-applied-text"><i class="fas fa-tag"></i> {{ $couponCode }} APPLIED</span>
                <form action="{{ route('coupon.remove') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="promo-remove-btn">REMOVE</button>
                </form>
            </div>
        @else
            <form action="{{ route('coupon.apply') }}" method="POST" class="d-flex gap-3">
                @csrf
                <input type="text" name="code" class="form-control mb-0" placeholder="Enter coupon code...">
                <button type="submit" class="btn btn-primary btn-checkout-apply">APPLY</button>
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

        <div class="glass checkout-summary-box">
            <h4 class="summary-title">ORDER SUMMARY</h4>
            @foreach($cartItems as $item)
                <div class="summary-item">
                    <span>{{ $item->product->name }} x {{ $item->quantity }}</span>
                    <span>₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                </div>
            @endforeach
            
            <hr class="summary-hr">
            
            <div class="summary-subtotal">
                <span>Subtotal</span>
                <span>₱{{ number_format($total, 2) }}</span>
            </div>

            @if($discount > 0)
                <div class="summary-discount">
                    <span>Discount ({{ $couponCode }})</span>
                    <span>-₱{{ number_format($discount, 2) }}</span>
                </div>
            @endif

            <div class="summary-total-row">
                <span>TOTAL</span>
                <span class="summary-total-value">₱{{ number_format($total - $discount, 2) }}</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-place-order">PLACE ORDER</button>
    </form>
</div>
@endsection
