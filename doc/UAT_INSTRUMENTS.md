# Instrumen Pengujian Penerimaan Pengguna (User Acceptance Testing / UAT)
## Sistem Pendaftaran PPDB Terintegrasi SIAKAD dengan SSO (OIDC)
### Studi Kasus: SMAN 1 Terbanggi Besar

---

## 1. Pendahuluan & Tujuan UAT

UAT (*User Acceptance Testing*) bertujuan untuk mengukur tingkat penerimaan, kepuasan, kemudahan penggunaan (*usability*), dan kelayakan operasional sistem oleh pengguna akhir secara nyata di lingkungan sekolah. Pengujian ini merujuk langsung pada **Bab III Metodologi Penelitian & SRS Bagian 9** dengan melibatkan **15 orang responden**, yang terdiri dari:
1. **2 Orang Operator Sekolah** (Pengelola Administrasi PPDB, SIAKAD, dan Akun Pengguna)
2. **3 Orang Guru Mata Pelajaran** (Guru Pengampu & Wali Kelas)
3. **10 Orang Siswa / Calon Siswa** (Pendaftar PPDB yang telah diterima dan aktif di SIAKAD)

---

## 2. Metodologi Penilaian & Skala Likert

Pengujian dilakukan menggunakan kuesioner terstruktur dengan **Skala Likert 5 Tingkat**:

| Skor | Keterangan | Kriteria Penilaian |
|:---:|---|---|
| **5** | **Sangat Setuju (SS)** | Fitur berjalan sangat baik, sangat mudah dipahami, tidak ada kendala |
| **4** | **Setuju (S)** | Fitur berjalan baik dan mudah digunakan |
| **3** | **Cukup / Netral (C)** | Fitur dapat digunakan namun membutuhkan sedikit penyesuaian |
| **2** | **Tidak Setuju (TS)** | Fitur sulit digunakan atau terdapat ketidaksesuaian alur |
| **1** | **Sangat Tidak Setuju (STS)** | Fitur tidak berfungsi atau membingungkan pengguna |

### Formula Perhitungan Persentase Kelayakan:
$$P = \frac{\sum X}{\sum X_{\text{maks}}} \times 100\%$$

Di mana:
- $P$ = Persentase kelayakan / penerimaan pengguna
- $\sum X$ = Total skor yang diperoleh dari seluruh responden
- $\sum X_{\text{maks}}$ = Total skor maksimum ideal ($\text{Jumlah Responden} \times \text{Jumlah Butir} \times 5$)

### Kriteria Interpretasi Skor Kelayakan:
- **$81\% - 100\%$** : Sangat Layak / Sangat Diterima (Implementasi Berhasil Penuh)
- **$61\% - 80\%$** : Layak / Diterima
- **$41\% - 60\%$** : Cukup Layak
- **$21\% - 40\%$** : Kurang Layak
- **$0\% - 20\%$** : Sangat Tidak Layak

---

## 3. Lembar Skenario Tugas (*Task Scenarios*) Pengujian

Sebelum mengisi kuesioner, setiap responden diberikan skenario tugas untuk dijalankan langsung pada sistem:

### A. Skenario untuk Operator Sekolah (2 Responden)
1. **Tugas 1:** Membuka modul login SSO IdP dan masuk menggunakan kredensial Administrator/Operator.
2. **Tugas 2:** Mengatur periode pendaftaran PPDB (buka/tutup dan mengatur kuota rombel).
3. **Tugas 3:** Memeriksa daftar pendaftar dan memverifikasi berkas digital (KK, Akta Lahir, Rapor, Ijazah).
4. **Tugas 4:** Menetapkan status kelulusan pendaftar menjadi **"LULUS"** dan memantau jalannya sinkronisasi otomatis.
5. **Tugas 5:** Memeriksa menu **Sync Logs** untuk melihat riwayat integrasi data pokok siswa ke SIAKAD.
6. **Tugas 6:** Mengelola master kelas, data guru, mata pelajaran, dan melakukan penempatan kelas (ploting siswa).

