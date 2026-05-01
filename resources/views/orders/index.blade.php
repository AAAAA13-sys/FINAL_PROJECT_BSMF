@extends('layouts.app')

@section('title', 'Your Collection History - BSMF GARAGE')

@section('content')
<section class="section-padding" style="background: var(--bg-darker); min-height: 100vh; padding-top: 4rem;">
    <div class="container">
        <div class="d-flex align-items-center gap-3 mb-5">
            <h1 class="text-white text-uppercase italic fw-black mb-0">ORDER <span>HISTORY</span></h1>
        </div>

        <div class="row g-4">
            @forelse($orders as $order)
                <div class="col-12">
                    <div style="background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 24px; overflow: hidden; box-shadow: var(--surface-raised);">
                        <div style="background: var(--bg-darker); border-bottom: 1px solid var(--glass-border); padding: 1.5rem 2.5rem;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                                <div class="d-flex gap-5">
                                    <div>
                                        <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; display: block; margin-bottom: 0.5rem;">Order Placed</small>
                                        <span style="color: white; font-weight: 700; font-size: 0.9rem;">{{ $order->placed_at->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; display: block; margin-bottom: 0.5rem;">Total Value</small>
                                        <span style="color: white; font-weight: 700; font-size: 0.9rem;">₱{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div>
                                        <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; display: block; margin-bottom: 0.5rem;">Collector</small>
                                        <span style="color: white; font-weight: 700; font-size: 0.9rem;">{{ $order->customer_name }}</span>
                                    </div>
                                </div>
                                <div class="text-md-end">
                                    <small style="color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 800; font-size: 0.65rem; display: block; margin-bottom: 0.5rem;">Archive # {{ $order->order_number }}</small>
                                    <a href="{{ route('orders.show', $order->id) }}" style="color: var(--secondary); text-decoration: none; font-weight: 800; font-size: 0.75rem; letter-spacing: 1px;">VIEW DOSSIER</a>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 2.5rem;">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex flex-column gap-4">
                                        @foreach($order->items as $item)
                                            <div class="d-flex align-items-center gap-4">
                                                <div style="background: var(--bg-darker); border: 1px solid var(--glass-border); border-radius: 12px; padding: 8px; width: 70px; height: 50px; display: flex; align-items: center; justify-content: center; box-shadow: var(--surface-inset);">
                                                    <img src="{{ $item->product_image ?? asset('images/placeholder-car.webp') }}" class="img-fluid" alt="Model" style="max-height: 100%;">
                                                </div>
                                                <div>
                                                    <div style="color: white; font-weight: 800; font-size: 1rem;">{{ $item->product_name }}</div>
                                                    <div style="color: var(--text-muted); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Quantity: {{ $item->quantity }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div style="margin-top: 2rem;">
                                        <span style="color: var(--text-muted); font-weight: 900; text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem;">Current Status: </span>
                                        <span style="color: {{ $order->status == 'delivered' ? '#22c55e' : '#fbbf24' }}; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; font-size: 0.75rem;">{{ $order->status }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                                    @if($order->status == 'delivered')
                                        <button class="btn" style="border: 1px solid #22c55e; color: #22c55e; padding: 0.8rem 2rem; border-radius: 12px; font-weight: 800; font-size: 0.8rem; pointer-events: none;"><i class="fas fa-check-double me-2"></i> ARCHIVED</button>
                                    @else
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" style="padding: 0.8rem 2.5rem; border-radius: 12px; font-weight: 800; font-size: 0.8rem; letter-spacing: 1px;">TRACK SHIPMENT</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center" style="padding: 8rem 2rem; background: var(--bg-dark); border: 1px solid var(--glass-border); border-radius: 40px; box-shadow: var(--surface-inset);">
                    <i class="fas fa-history" style="font-size: 5rem; color: var(--secondary); margin-bottom: 2.5rem; opacity: 0.1;"></i>
                    <h2 class="text-white mb-2" style="font-weight: 900; text-transform: uppercase; font-style: italic;">No History Found</h2>
                    <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 3rem; font-weight: 500;">You haven't added any legendary cars to your collection yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary" style="padding: 1rem 4rem; border-radius: 50px; font-weight: 900; letter-spacing: 2px;">DISCOVER MODELS</a>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</section>
@endsection
