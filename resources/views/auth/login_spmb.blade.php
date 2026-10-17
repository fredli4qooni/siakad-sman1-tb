<x-layouts.auth title="Masuk Portal SPMB" heading="Portal Pendaftaran Ulang (SPMB)">
    <div class="mb-5 text-center">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/20 mb-2">
            Khusus Calon Murid Baru
        </span>
        <p class="text-xs text-[#545B52]">
            Masuk untuk melengkapi formulir daftar ulang, unggah berkas fisik, dan cek jadwal verifikasi.
        </p>
    </div>

    <form method="POST" action="{{ route('spmb.login.post') }}" class="space-y-4">
        @csrf

        <x-input
            label="Email atau NISN Calon Murid"
            name="identity"
            type="text"
            required
            placeholder="nama@email.com atau NISN 10 digit"
            :value="old('identity')"
        />

        <x-input
            label="Kata Sandi (Password)"
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
            <span class="text-xs text-[#545B52]">Pendaftaran Ulang 2026/2027</span>
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full py-2.5 font-semibold shadow-sm">
                Masuk ke Portal SPMB
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-[#E1E4DE] text-center text-xs text-[#545B52] space-y-3">
        <div>
            Belum memiliki akun pendaftaran ulang?
            <a href="{{ route('auth.register') }}" class="text-[#0E6026] font-semibold hover:underline ml-1">
                Daftar Akun SPMB Baru
            </a>
        </div>

        <div class="p-3 rounded-xl bg-[#F3F5F2] border border-[#E1E4DE] text-[11px] text-[#545B52] flex items-center justify-between gap-2 text-left">
            <span>Sudah diterima & ingin akses kelas?</span>
            <a href="{{ route('siakad.login') }}" class="font-bold text-[#0E6026] hover:underline whitespace-nowrap">
                Login SIAKAD &rarr;
            </a>
        </div>
    </div>
</x-layouts.auth>
