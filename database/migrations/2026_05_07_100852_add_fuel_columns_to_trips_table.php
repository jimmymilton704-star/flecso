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
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('total_fuel_cost', 10, 2)->default(0);
            $table->decimal('total_fuel_liters', 10, 2)->default(0);
            $table->decimal('fuel_cost_per_km', 10, 2)->nullable();
            $table->decimal('avg_kmpl', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            
        });
    }
};
