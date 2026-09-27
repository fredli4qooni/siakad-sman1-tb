<x-layouts.pendaftar title="Dashboard Pendaftar — SMAN 1 TB">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#E1E4DE]">
            <div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Selamat Datang, {{ auth()->user()->nama ?? 'Calon Siswa' }}</h1>
                <p class="text-xs text-[#545B52] mt-1">Portal Pendaftaran Peserta Didik Baru SMAN 1 Terbanggi Besar</p>
            </div>
            <div>
                <x-badge status="menunggu">Menunggu Verifikasi</x-badge>
            </div>
        </div>

        <!-- 3 Kartu Progres Pendaftaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <x-card title="1. Formulir Biodata" subtitle="Data pribadi & orang tua">
                <p class="text-xs text-[#545B52] mb-4">Pastikan seluruh data pokok NISN, nama, alamat, dan identitas orang tua terisi lengkap.</p>
                <x-button as="a" href="{{ route('pendaftar.formulir') }}" variant="secondary" class="w-full">
                    Lengkapi Formulir
                </x-button>
            </x-card>

            <x-card title="2. Unggah Dokumen" subtitle="Berkas KK, Akta, Rapor">
                <p class="text-xs text-[#545B52] mb-4">Unggah scan berkas asli sesuai persyaratan agar segera dapat diverifikasi panitia.</p>
                <x-button as="a" href="{{ route('pendaftar.berkas') }}" variant="secondary" class="w-full">
                    Kelola Berkas
                </x-button>
            </x-card>

            <x-card title="3. Hasil Kelulusan" subtitle="Status seleksi & SIAKAD">
                <p class="text-xs text-[#545B52] mb-4">Pantau pengumuman kelulusan dan sinkronisasi akun menuju SIAKAD secara real-time.</p>
                <x-button as="a" href="{{ route('pendaftar.kelulusan') }}" variant="secondary" class="w-full">
                    Lihat Status
                </x-button>
            </x-card>
        </div>
    </div>
</x-layouts.pendaftar>
