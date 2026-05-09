<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Wipe previous seeded logs for a clean slate
        AuditLog::truncate();

        $admin = User::where('role', 'admin')->first() ?: User::factory()->create(['role' => 'admin', 'username' => 'admin']);
        $staff = User::where('role', 'staff')->first() ?: User::factory()->create(['role' => 'staff', 'username' => 'staff_user']);

        // 2. Real System Activity Simulation
        $activities = [
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'model_type' => 'App\Models\Product',
                'description' => 'Updated product: STH Nissan Skyline GT-R',
                'old_values' => ['price' => 1200.00, 'stock_quantity' => 2],
                'new_values' => ['price' => 1500.00, 'stock_quantity' => 5],
                'ip_address' => '127.0.0.1'
            ],
            [
                'user_id' => $staff->id,
                'action' => 'ORDER_STATUS_UPDATE',
                'model_type' => 'App\Models\Order',
                'description' => 'Updated order #BSG-20260507-0008-891 status from processing to shipped',
                'old_values' => ['status' => 'processing'],
                'new_values' => ['status' => 'shipped'],
                'ip_address' => '110.54.23.12'
            ],
            [
                'user_id' => $admin->id,
                'action' => 'INSERT',
                'model_type' => 'App\Models\Coupon',
                'description' => 'Created coupon: RACING2026',
                'old_values' => null,
                'new_values' => ['code' => 'RACING2026', 'discount_percent' => 15, 'is_active' => 1],
                'ip_address' => '127.0.0.1'
            ],
            [
                'user_id' => $staff->id,
                'action' => 'UPDATE',
                'model_type' => 'App\Models\Product',
                'description' => 'Refilled stock for Zamac Porsche 911',
                'old_values' => ['stock_quantity' => 0],
                'new_values' => ['stock_quantity' => 12],
                'ip_address' => '110.54.23.45'
            ],
            [
                'user_id' => $admin->id,
                'action' => 'UPDATE',
                'model_type' => 'App\Models\User',
                'description' => 'Updated user permissions for staff_user',
                'old_values' => ['role' => 'staff'],
                'new_values' => ['role' => 'moderator'],
                'ip_address' => '127.0.0.1'
            ],
            [
                'user_id' => $admin->id,
                'action' => 'PAGE_VISIT',
                'model_type' => null,
                'description' => 'Visited page: admin/audit-logs',
                'old_values' => null,
                'new_values' => [],
                'ip_address' => '127.0.0.1'
            ]
        ];

        // Create 80 logs to demonstrate pagination and high integrity
        for ($i = 0; $i < 80; $i++) {
            $base = $activities[array_rand($activities)];
            AuditLog::create(array_merge($base, [
                'created_at' => Carbon::now()->subMinutes(rand(1, 43200)), // Spread over a month
            ]));
        }

        echo "Successfully injected 80 HIGH-INTEGRITY audit log entries aligned with DB schema!\n";
    }
}
