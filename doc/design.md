# Design System — Sistem PPDB Terintegrasi SIAKAD

**Versi:** 1.0 (Draft)
**Cakupan:** Warna, tipografi, komponen, prinsip desain (tidak termasuk layout per halaman)

## Arah Desain

Sistem ini dipakai dua jenis orang dengan kebutuhan berbeda: **calon siswa/orang tua** yang datang sesekali untuk momen penting (mendaftar, menunggu pengumuman) dan **admin/guru** yang memakainya berulang setiap hari untuk kerja data. Keduanya butuh antarmuka yang tenang, jelas, dan cepat dipahami — bukan yang ramai secara visual. Karena itu arah desainnya: **minimalist, terang, fungsional**, dengan warna resmi sekolah dipakai sebagai sinyal (status, aksi), bukan sebagai dekorasi.

Aturan keras sesuai permintaan: **tidak ada gradient di seluruh antarmuka** — semua warna solid flat.

---

## 1. Warna

Warna dasar diambil dari warna resmi sekolah. Karena hijau utama dan merah brand terlalu terang untuk dipakai sebagai teks/tombol solid (gagal syarat kontras keterbacaan AA di atas putih), disiapkan varian lebih gelap khusus untuk teks & tombol — sementara warna asli tetap dipertahankan untuk ikon, aksen, dan elemen besar. Dengan begitu identitas warna sekolah tetap terjaga tanpa mengorbankan keterbacaan formulir.

### Netral & Latar

| Token | Hex | Pemakaian |
|---|---|---|
| `--color-bg` | `#FFFFFF` | Latar utama halaman |
| `--color-bg-muted` | `#F3F5F2` | Latar sidebar, section alternate, hover baris tabel |
| `--color-border` | `#E1E4DE` | Garis pembatas tipis (kartu, tabel) |
| `--color-border-strong` | `#C9CDC3` | Garis input, pembatas yang perlu lebih terlihat |
| `--color-ink` | `#1C2620` | Teks utama (diturunkan dari hijau tua brand, didesaturasi agar netral untuk teks panjang) |
| `--color-ink-muted` | `#545B52` | Teks sekunder, keterangan |
| `--color-ink-faint` | `#9FA29E` | Placeholder, teks nonaktif (abu-abu resmi sekolah) |

### Hijau (Primary)

| Token | Hex | Pemakaian |
|---|---|---|
| `--color-primary` | `#0E6026` | Tombol solid, tautan, item navigasi aktif, teks aksi — **hijau tua**, dipakai untuk teks/tombol karena kontrasnya aman |
| `--color-primary-hover` | `#0A4C1E` | Hover/pressed state dari primary |
| `--color-primary-bright` | `#039834` | **Hijau utama** brand — dipakai untuk ikon, progress bar, grafik, ring fokus, aksen kecil. Tidak dipakai untuk teks kecil (kontras kurang di atas putih) |
| `--color-primary-tint` | `#E7F4EA` | Latar lembut untuk badge/status "Diterima", baris terpilih |

### Merah (Danger)

| Token | Hex | Pemakaian |
|---|---|---|
| `--color-danger` | `#C81210` | Teks error, tombol destruktif — varian gelap dari merah brand untuk kontras aman |
| `--color-danger-bright` | `#F10704` | **Merah brand asli** — ikon, border tegas, elemen besar. Tidak dipakai untuk teks kecil |
| `--color-danger-tint` | `#FBEAEA` | Latar lembut untuk badge status "Ditolak", banner error |

### Kuning (Warning)

| Token | Hex | Pemakaian |
|---|---|---|
| `--color-warning-bright` | `#E9E920` | **Kuning brand asli** — hanya untuk dot indikator kecil/ikon, tidak untuk area bertuliskan teks |
| `--color-warning-ink` | `#6B6200` | Teks di atas latar kuning lembut (diturunkan dari kuning brand agar terbaca) |
| `--color-warning-tint` | `#FBF9D6` | Latar lembut untuk badge status "Menunggu Verifikasi" |

> Status "Lulus/Diterima" memakai token hijau (primary) yang sama — tidak perlu warna "success" terpisah, karena hijau brand sudah secara alami membawa makna itu.

---

## 2. Tipografi

