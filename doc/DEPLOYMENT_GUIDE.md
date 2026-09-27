# Panduan Deployment & Instalasi Server Produksi
## Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan Single Sign-On (SSO OIDC)
### SMAN 1 Terbanggi Besar — Lampung Tengah

---

## 1. Spesifikasi Server

### Spesifikasi Minimum (Untuk Beban Uji & Evaluasi):
- **CPU:** 1 vCPU / 2.0 GHz
- **RAM:** 2 GB
- **Penyimpanan:** 25 GB SSD
- **Sistem Operasi:** Ubuntu Server 22.04 LTS / 24.04 LTS atau Debian 12

### Spesifikasi Rekomendasi (Untuk Beban Produksi PPDB ±540 Pendaftar):
- **CPU:** 2 vCPU
- **RAM:** 4 GB
- **Penyimpanan:** 50 GB NVMe SSD
- **Jaringan:** Bandwidth 100 Mbps simetris dengan IP Publik statis

---

## 2. Instalasi Paket Dependensi Linux (Ubuntu/Debian)

Perbarui repository sistem dan instal paket-paket yang diperlukan:

```bash
sudo apt update && sudo apt upgrade -y

# Instal Web Server, Database, dan Utilitas
sudo apt install -y nginx mysql-server git curl unzip certbot python3-certbot-nginx

# Instal PHP 8.2 dan ekstensi yang disyaratkan Laravel
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-intl \
    php8.2-tokenizer php8.2-fileinfo
```

### Instal Composer (PHP Package Manager):
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Instal Node.js (v20 LTS) & NPM:
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 3. Konfigurasi Basis Data MySQL

Masuk ke konsol MySQL sebagai root:
```bash
sudo mysql
```

Jalankan perintah SQL berikut untuk membuat basis data dan pengguna sistem:
```sql
CREATE DATABASE siakad_sman1_tb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'siakad_user'@'localhost' IDENTIFIED BY 'KataSandiKuat@2026';

GRANT ALL PRIVILEGES ON siakad_sman1_tb.* TO 'siakad_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 4. Pemasangan Aplikasi Web

Kloning kode sumber dari repositori GitHub ke direktori web `/var/www/`:

```bash
cd /var/www
sudo git clone https://github.com/fredli4qooni/siakad-sman1-tb.git siakad-sman1-tb
cd siakad-sman1-tb

# Salin konfigurasi environment
sudo cp .env.example .env

# Sesuaikan kepemilikan file ke user web server (www-data)
sudo chown -R www-data:www-data /var/www/siakad-sman1-tb
sudo usermod -a -G www-data $USER
```

### Konfigurasi File `.env`:
Sunting file `.env` menggunakan nano/vim:
```bash
sudo nano .env
```

Pastikan variabel-variabel kunci berikut telah dikonfigurasi untuk lingkungan produksi:
```env
APP_NAME="PPDB & SIAKAD SMAN 1 Terbanggi Besar"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://siakad.sman1terbanggibesar.sch.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad_sman1_tb
DB_USERNAME=siakad_user
DB_PASSWORD=KataSandiKuat@2026

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Konfigurasi Kunci Enkripsi Token OIDC SSO
JWT_SECRET=GantiDenganStringKunciKriptografis64KarakterAcak
```

### Instal Dependensi & Inisialisasi Database:
```bash
# Instal dependensi PHP tanpa paket pengembangan
composer install --no-dev --optimize-autoloader

# Generate kunci aplikasi
php artisan key:generate

# Migrasi tabel dan isi data awal (seeders)
php artisan migrate --force --seed

# Hubungkan penyimpanan berkas publik (storage link)
php artisan storage:link

