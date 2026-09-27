<x-layouts.guest title="Pengumuman Kelulusan PPDB — SMAN 1 TB">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8 max-w-2xl">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E7F4EA] text-[#0E6026] mb-2">
                Hasil Seleksi PPDB
            </span>
            <h1 class="text-3xl font-bold text-[#1C2620]">Pengumuman Penerimaan Peserta Didik Baru</h1>
            <p class="mt-1 text-sm text-[#545B52]">
                Masukkan Nomor Pendaftaran atau Nomor Induk Siswa Nasional (NISN) Anda untuk memeriksa status kelulusan.
            </p>
        </div>

        <!-- Form Pencarian Status -->
        <div class="max-w-xl mb-8">
            <x-card>
                <form action="{{ route('ppdb.pengumuman') }}" method="GET" class="space-y-4">
                    <x-input
                        label="Nomor Pendaftaran atau NISN"
                        name="keyword"
                        placeholder="Contoh: PPDB-2026-0001 atau 0098765432"
                        required
                        class="tabular-nums"
                        :value="$keyword ?? ''"
                    />

                    <div>
                        <x-button type="submit" variant="primary" class="w-full">
                            Periksa Hasil Seleksi
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Hasil Pencarian -->
        @if($searched)
            <div class="max-w-2xl mb-8">
                @if($pendaftar)
                    @php
                        $hasil = $pendaftar->hasilSeleksi;
                        $status = $hasil ? $hasil->status : ($pendaftar->status_pendaftaran === 'terverifikasi' ? 'MENUNGGU' : 'PROSES_BERKAS');
                    @endphp

                    <div class="rounded-xl border border-[#E1E4DE] bg-white overflow-hidden p-6 space-y-5">
                        <div class="flex items-center justify-between pb-4 border-b border-[#E1E4DE]">
                            <div>
                                <span class="text-xs text-[#545B52]">Nama Calon Siswa</span>
                                <div class="text-lg font-bold text-[#1C2620]">{{ $pendaftar->nama_lengkap }}</div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs text-[#545B52]">No. Pendaftaran</span>
                                <div class="text-sm font-semibold text-[#1C2620] tabular-nums">{{ $pendaftar->no_pendaftaran }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                            <div>
                                <span class="text-[#545B52]">NISN</span>
                                <div class="font-medium text-[#1C2620] tabular-nums mt-0.5">{{ $pendaftar->nisn }}</div>
                            </div>
                            <div>
                                <span class="text-[#545B52]">Asal Sekolah</span>
                                <div class="font-medium text-[#1C2620] mt-0.5">{{ $pendaftar->asal_sekolah ?? '-' }}</div>
                            </div>
                            <div>
                                <span class="text-[#545B52]">Gelombang</span>
                                <div class="font-medium text-[#1C2620] mt-0.5">{{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}</div>
                            </div>
                        </div>

                        <div class="pt-2">
                            @if($status === 'LULUS')
                                <div class="p-4 rounded-xl bg-[#E7F4EA] border border-[#039834] text-[#0E6026] space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-[#039834]"></span>
                                        <span class="text-base font-bold">SELAMAT, ANDA DINYATAKAN LULUS SELEKSI!</span>
                                    </div>
                                    <p class="text-xs text-[#1C2620] leading-relaxed">
                                        Data Anda telah otomatis disinkronisasikan ke sistem akademik SIAKAD SMAN 1 Terbanggi Besar. Anda dapat langsung masuk ke portal SIAKAD menggunakan akun SSO Anda.
                                    </p>
                                    @if($hasil && $hasil->catatan)
                                        <div class="text-xs pt-1 border-t border-[#039834]/30">
                                            <strong>Catatan Panitia:</strong> {{ $hasil->catatan }}
                                        </div>
                                    @endif
                                    <div class="pt-2">
                                        <x-button as="a" href="{{ route('auth.login') }}" variant="primary" class="w-full sm:w-auto">
                                            Masuk Portal Siswa SIAKAD
                                        </x-button>
                                    </div>
                                </div>
                            @elseif($status === 'TIDAK_LULUS')
                                <div class="p-4 rounded-xl bg-[#FBEAEA] border border-[#C81210] text-[#C81210] space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-[#C81210]"></span>
                                        <span class="text-base font-bold">MOHON MAAF, ANDA BELUM DINYATAKAN LULUS</span>
                                    </div>
                                    <p class="text-xs text-[#1C2620] leading-relaxed">
                                        Terima kasih telah berpartisipasi dalam proses seleksi PPDB SMAN 1 Terbanggi Besar. Tetap semangat dalam menggapai cita-cita Anda.
                                    </p>
                                    @if($hasil && $hasil->catatan)
                                        <div class="text-xs pt-1 border-t border-[#C81210]/30 text-[#1C2620]">
                                            <strong>Keterangan:</strong> {{ $hasil->catatan }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-4 rounded-xl bg-[#FBF9D6] border border-[#6B6200] text-[#6B6200] space-y-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-[#6B6200]"></span>
                                        <span class="text-base font-bold">STATUS: SEDANG DALAM PROSES SELEKSI</span>
                                    </div>
                                    <p class="text-xs text-[#1C2620] leading-relaxed">
                                        Berkas pendaftaran Anda saat ini sedang dalam tahap verifikasi atau menunggu jadwal sidang kelulusan oleh panitia PPDB. Silakan periksa kembali secara berkala.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <x-alert type="danger" title="Data Tidak Ditemukan">
                        Tidak ditemukan pendaftar dengan nomor pendaftaran atau NISN <strong>"{{ $keyword }}"</strong>. Pastikan nomor yang Anda ketikkan sesuai dengan bukti pendaftaran Anda.
                    </x-alert>
                @endif
            </div>
        @endif

        <!-- Informasi Tambahan -->
        <div class="max-w-2xl">
            <x-alert type="info" title="Informasi Penting:">
                Bagi calon peserta didik yang dinyatakan <strong>LULUS</strong>, data Anda otomatis tersinkronisasi ke sistem SIAKAD SMAN 1 Terbanggi Besar tanpa perlu entri berkas ulang.
            </x-alert>
        </div>
    </div>
</x-layouts.guest>
