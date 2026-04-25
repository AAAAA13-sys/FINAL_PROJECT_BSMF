@extends('layouts.app')

@section('title', 'Final Lap - Collector Checkout')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-5">
        <h1 class="text-white text-uppercase italic fw-black mb-0">FINAL <span>LAP</span></h1>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-5">
            <!-- Left Side: Forms -->
            <div class="col-md-7">
                <!-- Shipping Info -->
                <div class="card bg-dark border-secondary rounded-4 p-4 mb-4">
                    <h5 class="text-white text-uppercase italic mb-4">SHIPPING <span>DETAILS</span></h5>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-muted small text-uppercase">Full Name</label>
                            <input type="text" name="customer_name" class="form-control bg-transparent text-white border-secondary" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small text-uppercase">Email Address</label>
                            <input type="email" name="customer_email" class="form-control bg-transparent text-white border-secondary" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small text-uppercase">Phone (For Carrier)</label>
                            <input type="text" name="customer_phone" class="form-control bg-transparent text-white border-secondary" value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small text-uppercase">Shipping Address</label>
                            <textarea name="shipping_address" class="form-control bg-transparent text-white border-secondary" rows="3" required>{{ Auth::user()->default_shipping_address }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Collector Preferences -->
                <div class="card bg-dark border-warning rounded-4 p-4 mb-4">
                    <h5 class="text-warning text-uppercase italic mb-4">COLLECTOR <span>PREFERENCES</span></h5>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="extra_packaging" id="extra_packaging">
                        <label class="form-check-label text-white fw-bold" for="extra_packaging">
                            EXTRA PROTECTION PACKAGING (+ $0.00)
                        </label>
                        <p class="text-muted small mb-0">Crucial for carded collectors. We use double-walled boxes and extra bubble wrap.</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted small text-uppercase">Notes for the Garage</label>
                        <textarea name="notes" class="form-control bg-transparent text-white border-secondary" rows="2" placeholder="e.g. Please pick the best card possible..."></textarea>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card bg-dark border-secondary rounded-4 p-4">
                    <h5 class="text-white text-uppercase italic mb-4">PAYMENT <span>METHOD</span></h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input class="btn-check" type="radio" name="payment_method" id="pay_card" value="Credit Card" checked>
                            <label class="btn btn-outline-secondary px-4 py-3" for="pay_card">
                                <i class="fas fa-credit-card me-2"></i> CREDIT CARD
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="btn-check" type="radio" name="payment_method" id="pay_paypal" value="PayPal">
                            <label class="btn btn-outline-secondary px-4 py-3" for="pay_paypal">
                                <i class="fab fa-paypal me-2"></i> PAYPAL
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-md-5">
                <div class="card bg-dark border-secondary rounded-4 shadow-lg sticky-top" style="top: 100px;">
                    <div class="card-body p-4">
                        <h5 class="text-white text-uppercase italic mb-4">YOUR <span>SELECTION</span></h5>
                        
                        <div class="mb-4">
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted">{{ $item->quantity }}x</span>
                                        <span class="text-white small">{{ $item->product->name }}</span>
                                    </div>
                                    <span class="text-white small">${{ number_format($item->price_at_time * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="border-secondary my-4">

                        <div class="d-flex justify-content-between mb-2 text-muted small">
                            <span>Subtotal</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-2 text-success small">
                                <span>Discount ({{ $couponCode }})</span>
                                <span>- ${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mb-4 text-muted small">
                            <span>Shipping</span>
                            <span>{{ $shipping == 0 ? 'FREE' : '$' . number_format($shipping, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-5 h3 text-white">
                            <span class="fw-black italic">TOTAL</span>
                            <span class="fw-black text-warning">${{ number_format($total, 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-3 fw-black ls-1 h5 mb-3">COMPLETE ACQUISITION</button>
                        <p class="text-center small text-muted mb-0"><i class="fas fa-shield-alt me-1"></i> 256-BIT ENCRYPTED PIT STOP</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
