package com.whasapi.receiver;

import android.app.Notification;
import android.content.Intent;
import android.os.Bundle;
import android.service.notification.NotificationListenerService;
import android.service.notification.StatusBarNotification;
import android.util.Log;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class NotificationService extends NotificationListenerService {
    private static final String TAG = "WHASAPI_Service";
    private SettingsHelper helper;

    @Override
    public void onCreate() {
        super.onCreate();
        this.helper = new SettingsHelper(this);
        helper.addLog("[SYSTEM] Listener notifikasi aktif.");
    }

    @Override
    public void onListenerConnected() {
        super.onListenerConnected();
        Log.d(TAG, "Notification listener connected");
        helper.addLog("[SYSTEM] Layanan terhubung ke sistem operasi Android.");
        
        // Broadcast to activity to update status badge
        Intent intent = new Intent("com.whasapi.receiver.SERVICE_STATUS");
        intent.putExtra("status", "AKTIF");
        sendBroadcast(intent);
    }

    @Override
    public void onListenerDisconnected() {
        super.onListenerDisconnected();
        Log.d(TAG, "Notification listener disconnected");
        helper.addLog("[SYSTEM] Layanan terputus.");
        
        Intent intent = new Intent("com.whasapi.receiver.SERVICE_STATUS");
        intent.putExtra("status", "NONAKTIF");
        sendBroadcast(intent);
    }

    @Override
    public void onNotificationPosted(StatusBarNotification sbn) {
        if (sbn == null) return;

        String packageName = sbn.getPackageName();
        Bundle extras = sbn.getNotification().extras;
        if (extras == null) return;

        String title = "";
        Object titleObj = extras.get(Notification.EXTRA_TITLE);
        if (titleObj != null) {
            title = titleObj.toString();
        }

        String text = "";
        Object textObj = extras.get(Notification.EXTRA_TEXT);
        if (textObj != null) {
            text = textObj.toString();
        }

        // Empty notifications are ignored
        if (title.isEmpty() && text.isEmpty()) return;

        // Check package filters
        boolean isMatch = false;
        String appName = "Aplikasi Lain";

        if (packageName.contains("gobiz")) {
            if (helper.isFilterGoBiz()) { isMatch = true; appName = "GoBiz / GoPay"; }
        } else if (packageName.contains("ovo") || packageName.contains("opco")) {
            if (helper.isFilterOvo()) { isMatch = true; appName = "OVO"; }
        } else if (packageName.contains("dana")) {
            if (helper.isFilterDana()) { isMatch = true; appName = "DANA"; }
        } else if (packageName.contains("shopee")) {
            if (helper.isFilterShopee()) { isMatch = true; appName = "ShopeePay"; }
        } else if (packageName.contains("bca")) {
            if (helper.isFilterBca()) { isMatch = true; appName = "BCA Mobile"; }
        } else if (packageName.contains("mandiri.livin") || packageName.contains("livin")) {
            if (helper.isFilterMandiri()) { isMatch = true; appName = "Livin Mandiri"; }
        } else if (packageName.contains("mms") || packageName.contains("messaging") || packageName.contains("telephony")) {
            if (helper.isFilterSms()) { isMatch = true; appName = "SMS Transaksi"; }
        }

        if (isMatch) {
            final String finalAppName = appName;
            final String finalTitle = title;
            final String finalText = text;
            final String finalPkg = packageName;
            
            helper.addLog("[NOTIFIKASI] Mendeteksi dari " + finalAppName + ": " + finalTitle + " - " + finalText);
            
            // Forward asynchronously
            forwardNotificationToWebhook(finalPkg, finalAppName, finalTitle, finalText);
        }
    }

    public void forwardNotificationToWebhook(final String packageName, final String friendlyName, final String title, final String text) {
        final String webhookUrl = helper.getWebhookUrl();
        final String authToken = helper.getAuthToken();

        if (webhookUrl.isEmpty()) {
            helper.addLog("[E-WEBHOOK] Gagal kirim: Webhook URL belum dikonfigurasi.");
            return;
        }

        new Thread(new Runnable() {
            @Override
            public void run() {
                HttpURLConnection conn = null;
                try {
                    URL url = new URL(webhookUrl);
                    conn = (HttpURLConnection) url.openConnection();
                    conn.setRequestMethod("POST");
                    conn.setRequestProperty("Content-Type", "application/json; charset=utf-8");
                    conn.setRequestProperty("X-Auth-Token", authToken);
                    conn.setDoOutput(true);
                    conn.setConnectTimeout(10000); // 10s
                    conn.setReadTimeout(10000);

                    // Build JSON string
                    // Note: amount is optional. The Laravel backend extracts it from text (description)
                    String jsonPayload = "{"
                            + "\"amount\": \"\","
                            + "\"description\": \"" + escapeJsonString(title + " - " + text) + "\","
                            + "\"source\": \"android-notification\","
                            + "\"app\": \"" + escapeJsonString(friendlyName) + "\","
                            + "\"package\": \"" + escapeJsonString(packageName) + "\""
                            + "}";

                    byte[] input = jsonPayload.getBytes(StandardCharsets.UTF_8);
                    try (OutputStream os = conn.getOutputStream()) {
                        os.write(input, 0, input.length);
                    }

                    int responseCode = conn.getResponseCode();
                    if (responseCode == 200 || responseCode == 201) {
                        helper.addLog("[WEBHOOK] Sukses terkirim! Respon: " + responseCode);
                    } else {
                        helper.addLog("[E-WEBHOOK] Ditolak oleh server. Status: " + responseCode);
                    }
                } catch (Exception e) {
                    Log.e(TAG, "Webhook call error", e);
                    helper.addLog("[E-WEBHOOK] Gagal terhubung ke server: " + e.getMessage());
                } finally {
                    if (conn != null) {
                        conn.disconnect();
                    }
                }
            }
        }).start();
    }

    private String escapeJsonString(String input) {
        if (input == null) return "";
        return input.replace("\\", "\\\\")
                    .replace("\"", "\\\"")
                    .replace("\n", "\\n")
                    .replace("\r", "\\r")
                    .replace("\t", "\\t");
    }
}
