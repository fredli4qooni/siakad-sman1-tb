# Implementation Plan & Milestone Tracker
## Sistem PPDB Terintegrasi SIAKAD dengan SSO (OIDC) — SMAN 1 Terbanggi Besar

> **Metode Pelaksanaan:** Milestone-driven development.  
> **Aturan Eksekusi:** Setiap subtask dicatat statusnya:
> - `[ ]` Belum dikerjakan (Pending)
> - `[/]` Sedang dikerjakan (In Progress)
> - `[x]` Selesai & Terverifikasi (Completed)

---

### Progress Ringkasan

| Milestone | Deskripsi | Target Status | Persentase |
|---|---|:---:|:---:|
| **M1** | Project Setup & Design Foundation | `[x]` | 100% |
| **M2** | Database Schema, Models & Seeders | `[x]` | 100% |
| **M3** | Modul Auth & Identity Provider (OIDC SSO) | `[x]` | 100% |
| **M4** | Modul PPDB (Pendaftaran, Berkas, Verifikasi) | `[x]` | 100% |
| **M5** | Sync Service Engine (PPDB → SIAKAD Pipeline) | `[x]` | 100% |
| **M6** | Modul SIAKAD (Kelas, Pengampu, Input Nilai) | `[x]` | 100% |
| **M7** | Automated Testing & Black Box Validation | `[x]` | 100% |
| **M8** | UI Polish, Panduan Operator & UAT Package | `[x]` | 100% |
| **M9** | Revisi Flow: Daftar Ulang, Validasi Berkas Fisik & Dashboard Siswa SIAKAD | `[x]` | 100% |

---

## Rincian Milestone & Task Checklist

### Milestone 1: Inisialisasi Proyek & Design System Foundation
*Tujuan: Membangun fondasi aplikasi Laravel 11/12, konfigurasi environment, build tool, dan sistem desain flat solid sesuai `design.md`.*

- [x] **Task 1.1: Inisialisasi Laravel**
  - Buat kerangka proyek Laravel di workspace.
  - Setup `.env` (koneksi MySQL `siakad_sman1_tb`, APP_NAME, APP_URL).
  - Verifikasi pembuatan database lokal di MySQL 8.4.
- [x] **Task 1.2: Konfigurasi Frontend & Design System**
  - Install & setup Tailwind CSS dan Vite.
  - Tambahkan font **Plus Jakarta Sans** via Google Fonts.
  - Konfigurasi token warna sekolah di `tailwind.config.js`:
    - Primary (`#0E6026`), Primary Bright (`#039834`), Primary Tint (`#E7F4EA`).
    - Danger (`#C81210`, `#FBEAEA`), Warning (`#6B6200`, `#FBF9D6`).
    - Neutral Ink (`#1C2620`), Border (`#E1E4DE`, `#C9CDC3`), Bg (`#FFFFFF`, `#F3F5F2`).
  - Pastikan aturan keras: *No gradients*, radius bertingkat, clean flat borders.
- [x] **Task 1.3: Kerangka Layout Blade & UI Components**
  - `layouts.guest`: Layout halaman publik / landing pendaftaran PPDB.
  - `layouts.auth`: Layout halaman login SSO terpusat.
  - `layouts.admin`: Layout portal admin/operator (sidebar kiri, header pengguna).
  - `layouts.pendaftar`: Layout portal calon siswa / siswa.
  - `layouts.guru`: Layout portal guru akademik.
  - Komponen dasar: Tombol (primary, sekunder, destruktif), Form Input (dengan error state), Badge status (Diterima, Ditolak, Menunggu, Draft), Alert toast.

---

### Milestone 2: Skema Basis Data, Models & Seeders
*Tujuan: Mengimplementasikan seluruh entitas ERD dari SRS Bab 6 secara presisi dengan konvensi Bahasa Indonesia.*

- [x] **Task 2.1: Migrations Entitas Auth & Pengguna**
  - Tabel `users` (id, nama, email, password, role: `admin|operator|guru|siswa|calon_siswa`, status_aktif).
