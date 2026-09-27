# PRD — Sistem PPDB Terintegrasi SIAKAD dengan SSO (OIDC)
### Studi Kasus: SMAN 1 Terbanggi Besar

**Versi:** 1.0 (Draft)
**Diturunkan dari:** Proposal Skripsi "Pengembangan Sistem Pendaftaran PPDB Terintegrasi Sistem Informasi Akademik (SIAKAD) Menggunakan Teknologi Single Sign-On (SSO)" — M Sigit Pratama S, NPM 2271020114
**Status:** Draft awal, perlu direview sebelum dieksekusi

> **Catatan asumsi kunci** (dikonfirmasi bersama pemilik project):
> - PPDB saat ini berjalan manual/tidak terdigitalisasi penuh; SIAKAD belum ada. Keduanya dibangun sebagai bagian dari project ini.
> - Arsitektur: **satu aplikasi Laravel** dengan dua modul (PPDB & SIAKAD) plus satu modul Auth yang bertindak sebagai **Identity Provider (IdP) OIDC internal**. Modul PPDB dan SIAKAD berperan sebagai *Relying Party* terhadap IdP tersebut, sehingga alur SSO tetap nyata secara teknis walau berjalan dalam satu deployment.
> - Detail teknis arsitektur & requirement fungsional lengkap ada di dokumen **SRS** yang menyertai PRD ini.

---

## 1. Ringkasan Eksekutif

Produk ini adalah aplikasi web tunggal untuk SMAN 1 Terbanggi Besar yang menggabungkan dua kebutuhan sekolah yang selama ini terpisah: **pendaftaran siswa baru (PPDB)** dan **pengelolaan data akademik dasar (SIAKAD)** — biodata siswa, pembagian kelas, dan input nilai. Kedua modul memakai satu sistem login terpusat berbasis **OpenID Connect (OIDC)**, dan data siswa yang dinyatakan lulus PPDB akan otomatis tersalin ke SIAKAD tanpa input ulang manual.

## 2. Latar Belakang Masalah

Setiap tahun, operator sekolah menginput ulang data siswa yang diterima dari proses PPDB ke dalam pencatatan akademik secara manual. Berdasarkan observasi awal:

- Waktu input ulang: **3–5 menit per siswa**
- Volume pendaftar: **350 siswa/tahun (2023–2025)**, naik menjadi **540 siswa (2026)**
- Perkiraan tingkat kesalahan input manual: **5–10%**, terutama pada NISN, nama, dan tanggal lahir
- Pengguna (siswa, guru, admin) harus mengingat akun berbeda-beda untuk tiap layanan sekolah

Masalah ini menyebabkan pemborosan jam kerja operator, risiko data siswa tidak akurat, dan pengalaman pengguna yang tidak nyaman.

## 3. Tujuan Produk

1. Menghilangkan proses input ulang data siswa dari PPDB ke SIAKAD melalui sinkronisasi otomatis.
2. Menyediakan satu akun (SSO) untuk seluruh pengguna — calon siswa/siswa, guru, dan admin/operator — agar tidak perlu login berkali-kali ke sistem berbeda.
3. Meningkatkan akurasi data siswa dan mempercepat proses administrasi sekolah selama musim PPDB.

## 4. Target Pengguna

| Peran | Kebutuhan Utama |
|---|---|
| **Calon Siswa / Siswa** | Daftar PPDB secara online, upload berkas, cek status kelulusan; setelah diterima, login yang sama dipakai untuk melihat kelas & nilai di SIAKAD |
| **Guru** | Login satu akun, input nilai siswa sesuai kelas & mapel yang diampu |
| **Admin/Operator Sekolah** | Mengelola periode PPDB, verifikasi berkas, menetapkan kelulusan, mengelola kelas, mengelola akun pengguna |

## 5. Ruang Lingkup Produk

### 5.1 Termasuk (In Scope)
- Modul PPDB: pendaftaran online, upload berkas, verifikasi, penetapan kelulusan, pengumuman
- Modul SIAKAD (terbatas): biodata siswa, pembagian kelas, input nilai
- Sinkronisasi otomatis data pokok siswa **yang lulus seleksi** (biodata, NISN, alamat, data orang tua) dari PPDB ke SIAKAD
- SSO menggunakan protokol OpenID Connect (OIDC) yang menghubungkan modul PPDB dan SIAKAD
- Tiga level hak akses: Admin/Operator, Calon Siswa/Siswa, Guru

### 5.2 Di Luar Lingkup (Out of Scope)
- Fitur SIAKAD lengkap (jadwal pelajaran, presensi, rapor cetak, dll.) — hanya biodata, kelas, dan nilai
- Pengamanan jaringan tingkat lanjut (mitigasi DDoS, SQL Injection spesifik) — cukup praktik keamanan dasar
- Pengadaan perangkat keras sekolah
- Integrasi dengan sistem PPDB zonasi resmi milik pemerintah (di luar cakupan proposal)

## 6. Fitur Utama (User Stories)

