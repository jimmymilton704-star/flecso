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
        Schema::create('trip_account_transactions', function (Blueprint $table) {
            $table->id();
             $table->foreignId('trip_account_id')
                ->constrained('trip_accounts')
                ->cascadeOnDelete();

            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();

            $table->enum('type', [
                'fuel',
                'toll',
                'maintenance',
                'food',
                'advance',
                'other'
            ])->default('other');

            $table->decimal('amount', 12, 2);
            $table->date('expense_date')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->decimal('balance_before', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_account_transactions');
    }
};
