<x-layouts.admin title="Hasil Seleksi PPDB" heading="Penetapan Hasil Seleksi & Kelulusan">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Tetapkan status kelulusan peserta. Menetapkan status LULUS akan langsung memicu pembuatan siswa di SIAKAD.</p>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No. Daftar</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">Asal Sekolah</th>
                            <th class="py-3 px-3">Status Seleksi</th>
                            <th class="py-3 px-3">Status Sinkronisasi</th>
                            <th class="py-3 px-3 text-right">Penetapan Kelulusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono">PPDB-2026-0001</td>
                            <td class="py-3 px-3 font-semibold">Budi Santoso</td>
                            <td class="py-3 px-3">SMPN 1 Terbanggi Besar</td>
                            <td class="py-3 px-3"><x-badge status="lulus">LULUS</x-badge></td>
                            <td class="py-3 px-3"><x-badge status="lulus">Tersinkron SIAKAD</x-badge></td>
                            <td class="py-3 px-3 text-right space-x-1">
                                <x-button type="button" variant="primary" class="text-xs py-1">Ubah Status</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