**Calon Siswa / Siswa**
- Sebagai calon siswa, saya ingin mendaftar secara online agar tidak perlu datang ke sekolah untuk mengisi formulir.
- Sebagai calon siswa, saya ingin mengunggah berkas persyaratan agar proses verifikasi bisa dilakukan operator secara digital.
- Sebagai calon siswa, saya ingin melihat status pendaftaran dan pengumuman kelulusan secara real-time.
- Sebagai siswa yang diterima, saya ingin memakai akun pendaftaran yang sama untuk masuk ke SIAKAD tanpa mendaftar ulang.
- Sebagai siswa, saya ingin melihat kelas dan nilai saya di SIAKAD.

**Guru**
- Sebagai guru, saya ingin login dengan satu akun untuk mengakses SIAKAD.
- Sebagai guru, saya ingin menginput nilai siswa hanya untuk kelas dan mata pelajaran yang saya ampu.

**Admin/Operator Sekolah**
- Sebagai admin, saya ingin membuka/menutup periode pendaftaran PPDB beserta kuotanya.
- Sebagai admin, saya ingin memverifikasi berkas pendaftar dan menetapkan status kelulusan.
- Sebagai admin, saya ingin data siswa yang lulus otomatis masuk ke SIAKAD tanpa input ulang.
- Sebagai admin, saya ingin mengelola pembagian kelas dan mengelola akun pengguna (termasuk guru).

## 7. Metrik Keberhasilan

| Metrik | Kondisi Saat Ini | Target |
|---|---|---|
| Waktu input data siswa lulus ke SIAKAD | 3–5 menit/siswa (manual) | Mendekati instan (otomatis via sinkronisasi) |
| Tingkat kesalahan data (NISN/nama/tanggal lahir) | 5–10% | Ditargetkan turun signifikan (diukur saat UAT) |
| Jumlah akun yang harus diingat pengguna | 2+ (jika sistem terpisah) | 1 akun (SSO) |
| Kepuasan pengguna pada UAT (15 responden: 2 operator, 3 guru, 10 siswa) | — | Mayoritas responden menyatakan sistem mudah digunakan |

## 8. Asumsi & Dependensi

- Sekolah menyediakan data referensi awal (daftar mata pelajaran, struktur kelas per tingkat, daftar guru).
- **Kriteria seleksi PPDB belum dijelaskan di proposal** (zonasi/nilai/prestasi/afirmasi) — perlu dikonfirmasi ke pihak sekolah sebelum modul verifikasi/kelulusan dirancang detail. Untuk v1, diasumsikan admin menetapkan status lulus/tidak secara manual per pendaftar (bukan otomatis oleh sistem).
- Kebutuhan notifikasi (email/WhatsApp/SMS) tidak disebutkan di proposal — diasumsikan notifikasi cukup melalui halaman status di sistem (tanpa channel eksternal) untuk v1, bisa ditambah kemudian.
- Infrastruktur hosting & domain sekolah tersedia sebelum tahap deployment.

## 9. Rencana Kerja (Roadmap)

Proposal menyebut penelitian dilaksanakan **Juli 2026**, sementara pengerjaan project ini praktis dimulai sekitar akhir September 2026 — ada baiknya tanggal di proposal disesuaikan sebelum diajukan/disidangkan. Roadmap berikut dibuat relatif per minggu agar bisa disesuaikan dengan tanggal mulai aktual, mengikuti 5 tahap Waterfall dari Bab III proposal:

| Minggu | Tahap | Output |
|---|---|---|
| 1 | Requirement Analysis | Kebutuhan final (SRS disepakati), wawancara operator/guru |
| 2 | System Design | ERD, Use Case & Activity Diagram, desain arsitektur SSO |
| 3–5 | Implementation | Modul Auth (OIDC), Modul PPDB, Modul SIAKAD, sinkronisasi data |
| 6 | Integration & Testing | Black Box Testing, UAT dengan 15 responden |
| 7 | Deployment & Maintenance | Serah terima ke sekolah, dokumentasi, perbaikan hasil UAT |

## 10. Risiko

| Risiko | Dampak | Mitigasi |
|---|---|---|
| Kriteria seleksi PPDB belum jelas | Modul kelulusan bisa salah asumsi | Konfirmasi ke sekolah di tahap Requirement Analysis |
| Waktu penelitian sempit (proposal: 1 bulan) | Fitur tidak selesai semua | Prioritaskan MVP: pendaftaran → verifikasi → sinkronisasi → SSO dasar |
| OIDC custom dalam satu aplikasi bisa dipertanyakan saat sidang ("kenapa perlu SSO kalau satu app?") | Pertanyaan penguji | Siapkan penjelasan: SSO tetap didemonstrasikan nyata via alur OIDC antar modul (RP-IdP), relevan untuk skenario ekspansi ke sistem sekolah lain di masa depan |

## 11. Referensi

Dokumen ini merupakan turunan dari proposal skripsi (Bab I–III) yang diunggah, dan dilengkapi oleh dokumen **SRS_PPDB_SIAKAD_SSO.md** untuk detail teknis.
