<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a1a; color: #ffffff; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; letter-spacing: 2px; }
        .content { padding: 30px; }
        .order-info { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .item-table th { text-align: left; border-bottom: 2px solid #eee; padding: 10px; font-size: 14px; text-transform: uppercase; color: #888; }
        .item-table td { padding: 15px 10px; border-bottom: 1px solid #eee; }
        .item-name { font-weight: bold; color: #1a1a1a; }
        .total-section { text-align: right; }
        .total-row { display: flex; justify-content: flex-end; margin-bottom: 10px; }
        .total-label { width: 150px; color: #888; }
        .total-value { width: 100px; font-weight: bold; }
        .grand-total { font-size: 20px; color: #e63946; margin-top: 10px; }
        .footer { background: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #888; }
        .btn { display: inline-block; padding: 12px 25px; background: #e63946; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
    </style>
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
                <p>Thank you for your acquisition. We've received your order and are preparing it for shipment. Below is your itemized acquisition list.</p>
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
                    <span class="total-label">Shipping:</span>
                    <span class="total-value">₱{{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="total-row grand-total">
                    <span class="total-label">TOTAL:</span>
                    <span class="total-value">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('orders.show', $order->id) }}" class="btn">TRACK YOUR SHIPMENT</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} BSMF GARAGE. All rights reserved.<br>
            Premium Die-Cast Collector Series
        </div>
    </div>
</body>
</html>
