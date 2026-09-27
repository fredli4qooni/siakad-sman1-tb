<x-layouts.admin title="Penugasan Mengajar" heading="Alokasi Guru Pengampu">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Penetapan guru pengampu pada setiap rombel kelas dan mata pelajaran.</p>
            <x-button type="button" variant="primary">Tambah Penugasan</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama Guru</th>
                            <th class="py-3 px-3">Kelas</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-semibold">Drs. Ahmad Fauzi, M.Pd.</td>
                            <td class="py-3 px-3">Kelas X MIPA 1</td>
                            <td class="py-3 px-3 font-medium">Matematika Wajib</td>
                            <td class="py-3 px-3 tabular-nums">2026/2027 Ganjil</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Hapus</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