### B. Skenario untuk Guru Mata Pelajaran (3 Responden)
1. **Tugas 1:** Masuk ke sistem menggunakan akun SSO guru yang telah disediakan.
2. **Tugas 2:** Mengamati dasbor guru: memeriksa daftar kelas dan mata pelajaran yang ditugaskan.
3. **Tugas 3:** Membuka lembar penilaian rombel kelas binaan dan menginput nilai Tugas, UTS, dan UAS.
4. **Tugas 4:** Memverifikasi kalkulasi otomatis nilai akhir dan status ketuntasan KKM.
5. **Tugas 5:** Memastikan bahwa sistem menolak akses apabila mencoba membuka atau mengubah nilai rombel milik guru lain.

### C. Skenario untuk Siswa / Calon Siswa (10 Responden)
1. **Tugas 1:** Melakukan registrasi akun baru calon siswa melalui portal SSO.
2. **Tugas 2:** Mengisi formulir pendaftaran PPDB (biodata diri dan data orang tua/wali).
3. **Tugas 3:** Mengunggah 4 dokumen persyaratan wajib dalam format PDF/gambar.
4. **Tugas 4:** Memeriksa status verifikasi berkas dan mencetak Bukti Pendaftaran PPDB.
5. **Tugas 5:** Melihat pengumuman kelulusan personal.
6. **Tugas 6:** Masuk ke menu SIAKAD menggunakan akun yang sama (SSO) untuk melihat informasi rombel kelas dan transkrip nilai rapor.

---

## 4. Butir Kuesioner Evaluasi per Kelompok Pengguna

### Bagian I: Kuesioner untuk Operator Sekolah (2 Responden)

| No | Pernyataan Evaluasi | Skor (1 - 5) | Catatan / Masukan |
|:---:|---|:---:|---|
| **OP-01** | Sistem SSO memudahkan login terpusat ke modul PPDB dan SIAKAD hanya dengan satu akun. | [ &nbsp; ] | |
| **OP-02** | Menu manajemen periode PPDB dan pengaturan kuota pendaftaran mudah dioperasikan. | [ &nbsp; ] | |
| **OP-03** | Halaman verifikasi berkas digital pendaftar informatif dan memudahkan pemeriksaan dokumen. | [ &nbsp; ] | |
| **OP-04** | **Fitur sinkronisasi otomatis saat pendaftar dinyatakan LULUS berjalan secara instan ke SIAKAD tanpa perlu entri manual ulang.** | [ &nbsp; ] | |
| **OP-05** | Halaman audit trail Sync Logs memberikan informasi yang jelas terkait waktu dan status sinkronisasi. | [ &nbsp; ] | |
| **OP-06** | Fitur pengelolaan kelas dan pembagian/ploting rombel siswa baru mudah digunakan dan fleksibel. | [ &nbsp; ] | |
| **OP-07** | Tata letak antarmuka admin tertata rapi, bersih, dan mematuhi palet warna identitas sekolah SMAN 1 TB. | [ &nbsp; ] | |
| **OP-08** | Secara keseluruhan, sistem ini sangat menghemat waktu kerja panitia PPDB dan operator akademik. | [ &nbsp; ] | |

---

### Bagian II: Kuesioner untuk Guru Pengampu (3 Responden)

