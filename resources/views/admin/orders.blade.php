@extends('layouts.admin')

@section('content')
<h1 style="margin-bottom: 2rem;">Order Management</h1>

<div class="glass" style="padding: 1.5rem; overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 1rem;">Order ID</th>
                <th style="padding: 1rem;">Customer</th>
                <th style="padding: 1rem;">Date</th>
                <th style="padding: 1rem;">Total</th>
                <th style="padding: 1rem;">Status</th>
                <th style="padding: 1rem;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr style="border-bottom: 1px solid var(--glass-border);">
                <td style="padding: 1rem;">#BFMSL-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td style="padding: 1rem;">{{ $order->user->name }}<br><small style="color: var(--text-muted)">{{ $order->user->email }}</small></td>
                <td style="padding: 1rem;">{{ $order->created_at->format('M d, Y') }}</td>
                <td style="padding: 1rem; color: var(--accent); font-weight: 800;">${{ number_format($order->total_price, 2) }}</td>
                <td style="padding: 1rem;">
                    @php
                        $color = match($order->status) {
                            'Order Placed' => '#60a5fa',
                            'Processing' => '#fbbf24',
                            'Out for Delivery' => '#ec4899',
                            'Delivered' => '#10b981',
                            default => 'var(--primary)'
                        };
                    @endphp
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display: flex; gap: 0.5rem;">
                        @csrf
                        <select name="status" class="form-control" style="background: rgba(0,0,0,0.3); color: {{ $color }}; border-color: {{ $color }}; font-size: 0.8rem; padding: 0.2rem; width: auto; font-weight: 800;">
                            <option value="Order Placed" {{ $order->status == 'Order Placed' ? 'selected' : '' }}>Order Placed</option>
                            <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                            <option value="Out for Delivery" {{ $order->status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                            <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="font-size: 0.6rem; padding: 0.2rem 0.5rem; background: {{ $color }}; border-color: {{ $color }};">Update</button>
                    </form>
                </td>
                <td style="padding: 1rem;">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary" style="font-size: 0.7rem; padding: 0.4rem 0.8rem;">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
