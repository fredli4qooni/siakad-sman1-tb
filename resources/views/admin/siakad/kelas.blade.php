<x-layouts.admin title="Manajemen Kelas" heading="Manajemen Kelas & Rombongan Belajar">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Struktur rombel kelas per tingkat dan penugasan wali kelas.</p>
            <x-button type="button" variant="primary">Tambah Kelas</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama Kelas</th>
                            <th class="py-3 px-3">Tingkat</th>
                            <th class="py-3 px-3">Wali Kelas</th>
                            <th class="py-3 px-3 text-right">Jumlah Siswa</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-semibold">Kelas X MIPA 1</td>
                            <td class="py-3 px-3">X (Fase E)</td>
                            <td class="py-3 px-3">Drs. Ahmad Fauzi, M.Pd.</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold">36 Siswa</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Kelola Rombel</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
