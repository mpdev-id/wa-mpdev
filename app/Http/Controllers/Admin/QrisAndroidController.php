<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class QrisAndroidController extends Controller
{
    /**
     * Halaman konfigurasi & monitoring QRIS Android Webhook.
     */
    public function index()
    {
        $webhookUrl   = url('/api/qris-webhook');
        $authToken    = $this->getToken();
        $lastPing     = Cache::get('qris_android_last_ping');
        $lastPingDiff = $lastPing ? now()->diffForHumans($lastPing) : 'Belum ada';
        $deviceOnline = $lastPing && now()->diffInMinutes($lastPing) <= 5;

        // Mask token for display: show only first 8 and last 4 characters
        $maskedToken = '';
        if ($authToken) {
            $len = strlen($authToken);
            $maskedToken = substr($authToken, 0, 8)
                . str_repeat('•', max(0, $len - 12))
                . substr($authToken, -4);
        }

        // 10 transaksi terbaru yang terverifikasi otomatis oleh webhook
        $recentOrders = Order::with('user', 'plan')
            ->where('status', 1)
            ->where('webhook_processed', 1)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.qris-android', compact(
            'webhookUrl',
            'authToken',
            'maskedToken',
            'lastPing',
            'lastPingDiff',
            'deviceOnline',
            'recentOrders'
        ));
    }

    /**
     * Generate a new cryptographically secure auth token and persist it.
     */
    public function test(Request $request)
    {
        // 32 bytes = 64-char hex token (256-bit entropy)
        $newToken = bin2hex(random_bytes(32));

        // Save to options table (same pattern used throughout the project)
        $option = Option::where('key', 'qris_android_token')->first();
        if (!$option) {
            $option      = new Option;
            $option->key = 'qris_android_token';
        }
        $option->value = $newToken;
        $option->save();

        // Clear the cache so get_option() picks up the new value immediately
        Cache::forget('qris_android_token');

        // Also write to .env so config('services.qris_android_token') works after config:clear
        $this->updateEnvKey('ANDROID_QRIS_TOKEN', $newToken);

        // Do NOT expose the token in the flash session message (visible in server logs).
        return back()->with('success', 'Token baru berhasil dibuat. Salin dari panel di bawah dan update di aplikasi Android Anda.');
    }

    /**
     * Read the QRIS token — prefers config() (from .env via config cache),
     * falls back to the options table (admin-panel managed value).
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

        if (!file_exists($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);
        $newLine = "{$key}={$value}";

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", $newLine, $content);
        } else {
            $content .= PHP_EOL . $newLine;
        }

        file_put_contents($envPath, $content);

        // Rebuild config cache so new value is available immediately
        Artisan::call('config:clear');
    }
}
