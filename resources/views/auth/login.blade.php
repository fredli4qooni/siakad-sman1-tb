<x-layouts.auth title="Masuk SSO" heading="Masuk ke Akun SSO">
    <p class="text-xs text-[#545B52] mb-6 text-center">
        Gunakan akun tunggal Anda untuk mengakses portal PPDB dan SIAKAD.
    </p>

    <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
        @csrf

        <x-input
            label="Email atau NISN / NIP"
            name="identity"
            type="text"
            required
            placeholder="nama@email.com atau NISN"
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

    <div class="mt-6 pt-6 border-t border-[#E1E4DE] text-center text-xs text-[#545B52]">
        Belum memiliki akun pendaftaran?
        <a href="{{ route('auth.register') }}" class="text-[#0E6026] font-semibold hover:underline ml-1">
            Daftar Calon Siswa
        </a>
    </div>
</x-layouts.auth>
