@extends('layouts.admin')

@section('content')
<div class="fade-in">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-size: 2rem; color: white; text-transform: uppercase; font-style: italic; font-weight: 900;">ORDER <span style="color: var(--secondary);">MANAGEMENT</span></h2>
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
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            <select name="status" class="form-select form-select-sm bg-dark border-secondary text-white w-auto" style="font-size: 0.7rem; font-weight: 700;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                <option value="out_for_delivery" {{ $order->status == 'out_for_delivery' ? 'selected' : '' }}>SHIPPED</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>DELIVERED</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>CANCELLED</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-warning py-1 px-3 fw-bold" style="font-size: 0.6rem;">SET</button>
                        </form>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold" style="font-size: 0.65rem;">DETAILS</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($orders->hasPages())
            <div class="p-4 border-top border-secondary bg-darker bg-opacity-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