# Instal dependensi Node.js & kompilasi aset Vite
npm install
npm run build
```

---

## 5. Konfigurasi Web Server Nginx

Buat file konfigurasi virtual host Nginx:
```bash
sudo nano /etc/nginx/sites-available/siakad-sman1-tb
```

Isikan konfigurasi berikut:
```nginx
server {
    listen 80;
    server_name siakad.sman1terbanggibesar.sch.id;
    root /var/www/siakad-sman1-tb/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;
    charset utf-8;

    # Batas ukuran unggahan berkas (sesuai modul PPDB: 10MB batas server)
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi dan muat ulang Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/siakad-sman1-tb /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 6. Pemasangan Sertifikat SSL/TLS (HTTPS)

Protokol OpenID Connect (OIDC) dan Single Sign-On (SSO) mensyaratkan komunikasi terenkripsi (HTTPS) agar pertukaran `id_token` dan kredensial login aman dari intersepsi.

Gunakan Certbot untuk memasang sertifikat gratis Let's Encrypt:
```bash
sudo certbot --nginx -d siakad.sman1terbanggibesar.sch.id
```
Pilih opsi **Redirect HTTP to HTTPS** secara otomatis.

---

## 7. Optimasi Kinerja Produksi Laravel

Jalankan perintah cache untuk meningkatkan kecepatan respons sistem:
```bash
cd /var/www/siakad-sman1-tb

# Cache konfigurasi, rute, dan view blade
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Izin Akses Direktori (Permissions):
Pastikan direktori storage dan cache dapat ditulis oleh web server:
```bash
sudo chown -R www-data:www-data /var/www/siakad-sman1-tb/storage /var/www/siakad-sman1-tb/bootstrap/cache
sudo chmod -R 775 /var/www/siakad-sman1-tb/storage /var/www/siakad-sman1-tb/bootstrap/cache
```

---

## 8. Automasi Tugas Terjadwal (Cron Job)

Buka crontab server:
```bash
sudo crontab -u www-data -e
```
Tambahkan baris scheduler Laravel:
```cron
* * * * * cd /var/www/siakad-sman1-tb && php artisan schedule:run >> /dev/null 2>&1
```

---

## 9. Prosedur Pencadangan (*Backup*) Basis Data Rutin

Buat skrip pencadangan otomatis harian:
```bash
sudo mkdir -p /var/backups/siakad
sudo nano /usr/local/bin/backup-siakad.sh
```

Isi skrip:
```bash
#!/bin/bash
BACKUP_DIR="/var/backups/siakad"
DATE=$(date +"%Y%m%d_%H%M%S")
FILENAME="$BACKUP_DIR/db_siakad_$DATE.sql.gz"

mysqldump -u siakad_user -p'KataSandiKuat@2026' siakad_sman1_tb | gzip > "$FILENAME"

# Hapus backup yang lebih lama dari 30 hari
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +30 -exec rm {} \;
```

Jadikan executable dan jadwalkan di crontab setiap pukul 02:00 pagi:
```bash
sudo chmod +x /usr/local/bin/backup-siakad.sh

# Tambahkan ke crontab root:
# 0 2 * * * /usr/local/bin/backup-siakad.sh
```

---

## 10. Panduan Pemecahan Masalah (*Troubleshooting*)

| Gejala Masalah | Kemungkinan Penyebab | Tindakan Solusi |
|---|---|---|
| **HTTP 500 Internal Server Error** | Izin direktori storage atau `.env` belum dikonfigurasi | Periksa log di `storage/logs/laravel.log`. Pastikan izin folder 775 untuk `storage/` dan `bootstrap/cache/`. |
| **Unggahan Berkas Gagal (Ukuran Melebihi Batas)** | Limit `upload_max_filesize` di PHP-FPM atau Nginx | Sesuaikan `upload_max_filesize = 10M` dan `post_max_size = 10M` pada `/etc/php/8.2/fpm/php.ini`, lalu `sudo systemctl restart php8.2-fpm`. |
| **Token OIDC Invalid / Signature Mismatch** | Perubahan `APP_KEY` atau `JWT_SECRET` | Pastikan `APP_KEY` dan `JWT_SECRET` tidak berubah setelah sistem berjalan di produksi. |
| **Tampilan CSS/JS Rusak setelah Pembaruan Kode** | Aset belum dikompilasi atau cache rute usang | Jalankan `npm run build && php artisan optimize:clear && php artisan optimize`. |
