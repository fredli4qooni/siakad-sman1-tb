<x-layouts.guru title="Dashboard Guru — SIAKAD SMAN 1 TB">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#E1E4DE]">
            <div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Selamat Datang, Bapak/Ibu Guru</h1>
                <p class="text-xs text-[#545B52] mt-1">Sistem Informasi Akademik (SIAKAD) — SMAN 1 Terbanggi Besar</p>
            </div>
            <div>
                <x-badge status="aktif">Guru Aktif</x-badge>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Mata Pelajaran & Kelas Diampu" subtitle="Penugasan mengajar semester aktif">
                <p class="text-xs text-[#545B52] mb-4">Lihat daftar rombel kelas dan mata pelajaran yang Anda ampu pada tahun ajaran ini.</p>
                <x-button as="a" href="{{ route('guru.pengampu.index') }}" variant="secondary">
                    Buka Kelas Diampu
                </x-button>
            </x-card>

            <x-card title="Input & Rekap Nilai Siswa" subtitle="Penilaian hasil belajar">
                <p class="text-xs text-[#545B52] mb-4">Entri nilai tugas, UTS, UAS, dan capaian kompetensi untuk siswa di kelas binaan Anda.</p>
                <x-button as="a" href="{{ route('guru.nilai.index') }}" variant="primary">
                    Input Nilai Siswa
                </x-button>
            </x-card>
        </div>
    </div>
</x-layouts.guru>
