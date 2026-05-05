@extends('layouts.app')

@section('title', 'Track Order - BFMSL')

@section('content')
<section class="section-padding">
    <div class="glass tracking-card fade-in">
        <h2 class="tracking-header">Track Order <span>#{{ $order->order_number }}</span></h2>
        <p class="tracking-subtitle">Placed on {{ $order->created_at->format('F d, Y') }}</p>

        @if($order->extra_packaging_requested)
            <div class="alert alert-info border-0 mb-3 text-center" style="background: rgba(251, 191, 36, 0.1); color: var(--secondary); border-radius: 10px; font-weight: 700; font-size: 0.75rem; letter-spacing: 1px; padding: 0.5rem;">
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
                <div class="tracking-step">
                    <div class="tracking-dot" style="background: {{ $index <= $currentIndex ? 'var(--primary)' : 'var(--glass-border)' }}; border: 2px solid {{ $index <= $currentIndex ? 'var(--secondary)' : 'transparent' }};">
                        @if($index < $currentIndex)
                            <i class="fas fa-check"></i>
                        @elseif($index == $currentIndex)
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                        @endif
                    </div>
                    <p class="tracking-label" style="color: {{ $index <= $currentIndex ? 'white' : 'var(--text-muted)' }}">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <div class="tracking-info-grid">
            <div>
                <h4 style="color: var(--secondary); margin-bottom: 0.75rem; font-size: 0.9rem;">SHIPPING TO</h4>
                <p style="white-space: pre-line; font-size: 0.85rem; color: var(--text-muted);">{{ $order->shipping_address }}</p>
            </div>
            <div>
                <h4 style="color: var(--secondary); margin-bottom: 0.75rem; font-size: 0.9rem;">ORDER SUMMARY</h4>
                @foreach($order->items as $item)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.3rem; font-size: 0.85rem;">
                        <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                        <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                @endforeach
                <hr style="border: none; border-top: 1px solid var(--glass-border); margin: 1rem 0;">
                <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 1rem;">
                    <span>TOTAL</span>
                    <span style="color: var(--secondary);">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem; text-align: center; display: flex; justify-content: center; gap: 1rem;">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" style="padding: 0.8rem 3rem; font-size: 0.9rem; border-radius: 12px; font-weight: 700;">BACK TO HISTORY</a>
            
            @if($order->status == \App\Models\Order::STATUS_PENDING)
                <button type="button" class="btn btn-danger" style="padding: 0.8rem 3rem; font-size: 0.9rem; border-radius: 12px; font-weight: 700;" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                    CANCEL ORDER
                </button>
            @endif
        </div>
    </div>
</section>

@if($order->status == \App\Models\Order::STATUS_PENDING)
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 24px; overflow: hidden;">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title text-white fw-black italic">CANCEL ACQUISITION</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Please provide a reason for cancelling this acquisition. The items will be returned to the general stock.</p>
                    <div class="form-group">
                        <label for="cancellation_reason" style="color: white; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem; display: block;">Cancellation Reason</label>
                        <select name="cancellation_reason" id="cancellation_reason" class="form-select filter-input" required style="background: var(--bg-darker); color: white; border: 1px solid var(--glass-border); border-radius: 12px; padding: 0.8rem;">
                            <option value="" disabled selected>Select a reason...</option>
                            <option value="Changed my mind">Changed my mind</option>
                            <option value="Found a better price elsewhere">Found a better price elsewhere</option>
                            <option value="Duplicate order">Duplicate order</option>
                            <option value="Shipping time too long">Shipping time too long</option>
                            <option value="Financial reasons">Financial reasons</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 700;">KEEP ORDER</button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 10px; font-weight: 700;">CONFIRM CANCELLATION</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .btn-outline-secondary {
        border-color: var(--glass-border);
        color: var(--text-muted);
    }
    .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: white;
        color: white;
    }
</style>
@endsection
