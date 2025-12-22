<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->string('ai_type')->default('default')->after('device_id');
        });
    }

    public function down(): void
    {
        Schema::table('ai_responses', function (Blueprint $table) {
            $table->dropColumn('ai_type');
        });
    }
};
