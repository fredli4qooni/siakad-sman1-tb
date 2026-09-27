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

        <!-- Card Form Pencarian Status -->
        <div class="max-w-xl mb-10">
            <x-card>
                <form action="{{ route('ppdb.pengumuman') }}" method="GET" class="space-y-4">
                    <x-input
                        label="Nomor Pendaftaran atau NISN"
                        name="keyword"
                        placeholder="Contoh: PPDB-2026-0001 atau 0071234567"
                        required
                        class="tabular-nums"
                    />

                    <div>
                        <x-button type="submit" variant="primary" class="w-full">
                            Periksa Hasil Seleksi
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <!-- Informasi Bagi yang Lulus -->
        <div class="max-w-2xl">
            <x-alert type="info" title="Informasi Penting Peserta yang Dinyatakan LULUS:">
                Bagi calon peserta didik yang dinyatakan <strong>LULUS</strong>, data Anda otomatis tersinkronisasi ke sistem SIAKAD SMAN 1 Terbanggi Besar. Anda dapat langsung login ke portal siswa menggunakan akun pendaftaran yang telah dibuat tanpa perlu mendaftar ulang.
            </x-alert>
        </div>
    </div>
</x-layouts.guest>
