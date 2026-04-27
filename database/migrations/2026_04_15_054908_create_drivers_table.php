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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            // Admin relation
            $table->unsignedBigInteger('admin_id');

            /*
            |-----------------------------------------
            | DRIVER PHOTO
            |-----------------------------------------
            */
            $table->string('driver_photo')->nullable();

            /*
            |-----------------------------------------
            | PERSONAL INFORMATION
            |-----------------------------------------
            */
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');

            /*
            |-----------------------------------------
            | LICENSE INFORMATION
            |-----------------------------------------
            */
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('status')->default('active');

            /*
            |-----------------------------------------
            | ITALIAN LEGAL INFO
            |-----------------------------------------
            */
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('fiscal_code', 16)->nullable();
            $table->string('residential_address')->nullable();
            $table->string('nationality')->nullable();

            // Work Permit
            $table->string('work_permit_number')->nullable();
            $table->date('work_permit_expiry')->nullable();

            // Medical
            $table->date('medical_fitness_date')->nullable();

            // Criminal Record
            $table->string('criminal_record_check')->nullable();

            /*
            |-----------------------------------------
            | PROFESSIONAL LICENSING
            |-----------------------------------------
            */
            $table->string('driving_license_category')->nullable(); // C, C+E, D
            $table->string('cqc_number')->nullable();
            $table->date('cqc_expiry')->nullable();
            $table->string('tachograph_card_number')->nullable();

            /*
            |-----------------------------------------
            | DOCUMENT UPLOADS
            |-----------------------------------------
            */
            $table->string('license_front')->nullable();
            $table->string('license_back')->nullable();
            $table->string('cqc_card')->nullable();
            $table->string('work_permit_file')->nullable();
            $table->string('medical_certificate')->nullable();

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
        Schema::dropIfExists('drivers');
    }
};
