<x-layouts.guru title="Kelas Diampu — SIAKAD SMAN 1 TB">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Daftar Kelas & Mata Pelajaran Diampu</h1>
            <p class="text-xs text-[#545B52] mt-1">Hanya kelas dan mapel berikut yang diberikan wewenang untuk Anda kelola nilainya.</p>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Nama Kelas</th>
                            <th class="py-3 px-3">Tingkat</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE]">
                        <tr class="hover:bg-[#F3F5F2] transition-colors">
                            <td class="py-3 px-3 tabular-nums">1</td>
                            <td class="py-3 px-3 font-semibold">Kelas X MIPA 1</td>
                            <td class="py-3 px-3">X (Fase E)</td>
                            <td class="py-3 px-3">Matematika Wajib</td>
                            <td class="py-3 px-3">2026/2027 Ganjil</td>
                            <td class="py-3 px-3 text-right">
                                <x-button as="a" href="{{ route('guru.nilai.index') }}" variant="ghost" class="text-xs">
                                    Kelola Nilai
                                </x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.guru>
