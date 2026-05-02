<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('trips', function (Blueprint $table) {
        $table->decimal('pickup_lat', 10, 7)->nullable();
        $table->decimal('pickup_lng', 10, 7)->nullable();

        $table->decimal('delivery_lat', 10, 7)->nullable();
        $table->decimal('delivery_lng', 10, 7)->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            //
        });
    }
};
