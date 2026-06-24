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
            if (!Schema::hasColumn('ai_responses', 'contact_filter_mode')) {
                $table->enum('contact_filter_mode', ['all', 'whitelist', 'blacklist'])->default('all')->after('reply_to_groups');
            }
            if (!Schema::hasColumn('ai_responses', 'contact_numbers')) {
                $table->text('contact_numbers')->nullable()->after('contact_filter_mode');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('ai_responses', 'contact_filter_mode')) {
                $cols[] = 'contact_filter_mode';
            }
            if (Schema::hasColumn('ai_responses', 'contact_numbers')) {
                $cols[] = 'contact_numbers';
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
