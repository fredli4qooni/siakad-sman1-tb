# Panduan Operasional Pengguna (User Manual)
## Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan Single Sign-On (SSO OIDC)
### SMAN 1 Terbanggi Besar

---

## 1. Pendahuluan
Buku panduan ini disusun sebagai petunjuk teknis operasional bagi seluruh pengguna sistem di lingkungan **SMAN 1 Terbanggi Besar**, yang terbagi atas tiga kelompok pengguna:
1. **Calon Siswa / Siswa Aktif**
2. **Panitia PPDB & Operator Sekolah (Administrator)**
3. **Bapak/Ibu Guru Mata Pelajaran (Pengampu)**

Sistem ini menggunakan arsitektur **Single Sign-On (SSO)** berbasis **OpenID Connect (OIDC)**, sehingga setiap pengguna cukup memiliki **satu akun** untuk mengakses seluruh modul (PPDB dan SIAKAD) tanpa perlu mendaftar atau login berulang kali.

---

## 2. Panduan Pengguna: Calon Siswa & Siswa Aktif

### 2.1 Pembuatan Akun Baru (Registrasi Calon Siswa)
1. Buka peramban web dan akses alamat resmi: `http://localhost:8000/auth/register` (atau klik tombol **"Daftar Sekarang"** pada halaman utama).
2. Lengkapi formulir pendaftaran akun:
   - **Nama Lengkap:** Masukkan nama sesuai Akta Kelahiran.
   - **NISN:** Masukkan 10 digit Nomor Induk Siswa Nasional yang valid dan belum terdaftar.
   - **Alamat Email:** Masukkan alamat email aktif (digunakan untuk notifikasi dan akun SSO).
   - **Kata Sandi:** Minimal 8 karakter (kombinasi huruf dan angka disarankan).
   - **Konfirmasi Kata Sandi:** Masukkan ulang kata sandi yang sama persis.
3. Klik tombol **"Daftar Akun Baru"**. Sistem akan otomatis membuat akun SSO ber-role `calon_siswa`, menerbitkan profil pendaftaran draft, dan mengarahkan Anda ke formulir pendaftaran.

### 2.2 Pengisian Formulir Pendaftaran PPDB
1. Masuk ke menu **"Formulir Pendaftaran"** (`/pendaftar/formulir`).
2. Lengkapi tiga bagian formulir:
   - **Data Pribadi:** NIK (16 digit sesuai Kartu Keluarga), Tempat/Tanggal Lahir, Jenis Kelamin, Agama, Asal Sekolah SMP/MTs, Alamat Lengkap Tempat Tinggal, dan Nomor WhatsApp/HP aktif.
   - **Data Orang Tua / Wali:** Nama Ayah, Pekerjaan Ayah, Nama Ibu, Pekerjaan Ibu, dan Nomor HP Orang Tua.
3. Klik tombol **"Simpan dan Lanjutkan ke Unggah Berkas"**. Data akan tersimpan dengan status pendaftaran `draft`.

### 2.3 Mengunggah Dokumen Persyaratan Digital
1. Masuk ke menu **"Unggah Berkas"** (`/pendaftar/berkas`).
2. Terdapat **4 dokumen persyaratan wajib**:
   - **Kartu Keluarga (KK)**
   - **Akta Kelahiran**
   - **Ijazah / Surat Keterangan Lulus (SKL) SMP**
   - **Buku Rapor SMP (Semester 1–5)**
3. **Ketentuan File:**
   - Format file yang diterima: **PDF, JPG, JPEG, atau PNG**.
   - Ukuran maksimum file: **2 MB (2.048 KB)** per dokumen.
4. Pilih jenis berkas pada menu dropdown, klik tombol **"Pilih Berkas"**, lalu klik **"Unggah Berkas"**.
5. Ulangi proses hingga keempat dokumen wajib terunggah. Anda dapat melihat pratinjau (*preview*) atau menghapus berkas yang salah unggah sebelum diajukan.
6. Setelah seluruh 4 dokumen terunggah, klik tombol **"Ajukan Verifikasi Berkas"**. Status pendaftaran akan berubah menjadi `Menunggu Verifikasi`.

### 2.4 Cetak Bukti Pendaftaran
1. Buka menu **"Dashboard"** calon siswa.
2. Klik tombol **"Cetak Bukti Pendaftaran"** (`/pendaftar/cetak-bukti`).
3. Halaman ramah cetak format standar sekolah akan terbuka. Klik tombol cetak atau tekan tombol `Ctrl + P` pada keyboard untuk mencetak langsung ke kertas A4 atau menyimpannya sebagai file PDF.

