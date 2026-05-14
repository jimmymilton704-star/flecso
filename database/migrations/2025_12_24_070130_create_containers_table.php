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
       Schema::create('containers', function (Blueprint $table) {
        $table->id();

        // Admin رابطه
        $table->unsignedBigInteger('admin_id');

        // Basic Info
        $table->string('container_id');
        $table->string('container_license_number');
        $table->string('container_type');
        $table->string('status')->default('available');
        $table->decimal('weight_capacity', 10, 2)->nullable();
        $table->string('image')->nullable();

        /*
        |-----------------------------------------
        | ISO IDENTIFICATION SYSTEM
        |-----------------------------------------
        */
        $table->string('owner_code', 3)->nullable(); // 3 letters
        $table->string('category_identifier', 1)->default('U'); // usually U
        $table->string('serial_number', 6)->nullable(); // 6 digits
        $table->string('check_digit', 1)->nullable(); // 1 digit
        $table->string('iso_type_size_code', 4)->nullable(); // e.g., 42G1

        /*
        |-----------------------------------------
        | TECHNICAL & SAFETY (CSC)
        |-----------------------------------------
        */
        $table->string('manufacturer_serial_number')->nullable();
        $table->date('manufacture_date')->nullable(); // month/year
        $table->decimal('max_operating_weight', 10, 2)->nullable();
        $table->decimal('stacking_weight', 10, 2)->nullable();
        $table->date('next_examination_date')->nullable(); // NED

        /*
        |-----------------------------------------
        | CUSTOM & LOGISTICS
        |-----------------------------------------
        */
        $table->string('eori_number')->nullable();
        $table->string('seal_number')->nullable();
        $table->enum('container_status', ['empty', 'full'])->default('empty');
        $table->string('owner_lessor')->nullable();

        $table->timestamps();

        /*
        |-----------------------------------------
        | FOREIGN KEY
        |-----------------------------------------
        */
        $table->foreign('admin_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
