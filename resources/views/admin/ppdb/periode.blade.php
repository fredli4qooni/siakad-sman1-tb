<x-layouts.admin title="Periode & Kuota PPDB" heading="Manajemen Periode & Kuota PPDB">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Buka dan kelola jadwal gelombang pendaftaran serta kuota rombel peserta didik baru.</p>
            <x-button type="button" variant="primary">Tambah Periode PPDB</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3">Tanggal Buka</th>
                            <th class="py-3 px-3">Tanggal Tutup</th>
                            <th class="py-3 px-3 text-right">Kuota Siswa</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-semibold">2026/2027</td>
                            <td class="py-3 px-3 tabular-nums">01 Juli 2026</td>
                            <td class="py-3 px-3 tabular-nums">15 Juli 2026</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold">540</td>
                            <td class="py-3 px-3"><x-badge status="aktif">Aktif</x-badge></td>
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
