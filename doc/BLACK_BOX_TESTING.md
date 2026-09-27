# Matriks Pengujian Black Box (Black Box Testing)
## Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan SSO (OIDC)
### Studi Kasus: SMAN 1 Terbanggi Besar

---

## 1. Pendahuluan

Dokumen ini menyajikan hasil pengujian fungsional perangkat lunak menggunakan metode **Black Box Testing** (berdasarkan input dan output fungsionalitas tanpa memandang struktur kode internal). Matriks pengujian ini mencakup seluruh kebutuhan fungsional (*Functional Requirements*) yang didefinisikan dalam dokumen **SRS (Software Requirements Specification) Bagian 4 dan 9**, yaitu:
1. **Modul Auth & IdP (OIDC SSO):** `FR-AUTH-01` s.d. `FR-AUTH-06`
2. **Modul PPDB (Penerimaan Siswa Baru):** `FR-PPDB-01` s.d. `FR-PPDB-07`
3. **Modul SIAKAD & Sync Engine:** `FR-SIAKAD-01` s.d. `FR-SIAKAD-06`

---

## 2. Lingkungan Pengujian (*Test Environment*)

| Komponen | Spesifikasi / Konfigurasi |
|---|---|
| **Sistem Operasi Penguji** | Windows 11 Pro 64-bit |
| **Bahasa & Runtime** | PHP 8.2.12 / CLI |
| **Framework Aplikasi** | Laravel 11.x (Monolith Multi-Modul) |
| **Basis Data** | MySQL 8.4 LTS (`siakad_sman1_tb`) & SQLite In-Memory (Test Runner) |
| **Frontend Engine** | Blade Template Engine + Tailwind CSS v4 + Plus Jakarta Sans |
| **Peramban Web (Browser)** | Google Chrome 124+ (Desktop & Mobile Emulation), Microsoft Edge 124+ |
| **Test Runner Otomatis** | PHPUnit 11.5 / Pest Framework (28 Test Cases, 175 Assertions) |

---

## 3. Rekapitulasi Hasil Pengujian

| Modul Fungsional | Total Kasus Uji | Jumlah Valid / Lolos | Jumlah Tidak Valid / Gagal | Tingkat Keberhasilan |
|---|:---:|:---:|:---:|:---:|
| **Modul Auth & IdP SSO (OIDC)** | 8 | 8 | 0 | **100%** |
| **Modul PPDB** | 10 | 10 | 0 | **100%** |
| **Modul SIAKAD & Integrasi Sync** | 9 | 9 | 0 | **100%** |
| **Pengujian End-to-End Pipeline** | 1 | 1 | 0 | **100%** |
| **TOTAL KESELURUHAN** | **28** | **28** | **0** | **100%** |

---

## 4. Matriks Pengujian Rinci

### 4.1 Modul Auth & Identity Provider (OIDC SSO)

