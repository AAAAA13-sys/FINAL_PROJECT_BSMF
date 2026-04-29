<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create Dedicated Database User (Non-Root)
        \Illuminate\Support\Facades\DB::unprepared("
            CREATE USER IF NOT EXISTS 'BSMF_User1'@'localhost' IDENTIFIED BY 'BSMF_Pass123!';
            GRANT SELECT, INSERT, UPDATE, DELETE, EXECUTE ON `final_project_bsmf`.* TO 'BSMF_User1'@'localhost';
            FLUSH PRIVILEGES;
        ");

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->string('phone', 20)->nullable();
            $table->text('default_shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