### 2.5 Pengumuman Kelulusan & Masuk ke Modul SIAKAD
1. Buka menu **"Status Kelulusan"** (`/pendaftar/kelulusan`).
2. Jika panitia telah menetapkan keputusan, pengumuman resmi akan tampil:
   - **Lulus:** Ditandai dengan kartu ucapan selamat berwarna hijau sekolah.
   - **Tidak Lulus / Cadangan:** Ditandai dengan informasi resmi dan arahan panitia.
3. **Bagi Siswa yang Dinyatakan LULUS:**
   - Anda **TIDAK PERLU** membuat akun baru di SIAKAD. Sistem telah otomatis menyinkronkan data Anda.
   - Klik tombol **"Masuk ke Portal SIAKAD"** pada layar pengumuman kelulusan.
   - Anda dapat langsung mengakses menu **"Kelas Saya"** (melihat rombel kelas, nama wali kelas, dan teman sekelas) serta menu **"Nilai Akademik"** (melihat transkrip nilai rapor) pada navbar portal.

---

## 3. Panduan Pengguna: Panitia PPDB & Operator Sekolah (Admin)

### 3.1 Masuk ke Panel Administrator
1. Akses halaman login: `http://localhost:8000/auth/login`.
2. Masukkan akun operator sekolah (mis. `admin@sman1tb.sch.id` / password bawaan: `password`).
3. Anda akan diarahkan ke Dasbor Utama Administrator (`/admin/dashboard`).

### 3.2 Mengelola Periode Pendaftaran PPDB
1. Masuk ke menu **PPDB $\to$ Periode PPDB** (`/admin/ppdb/periode`).
2. Untuk membuka gelombang baru:
   - Masukkan **Tahun Ajaran** (mis. `2026/2027`).
   - Tentukan **Kuota Penerimaan Siswa** (mis. `150`).
   - Masukkan **Tanggal Buka** dan **Tanggal Tutup**.
   - Centang opsi **Aktifkan Periode Ini Sekarang**.
   - Klik **"Simpan Periode"**.
3. Gunakan tombol **Toggle** untuk mengaktifkan atau menonaktifkan gelombang pendaftaran sewaktu-waktu.

### 3.3 Memeriksa dan Memverifikasi Berkas Pendaftar
1. Masuk ke menu **PPDB $\to$ Data Pendaftar** (`/admin/ppdb/pendaftar`).
2. Gunakan bilah pencarian atau filter status untuk mencari calon siswa.
3. Klik tombol **"Periksa Berkas"** pada baris pendaftar bersangkutan.
4. Pada halaman rincian verifikasi:
   - Tinjau keabsahan dokumen (KK, Akta Lahir, Ijazah, Rapor) melalui tombol pratinjau.
   - Ubah status per berkas menjadi **Valid** atau **Tidak Valid** (sertakan catatan koreksi jika ditolak).
   - Klik **"Simpan Verifikasi"**.
5. Setelah seluruh 4 berkas dinyatakan valid, klik tombol **"Tetapkan Terverifikasi"** pada kartu finalisasi.

### 3.4 Menetapkan Hasil Seleksi Kelulusan & Memantau Sinkronisasi
1. Masuk ke menu **PPDB $\to$ Hasil Seleksi** (`/admin/ppdb/seleksi`).
2. Pada tabel calon siswa terverifikasi:
   - Pilih keputusan pada kolom Penetapan Keputusan: **Lulus**, **Tidak Lulus**, atau **Menunggu**.
   - Klik tombol **"Simpan"**.
3. **Apa yang Terjadi Secara Otomatis di Latar Belakang?**
   - Saat status diubah menjadi **"Lulus"**, `SyncService` otomatis memicu pembuatan entitas `Siswa` baru di SIAKAD.
   - NIS unik diterbitkan otomatis (`YYYYXXXX`).
   - Role SSO pengguna di-upgrade dari `calon_siswa` menjadi `siswa`.
   - Transaksi dicatat secara atomik pada tabel audit `sync_log`.

### 3.5 Memantau Audit Trail Sync Logs
1. Masuk ke menu **Integrasi Data $\to$ Sync Logs** (`/admin/sync`).
2. Periksa tabel log sinkronisasi:
   - Memuat informasi Nama Siswa, NISN, NIS baru, Waktu Sinkronisasi, dan Status (`BERHASIL` / `GAGAL`).
   - Jika terdapat data pendaftar lulus yang belum tersinkron, klik tombol **"Batch Sync Semua Siswa Lulus"**.
   - Jika terdapat baris berstatus gagal, klik tombol **"Coba Lagi"** pada baris bersangkutan untuk menjalankan *retry engine*.

