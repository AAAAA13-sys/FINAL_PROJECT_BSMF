<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert the WELCOME10 coupon
        DB::table('coupons')->insertOrIgnore([
            'code' => 'WELCOME10',
            'name' => 'First Acquisition Gift',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_order_amount' => 0.00,
            'usage_limit' => null, // Infinite global use, enforced per-user in code
            'times_used' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Remove the redundant user-specific trigger if it exists
        DB::unprepared("DROP TRIGGER IF EXISTS Trigger_AfterUserRegistration;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('coupons')->where('code', 'WELCOME10')->delete();
    }
};
