<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran — {{ $pendaftar->no_pendaftaran }} — {{ $pendaftar->nama_lengkap }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            color: #1C2620;
            background-color: #F3F5F2;
            margin: 0;
            padding: 24px;
            font-size: 13px;
            line-height: 1.5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #FFFFFF;
            padding: 36px 48px;
            border: 1px solid #E1E4DE;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #1C2620;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            margin: 2px 0;
            font-size: 17px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 2px 0;
            font-size: 20px;
            font-weight: 800;
            color: #0E6026;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #545B52;
        }
        .title-doc {
            text-align: center;
            margin: 20px 0;
        }
        .title-doc h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            text-decoration: underline;
        }
        .title-doc span {
            font-size: 12px;
            color: #545B52;
        }
        .section-title {
            font-weight: 700;
            font-size: 13px;
            background: #F3F5F2;
            padding: 4px 8px;
            margin: 16px 0 8px 0;
            border-left: 4px solid #0E6026;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        table td.label {
            width: 28%;
            color: #545B52;
        }
        table td.separator {
            width: 2%;
        }
        table td.value {
            width: 70%;
            font-weight: 500;
        }
        .reg-badge {
            display: inline-block;
            background: #E7F4EA;
            color: #0E6026;
            font-family: monospace;
            font-weight: 700;
            font-size: 14px;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid #039834;
        }
        .signatures {
            margin-top: 36px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sig-block {
            width: 40%;
            text-align: center;
        }
        .sig-space {
            height: 64px;
        }
        .sig-name {
            font-weight: 700;
            text-decoration: underline;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #0E6026;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }
        .btn-back {
            background: #FFFFFF;
            color: #1C2620;
            border: 1px solid #C9CDC3;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 8px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <a href="{{ route('pendaftar.dashboard') }}" class="btn-back">Kembali ke Dasbor</a>
        <button onclick="window.print()" class="btn-print">&#128438; Cetak Dokumen Ini</button>
    </div>

    <div class="container">
        <!-- Kop Surat Resmi -->
        <div class="header">
            <h3>Pemerintah Provinsi Lampung</h3>
            <h2>Dinas Pendidikan dan Kebudayaan</h2>
            <h1>SMA Negeri 1 Terbanggi Besar</h1>
            <p>Jalan Raya Terbanggi Besar, Kec. Terbanggi Besar, Kabupaten Lampung Tengah, Kode Pos 34163</p>
            <p>Website: sman1terbanggibesar.sch.id &bull; Email: info@sman1terbanggibesar.sch.id</p>
        </div>

        <div class="title-doc">
            <h4>TANDA BUKTI PENDAFTARAN PPDB TAHUN AJARAN {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}</h4>
            <span>Nomor Registrasi: <span class="reg-badge">{{ $pendaftar->no_pendaftaran }}</span></span>
        </div>

        <div class="section-title">A. Data Calon Siswa</div>
        <table>
            <tr>
                <td class="label">Nomor Induk Siswa Nasional (NISN)</td>
                <td class="separator">:</td>
                <td class="value" style="font-family: monospace;">{{ $pendaftar->nisn }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Kependudukan (NIK)</td>
                <td class="separator">:</td>
                <td class="value" style="font-family: monospace;">{{ $pendaftar->nik }}</td>
            </tr>
            <tr>
                <td class="label">Nama Lengkap Siswa</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->agama }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah (SMP/MTs)</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->asal_sekolah }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Telepon/HP Siswa</td>
                <td class="separator">:</td>
                <td class="value" style="font-family: monospace;">{{ $pendaftar->no_hp }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Lengkap Domisili</td>
                <td class="separator">:</td>
                <td class="value">{{ $pendaftar->alamat }}</td>
            </tr>
        </table>

        @if($pendaftar->orangTua)
            <div class="section-title">B. Data Orang Tua / Wali Siswa</div>
            <table>
                <tr>
                    <td class="label">Nama Ayah Kandung</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pendaftar->orangTua->nama_ayah }}</td>
                </tr>
                <tr>
                    <td class="label">Pekerjaan Ayah</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pendaftar->orangTua->pekerjaan_ayah }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Ibu Kandung</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pendaftar->orangTua->nama_ibu }}</td>
                </tr>
                <tr>
                    <td class="label">Pekerjaan Ibu</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $pendaftar->orangTua->pekerjaan_ibu }}</td>
                </tr>
                <tr>
                    <td class="label">Nomor HP/Telepon Orang Tua</td>
                    <td class="separator">:</td>
                    <td class="value" style="font-family: monospace;">{{ $pendaftar->orangTua->no_hp_ortu }}</td>
                </tr>
            </table>
        @endif

        <div class="section-title">C. Rekap Dokumen Berkas yang Diunggah</div>
        <table>
            @forelse($pendaftar->berkas as $idx => $b)
                <tr>
                    <td style="width: 5%;">{{ $idx + 1 }}.</td>
                    <td style="width: 50%;">{{ ucwords(str_replace('_', ' ', $b->jenis_berkas)) }}</td>
                    <td style="width: 45%; color: #0E6026; font-weight: 600;">✓ Terunggah ({{ $b->nama_file_asli }})</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="color: #C81210;">Belum ada dokumen yang diunggah.</td>
                </tr>
            @endforelse
        </table>

        <div class="signatures">
            <div class="sig-block">
                <div>Calon Peserta Didik,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $pendaftar->nama_lengkap }}</div>
                <div style="font-size: 11px; color: #545B52;">NISN: {{ $pendaftar->nisn }}</div>
            </div>

            <div class="sig-block">
                <div>Terbanggi Besar, {{ now()->translatedFormat('d F Y') }}</div>
                <div>Panitia PPDB SMAN 1 Terbanggi Besar,</div>
                <div class="sig-space"></div>
                <div class="sig-name">( Petugas Verifikator )</div>
                <div style="font-size: 11px; color: #545B52;">NIP. ........................................</div>
            </div>
        </div>

        <div style="margin-top: 32px; padding: 8px 12px; background: #F3F5F2; border: 1px dashed #C9CDC3; font-size: 11px; color: #545B52;">
            <strong>Catatan Penting:</strong> Simpan tanda bukti pendaftaran ini sebagai bukti registrasi yang sah. Apabila dinyatakan <strong>LULUS</strong>, Anda dapat langsung login ke portal akademik SIAKAD menggunakan akun Single Sign-On (SSO) ini tanpa registrasi ulang.
        </div>
    </div>
</body>
</html>
