<x-layouts.admin title="Mata Pelajaran" heading="Master Mata Pelajaran">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Daftar kurikulum mata pelajaran dan standar KKM kelulusan.</p>
            <x-button type="button" variant="primary">Tambah Mapel</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Kode Mapel</th>
                            <th class="py-3 px-3">Nama Mata Pelajaran</th>
                            <th class="py-3 px-3 text-right">Nilai KKM</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono font-semibold">MTK-WAJIB</td>
                            <td class="py-3 px-3 font-medium">Matematika Wajib</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold">75</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Ubah</x-button>
                            </td>
                        </tr>
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono font-semibold">BIN-WAJIB</td>
                            <td class="py-3 px-3 font-medium">Bahasa Indonesia</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold">75</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Ubah</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
