<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_user_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->string('sender'); // WhatsApp number
            $table->boolean('ai_enabled')->default(true);
            $table->timestamps();
            
            // Unique constraint: one preference per user per device
            $table->unique(['device_id', 'sender']);
            
            // Foreign key
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_user_preferences');
    }
};
