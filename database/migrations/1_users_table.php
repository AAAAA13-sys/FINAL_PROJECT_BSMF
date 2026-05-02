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
        // Schema creation starts here
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username', 50)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer');
            $table->string('phone', 20)->nullable();
            $table->text('default_shipping_address')->nullable();
            $table->timestamps();
        });

        // Seed initial users
        \Illuminate\Support\Facades\DB::table('users')->insert([
            [
                'name' => 'BSMF Admin',
                'username' => 'admin',
                'email' => 'admin@bsmfgarage.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Garage Staff',
                'username' => 'staff',
                'email' => 'staff@bsmfgarage.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'John Collector',
                'username' => 'john',
                'email' => 'john@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
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
