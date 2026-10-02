# Panduan Pengujian Manual End-to-End (E2E Manual Testing Guide)
## Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan Single Sign-On (SSO OIDC)
### SMAN 1 Terbanggi Besar

---

## 1. Persiapan Lingkungan Pengujian (*Pre-requisites*)

Sebelum memulai pengujian manual, pastikan server lokal dan basis data berada dalam status bersih (*fresh*):

### Langkah 1: Reset Basis Data & Isi Data Awal (Seeder)
Buka terminal (PowerShell / Command Prompt) di direktori proyek, lalu jalankan:
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan membuat ulang seluruh tabel dan mengisikan data master: Admin, Operator, Guru, Rombel, Mata Pelajaran, serta Periode PPDB aktif.*

### Langkah 2: Hubungkan Penyimpanan Berkas (*Storage Link*)
Pastikan folder berkas publik terhubung:
```bash
php artisan storage:link
```

### Langkah 3: Kompilasi Aset Frontend
Pastikan aset CSS dan JS telah terkompilasi:
```bash
npm run build
```

### Langkah 4: Jalankan Server Lokal
Jalankan server aplikasi Laravel:
```bash
php artisan serve
```
Aplikasi dapat diakses melalui peramban web (*browser*) pada alamat:
👉 **`http://127.0.0.1:8000`** atau **`http://localhost:8000`**

---

## 2. Kredensial Akun untuk Pengujian

Gunakan akun-akun bawaan berikut selama proses pengujian skenario:

| Peran Akun | Alamat Email | Kata Sandi | Tujuan Pengujian |
|---|---|---|---|
| **Super Admin / Operator** | `admin@sman1tb.sch.id` | `password` | Mengelola periode PPDB, verifikasi berkas, penetapan lulus, sync log, kelas, dan pengampu |
| **Guru A (Pengampu X MIPA 1)** | `guru.ahmad@sman1tb.sch.id` | `password` | Menguji Portal Guru, input nilai Matematika di X MIPA 1, dan kalkulasi otomatis |
| **Guru B (Pengampu X MIPA 2)** | `guru.siti@sman1tb.sch.id` | `password` | Menguji proteksi otorisasi (dilarang mengakses nilai kelas Guru A) |
| **Calon Siswa Baru** | *(Dibuat baru via registrasi)* | *(Sesuai input)* | Menguji pendaftaran, unggah berkas, bukti cetak, dan transisi ke SIAKAD |

---

## 3. Rangkaian Skenario Pengujian Manual (12 Skenario)

---

### Skenario 1: Pengujian Halaman Publik PPDB (Tamu / Guest)
> **Tujuan:** Memastikan pengunjung dapat melihat informasi PPDB tanpa login.

1. Buka peramban (gunakan mode *Incognito* / *Private Window* disarankan).
2. Kunjungi URL: `http://localhost:8000/` atau `http://localhost:8000/ppdb`.
3. **Hal yang Diamati & Diverifikasi:**
   - [ ] Tampil informasi **PPDB SMAN 1 Terbanggi Besar**.
   - [ ] Tampil kartu informasi gelombang pendaftaran aktif (Tahun Ajaran `2026/2027`, kuota penerimaan).
   - [ ] Klik menu **"Alur & Persyaratan"** (`/ppdb/alur`), pastikan daftar berkas persyaratan tampil lengkap (KK, Akta, Ijazah, Rapor).
   - [ ] Klik menu **"Pengumuman"** (`/ppdb/pengumuman`), pastikan tabel pengumuman dapat diakses.

---

### Skenario 2: Registrasi Akun Calon Siswa Baru (SSO IdP)
> **Tujuan:** Memvalidasi pembuatan akun SSO mandiri untuk pendaftar.

1. Pada navigasi atas, klik tombol **"Daftar Akun"** atau buka `http://localhost:8000/auth/register`.
2. Masukkan data uji calon siswa baru:
   - **Nama Lengkap:** `Budi Santoso`
   - **NISN:** `0081234567` *(Tepat 10 digit angka unik)*
   - **Email:** `budi.santoso@gmail.com`
   - **Kata Sandi:** `password123`
   - **Konfirmasi Kata Sandi:** `password123`
