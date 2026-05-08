@extends('layouts.app')

@section('title', 'Your Cart - BSMF GARAGE')

@section('content')
<section class="section-padding bg-surface-base min-vh-100 pt-5">
    <div class="container">
        <div class="mb-5">
            <a href="javascript:history.back()" class="text-muted text-decoration-none text-xs fw-black text-uppercase tracking-wider px-4 py-2 bg-glass border-glass rounded-pill shadow-raised transition-300 hover-text-white hover-border-slate d-inline-flex align-items-center gap-2">
                <i class="fas fa-chevron-left icon-sm"></i> RETURN TO PREVIOUS
            </a>
        </div>
        <div class="d-flex align-items-center gap-3 mb-5">
            <h2 class="text-white text-uppercase fw-black mb-0">YOUR <span>CART</span></h2>
            <span class="text-xs text-cool-slate fw-black text-uppercase tracking-wider bg-slate-subtle px-3 py-1 rounded-pill border-slate-subtle">{{ $cart->items->count() }} MODELS</span>
        </div>

        @if($cart->items->count() > 0)
            <form action="{{ route('checkout') }}" method="GET" id="cartForm">
            <div class="row g-5">
                <div class="col-md-8">
                    <!-- Cart Items Table -->
                    <div class="bg-surface-elevated border-glass rounded-24 overflow-hidden shadow-raised mb-5">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0 bg-transparent">
                                <thead>
                                    <tr class="text-xs text-uppercase tracking-wider text-muted border-bottom-glass">
                                        <th class="ps-4 py-4" style="width: 50px;">
                                            <label class="custom-checkbox m-0">
                                                <input type="checkbox" id="selectAllItems" checked>
                                                <span class="checkbox-box"></span>
                                            </label>
                                        </th>
                                        <th class="py-4">Collector Model</th>
                                        <th class="py-4">Price</th>
                                        <th class="py-4">Quantity</th>
                                        <th class="py-4 text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr class="align-middle border-bottom-glass-subtle cart-item-row" data-price="{{ $item->price_at_time ?? $item->product->price }}" data-quantity="{{ $item->quantity }}">
                                            <td class="ps-4 py-4">
                                                <label class="custom-checkbox m-0">
                                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="item-checkbox" checked>
                                                    <span class="checkbox-box"></span>
                                                </label>
                                            </td>
                                            <td class="py-4">
                                                <div class="d-flex align-items-center gap-4">
                                                    <div class="bg-surface-base border-glass rounded-12 p-2 w-90 shadow-inset">
                                                        <img src="{{ $item->product->main_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="{{ $item->product->name }}">
                                                    </div>
                                                    <div>
                                                        <h6 class="text-white mb-1 fw-bolder fs-6">{{ $item->product->name }}</h6>
                                                        <span class="text-xs text-cool-slate fw-black text-uppercase tracking-wide">{{ $item->product->brand->name }}</span>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn-link-destructive p-0" onclick="removeItem({{ $item->id }})">Remove Piece</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-white fw-semibold">₱{{ number_format($item->price_at_time ?? $item->product->price, 2) }}</td>
                                            <td class="py-4">
                                                <div class="quantity-selector-bsmf">
                                                    <button type="button" class="qty-btn" onclick="decrementQty({{ $item->id }}, {{ $item->product->stock_quantity }})">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                        name="quantities[{{ $item->id }}]" 
                                                        value="{{ $item->quantity }}" 
                                                        min="1" 
                                                        max="{{ $item->product->stock_quantity }}" 
                                                        class="qty-input-field quantity-input" 
                                                        id="qty-{{ $item->id }}"
                                                        readonly
                                                        onchange="updateQuantity({{ $item->id }}, this.value)">
                                                    <button type="button" class="qty-btn" onclick="incrementQty({{ $item->id }}, {{ $item->product->stock_quantity }})">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="py-4 text-end pe-4 text-white fw-black fs-5 item-subtotal">₱{{ number_format(($item->price_at_time ?? $item->product->price) * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="reveal">
                        <h3 class="text-white text-uppercase mb-4 fw-black fs-4 tracking-wide">VAULT <span>RECOMMENDATIONS</span></h3>
                        <div class="row g-4">
                            @foreach($recommendedProducts as $rec)
                                <div class="col-md-3">
                                    <a href="{{ route('products.show', $rec->id) }}" class="text-decoration-none">
                                        <div class="product-card bg-surface-elevated border-glass rounded-20 p-4 shadow-raised transition-transform-300">
                                            <div class="bg-surface-base rounded-12 p-2 mb-4 shadow-inset">
                                                <img src="{{ $rec->main_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="{{ $rec->name }}">
                                            </div>
                                            <h6 class="text-white mb-1 fw-bolder text-sm h-2-2-rem overflow-hidden">{{ $rec->name }}</h6>
                                            <div class="mb-2 text-xs text-warm-bronze">
                                                @php
                                                    $rating = $rec->reviews_avg_rating ?? 0;
                                                    $fullStars = floor($rating);
                                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                                @endphp
                                                @for($i = 0; $i < $fullStars; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @if($halfStar)
                                                    <i class="fas fa-star-half-alt"></i>
                                                    @for($i = 0; $i < 4 - $fullStars; $i++)
                                                        <i class="far fa-star text-muted"></i>
                                                    @endfor
                                                @else
                                                    @for($i = 0; $i < 5 - $fullStars; $i++)
                                                        <i class="far fa-star text-muted"></i>
                                                    @endfor
                                                @endif
                                                <span class="text-muted ms-1 text-xs">({{ $rec->reviews_count ?? 0 }})</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="text-cool-slate fw-black text-sm">₱{{ number_format($rec->price, 2) }}</span>
                                                <span class="text-muted text-xs fw-bolder text-uppercase">View <i class="fas fa-chevron-right ms-1"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="bg-surface-elevated border-glass rounded-24 p-5 shadow-raised sticky-top-120">
                        <h4 class="text-white text-uppercase mb-4 fw-black tracking-wide">GARAGE <span>SUMMARY</span></h4>
                        
                        @if($discount > 0)
                            <div class="d-flex justify-content-between mb-3 text-sm text-brand-red fw-semibold">
                                <div class="d-flex align-items-center gap-2">
                                    <span>Discount Applied</span>
                                    <form action="{{ route('coupon.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link-muted text-xs text-decoration-underline p-0">Remove</button>
                                    </form>
                                </div>
                                <span>-₱{{ number_format($discount, 2) }}</span>
                            </div>
                        @endif


                        <hr class="hr-glass">

                        <div class="d-flex justify-content-between mb-5 align-items-end">
                            <span class="fw-black fst-italic text-muted text-uppercase tracking-wide">Total</span>
                            <span class="fs-2 fw-black text-white lh-1" id="cartTotalDisplay">₱{{ number_format($total, 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-16 fw-black tracking-wider text-sm" id="checkoutBtn">ACQUIRE PIECES</button>
                    </div>
                </div>
            </div>
            </form>
        @else
            <div class="text-center py-5 px-4 bg-surface-elevated border-glass rounded-40 shadow-inset">
                <i class="fas fa-box-open fs-huge text-cool-slate mb-5 opacity-20"></i>
                <h2 class="text-white mb-2 fw-black text-uppercase">Your Garage is Empty</h2>
                <p class="text-muted fs-5 mb-5 fw-medium">Start building your legendary collection today.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-black tracking-wider">DISCOVER MODELS</a>
            </div>
        @endif
    </div>
</section>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllItems');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const totalDisplay = document.getElementById('cartTotalDisplay');
        const checkoutBtn = document.getElementById('checkoutBtn');

        function calculateTotal() {
            let total = 0;
            let checkedCount = 0;
            
            document.querySelectorAll('.cart-item-row').forEach(row => {
                const checkbox = row.querySelector('.item-checkbox');
                if (checkbox.checked) {
                    const price = parseFloat(row.dataset.price);
                    const qty = parseInt(row.querySelector('.quantity-input').value);
                    total += price * qty;
                    checkedCount++;
                }
            });

            totalDisplay.innerText = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Disable button if nothing selected
            checkoutBtn.disabled = checkedCount === 0;
            checkoutBtn.style.opacity = checkedCount === 0 ? '0.5' : '1';
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            calculateTotal();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = Array.from(checkboxes).every(c => c.checked);
                selectAll.checked = allChecked;
                calculateTotal();
            });
        });

        // Initial calculation
        calculateTotal();

        // Expose functions to window for global access
        window.removeItem = function(id) {
            if (confirm('Are you sure you want to remove this piece from your garage?')) {
                fetch(`/cart/remove/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                }).then(res => {
                    if (res.ok) window.location.reload();
                    else alert('Failed to remove item.');
                });
            }
        };

        window.updateQuantity = function(id, qty) {
            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            }).then(res => {
                if (res.ok) {
                    calculateTotal();
                    const checkbox = document.querySelector(`.item-checkbox[value="${id}"]`);
                    if (checkbox) {
                        const row = checkbox.closest('tr');
                        const price = parseFloat(row.dataset.price);
                        row.querySelector('.item-subtotal').innerText = '₱' + (price * qty).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                } else {
                    alert('Stock limit reached or error updating quantity.');
                    window.location.reload();
                }
            });
        };

        window.incrementQty = function(id, max) {
            const input = document.getElementById('qty-' + id);
            let val = parseInt(input.value);
            if (val < max) {
                input.value = val + 1;
                updateQuantity(id, input.value);
            }
        };

        window.decrementQty = function(id, max) {
            const input = document.getElementById('qty-' + id);
            let val = parseInt(input.value);
            if (val > 1) {
                input.value = val - 1;
                updateQuantity(id, input.value);
            }
        };
    });
</script>
@endpush
@endsection
