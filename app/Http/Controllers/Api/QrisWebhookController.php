<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\Order;
use App\Models\User;
use App\Models\Plan;
use App\Traits\Notifications;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QrisWebhookController extends Controller
{
    use Notifications;

    /**
     * Handle incoming QRIS payment notification from Android device.
     *
     * Security measures applied:
     *  - Token only accepted via X-Auth-Token header (not body/query)
     *  - Constant-time comparison with hash_equals() to prevent timing attacks
     *  - Token loaded from config() not env() to respect config cache
     *  - DB row-level lock (lockForUpdate) to prevent race conditions
     *  - Idempotency guard: webhook_processed flag prevents double-activation
     *  - No sensitive data returned in response body
     *  - Stack traces never exposed publicly
     *  - All rejections logged with IP for intrusion detection
     */
    public function handle(Request $request)
    {
        // ── 1. Token Authentication ────────────────────────────────────────
        // Read ONLY from X-Auth-Token header. Never from body or query string
        // to prevent token leakage in server access logs.
        $receivedToken = $request->header('X-Auth-Token', '');

        // Load from config() — safe after php artisan config:cache.
        // Falls back to Option model (DB-stored token managed via admin panel).
        $configToken = config('services.qris_android_token')
            ?: (Option::where('key', 'qris_android_token')->value('value') ?? '');

        // Empty token means system is not configured — deny all.
        if (empty($configToken)) {
            Log::error('QRIS Webhook: Token not configured. Set ANDROID_QRIS_TOKEN in .env or via admin panel.', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Service unavailable'], 503);
        }

        // Constant-time comparison prevents timing side-channel attacks.
        if (empty($receivedToken) || !hash_equals($configToken, $receivedToken)) {
            Log::warning('QRIS Webhook: Unauthorized access attempt', [
                'ip'             => $request->ip(),
                'user_agent'     => $request->userAgent(),
                'has_token'      => !empty($receivedToken),
            ]);
            // Return 404 instead of 401 to avoid revealing endpoint existence to attackers.
            return response()->json(['message' => 'Not found'], 404);
        }

        // ── 2. Ping heartbeat — update last-seen timestamp ─────────────────
        Cache::put('qris_android_last_ping', now(), now()->addMinutes(10));

        // ── 3. Input Validation ────────────────────────────────────────────
        $amount = $request->input('amount');
        $text   = $request->input('description')
            ?? $request->input('message')
            ?? $request->input('text')
            ?? $request->input('body');

        // Try to parse amount from raw notification text if not provided directly.
        if (empty($amount) && !empty($text)) {
            $amount = $this->parseAmountFromText($text);
        }

        $amount = is_numeric($amount) ? (float) $amount : null;

        if (empty($amount) || $amount <= 0) {
            Log::info('QRIS Webhook: Rejected — invalid or missing amount', [
                'ip'   => $request->ip(),
                'text' => substr((string) $text, 0, 200),  // truncated for log safety
            ]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Enforce maximum amount sanity check (prevents absurdly large amounts).
        if ($amount > 99_999_999) {
            Log::warning('QRIS Webhook: Rejected — amount exceeds sanity limit', [
                'ip'     => $request->ip(),
                'amount' => $amount,
            ]);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // ── 4. Match & Activate Order (with DB lock + idempotency guard) ───
        DB::beginTransaction();
        try {
            // lockForUpdate() acquires a row-level write lock, preventing
            // concurrent requests from matching the same order simultaneously
            // (race condition / double-activation prevention).
            $order = Order::where('status', 2)           // status 2 = pending
                          ->where('amount', $amount)
                          ->where('webhook_processed', 0) // idempotency guard
                          ->lockForUpdate()
                          ->with('user', 'plan')
                          ->first();

            if (!$order) {
                DB::rollBack();
                // Generic response — do not reveal whether order exists or not.
                Log::info('QRIS Webhook: No matching unprocessed pending order', [
                    'amount' => $amount,
                ]);
                return response()->json(['message' => 'Acknowledged'], 200);
            }

            // Guard: mark as webhook-processed BEFORE activating subscription
            // to prevent any concurrent request from processing it again.
            $order->webhook_processed = 1;
            $order->status            = 1; // Complete / Paid
            $order->save();

            // 5. Activate user subscription
            $user = $order->user;
            $plan = $order->plan;

            if (!$user || !$plan) {
                DB::rollBack();
                Log::error('QRIS Webhook: Order has missing user or plan relationship', [
                    'order_id' => $order->id,
                ]);
                return response()->json(['message' => 'Internal error'], 500);
            }

            $user->plan_id    = $plan->id;
            $user->will_expire = $order->will_expire;
            $user->plan       = json_encode($plan->data);
            $user->save();

            // 6. Notify user
            $this->createNotification([
                'user_id' => $order->user_id,
                'title'   => 'Pembayaran QRIS sukses. Paket ' . $plan->title . ' telah diaktifkan.',
                'url'     => '/user/subscription-history',
            ]);

            $this->sentOrderMail($order);

            DB::commit();

            Log::info('QRIS Webhook: Order activated successfully', [
                'order_id' => $order->id,
                'plan'     => $plan->title,
                'amount'   => $amount,
            ]);

            // Return minimal response — no user data, no amount, no order ID leaked.
            return response()->json(['success' => true, 'message' => 'Processed'], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            // Log full trace internally but NEVER expose it publicly.
            Log::error('QRIS Webhook: Unhandled exception', [
                'error' => $th->getMessage(),
                'file'  => $th->getFile(),
                'line'  => $th->getLine(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Parse Indonesian Rupiah amount from raw notification text.
     *
     * Handles formats like:
     *  - "Rp 50.123,00"
     *  - "Rp50.123"
     *  - "50123"
     *  - "IDR 50,123.00"
     */
    private function parseAmountFromText(string $text): ?float
    {
        // Strip trailing cents suffix common in Indonesian e-wallets (,00)
        $cleaned = preg_replace('/,00\b/', '', $text);

        // Match: optional Rp prefix, then a number with separators
        if (preg_match('/(?:Rp\.?\s*)?([\d]{1,3}(?:[.,]\d{3})+|\d+)/i', $cleaned, $matches)) {
            // Normalize: remove all dots and commas to get raw integer string
            $numeric = preg_replace('/[.,]/', '', $matches[1]);
            $value   = (float) $numeric;
            // Sanity: must be > 0 and look like a real payment (>= Rp 100)
            return $value >= 100 ? $value : null;
        }

        return null;
    }
}