3. Klik tombol **"Daftar Akun Baru"**.
4. **Hal yang Diamati & Diverifikasi:**
   - [ ] Sistem berhasil mendaftarkan akun tanpa error.
   - [ ] Pengguna otomatis login ke dalam sesi SSO.
   - [ ] Pengguna otomatis dialihkan (*redirect*) ke halaman formulir pendaftaran: `/pendaftar/formulir`.

---

### Skenario 3: Pengisian Formulir Pendaftaran PPDB
> **Tujuan:** Menguji pengisian data pokok calon siswa dan data orang tua/wali.

1. Anda saat ini berada di halaman: `http://localhost:8000/pendaftar/formulir`.
2. Lengkapi formulir pendaftaran dengan data berikut:
   - **NIK:** `1802011405080002` *(16 digit)*
   - **Tempat Lahir:** `Terbanggi Besar`
   - **Tanggal Lahir:** Pilih tanggal di masa lalu (mis. `14 Mei 2008`)
   - **Jenis Kelamin:** Pilih `Laki-laki`
   - **Agama:** `Islam`
   - **Asal Sekolah:** `SMP Negeri 1 Terbanggi Besar`
   - **Alamat Tinggal:** `Jl. Lintas Sumatera No. 88, Yukum Jaya`
   - **Nomor HP / WhatsApp:** `081234567890`
   - **Nama Ayah Kandung:** `Bambang Santoso`
   - **Pekerjaan Ayah:** `Wiraswasta`
   - **Nama Ibu Kandung:** `Sri Rahayu`
   - **Pekerjaan Ibu:** `Ibu Rumah Tangga`
   - **Nomor HP Orang Tua:** `081298765432`
3. Klik tombol **"Simpan dan Lanjutkan ke Unggah Berkas"**.
4. **Hal yang Diamati & Diverifikasi:**
   - [ ] Formulir tersimpan sukses dengan notifikasi hijau: *"Formulir pendaftaran berhasil disimpan!"*.
   - [ ] Pengguna otomatis dialihkan ke halaman berkas: `/pendaftar/berkas`.

---

### Skenario 4: Pengunggahan 4 Dokumen Persyaratan Digital
> **Tujuan:** Memvalidasi validasi unggah file, pratinjau, dan pengajuan verifikasi.

1. Anda saat ini berada di halaman: `http://localhost:8000/pendaftar/berkas`.
2. **Uji Validasi:** Coba klik tombol **"Lengkapi Seluruh Berkas"** (tombol belum aktif sebelum 4 berkas lengkap).
3. **Unggah Dokumen:** Unggah berkas satu per satu (gunakan file PDF atau gambar JPG/PNG bebas berukuran < 2MB):
   - Dokumen 1: Pilih `Kartu Keluarga (KK)`, pilih file, klik **"Unggah Berkas"**.
   - Dokumen 2: Pilih `Akta Kelahiran`, pilih file, klik **"Unggah Berkas"**.
   - Dokumen 3: Pilih `Ijazah / SKL SMP`, pilih file, klik **"Unggah Berkas"**.
   - Dokumen 4: Pilih `Buku Rapor Semester 1–5`, pilih file, klik **"Unggah Berkas"**.
4. **Uji Pratinjau:** Klik tautan nama file atau tombol mata pada salah satu berkas yang telah diunggah untuk melihat pratinjau dokumen di tab baru.
5. **Pengajuan Verifikasi:** Setelah progress menunjukkan `4 dari 4 dokumen wajib terunggah`, klik tombol hijau: **"Ajukan Verifikasi Berkas"**.
6. **Hal yang Diamati & Diverifikasi:**
   - [ ] Notifikasi sukses: *"Pendaftaran Anda berhasil diajukan! Panitia PPDB akan segera memverifikasi..."*.
   - [ ] Pengguna dialihkan ke halaman **Dashboard Pendaftar** (`/pendaftar/dashboard`).
   - [ ] Badge status pendaftaran berubah menjadi kuning: **"Menunggu Verifikasi"**.

---

### Skenario 5: Cetak Bukti Pendaftaran Calon Siswa
> **Tujuan:** Memastikan lembar bukti registrasi dapat diakses dan siap dicetak ke kertas A4.

