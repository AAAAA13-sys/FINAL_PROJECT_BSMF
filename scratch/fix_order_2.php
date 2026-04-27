<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$order = Order::where('order_number', 'BSG-20260427-00002')->first();
if ($order) {
    $order->update([
        'subtotal' => 170.00,
        'shipping_fee' => 0.00,
        'total_amount' => 170.00
    ]);
    echo "Order #2 Fixed Successfully\n";
} else {
    echo "Order #2 Not Found\n";
}
