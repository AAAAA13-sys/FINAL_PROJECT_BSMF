@extends('layouts.admin')

@section('content')
<div class="fade-in p-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <a href="{{ route('admin.orders') }}" class="text-secondary text-decoration-none small fw-bold text-uppercase ls-2 mb-2 d-inline-block">← BACK TO ALL SHIPMENTS</a>
            <h1 class="h3 text-white text-uppercase italic fw-black">ORDER <span style="color: var(--secondary);">#{{ $order->order_number }}</span></h1>
        </div>
        <div class="text-end">
            <span class="badge {{ $order->status == 'delivered' ? 'bg-success' : 'bg-warning' }} text-dark px-4 py-2 rounded-pill fw-black ls-1">
                {{ strtoupper($order->status) }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card glass border-secondary mb-4">
                <div class="card-header bg-transparent border-secondary p-4">
                    <h5 class="text-white text-uppercase italic mb-0">MANIFEST <span>CONTENTS</span></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4 border-secondary">Model</th>
                                    <th class="border-secondary">Price</th>
                                    <th class="border-secondary">Qty</th>
                                    <th class="border-secondary text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div style="width: 80px; height: 50px; background: #000; border-radius: 8px; overflow: hidden; margin-right: 1.5rem; border: 1px solid var(--glass-border);">
                                                <img src="{{ $item->product_image ? asset($item->product_image) : asset('images/placeholder-car.webp') }}" style="width: 100%; height: 100%; object-fit: contain;">
                                            </div>
                                            <div>
                                                <div class="text-white fw-bold">{{ $item->product_name }}</div>
                                                <div class="text-muted small text-uppercase" style="font-size: 0.6rem;">{{ $item->product_brand }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-white-50">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-white">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 text-warning fw-black">₱{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-secondary p-4">
                    <div class="d-flex justify-content-end">
                        <div style="width: 300px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="text-white fw-bold">₱{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Shipping Fee</span>
                                <span class="text-white fw-bold">₱{{ number_format($order->shipping_fee, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-danger small">Discount ({{ $order->coupon_code }})</span>
                                    <span class="text-danger fw-bold">-₱{{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            <hr class="border-secondary opacity-25 my-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-white fw-black italic">GRAND TOTAL</span>
                                <span class="text-secondary h4 fw-black mb-0">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Internal Notes -->
            <div class="card glass border-secondary">
                <div class="card-header bg-transparent border-secondary p-4">
                    <h5 class="text-white text-uppercase italic mb-0">CUSTOMER <span>NOTES</span></h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-white-50 mb-0 italic">"{{ $order->notes ?? 'No special instructions provided.' }}"</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Collector Profile -->
            <div class="card glass border-info mb-4">
                <div class="card-header bg-transparent border-info p-4">
                    <h5 class="text-info text-uppercase italic mb-0">COLLECTOR <span>PROFILE</span></h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">{{ strtoupper(substr($order->customer_name, 0, 2)) }}</div>
                    <h5 class="text-white mb-1">{{ $order->customer_name }}</h5>
                    <p class="text-muted small mb-3">{{ $order->customer_email }}</p>
                    <hr class="border-secondary opacity-25">
                    <div class="text-start mt-3">
                        <h6 class="text-white-50 small text-uppercase ls-1 mb-2">Delivery Address</h6>
                        <div class="bg-dark bg-opacity-50 p-3 rounded-3 border border-secondary border-opacity-25 small text-white">
                            {{ $order->shipping_address }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipment Control -->
            <div class="card glass border-warning shadow-lg">
                <div class="card-header bg-transparent border-warning p-4">
                    <h5 class="text-warning text-uppercase italic mb-0">SHIPMENT <span>CONTROL</span></h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase ls-1">Current Status</label>
                            <select name="status" class="form-select bg-dark border-secondary text-white fw-bold">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>SHIPPED</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 py-3 fw-black ls-1 text-uppercase">UPDATE SHIPMENT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