1. Pada Dashboard Pendaftar, klik tombol **"Cetak Bukti Pendaftaran"** atau buka `http://localhost:8000/pendaftar/cetak-bukti`.
2. **Hal yang Diamati & Diverifikasi:**
   - [ ] Tampil lembar bukti pendaftaran resmi lengkap dengan Kop Surat SMAN 1 Terbanggi Besar.
   - [ ] Nomor Pendaftaran tercetak rapi (format: `PPDB-2026-XXXX`).
   - [ ] Data biodata, NISN (`0081234567`), asal sekolah, dan data orang tua tercetak akurat.
   - [ ] Tampil daftar checklist 4 berkas yang sudah diunggah.
   - [ ] Klik tombol **"Cetak Dokumen Ini"** -> dialog print browser terbuka dengan layout rapi tanpa elemen navigasi website yang mengganggu.
3. Klik tombol **"Kembali ke Dasbor"**.

---

### Skenario 6: Verifikasi Berkas & Pengaturan Jadwal Validasi Berkas Fisik (Admin Panel)
> **Tujuan:** Memverifikasi dokumen pendaftar secara digital dan menetapkan jadwal kedatangan verifikasi dokumen fisik di sekolah.

1. Di pojok kanan atas, klik tombol **"Keluar SSO"** untuk logout dari akun siswa.
2. Masuk sebagai Administrator:
   - **URL:** `http://localhost:8000/auth/login`
   - **Email:** `admin@sman1tb.sch.id`
   - **Kata Sandi:** `password`
   - Klik **"Masuk ke Sistem"**.
3. Buka menu **PPDB $\to$ Data Pendaftar** (`http://localhost:8000/admin/ppdb/pendaftar`).
4. Cari nama `Budi Santoso` (status saat ini: `Menunggu Verifikasi`).
5. Klik tombol **"Periksa Berkas"**.
6. Pada halaman verifikasi berkas:
   - Periksa keempat dokumen. Pada masing-masing berkas, pilih status **"Valid"**, beri catatan (mis. *"Dokumen jelas dan sah"*), lalu klik **"Simpan"**.
   - Setelah berkas valid, pada kotak *Finalisasi Verifikasi*, klik tombol **"Tetapkan Berkas TERVERIFIKASI"**.
7. **Pengaturan Jadwal Validasi Berkas Fisik:**
   - Gulir ke kartu **"Pengaturan Jadwal Validasi Berkas Fisik"**.
   - Masukkan **Tanggal Verifikasi Fisik:** misal tanggal besok / tanggal berjalan.
   - Pilih **Sesi / Jam Validasi:** `Sesi 1 (08.00 - 10.00 WIB)`.
   - Pilih **Status Verifikasi Fisik:** `Dijadwalkan Hadir`.
   - Lokasi: `Ruang Panitia PPDB / Aula SMAN 1 Terbanggi Besar`.
   - Catatan: `Bawa lembar cetak bukti registrasi, KK asli, Akta asli, dan SKL asli`.
   - Klik tombol **"Simpan Jadwal Validasi Fisik"**.
8. **Hal yang Diamati & Diverifikasi:**
   - [ ] Notifikasi hijau muncul: *"Jadwal validasi berkas fisik untuk Budi Santoso berhasil diperbarui"*.
   - [ ] Jadwal fisik tersimpan di database.

---

### Skenario 7: Siswa Melihat Jadwal Validasi Fisik & Cetak Bukti Registrasi
> **Tujuan:** Memvalidasi tampilan jadwal fisik pada dasbor calon siswa dan lembar cetak bukti registrasi.

1. Logout dari akun Admin dan login kembali dengan akun siswa:
   - **Email:** `budi.santoso@gmail.com`
   - **Kata Sandi:** `password123`
2. Buka **Dashboard Daftar Ulang** (`http://localhost:8000/pendaftar/dashboard`).
3. **Hal yang Diamati & Diverifikasi:**
   - [ ] Tampil kartu informasi warna kuning: **"Jadwal Validasi Berkas Fisik di SMAN 1 Terbanggi Besar"**.
   - [ ] Tercantum Hari/Tanggal, Sesi Waktu (`Sesi 1 (08.00 - 10.00 WIB)`), dan Tempat Verifikasi.
   - [ ] Tercantum instruksi berkas fisik yang wajib dibawa ke sekolah.
