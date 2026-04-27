<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('driver_id');

            // LIVE GPS DATA
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // OPTIONAL MOVEMENT DATA
            $table->decimal('speed', 8, 2)->nullable();   // km/h
            $table->decimal('heading', 8, 2)->nullable();  // direction angle

            // LAST UPDATE TIME (important for offline detection)
            $table->timestamp('updated_at')->useCurrent();

            // RELATION
            $table->foreign('driver_id')
                ->references('id')
                ->on('drivers')
                ->onDelete('cascade');

            $table->index('driver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};