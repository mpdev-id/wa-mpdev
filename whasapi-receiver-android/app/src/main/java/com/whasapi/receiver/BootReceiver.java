package com.whasapi.receiver;

import android.content.BroadcastReceiver;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.service.notification.NotificationListenerService;

public class BootReceiver extends BroadcastReceiver {
    @Override
    public void onReceive(Context context, Intent intent) {
        if (Intent.ACTION_BOOT_COMPLETED.equals(intent.getAction()) || 
            "android.intent.action.QUICKBOOT_POWERON".equals(intent.getAction())) {
            
            SettingsHelper helper = new SettingsHelper(context);
            helper.addLog("[SYSTEM] Perangkat booting ulang. Mencoba me-restart pendengar notifikasi.");
            
            // Force re-binding NotificationListenerService on boot
            try {
                PackageManager pm = context.getPackageManager();
                ComponentName componentName = new ComponentName(context, NotificationService.class);
                pm.setComponentEnabledSetting(componentName,
                        PackageManager.COMPONENT_ENABLED_STATE_DISABLED, PackageManager.DONT_KILL_APP);
                pm.setComponentEnabledSetting(componentName,
                        PackageManager.COMPONENT_ENABLED_STATE_ENABLED, PackageManager.DONT_KILL_APP);
            } catch (Exception e) {
                helper.addLog("[SYSTEM] Gagal memicu binding service: " + e.getMessage());
            }
        }
    }
}
