#!/bin/bash

# Nama proyek
PROJECT_NAME="soketwasap"

# Memeriksa status proyek
STATUS=$(ps -ef | grep "$PROJECT_NAME" | grep -v grep)

if [ -z "$STATUS" ]; then
    # Jika proyek tidak berjalan, maka mulai proyek
    cd /www/wwwroot/wa.mpdev.my.id
    npm start # Atau perintah sesuai yang Anda butuhkan untuk menjalankan proyek
fi