<x-layouts.guest title="Beranda PPDB & SIAKAD">
    <!-- Hero Section: Flat Minimalist, Solid Warna Resmi SMAN 1 Terbanggi Besar -->
    <div class="border-b border-[#E1E4DE] bg-[#FFFFFF] py-16 sm:py-20">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7">
                    @if($periodeAktif)
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] mb-4">
                            <span class="w-2 h-2 rounded-full bg-[#039834] animate-pulse"></span>
                            PPDB {{ $periodeAktif->tahun_ajaran }} &bull; {{ $periodeAktif->nama_gelombang }}
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#FBEAEA] text-[#C81210] mb-4">
                            Periode Pendaftaran Belum Dibuka
                        </div>
                    @endif

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1C2620] tracking-tight leading-tight">
                        Penerimaan Peserta Didik Baru Terintegrasi SIAKAD
                    </h1>
                    <p class="mt-4 text-base sm:text-lg text-[#545B52] leading-relaxed">
                        Sistem pendaftaran online SMAN 1 Terbanggi Besar dengan teknologi Single Sign-On (SSO OIDC). Calon siswa yang dinyatakan lulus otomatis tersinkronisasi ke sistem akademik tanpa entri data berulang.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        @if($periodeAktif && $periodeAktif->is_aktif)
                            <x-button as="a" href="{{ route('auth.register') }}" variant="primary">
                                Daftar PPDB Sekarang
                            </x-button>
                        @endif
                        <x-button as="a" href="{{ route('ppdb.pengumuman') }}" variant="secondary">
                            Cek Hasil Seleksi
                        </x-button>
                        <x-button as="a" href="{{ route('auth.login') }}" variant="ghost">
                            Masuk Akun SSO
                        </x-button>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="p-6 rounded-2xl border border-[#E1E4DE] bg-[#F3F5F2] space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-[#E1E4DE]">
                            <span class="text-xs font-semibold uppercase tracking-wider text-[#545B52]">Informasi Gelombang</span>
                            @if($periodeAktif)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E7F4EA] text-[#0E6026]">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-200 text-neutral-700">Tutup</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-white border border-[#E1E4DE]">
                                <div class="text-xs text-[#545B52]">Target Kuota</div>
                                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                                    {{ $periodeAktif ? number_format($periodeAktif->kuota, 0, ',', '.') : '-' }}
                                </div>
                                <div class="text-[11px] text-[#545B52] mt-0.5">Siswa Tingkat X</div>
                            </div>

                            <div class="p-4 rounded-xl bg-white border border-[#E1E4DE]">
                                <div class="text-xs text-[#545B52]">Total Pendaftar</div>
                                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">
                                    {{ number_format($totalPendaftar, 0, ',', '.') }}
                                </div>
                                <div class="text-[11px] text-[#545B52] mt-0.5">Berkas Masuk</div>
                            </div>
                        </div>

                        <div class="pt-2 text-xs text-[#545B52] space-y-1.5">
                            <div class="flex justify-between">
                                <span>Periode Pendaftaran:</span>
                                <span class="font-medium text-[#1C2620] tabular-nums">
                                    {{ $periodeAktif ? $periodeAktif->tanggal_buka->format('d M Y') . ' - ' . $periodeAktif->tanggal_tutup->format('d M Y') : 'Menunggu Jadwal' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>Jalur Seleksi:</span>
                                <span class="font-medium text-[#1C2620]">Zonasi, Prestasi & Afirmasi</span>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('ppdb.alur') }}" class="block text-center text-xs font-semibold text-[#0E6026] hover:underline">
                                Lihat Persyaratan dan Alur Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi 3 Pilar Utama -->
    <div class="py-12 bg-[#F3F5F2] border-b border-[#E1E4DE]">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card title="1. Satu Akun (SSO OIDC)" subtitle="OpenID Connect Identity Provider">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Calon siswa, guru, dan staf sekolah cukup menggunakan satu identitas akun terpusat untuk mengakses portal PPDB maupun portal akademik SIAKAD.
                    </p>
                </x-card>

                <x-card title="2. Pendaftaran Digital Cepat" subtitle="Formulir & Unggah Berkas Online">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Pengisian formulir biodata pendaftar, data orang tua, dan unggah berkas persyaratan (KK, Akta Lahir, Rapor) dilakukan secara daring.
                    </p>
                </x-card>

                <x-card title="3. Sinkronisasi Otomatis" subtitle="Zero Manual Re-Entry">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Saat status kelulusan ditetapkan oleh operator sekolah, data pokok siswa langsung dibuat di SIAKAD tanpa perlu entri manual berulang.
                    </p>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Alur Pendaftaran Ringkas -->
    <div class="py-16 bg-white">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="mb-10 text-left">
                <h2 class="text-2xl font-bold text-[#1C2620]">Alur Pendaftaran PPDB</h2>
                <p class="mt-1 text-sm text-[#545B52]">4 tahapan mudah penerimaan peserta didik baru hingga terdaftar di SIAKAD.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        1
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Registrasi Akun</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Calon siswa mendaftar akun SSO dengan NISN dan email aktif, lalu mengisi formulir pendaftaran.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        2
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Unggah Berkas</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Unggah scan berkas asli Kartu Keluarga, Akta Kelahiran, dan dokumen pendukung sesuai persyaratan sekolah.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        3
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Verifikasi Operator</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Panitia PPDB memvalidasi keabsahan data dan berkas yang diajukan oleh calon siswa secara online.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white">
                    <div class="w-8 h-8 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-sm mb-3">
                        4
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Pengumuman & SIAKAD</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Pendaftar yang dinyatakan lulus otomatis memperoleh status Siswa Aktif di SIAKAD dengan akun yang sama.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
