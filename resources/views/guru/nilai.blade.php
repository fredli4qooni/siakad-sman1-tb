<x-layouts.guru title="Input Nilai Siswa — SIAKAD SMAN 1 TB">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Input Nilai: Matematika Wajib (Kelas X MIPA 1)</h1>
                <p class="text-xs text-[#545B52] mt-1">KKM: 75 | Semester Ganjil 2026/2027</p>
            </div>
            <div>
                <x-button type="button" variant="primary">
                    Simpan Seluruh Nilai
                </x-button>
            </div>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">Nama Siswa</th>
                            <th class="py-3 px-3 w-28">Nilai Tugas</th>
                            <th class="py-3 px-3 w-28">Nilai UTS</th>
                            <th class="py-3 px-3 w-28">Nilai UAS</th>
                            <th class="py-3 px-3 w-28 text-right">Nilai Akhir</th>
                            <th class="py-3 px-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE]">
                        <tr class="hover:bg-[#F3F5F2] transition-colors">
                            <td class="py-3 px-3 tabular-nums">0071234567</td>
                            <td class="py-3 px-3 font-medium">Budi Santoso</td>
                            <td class="py-2 px-3">
                                <input type="number" step="0.1" value="85.0" class="w-full rounded border border-[#C9CDC3] px-2 py-1 text-sm tabular-nums text-right focus:border-[#039834] focus:outline-none" />
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" step="0.1" value="80.0" class="w-full rounded border border-[#C9CDC3] px-2 py-1 text-sm tabular-nums text-right focus:border-[#039834] focus:outline-none" />
                            </td>
                            <td class="py-2 px-3">
                                <input type="number" step="0.1" value="88.0" class="w-full rounded border border-[#C9CDC3] px-2 py-1 text-sm tabular-nums text-right focus:border-[#039834] focus:outline-none" />
                            </td>
                            <td class="py-3 px-3 tabular-nums font-bold text-[#0E6026] text-right">84.3</td>
                            <td class="py-3 px-3"><x-badge status="lulus">Tuntas</x-badge></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.guru>
