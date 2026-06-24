package com.whasapi.receiver;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class MainActivity extends AppCompatActivity {
    private EditText etWebhookUrl, etAuthToken;
    private TextView tvServiceStatus, tvLogsConsole;
    private Button btnSaveConfig, btnGrantPermission, btnTestWebhook;
    private SwitchCompat swGoBiz, swOvo, swDana, swShopee, swBca, swMandiri, swSms;

    private SettingsHelper helper;
    private Handler logHandler;
    private Runnable logRunnable;
    private BroadcastReceiver serviceStatusReceiver;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        helper = new SettingsHelper(this);

        // Bind Views
        etWebhookUrl = findViewById(R.id.etWebhookUrl);
        etAuthToken = findViewById(R.id.etAuthToken);
        tvServiceStatus = findViewById(R.id.tvServiceStatus);
        tvLogsConsole = findViewById(R.id.tvLogsConsole);
        btnSaveConfig = findViewById(R.id.btnSaveConfig);
        btnGrantPermission = findViewById(R.id.btnGrantPermission);
        btnTestWebhook = findViewById(R.id.btnTestWebhook);

        swGoBiz = findViewById(R.id.swGoBiz);
        swOvo = findViewById(R.id.swOvo);
        swDana = findViewById(R.id.swDana);
        swShopee = findViewById(R.id.swShopee);
        swBca = findViewById(R.id.swBca);
        swMandiri = findViewById(R.id.swMandiri);
        swSms = findViewById(R.id.swSms);

        // Load Preferences
        loadConfigValues();

        // Save Button Listener
        btnSaveConfig.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                saveConfigValues();
            }
        });

        // Grant Permission Button Listener
        btnGrantPermission.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(Settings.ACTION_NOTIFICATION_LISTENER_SETTINGS));
                Toast.makeText(MainActivity.this, "Pilih WHASAPI Receiver dan beri izin akses.", Toast.LENGTH_LONG).show();
            }
        });

        // Test Webhook Button Listener
        btnTestWebhook.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendTestWebhook();
            }
        });

        // Poll Logs every second
        logHandler = new Handler();
        logRunnable = new Runnable() {
            @Override
            public void run() {
                tvLogsConsole.setText(helper.getLogs());
                checkServiceStatus();
                logHandler.postDelayed(this, 1000);
            }
        };
        logHandler.postDelayed(logRunnable, 1000);

        // Register status receiver
        serviceStatusReceiver = new BroadcastReceiver() {
            @Override
            public void onReceive(Context context, Intent intent) {
                String status = intent.getStringExtra("status");
                updateStatusBadge(status);
            }
        };
        registerReceiver(serviceStatusReceiver, new IntentFilter("com.whasapi.receiver.SERVICE_STATUS"));
    }

    @Override
    protected void onResume() {
        super.onResume();
        checkServiceStatus();
        tvLogsConsole.setText(helper.getLogs());
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        logHandler.removeCallbacks(logRunnable);
        try {
            unregisterReceiver(serviceStatusReceiver);
        } catch (Exception ignored) {}
    }

    private void loadConfigValues() {
        etWebhookUrl.setText(helper.getWebhookUrl());
        etAuthToken.setText(helper.getAuthToken());

        swGoBiz.setChecked(helper.isFilterGoBiz());
        swOvo.setChecked(helper.isFilterOvo());
        swDana.setChecked(helper.isFilterDana());
        swShopee.setChecked(helper.isFilterShopee());
        swBca.setChecked(helper.isFilterBca());
        swMandiri.setChecked(helper.isFilterMandiri());
        swSms.setChecked(helper.isFilterSms());
    }

    private void saveConfigValues() {
        String url = etWebhookUrl.getText().toString().trim();
        String token = etAuthToken.getText().toString().trim();

        helper.setWebhookUrl(url);
        helper.setAuthToken(token);

        helper.setFilterGoBiz(swGoBiz.isChecked());
        helper.setFilterOvo(swOvo.isChecked());
        helper.setFilterDana(swDana.isChecked());
        helper.setFilterShopee(swShopee.isChecked());
        helper.setFilterBca(swBca.isChecked());
        helper.setFilterMandiri(swMandiri.isChecked());
        helper.setFilterSms(swSms.isChecked());

        helper.addLog("[CONFIG] Konfigurasi berhasil disimpan.");
        Toast.makeText(this, "Konfigurasi disimpan!", Toast.LENGTH_SHORT).show();
    }

    private void checkServiceStatus() {
        boolean isNotificationServiceEnabled = false;
        String packageName = getPackageName();
        String flat = Settings.Secure.getString(getContentResolver(), "enabled_notification_listeners");
        if (flat != null) {
            String[] names = flat.split(":");
            for (String name : names) {
                if (name.contains(packageName)) {
                    isNotificationServiceEnabled = true;
                    break;
                }
            }
        }

        if (isNotificationServiceEnabled) {
            updateStatusBadge("AKTIF");
        } else {
            updateStatusBadge("NONAKTIF");
        }
    }

    private void updateStatusBadge(String status) {
        if ("AKTIF".equals(status)) {
            tvServiceStatus.setText("LAYANAN AKTIF");
            tvServiceStatus.setBackgroundResource(R.drawable.bg_badge_green);
        } else {
            tvServiceStatus.setText("IZIN DIMATIKAN");
            tvServiceStatus.setBackgroundResource(R.drawable.bg_badge_red);
        }
    }

    private void sendTestWebhook() {
        final String webhookUrl = helper.getWebhookUrl();
        final String authToken = etAuthToken.getText().toString().trim();

        if (webhookUrl.isEmpty()) {
            Toast.makeText(this, "Simpan Webhook URL terlebih dahulu!", Toast.LENGTH_LONG).show();
            return;
        }

        helper.addLog("[TEST] Mencoba mengirim uji coba pembayaran nominal Rp 50.123...");
        Toast.makeText(this, "Mengirim Uji Coba...", Toast.LENGTH_SHORT).show();

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
                    conn.setConnectTimeout(5000);
                    conn.setReadTimeout(5000);

                    // Mock DANA payment text payload
                    String jsonPayload = "{"
                            + "\"amount\": \"50123\","
                            + "\"description\": \"[TEST] Uji Coba: Transfer masuk Rp 50.123 dari DANA berhasil diterima.\","
                            + "\"source\": \"android-notification\","
                            + "\"app\": \"DANA\","
                            + "\"package\": \"id.dana\""
                            + "}";

                    byte[] input = jsonPayload.getBytes(StandardCharsets.UTF_8);
                    try (OutputStream os = conn.getOutputStream()) {
                        os.write(input, 0, input.length);
                    }

                    final int responseCode = conn.getResponseCode();
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            if (responseCode == 200 || responseCode == 201) {
                                helper.addLog("[TEST] Webhook Uji Coba Sukses terkirim! Respon: " + responseCode);
                                Toast.makeText(MainActivity.this, "Test Sukses!", Toast.LENGTH_SHORT).show();
                            } else {
                                helper.addLog("[TEST-ERROR] Server menolak dengan respon: " + responseCode);
                                Toast.makeText(MainActivity.this, "Test Gagal! Cek log.", Toast.LENGTH_LONG).show();
                            }
                        }
                    });
                } catch (final Exception e) {
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            helper.addLog("[TEST-ERROR] Koneksi gagal: " + e.getMessage());
                            Toast.makeText(MainActivity.this, "Test Gagal! Cek log.", Toast.LENGTH_LONG).show();
                        }
                    });
                } finally {
                    if (conn != null) {
                        conn.disconnect();
                    }
                }
            }
        }).start();
    }
}
