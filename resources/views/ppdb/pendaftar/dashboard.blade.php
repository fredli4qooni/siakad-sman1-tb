<x-layouts.pendaftar title="Dashboard Pendaftar" heading="Dashboard Calon Siswa">
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
                    No. Pendaftaran: <span class="font-mono font-bold text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</span> &bull; NISN: <span class="tabular-nums font-medium text-[#1C2620]">{{ $pendaftar->nisn }}</span> &bull; Asal: <span class="font-medium text-[#1C2620]">{{ $pendaftar->asal_sekolah ?? '-' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                @if($pendaftar->status_pendaftaran === 'lulus')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]">
                        LULUS SELEKSI
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'tidak_lulus')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#FBEAEA] text-[#C81210] border border-[#C81210]">
                        TIDAK LULUS
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'terverifikasi')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026]">
                        BERKAS TERVERIFIKASI
                    </span>
                @elseif($pendaftar->status_pendaftaran === 'menunggu_verifikasi')
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#FBF9D6] text-[#6B6200]">
                        MENUNGGU VERIFIKASI
                    </span>
                @else
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#F3F5F2] text-[#545B52] border border-[#E1E4DE]">
                        DRAFT PENDAFTARAN
                    </span>
                @endif

                @if($formulirLengkap && $berkasCount >= 4)
                    <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs">
                        Cetak Bukti Pendaftaran
                    </x-button>
                @endif
            </div>
        </div>


        @if($hasilSeleksi && $hasilSeleksi->status === 'LULUS')
            <div class="p-6 rounded-2xl bg-[#E7F4EA] border border-[#039834] text-[#0E6026] space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-[#039834]"></span>
                    <h2 class="text-lg font-bold">SELAMAT! ANDA RESMI DITERIMA DI SMAN 1 TERBANGGI BESAR</h2>
                </div>
                <p class="text-xs text-[#1C2620] leading-relaxed">
                    Data pendaftaran Anda telah otomatis disinkronisasi ke sistem akademik SIAKAD sekolah. Akun SSO Anda kini memiliki hak akses penuh sebagai Siswa Aktif.
                </p>
                @if($hasilSeleksi->catatan)
                    <div class="text-xs text-[#1C2620] p-3 rounded-lg bg-white/70 border border-[#039834]/30">
                        <strong>Catatan Panitia:</strong> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif
                <div class="pt-2">
                    <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="primary">
                        Buka Portal Akademik (SIAKAD)
                    </x-button>
                </div>
            </div>
        @endif

        <!-- Tahapan Progres Pendaftaran -->
        <x-card title="Progres Pendaftaran Calon Siswa">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tahap 1 -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] bg-[#F3F5F2]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full bg-[#0E6026] text-white flex items-center justify-center font-bold text-xs">
                            &#10003;
                        </span>
                        <span class="text-[10px] font-semibold uppercase text-[#0E6026]">Selesai</span>
                    </div>
                    <div class="font-semibold text-sm text-[#1C2620]">1. Akun SSO</div>
                    <div class="text-xs text-[#545B52] mt-0.5">Identitas akun terpusat aktif</div>
                </div>

                <!-- Tahap 2 -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] {{ $formulirLengkap ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $formulirLengkap ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $formulirLengkap ? '✓' : '2' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $formulirLengkap ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $formulirLengkap ? 'Lengkap' : 'Belum Lengkap' }}
                        </span>
                    </div>
                    <div class="font-semibold text-sm text-[#1C2620]">2. Formulir Biodata</div>
                    <div class="text-xs text-[#545B52] mt-0.5">Data diri, sekolah & orang tua</div>
                </div>

                <!-- Tahap 3 -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] {{ $berkasCount >= 4 ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ $berkasCount >= 4 ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ $berkasCount >= 4 ? '✓' : '3' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ $berkasCount >= 4 ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ $berkasCount }}/4 Berkas
                        </span>
                    </div>
                    <div class="font-semibold text-sm text-[#1C2620]">3. Unggah Berkas</div>
                    <div class="text-xs text-[#545B52] mt-0.5">Scan dokumen KK, Akta, Rapor</div>
                </div>

                <!-- Tahap 4 -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] {{ in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus']) ? 'bg-[#F3F5F2]' : 'bg-white' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-6 h-6 rounded-full {{ in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus']) ? 'bg-[#0E6026] text-white' : 'bg-neutral-200 text-[#545B52]' }} flex items-center justify-center font-bold text-xs">
                            {{ in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus']) ? '✓' : '4' }}
                        </span>
                        <span class="text-[10px] font-semibold uppercase {{ in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus']) ? 'text-[#0E6026]' : 'text-neutral-500' }}">
                            {{ ucfirst(str_replace('_', ' ', $pendaftar->status_pendaftaran)) }}
                        </span>
                    </div>
                    <div class="font-semibold text-sm text-[#1C2620]">4. Verifikasi & Kelulusan</div>
                    <div class="text-xs text-[#545B52] mt-0.5">Penetapan oleh panitia PPDB</div>
                </div>
            </div>
        </x-card>

        <!-- 3 Kartu Menu Cepat -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-card title="1. Formulir Biodata" subtitle="Data pribadi & orang tua">
                <p class="text-xs text-[#545B52] mb-4">
                    Status: <strong class="{{ $formulirLengkap ? 'text-[#0E6026]' : 'text-[#C81210]' }}">{{ $formulirLengkap ? 'Data Lengkap' : 'Belum Lengkap' }}</strong>.
                    Periksa kembali NISN, asal sekolah, dan nomor kontak orang tua.
                </p>
                <x-button as="a" href="{{ route('pendaftar.formulir') }}" variant="secondary" class="w-full">
                    {{ $formulirLengkap ? 'Lihat / Edit Formulir' : 'Lengkapi Formulir Sekarang' }}
                </x-button>
            </x-card>

            <x-card title="2. Unggah Dokumen" subtitle="Berkas KK, Akta, Rapor">
                <p class="text-xs text-[#545B52] mb-4">
                    Tersedia: <strong class="tabular-nums {{ $berkasCount >= 4 ? 'text-[#0E6026]' : 'text-[#6B6200]' }}">{{ $berkasCount }} dari 4 dokumen</strong>.
                    Scan dokumen asli format PDF/JPG maks 2MB.
                </p>
                <x-button as="a" href="{{ route('pendaftar.berkas') }}" variant="secondary" class="w-full">
                    {{ $berkasCount >= 4 ? 'Kelola Dokumen' : 'Unggah Dokumen Sekarang' }}
                </x-button>
            </x-card>

            <x-card title="3. Hasil Kelulusan" subtitle="Status seleksi & SIAKAD">
                <p class="text-xs text-[#545B52] mb-4">
                    Status akhir seleksi PPDB diumumkan secara resmi pada menu ini setelah rapat pleno panitia.
                </p>
                <x-button as="a" href="{{ route('pendaftar.kelulusan') }}" variant="secondary" class="w-full">
                    Cek Status Kelulusan
                </x-button>
            </x-card>
        </div>
    </div>
</x-layouts.pendaftar>
