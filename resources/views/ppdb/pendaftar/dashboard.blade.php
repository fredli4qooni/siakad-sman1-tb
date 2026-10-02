<x-layouts.pendaftar title="Dashboard Daftar Ulang" heading="Dashboard Siswa Baru">
    <div class="space-y-6">
        <!-- Header Banner Welcome Card -->
        <div class="bg-white rounded-xl border border-[#E1E4DE] p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                        Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}
                    </span>
                    <span class="text-xs text-[#545B52]">&bull; SMAN 1 Terbanggi Besar</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-[#1C2620]">Selamat Datang, {{ $pendaftar->nama_lengkap }}</h2>
                <p class="text-xs text-[#545B52] mt-1.5">
                    No. Registrasi: <span class="font-mono font-bold text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</span> &bull; NISN: <span class="tabular-nums font-medium text-[#1C2620]">{{ $pendaftar->nisn }}</span> &bull; Asal SMP: <span class="font-medium text-[#1C2620]">{{ $pendaftar->asal_sekolah ?? '-' }}</span>
                </p>
                @if($pendaftar->no_peserta_ppdb_provinsi)
                    <p class="text-xs text-[#0E6026] font-medium mt-1">
                        No. Peserta Kelulusan Pemerintah: <span class="font-mono">{{ $pendaftar->no_peserta_ppdb_provinsi }}</span>
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @if($pendaftar->isDiterima())
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]">
                        DITERIMA / SISWA RESMI
                    </span>
                @elseif($pendaftar->isDijadwalkanFisik())
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#FBF9D6] text-[#6B6200] border border-[#6B6200]">
                        JADWAL VERIFIKASI FISIK
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'tidak_lulus' || ($hasilSeleksi && $hasilSeleksi->status === 'TIDAK_LULUS'))
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#FBEAEA] text-[#C81210] border border-[#C81210]">
                        TIDAK LULUS
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'terverifikasi')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026]">
                        BERKAS ONLINE VALID
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'menunggu_verifikasi')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#FBF9D6] text-[#6B6200]">
                        MENUNGGU VERIFIKASI
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#F3F5F2] text-[#545B52] border border-[#E1E4DE]">
                        DRAFT DAFTAR ULANG
                    </span>
                @endif

                @if($formulirLengkap && $berkasCount >= 4)
                    <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs">
                        Cetak Bukti Registrasi
                    </x-button>
                @endif
            </div>
        </div>

        <!-- 1. PENGUMUMAN DITERIMA & KREDENSIAL SIAKAD -->
        @if($pendaftar->isDiterima())
            <div class="p-6 rounded-2xl bg-[#E7F4EA] border border-[#039834] text-[#0E6026] space-y-4 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#039834] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold tracking-wider uppercase text-[#0E6026]">Pengumuman Resmi Sekolah</span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#0E6026]">SELAMAT! ANDA RESMI DITERIMA SEBAGAI SISWA SMAN 1 TERBANGGI BESAR</h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-button as="a" href="{{ route('siakad.siswa.dashboard') }}" variant="primary" class="text-xs font-semibold">
                            Buka Data Induk Siswa (SIAKAD)
                        </x-button>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-[#1C2620] leading-relaxed">
                    Berkas pendaftaran ulang fisik Anda telah divalidasi oleh panitia dan dinyatakan lengkap. Data Anda telah resmi disinkronisasikan ke sistem akademik SIAKAD sekolah.
                </p>

                <!-- Box Kredensial Akun SIAKAD -->
                <div class="p-4 rounded-xl bg-white border border-[#039834]/40 text-xs text-[#1C2620] space-y-3">
                    <div class="font-bold text-sm text-[#0E6026] flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Kredensial Akun SIAKAD Anda
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-2.5 bg-[#F3F5F2] rounded-lg border border-[#E1E4DE]">
                            <span class="text-[#545B52] block text-[11px]">Nomor Induk Siswa (NIS)</span>
                            <span class="font-mono font-bold text-[#0E6026] text-sm tabular-nums">
                                {{ $pendaftar->user->siswa?->nis ?? '2627' . str_pad($pendaftar->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="p-2.5 bg-[#F3F5F2] rounded-lg border border-[#E1E4DE]">
                            <span class="text-[#545B52] block text-[11px]">Username SIAKAD</span>
                            <span class="font-mono font-bold text-[#1C2620] text-sm truncate block">
                                {{ $pendaftar->user->siswa?->nis ?? $pendaftar->user->email }}
                            </span>
                        </div>
                        <div class="p-2.5 bg-[#F3F5F2] rounded-lg border border-[#E1E4DE]">
                            <span class="text-[#545B52] block text-[11px]">Status Akun SSO</span>
                            <span class="font-semibold text-[#0E6026] text-sm flex items-center gap-1.5 mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-[#039834]"></span> Siswa Aktif
                            </span>
                        </div>
                    </div>

                    @if($hasilSeleksi && $hasilSeleksi->catatan)
                        <div class="text-xs pt-2 border-t border-[#E1E4DE]">
                            <strong>Catatan Panitia:</strong> {{ $hasilSeleksi->catatan }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- 2. JADWAL VALIDASI BERKAS FISIK DI SEKOLAH -->
        @if($pendaftar->isDijadwalkanFisik() && !$pendaftar->isDiterima())
            <div class="p-6 rounded-2xl bg-[#FBF9D6] border border-[#6B6200] text-[#1C2620] space-y-4 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#6B6200] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold tracking-wider uppercase text-[#6B6200]">Tahap Selanjutnya: Validasi Berkas Fisik</span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#1C2620]">Jadwal Validasi Berkas Fisik di SMAN 1 Terbanggi Besar</h2>
                        </div>
                    </div>

                    <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs font-semibold">
                        Cetak Bukti Pendaftaran Ulang
                    </x-button>
                </div>

                <p class="text-xs sm:text-sm text-[#545B52] leading-relaxed">
                    Berkas online Anda telah ditinjau oleh panitia. Silakan hadir langsung ke sekolah sesuai dengan jadwal yang telah ditetapkan berikut:
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Hari & Tanggal</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->tgl_verifikasi_fisik->isoFormat('dddd, D MMMM Y') }}
                        </div>
                    </div>

                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Sesi / Waktu</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->sesi_verifikasi_fisik ?? 'Sesi 1 (08.00 - 11.00 WIB)' }}
                        </div>
                    </div>

                    <div class="p-3.5 bg-white rounded-xl border border-[#6B6200]/30">
                        <span class="text-[#545B52] block">Tempat / Ruangan</span>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $pendaftar->lokasi_verifikasi_fisik ?? 'Ruang Panitia PPDB SMAN 1 Terbanggi Besar' }}
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-white border border-[#6B6200]/30 text-xs space-y-2">
                    <span class="font-bold text-[#6B6200] block">Berkas Fisik yang Wajib Dibawa:</span>
                    <p class="text-[#545B52] leading-relaxed">
                        {{ $pendaftar->catatan_verifikasi_fisik ?? '1. Cetak Bukti Pendaftaran Ulang dari sistem ini. 2. Ijazah SMP/MTs atau SKL Asli & Fotokopi legalisir (2 lembar). 3. Kartu Keluarga (KK) Asli & Fotokopi (2 lembar). 4. Akta Kelahiran Asli & Fotokopi (2 lembar). 5. Bukti Tanda Kelulusan PPDB Pemerintah. 6. Pas Foto 3x4 berwarna (3 lembar).' }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Tahapan Progres Daftar Ulang -->
        <x-card title="Tahapan Alur Daftar Ulang Siswa Baru">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Tahap 1 -->
                <div class="p-3.5 rounded-xl border border-[#E1E4DE] bg-[#F3F5F2]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full bg-[#0E6026] text-white flex items-center justify-center font-bold text-xs">
                            &#10003;
                        </span>
                        <span class="text-[10px] font-semibold uppercase text-[#0E6026]">Selesai</span>
                    </div>
                    <div class="font-semibold text-xs text-[#1C2620]">1. Akun SSO</div>
                    <div class="text-[11px] text-[#545B52] mt-0.5">Login akun email aktif</div>
                </div>

                <!-- Tahap 2 -->
                <div class="p-3.5 rounded-xl border border-[#E1E4DE] {{ $formulirLengkap ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $formulirLengkap ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $formulirLengkap ? '✓' : '2' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $formulirLengkap ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $formulirLengkap ? 'Lengkap' : 'Draft' }}
                        </span>
                    </div>
                    <div class="font-semibold text-xs text-[#1C2620]">2. Biodata Siswa</div>
                    <div class="text-[11px] text-[#545B52] mt-0.5">NISN, data diri & orang tua</div>
                </div>

                <!-- Tahap 3 -->
                <div class="p-3.5 rounded-xl border border-[#E1E4DE] {{ $berkasCount >= 4 ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $berkasCount >= 4 ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $berkasCount >= 4 ? '✓' : '3' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $berkasCount >= 4 ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $berkasCount }}/4 Dokumen
                        </span>
                    </div>
                    <div class="font-semibold text-xs text-[#1C2620]">3. Unggah Berkas</div>
                    <div class="text-[11px] text-[#545B52] mt-0.5">Scan bukti lulus, KK, Akta</div>
                </div>

                <!-- Tahap 4 -->
                <div class="p-3.5 rounded-xl border border-[#E1E4DE] {{ $pendaftar->isDijadwalkanFisik() ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $pendaftar->isDijadwalkanFisik() ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $pendaftar->isDijadwalkanFisik() ? '✓' : '4' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $pendaftar->isDijadwalkanFisik() ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $pendaftar->status_verifikasi_fisik === 'hadir_valid' ? 'Valid' : ($pendaftar->isDijadwalkanFisik() ? 'Terjadwal' : 'Menunggu') }}
                        </span>
                    </div>
                    <div class="font-semibold text-xs text-[#1C2620]">4. Validasi Fisik</div>
                    <div class="text-[11px] text-[#545B52] mt-0.5">Hadir ke sekolah bawa berkas</div>
                </div>

                <!-- Tahap 5 -->
                <div class="p-3.5 rounded-xl border border-[#E1E4DE] {{ $pendaftar->isDiterima() ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $pendaftar->isDiterima() ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $pendaftar->isDiterima() ? '✓' : '5' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $pendaftar->isDiterima() ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $pendaftar->isDiterima() ? 'Diterima' : 'Menunggu' }}
                        </span>
                    </div>
                    <div class="font-semibold text-xs text-[#1C2620]">5. Akses SIAKAD</div>
                    <div class="text-[11px] text-[#545B52] mt-0.5">Dapat NIS & akun aktif</div>
                </div>
            </div>
        </x-card>

        <!-- 3 Kartu Menu Cepat -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-card title="1. Formulir Daftar Ulang" subtitle="Data pribadi & orang tua">
                <p class="text-xs text-[#545B52] mb-4">
                    Status: <strong class="{{ $formulirLengkap ? 'text-[#0E6026]' : 'text-[#C81210]' }}">{{ $formulirLengkap ? 'Data Lengkap' : 'Belum Lengkap' }}</strong>.
                    Periksa kembali nomor bukti lulus pemerintah, NISN, dan nomor kontak orang tua.
                </p>
                <x-button as="a" href="{{ route('pendaftar.formulir') }}" variant="secondary" class="w-full text-xs">
                    {{ $formulirLengkap ? 'Lihat / Edit Formulir' : 'Lengkapi Formulir Sekarang' }}
                </x-button>
            </x-card>

            <x-card title="2. Unggah Dokumen" subtitle="Berkas KK, Akta, SKL">
                <p class="text-xs text-[#545B52] mb-4">
                    Tersedia: <strong class="tabular-nums {{ $berkasCount >= 4 ? 'text-[#0E6026]' : 'text-[#6B6200]' }}">{{ $berkasCount }} dari 4 dokumen</strong>.
                    Scan dokumen asli format PDF/JPG maks 2MB.
                </p>
                <x-button as="a" href="{{ route('pendaftar.berkas') }}" variant="secondary" class="w-full text-xs">
                    {{ $berkasCount >= 4 ? 'Kelola Dokumen' : 'Unggah Dokumen Sekarang' }}
                </x-button>
            </x-card>

            <x-card title="3. Status Penerimaan & SIAKAD" subtitle="Jadwal verifikasi & kredensial">
                <p class="text-xs text-[#545B52] mb-4">
                    Periksa jadwal validasi fisik dan perolehan kredensial NIS resmi untuk login SIAKAD.
                </p>
                <x-button as="a" href="{{ route('pendaftar.kelulusan') }}" variant="secondary" class="w-full text-xs">
                    Cek Status Penerimaan
                </x-button>
            </x-card>
        </div>
    </div>
</x-layouts.pendaftar>
