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

        <div style="margin-top: 3rem; text-align: center;">
            <a href="{{ route('orders.index') }}" class="btn btn-primary" style="padding: 0.8rem 3rem; font-size: 0.9rem;">BACK TO HISTORY</a>
        </div>
    </div>
</section>
@endsection
