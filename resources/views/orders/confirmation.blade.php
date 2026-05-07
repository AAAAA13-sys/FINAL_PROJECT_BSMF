@extends('layouts.app')

@section('title', 'Order Confirmed - COLLECTOR GARAGE')

@section('content')
<section class="confirmation-section">
    <div class="fade-in confirmation-wrapper">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="text-uppercase italic fw-black mb-0 confirmation-title">ORDER <span>SUCCESSFUL</span></h2>
        <p class="confirmation-receipt">
            Receipt <span>#{{ $order->order_number }}</span>
        </p>
        
        <div class="glass confirmation-glass-box">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="confirmation-summary-title mb-0">SHIPMENT <span>DETAILS</span></h3>
                <div class="confirmation-payment-box text-end">
                    <span class="confirmation-payment-label me-2">PAYMENT METHOD:</span>
                    <span class="confirmation-payment-badge">{{ $order->payment_method }}</span>
                </div>
            </div>
            
            <div class="confirmation-detail-box mb-4">
                <p class="confirmation-detail-text">{{ $order->shipping_address }}</p>
            </div>

            <h3 class="confirmation-summary-title">ORDER <span>SUMMARY</span></h3>
            <div class="confirmation-items-list mb-4">
                @foreach($order->items as $item)
                    <div class="confirmation-item-row">
                        <div class="confirmation-item-info">
                            <div class="confirmation-item-img-box">
                                <img src="{{ $item->product_image ? asset($item->product_image) : asset('images/placeholder-car.webp') }}" class="confirmation-item-img">
                            </div>
                            <div>
                                <span class="confirmation-item-name">{{ $item->product_name }}</span>
                                <span class="confirmation-item-qty">Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                        <span class="confirmation-item-price">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-top border-glass pt-3 mb-4">
                <div class="d-flex justify-content-between text-muted small mb-2">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between text-success small mb-2">
                        <span>Discount</span>
                        <span>-₱{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between text-muted small mb-2">
                    <span>Shipping ({{ $order->courier_name ?? 'Freight' }})</span>
                    <span>₱{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                @if($order->extra_packaging_requested)
                    <div class="d-flex justify-content-between text-muted small mb-2">
                        <span>Extra Protection</span>
                        <span>₱50.00</span>
                    </div>
                @endif
            </div>

            <div class="confirmation-total-settled-box">
                <span class="confirmation-total-label">TOTAL <span>SETTLED</span></span>
                <span class="confirmation-total-value">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="confirmation-footer-actions">
            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-garage">MY GARAGE</a>
            <a href="{{ route('products.index') }}" class="btn btn-back-shop">BACK TO SHOP</a>
        </div>
    </div>
</section>
@endsection
