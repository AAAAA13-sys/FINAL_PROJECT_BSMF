<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresentationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'john@example.com')->first();
        if (!$user) {
            return;
        }

        $products = Product::limit(5)->get();

        // Order 1: Processing
        $order1 = Order::create([
            'order_number' => 'BSMF-' . date('ymd') . $user->id . '001',
            'user_id' => $user->id,
            'status' => 'processing',
            'subtotal' => 2500.00,
            'total_amount' => 2500.00,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'shipping_address' => '123 Collector Lane, Diecast City',
            'payment_method' => 'Cash on Delivery',
            'placed_at' => Carbon::now()->subDays(2),
            'processed_at' => Carbon::now()->subDay(),
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[0]->id,
            'product_name' => $products[0]->name,
            'quantity' => 1,
            'price' => 2500.00,
            'total' => 2500.00,
        ]);

        // Order 2: Shipped
        $order2 = Order::create([
            'order_number' => 'BSMF-' . date('ymd') . $user->id . '002',
            'user_id' => $user->id,
            'status' => 'shipped',
            'subtotal' => 1850.00,
            'total_amount' => 1850.00,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'shipping_address' => '123 Collector Lane, Diecast City',
            'payment_method' => 'G-Cash',
            'placed_at' => Carbon::now()->subDays(3),
            'processed_at' => Carbon::now()->subDays(2),
            'shipped_at' => Carbon::now()->subHours(5),
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $products[1]->id,
            'product_name' => $products[1]->name,
            'quantity' => 1,
            'price' => 1850.00,
            'total' => 1850.00,
        ]);

        // Order 3: Delivered
        $order3 = Order::create([
            'order_number' => 'BSMF-' . date('ymd') . $user->id . '003',
            'user_id' => $user->id,
            'status' => 'delivered',
            'subtotal' => 4200.00,
            'total_amount' => 4200.00,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'shipping_address' => '123 Collector Lane, Diecast City',
            'payment_method' => 'Credit Card',
            'placed_at' => Carbon::now()->subDays(5),
            'processed_at' => Carbon::now()->subDays(4),
            'shipped_at' => Carbon::now()->subDays(1),
            'delivered_at' => Carbon::now()->subHours(2),
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[2]->id,
            'product_name' => $products[2]->name,
            'quantity' => 2,
            'price' => 2100.00,
            'total' => 4200.00,
        ]);

        // Seed Audit Logs
        $staff = User::where('role', 'staff')->first();
        $admin = User::where('role', 'admin')->first();

        DB::table('audit_logs')->insert([
            [
                'user_id' => $staff->id,
                'action' => 'ORDER_STATUS_UPDATE',
                'description' => "Order {$order3->order_number} marked as DELIVERED by {$staff->name}",
                'model_type' => 'Order',
                'model_id' => $order3->id,
                'old_values' => json_encode(['status' => 'shipped']),
                'new_values' => json_encode(['status' => 'delivered']),
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            [
                'user_id' => $staff->id,
                'action' => 'ORDER_STATUS_UPDATE',
                'description' => "Order {$order2->order_number} marked as SHIPPED by {$staff->name}",
                'model_type' => 'Order',
                'model_id' => $order2->id,
                'old_values' => json_encode(['status' => 'processing']),
                'new_values' => json_encode(['status' => 'shipped']),
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'STOCK_RESTOCK',
                'description' => "Restocked 5 units of {$products[0]->name}",
                'model_type' => 'Product',
                'model_id' => $products[0]->id,
                'old_values' => json_encode(['stock_quantity' => 0]),
                'new_values' => json_encode(['stock_quantity' => 5]),
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'user_id' => $admin->id,
                'action' => 'PRICE_ADJUSTMENT',
                'description' => "Adjusted price for {$products[1]->name} from ₱1500 to ₱1850",
                'model_type' => 'Product',
                'model_id' => $products[1]->id,
                'old_values' => json_encode(['price' => 1500.00]),
                'new_values' => json_encode(['price' => 1850.00]),
                'ip_address' => '127.0.0.1',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ]);
    }
}
