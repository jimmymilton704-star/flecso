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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            // Admin
            $table->unsignedBigInteger('admin_id');

            /*
            |-----------------------------------------
            | BASIC TRIP INFO
            |-----------------------------------------
            */
            $table->string('trip_id');
            $table->string('trip_type');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->unsignedBigInteger('container_id')->nullable();

            $table->string('pickup_location');
            $table->string('delivery_location');

            $table->decimal('distance_km', 10, 2)->nullable();
            $table->integer('eta_mins')->nullable();

            $table->dateTime('schedule_datetime')->nullable();

            $table->decimal('payment_amount', 10, 2)->nullable();

            $table->string('trip_status')->default('pending');

            /*
            |-----------------------------------------
            | DELIVERY CONTACT INFO
            |-----------------------------------------
            */
            $table->string('delivery_name')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->string('delivery_email')->nullable();

            /*
            |-----------------------------------------
            | PACKAGE DETAILS
            |-----------------------------------------
            */
            $table->text('package_description')->nullable();
            $table->decimal('package_weight', 10, 2)->nullable();
            $table->decimal('package_height', 10, 2)->nullable();
            $table->decimal('package_length', 10, 2)->nullable();
            $table->decimal('package_width', 10, 2)->nullable();

            $table->boolean('truck_verified')->default(false);
            $table->boolean('container_verified')->default(false);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
