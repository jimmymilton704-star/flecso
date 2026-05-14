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
        Schema::table('trip_account_transactions', function (Blueprint $table) {
            $table->string('source_type')->nullable()->after('description');
            $table->string('source_name')->nullable()->after('source_type');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_account_transactions', function (Blueprint $table) {
            $table->dropColumn(['source_type', 'source_name', 'source_id']);
        });
    }
};
