<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();

            // 🔗 Ownership
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->integer('driver_id')->nullable();
            // =========================
            // BASIC TRUCK INFO
            // =========================
            $table->string('truck_number');
            $table->string('truck_license_number');
            $table->float('capacity_tons')->nullable();
            $table->string('truck_type_category')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('available');
            $table->string('image')->nullable();

            // =========================
            // 1. CORE IDENTITY & LEGAL DOCUMENTS
            // =========================
            $table->string('license_plate_number'); // Targa
            $table->string('vin_number')->nullable(); // Telaio (E)
            $table->date('first_registration_date')->nullable(); // (B)
            $table->string('usage_type')->nullable(); // Conto Terzi / Conto Proprio
            $table->string('documento_unico')->nullable(); // DU upload

            // =========================
            // 2. TECHNICAL SPECIFICATIONS
            // =========================
            $table->string('vehicle_category')->nullable(); // N1/N2/N3
            $table->integer('gvw_kg')->nullable(); // F.2
            $table->integer('payload_capacity_kg')->nullable(); // (F.2 - G)
            $table->integer('number_of_axles')->nullable(); // L
            $table->string('engine_class')->nullable(); // V.9 Euro class
            $table->string('fuel_type')->nullable();

            // =========================
            // 3. COMPLIANCE & SAFETY DEADLINES
            // =========================
            $table->date('next_inspection_date')->nullable(); // Revisione
            $table->string('insurance_policy_number')->nullable(); // RCA
            $table->date('insurance_expiry_date')->nullable();
            $table->date('tachograph_calibration_expiry')->nullable();
            $table->date('bollo_expiry_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};