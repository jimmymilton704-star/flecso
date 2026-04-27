<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // basic, premium
            $table->string('slug')->unique(); // basic, premium

            $table->decimal('price', 10, 2)->default(0);

            // IMPORTANT:
            // null = unlimited drivers
            $table->integer('driver_limit')->nullable();

            // Stripe integration
            $table->string('stripe_price_id')->nullable();
            $table->string('stripe_product_id')->nullable();

            // Plan status
            $table->boolean('is_active')->default(true);

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};