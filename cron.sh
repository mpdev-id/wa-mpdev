#!/bin/bash

# ====================================================================
# CONFIGURATION: SESUAIKAN DENGAN ALAMAT WEBSITE ANDA
# Ganti dengan domain produksi Anda (tanpa tanda garis miring di akhir)
# Contoh: SITE_URL="https://wa-mpdev.com" atau "http://127.0.0.1:8000"
# ====================================================================
SITE_URL="http://127.0.0.1"

# ====================================================================
# 1. TUGAS RUTIN MENITAN (Dijalankan setiap menit)
# ====================================================================
echo "[$(date '+%Y-%m-%d %H:%M:%S')] [INFO] Memulai eksekusi tugas menit..."

# - Execute Schedule Messages
echo "   [+] Menjalankan: Execute Schedule Messages..."
curl -sL --max-time 50 "${SITE_URL}/cron/execute-schedule" > /dev/null

# - Execute Webhooks Requests
echo "   [+] Menjalankan: Execute Webhooks Requests..."
curl -sL --max-time 50 "${SITE_URL}/cron/execute-webhook" > /dev/null

# ====================================================================
# 2. TUGAS RUTIN HARIAN (Dijalankan sekali sehari pada pukul 03:00 dini hari)
# ====================================================================
CURRENT_HOUR=$(date +%H)
CURRENT_MINUTE=$(date +%M)

if [ "$CURRENT_HOUR" == "03" ] && [ "$CURRENT_MINUTE" == "00" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] [INFO] Memulai eksekusi tugas harian..."
    
    # - Notify to customer before expire subscription
    echo "   [+] Menjalankan: Notifikasi kedaluwarsa paket..."
    curl -sL --max-time 120 "${SITE_URL}/cron/notify-to-user" > /dev/null
    
    # - Remove Junk Devices
    echo "   [+] Menjalankan: Pembersihan perangkat sampah..."
    curl -sL --max-time 120 "${SITE_URL}/cron/remove-junk-device" > /dev/null
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] [SUCCESS] Seluruh tugas cronjob selesai."
