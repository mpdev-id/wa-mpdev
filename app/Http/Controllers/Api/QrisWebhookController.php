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
     * Simple ping endpoint — no auth needed.
     * Automate/Tasker can call GET /api/qris-ping to test connectivity.
     */
    public function ping(Request $request)
    {
        Cache::put('qris_android_last_ping', now(), now()->addMinutes(10));

        return response()->json([
            'status'    => 'ok',
            'message'   => 'WHASAPI QRIS Webhook aktif dan siap menerima notifikasi.',
            'server'    => config('app.name'),
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }

    /**
     * Warning endpoint for unsupported GET request to webhook URL.
     * Helpful for debugging redirect issues (HTTP->HTTPS or trailing slash).
     */
    public function handleGet(Request $request)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Method GET tidak didukung untuk webhook QRIS. Jika Anda mendapatkan error ini setelah mengirim request POST dari HP, mohon periksa konfigurasi Webhook URL Anda di aplikasi HP: (1) Pastikan protokolnya tepat (misal ganti http:// dengan https:// jika website Anda mewajibkan https), dan (2) Pastikan TIDAK menggunakan tanda garis miring (/) di akhir URL karena akan menyebabkan server meredireksi request POST menjadi GET.'
        ], 405);
    }

    /**
     * Handle incoming QRIS payment notification from Android device.
     *
     * Token diterima dari:
     *  1. Header: X-Auth-Token (recommended)
     *  2. Header: Authorization: Bearer <token>
     *  3. Body field: token (fallback untuk Automate yang susah set header)
     *  4. Query string: ?token=xxx (last resort)
     */
    public function handle(Request $request)
    {
        // ── 1. Update ping timestamp ────────────────────────────────────────
        Cache::put('qris_android_last_ping', now(), now()->addMinutes(10));

        // ── 2. Token Authentication ─────────────────────────────────────────
        // Accept token from multiple sources for Automate compatibility
        $receivedToken = $request->header('X-Auth-Token')
            ?: $this->extractBearerToken($request)
            ?: $request->input('token')
            ?: $request->query('token')
            ?: '';

        $configToken = config('services.qris_android_token')
            ?: (Option::where('key', 'qris_android_token')->value('value') ?? '');

        if (empty($configToken)) {
            Log::error('QRIS Webhook: Token belum dikonfigurasi.', ['ip' => $request->ip()]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Webhook belum dikonfigurasi. Generate token di panel admin.',
            ], 503);
        }

        if (empty($receivedToken) || !hash_equals($configToken, $receivedToken)) {
            Log::warning('QRIS Webhook: Token tidak valid', [
                'ip'        => $request->ip(),
                'has_token' => !empty($receivedToken),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Token autentikasi tidak valid. Periksa X-Auth-Token di header.',
            ], 401);
        }

        // ── 3. Input Validation ────────────────────────────────────────────
        $amount = $request->input('amount')
            ?: $request->input('nominal')
            ?: null;

        $text = $request->input('description')
            ?? $request->input('message')
            ?? $request->input('text')
            ?? $request->input('body')
            ?? $request->input('notif_text')
            ?? null;

        // Coba parse amount dari teks notifikasi jika tidak dikirim langsung
        if ((empty($amount) || $amount == 0) && !empty($text)) {
            $amount = $this->parseAmountFromText($text);
        }

        $amount = is_numeric($amount) ? (float) $amount : null;

        if (empty($amount) || $amount <= 0) {
            Log::info('QRIS Webhook: Payload tidak valid (amount kosong)', [
                'ip'   => $request->ip(),
                'text' => substr((string) $text, 0, 300),
                'body' => substr(json_encode($request->all()), 0, 500),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Field amount tidak ada atau bukan angka valid. Kirim field amount (angka) atau description (teks notifikasi).',
                'received' => [
                    'amount' => $request->input('amount'),
                    'description' => substr((string) $text, 0, 100),
                ],
            ], 400);
        }

        if ($amount > 99_999_999) {
            return response()->json(['status' => 'error', 'message' => 'Amount melebihi batas maksimum.'], 400);
        }

        // ── 4. Match & Activate Order ──────────────────────────────────────
        DB::beginTransaction();
        try {
            $order = Order::where('status', 2)
                          ->where('amount', $amount)
                          ->where('webhook_processed', 0)
                          ->lockForUpdate()
                          ->with('user', 'plan')
                          ->first();

            if (!$order) {
                DB::rollBack();
                Log::info('QRIS Webhook: Tidak ada order pending yang cocok', ['amount' => $amount]);
                return response()->json([
                    'status'  => 'acknowledged',
                    'message' => 'Tidak ada order pending yang cocok dengan nominal tersebut.',
                    'amount'  => $amount,
                ], 200);
            }

            $order->webhook_processed = 1;
            $order->status            = 1;
            $order->save();

            $user = $order->user;
            $plan = $order->plan;

            if (!$user || !$plan) {
                DB::rollBack();
                Log::error('QRIS Webhook: Order tidak memiliki relasi user/plan', ['order_id' => $order->id]);
                return response()->json(['status' => 'error', 'message' => 'Internal error: relasi order tidak valid.'], 500);
            }

            $user->plan_id    = $plan->id;
            $user->will_expire = $order->will_expire ?? now()->addDays($plan->duration ?? 30);
            $user->plan        = json_encode($plan->data);
            $user->save();

            $this->createNotification([
                'user_id' => $order->user_id,
                'title'   => 'Pembayaran QRIS sukses. Paket ' . $plan->title . ' telah diaktifkan.',
                'url'     => '/user/subscription-history',
            ]);

            try { $this->sentOrderMail($order); } catch (\Throwable $e) {
                Log::warning('QRIS Webhook: Gagal kirim email notifikasi', ['error' => $e->getMessage()]);
            }

            DB::commit();

            Log::info('QRIS Webhook: Order berhasil diaktifkan', [
                'order_id' => $order->id,
                'plan'     => $plan->title,
                'amount'   => $amount,
                'user'     => $user->email,
            ]);

            return response()->json([
                'status'   => 'success',
                'message'  => 'Pembayaran diterima. Paket ' . $plan->title . ' diaktifkan.',
                'order_id' => $order->id,
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('QRIS Webhook: Exception tidak tertangani', [
                'error' => $th->getMessage(),
                'file'  => $th->getFile(),
                'line'  => $th->getLine(),
            ]);
            return response()->json(['status' => 'error', 'message' => 'Internal server error.'], 500);
        }
    }

    /**
     * Extract token from Authorization: Bearer <token> header.
     */
    private function extractBearerToken(Request $request): ?string
    {
        $auth = $request->header('Authorization', '');
        if (str_starts_with($auth, 'Bearer ')) {
            return substr($auth, 7);
        }
        return null;
    }

    /**
     * Parse Rupiah amount dari teks notifikasi Android.
     * Handles: "Rp 50.123,00", "Rp50.123", "50123", "IDR 50,123.00"
     */
    private function parseAmountFromText(string $text): ?float
    {
        $cleaned = preg_replace('/,00\b/', '', $text);

        // Step 1: Coba cari angka yang didahului simbol mata uang (Rp atau IDR)
        if (preg_match('/(?:Rp\.?\s*|IDR\s*)+([\d]{1,3}(?:[.,]\d{3})+|\d{1,})/i', $cleaned, $matches)) {
            $numeric = preg_replace('/[.,]/', '', $matches[1]);
            $value   = (float) $numeric;
            if ($value >= 1) {
                return $value;
            }
        }

        // Step 2: Coba cari kata kunci "sebesar" / "nominal" / "sejumlah" diikuti angka
        if (preg_match('/(?:sebesar|nominal|sejumlah)\s*(?:Rp\.?\s*|IDR\s*)?([\d]{1,3}(?:[.,]\d{3})+|\d{1,})/i', $cleaned, $matches)) {
            $numeric = preg_replace('/[.,]/', '', $matches[1]);
            $value   = (float) $numeric;
            if ($value >= 1) {
                return $value;
            }
        }

        // Step 3: Fallback ke pencocokan angka umum (jika tidak ada Rp/IDR atau kata kunci)
        if (preg_match('/([\d]{1,3}(?:[.,]\d{3})+|\d{1,})/', $cleaned, $matches)) {
            $numeric = preg_replace('/[.,]/', '', $matches[1]);
            $value   = (float) $numeric;
            if ($value >= 1 && $value <= 99999999) { // batasi fallback agar tidak mencocokkan norek panjang
                return $value;
            }
        }

        return null;
    }
}