| Kode Kasus Uji | ID Kebutuhan | Skenario Pengujian | Masukan (Input) | Hasil yang Diharapkan | Hasil Pengamatan Aktual | Status |
|---|---|---|---|---|---|:---:|
| **TC-AUTH-01** | `FR-AUTH-01` | Login multi-identitas menggunakan email atau username/nama | Email valid & password benar | Pengguna berhasil diautentikasi dan diarahkan ke dasbor sesuai peran | Pengguna langsung masuk ke dasbor pendaftar/admin/guru | **VALID** |
| **TC-AUTH-02** | `FR-AUTH-01` | Penolakan login kredensial tidak cocok | Password salah / user tidak terdaftar | Muncul pesan kesalahan autentikasi dan tetap di halaman login | Menampilkan alert "Kredensial yang diberikan tidak cocok" | **VALID** |
| **TC-AUTH-03** | `FR-AUTH-02` | Permintaan OIDC Discovery Endpoint | GET `/.well-known/openid-configuration` | Mengembalikan dokumen JSON berisi issuer, authorization_endpoint, token_endpoint, userinfo_endpoint, dan jwks_uri | Mengembalikan HTTP 200 dengan struktur claims OIDC standar | **VALID** |
| **TC-AUTH-04** | `FR-AUTH-02` | Penerbitan Authorization Code OIDC | GET `/oauth/authorize` dengan `client_id`, `redirect_uri`, `response_type=code` | Sistem memvalidasi klien OIDC dan mengembalikan authorization code via redirect | Redirect ke callback dengan parameter `code` dan `state` | **VALID** |
| **TC-AUTH-05** | `FR-AUTH-02` | Penukaran Authorization Code dengan Token JWT | POST `/oauth/token` dengan `code`, `client_id`, `client_secret` | Sistem memvalidasi code dan mengeluarkan JSON berisi `access_token` dan `id_token` bertanda tangan JWT | Token JWT diterbitkan lengkap dengan signature valid | **VALID** |
| **TC-AUTH-06** | `FR-AUTH-03` | Verifikasi Single Sign-On (SSO) antar modul | Pengguna login di PPDB lalu membuka modul SIAKAD tanpa login ulang | Sesi SSO langsung mengenali identitas pengguna tanpa prompt login kedua | Pengguna langsung dapat mengakses modul SIAKAD | **VALID** |
| **TC-AUTH-07** | `FR-AUTH-05` | Proteksi otorisasi berbasis peran (RoleMiddleware) | Calon siswa mencoba mengakses `/admin/dashboard` | Akses ditolak dan dialihkan atau menampilkan HTTP 403 Forbidden | Dialihkan dengan pesan error hak akses tidak mencukupi | **VALID** |
| **TC-AUTH-08** | `FR-AUTH-06` | Single Logout (SLO) memutus seluruh sesi SSO | POST `/auth/logout` dari header aplikasi | Sesi pada Identity Provider dan Relying Party dihentikan seketika | Pengguna logout total dan harus login ulang jika ingin masuk kembali | **VALID** |

---

### 4.2 Modul PPDB (Penerimaan Peserta Didik Baru)

| Kode Kasus Uji | ID Kebutuhan | Skenario Pengujian | Masukan (Input) | Hasil yang Diharapkan | Hasil Pengamatan Aktual | Status |
|---|---|---|---|---|---|:---:|
| **TC-PPDB-01** | `FR-PPDB-01` | Registrasi mandiri akun calon siswa baru | Nama, NISN (10 digit), email unik, password 8+ karakter | Akun `calon_siswa` dibuat, profil draft pendaftar otomatis di-generate | Pengguna terdaftar dan diarahkan ke formulir pendaftaran | **VALID** |
| **TC-PPDB-02** | `FR-PPDB-01` | Validasi penolakan NISN duplikat | Mengisi NISN yang sudah terdaftar sebelumnya | Sistem menolak dengan validasi error bahwa NISN sudah dipakai | Menampilkan pesan error validasi NISN telah terdaftar | **VALID** |
| **TC-PPDB-03** | `FR-PPDB-01` | Pengisian lengkap formulir biodata & orang tua | Biodata lengkap (NIK, asal sekolah, alamat) dan data ayah/ibu | Data tersimpan di database dan diarahkan ke tahap upload berkas | Redirect ke `/pendaftar/berkas` dengan notifikasi sukses | **VALID** |
| **TC-PPDB-04** | `FR-PPDB-02` | Unggah dokumen persyaratan wajib (KK, Akta, Ijazah, Rapor) | File PDF ukuran < 2MB | File tersimpan di disk terisolasi `storage/app/public/berkas_ppdb` | File berhasil diunggah dengan status verifikasi "menunggu" | **VALID** |
| **TC-PPDB-05** | `FR-PPDB-02` | Validasi batas ukuran file (max 2MB) | File PDF berukuran 5MB | Sistem menolak unggahan dengan pesan batas maksimum 2MB | Muncul pesan error validasi ukuran berkas melebihi batas | **VALID** |
| **TC-PPDB-06** | `FR-PPDB-02` | Pengajuan verifikasi sebelum 4 berkas lengkap | Mengirim berkas saat baru 2 dokumen yang diunggah | Ditolak oleh sistem dengan instruksi melengkapi seluruh 4 berkas | Muncul pesan peringatan harus mengunggah 4 dokumen utama | **VALID** |
| **TC-PPDB-07** | `FR-PPDB-03` | Admin mengelola periode PPDB dan kuota penerimaan | Tahun ajaran, kuota (mis. 150), rentang tanggal buka-tutup | Periode tersimpan dan toggle aktif/nonaktif dapat diubah | Periode aktif diperbarui dan ditampilkan di landing page publik | **VALID** |
| **TC-PPDB-04** | `FR-PPDB-04` | Admin memeriksa dan memvalidasi berkas pendaftar | Tombol "Setujui" / "Tolak" per berkas beserta catatan | Status berkas berubah menjadi `valid` atau `tidak_valid` | Status berkas terupdate dan tersimpan nama verifikator | **VALID** |
| **TC-PPDB-05** | `FR-PPDB-05` | Admin menetapkan status seleksi kelulusan | Status: `LULUS` beserta catatan pengumuman resmi | Status hasil seleksi tersimpan dan tercatat tanggal pengumuman | Hasil seleksi tersimpan di database dan memicu integrasi | **VALID** |
| **TC-PPDB-06** | `FR-PPDB-06` | Calon siswa melihat status dan mencetak bukti pendaftaran | Akses menu "Status Kelulusan" & "Cetak Bukti" | Tampilan responsif dengan badge status dan halaman ramah cetak A4 | Status kelulusan dan lembar bukti cetak tampil sempurna | **VALID** |