- [x] **Task 2.2: Migrations Entitas PPDB**
  - Tabel `periode_ppdb` (id, tahun_ajaran, tanggal_buka, tanggal_tutup, kuota, is_aktif).
  - Tabel `pendaftar` (id, user_id, no_pendaftaran, nisn, nik, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, asal_sekolah, alamat, no_hp, periode_id).
  - Tabel `orang_tua` (id, pendaftar_id, nama_ayah, pekerjaan_ayah, nama_ibu, pekerjaan_ibu, no_hp_ortu).
  - Tabel `berkas_pendaftaran` (id, pendaftar_id, jenis_berkas, file_path, status_verifikasi, catatan).
  - Tabel `hasil_seleksi` (id, pendaftar_id, status: `MENUNGGU|LULUS|TIDAK_LULUS|CADANGAN`, catatan, diverifikasi_oleh, tanggal_pengumuman).
- [x] **Task 2.3: Migrations Entitas SIAKAD**
  - Tabel `siswa` (id, user_id, nisn, nis, nama, jenis_kelamin, alamat, kelas_id, tahun_masuk, status_aktif).
  - Tabel `kelas` (id, nama_kelas, tingkat: `X|XI|XII`, tahun_ajaran, wali_kelas_id).
  - Tabel `guru` (id, user_id, nip, nama_lengkap, gelar, no_hp).
  - Tabel `mata_pelajaran` (id, kode_mapel, nama_mapel, kkm).
  - Tabel `pengampu` (id, guru_id, kelas_id, mapel_id, tahun_ajaran).
  - Tabel `nilai` (id, siswa_id, pengampu_id, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, capaian_kompetensi).
- [x] **Task 2.4: Migrations Entitas Integrasi (Sync Log)**
  - Tabel `sync_log` (id, pendaftar_id, siswa_id, user_id, waktu_sinkron, status, detail_payload, error_message).
- [x] **Task 2.5: Eloquent Models & Relasi**
  - Definisi relasi 1:1, 1:N, dan N:M pada seluruh model domain di `app/Models/`.
- [x] **Task 2.6: Database Seeders**
  - Akun awal: 1 Super Admin, 1 Operator PPDB, 3 Guru contoh, 1 Akun Siswa contoh.
  - Data referensi awal: Daftar Mata Pelajaran SMA, Struktur Kelas Tingkat X, Periode PPDB aktif.

---

### Milestone 3: Modul Auth & Identity Provider (OIDC SSO Architecture)
*Tujuan: Menyediakan satu titik otentikasi (IdP) dengan alur standar OIDC & RBAC.*

- [x] **Task 3.1: Setup OAuth2 / OIDC Server**
  - Konfigurasi Laravel Passport / OIDC Server internal.
  - Pendaftaran OAuth Client untuk modul: Client PPDB & Client SIAKAD.
  - Endpoint discovery & token handler (`/oauth/authorize`, `/oauth/token`, `/oauth/userinfo`).
  - Penerbitan JWT Signed `id_token` yang memuat claims profil & peran.
- [x] **Task 3.2: Portal Login & Registrasi Terpusat**
  - Halaman login terpusat (`/auth/login`).
  - Halaman registrasi mandiri akun calon siswa (`/auth/register`).
  - Manajemen sesi SSO: pengecekan sesi aktif di PPDB dan SIAKAD tanpa login ulang.
  - Single Logout (SLO): logout di satu modul memutus sesi SSO secara aman.
- [x] **Task 3.3: Role-Based Access Control (RBAC) & Middleware**
  - Middleware otentikasi & pengecekan peran (`RoleMiddleware`).
  - Laravel Policies untuk proteksi resource: AdminPolicy, GuruPolicy, SiswaPolicy, PendaftarPolicy, NilaiPolicy.

---

### Milestone 4: Modul PPDB (Penerimaan Peserta Didik Baru)
*Tujuan: Membangun siklus pendaftaran online dari pengisian formulir, upload berkas, hingga seleksi kelulusan.*

- [x] **Task 4.1: Portal Publik & Landing PPDB**
  - Informasi periode pendaftaran aktif, kuota, persyaratan, dan alur pendaftaran.
- [x] **Task 4.2: Formulir Pendaftaran Calon Siswa**
  - Form multi-step / tab: Data Pribadi, Data Sekolah Asal, Data Orang Tua/Wali.
  - Form Request Validation (validasi format NISN, NIK, tanggal lahir).
  - Generate nomor pendaftaran unik otomatis.
- [x] **Task 4.3: Unggah & Manajemen Berkas Persyaratan**
  - Upload dokumen (KK, Akta Lahir, Rapor, Ijazah/SKL).
  - Validasi tipe file (PDF/JPG/PNG) & limit ukuran (maks 2MB).
  - Storage terisolasi di disk Laravel (`storage/app/public/berkas_ppdb`).
