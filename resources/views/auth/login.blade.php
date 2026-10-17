<x-layouts.auth title="Masuk SSO" heading="Pilih Portal Masuk">
    <p class="text-xs text-[#545B52] mb-5 text-center">
        Sistem SMAN 1 Terbanggi Besar menyediakan 2 portal masuk terpisah berbasis SSO OIDC:
    </p>

    <!-- 2 Pilihan Portal Masuk Cepat -->
    <div class="space-y-3 mb-6">
        <a href="{{ route('spmb.login') }}" class="group block p-4 rounded-xl border border-[#039834]/40 bg-[#E7F4EA]/40 hover:bg-[#E7F4EA] transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-xs">
                        SPMB
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#1C2620] group-hover:text-[#0E6026] transition-colors">
                            Portal Pendaftaran Ulang
                        </div>
                        <div class="text-[11px] text-[#545B52]">
                            Calon murid baru: isi formulir, berkas fisik & hasil seleksi
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-[#0E6026] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>

        <a href="{{ route('siakad.login') }}" class="group block p-4 rounded-xl border border-[#C9CDC3] bg-white hover:border-[#039834] hover:bg-[#F3F5F2] transition-all shadow-2xs">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-[#1C2620] text-white flex items-center justify-center font-bold text-xs">
                        SIAKAD
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#1C2620] group-hover:text-[#0E6026] transition-colors">
                            Portal Akademik (SIAKAD)
                        </div>
                        <div class="text-[11px] text-[#545B52]">
                            Siswa aktif, dewan guru & admin: kelas, semester & data siswa
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-[#545B52] group-hover:text-[#0E6026] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Login Form Fallback Langsung -->
    <div class="pt-4 border-t border-[#E1E4DE]">
        <div class="text-xs font-semibold text-[#1C2620] mb-3">Atau Masuk Langsung via Formulir SSO:</div>

        <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
            @csrf

            <x-input
                label="Email atau NISN / NIS / NIP"
                name="identity"
                type="text"
                required
                placeholder="nama@email.com atau NISN / NIS"
                :value="old('identity')"
            />

            <x-input
                label="Kata Sandi"
                name="password"
                type="password"
                required
                placeholder="••••••••"
            />

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-[#C9CDC3] text-[#0E6026] focus:ring-[#039834]">
                    <span class="text-[#545B52]">Ingat sesi saya</span>
                </label>
                <a href="#" class="text-[#0E6026] hover:underline font-medium">Lupa kata sandi?</a>
            </div>

            <div class="pt-2">
                <x-button type="submit" variant="primary" class="w-full">
                    Masuk Sekarang
                </x-button>
            </div>
        </form>
    </div>

    <div class="mt-6 pt-5 border-t border-[#E1E4DE] text-center text-xs text-[#545B52]">
        Belum memiliki akun pendaftaran?
        <a href="{{ route('auth.register') }}" class="text-[#0E6026] font-semibold hover:underline ml-1">
            Daftar Calon Murid Baru
        </a>
    </div>
</x-layouts.auth>