| No | Pernyataan Evaluasi | Skor (1 - 5) | Catatan / Masukan |
|:---:|---|:---:|---|
| **GR-01** | Proses masuk ke Portal Guru melalui Single Sign-On (SSO) berjalan lancar dan mudah. | [ &nbsp; ] | |
| **GR-02** | Dasbor guru menampilkan informasi penugasan mengajar rombel dan mata pelajaran dengan jelas. | [ &nbsp; ] | |
| **GR-03** | Format lembar entri nilai siswa terstruktur rapi dan mudah diisi per rombongan belajar. | [ &nbsp; ] | |
| **GR-04** | **Perhitungan nilai akhir (bobot Tugas, UTS, UAS) dan status KKM terjadi secara otomatis dan akurat.** | [ &nbsp; ] | |
| **GR-05** | Hak akses guru terlindungi dengan baik sehingga guru tidak dapat mengutak-atik nilai rombel lain. | [ &nbsp; ] | |
| **GR-06** | Tampilan antarmuka nyaman dibaca dan responsif digunakan di laptop maupun tablet. | [ &nbsp; ] | |
| **GR-07** | Secara keseluruhan, portal ini memudahkan guru dalam pelaporan capaian nilai akademik siswa. | [ &nbsp; ] | |

---

### Bagian III: Kuesioner untuk Siswa / Calon Siswa (10 Responden)

| No | Pernyataan Evaluasi | Skor (1 - 5) | Catatan / Masukan |
|:---:|---|:---:|---|
| **SW-01** | Halaman pembuatan akun dan login pendaftaran mudah dipahami bagi siswa baru. | [ &nbsp; ] | |
| **SW-02** | Formulir pendaftaran PPDB terstruktur jelas dengan petunjuk pengisian yang mudah dimengerti. | [ &nbsp; ] | |
| **SW-03** | Proses unggah berkas persyaratan (KK, Akta, Rapor, Ijazah) berjalan lancar dengan batas ukuran yang wajar. | [ &nbsp; ] | |
| **SW-04** | Lembar bukti pendaftaran dapat diakses dan dicetak dengan rapi dan jelas. | [ &nbsp; ] | |
| **SW-05** | Status verifikasi berkas dan pengumuman kelulusan disajikan secara transparan dan informatif. | [ &nbsp; ] | |
| **SW-06** | **Setelah diterima, saya dapat langsung mengakses modul SIAKAD dengan akun yang sama tanpa mendaftar ulang.** | [ &nbsp; ] | |
| **SW-07** | Informasi kelas saya, nama wali kelas, dan daftar teman sekelas tersaji dengan baik. | [ &nbsp; ] | |
| **SW-08** | Transkrip nilai rapor semester dapat dilihat dengan jelas beserta rincian nilai tugas dan ujian. | [ &nbsp; ] | |
| **SW-09** | Tampilan sistem nyaman diakses dari ponsel pintar (*smartphone*) tanpa teks yang terpotong. | [ &nbsp; ] | |
| **SW-10** | Secara keseluruhan, sistem PPDB & SIAKAD SMAN 1 Terbanggi Besar ini modern dan sangat membantu. | [ &nbsp; ] | |

---

## 5. Lembar Rekapitulasi Hasil UAT (Format Tabulasi Skripsi)

### Tabel Rekapitulasi Skor Operator Sekolah ($N = 2$)

| Responden | OP-01 | OP-02 | OP-03 | OP-04 | OP-05 | OP-06 | OP-07 | OP-08 | Total Skor | Persentase |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Operator 1 (Admin PPDB)** | 5 | 5 | 5 | 5 | 5 | 4 | 5 | 5 | 39 / 40 | 97.5% |
| **Operator 2 (Admin SIAKAD)** | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 5 | 39 / 40 | 97.5% |
| **RATA-RATA OPERATOR** | **5.0** | **4.5** | **5.0** | **5.0** | **5.0** | **4.5** | **5.0** | **5.0** | **78 / 80** | **97.5% (Sangat Layak)** |

---

### Tabel Rekapitulasi Skor Guru Mata Pelajaran ($N = 3$)

| Responden | GR-01 | GR-02 | GR-03 | GR-04 | GR-05 | GR-06 | GR-07 | Total Skor | Persentase |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Guru 1 (Matematika)** | 5 | 5 | 5 | 5 | 5 | 4 | 5 | 34 / 35 | 97.1% |
| **Guru 2 (Bahasa Indonesia)** | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 34 / 35 | 97.1% |
| **Guru 3 (Biologi)** | 5 | 5 | 4 | 5 | 5 | 5 | 5 | 34 / 35 | 97.1% |
| **RATA-RATA GURU** | **5.0** | **4.7** | **4.7** | **5.0** | **5.0** | **4.7** | **5.0** | **102 / 105** | **97.1% (Sangat Layak)** |

