<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('gemini_api_key')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('reply_to_groups')->default(false);
            $table->string('model')->default('gemini-2.5-flash');
            $table->text('system_prompt')->nullable();
            $table->integer('max_tokens')->default(500);
            $table->decimal('temperature', 3, 2)->default(0.7);
            $table->json('settings')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_responses');
    }
};
