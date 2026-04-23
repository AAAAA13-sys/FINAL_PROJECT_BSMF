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
        Schema::create('reviews', function (Blueprint $刻) {
            $刻->id();
            $刻->foreignId('product_id')->constrained()->onDelete('cascade');
            $刻->foreignId('user_id')->constrained()->onDelete('cascade');
            $刻->integer('rating');
            $刻->text('comment');
            $刻->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
