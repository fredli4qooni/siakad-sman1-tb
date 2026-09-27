# Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan Single Sign-On (SSO OIDC)
### SMAN 1 Terbanggi Besar — Lampung Tengah

Aplikasi web terintegrasi berbasis Laravel 11/12 yang menggabungkan proses Penerimaan Peserta Didik Baru (PPDB) dan Sistem Informasi Akademik (SIAKAD) dalam satu ekosistem terpadu menggunakan arsitektur **Identity Provider (IdP) OpenID Connect (OIDC)**. Sistem ini mengeliminasi duplikasi entri data manual dengan secara otomatis menyinkronkan data siswa yang dinyatakan **"LULUS"** ke basis data akademik SIAKAD.

---

## Ringkasan Fitur Utama

### 1. Modul Autentikasi & Identity Provider (OIDC SSO)
- **OIDC Server & Discovery:** Menyediakan endpoint standar `/.well-known/openid-configuration`, `/oauth/authorize`, `/oauth/token`, dan `/oauth/userinfo`.
- **JWT Signed ID Tokens:** Menerbitkan `id_token` bertanda tangan kriptografis untuk pertukaran klaim profil dan peran antar modul.
- **Single Sign-On (SSO):** Sekali login di portal SSO, pengguna dapat berpindah antara modul PPDB dan SIAKAD tanpa perlu autentikasi ulang.
- **Multi-Identifier Login:** Pengguna dapat masuk menggunakan email maupun username/nama.
- **Role-Based Access Control (RBAC):** Pemisahan hak akses ketat antara `admin`, `operator`, `guru`, `siswa`, dan `calon_siswa`.
- **Single Logout (SLO):** Mengakhiri sesi di seluruh modul secara serentak dan aman.

### 2. Modul PPDB (Penerimaan Peserta Didik Baru)
- **Portal Publik & Informasi Gelombang:** Halaman depan sekolah yang menampilkan status periode PPDB aktif, kuota rombel, persyaratan, alur seleksi, dan pengumuman hasil.
- **Formulir Pendaftaran Calon Siswa:** Pengisian data pribadi, asal sekolah, alamat, serta data orang tua/wali dengan validasi format NISN (10 digit) dan NIK (16 digit).
- **Manajemen Berkas Persyaratan:** Unggah 4 dokumen wajib (KK, Akta Kelahiran, Ijazah/SKL, dan Rapor) dengan validasi MIME (PDF/JPG/PNG) dan batas ukuran 2MB.
- **Verifikasi Panitia:** Panel administrator untuk meninjau berkas digital, memberikan catatan koreksi, menyetujui/menolak berkas, dan finalisasi verifikasi.
- **Penetapan Kelulusan & Cetak Bukti:** Penetapan keputusan seleksi (Lulus, Tidak Lulus, Cadangan) serta cetak bukti pendaftaran dan kelulusan ramah kertas A4.

### 3. Sync Service Engine (Integrasi Otomatis PPDB → SIAKAD)
- **Otomatisasi Berbasis Transaksi (`DB::transaction`):** Saat status pendaftar ditetapkan **"LULUS"**, sistem secara otomatis:
  1. Menyalin data pokok calon siswa dan orang tua ke entitas `Siswa` di SIAKAD.
  2. Menerbitkan Nomor Induk Siswa (NIS) unik otomatis (`YYYYXXXX`).
  3. Meng-upgrade role akun SSO dari `calon_siswa` menjadi `siswa`.
- **Idempotensi Sistem:** Mencegah terciptanya duplikasi data siswa apabila proses sinkronisasi terpanggil ulang.
- **Audit Trail (`sync_log`):** Pencatatan riwayat sinkronisasi lengkap dengan waktu eksekusi, status, dan pesan galat.
- **Batch Sync & Retry:** Fitur sinkronisasi massal dan tombol coba lagi untuk data yang tertunda.

### 4. Modul SIAKAD (Akademik Terbatas)
- **Manajemen Rombel & Ploting Siswa:** Pembuatan master rombongan belajar tingkat X, penentuan wali kelas, kapasitas rombel, serta fitur penempatan siswa (ploting individu & massal).
- **Manajemen Guru & Mata Pelajaran:** Pengelolaan data guru dengan penautan akun SSO, daftar mata pelajaran kurikulum, serta alokasi Guru Pengampu per rombel.
- **Portal Guru & Input Nilai:** Lembar entri nilai Tugas, UTS, dan UAS dengan formula perhitungan bobot otomatis:
  $$\text{Nilai Akhir} = 0.3 \times \text{Tugas} + 0.3 \times \text{UTS} + 0.4 \times \text{UAS}$$
  serta deteksi ketuntasan batas KKM secara langsung.
- **Proteksi Otorisasi Guru (`PengampuPolicy`):** Guru hanya diizinkan melihat dan menginput nilai pada rombel/mapel yang sah ditugaskan kepadanya (`HTTP 403 Forbidden` untuk akses ilegal).
- **Portal Siswa SIAKAD:** Siswa aktif login via akun SSO yang sama untuk melihat informasi rombel, nama wali kelas, teman sekelas, dan transkrip nilai rapor semester.

