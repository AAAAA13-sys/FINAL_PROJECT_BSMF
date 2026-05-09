@extends('layouts.app')

@section('title', 'Your Collection History - BSMF GARAGE')

@section('content')
<section class="section-padding section-garage-view">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-5">
            <h1 class="text-white text-uppercase italic fw-black mb-0">YOUR <span>ACQUISITIONS</span></h1>
        </div>

        <div class="row g-4">
            @forelse($orders as $order)
                <div class="col-12">
                    <div class="order-history-card">
                        <div class="order-history-header">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                                <div class="d-flex gap-5">
                                    <div>
                                        <small class="order-meta-label">Order Placed</small>
                                        <span class="order-meta-value">{{ $order->placed_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <small class="order-meta-label">Total Value</small>
                                        <span class="order-meta-value">₱{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <small class="order-meta-label">Collector</small>
                                        <span class="order-meta-value">{{ $order->customer_name }}</span>
                                    </div>
                                </div>
                                <div class="text-md-end">
                                    <small class="order-meta-label">Garage # {{ $order->order_number }}</small>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn-link-bsmf-subtle">VIEW DETAILS</a>
                                </div>
                            </div>
                        </div>
                        <div class="order-history-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex flex-column gap-4">
                                        @foreach($order->items as $item)
                                            <div class="d-flex align-items-center gap-4">
                                                <div class="order-item-img-box">
                                                    <img src="{{ $item->product_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid h-100" alt="Model">
                                                </div>
                                                <div>
                                                    <div class="order-item-name">{{ $item->product_name }}</div>
                                                    <div class="order-item-qty">Quantity: {{ $item->quantity }}</div>
                                                    @if($order->status == 'delivered')
                                                        <a href="{{ route('products.show', $item->product_id) }}#review-section" class="btn btn-link p-0 text-warning text-uppercase italic fw-bold mt-2" style="font-size: 0.65rem; text-decoration: none;">
                                                            <i class="fas fa-star me-1"></i> REVIEW PIECE
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-4">
                                        <span class="order-status-label">Current Status: </span>
                                        <span class="order-status-value" style="color: {{ $order->status == 'delivered' ? 'var(--color-forest-emerald)' : 'var(--color-warm-bronze)' }};">{{ $order->status }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                                    @if($order->status == 'delivered')
                                        <a href="{{ $order->items->first() ? route('products.show', $order->items->first()->product_id) . '#review-section' : '#' }}" class="btn btn-primary btn-bsmf-action">
                                            <i class="fas fa-star me-2"></i> LEAVE A REVIEW
                                        </a>
                                    @else
                                        @if($order->tracking_link)
                                            <a href="{{ $order->tracking_link }}" target="_blank" class="btn btn-primary btn-bsmf-action">TRACK SHIPMENT</a>
                                        @else
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-bsmf-action">VIEW DETAILS</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center empty-history-container">
                    <i class="fas fa-history empty-history-icon"></i>
                    <h2 class="text-white mb-2 empty-history-title">No History Found</h2>
                    <p class="empty-history-text">You haven't added any legendary cars to your collection yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-bsmf-rounded-pill">DISCOVER MODELS</a>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</section>
@endsection
