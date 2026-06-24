package com.whasapi.receiver;

import android.content.Context;
import android.content.SharedPreferences;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class SettingsHelper {
    private static final String PREF_NAME = "whasapi_receiver_prefs";
    private static final String KEY_WEBHOOK_URL = "webhook_url";
    private static final String KEY_AUTH_TOKEN = "auth_token";
    private static final String KEY_LOGS = "console_logs";
    
    // App filters
    private static final String KEY_FILTER_GOBIZ = "filter_gobiz";
    private static final String KEY_FILTER_OVO = "filter_ovo";
    private static final String KEY_FILTER_DANA = "filter_dana";
    private static final String KEY_FILTER_SHOPEE = "filter_shopee";
    private static final String KEY_FILTER_BCA = "filter_bca";
    private static final String KEY_FILTER_MANDIRI = "filter_mandiri";
    private static final String KEY_FILTER_SMS = "filter_sms";

    private final SharedPreferences prefs;

    public SettingsHelper(Context context) {
        this.prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
    }

    public String getWebhookUrl() {
        return prefs.getString(KEY_WEBHOOK_URL, "");
    }

    public void setWebhookUrl(String url) {
        prefs.edit().putString(KEY_WEBHOOK_URL, url).apply();
    }

    public String getAuthToken() {
        return prefs.getString(KEY_AUTH_TOKEN, "");
    }

    public void setAuthToken(String token) {
        prefs.edit().putString(KEY_AUTH_TOKEN, token).apply();
    }

    // Toggle filters getter/setter
    public boolean isFilterGoBiz() { return prefs.getBoolean(KEY_FILTER_GOBIZ, true); }
    public void setFilterGoBiz(boolean val) { prefs.edit().putBoolean(KEY_FILTER_GOBIZ, val).apply(); }

    public boolean isFilterOvo() { return prefs.getBoolean(KEY_FILTER_OVO, true); }
    public void setFilterOvo(boolean val) { prefs.edit().putBoolean(KEY_FILTER_OVO, val).apply(); }

    public boolean isFilterDana() { return prefs.getBoolean(KEY_FILTER_DANA, true); }
    public void setFilterDana(boolean val) { prefs.edit().putBoolean(KEY_FILTER_DANA, val).apply(); }

    public boolean isFilterShopee() { return prefs.getBoolean(KEY_FILTER_SHOPEE, true); }
    public void setFilterShopee(boolean val) { prefs.edit().putBoolean(KEY_FILTER_SHOPEE, val).apply(); }

    public boolean isFilterBca() { return prefs.getBoolean(KEY_FILTER_BCA, true); }
    public void setFilterBca(boolean val) { prefs.edit().putBoolean(KEY_FILTER_BCA, val).apply(); }

    public boolean isFilterMandiri() { return prefs.getBoolean(KEY_FILTER_MANDIRI, true); }
    public void setFilterMandiri(boolean val) { prefs.edit().putBoolean(KEY_FILTER_MANDIRI, val).apply(); }

    public boolean isFilterSms() { return prefs.getBoolean(KEY_FILTER_SMS, false); } // Default false
    public void setFilterSms(boolean val) { prefs.edit().putBoolean(KEY_FILTER_SMS, val).apply(); }

    // Logs management
    public String getLogs() {
        return prefs.getString(KEY_LOGS, "[SYSTEM] Menunggu konfigurasi...");
    }

    public void addLog(String message) {
        String time = new SimpleDateFormat("HH:mm:ss", Locale.getDefault()).format(new Date());
        String currentLogs = getLogs();
        
        // Keep logs clean, truncate if too long
        if (currentLogs.length() > 5000) {
            currentLogs = currentLogs.substring(0, 3000);
        }
        
        String newLog = "[" + time + "] " + message + "\n" + currentLogs;
        prefs.edit().putString(KEY_LOGS, newLog).apply();
    }

    public void clearLogs() {
        prefs.edit().putString(KEY_LOGS, "").apply();
    }
}
