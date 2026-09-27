<x-layouts.admin title="Data Siswa SIAKAD" heading="Daftar Siswa Aktif SIAKAD">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Data seluruh siswa aktif hasil sinkronisasi otomatis PPDB dan penempatan kelas akademik.</p>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">L/P</th>
                            <th class="py-3 px-3">Kelas / Rombel</th>
                            <th class="py-3 px-3">Tahun Masuk</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 tabular-nums font-mono">0071234567</td>
                            <td class="py-3 px-3 font-semibold">Budi Santoso</td>
                            <td class="py-3 px-3">L</td>
                            <td class="py-3 px-3">Kelas X MIPA 1</td>
                            <td class="py-3 px-3 tabular-nums">2026</td>
                            <td class="py-3 px-3"><x-badge status="aktif">Siswa Aktif</x-badge></td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Detail</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