**Satu keluarga font: [Plus Jakarta Sans](https://fonts.google.com/specimen/Plus+Jakarta+Sans)** — dipilih secara sengaja karena ini adalah typeface buatan studio tipografi Indonesia (Tokotype), awalnya dikomisikan untuk identitas Kota Jakarta. Cocok secara konteks (produk pemerintahan pendidikan Indonesia) dan tetap modern/netral untuk UI data-dense. Hierarki dibangun lewat bobot dan ukuran — bukan mencampur beberapa font.

| Level | Ukuran | Bobot | Line-height | Pemakaian |
|---|---|---|---|---|
| Display | 34px | 700 | 1.2 | Judul halaman utama — dipakai sekali per halaman |
| H2 | 24px | 600 | 1.3 | Judul seksi |
| H3 | 18px | 600 | 1.4 | Judul kartu/subseksi |
| Body | 15px | 400 | 1.6 | Paragraf, deskripsi |
| Body Kecil | 13px | 400 | 1.5 | Keterangan, caption |
| Label UI | 13px | 500 | 1.4 | Label form, header tabel, teks tombol — **sentence case**, bukan huruf besar semua |

Catatan:
- Lebar paragraf maksimum ±72 karakter per baris agar tetap nyaman dibaca.
- Rata kiri untuk seluruh konten (bukan rata tengah) — produk ini berbasis data & formulir, bukan halaman pemasaran dengan hero terpusat.
- Untuk kolom angka (nilai, NISN) gunakan `font-variant-numeric: tabular-nums` pada font yang sama, bukan berganti ke font monospace, supaya angka rata sejajar tanpa memecah keluarga font.

---

## 3. Layout Dasar

**Skala spasi** (kelipatan 4px): `4, 8, 12, 16, 24, 32, 48, 64`

**Radius bertingkat** — sengaja dibedakan per skala elemen, bukan satu radius dipakai untuk semua:

| Elemen | Radius |
|---|---|
| Kartu, container besar | 12px |
| Tombol, input, dropdown | 8px |
| Badge/status pill | 999px (bulat penuh — ini elemen yang memang secara semantik berbentuk tag) |
| Sel/baris tabel | 0 (tegas, tanpa lengkung) |

**Elevasi** — permukaan statis (kartu, section) memakai garis tipis `--color-border`, **bukan shadow**. Shadow hanya dipakai untuk lapisan yang benar-benar mengambang: modal, dropdown menu, toast notification.

```
--shadow-float: 0 8px 24px rgba(28, 38, 32, 0.14);
```

(Warna shadow diturunkan dari `--color-ink`, bukan hitam generik, supaya konsisten dengan palet.)

---

## 4. Komponen

**Tombol**
- Primary: latar `--color-primary`, teks putih, radius 8px. Hover → `--color-primary-hover`. Fokus → outline 2px `--color-primary-bright`.
- Sekunder: latar putih, border `--color-border-strong`, teks `--color-ink`. Hover → latar `--color-bg-muted`.
- Destruktif: latar `--color-danger`, teks putih — khusus aksi hapus/tolak permanen.
- Ghost/teks: tanpa latar & border, teks `--color-primary` — untuk aksi sekunder di dalam tabel/kartu.
- Label tombol pakai kata kerja aktif spesifik ("Simpan Pendaftaran", "Verifikasi Berkas") — tidak ada tanda panah (→) ditambahkan di akhir teks.

**Input Form**
- Label di atas input, sentence case, bobot 500, warna `--color-ink`.
- Border `--color-border-strong`, radius 8px.
- Fokus: border `--color-primary-bright`.
- Teks bantuan di bawah input warna `--color-ink-muted`.
- Error: border & teks `--color-danger`, disertai ikon kecil (bukan hanya mengandalkan warna, agar tetap jelas bagi pengguna buta warna).

**Badge Status**

| Status | Latar | Teks |
|---|---|---|
| Diterima / Lulus | `--color-primary-tint` | `--color-primary` |
| Ditolak | `--color-danger-tint` | `--color-danger` |
| Menunggu Verifikasi | `--color-warning-tint` | `--color-warning-ink` |
| Draft / Belum Lengkap | `--color-bg-muted` | `--color-ink-muted` |

**Kartu**
- Latar putih, border 1px `--color-border`, radius 12px, padding 20–24px.
- Tidak ada shadow pada kartu statis.

**Tabel Data**
- Header: sentence case (bukan UPPERCASE), bobot 600, warna `--color-ink-muted`, border bawah `--color-border-strong`, tanpa garis vertikal antar kolom.
- Baris: border bawah tipis `--color-border`; hover baris → `--color-bg-muted`.
- Kolom angka rata kanan dengan tabular numerals.

**Navigasi (Sidebar)**
- Latar `--color-bg-muted`. Item aktif: teks `--color-primary` + latar `--color-primary-tint`, radius 8px.
- Ikon satu warna (bukan gradient/duotone), stroke konsisten.

**Ikonografi**
- Gaya outline/line icon, ketebalan stroke konsisten (1.5–2px), satu warna per ikon sesuai konteks (netral/aksi/peringatan).
- Tidak memakai ikon 3D, gradient, atau ilustrasi dekoratif tanpa fungsi.

**Motion**
- Transisi hover/warna: 150ms ease.
- Tidak ada animasi fade-slide-up otomatis di setiap seksi saat halaman dimuat.
- Animasi hanya merespons aksi pengguna (buka dropdown, submit berhasil → toast sekali, toggle berubah state).
- Hormati `prefers-reduced-motion`.

---

## 5. Prinsip Desain

1. **Warna sebagai sinyal, bukan dekorasi** — hijau/merah/kuning hanya dipakai untuk makna fungsional (status, aksi), tidak untuk mempercantik area yang netral.
2. **Kontras & keterbacaan diutamakan** — penggunanya termasuk orang tua/wali yang mengisi formulir penting sekali dalam setahun, bukan pengguna power-user.
3. **Satu keluarga font, hierarki lewat bobot & ukuran.**
4. **Permukaan statis pakai garis tipis; shadow hanya untuk lapisan mengambang.**
5. **Tidak ada gradient di seluruh antarmuka.**
6. **Radius bertingkat sesuai skala elemen**, bukan satu radius untuk semua.

## 6. Checklist Anti-Generik (yang sengaja dihindari)

- ❌ Gradient dekoratif di background, tombol, atau ikon
- ❌ Label huruf besar semua (ALL CAPS)
- ❌ Tanda panah (→) di akhir teks tombol/tautan
- ❌ Shadow abu-abu seragam di semua kartu
- ❌ Font monospace untuk label kecil
- ❌ Satu radius dipakai di semua elemen tanpa mempertimbangkan hierarki
- ❌ Latar krem hangat + aksen terracotta, atau latar gelap + aksen neon (dua kombinasi paling umum dipakai desain hasil AI generik) — sistem ini terang dan berbasis warna sekolah, bukan keduanya
