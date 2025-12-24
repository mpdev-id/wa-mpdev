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
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->enum('contact_filter_mode', ['all', 'whitelist', 'blacklist'])->default('all')->after('reply_to_groups');
            $table->text('contact_numbers')->nullable()->after('contact_filter_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->dropColumn(['contact_filter_mode', 'contact_numbers']);
        });
    }
};
