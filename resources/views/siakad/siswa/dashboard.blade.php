<x-layouts.pendaftar title="Dashboard SIAKAD Siswa" heading="Data Induk Siswa">
    <div class="space-y-6">
        <!-- Header Banner Sambutan Siswa -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-[#E7F4EA] to-[#F3F5F2] border border-[#039834]/30 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#0E6026] text-white">
                        Siswa Aktif SIAKAD
                    </span>
                    <span class="text-xs text-[#545B52] tabular-nums">
                        Tahun Masuk: {{ $siswa->tahun_masuk ?? date('Y') }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-[#1C2620]">
                    Selamat Datang, {{ $siswa->nama }}!
                </h1>
                <p class="text-sm text-[#545B52]">
                    Berikut adalah data induk akademik Anda yang telah diverifikasi dan tersinkronisasi di sistem SIAKAD SMAN 1 Terbanggi Besar.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="primary" class="text-xs font-semibold">
                    Lihat Rombel Kelas
                </x-button>
                <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs font-semibold">
                    Cetak Bukti Pendaftaran
                </x-button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Biodata Lengkap Siswa (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Kartu Identitas Siswa -->
                <x-card title="Data Induk Kependidikan Siswa" subtitle="Data pokok resmi yang tercatat di buku induk sekolah">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Nomor Induk Siswa (NIS)</span>
                            <div class="font-mono text-base font-bold text-[#0E6026] tabular-nums mt-0.5">
                                {{ $siswa->nis ?? 'Dalam Proses Penomoran' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">NISN (Nasional)</span>
                            <div class="font-mono text-base font-bold text-[#1C2620] tabular-nums mt-0.5">
                                {{ $siswa->nisn }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Nama Lengkap Siswa</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $siswa->nama }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Nomor Induk Kependudukan (NIK)</span>
                            <div class="font-mono text-sm font-semibold text-[#1C2620] mt-0.5">
                                {{ $pendaftar->nik ?? '-' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Jenis Kelamin</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $siswa->jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Tempat, Tanggal Lahir</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $pendaftar ? $pendaftar->tempat_lahir . ', ' . $pendaftar->tanggal_lahir->isoFormat('D MMMM Y') : '-' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Agama</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $pendaftar->agama ?? '-' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Asal Sekolah (SMP/MTs)</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $pendaftar->asal_sekolah ?? '-' }}
                            </div>
                        </div>

                        <div class="sm:col-span-2 p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Alamat Tempat Tinggal</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5 leading-relaxed">
                                {{ $siswa->alamat ?? ($pendaftar->alamat ?? '-') }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">No. HP / WhatsApp Siswa</span>
                            <div class="font-mono text-sm font-semibold text-[#1C2620] mt-0.5">
                                {{ $pendaftar->no_hp ?? '-' }}
                            </div>
                        </div>

                        <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Email Akun SSO</span>
                            <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $siswa->user?->email ?? '-' }}
                            </div>
                        </div>
                    </div>
                </x-card>

                <!-- Kartu Data Orang Tua / Wali -->
                @if($pendaftar && $pendaftar->orangTua)
                    <x-card title="Data Orang Tua / Wali" subtitle="Data kontak dan informasi orang tua yang tercatat">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                                <span class="text-[#545B52]">Nama Ayah</span>
                                <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                    {{ $pendaftar->orangTua->nama_ayah ?? '-' }}
                                </div>
                                <div class="text-[11px] text-[#545B52] mt-1">
                                    Pekerjaan: {{ $pendaftar->orangTua->pekerjaan_ayah ?? '-' }}
                                </div>
                            </div>

                            <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                                <span class="text-[#545B52]">Nama Ibu</span>
                                <div class="font-semibold text-sm text-[#1C2620] mt-0.5">
                                    {{ $pendaftar->orangTua->nama_ibu ?? '-' }}
                                </div>
                                <div class="text-[11px] text-[#545B52] mt-1">
                                    Pekerjaan: {{ $pendaftar->orangTua->pekerjaan_ibu ?? '-' }}
                                </div>
                            </div>

                            <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                                <span class="text-[#545B52]">No. HP Orang Tua / Wali</span>
                                <div class="font-mono text-sm font-semibold text-[#1C2620] mt-0.5">
                                    {{ $pendaftar->orangTua->no_hp_ortu ?? '-' }}
                                </div>
                            </div>

                            <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE]">
                                <span class="text-[#545B52]">Alamat Orang Tua</span>
                                <div class="font-semibold text-sm text-[#1C2620] mt-0.5 truncate">
                                    {{ $pendaftar->orangTua->alamat_ortu ?? ($pendaftar->alamat ?? '-') }}
                                </div>
                            </div>
                        </div>
                    </x-card>
                @endif
            </div>

            <!-- Kolom Kanan: Status Akademik & Kredensial SIAKAD (1 Kolom) -->
            <div class="space-y-6">
                <!-- Status Penempatan Kelas -->
                <x-card title="Penempatan Kelas">
                    @if($siswa->kelas)
                        <div class="p-4 rounded-xl bg-[#E7F4EA] border border-[#039834]/30 space-y-3">
                            <div>
                                <span class="text-[11px] text-[#0E6026] font-semibold uppercase tracking-wider">Rombongan Belajar</span>
                                <div class="text-xl font-bold text-[#0E6026] mt-0.5">
                                    {{ $siswa->kelas->nama_kelas }}
                                </div>
                                <div class="text-xs text-[#545B52] mt-1">
                                    Tingkat {{ $siswa->kelas->tingkat }} &bull; TP {{ $siswa->kelas->tahun_ajaran }}
                                </div>
                            </div>

                            <div class="pt-2 border-t border-[#039834]/20 text-xs">
                                <span class="text-[#545B52]">Wali Kelas:</span>
                                <div class="font-semibold text-[#1C2620] mt-0.5">
                                    {{ $siswa->kelas->waliKelas ? $siswa->kelas->waliKelas->nama_lengkap . ($siswa->kelas->waliKelas->gelar ? ', ' . $siswa->kelas->waliKelas->gelar : '') : 'Belum Ditentukan' }}
                                </div>
                            </div>

                            <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="primary" class="w-full text-xs font-semibold py-2">
                                Lihat Teman Sekelas
                            </x-button>
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-[#FBF9D6] border border-[#6B6200]/30 space-y-2">
                            <div class="flex items-center gap-2 text-[#6B6200] font-semibold text-xs">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Menunggu Ploting Kelas</span>
                            </div>
                            <p class="text-xs text-[#545B52] leading-relaxed">
                                Pembagian rombel/kelas untuk siswa baru sedang disiapkan oleh kurikulum sekolah. Silakan cek berkala.
                            </p>
                        </div>
                    @endif
                </x-card>

                <!-- Kredensial & Akses SIAKAD -->
                <x-card title="Kredensial SIAKAD">
                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-[#F3F5F2] rounded-lg border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Username Login</span>
                            <div class="font-mono font-semibold text-sm text-[#1C2620] mt-0.5">
                                {{ $siswa->nis ?? $siswa->nisn }}
                            </div>
                            <span class="text-[11px] text-[#545B52]">atau gunakan email SSO: {{ $siswa->user?->email }}</span>
                        </div>

                        <div class="p-3 bg-[#F3F5F2] rounded-lg border border-[#E1E4DE]">
                            <span class="text-[#545B52]">Status Akun SSO</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 rounded-full bg-[#039834]"></span>
                                <span class="font-semibold text-[#0E6026]">Aktif & Terintegrasi</span>
                            </div>
                        </div>

                        <div class="p-3 bg-[#E7F4EA] rounded-lg border border-[#039834]/30 text-[#0E6026] text-[11px] leading-relaxed">
                            <strong>SSO Terintegrasi:</strong> Anda dapat langsung mengakses modul PPDB maupun SIAKAD tanpa perlu login ulang.
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.pendaftar>
