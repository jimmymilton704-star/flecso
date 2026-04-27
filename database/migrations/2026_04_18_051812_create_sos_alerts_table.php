<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sos_alerts', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('trip_id');

            // Emergency Info
            $table->string('emergency_type');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();

            // Location
            $table->string('location')->nullable();

            // Status
            $table->enum('status', ['pending', 'resolved'])->default('pending');

            $table->timestamps();

            /*
            |-----------------------------------------
            | FOREIGN KEYS
            |-----------------------------------------
            */
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sos_alerts');
    }
};