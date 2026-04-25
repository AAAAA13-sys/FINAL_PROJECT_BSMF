@extends('layouts.app')

@section('title', 'Your Collection History - BSMF Garage')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center gap-3 mb-5">
        <h1 class="text-white text-uppercase italic fw-black mb-0">ORDER <span>HISTORY</span></h1>
    </div>

    <div class="row g-4">
        @forelse($orders as $order)
            <div class="col-12">
                <div class="card bg-dark border-secondary rounded-4 overflow-hidden">
                    <div class="card-header bg-black border-secondary p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex gap-4">
                                <div>
                                    <small class="text-muted text-uppercase d-block mb-1">Order Placed</small>
                                    <span class="text-white small fw-bold">{{ $order->placed_at->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase d-block mb-1">Total Amount</small>
                                    <span class="text-white small fw-bold">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div>
                                    <small class="text-muted text-uppercase d-block mb-1">Ship To</small>
                                    <span class="text-white small fw-bold">{{ $order->customer_name }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted text-uppercase d-block mb-1">Order # {{ $order->order_number }}</small>
                                <a href="{{ route('orders.show', $order->id) }}" class="text-warning small text-decoration-none fw-bold">VIEW DETAILS</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex gap-2 overflow-auto pb-2">
                                    @foreach($order->items as $item)
                                        <div class="bg-black p-1 rounded" style="width: 80px; height: 60px; flex-shrink: 0;" title="{{ $item->product_name }}">
                                            <img src="{{ $item->product_image ?? asset('images/placeholder-car.webp') }}" class="w-100 h-100 object-fit-contain" alt="Car Thumbnail">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-white mt-3 mb-0 fw-bold">
                                    Status: <span class="text-warning">{{ strtoupper($order->tracking_status) }}</span>
                                </p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-warning px-4">TRACK PACKAGE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 glass border-secondary rounded-4">
                <i class="fas fa-history text-secondary display-1 mb-4 opacity-25"></i>
                <h3 class="text-white">NO ORDERS FOUND</h3>
                <p class="text-muted mb-4">You haven't added any legendary cars to your collection yet.</p>
                <a href="{{ route('products.index') }}" class="btn btn-warning px-5 py-3 fw-bold">BROWSE CATALOG</a>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