4. Klik tombol **"Cetak Bukti Registrasi"** (`/pendaftar/cetak-bukti`).
   - [ ] Pada lembar cetak Section IV, tercantum jadwal validasi fisik dan berkas wajib yang harus dibawa ke sekolah.

---

### Skenario 8: Penetapan DITERIMA & Sinkronisasi Otomatis ke SIAKAD (Admin Panel)
> **Tujuan: (CRITICAL STEP)** Mensimulasikan hasil verifikasi fisik di sekolah dan penetapan resmi menjadi siswa baru yang tersinkron ke SIAKAD.

1. Logout dari siswa, lalu login kembali sebagai Admin (`admin@sman1tb.sch.id`).
2. Buka menu **PPDB $\to$ Hasil Seleksi** (`http://localhost:8000/admin/ppdb/seleksi`).
3. Cari baris siswa `Budi Santoso`.
4. Periksa kolom **Verifikasi Fisik**: tercatat jadwal yang telah ditetapkan.
5. Pada kolom **Penetapan Siswa**:
   - Pilih opsi: **Diterima (Siswa Baru)**
   - Klik tombol **"Simpan"**.
6. **Uji Otomatisasi Sync Engine:**
   - Buka menu **Integrasi Data $\to$ Sync Logs** (`http://localhost:8000/admin/sync`).
   - [ ] Muncul baris log baru untuk `Budi Santoso` dengan status **"BERHASIL"**.
   - [ ] Nomor Induk Siswa (NIS) resmi baru otomatis di-generate oleh sistem.
   - [ ] Peran akun pengguna di-upgrade otomatis dari `calon_siswa` menjadi `siswa`.

---

### Skenario 9: Pengumuman DITERIMA, Kredensial SIAKAD & Dashboard Data Siswa
> **Tujuan:** Memastikan siswa baru melihat status Diterima, kredensial login SIAKAD, dan mengakses dashboard khusus data siswa miliknya sendiri.

1. Logout dari Admin, lalu login kembali sebagai siswa (`budi.santoso@gmail.com`).
2. Masuk ke **Dashboard Daftar Ulang** (`/pendaftar/dashboard`):
   - [ ] Tampil banner pengumuman hijau: **"SELAMAT! ANDA RESMI DITERIMA SEBAGAI SISWA SMAN 1 TERBANGGI BESAR"**.
   - [ ] Tampil kotak **Kredensial Akun SIAKAD**: Nomor Induk Siswa (NIS) resmi, Username SIAKAD, dan status Siswa Aktif.
3. Klik tombol **"Buka Data Induk Siswa (SIAKAD)"** atau menu sidebar **Akademik SIAKAD $\to$ Data Induk Siswa** (`/siakad/siswa/dashboard`).
4. **Hal yang Diamati & Diverifikasi pada Dashboard SIAKAD:**
   - [ ] Tampil Dashboard khusus data siswa: nama siswa, NIS resmi, NISN, NIK, jenis kelamin, TTL, alamat, dan kontak.
   - [ ] Tampil data orang tua siswa (nama ayah, nama ibu, pekerjaan, nomor telepon orang tua).
   - [ ] Tampil status penempatan rombel kelas.
   - [ ] Tidak menampilkan data siswa lain (hanya data miliknya sendiri).

---

### Skenario 8: Manajemen Akademik SIAKAD (Ploting Kelas & Alokasi Guru)
> **Tujuan:** Menempatkan siswa baru ke rombel kelas dan memastikan alokasi guru pengampu aktif.

1. Di panel admin, buka menu **Modul SIAKAD $\to$ Data Siswa** (`http://localhost:8000/admin/siakad/siswa`).
2. Periksa baris siswa `Budi Santoso`:
   - [ ] Siswa otomatis sudah ada di tabel siswa SIAKAD!
   - [ ] Status kelas saat ini: *"Belum Ditempatkan"*.
3. Pada dropdown **Penempatan Kelas** di baris Budi Santoso:
   - Pilih kelas: **X MIPA 1**
   - Klik tombol **"Simpan"**.
4. **Verifikasi Alokasi Guru Pengampu:**
   - Buka menu **Modul SIAKAD $\to$ Penugasan Mengajar** (`http://localhost:8000/admin/siakad/pengampu`).
   - Pastikan terdapat penugasan untuk Guru: **Drs. Ahmad Fauzi** mengampu mata pelajaran **Matematika Wajib** di kelas **X MIPA 1** (Tahun Ajaran `2026/2027`).

