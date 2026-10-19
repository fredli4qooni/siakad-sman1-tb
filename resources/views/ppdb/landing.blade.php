<x-layouts.guest title="Beranda SPMB & SIAKAD">
    <!-- Hero Section: Latar Belakang Foto Sekolah SMAN 1 Terbanggi Besar -->
    <div class="border-b border-[#E1E4DE] bg-gradient-to-r from-white/85 via-white/55 to-white/20 py-16 sm:py-20">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="max-w-3xl">
                @if($periodeAktif)
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] mb-4 border border-[#039834]/20 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-[#039834] animate-pulse"></span>
                        SPMB {{ $periodeAktif->tahun_ajaran }} &bull; {{ $periodeAktif->nama_gelombang }}
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-[#FBEAEA] text-[#C81210] mb-4 border border-[#C81210]/20 shadow-2xs">
                        Periode Pendaftaran Belum Dibuka
                    </div>
                @endif

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#1C2620] tracking-tight leading-tight">
                    Sistem Penerimaan Murid Baru Terintegrasi SIAKAD
                </h1>
                <p class="mt-4 text-base sm:text-lg text-[#545B52] leading-relaxed">
                    Sistem pendaftaran online SMAN 1 Terbanggi Besar dengan teknologi Single Sign-On (SSO OIDC). Calon siswa yang dinyatakan lulus otomatis tersinkronisasi ke sistem akademik tanpa entri data berulang.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-3">
                    @if($periodeAktif && $periodeAktif->is_aktif)
                        <x-button as="a" href="{{ route('auth.register') }}" variant="primary" class="shadow-sm">
                            Daftar SPMB Baru
                        </x-button>
                    @endif
                    <x-button as="a" href="{{ route('spmb.login') }}" variant="secondary" class="bg-white/90 shadow-2xs hover:bg-white text-[#0E6026] font-semibold border border-[#039834]/30">
                        Masuk Portal SPMB
                    </x-button>
                    <x-button as="a" href="{{ route('siakad.login') }}" variant="ghost" class="bg-white/80 hover:bg-white text-[#1C2620] font-semibold border border-[#C9CDC3]">
                        Masuk Portal SIAKAD
                    </x-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pilihan 2 Portal Terpisah (SPMB vs SIAKAD) -->
    <div class="py-12 bg-white/70 backdrop-blur-sm border-b border-[#E1E4DE]">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="max-w-2xl mb-8">
                <span class="text-xs font-bold text-[#0E6026] tracking-wider uppercase">Akses Terpisah</span>
                <h2 class="text-2xl font-bold text-[#1C2620] mt-1">Dua Portal Layanan Sistem</h2>
                <p class="text-sm text-[#545B52] mt-1">Silakan pilih portal sesuai tujuan Anda. Masuk dengan akun SSO masing-masing.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Portal SPMB -->
                <div class="p-6 rounded-2xl border-2 border-[#0E6026]/20 bg-gradient-to-br from-[#E7F4EA]/40 via-white to-white shadow-md hover:border-[#0E6026]/40 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/30">
                                Calon Murid Baru
                            </span>
                            <span class="text-xs text-[#545B52] font-semibold">Tahun Masuk 2026/2027</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#1C2620]">1. Portal Pendaftaran Ulang (SPMB)</h3>
                        <p class="text-sm text-[#545B52] mt-2 leading-relaxed">
                            Khusus calon murid baru untuk melengkapi biodata pendaftaran ulang, mengunggah berkas fisik (KK, Akta, Surat Pernyataan), dan mengecek jadwal verifikasi berkas oleh panitia.
                        </p>
                        <ul class="mt-4 space-y-2 text-xs text-[#545B52]">
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Pengisian Formulir Biodata & Data Orang Tua
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Unggah & Validasi Dokumen Fisik
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Cek Status Kelulusan & Kredensial SIAKAD
                            </li>
                        </ul>
                    </div>
                    <div class="mt-6 pt-5 border-t border-[#E1E4DE] flex items-center justify-between gap-3">
                        <a href="{{ route('auth.register') }}" class="text-xs font-semibold text-[#545B52] hover:text-[#0E6026]">
                            Belum punya akun? Buat Akun
                        </a>
                        <x-button as="a" href="{{ route('spmb.login') }}" variant="primary" class="shadow-sm">
                            Masuk Portal SPMB &rarr;
                        </x-button>
                    </div>
                </div>

                <!-- Card Portal SIAKAD -->
                <div class="p-6 rounded-2xl border-2 border-[#1C2620]/15 bg-white shadow-md hover:border-[#1C2620]/30 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#F3F5F2] text-[#1C2620] border border-[#C9CDC3]">
                                Siswa Aktif &bull; Guru &bull; Admin
                            </span>
                            <span class="text-xs text-[#0E6026] font-semibold">Semester Ganjil 2026/2027</span>
                        </div>
                        <h3 class="text-xl font-bold text-[#1C2620]">2. Portal Akademik (SIAKAD)</h3>
                        <p class="text-sm text-[#545B52] mt-2 leading-relaxed">
                            Khusus siswa yang telah resmi diterima dan berstatus aktif, guru pengajar, serta staf tata usaha untuk mengelola informasi kependidikan, pembagian rombongan belajar, dan semester berjalan.
                        </p>
                        <ul class="mt-4 space-y-2 text-xs text-[#545B52]">
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Data Induk Siswa & Riwayat Sekolah
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Rombongan Belajar (Kelas) & Teman Sekelas
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-[#0E6026] font-bold">&check;</span> Semester Berjalan & Informasi Kurikulum
                            </li>
                        </ul>
                    </div>
                    <div class="mt-6 pt-5 border-t border-[#E1E4DE] flex items-center justify-between gap-3">
                        <span class="text-xs text-[#545B52]">
                            Gunakan NIS / NIP / Email
                        </span>
                        <x-button as="a" href="{{ route('siakad.login') }}" variant="secondary" class="bg-[#1C2620] text-white hover:bg-black">
                            Masuk Portal SIAKAD &rarr;
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi 3 Pilar Utama -->
    <div class="py-12 bg-white/40 border-b border-[#E1E4DE]">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card title="1. Satu Akun (SSO OIDC)" subtitle="OpenID Connect Identity Provider" class="shadow-md bg-white">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Calon siswa, guru, dan staf sekolah cukup menggunakan satu identitas akun terpusat untuk mengakses portal SPMB maupun portal akademik SIAKAD.
                    </p>
                </x-card>

                <x-card title="2. Pendaftaran Digital Cepat" subtitle="Formulir & Unggah Berkas Online" class="shadow-md bg-white">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Pengisian formulir biodata pendaftar, data orang tua, dan unggah berkas persyaratan (KK, Akta Lahir, Rapor) dilakukan secara daring.
                    </p>
                </x-card>

                <x-card title="3. Sinkronisasi Otomatis" subtitle="Zero Manual Re-Entry" class="shadow-md bg-white">
                    <p class="text-sm text-[#545B52] leading-relaxed">
                        Saat status kelulusan ditetapkan oleh operator sekolah, data pokok siswa langsung dibuat di SIAKAD tanpa perlu entri manual berulang.
                    </p>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Alur Pendaftaran Ringkas -->
    <div class="py-16 bg-white/30">
        <div class="w-full px-6 sm:px-10 lg:px-16">
            <div class="mb-10 text-left">
                <h2 class="text-2xl font-bold text-[#1C2620]">Alur Pendaftaran SPMB</h2>
                <p class="mt-1 text-sm text-[#545B52]">4 tahapan mudah penerimaan murid baru hingga terdaftar di SIAKAD.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white shadow-md">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        1
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Registrasi Akun</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Calon siswa mendaftar akun SSO dengan NISN dan email aktif, lalu mengisi formulir pendaftaran.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white shadow-md">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        2
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Unggah Berkas</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Unggah scan berkas asli Kartu Keluarga, Akta Kelahiran, dan dokumen pendukung sesuai persyaratan sekolah.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white shadow-md">
                    <div class="w-8 h-8 rounded-lg bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-sm mb-3">
                        3
                    </div>
                    <h3 class="text-base font-semibold text-[#1C2620] mb-1">Verifikasi Operator</h3>
                    <p class="text-xs text-[#545B52] leading-relaxed">
                        Panitia SPMB memvalidasi keabsahan data dan berkas yang diajukan oleh calon siswa secara online.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white shadow-md">
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
