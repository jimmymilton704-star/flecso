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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('chat_id');

            // Who sent
            $table->enum('sender_type', ['admin', 'driver']);
            $table->unsignedBigInteger('sender_id');

            // Message
            $table->text('message')->nullable();

            // File support
            $table->string('file')->nullable(); // image, pdf, etc
            $table->string('file_type')->nullable(); // image, video, doc

            // Seen / read
            $table->boolean('is_seen')->default(false);

            $table->timestamps();

            // Relation
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
