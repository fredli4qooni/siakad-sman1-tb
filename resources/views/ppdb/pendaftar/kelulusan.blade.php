<x-layouts.pendaftar title="Status Kelulusan — SMAN 1 TB">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Status Kelulusan PPDB</h1>
            <p class="text-xs text-[#545B52] mt-1">Hasil seleksi penerimaan peserta didik baru SMAN 1 Terbanggi Besar.</p>
        </div>

        @if($hasilSeleksi && $hasilSeleksi->status === 'LULUS')
            <div class="rounded-2xl border border-[#039834] bg-[#E7F4EA] p-8 text-center space-y-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#039834] text-white mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white text-[#0E6026] border border-[#039834]">
                    DINYATAKAN LULUS SELEKSI
                </span>
                <h2 class="text-2xl font-bold text-[#0E6026]">
                    Selamat, {{ $pendaftar->nama_lengkap }}!
                </h2>
                <p class="text-sm text-[#1C2620] max-w-lg mx-auto leading-relaxed">
                    Anda telah resmi diterima sebagai Calon Siswa Baru SMAN 1 Terbanggi Besar Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}. Data Anda telah otomatis disinkronisasi ke portal SIAKAD.
                </p>

                @if($hasilSeleksi->catatan)
                    <div class="p-4 rounded-xl bg-white border border-[#039834]/30 text-xs text-[#1C2620] max-w-lg mx-auto text-left">
                        <strong>Catatan Panitia:</strong> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif

                <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
                    <x-button as="a" href="{{ route('siakad.sso.redirect') }}" variant="primary">
                        Masuk ke Portal SIAKAD &rarr;
                    </x-button>
                    <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary">
                        Cetak Bukti Kelulusan
                    </x-button>
                </div>
            </div>
        @elseif($hasilSeleksi && $hasilSeleksi->status === 'TIDAK_LULUS')
            <div class="rounded-2xl border border-[#C81210] bg-[#FBEAEA] p-8 text-center space-y-4">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#C81210] text-white mx-auto">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-white text-[#C81210] border border-[#C81210]">
                    TIDAK LULUS
                </span>
                <h2 class="text-2xl font-bold text-[#C81210]">
                    Mohon Maaf, {{ $pendaftar->nama_lengkap }}
                </h2>
                <p class="text-sm text-[#1C2620] max-w-lg mx-auto leading-relaxed">
                    Berdasarkan hasil rapat sidang penetapan penerimaan peserta didik baru, Anda belum memenuhi kualifikasi kuota penerimaan tahun ajaran ini.
                </p>

                @if($hasilSeleksi->catatan)
                    <div class="p-4 rounded-xl bg-white border border-[#C81210]/30 text-xs text-[#1C2620] max-w-lg mx-auto text-left">
                        <strong>Catatan Panitia:</strong> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif
            </div>
        @else
            <x-card>
                <div class="text-center py-10 space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#F3F5F2] text-[#0E6026] mx-auto">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#1C2620]">Status: Menunggu Sidang Penetapan</h2>
                    <p class="text-xs text-[#545B52] max-w-md mx-auto leading-relaxed">
                        Data formulir dan berkas Anda saat ini sedang diperiksa dan menunggu jadwal pengumuman resmi dari panitia seleksi PPDB SMAN 1 Terbanggi Besar.
                    </p>

                    <div class="pt-2">
                        <x-badge status="menunggu">Status Berkas: {{ ucfirst(str_replace('_', ' ', $pendaftar->status_pendaftaran)) }}</x-badge>
                    </div>
                </div>
            </x-card>
        @endif

        <div class="flex justify-start">
            <x-button as="a" href="{{ route('pendaftar.dashboard') }}" variant="ghost">
                &larr; Kembali ke Dasbor
            </x-button>
        </div>
    </div>
</x-layouts.pendaftar>
