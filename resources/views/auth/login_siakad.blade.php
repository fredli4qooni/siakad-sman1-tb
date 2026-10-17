<x-layouts.auth title="Masuk Portal SIAKAD" heading="Portal Akademik (SIAKAD)">
    <div class="mb-5 text-center">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/20 mb-2">
            Siswa Aktif &bull; Guru &bull; Administrator
        </span>
        <p class="text-xs text-[#545B52]">
            Masuk untuk mengakses data induk kependidikan, informasi kelas rombel, dan semester akademik berjalan.
        </p>
    </div>

    <form method="POST" action="{{ route('siakad.login.post') }}" class="space-y-4">
        @csrf

        <x-input
            label="NIS / NISN / NIP / Email SSO"
            name="identity"
            type="text"
            required
            placeholder="NIS Siswa, NIP Guru, atau Email SSO"
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
            <span class="text-xs text-[#0E6026] font-semibold">Tahun Pelajaran 2026/2027</span>
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full py-2.5 font-semibold shadow-sm">
                Masuk ke Portal SIAKAD
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-[#E1E4DE] text-center text-xs text-[#545B52] space-y-3">
        <div class="p-3.5 rounded-xl bg-[#F3F5F2] border border-[#E1E4DE] text-[11px] text-[#545B52] flex items-center justify-between gap-3 text-left">
            <div>
                <span class="font-bold text-[#1C2620] block">Calon Murid Baru?</span>
                <span>Ingin daftar ulang atau unggah berkas fisik?</span>
            </div>
            <a href="{{ route('spmb.login') }}" class="font-bold text-[#0E6026] hover:underline whitespace-nowrap bg-white px-3 py-1.5 rounded-lg border border-[#C9CDC3]">
                Login SPMB &rarr;
            </a>
        </div>

        <div class="text-[11px] text-[#545B52]">
            SIAKAD SMAN 1 Terbanggi Besar terintegrasi Single Sign-On (SSO OIDC).
        </div>
    </div>
</x-layouts.auth>
