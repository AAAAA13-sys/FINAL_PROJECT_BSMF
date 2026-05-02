<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::unprepared("
            ALTER TABLE products 
            ADD CONSTRAINT check_price_non_negative CHECK (price >= 0);
        ");

        DB::unprepared("
            ALTER TABLE products 
            ADD CONSTRAINT check_stock_non_negative CHECK (stock_quantity >= 0);
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("ALTER TABLE products DROP CONSTRAINT check_price_non_negative;");
        DB::unprepared("ALTER TABLE products DROP CONSTRAINT check_stock_non_negative;");
    }
};
