@extends('layouts.app')

@section('title', 'Order History - BFMSL')

@section('content')
<section class="section-padding">
    <div class="section-header">
        <h2 class="section-title">Order <span>History</span></h2>
    </div>

    <div class="orders-list">
        @forelse($orders as $order)
            <div class="glass order-item-card fade-in">
                <div>
                    <p class="order-info-label">ORDER ID</p>
                    <p class="order-info-value">#BFMSL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="order-info-label">DATE</p>
                    <p class="order-info-value">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="order-info-label">TOTAL</p>
                    <p class="order-info-value">${{ number_format($order->total_price, 2) }}</p>
                </div>
                <div>
                    <span class="status-badge">{{ $order->status }}</span>
                </div>
                <div>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">TRACK</a>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 5rem 0;">
                <p style="font-size: 1.5rem; color: var(--text-muted);">You haven't placed any orders yet.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
