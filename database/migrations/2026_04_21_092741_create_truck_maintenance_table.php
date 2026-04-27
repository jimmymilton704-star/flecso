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
        Schema::create('truck_maintenance', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('truck_id');

            $table->string('type'); // oil_change, service
            $table->decimal('last_service_km', 10, 2)->nullable();
            $table->decimal('next_due_km', 10, 2)->nullable();

            $table->date('scheduled_date')->nullable();
            $table->date('completed_date')->nullable();

            $table->string('status')->default('pending'); 
            // pending | scheduled | completed

            $table->timestamps();

            $table->foreign('truck_id')->references('id')->on('trucks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_maintenance');
    }
};
