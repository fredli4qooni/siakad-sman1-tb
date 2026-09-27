<x-layouts.auth title="Registrasi Akun Calon Siswa" heading="Daftar Akun Calon Siswa">
    <p class="text-xs text-[#545B52] mb-6 text-center">
        Akun yang dibuat akan digunakan untuk proses seleksi PPDB hingga menjadi akun SIAKAD saat dinyatakan lulus.
    </p>

    <form method="POST" action="{{ route('auth.register') }}" class="space-y-4">
        @csrf

        <x-input
            label="Nama Lengkap"
            name="nama"
            type="text"
            required
            placeholder="Sesuai Akta Kelahiran / Ijazah"
            :value="old('nama')"
        />

        <x-input
            label="NISN (Nomor Induk Siswa Nasional)"
            name="nisn"
            type="text"
            required
            placeholder="10 digit nomor NISN aktif"
            class="tabular-nums"
            :value="old('nisn')"
        />

        <x-input
            label="Alamat Email Aktif"
            name="email"
            type="email"
            required
            placeholder="contoh@gmail.com"
            :value="old('email')"
        />

        <x-input
            label="Kata Sandi"
            name="password"
            type="password"
            required
            placeholder="Minimal 8 karakter"
            help="Gunakan kombinasi huruf dan angka agar aman."
        />

        <x-input
            label="Konfirmasi Kata Sandi"
            name="password_confirmation"
            type="password"
            required
            placeholder="Ulangi kata sandi di atas"
        />

        <div class="pt-2">
            <x-button type="submit" variant="primary" class="w-full">
                Daftar Akun Sekarang
            </x-button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-[#E1E4DE] text-center text-xs text-[#545B52]">
        Sudah memiliki akun pendaftaran?
        <a href="{{ route('auth.login') }}" class="text-[#0E6026] font-semibold hover:underline ml-1">
            Masuk SSO
        </a>
    </div>
</x-layouts.auth>
