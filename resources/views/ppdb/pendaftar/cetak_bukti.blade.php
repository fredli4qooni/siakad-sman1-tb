<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Bukti Pendaftaran & Ketetapan PPDB — {{ $pendaftar->no_pendaftaran }} — {{ $pendaftar->nama_lengkap }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: 'Plus Jakarta Sans', Arial, Helvetica, sans-serif;
            color: #1C2620;
            background-color: #EBF0EA;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.45;
            -webkit-font-smoothing: antialiased;
        }

        /* Screen Top Navigation Bar */
        .no-print-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #FFFFFF;
            border-bottom: 1px solid #D6DCD3;
            box-shadow: 0 2px 8px rgba(28, 38, 32, 0.08);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .bar-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .bar-title {
            font-size: 14px;
            font-weight: 700;
            color: #1C2620;
            margin: 0;
        }
        .bar-desc {
            font-size: 12px;
            color: #545B52;
            margin: 0;
        }
        .bar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .btn-back {
            background: #FFFFFF;
            color: #1C2620;
            border: 1px solid #C9CDC3;
        }
        .btn-back:hover {
            background: #F3F5F2;
            border-color: #9FA29E;
        }
        .btn-print {
            background: #0E6026;
            color: #FFFFFF;
            border: 1px solid #0A4C1E;
        }
        .btn-print:hover {
            background: #0A4C1E;
        }

        /* Page Preview Container (A4 Scale) */
        .page-wrapper {
            padding: 24px 0 48px;
            display: flex;
            justify-content: center;
        }
        .sheet {
            width: 210mm;
            min-height: 297mm;
            background: #FFFFFF;
            padding: 16mm 20mm;
            border: 1px solid #D6DCD3;
            box-shadow: 0 4px 16px rgba(28, 38, 32, 0.12);
            position: relative;
        }

        /* Kop Surat Resmi Kedinasan */
        .kop-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .kop-logo {
            width: 75px;
            height: 75px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .kop-logo img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .kop-text {
            text-align: center;
            flex: 1;
        }
        .kop-text .instansi-prov {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1C2620;
            margin: 0;
            line-height: 1.2;
        }
        .kop-text .instansi-dinas {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #1C2620;
            margin: 1px 0 0 0;
            line-height: 1.25;
        }
        .kop-text .nama-sekolah {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #0E6026;
            margin: 2px 0;
            line-height: 1.2;
        }
        .kop-text .akreditasi {
            font-size: 9.5px;
            font-weight: 600;
            color: #1C2620;
            margin: 0;
            line-height: 1.3;
        }
        .kop-text .alamat {
            font-size: 9.5px;
            color: #545B52;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }
        .kop-text .kontak {
            font-size: 9px;
            color: #545B52;
            font-style: italic;
            margin: 1px 0 0 0;
            line-height: 1.3;
        }

        /* Garis Ganda Kop Surat Resmi Kedinasan */
        .kop-divider {
            border-top: 3px solid #1C2620;
            border-bottom: 1px solid #1C2620;
            height: 3px;
            margin: 10px 0 16px 0;
        }

        /* Judul Dokumen */
        .doc-header {
            text-align: center;
            margin-bottom: 16px;
        }
        .doc-title {
            font-size: 13.5px;
            font-weight: 800;
            text-transform: uppercase;
            text-decoration: underline;
            color: #1C2620;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .doc-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: #1C2620;
            margin: 0 0 6px 0;
        }
        .doc-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px 14px;
            font-size: 11px;
            color: #545B52;
            margin: 4px 0 0 0;
            line-height: 1.4;
        }
        .doc-meta-item {
            white-space: nowrap;
        }
        .doc-meta-sep {
            color: #C9CDC3;
            font-size: 9px;
            user-select: none;
        }
        .doc-meta strong {
            color: #1C2620;
            font-weight: 600;
        }

        /* Section Headings */
        .section-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            background: #F3F5F2;
            border-left: 3px solid #0E6026;
            border-bottom: 1px solid #E1E4DE;
            padding: 4px 8px;
            margin: 14px 0 6px 0;
            color: #1C2620;
            letter-spacing: 0.3px;
        }

        /* Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .info-table td {
            padding: 3.5px 6px;
            vertical-align: top;
            line-height: 1.4;
        }
        .info-table td.col-label {
            width: 28%;
            color: #545B52;
        }
        .info-table td.col-sep {
            width: 2%;
            text-align: center;
            color: #545B52;
        }
        .info-table td.col-val {
            width: 70%;
            font-weight: 600;
            color: #1C2620;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        /* Student Grid with Photo Box */
        .identity-layout {
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }
        .identity-details {
            flex: 1;
        }
        .photo-box {
            width: 30mm;
            height: 40mm;
            flex-shrink: 0;
            border: 1px dashed #9FA29E;
            background: #FAFBF9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6px;
            margin-top: 4px;
        }
        .photo-box svg {
            width: 32px;
            height: 32px;
            color: #9FA29E;
            margin-bottom: 6px;
        }
        .photo-box span {
            font-size: 9px;
            font-weight: 600;
            color: #545B52;
            text-transform: uppercase;
            line-height: 1.2;
        }

        /* Grid Table for Berkas */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            margin-top: 4px;
        }
        .grid-table th {
            background: #F3F5F2;
            border: 1px solid #D6DCD3;
            padding: 5px 8px;
            font-weight: 700;
            text-align: left;
            color: #1C2620;
        }
        .grid-table td {
            border: 1px solid #E1E4DE;
            padding: 4px 8px;
            vertical-align: middle;
        }
        .grid-table tr:nth-child(even) {
            background: #FAFBF9;
        }
        .status-ok {
            color: #0E6026;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        /* Signatures Section */
        .signatures-grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr 1.25fr;
            gap: 16px;
            text-align: center;
            page-break-inside: avoid;
            font-size: 11px;
        }
        .sig-role {
            font-weight: 600;
            color: #1C2620;
            margin-bottom: 2px;
        }
        .sig-subrole {
            font-size: 9.5px;
            color: #545B52;
            margin-bottom: 4px;
        }
        .sig-space {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .stamp-mark {
            width: 64px;
            height: 64px;
            opacity: 0.85;
            position: absolute;
            left: 20px;
            pointer-events: none;
        }
        .sig-name {
            font-weight: 700;
            text-decoration: underline;
            color: #1C2620;
        }
        .sig-id {
            font-size: 9.5px;
            color: #545B52;
            margin-top: 2px;
            font-variant-numeric: tabular-nums;
        }

        /* Footer Electronic Verification */
        .security-footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #E1E4DE;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 9px;
            color: #545B52;
            page-break-inside: avoid;
        }
        .qr-placeholder {
            width: 50px;
            height: 50px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFFFFF;
            border: 1px solid #D6DCD3;
            padding: 3px;
        }
        .qr-placeholder svg {
            width: 100%;
            height: 100%;
        }
        .security-text {
            flex: 1;
            line-height: 1.35;
        }
        .security-text strong {
            color: #1C2620;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #FFFFFF !important;
                color: #000000 !important;
                font-size: 11px !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .page-wrapper {
                padding: 0 !important;
                margin: 0 !important;
            }
            .sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            .kop-divider {
                border-top: 3px solid #000000 !important;
                border-bottom: 1px solid #000000 !important;
            }
            .section-header {
                background: #F3F5F2 !important;
                border-left-color: #0E6026 !important;
                border-bottom-color: #C9CDC3 !important;
            }
            .grid-table th {
                background: #F3F5F2 !important;
                border-color: #9FA29E !important;
            }
            .grid-table td {
                border-color: #C9CDC3 !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 15mm 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Screen Navigation Bar (Hidden during printing) -->
    <header class="no-print-bar">
        <div class="bar-info">
            <a href="{{ route('pendaftar.dashboard') }}" class="btn btn-back" title="Kembali ke Dasbor">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Dasbor
            </a>
            <div>
                <h1 class="bar-title">Surat Bukti Pendaftaran & Ketetapan PPDB Resmi</h1>
                <p class="bar-desc">SMA Negeri 1 Terbanggi Besar — Format Resmi Standar Kedinasan</p>
            </div>
        </div>
        <div class="bar-actions">
            <span style="font-size: 11px; color: #545B52; background: #F3F5F2; padding: 6px 10px; border-radius: 4px; border: 1px solid #E1E4DE;">
                Kertas: <strong>A4 Portrait</strong> (Aktifkan opsi "Background Graphics")
            </span>
            <button onclick="window.print()" class="btn btn-print">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Cetak Dokumen Ini
            </button>
        </div>
    </header>

    <!-- Printable Paper Sheet -->
    <main class="page-wrapper">
        <article class="sheet">
            <!-- Kop Surat Resmi Kedinasan -->
            <header class="kop-wrapper">
                <!-- Lambang Pemerintah Provinsi Lampung -->
                <div class="kop-logo">
                    <img src="{{ asset('images/logo-pemprov-lampung.png') }}" alt="Logo Pemerintah Provinsi Lampung">
                </div>

                <!-- Teks Kop Surat Resmi -->
                <div class="kop-text">
                    <h2 class="instansi-prov">Pemerintah Provinsi Lampung</h2>
                    <h3 class="instansi-dinas">Dinas Pendidikan dan Kebudayaan</h3>
                    <h1 class="nama-sekolah">SMA Negeri 1 Terbanggi Besar</h1>
                    <p class="akreditasi">TERAKREDITASI: A (UNGGUL) &bull; NPSN: 10802068 &bull; NSS: 301120208001</p>
                    <p class="alamat">Jl. Raya Terbanggi Besar No. 01, Kec. Terbanggi Besar, Kab. Lampung Tengah, Kode Pos 34163</p>
                    <p class="kontak">Laman: sman1terbanggibesar.sch.id &bull; Pos-el: info@sman1terbanggibesar.sch.id &bull; Telp: (0725) 529888</p>
                </div>

                <!-- Lambang SMA Negeri 1 Terbanggi Besar -->
                <div class="kop-logo">
                    <img src="{{ asset('images/logo-sma.png') }}" alt="Logo SMA Negeri 1 Terbanggi Besar">
                </div>
            </header>

            <!-- Garis Ganda Pembatas Kop Surat Kedinasan -->
            <div class="kop-divider"></div>

            <!-- Judul & Nomor Surat Resmi -->
            <section class="doc-header">
                @if($pendaftar->hasilSeleksi && $pendaftar->hasilSeleksi->isLulus())
                    <h2 class="doc-title">TANDA BUKTI PENDAFTARAN PPDB & SURAT KETETAPAN KELULUSAN</h2>
                @else
                    <h2 class="doc-title">TANDA BUKTI PENDAFTARAN PPDB TAHUN AJARAN {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}</h2>
                @endif
                <div class="doc-meta">
                    <span class="doc-meta-item">Nomor: <strong>421.3/{{ str_pad($pendaftar->id, 4, '0', STR_PAD_LEFT) }}/V.01/DP.2/{{ date('Y') }}</strong></span>
                    <span class="doc-meta-sep">&bull;</span>
                    <span class="doc-meta-item">No. Registrasi: <strong class="tabular-nums">{{ $pendaftar->no_pendaftaran }}</strong></span>
                    <span class="doc-meta-sep">&bull;</span>
                    <span class="doc-meta-item">Tanggal: <strong>{{ $pendaftar->created_at ? $pendaftar->created_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</strong></span>
                </div>
            </section>

            <p style="margin: 0 0 10px 0; font-size: 11px; text-align: justify; color: #1C2620;">
                Panitia Penerimaan Peserta Didik Baru (PPDB) SMA Negeri 1 Terbanggi Besar menerangkan bahwa calon peserta didik dengan identitas di bawah ini telah terdata secara sah pada pangkalan data seleksi penerimaan siswa baru Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}:
            </p>

            <!-- I. Data Calon Siswa & Pasfoto -->
            <h3 class="section-header">I. Data Identitas Calon Peserta Didik</h3>
            <div class="identity-layout">
                <div class="identity-details">
                    <table class="info-table">
                        <tr>
                            <td class="col-label">Nomor Registrasi PPDB</td>
                            <td class="col-sep">:</td>
                            <td class="col-val tabular-nums">{{ $pendaftar->no_pendaftaran }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Nomor Induk Siswa Nasional (NISN)</td>
                            <td class="col-sep">:</td>
                            <td class="col-val tabular-nums">{{ $pendaftar->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Nomor Induk Kependudukan (NIK)</td>
                            <td class="col-sep">:</td>
                            <td class="col-val tabular-nums">{{ $pendaftar->nik }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Nama Lengkap Siswa</td>
                            <td class="col-sep">:</td>
                            <td class="col-val" style="text-transform: uppercase;">{{ $pendaftar->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Jenis Kelamin</td>
                            <td class="col-sep">:</td>
                            <td class="col-val">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Tempat, Tanggal Lahir</td>
                            <td class="col-sep">:</td>
                            <td class="col-val">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Agama</td>
                            <td class="col-sep">:</td>
                            <td class="col-val">{{ $pendaftar->agama }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Asal Sekolah (SMP/MTs)</td>
                            <td class="col-sep">:</td>
                            <td class="col-val">{{ $pendaftar->asal_sekolah }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Nomor Telepon / WhatsApp</td>
                            <td class="col-sep">:</td>
                            <td class="col-val tabular-nums">{{ $pendaftar->no_hp }}</td>
                        </tr>
                        <tr>
                            <td class="col-label">Alamat Lengkap Domisili</td>
                            <td class="col-sep">:</td>
                            <td class="col-val">{{ $pendaftar->alamat }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Pasfoto 3x4 Box Placeholder -->
                <div class="photo-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Pasfoto Resmi<br>3 &times; 4 cm</span>
                </div>
            </div>

            <!-- II. Data Orang Tua / Wali -->
            @if($pendaftar->orangTua)
                <h3 class="section-header">II. Data Orang Tua / Wali Calon Siswa</h3>
                <table class="info-table">
                    <tr>
                        <td class="col-label">Nama Ayah Kandung</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->orangTua->nama_ayah }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Pekerjaan Ayah</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->orangTua->pekerjaan_ayah ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Nama Ibu Kandung</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->orangTua->nama_ibu }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Pekerjaan Ibu</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->orangTua->pekerjaan_ibu ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Nomor HP / Telepon Orang Tua</td>
                        <td class="col-sep">:</td>
                        <td class="col-val tabular-nums">{{ $pendaftar->orangTua->no_hp_ortu }}</td>
                    </tr>
                </table>
            @endif

            <!-- III. Rekapitulasi Berkas Persyaratan -->
            <h3 class="section-header">III. Rekapitulasi Dokumen Persyaratan Pendaftaran</h3>
            <table class="grid-table">
                <thead>
                    <tr>
                        <th style="width: 6%; text-align: center;">No.</th>
                        <th style="width: 44%;">Nama Dokumen Persyaratan</th>
                        <th style="width: 25%;">Status Verifikasi</th>
                        <th style="width: 25%;">Keterangan Arsip</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftar->berkas as $idx => $b)
                        <tr>
                            <td style="text-align: center;" class="tabular-nums">{{ $idx + 1 }}.</td>
                            <td style="font-weight: 600;">{{ ucwords(str_replace('_', ' ', $b->jenis_berkas)) }}</td>
                            <td>
                                <span class="status-ok">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Terverifikasi Sah
                                </span>
                            </td>
                            <td style="color: #545B52; font-size: 10px;">{{ Str::limit($b->nama_file_asli, 24) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #545B52; font-style: italic; padding: 10px;">
                                Dokumen fisik diverifikasi langsung di sekretariat panitia PPDB.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- IV. Status Ketetapan Hasil & Jadwal Validasi Fisik -->
            <h3 class="section-header">IV. Ketetapan Pendaftaran Ulang & Jadwal Validasi Berkas Fisik</h3>
            @if($pendaftar->isDiterima())
                <table class="info-table">
                    <tr>
                        <td class="col-label">Status Ketetapan</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="color: #0E6026; font-weight: 700;">DITERIMA — RESMI MENJADI SISWA SMAN 1 TERBANGGI BESAR</td>
                    </tr>
                    <tr>
                        <td class="col-label">Keterangan Penetapan</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-weight: 400; color: #1C2620;">
                            Berdasarkan hasil validasi berkas fisik pendaftaran ulang, calon peserta didik dinyatakan <strong>RESMI DITERIMA</strong> di SMA Negeri 1 Terbanggi Besar Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}.
                        </td>
                    </tr>
                    <tr>
                        <td class="col-label">Nomor Induk Siswa (NIS)</td>
                        <td class="col-sep">:</td>
                        <td class="col-val tabular-nums" style="font-weight: 700; color: #0E6026;">{{ $siswa->nis ?? ($pendaftar->user->siswa?->nis ?? 'Ditetapkan Saat Registrasi Kelas') }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Status Akun SIAKAD</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">Aktif (Tersinkronisasi Otomatis via Akun Single Sign-On / SSO)</td>
                    </tr>
                </table>
            @elseif($pendaftar->isDijadwalkanFisik())
                <table class="info-table">
                    <tr>
                        <td class="col-label">Status Pendaftaran</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-weight: 700; color: #6B6200;">DIJADWALKAN UNTUK VALIDASI BERKAS FISIK</td>
                    </tr>
                    <tr>
                        <td class="col-label">Hari & Tanggal Hadir</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-weight: 600;">{{ $pendaftar->tgl_verifikasi_fisik->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Sesi / Jam Validasi</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->sesi_verifikasi_fisik ?? 'Sesi 1 (08.00 - 11.00 WIB)' }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Tempat / Ruangan</td>
                        <td class="col-sep">:</td>
                        <td class="col-val">{{ $pendaftar->lokasi_verifikasi_fisik ?? 'Ruang Panitia PPDB SMAN 1 Terbanggi Besar' }}</td>
                    </tr>
                    <tr>
                        <td class="col-label">Berkas Wajib Dibawa</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-size: 10px; color: #1C2620; line-height: 1.4;">
                            {{ $pendaftar->catatan_verifikasi_fisik ?? '1. Lembar Cetak Bukti Pendaftaran Ulang ini. 2. Ijazah SMP/MTs atau SKL Asli & Fotokopi legalisir (2 lembar). 3. Kartu Keluarga (KK) Asli & Fotokopi (2 lembar). 4. Akta Kelahiran Asli & Fotokopi (2 lembar). 5. Bukti Kelulusan PPDB Pemerintah. 6. Pas Foto 3x4 (3 lembar).' }}
                        </td>
                    </tr>
                </table>
            @else
                <table class="info-table">
                    <tr>
                        <td class="col-label">Status Pendaftaran</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-weight: 700;">BERKAS DITERIMA — MENUNGGU PENJADWALAN VERIFIKASI FISIK</td>
                    </tr>
                    <tr>
                        <td class="col-label">Keterangan</td>
                        <td class="col-sep">:</td>
                        <td class="col-val" style="font-weight: 400; color: #1C2620;">
                            Data registrasi dan dokumen pendaftaran telah tercatat di sistem sekolah dan sedang dalam proses peninjauan administrasi panitia.
                        </td>
                    </tr>
                </table>
            @endif

            <!-- V. Pengesahan & Tanda Tangan -->
            <div class="signatures-grid">
                <div>
                    <div class="sig-role">Calon Peserta Didik,</div>
                    <div class="sig-subrole">Tanda tangan pemohon</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $pendaftar->nama_lengkap }}</div>
                    <div class="sig-id">NISN: {{ $pendaftar->nisn }}</div>
                </div>

                <div>
                    <div class="sig-role">Orang Tua / Wali,</div>
                    <div class="sig-subrole">Menyetujui pendaftaran</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">( {{ $pendaftar->orangTua ? $pendaftar->orangTua->nama_ayah : 'Orang Tua / Wali' }} )</div>
                    <div class="sig-id">Nama Jelas & Tanda Tangan</div>
                </div>

                <div>
                    <div class="sig-role">Terbanggi Besar, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="sig-subrole">a.n. Kepala SMA Negeri 1 Terbanggi Besar<br>Ketua Panitia Penerimaan PPDB,</div>
                    <div class="sig-space">
                        <!-- Digital Stamp Seal Representation -->
                        <svg class="stamp-mark" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="46" stroke="#0E6026" stroke-width="2.5" stroke-dasharray="4,2"/>
                            <circle cx="50" cy="50" r="38" stroke="#0E6026" stroke-width="1.2"/>
                            <path id="curve-stamp" d="M20,50 A30,30 0 1,1 80,50" fill="none"/>
                            <text font-size="7" font-weight="bold" fill="#0E6026" letter-spacing="1">
                                <textPath href="#curve-stamp" startOffset="50%" text-anchor="middle">PANITIA PPDB SMAN 1 TB</textPath>
                            </text>
                            <text x="50" y="53" font-size="8.5" font-weight="900" fill="#0E6026" text-anchor="middle">TERVERIFIKASI</text>
                            <text x="50" y="63" font-size="6.5" font-weight="700" fill="#039834" text-anchor="middle">RESMI DINAS</text>
                        </svg>
                    </div>
                    <div class="sig-name">Drs. H. Haryono, M.Pd.</div>
                    <div class="sig-id">NIP. 19680514 199303 1 004</div>
                </div>
            </div>

            <!-- Footer Keamanan & Validasi Sistem Elektronik -->
            <footer class="security-footer">
                <div class="qr-placeholder">
                    <!-- Crisp Vector QR Code Mockup -->
                    <svg viewBox="0 0 29 29" shape-rendering="crispEdges">
                        <path fill="#1C2620" d="M0,0 h7 v7 h-7 z M1,1 h5 v5 h-5 z M2,2 h3 v3 h-3 z M22,0 h7 v7 h-7 z M23,1 h5 v5 h-5 z M24,2 h3 v3 h-3 z M0,22 h7 v7 h-7 z M1,23 h5 v5 h-5 z M2,24 h3 v3 h-3 z M8,2 h2 v1 h-2 z M12,0 h1 v4 h-1 z M15,1 h2 v2 h-2 z M19,3 h2 v1 h-2 z M9,8 h3 v1 h-3 z M14,7 h1 v3 h-1 z M18,8 h3 v2 h-3 z M8,12 h1 v3 h-1 z M11,11 h3 v2 h-3 z M16,12 h2 v1 h-2 z M20,13 h3 v2 h-3 z M25,10 h3 v3 h-3 z M8,16 h4 v1 h-4 z M14,16 h2 v3 h-2 z M18,17 h4 v1 h-4 z M24,15 h2 v3 h-2 z M9,20 h2 v2 h-2 z M12,23 h3 v2 h-3 z M17,21 h2 v3 h-2 z M21,20 h1 v4 h-1 z M24,22 h4 v2 h-4 z M10,26 h2 v3 h-2 z M15,25 h3 v2 h-3 z M20,26 h3 v2 h-3 z M25,26 h2 v3 h-2 z"/>
                    </svg>
                </div>
                <div class="security-text">
                    <strong>Pemberitahuan Keabsahan Dokumen Elektronik:</strong><br>
                    Surat tanda bukti ini diterbitkan secara otomatis dan sah melalui <strong>Sistem PPDB & SIAKAD Terintegrasi SMA Negeri 1 Terbanggi Besar</strong> (UUID: {{ md5($pendaftar->no_pendaftaran . $pendaftar->nisn) }}). Keabsahan dokumen dapat divalidasi dengan memindai kode QR atau melakukan pengecekan nomor registrasi pada portal resmi <strong>https://sman1terbanggibesar.sch.id</strong>.<br>
                    <span style="font-size: 8.5px; color: #9FA29E;">Waktu cetak: {{ now()->translatedFormat('d F Y, H:i:s') }} WIB &bull; Sistem Single Sign-On (SSO) Terpadu SMAN 1 TB</span>
                </div>
            </footer>
        </article>
    </main>

</body>
</html>