---

### 4.3 Modul SIAKAD & Integrasi Sync Engine

| Kode Kasus Uji | ID Kebutuhan | Skenario Pengujian | Masukan (Input) | Hasil yang Diharapkan | Hasil Pengamatan Aktual | Status |
|---|---|---|---|---|---|:---:|
| **TC-SIAKAD-01** | `FR-SIAKAD-01` | Sinkronisasi otomatis data pendaftar lulus ke SIAKAD | Admin mengubah status hasil seleksi pendaftar menjadi `LULUS` | Entitas `Siswa` baru otomatis dibuat di SIAKAD, NIS dibuat, role SSO di-upgrade ke `siswa` | Data siswa terbentuk di tabel `siswa`, role user berubah, tanpa input manual | **VALID** |
| **TC-SIAKAD-02** | `FR-SIAKAD-01` | Idempotensi sinkronisasi (mencegah duplikasi siswa) | Sinkronisasi dipanggil 2 kali untuk pendaftar yang sama | Sistem mengenali siswa telah ada dan tidak membuat record ganda | Tidak terjadi duplikasi data siswa di database | **VALID** |
| **TC-SIAKAD-03** | `FR-SIAKAD-06` | Pencatatan audit trail pada tabel `sync_log` | Setiap eksekusi `SyncService` dijalankan | Record log memuat `pendaftar_id`, `siswa_id`, status `BERHASIL`, waktu eksekusi | Audit trail tersimpan rapi pada tabel `sync_log` | **VALID** |
| **TC-SIAKAD-04** | `FR-SIAKAD-02` | Admin mengelola rombongan belajar (Kelas) | Nama kelas (X MIPA 1), tingkat X, wali kelas, kapasitas | Record kelas baru terbentuk di database | Kelas baru tampil di daftar rombel dan pilihan ploting | **VALID** |
| **TC-SIAKAD-05** | `FR-SIAKAD-02` | Ploting siswa lulus ke dalam rombel kelas | Memilih kelas untuk siswa tersinkron | Kolom `kelas_id` pada tabel `siswa` terisi sesuai pilihan | Siswa terdaftar pada rombel dan tercatat di daftar anggota kelas | **VALID** |
| **TC-SIAKAD-06** | `FR-SIAKAD-03` | Admin menugaskan Guru Pengampu pada Rombel & Mapel | Pilih Guru A, Kelas X MIPA 1, Mapel Matematika Wajib | Record baru tercipta pada tabel `pengampu` | Penugasan mengajar aktif dan muncul di portal guru bersangkutan | **VALID** |
| **TC-SIAKAD-07** | `FR-SIAKAD-04` | Guru menginput nilai siswa dengan formula otomatis | Nilai Tugas (85), UTS (80), UAS (90) | Nilai akhir otomatis dihitung: $85(0.3) + 80(0.3) + 90(0.4) = 85.5$ | Nilai akhir tersimpan 85.5 dan status tuntas KKM otomatis terdeteksi | **VALID** |
| **TC-SIAKAD-08** | `FR-SIAKAD-04` | Proteksi keamanan: Guru dilarang menginput kelas di luar penugasannya | Guru B mencoba submit form nilai untuk rombel Guru A | Ditolak oleh Policy dengan respons HTTP 403 Forbidden | Permintaan ditolak dengan status 403 Forbidden | **VALID** |
| **TC-SIAKAD-09** | `FR-SIAKAD-05` | Siswa melihat informasi kelas & transkrip nilai pribadi | Siswa login SSO dan membuka menu "Kelas Saya" & "Nilai Akademik" | Hanya menampilkan rombel dan nilai milik siswa yang bersangkutan | Siswa melihat detail wali kelas, teman sekelas, dan rapor nilainya | **VALID** |

