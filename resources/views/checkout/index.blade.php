@extends('layouts.app')

@section('title', 'Final Lap - Collector Checkout')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="text-white text-uppercase italic fw-black mb-0">FINAL <span>LAP</span></h2>
    </div>

    <div class="row g-5">
        <!-- Left Side: Forms -->
        <div class="col-md-7">
            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <!-- Shipping Info -->
                <div class="card bg-dark border-secondary rounded-4 p-4 mb-3 shadow-lg">
                    <h6 class="text-white text-uppercase italic mb-4">SHIPPING <span>DETAILS</span></h6>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="input-label-sm">Full Name</label>
                            <input type="text" name="customer_name" class="form-control garage-input" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Email Address</label>
                            <input type="email" name="customer_email" class="form-control garage-input" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Phone (For Carrier)</label>
                            <input type="text" name="customer_phone" class="form-control garage-input" value="{{ Auth::user()->phone }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="input-label-sm">Region / State</label>
                            <select name="shipping_region" id="shipping_region" class="form-select garage-select" required onchange="handleRegionChange()">
                                <option value="" disabled selected>Select Region</option>
                                @foreach($regions as $code => $name)
                                    <option value="{{ $code }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="input-label-sm">City / Municipality</label>
                            <select name="shipping_city" id="shipping_city" class="form-select garage-select" required onchange="updateShipping()">
                                <option value="" disabled selected>Select Region First</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="input-label-sm">Unit / Street / House No. / Barangay</label>
                            <input type="text" name="shipping_address" class="form-control garage-input" placeholder="e.g. Unit 201, 123 Speed Way St., Brgy. San Antonio" required>
                        </div>
                    </div>
                </div>

                <!-- Collector Preferences -->
                <div class="card bg-dark border-warning rounded-4 p-4 mb-3 shadow-lg">
                    <h6 class="text-warning text-uppercase italic mb-3">COLLECTOR <span>PREFERENCES</span></h6>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="extra_packaging" id="extra_packaging" onchange="updateShipping()">
                        <label class="form-check-label text-white fw-bold small" for="extra_packaging">
                            EXTRA PROTECTION PACKAGING (+ ₱50.00)
                        </label>
                        <p class="text-muted small mb-0" style="font-size: 0.7rem;">Crucial for carded collectors. We use double-walled boxes and extra bubble wrap.</p>
                    </div>
                    <div>
                        <label class="input-label-sm">Special Instructions</label>
                        <textarea name="notes" class="form-control garage-textarea" rows="1" placeholder="e.g. Please pick the best card possible..."></textarea>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg">
                    <h6 class="text-white text-uppercase italic mb-3">PAYMENT <span>METHOD</span></h6>
                    <div class="p-3 glass rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-money-bill-wave text-success fs-4"></i>
                            <div>
                                <span class="text-white fw-bold d-block">Cash on Delivery</span>
                                <span class="text-muted small">Pay when your die-cast arrives</span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">ACTIVE</span>
                    </div>
                    <input type="hidden" name="payment_method" value="Cash on Delivery">
                </div>

                <!-- Hidden inputs for shipping calc -->
                <input type="hidden" name="shipping_fee" id="input_shipping_fee" value="0">
            </form>
        </div>

        <!-- Right Side: Order Summary -->
        <div class="col-md-5">
            <!-- Independent Coupon Section -->
            <div class="card bg-dark border-secondary rounded-4 p-4 mb-4 shadow-lg">
                <label class="input-label-sm mb-3">COUPON CODE</label>
                <form action="{{ route('coupon.apply') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="code" class="form-control garage-input" placeholder="ENTER CODE" value="{{ $couponCode }}">
                    <button type="submit" class="btn btn-outline-warning fw-bold px-4" style="border-radius: 12px; font-size: 0.75rem;">APPLY</button>
                </form>
            </div>

            <div class="card bg-dark border-secondary rounded-4 shadow-lg sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h6 class="text-white text-uppercase italic mb-4">YOUR <span>SELECTION</span></h6>
                    
                    <div class="mb-4">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.6rem;">{{ $item->quantity }}x</span>
                                    <span class="text-white small fw-bold">{{ $item->product->name }}</span>
                                </div>
                                <span class="text-white small">₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="section-divider mb-3">SUMMARY</div>

                    <div class="d-flex justify-content-between mb-2 text-white opacity-75 small">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    @if($discount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success small">
                            <div class="d-flex align-items-center gap-2">
                                <span>Discount ({{ $couponCode }})</span>
                                <form action="{{ route('coupon.remove') }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: var(--text-muted); font-size: 0.6rem; text-decoration: underline; padding: 0;">Remove</button>
                                </form>
                            </div>
                            <span>- ₱{{ number_format($discount, 2) }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2 text-white opacity-75 small">
                        <span>Freight (LBC)</span>
                        <span id="display_shipping">₱0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 text-white opacity-75 small" id="packaging_line" style="display: none !important;">
                        <span>Extra Protection</span>
                        <span id="display_packaging">₱50.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 h4 text-white">
                        <span class="fw-black italic">TOTAL</span>
                        <span class="fw-black text-warning" id="display_total">₱{{ number_format($subtotal - $discount, 2) }}</span>
                    </div>

                    <button type="submit" form="checkout-form" class="btn btn-warning w-100 py-3 fw-black text-uppercase ls-2 shadow-lg" style="border-radius: 12px;">COMPLETE ACQUISITION</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const subtotal = {{ $subtotal }};
    const discount = {{ $discount }};
    const regionalCities = @json($regionalCities);

    function handleRegionChange() {
        const region = document.getElementById('shipping_region').value;
        const citySelect = document.getElementById('shipping_city');
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        if (regionalCities[region]) {
            const cities = regionalCities[region];
            for (const [city, km] of Object.entries(cities)) {
                const option = document.createElement('option');
                option.value = city;
                option.text = city + (km > 0 ? ` (~${km}km)` : '');
                option.setAttribute('data-km', km);
                citySelect.appendChild(option);
            }
        }
        updateShipping();
    }

    function updateShipping() {
        const region = document.getElementById('shipping_region').value;
        const citySelect = document.getElementById('shipping_city');
        const extraPackaging = document.getElementById('extra_packaging').checked;
        const packagingLine = document.getElementById('packaging_line');
        
        let fee = 0;
        let packagingFee = extraPackaging ? 50 : 0;

        if (extraPackaging) {
            packagingLine.setAttribute('style', 'display: flex !important;');
        } else {
            packagingLine.setAttribute('style', 'display: none !important;');
        }

        if (region === 'NCR') {
            const selectedCity = citySelect.options[citySelect.selectedIndex];
            const km = parseFloat(selectedCity.getAttribute('data-km')) || 0;
            if (km > 0) {
                let base = 49;
                if (km <= 5) fee = base + (km * 6);
                else fee = base + (5 * 6) + ((km - 5) * 5);
            } else {
                fee = 60;
            }
        } else if (region) {
            const luzon = ['Region I', 'Region II', 'Region III', 'Region IV-A', 'Region V', 'CAR'];
            fee = luzon.includes(region) ? 150 : 160;
        }

        document.getElementById('display_shipping').innerText = '₱' + fee.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('input_shipping_fee').value = fee;
        
        const total = subtotal - discount + fee + packagingFee;
        document.getElementById('display_total').innerText = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }
</script>
@endsection