### 3.6 Mengelola Kelas & Ploting Rombongan Belajar
1. Masuk ke menu **Modul SIAKAD $\to$ Manajemen Kelas** (`/admin/siakad/kelas`).
2. Tambahkan kelas baru: Isi Nama Kelas (mis. `X MIPA 1`), Tingkat (`X`), Tahun Ajaran, Kapasitas (mis. `36`), dan pilih Wali Kelas dari daftar guru.
3. Masuk ke menu **Modul SIAKAD $\to$ Data Siswa** (`/admin/siakad/siswa`).
4. **Ploting Individu:** Pilih rombel kelas pada dropdown di baris siswa lalu klik **"Simpan"**.
5. **Ploting Massal:** Pilih kelas tujuan pada kotak *Ploting Massal*, sistem akan otomatis menempatkan siswa-siswa yang belum memiliki kelas ke rombel tersebut hingga batas kuota kelas terpenuhi.

### 3.7 Mengelola Guru, Mata Pelajaran & Penugasan Mengajar
1. **Data Guru:** Masuk ke menu `/admin/siakad/guru` untuk menambahkan NIP, Nama Lengkap, Gelar, No. HP, dan menautkannya ke akun login pengguna.
2. **Mata Pelajaran:** Masuk ke menu `/admin/siakad/mapel` untuk mengatur Kode Mapel, Nama Mapel, KKM (mis. `75`), dan Kelompok Mapel (Umum/Peminatan).
3. **Penugasan Mengajar:** Masuk ke menu `/admin/siakad/pengampu` untuk mengalokasikan Guru Pengampu pada Rombel Kelas dan Mata Pelajaran yang ditentukan.

---

## 4. Panduan Pengguna: Guru Mata Pelajaran (Pengampu)

### 4.1 Masuk ke Portal Guru (SSO)
1. Akses halaman login SSO: `http://localhost:8000/auth/login`.
2. Masukkan email guru (mis. `guru.ahmad@sman1tb.sch.id` / password: `password`).
3. Sistem akan mengenali peran Anda dan langsung mengarahkan ke **Portal Guru Akademik** (`/guru/dashboard`).

### 4.2 Melihat Alokasi Penugasan Mengajar
1. Pada dasbor guru atau menu **"Kelas & Mapel Diampu"**, Anda dapat melihat daftar rombongan belajar dan mata pelajaran yang resmi ditugaskan oleh pihak kurikulum/operator sekolah.
2. Setiap kartu penugasan menampilkan: Nama Rombel, Mata Pelajaran, Kode Mapel, dan Jumlah Siswa yang terdaftar.

### 4.3 Menginput Nilai Hasil Belajar Siswa
1. Klik tombol **"Input Nilai"** pada kartu rombongan belajar yang ingin dinilai.
2. Lembar penilaian per rombel akan terbuka (`/guru/nilai/{id_pengampu}`):
   - Masukkan **Nilai Tugas** (Skala 0–100).
   - Masukkan **Nilai UTS** (Skala 0–100).
   - Masukkan **Nilai UAS** (Skala 0–100).
   - Isi ringkasan **Capaian Kompetensi** siswa jika diperlukan.
3. **Kalkulasi Nilai Otomatis:**
   Sistem secara otomatis menghitung nilai akhir dengan bobot resmi:
   $$\text{Nilai Akhir} = (30\% \times \text{Tugas}) + (30\% \times \text{UTS}) + (40\% \times \text{UAS})$$
   Siswa yang mencapai nilai akhir $\ge$ batas KKM mata pelajaran (mis. 75) akan otomatis ditandai **"Tuntas"**, dan ditandai **"Remedial"** jika di bawah KKM.
4. Klik tombol **"Simpan Nilai Siswa"** di bagian bawah lembar penilaian.

### 4.4 Keamanan & Pembatasan Otorisasi
- Demi menjaga integritas data akademik, sistem menerapkan kebijakan otorisasi `PengampuPolicy` yang ketat.
- Bapak/Ibu Guru **hanya berhak** menginput dan mengedit nilai untuk rombongan belajar yang sah ditugaskan kepada Anda.
- Segala upaya untuk mengubah nilai kelas milik guru lain akan secara otomatis ditolak oleh sistem dengan pesan **HTTP 403 Forbidden**.

---

## 5. Keluar dari Sistem (Single Logout / SLO)
Untuk mengakhiri sesi secara aman, klik tombol **"Keluar SSO"** pada bilah navigasi atas. Sistem akan menutup sesi Anda pada Identity Provider sekaligus memutus akses Relying Party di modul PPDB dan SIAKAD secara menyeluruh.