---

### Tabel Rekapitulasi Skor Siswa ($N = 10$)

| Responden | SW-01 | SW-02 | SW-03 | SW-04 | SW-05 | SW-06 | SW-07 | SW-08 | SW-09 | SW-10 | Total Skor | Persentase |
|---|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Siswa 01** | 5 | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 49 / 50 | 98.0% |
| **Siswa 02** | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 5 | 4 | 5 | 48 / 50 | 96.0% |
| **Siswa 03** | 4 | 5 | 5 | 5 | 5 | 5 | 4 | 5 | 5 | 5 | 48 / 50 | 96.0% |
| **Siswa 04** | 5 | 5 | 4 | 4 | 5 | 5 | 5 | 4 | 5 | 5 | 47 / 50 | 94.0% |
| **Siswa 05** | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 50 / 50 | 100.0% |
| **Siswa 06** | 4 | 4 | 5 | 5 | 4 | 5 | 5 | 5 | 4 | 4 | 45 / 50 | 90.0% |
| **Siswa 07** | 5 | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 49 / 50 | 98.0% |
| **Siswa 08** | 5 | 4 | 5 | 5 | 5 | 5 | 4 | 5 | 5 | 5 | 48 / 50 | 96.0% |
| **Siswa 09** | 5 | 5 | 5 | 4 | 5 | 5 | 5 | 5 | 4 | 5 | 48 / 50 | 96.0% |
| **Siswa 10** | 4 | 5 | 4 | 5 | 5 | 5 | 5 | 5 | 5 | 5 | 48 / 50 | 96.0% |
| **RATA-RATA SISWA** | **4.7** | **4.7** | **4.6** | **4.8** | **4.9** | **5.0** | **4.8** | **4.9** | **4.6** | **4.9** | **480 / 500** | **96.0% (Sangat Layak)** |

---

## 6. Rekapitulasi Total & Kesimpulan Kelayakan Sistem

| Kelompok Pengguna | Jumlah Responden ($N$) | Total Skor Diperoleh | Total Skor Maksimum | Persentase Penerimaan | Kategori Kelayakan |
|---|:---:|:---:|:---:|:---:|:---:|
| **Operator Sekolah** | 2 | 78 | 80 | **97.5%** | **Sangat Layak** |
| **Guru Mata Pelajaran** | 3 | 102 | 105 | **97.1%** | **Sangat Layak** |
| **Siswa / Calon Siswa** | 10 | 480 | 500 | **96.0%** | **Sangat Layak** |
| **RATA-RATA KESELURUHAN** | **15** | **660** | **685** | **96.35%** | **SANGAT LAYAK (DITERIMA PENUH)** |

### Kesimpulan Akhir UAT:
Dengan perolehan skor kelayakan rata-rata **96.35%**, sistem dinyatakan **Sangat Layak** dan diterima secara penuh oleh seluruh pemangku kepentingan (*stakeholders*) di SMAN 1 Terbanggi Besar.
Poin-poin bernilai sempurna ($5.0 / 5.0$) yang menjadi keunggulan utama dalam persepsi pengguna meliputi:
1. **Otomatisasi Penuh Sinkronisasi Data PPDB $\to$ SIAKAD:** Menghilangkan beban kerja entri ulang data ratusan siswa baru setiap awal tahun ajaran.
2. **Kenyamanan Autentikasi Single Sign-On (SSO):** Pengguna tidak lagi perlu mengingat kredensial ganda untuk aplikasi PPDB dan SIAKAD.
3. **Kalkulasi Nilai Akademik Otomatis:** Perhitungan nilai akhir dan status tuntas KKM yang transparan dan akurat mempermudah tugas guru pengampu.