---

### Skenario 9: Portal Guru - Entri Nilai & Kalkulasi Bobot Otomatis
> **Tujuan:** Menguji login SSO Guru, input nilai, formula bobot otomatis, dan ambang batas KKM.

1. Logout dari akun Admin (klik tombol **"Keluar"** di pojok bawah sidebar).
2. Masuk sebagai Guru Pengampu:
   - **URL:** `http://localhost:8000/auth/login`
   - **Email:** `guru.ahmad@sman1tb.sch.id`
   - **Kata Sandi:** `password`
   - Klik **"Masuk ke Sistem"**.
3. Anda akan masuk ke **Portal Guru Akademik** (`/guru/dashboard`).
4. Pada kartu tugas mengajar **X MIPA 1 — Matematika Wajib**:
   - Periksa jumlah siswa terdaftar (Budi Santoso sudah termasuk di dalamnya).
   - Klik tombol **"Input dan Kelola Nilai"** (`/guru/nilai/1`).
5. Pada tabel penilaian siswa, cari baris **Budi Santoso**:
   - Masukkan **Nilai Tugas:** `85`
   - Masukkan **Nilai UTS:** `80`
   - Masukkan **Nilai UAS:** `90`
   - Masukkan **Capaian Kompetensi:** `Sangat menguasai materi persamaan kuadrat dan trigonometri dasar.`
6. Klik tombol **"Simpan Nilai Siswa"** di bagian bawah.
7. **Hal yang Diamati & Diverifikasi:**
   - [ ] Notifikasi sukses: *"Nilai siswa berhasil disimpan dan nilai akhir otomatis dihitung."*
   - [ ] **Kalkulasi Otomatis Terverifikasi:**
     $$\text{Nilai Akhir} = (85 \times 0.3) + (80 \times 0.3) + (90 \times 0.4) = 25.5 + 24.0 + 36.0 = \mathbf{85.5}$$
   - [ ] Nilai Akhir tampil angka **85.5**.
   - [ ] Badge status kelulusan KKM (KKM = 75) otomatis menampilkan badge hijau: **"Tuntas"**.

---

### Skenario 10: Pengujian Keamanan & Isolasi Hak Akses Guru (Negative Test)
> **Tujuan:** Memastikan guru dilarang keras mengedit nilai di luar kelas penugasannya (FR-SIAKAD-04).

1. Logout dari akun Guru Ahmad Fauzi.
2. Masuk sebagai Guru lain (Guru B):
   - **Email:** `guru.siti@sman1tb.sch.id`
   - **Kata Sandi:** `password`
3. Anda masuk ke portal Guru Siti Rahmawati.
4. Di bilah alamat browser (*address bar*), secara sengaja ketikkan URL lembar nilai milik Guru Ahmad:
   `http://localhost:8000/guru/nilai/1` lalu tekan **Enter**.
5. **Hal yang Diamati & Diverifikasi:**
   - [ ] **Akses Ditolak!** Sistem menampilkan halaman error **HTTP 403 Forbidden** (*"This action is unauthorized"*).
   - [ ] Guru Siti tidak dapat melihat maupun mengubah nilai siswa di kelas Guru Ahmad.

---

### Skenario 11: Akses Portal Siswa SIAKAD via SSO (Kelas & Transkrip Rapor)
> **Tujuan:** Memvalidasi bahwa siswa yang diterima dapat melihat kelas dan nilai rapornya via SSO.

1. Logout dari sesi guru.
2. Masuk kembali menggunakan akun pendaftar **Budi Santoso** yang dibuat pada Skenario 2:
   - **Email:** `budi.santoso@gmail.com`
   - **Kata Sandi:** `password123`
3. Di Dasbor Pendaftar:
   - [ ] Tampil kartu hijau kelulusan: *"Selamat! Anda dinyatakan LULUS SELEKSI PPDB SMAN 1 Terbanggi Besar"*.