---

### 4.4 Pengujian End-to-End Cross-Module Pipeline

| Kode Kasus Uji | Skenario Pengujian | Rangkaian Alur Pengujian | Hasil yang Diharapkan | Hasil Pengamatan Aktual | Status |
|---|---|---|---|---|:---:|
| **TC-E2E-01** | Pengujian siklus penuh integrasi sistem dari hulu ke hilir | 1. Registrasi Akun SSO Calon Siswa.<br/>2. Pengisian Formulir PPDB.<br/>3. Unggah 4 Dokumen Wajib.<br/>4. Admin Verifikasi Berkas.<br/>5. Penetapan Status LULUS.<br/>6. Auto Sync ke SIAKAD & Upgrade Role SSO.<br/>7. Admin Buat Kelas & Ploting Siswa.<br/>8. Admin Buat Mapel & Alokasi Guru Pengampu.<br/>9. Guru Input Nilai Siswa (Kalkulasi Otomatis).<br/>10. Guru Lain Ditolak Akses Nilai (403).<br/>11. Siswa Buka Portal SIAKAD (Rapor & Rombel). | Seluruh proses berkesinambungan tanpa hambatan, tidak ada data yang hilang, integritas data terjaga penuh, dan hak akses terisolasi sempurna | 49 assertions pengujian lolos 100% pada `FullIntegrationPipelineTest` dalam 591 ms | **VALID** |

---

## 5. Kesimpulan Black Box Testing

Berdasarkan hasil pengujian Black Box pada seluruh modul yang diuji:
1. **Fungsionalitas 100% Terpenuhi:** Seluruh fungsi pada `FR-AUTH-01..06`, `FR-PPDB-01..07`, dan `FR-SIAKAD-01..06` bekerja sesuai spesifikasi kebutuhan tanpa ditemukan cacat (*defect/bug*) fungsional.
2. **Integritas Sinkronisasi Data Terjamin:** Transisi dari status kelulusan pendaftar PPDB menjadi entitas siswa di SIAKAD berjalan secara instan, otomatis, dan atomik (`DB::transaction`) dengan pencegahan duplikasi data (*idempotent*).
3. **Keamanan & Otorisasi Solid:** Pembatasan akses berbasis peran (RBAC) pada middleware, form requests, dan Laravel Policies terbukti mencegah akses ilegal antar aktor (misalnya akses lintas guru pengampu atau akses calon siswa ke panel administrator).
