<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Owner (admin)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Plan
            $table->foreignId('plan_id')
                ->nullable()
                ->constrained('plans')
                ->nullOnDelete();

            // Stripe IDs
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_price_id')->nullable();

            // Subscription status
            $table->string('status')->default('trial');
            // trial | active | canceled | expired

            // Trial system
            $table->timestamp('trial_ends_at')->nullable();

            // Billing period
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();

            // Cancellation
            $table->timestamp('canceled_at')->nullable();

            // Extra billing (future: extra drivers, addons)
            $table->integer('extra_drivers')->default(0);
            $table->decimal('extra_cost', 10, 2)->default(0);

            $table->timestamps();

            // Indexing (important for speed)
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};