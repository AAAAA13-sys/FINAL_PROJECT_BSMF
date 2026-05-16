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
                @foreach($items as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                @endforeach
                <!-- Shipping Info -->
                <div class="card bg-dark border-secondary rounded-4 p-4 mb-3 shadow-lg">
                    <h6 class="text-white text-uppercase italic mb-4">SHIPPING <span>DETAILS</span></h6>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="input-label-sm">Full Name</label>
                            <input type="text" name="customer_name" class="form-control garage-input" value="{{ old('customer_name', Auth::user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Email Address</label>
                            <input type="email" name="customer_email" class="form-control garage-input" value="{{ old('customer_email', Auth::user()->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="input-label-sm">Phone (For Carrier)</label>
                            <input type="text" name="customer_phone" class="form-control garage-input" value="{{ old('customer_phone', Auth::user()->phone) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="input-label-sm">Region</label>
                            <select name="shipping_region" id="shipping_region" class="form-select garage-select" required onchange="handleRegionChange()">
                                <option value="" disabled {{ !old('shipping_region', Auth::user()->region) ? 'selected' : '' }}>Select Region</option>
                                @foreach($regions as $code => $name)
                                    <option value="{{ $code }}" {{ old('shipping_region', Auth::user()->region) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="input-label-sm">City / Municipality</label>
                            <select name="shipping_city" id="shipping_city" class="form-select garage-select" required onchange="updateShipping()">
                                <option value="" disabled {{ !old('shipping_city', Auth::user()->city) ? 'selected' : '' }}>{{ old('shipping_region', Auth::user()->region) ? 'Select City' : 'Select Region First' }}</option>
                                @if(old('shipping_region', Auth::user()->region) && isset($regionalCities[old('shipping_region', Auth::user()->region)]))
                                    @foreach($regionalCities[old('shipping_region', Auth::user()->region)] as $cityName => $km)
                                        <option value="{{ $cityName }}" {{ old('shipping_city', Auth::user()->city) == $cityName ? 'selected' : '' }} data-km="{{ $km }}">{{ $cityName }}{{ $km > 0 ? " (~{$km}km)" : '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label class="input-label-sm">Unit / Street / House No. / Barangay</label>
                            <input type="text" name="shipping_address" class="form-control garage-input" value="{{ old('shipping_address', Auth::user()->default_shipping_address) }}" placeholder="e.g. Unit 201, 123 Speed Way St., Brgy. San Antonio" required>
                        </div>
                    </div>
                </div>

                <!-- Collector Preferences -->
                <div class="card bg-dark border-glass rounded-4 p-4 mb-3 shadow-lg">
                    <h6 class="text-white text-uppercase italic mb-3">COLLECTOR <span>PREFERENCES</span></h6>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="extra_packaging" id="extra_packaging" onchange="updateShipping()">
                        <label class="form-check-label text-white fw-bold small" for="extra_packaging">
                            EXTRA PROTECTION PACKAGING (+ ₱50.00)
                        </label>
                        <p class="text-muted small mb-0 fs-07rem">Crucial for carded collectors. We use double-walled boxes and extra bubble wrap.</p>
                    </div>
                    <div>
                        <label class="input-label-sm">Special Instructions</label>
                        <textarea name="notes" class="form-control garage-textarea" rows="1" placeholder="e.g. Please pick the best card possible...">{{ old('notes') }}</textarea>
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
                <form id="coupon-form" class="d-flex gap-2">
                    <input type="text" id="coupon_code_input" name="code" class="form-control garage-input" placeholder="ENTER CODE" value="{{ $couponCode }}">
                    <button type="button" onclick="applyCoupon()" class="btn btn-outline-danger fw-bold px-4 coupon-btn-apply">APPLY</button>
                </form>
                <div id="coupon-message" class="small mt-2 d-none"></div>
            </div>

            <div class="card bg-dark border-secondary rounded-4 shadow-lg sticky-top checkout-sticky">
                <div class="card-body p-4">
                    <h6 class="text-white text-uppercase italic mb-4">YOUR <span>SELECTION</span></h6>
                    
                    <div class="mb-4">
                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-secondary rounded-pill badge-xs">{{ $item->quantity }}x</span>
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
                    
                    <div id="discount-container" class="{{ $discount > 0 ? 'd-block' : 'd-none' }}">
                        <div class="d-flex justify-content-between mb-2 text-success small">
                            <div class="d-flex align-items-center gap-2">
                                <span>Discount (<span id="display_coupon_code">{{ $couponCode }}</span>)</span>
                                <button type="button" onclick="removeCoupon()" class="remove-coupon-link bg-transparent border-0 p-0 text-decoration-underline">Remove</button>
                            </div>
                            <span>- ₱<span id="display_discount">{{ number_format($discount, 2) }}</span></span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-white opacity-75 small">
                        <span>Courier: <span id="carrier_name">LBC Express</span></span>
                        <span id="display_shipping">₱0.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 text-white opacity-75 small d-none-important" id="packaging_line">
                        <span>Extra Protection</span>
                        <span id="display_packaging">₱50.00</span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 h4 text-white">
                        <span class="fw-black italic">TOTAL</span>
                        <span class="fw-black text-white" id="display_total">₱{{ number_format($subtotal - $discount, 2) }}</span>
                    </div>

                    <button type="submit" form="checkout-form" class="btn btn-danger w-100 py-3 fw-black text-uppercase ls-2 shadow-lg rounded-3" onclick="if(this.form.checkValidity()) { this.disabled=true; this.innerHTML='PROCESSING...'; this.form.submit(); }">COMPLETE ACQUISITION</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const subtotal = {{ $subtotal }};
    let discount = {{ $discount }};
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
        const selectedCity = citySelect.options[citySelect.selectedIndex];
        const distance = selectedCity ? parseFloat(selectedCity.getAttribute('data-km') || 0) : 0;
        
        const extraPackaging = document.getElementById('extra_packaging').checked;
        const packagingLine = document.getElementById('packaging_line');
        const carrierDisplay = document.getElementById('carrier_name');
        
        let fee = 0;
        let carrier = "Freight";

        if (region) {
            if (region === 'NCR') {
                carrier = "Lalamove";
                const base = 49.00;
                if (distance <= 0) fee = 60.00;
                else if (distance <= 5) fee = base + (distance * 6);
                else fee = base + (5 * 6) + ((distance - 5) * 5);
            } else {
                carrier = "LBC Express";
                const luzonRegions = ['Region I', 'Region II', 'Region III', 'Region IV-A', 'Region V', 'CAR'];
                fee = luzonRegions.includes(region) ? 150.00 : 180.00;
            }
        }

        let packagingFee = extraPackaging ? 50 : 0;

        if (extraPackaging) {
            packagingLine.classList.remove('d-none-important');
            packagingLine.classList.add('d-flex-important');
        } else {
            packagingLine.classList.remove('d-flex-important');
            packagingLine.classList.add('d-none-important');
        }

        carrierDisplay.innerText = carrier;
        document.getElementById('display_shipping').innerText = '₱' + fee.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById('input_shipping_fee').value = fee;
        
        const total = subtotal - discount + fee + packagingFee;
        document.getElementById('display_total').innerText = '₱' + total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    async function applyCoupon() {
        const code = document.getElementById('coupon_code_input').value;
        const messageDiv = document.getElementById('coupon-message');
        
        if (!code) return;

        try {
            const response = await fetch("{{ route('coupon.apply') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code, subtotal: subtotal })
            });

            const data = await response.json();

            if (data.success) {
                discount = data.discount;
                document.getElementById('display_discount').innerText = discount.toLocaleString(undefined, {minimumFractionDigits: 2});
                document.getElementById('display_coupon_code').innerText = data.code;
                document.getElementById('discount-container').classList.remove('d-none');
                document.getElementById('discount-container').classList.add('d-block');
                
                messageDiv.innerText = data.message;
                messageDiv.className = 'small mt-2 text-success';
                messageDiv.classList.remove('d-none');
                
                updateShipping();
            } else {
                messageDiv.innerText = data.message || 'Invalid coupon';
                messageDiv.className = 'small mt-2 text-danger';
                messageDiv.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function removeCoupon() {
        try {
            const response = await fetch("{{ route('coupon.remove') }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                discount = 0;
                document.getElementById('discount-container').classList.remove('d-block');
                document.getElementById('discount-container').classList.add('d-none');
                document.getElementById('coupon_code_input').value = '';
                
                const messageDiv = document.getElementById('coupon-message');
                messageDiv.innerText = data.message;
                messageDiv.className = 'small mt-2 text-info';
                messageDiv.classList.remove('d-none');
                
                updateShipping();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    window.onload = updateShipping;
</script>
@endsection
