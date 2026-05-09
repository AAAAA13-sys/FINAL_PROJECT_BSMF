<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/email-layout.css', true) }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BSMF GARAGE</h1>
            <p>ACQUISITION CONFIRMED</p>
        </div>
        <div class="content">
            <div class="order-info">
                <p>Hello <strong>{{ $order->customer_name }}</strong>,</p>
                <p>Thank you for your acquisition. We've received your order and are preparing it for processing. Below is your itemized acquisition list.</p>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}<br>
                <strong>Date:</strong> {{ $order->placed_at->format('F d, Y h:i A') }}</p>
            </div>

            <table class="item-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->product_name }}</div>
                            <div style="font-size: 12px; color: #888;">{{ $item->product_brand }}</div>
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span class="total-value">₱{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div class="total-row">
                    <span class="total-label">Discount:</span>
                    <span class="total-value">-₱{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="total-row">
                    <span class="total-label">Shipping Fee:</span>
                    <span class="total-value">₱{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">TOTAL:</span>
                    <span class="total-value">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="btn">VIEW ACQUISITION</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSMF GARAGE. All rights reserved.<br>
            Premium Die-Cast Collector Series
        </div>
    </div>
</body>
</html>
