<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 🔹 Basic Info (Signup)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            // 🔹 Role & Hierarchy
            $table->string('role')->default('admin'); // admin / driver
            $table->unsignedBigInteger('parent_id')->nullable();

            // 🔹 Profile Status
            $table->boolean('profile_completed')->default(false);

            // 🔹 Optional Basic Fields
            $table->string('avatar')->nullable();
            $table->string('company_name')->nullable(); // keep if needed

            // ================================
            // 🧾 PART 1: Company Profile
            // ================================
            $table->string('company_legal_name')->nullable();
            $table->string('company_type')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('rea_number')->nullable();

            // ================================
            // 📧 PART 2: Communication
            // ================================
            $table->string('pec_email')->nullable();
            $table->string('sdi_code')->nullable();
            $table->string('registered_address')->nullable();
            $table->string('city')->nullable();
            $table->string('province', 2)->nullable();
            $table->string('zip_code')->nullable();

            // ================================
            // 🚚 PART 3: Logistics
            // ================================
            $table->string('ren_number')->nullable();
            $table->string('eu_license_number')->nullable();
            $table->integer('fleet_trucks')->nullable();
            $table->integer('fleet_vans')->nullable();
            $table->integer('fleet_containers')->nullable();
            $table->string('insurance_policy_number')->nullable();

            // ================================
            // 👤 PART 4: Legal Representative
            // ================================
            $table->string('rep_full_name')->nullable();
            $table->string('rep_position')->nullable();
            $table->string('rep_fiscal_code')->nullable();
            $table->string('rep_document')->nullable();

            // 🔹 Laravel Defaults
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // 🔹 Foreign Key
            $table->foreign('parent_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};