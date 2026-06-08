<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add webhook_processed flag to orders table.
     *
     * Purpose: Idempotency guard to prevent double-activation via replay attacks.
     * When the QRIS webhook fires, it sets this to 1 atomically (with lockForUpdate)
     * before activating the subscription. Subsequent identical webhook calls will
     * find no matching order (status=2 AND webhook_processed=0) and do nothing.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Default 0 = not yet processed by webhook
            // Set to 1 the moment webhook claims this order
            $table->tinyInteger('webhook_processed')->default(0)->after('status')
                  ->comment('1 = activated via QRIS Android webhook (idempotency guard)');

            // Index for fast lookup in the webhook hot path
            $table->index(['status', 'amount', 'webhook_processed'], 'orders_qris_match_idx');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_qris_match_idx');
            $table->dropColumn('webhook_processed');
        });
    }
};
