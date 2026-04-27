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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            // Participants
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('driver_id');

            // Last message (for listing)
            $table->text('last_message')->nullable();
            $table->timestamp('last_message_at')->nullable();

            $table->timestamps();

            // Relations
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('cascade');

            $table->unique(['admin_id', 'driver_id']); // 1 chat per pair
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