4. Klik tombol **"Buka Portal Akademik (SIAKAD)"** atau klik tab **"Kelas Saya"** di navigasi atas (`/siakad/siswa/kelas`).
5. **Verifikasi Halaman Kelas Saya:**
   - [ ] Nama Siswa: `Budi Santoso`.
   - [ ] Rombel: `X MIPA 1`.
   - [ ] Nama Wali Kelas: `Drs. Ahmad Fauzi, M.Pd.`
   - [ ] Nama Budi Santoso muncul di tabel anggota rombel kelas.
6. Klik tab **"Nilai Akademik"** di navigasi atas (`/siakad/siswa/nilai`).
7. **Verifikasi Transkrip Nilai Rapor:**
   - [ ] Mata Pelajaran: **Matematika Wajib** (Kode: `MAT-X`).
   - [ ] Guru Pengampu: **Drs. Ahmad Fauzi**.
   - [ ] Nilai Tugas: `85`, UTS: `80`, UAS: `90`.
   - [ ] Nilai Akhir: **85.5**.
   - [ ] Status Ketuntasan: Badge hijau **"Tuntas"**.
   - [ ] Rata-rata nilai rapor pada kartu atas otomatis menampilkan **85.5**.

---

### Skenario 12: Pengujian Single Logout (SLO) Terpusat
> **Tujuan:** Memastikan logout memutus seluruh sesi SSO di semua modul secara serentak.

1. Di pojok kanan atas, klik tombol **"Keluar SSO"**.
2. Anda akan dialihkan kembali ke halaman landing PPDB / login.
3. Di bilah alamat browser, coba buka kembali halaman berproteksi secara manual:
   - `http://localhost:8000/siakad/siswa/nilai`
   - `http://localhost:8000/pendaftar/dashboard`
4. **Hal yang Diamati & Diverifikasi:**
   - [ ] Sistem secara otomatis mencegat akses dan mengalihkan Anda ke halaman login: `http://localhost:8000/auth/login`.
   - [ ] Sesi login telah terputus secara sempurna di seluruh modul (PPDB, SIAKAD, dan Identity Provider).

---

## 4. Lembar Checklist Pengujian Manual (Siap Dicentang)

Gunakan tabel checklist di bawah ini saat melakukan pengujian manual di depan dosen pembimbing / penguji:

| No | ID Skenario | Deskripsi Alur Uji | Target Hasil | Hasil Aktual | Kesimpulan |
|:---:|:---:|---|---|:---:|:---:|
| 1 | `E2E-01` | Halaman Publik PPDB | Landing page, gelombang aktif, dan pengumuman dapat diakses tamu | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 2 | `E2E-02` | Registrasi Akun SSO | Calon siswa berhasil daftar akun baru & langsung masuk sesi SSO | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 3 | `E2E-03` | Pengisian Formulir PPDB | Biodata siswa dan orang tua berhasil tersimpan di basis data | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 4 | `E2E-04` | Unggah 4 Berkas Digital | Upload KK, Akta, Ijazah, Rapor tervalidasi & diajukan verifikasi | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 5 | `E2E-05` | Cetak Bukti Registrasi | Halaman cetak bukti registrasi A4 rapi dengan nomor pendaftaran | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 6 | `E2E-06` | Verifikasi Berkas Admin | Admin memvalidasi keabsahan dokumen & menetapkan terverifikasi | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 7 | `E2E-07` | **Penetapan Lulus & Auto Sync** | **Pendaftar lulus otomatis jadi siswa SIAKAD + terbit NIS baru** | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 8 | `E2E-08` | Ploting Kelas Rombel | Admin berhasil menempatkan siswa lulus ke kelas X MIPA 1 | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 9 | `E2E-09` | **Input Nilai & Auto Grade** | **Formula $30\% + 30\% + 40\%$ otomatis menghitung nilai akhir & KKM** | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 10 | `E2E-10` | **Proteksi Otorisasi Guru** | **Guru lain ditolak (403 Forbidden) saat coba akses kelas bukan miliknya** | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 11 | `E2E-11` | **Portal Siswa SIAKAD** | **Siswa lulus login akun SSO sama & melihat kelas serta transkrip nilai** | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
| 12 | `E2E-12` | Single Logout (SLO) | Logout memutus seluruh sesi dan mencegah akses kembali tanpa login | [ &nbsp; ] Lulus &nbsp; [ &nbsp; ] Gagal | VALID / DEFECT |
