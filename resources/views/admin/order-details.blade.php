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

    <div class="row">
        <div class="col-md-12">
            <!-- Order Items -->
            <div class="card glass border-secondary mb-4">
                <div class="card-header bg-transparent border-secondary p-4 d-flex justify-content-between align-items-center">
                    <h5 class="text-white text-uppercase italic mb-0">MANIFEST <span>CONTENTS</span></h5>
                    
                    <!-- Integrated Shipment Control Trigger -->
                    <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="fas fa-cog fa-lg"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4 border-secondary">Model Detail</th>
                                    <th class="border-secondary">Brand/Scale</th>
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
                                            <div style="width: 70px; height: 45px; background: #000; border-radius: 8px; overflow: hidden; margin-right: 1rem; border: 1px solid var(--glass-border);">
                                                <img src="{{ $item->product_image ? asset($item->product_image) : asset('images/placeholder-car.webp') }}" style="width: 100%; height: 100%; object-fit: contain;">
                                            </div>
                                            <div>
                                                <div class="text-white fw-bold small">{{ $item->product_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-white small fw-bold">{{ $item->product_brand }}</div>
                                        <div class="text-warning" style="font-size: 0.85rem; font-weight: 900;">
                                            {{ $item->product->scale->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="text-white-50 small">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-white small">{{ $item->quantity }}</td>
                                    <td class="text-end pe-4 text-warning fw-black small">₱{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-secondary p-4">
                    <div class="row align-items-end">
                        <div class="col-md-7">
                            <div class="mb-4">
                                <h6 class="text-secondary text-uppercase italic small mb-2">Acquisition Buyer</h6>
                                <div class="text-white fw-bold h5 mb-1">{{ $order->customer_name }}</div>
                                <div class="text-white-50 italic small">{{ $order->shipping_address }}</div>
                            </div>
                            <div>
                                <h6 class="text-secondary text-uppercase italic small mb-2">Special Instructions</h6>
                                <p class="text-muted italic small mb-0">"{{ $order->notes ?? 'No special instructions provided.' }}"</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="ms-auto" style="max-width: 300px;">
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
                                <!-- Grand Total -->
                                <hr class="border-secondary opacity-25 my-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-white fw-black italic">GRAND TOTAL</span>
                                    <span class="text-secondary h4 fw-black mb-0">₱{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
</div>

<!-- Status Control Floating Card (Modal) -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content glass border-warning shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-secondary border-opacity-25 p-3">
                <h6 class="modal-title text-warning text-uppercase italic fw-black mb-0">SHIPMENT <span>CONTROL</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.6rem;"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase ls-1 mb-2">Transmission Status</label>
                        <select name="status" class="form-select bg-black border-secondary text-white fw-bold py-2" style="font-size: 0.85rem;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>SHIPPED</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                        </select>
                        <div class="mt-2 text-muted italic" style="font-size: 0.6rem;">Updating this will sync the shipment timeline for the collector.</div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 py-2 fw-black text-uppercase ls-1" style="border-radius: 10px;">SET STATUS</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
