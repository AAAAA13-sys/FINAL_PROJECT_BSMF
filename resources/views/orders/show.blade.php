@extends('layouts.app')

@section('title', 'Track Order - BFMSL')

@section('content')
<section class="section-padding">
    <div class="glass tracking-card fade-in">
        <h2 class="tracking-header">Track Order <span>#{{ $order->order_number }}</span></h2>
        <p class="tracking-subtitle">Placed on {{ $order->created_at->format('F d, Y') }}</p>

        @if($order->extra_packaging_requested)
            <div class="alert alert-info border-0 mb-3 text-center order-tracking-alert">
                <i class="fas fa-box me-2"></i> EXTRA CARE PACKAGING ENABLED
            </div>
        @endif

        @php
            $statuses = ['pending' => 'Order Placed', 'processing' => 'Processing', 'out_for_delivery' => 'Out for Delivery', 'delivered' => 'Delivered'];
            $statusKeys = array_keys($statuses);
            $currentIndex = array_search($order->status, $statusKeys);
            if ($currentIndex === false) $currentIndex = 0;
            $progress = (($currentIndex) / (count($statuses) - 1)) * 100;
        @endphp

        <div class="tracking-progress-container">
            <div class="tracking-line"></div>
            <div class="tracking-line-active" style="width: {{ $progress }}%;"></div>
            
            @foreach($statuses as $key => $label)
                @php $index = array_search($key, $statusKeys); @endphp
                <div class="tracking-step {{ $index <= $currentIndex ? 'tracking-step-active' : '' }}">
                    <div class="tracking-dot">
                        @if($index < $currentIndex)
                            <i class="fas fa-check"></i>
                        @elseif($index == $currentIndex)
                            <i class="fas fa-circle tracking-dot-inner"></i>
                        @endif
                    </div>
                    <p class="tracking-label">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <div class="tracking-info-grid">
            <div>
                <h4 class="order-section-title text-uppercase">SHIPPING TO</h4>
                <p class="order-shipping-address mb-1">{{ $order->shipping_address }}</p>
                <p class="text-secondary text-xs fw-black text-uppercase tracking-wider">Courier: {{ $order->courier_name ?? 'LBC Express' }}</p>
            </div>
            <div>
                <h4 class="order-section-title text-uppercase">ORDER SUMMARY</h4>
                @foreach($order->items as $item)
                    <div class="order-summary-item">
                        <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                        <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <hr class="order-summary-hr">
                <div class="order-total-row">
                    <span>TOTAL</span>
                    <span class="order-total-value">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="order-actions-footer">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-bsmf-subtle btn-track-back">BACK TO HISTORY</a>
            
            @if($order->status == \App\Models\Order::STATUS_PENDING)
                <button type="button" class="btn btn-danger btn-track-cancel" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    CANCEL ORDER
                </button>
            @endif
        </div>
    </div>
</section>

@if($order->status == \App\Models\Order::STATUS_PENDING)
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-bsmf-content">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title modal-bsmf-title">CANCEL ACQUISITION</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="modal-bsmf-body-text">Please select a reason for ejecting this acquisition from your garage.</p>
                    <div class="form-group">
                        <div class="d-flex flex-column gap-3">
                            <div class="form-check bsmf-custom-radio">
                                <input class="form-check-input" type="radio" name="reason" id="reason1" value="Changed my mind" required>
                                <label class="form-check-label" for="reason1">Changed my mind</label>
                            </div>
                            <div class="form-check bsmf-custom-radio">
                                <input class="form-check-input" type="radio" name="reason" id="reason2" value="Incorrect Address Details">
                                <label class="form-check-label" for="reason2">Incorrect Address Details (Fix Address)</label>
                            </div>
                            <div class="form-check bsmf-custom-radio">
                                <input class="form-check-input" type="radio" name="reason" id="reason3" value="Duplicate Order">
                                <label class="form-check-label" for="reason3">Duplicate Order</label>
                            </div>
                            <div class="form-check bsmf-custom-radio">
                                <input class="form-check-input" type="radio" name="reason" id="reason4" value="Found better price elsewhere">
                                <label class="form-check-label" for="reason4">Found better price elsewhere</label>
                            </div>
                            <div class="form-check bsmf-custom-radio">
                                <input class="form-check-input" type="radio" name="reason" id="reason5" value="Other">
                                <label class="form-check-label" for="reason5">Other</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-bsmf-subtle modal-bsmf-btn-cancel" data-bs-dismiss="modal">KEEP ORDER</button>
                    <button type="submit" class="btn btn-danger modal-bsmf-btn-confirm">CONFIRM CANCELLATION</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