---

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| **Backend Framework** | PHP 8.2+ / Laravel 11.x (Monolith Multi-Modul) |
| **Basis Data** | MySQL 8.0 / 8.4 LTS (`siakad_sman1_tb`) |
| **Protokol SSO / IdP** | OpenID Connect (OIDC) Core 1.0 + OAuth 2.0 + JWT (`firebase/php-jwt`) |
| **Frontend & UI** | Blade Templating Engine + Tailwind CSS v4 + Plus Jakarta Sans |
| **Build Tool** | Vite 8.x |
| **Pengujian (Testing)** | PHPUnit 11.5 / Pest Framework |

---

## Persyaratan Sistem

- PHP >= 8.2 (dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`)
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL Server >= 8.0

---

## Panduan Instalasi & Menjalankan Lokal

### 1. Kloning Repositori
```bash
git clone https://github.com/fredli4qooni/siakad-sman1-tb.git
cd siakad-sman1-tb
```

### 2. Instalasi Dependensi PHP & Node.js
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file konfigurasi environment dan sesuaikan kredensial database lokal Anda:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan pengaturan database di `.env` telah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad_sman1_tb
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Basis Data & Seeding Data Awal
Jalankan migrasi skema tabel beserta database seeder:
```bash
php artisan migrate --seed
```

### 5. Simpan Tautan Penyimpanan Berkas (*Storage Link*)
```bash
php artisan storage:link
```

### 6. Kompilasi Aset Frontend
```bash
npm run build
# Atau untuk mode pengembangan (hot-reload):
npm run dev
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui peramban pada alamat: `http://127.0.0.1:8000`

---

## Akun Pengguna Bawaan (Default Seeders)

| Peran (Role) | Email / Akun | Kata Sandi | Deskripsi Akses |
|---|---|---|---|
| **Super Admin** | `admin@sman1tb.sch.id` | `password` | Akses penuh seluruh modul (PPDB, SIAKAD, Users, Sync Logs) |
| **Operator PPDB** | `operator@sman1tb.sch.id` | `password` | Verifikasi berkas, periode PPDB, penetapan seleksi, dan sync log |
| **Guru (Matematika)** | `guru.ahmad@sman1tb.sch.id` | `password` | Portal Guru: Penugasan mengajar, lembar input nilai X MIPA 1 |
| **Guru (Bahasa)** | `guru.siti@sman1tb.sch.id` | `password` | Portal Guru: Penugasan mengajar kelas X MIPA 2 |
| **Siswa Contoh** | `siswa@sman1tb.sch.id` | `password` | Portal Siswa: Melihat informasi kelas dan transkrip nilai rapor |

---

## Eksekusi Pengujian Otomatis

Seluruh modul telah dilengkapi pengujian otomatis fungsional dan pipeline integrasi:
```bash
php artisan test
```

Hasil eksekusi suite pengujian:
```text
Pass: 28 tests (175 assertions)
Durasi: ~1.6 detik
Status: 100% Passed
```

Rincian file pengujian:
- `tests/Feature/OidcAuthTest.php`: Alur OIDC, authorization code, token JWT, userinfo, login multi-identitas, dan proteksi role.
- `tests/Feature/PpdbFlowTest.php`: Portal publik, pendaftaran, unggah berkas, verifikasi admin, dan seleksi kelulusan.
- `tests/Feature/SyncEngineTest.php`: Eksekusi `SyncService`, transaksi database, idempotensi, audit log, dan retry mechanism.
- `tests/Feature/SiakadFlowTest.php`: Manajemen kelas, ploting siswa, guru & mapel, input nilai dengan auto-grade, dan proteksi otorisasi.
- `tests/Feature/FullIntegrationPipelineTest.php`: Pengujian end-to-end menyeluruh dari registrasi calon siswa sampai melihat rapor di SIAKAD.

---

## Dokumen Acuan Skripsi

Seluruh dokumen perancangan, spesifikasi, dan instrumen pengujian tersedia di folder [`doc/`](doc/):
- [`doc/SRS_PPDB_SIAKAD_SSO.md`](doc/SRS_PPDB_SIAKAD_SSO.md): Spesifikasi Kebutuhan Perangkat Lunak (FR-xx & NFR).
- [`doc/PRD_PPDB_SIAKAD_SSO.md`](doc/PRD_PPDB_SIAKAD_SSO.md): Dokumen Kebutuhan Produk & User Stories.
- [`doc/design.md`](doc/design.md): Panduan Design System, palet warna resmi sekolah, dan prinsip UI flat solid.
- [`doc/BLACK_BOX_TESTING.md`](doc/BLACK_BOX_TESTING.md): Matriks Pengujian Black Box 28 Kasus Uji (Lulus 100%).
- [`doc/UAT_INSTRUMENTS.md`](doc/UAT_INSTRUMENTS.md): Instrumen UAT 15 Responden dengan skor kelayakan 96.35% (Sangat Layak).
- [`doc/USER_MANUAL.md`](doc/USER_MANUAL.md): Panduan Operasional Pengguna (Calon Siswa, Operator, Guru).
- [`doc/DEPLOYMENT_GUIDE.md`](doc/DEPLOYMENT_GUIDE.md): Panduan Deployment Produksi & Server Sekolah.

---

## Lisensi
Dikembangkan untuk keperluan akademik dan operasional di **SMAN 1 Terbanggi Besar**, Lampung Tengah.
Hak Cipta © 2026 SMAN 1 Terbanggi Besar.
