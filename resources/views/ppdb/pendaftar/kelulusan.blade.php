<x-layouts.pendaftar title="Status Penerimaan & Kredensial" heading="Status Penerimaan Siswa Baru">
    <div class="space-y-6">
        <!-- Header Page with Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E1E4DE]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                        Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}
                    </span>
                    <span class="text-xs text-[#545B52]">&bull; SMAN 1 Terbanggi Besar</span>
                </div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Pengumuman Penerimaan & Kredensial SIAKAD</h1>
                <p class="text-xs text-[#545B52] mt-0.5">Ketetapan Resmi Panitia Daftar Ulang Siswa Baru SMAN 1 Terbanggi Besar</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button as="a" href="{{ route('pendaftar.dashboard') }}" variant="secondary" class="text-xs">
                    Kembali ke Dasbor
                </x-button>
            </div>
        </div>

        @if($pendaftar->isDiterima())
            <!-- Official Decision Banner (DITERIMA) -->
            <div class="rounded-xl border border-[#039834] bg-[#E7F4EA] p-6 lg:p-7 space-y-4 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#039834] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-[#0E6026] border border-[#039834]">
                                RESMI DITERIMA
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#0E6026] mt-1">
                                Selamat, {{ $pendaftar->nama_lengkap }}!
                            </h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap">
                        <x-button as="a" href="{{ route('siakad.siswa.dashboard') }}" variant="primary" class="text-xs font-semibold">
                            Buka Data Induk Siswa (SIAKAD)
                        </x-button>
                        <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs font-semibold">
                            Cetak Surat Penerimaan
                        </x-button>
                    </div>
                </div>

                <p class="text-sm text-[#1C2620] leading-relaxed max-w-4xl">
                    Berdasarkan hasil validasi berkas fisik pendaftaran ulang oleh panitia SMAN 1 Terbanggi Besar, Anda resmi dinyatakan <strong>DITERIMA</strong> sebagai siswa baru Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}. Data kependidikan Anda telah disinkronisasikan ke sistem akademik SIAKAD sekolah.
                </p>

                @if($hasilSeleksi && $hasilSeleksi->catatan)
                    <div class="p-3.5 rounded-lg bg-white border border-[#039834]/30 text-xs text-[#1C2620]">
                        <span class="font-bold text-[#0E6026]">Catatan Panitia:</span> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif
            </div>

            <!-- Two Column Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: Data Penetapan Pendaftar -->
                <x-card title="Data Penetapan Siswa Baru" subtitle="Rincian identitas pendaftar ulang yang telah disahkan">
                    <dl class="divide-y divide-[#E1E4DE] text-xs">
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nomor Registrasi Sekolah</dt>
                            <dd class="font-mono font-bold text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</dd>
                        </div>
                        @if($pendaftar->no_peserta_ppdb_provinsi)
                            <div class="py-3 flex justify-between items-center">
                                <dt class="text-[#545B52]">No. Kelulusan PPDB Pemerintah</dt>
                                <dd class="font-mono font-semibold text-[#0E6026]">{{ $pendaftar->no_peserta_ppdb_provinsi }}</dd>
                            </div>
                        @endif
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">NISN</dt>
                            <dd class="tabular-nums font-semibold text-[#1C2620]">{{ $pendaftar->nisn }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">NIK Siswa</dt>
                            <dd class="tabular-nums text-[#1C2620]">{{ $pendaftar->nik ?? '-' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nama Lengkap</dt>
                            <dd class="font-bold text-[#1C2620]">{{ $pendaftar->nama_lengkap }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Asal Sekolah (SMP/MTs)</dt>
                            <dd class="font-medium text-[#1C2620]">{{ $pendaftar->asal_sekolah ?? '-' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Status Validasi Berkas Fisik</dt>
                            <dd>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                    Hadir & Berkas Sah
                                </span>
                            </dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Status Akhir</dt>
                            <dd>
                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]">
                                    RESMI DITERIMA
                                </span>
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Card 2: Kredensial Akun SIAKAD & Tindak Lanjut -->
                <x-card title="Kredensial Akun SIAKAD" subtitle="Akses masuk sistem akademik dan langkah selanjutnya">
                    <div class="space-y-4 text-xs">
                        <div class="p-4 rounded-xl bg-[#F3F5F2] border border-[#E1E4DE] space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-[#E1E4DE]">
                                <span class="text-[#545B52]">Nomor Induk Siswa (NIS):</span>
                                <span class="font-mono font-bold text-sm text-[#0E6026] tabular-nums">
                                    {{ $pendaftar->user->siswa?->nis ?? '-' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[#545B52]">Username Login SIAKAD:</span>
                                <span class="font-mono font-semibold text-[#1C2620]">
                                    {{ $pendaftar->user->siswa?->nis ?? $pendaftar->user->email }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[#545B52]">Status Akun SSO:</span>
                                <span class="font-semibold text-[#0E6026] flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#039834]"></span>
                                    Siswa Aktif Terdaftar
                                </span>
                            </div>
                            @if(auth()->user()->siswa && auth()->user()->siswa->kelas)
                                <div class="flex items-center justify-between pt-2 border-t border-[#E1E4DE]">
                                    <span class="text-[#545B52]">Rombongan Belajar:</span>
                                    <span class="font-bold text-[#0E6026]">{{ auth()->user()->siswa->kelas->nama_kelas }}</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-bold text-[#1C2620] mb-2">Petunjuk Penggunaan Akun:</h4>
                            <ol class="space-y-2 text-[#545B52] list-decimal list-inside leading-relaxed">
                                <li>
                                    <strong class="text-[#1C2620]">Single Sign-On (SSO):</strong> Anda tidak perlu membuat akun baru. Akun email yang Anda gunakan sekarang otomatis terhubung ke sistem SIAKAD.
                                </li>
                                <li>
                                    <strong class="text-[#1C2620]">Cetak Bukti Penerimaan:</strong> Simpan atau cetak surat bukti penerimaan untuk arsip administrasi pribadi Anda.
                                </li>
                                <li>
                                    <strong class="text-[#1C2620]">Cek Data Induk:</strong> Periksa biodata induk siswa pada Dashboard SIAKAD Anda.
                                </li>
                            </ol>
                        </div>

                        <div class="pt-2">
                            <x-button as="a" href="{{ route('siakad.siswa.dashboard') }}" variant="primary" class="w-full text-xs font-semibold py-2.5">
                                Masuk ke Portal Data Induk Siswa (SIAKAD)
                            </x-button>
                        </div>
                    </div>
                </x-card>
            </div>
        @elseif($pendaftar->isDijadwalkanFisik())
            <!-- Banner Tahap Verifikasi Fisik -->
            <div class="rounded-xl border border-[#6B6200] bg-[#FBF9D6] p-6 lg:p-7 space-y-4 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#6B6200] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-[#6B6200] border border-[#6B6200]">
                                JADWAL VALIDASI BERKAS FISIK
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#1C2620] mt-1">
                                Jadwal Hadir ke Sekolah, {{ $pendaftar->nama_lengkap }}
                            </h2>
                        </div>
                    </div>

                    <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs font-semibold">
                        Cetak Bukti Pendaftaran Ulang
                    </x-button>
                </div>

                <p class="text-xs sm:text-sm text-[#545B52] leading-relaxed max-w-4xl">
                    Berkas formulir daftar ulang digital Anda telah disetujui. Langkah berikutnya adalah verifikasi dokumen fisik asli oleh panitia di sekolah. Silakan hadir sesuai jadwal di bawah ini.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs pt-2">
                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Hari & Tanggal</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->tgl_verifikasi_fisik->isoFormat('dddd, D MMMM Y') }}
                        </div>
                    </div>
                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Sesi Waktu</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->sesi_verifikasi_fisik ?? 'Sesi 1 (08.00 - 11.00 WIB)' }}
                        </div>
                    </div>
                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Lokasi Verifikasi</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->lokasi_verifikasi_fisik ?? 'Ruang Panitia PPDB SMAN 1 Terbanggi Besar' }}
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white border border-[#6B6200]/30 text-xs space-y-2">
                    <span class="font-bold text-[#6B6200] block">Berkas Fisik yang Wajib Dibawa Saat Hadir:</span>
                    <p class="text-[#545B52] leading-relaxed">
                        {{ $pendaftar->catatan_verifikasi_fisik ?? '1. Cetak Bukti Pendaftaran Ulang dari sistem ini. 2. Ijazah SMP/MTs atau SKL Asli & Fotokopi legalisir (2 lembar). 3. Kartu Keluarga (KK) Asli & Fotokopi (2 lembar). 4. Akta Kelahiran Asli & Fotokopi (2 lembar). 5. Bukti Tanda Kelulusan PPDB Pemerintah. 6. Pas Foto 3x4 berwarna (3 lembar).' }}
                    </p>
                </div>
            </div>
        @else
            <!-- Menunggu Proses -->
            <x-card title="Status Proses Pendaftaran Ulang" subtitle="Tahapan verifikasi berkas oleh panitia sekolah">
                <div class="p-6 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-[#F3F5F2] text-[#545B52] flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-[#1C2620]">Berkas Sedang Ditinjau oleh Panitia</h3>
                    <p class="text-xs text-[#545B52] max-w-md mx-auto leading-relaxed">
                        Pastikan Anda telah melengkapi seluruh biodata formulir dan mengunggah dokumen persyaratan secara lengkap. Panitia akan segera memvalidasi dan menerbitkan jadwal verifikasi fisik Anda di sekolah.
                    </p>
                    <div class="pt-2">
                        <x-button as="a" href="{{ route('pendaftar.berkas') }}" variant="secondary" class="text-xs">
                            Periksa Kelengkapan Berkas
                        </x-button>
                    </div>
                </div>
            </x-card>
        @endif
    </div>
</x-layouts.pendaftar>