- [x] **Task 4.4: Dashboard Calon Siswa**
  - Monitoring status verifikasi berkas dan progres pendaftaran.
  - Cetak / unduh Bukti Pendaftaran (PDF format sederhana/HTML print).
  - Tampilan pengumuman kelulusan personal.
- [x] **Task 4.5: Dashboard Admin/Operator PPDB**
  - Manajemen Periode PPDB (buka/tutup, atur kuota penerimaan).
  - Tabel pendaftar dengan filter (status, periode, pencarian nama/NISN).
  - Halaman verifikasi berkas: preview berkas pendaftar, tombol setujui / tolak berkas + catatan koreksi.
  - Penetapan hasil seleksi pendaftar (LULUS / TIDAK LULUS) secara manual per siswa.

---

### Milestone 5: Sync Service Engine (Integrasi PPDB → SIAKAD)
*Tujuan: Menghilangkan input manual dengan menyalin otomatis siswa yang diterima ke SIAKAD.*

- [x] **Task 5.1: Desain & Implementasi `SyncService`**
  - Service class `app/Services/SyncService.php` dengan transaksi database aman (`DB::transaction`).
  - Logika pemindahan data:
    - Ambil data pendaftar & orang tua yang berstatus `LULUS`.
    - Buat entitas `Siswa` baru di SIAKAD.
    - Tautkan `user_id` akun SSO yang sama (role user otomatis di-upgrade dari `calon_siswa` menjadi `siswa`).
    - Catat status transaksi di `sync_log`.
- [x] **Task 5.2: Event-Driven Trigger & Tombol Manual Sync**
  - Event `PendaftarLulusEvent` yang otomatis memanggil `SyncService` saat admin mengubah status ke `LULUS`.
  - Tombol aksi massal (Batch Sync) di admin panel untuk sinkronisasi pendaftar lulus yang belum tersinkron.
- [x] **Task 5.3: Dashboard Monitoring Sync Log**
  - Halaman audit trail `Sync Logs` untuk Operator/Admin.
  - Menampilkan waktu sinkron, nama siswa, status (Berhasil/Gagal), dan error log jika ada.

---

### Milestone 6: Modul SIAKAD (Akademik Terbatas)
*Tujuan: Mengelola pembagian kelas siswa lulus, penugasan guru, dan input nilai.*

- [x] **Task 6.1: Manajemen Kelas & Penempatan Siswa (Admin)**
  - CRUD master data Kelas (Tingkat X, nama rombel, tahun ajaran).
  - Fitur penempatan siswa yang sudah tersinkron ke dalam kelas (ploting rombel).
- [x] **Task 6.2: Manajemen Guru & Penugasan Mengajar (Admin)**
  - CRUD master data Guru & Mata Pelajaran.
  - Alokasi guru pengampu ke kelas & mapel (`tabel pengampu`).
- [x] **Task 6.3: Portal Guru — Input Nilai Siswa**
  - Guru login via SSO dan melihat daftar kelas/mapel yang diampu saja.
  - Form entri nilai (Tugas, UTS, UAS, Nilai Akhir otomatis terhitung).
  - Validasi otorisasi via `NilaiPolicy` / `PengampuPolicy`: guru dilarang keras mengedit nilai di luar kelas/mapel binaannya.
- [x] **Task 6.4: Portal Siswa — Kelas & Nilai Pribadi**
  - Siswa yang diterima login menggunakan akun yang sama saat mendaftar PPDB (SSO).
  - Melihat informasi kelas, wali kelas, dan daftar nilai akademik per semester.

---

### Milestone 7: Pengujian, Validasi Black Box & UAT Readiness
*Tujuan: Memastikan seluruh fungsionalitas lolos uji teknis & siap untuk sidang skripsi serta UAT.*

- [x] **Task 7.1: Automated Tests (Pest / PHPUnit)**
  - Test alur SSO OIDC antar modul (login modul auth -> akses PPDB & SIAKAD).
  - Test siklus pendaftaran PPDB sampai verifikasi.
  - Test eksekusi `SyncService` & verifikasi idempotency / duplikasi data.
  - Test otorisasi guru (hanya bisa input nilai pada kelas yang diampu).
  - Test integrasi pipeline penuh secara end-to-end (`FullIntegrationPipelineTest.php`).
