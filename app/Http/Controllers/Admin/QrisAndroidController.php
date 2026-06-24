<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QrisAndroidController extends Controller
{
    /**
     * Halaman konfigurasi & monitoring QRIS Android Webhook.
     */
    public function index()
    {
        $webhookUrl   = url('/api/qris-webhook');
        $pingUrl      = url('/api/qris-ping');
        $authToken    = $this->getToken();
        $lastPing     = Cache::get('qris_android_last_ping');
        $lastPingDiff = $lastPing ? now()->diffForHumans($lastPing) : 'Belum pernah';
        $deviceOnline = $lastPing && now()->diffInMinutes($lastPing) <= 5;

        // Mask token: tampilkan 8 karakter pertama + dots + 4 karakter terakhir
        $maskedToken = '';
        if ($authToken) {
            $len         = strlen($authToken);
            $maskedToken = substr($authToken, 0, 8)
                . str_repeat('•', max(0, $len - 12))
                . substr($authToken, -4);
        }

        // 10 transaksi terbaru yang diaktifkan oleh webhook
        $recentOrders = Order::with('user', 'plan')
            ->where('status', 1)
            ->where('webhook_processed', 1)
            ->latest()
            ->take(10)
            ->get();

        // Order pending yang bisa dijadikan target simulasi
        $pendingOrders = Order::with('user', 'plan')
            ->where('status', 2)
            ->where('webhook_processed', 0)
            ->latest()
            ->take(5)
            ->get();

        return view('admin.qris-android', compact(
            'webhookUrl',
            'pingUrl',
            'authToken',
            'maskedToken',
            'lastPing',
            'lastPingDiff',
            'deviceOnline',
            'recentOrders',
            'pendingOrders'
        ));
    }

    /**
     * Generate a new cryptographically secure auth token.
     */
    public function test(Request $request)
    {
        $newToken = bin2hex(random_bytes(32));

        $option = Option::where('key', 'qris_android_token')->first();
        if (!$option) {
            $option      = new Option;
            $option->key = 'qris_android_token';
        }
        $option->value = $newToken;
        $option->save();

        Cache::forget('qris_android_token');
        $this->updateEnvKey('ANDROID_QRIS_TOKEN', $newToken);

        return back()->with('success', 'Token baru berhasil dibuat. Salin dari panel token di bawah dan update di aplikasi Android Anda.');
    }

    /**
     * Simulasi kirim webhook dari server ke endpoint lokal
     * — untuk memverifikasi bahwa endpoint dan token berfungsi.
     */
    public function simulate(Request $request)
    {
        $authToken = $this->getToken();

        if (empty($authToken)) {
            return back()->with('error', 'Generate token terlebih dahulu sebelum menjalankan simulasi.');
        }

        $amount = (float) $request->input('amount', 0);

        if ($amount <= 0) {
            return back()->with('error', 'Masukkan nominal yang valid untuk simulasi.');
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'X-Auth-Token' => $authToken,
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post(url('/api/qris-webhook'), [
                    'amount'      => $amount,
                    'description' => 'Simulasi dari panel admin WHASAPI',
                    'source'      => 'admin-simulation',
                    'app'         => 'whasapi-admin',
                ]);

            $body = $response->json();
            $status = $response->status();

            if ($response->successful()) {
                $msg = $body['message'] ?? 'Webhook berhasil diproses.';
                return back()->with('success', "✅ Simulasi berhasil (HTTP $status): $msg");
            } else {
                $msg = $body['message'] ?? 'Tidak ada response dari server.';
                return back()->with('error', "⚠️ Webhook gagal (HTTP $status): $msg");
            }

        } catch (\Throwable $e) {
            Log::error('QRIS Simulate error: ' . $e->getMessage());
            return back()->with('error', 'Koneksi ke endpoint gagal: ' . $e->getMessage());
        }
    }

    /**
     * Read the QRIS token — prefers config() (from .env), falls back to DB options.
     */
    private function getToken(): string
    {
        return config('services.qris_android_token')
            ?: (Option::where('key', 'qris_android_token')->value('value') ?? '');
    }

    /**
     * Safely update a key in the .env file.
     */
    private function updateEnvKey(string $key, string $value): void
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) return;

        $content = file_get_contents($envPath);
        $newLine = "{$key}={$value}";

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", $newLine, $content);
        } else {
            $content .= PHP_EOL . $newLine;
        }

        file_put_contents($envPath, $content);
        Artisan::call('config:clear');
    }
}
