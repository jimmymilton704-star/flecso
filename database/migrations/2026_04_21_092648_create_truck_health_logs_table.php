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
        Schema::create('truck_health_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('truck_id');
            $table->decimal('current_km', 10, 2)->default(0);
            $table->decimal('engine_hours', 10, 2)->nullable();

            $table->timestamp('recorded_at')->useCurrent();

            $table->timestamps();

            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_health_logs');
    }
};
