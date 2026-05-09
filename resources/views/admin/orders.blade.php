@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="admin-header-title">ORDER <span>MANAGEMENT</span></h2>
    </div>

    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th class="ps-4">Order Number</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status Control</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="ps-4">
                        <div class="text-warning fw-black ls-1">{{ $order->order_number }}</div>
                        @if($order->extra_packaging_requested)
                            <span class="badge bg-danger mt-1" style="font-size: 0.55rem;"><i class="fas fa-box me-1"></i> EXTRA CARE</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-white fw-bold">{{ $order->customer_name }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $order->customer_email }}</div>
                    </td>
                    <td><span class="text-white-50 small">{{ $order->created_at->format('M d, Y') }}</span></td>
                    <td class="text-white fw-black">₱{{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'badge-pending',
                                'processing' => 'badge-processing',
                                'shipped' => 'badge-shipped',
                                'delivered' => 'badge-delivered',
                                'cancelled' => 'badge-cancelled',
                                default => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }} px-3 py-2 text-xs rounded-pill fw-black ls-1">
                            {{ strtoupper($order->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold" style="font-size: 0.65rem;">DETAILS</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex flex-column align-items-center">
        <div class="text-muted smaller fw-normal text-uppercase ls-2 mb-2 opacity-50">
            SHOWING {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} OF {{ $orders->total() }} ORDERS
        </div>
        <div id="pagination-container">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