- [x] **Task 7.2: Matriks Pengujian Black Box**
  - Penyusunan tabel pengujian Black Box untuk seluruh ID kebutuhan (`FR-AUTH-01..06`, `FR-PPDB-01..07`, `FR-SIAKAD-01..06`) di `doc/BLACK_BOX_TESTING.md`.
- [x] **Task 7.3: Instrumen UAT (15 Responden)**
  - Penyusunan kuesioner & skenario pengujian untuk 2 operator, 3 guru, dan 10 siswa di `doc/UAT_INSTRUMENTS.md`.

---

### Milestone 8: Polish Antarmuka, Dokumentasi & Deployment Prep
*Tujuan: Memastikan standar desain solid terpenuhi dan sistem siap dioperasikan.*

- [x] **Task 8.1: UI Audit & Mobile Responsiveness**
  - Verifikasi checklist anti-generik `design.md` (no gradient, warna solid kontras tinggi, label sentence case).
  - Pengujian tampilan mobile pada halaman formulir pendaftaran PPDB, navigasi responsif, dan layout drawer.
- [x] **Task 8.2: Dokumentasi Teknis & Panduan Pengguna**
  - Panduan instalasi dan deployment lokal/server sekolah di `doc/DEPLOYMENT_GUIDE.md` & `README.md`.
  - Panduan operasional untuk Operator Sekolah, Siswa, dan Guru di `doc/USER_MANUAL.md`.

---

### Milestone 9: Revisi Alur Sistem (Daftar Ulang, Validasi Berkas Fisik & Dashboard Siswa SIAKAD)
*Tujuan: Mengadaptasi flow bisnis nyata SMAN 1 Terbanggi Besar di mana seleksi awal dilakukan sistem PPDB pemerintah, sekolah menyelenggarakan daftar ulang, penjadwalan verifikasi berkas fisik, pengumuman penerimaan dengan kredensial SIAKAD, dan dashboard SIAKAD khusus data siswa.*

- [x] **Task 9.1: Penyesuaian Skema & Model Verifikasi Fisik**
  - Migrasi penambahan kolom: `no_peserta_ppdb_provinsi`, `tgl_verifikasi_fisik`, `sesi_verifikasi_fisik`, `lokasi_verifikasi_fisik`, `catatan_verifikasi_fisik`, `status_verifikasi_fisik`.
  - Update model `Pendaftar` & `HasilSeleksi` dengan helper methods (`isDijadwalkanFisik()`, `isDiterima()`).
  - Adaptasi `SyncService` untuk mendukung status `DITERIMA` dan sinkronisasi otomatis SIAKAD.
- [x] **Task 9.2: Fitur Admin Validasi & Pengaturan Jadwal Berkas Fisik**
  - Endpoint `POST /admin/ppdb/pendaftar/{pendaftar}/jadwal-fisik` dan request `AturJadwalFisikRequest`.
  - Form penjadwalan fisik pada halaman verifikasi berkas admin (`admin.ppdb.verifikasi`).
  - Penetapan kelulusan/penerimaan resmi (`DITERIMA`) pada halaman seleksi admin.
- [x] **Task 9.3: Pengumuman Diterima & Kredensial SIAKAD di Portal Siswa**
  - Dasbor pendaftar menampilkan kartu selamat datang dan kredensial login SIAKAD (NIS resmi, username, status aktif).
  - Tampilan jadwal validasi berkas fisik (hari/tanggal, sesi, ruangan, daftar berkas wajib dibawa).
  - Cetak bukti registrasi resmi yang mencantumkan jadwal verifikasi berkas fisik dan status penerimaan.
- [x] **Task 9.4: Dashboard SIAKAD Khusus Data Siswa Sendiri**
  - Route `/siakad/siswa/dashboard` dengan action `SiswaPortalController::dashboard`.
  - Tampilan bersih berfokus pada data induk kependidikan siswa (NIS, NISN, NIK, tempat/tgl lahir, alamat, kontak, rombel kelas, wali kelas, kontak orang tua).
- [x] **Task 9.5: Automated Testing & Verifikasi Komprehensif**
  - Feature test untuk penjadwalan fisik admin dan tampilan di sisi siswa.
  - Feature test untuk penetapan `DITERIMA`, eksekusi sinkronisasi SIAKAD, dan akses dashboard data siswa.
  - Seluruh 30 test PHPUnit lulus 100% (192 assertions).
