<x-layouts.pendaftar title="Nilai Akademik — SIAKAD SMAN 1 TB">
    <div class="max-w-5xl mx-auto space-y-6">
        <div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E7F4EA] text-[#0E6026] mb-2">
                Transkrip Akademik
            </span>
            <h1 class="text-2xl font-bold text-[#1C2620]">Daftar Nilai Akademik Siswa</h1>
            <p class="text-xs text-[#545B52] mt-1">Rekapitulasi capaian hasil belajar per mata pelajaran yang diinput guru pengampu.</p>
        </div>

        <x-card title="Semester Ganjil 2026/2027">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3 text-right">KKM</th>
                            <th class="py-3 px-3 text-right">Tugas</th>
                            <th class="py-3 px-3 text-right">UTS</th>
                            <th class="py-3 px-3 text-right">UAS</th>
                            <th class="py-3 px-3 text-right">Nilai Akhir</th>
                            <th class="py-3 px-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE]">
                        <tr class="hover:bg-[#F3F5F2] transition-colors">
                            <td class="py-3 px-3 tabular-nums">1</td>
                            <td class="py-3 px-3 font-medium">Matematika Wajib</td>
                            <td class="py-3 px-3 text-right tabular-nums">75</td>
                            <td class="py-3 px-3 text-right tabular-nums">85.0</td>
                            <td class="py-3 px-3 text-right tabular-nums">82.0</td>
                            <td class="py-3 px-3 text-right tabular-nums">88.0</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold text-[#0E6026]">85.0</td>
                            <td class="py-3 px-3"><x-badge status="lulus">Tuntas</x-badge></td>
                        </tr>
                        <tr class="hover:bg-[#F3F5F2] transition-colors">
                            <td class="py-3 px-3 tabular-nums">2</td>
                            <td class="py-3 px-3 font-medium">Bahasa Indonesia</td>
                            <td class="py-3 px-3 text-right tabular-nums">75</td>
                            <td class="py-3 px-3 text-right tabular-nums">88.0</td>
                            <td class="py-3 px-3 text-right tabular-nums">85.0</td>
                            <td class="py-3 px-3 text-right tabular-nums">90.0</td>
                            <td class="py-3 px-3 text-right tabular-nums font-bold text-[#0E6026]">88.0</td>
                            <td class="py-3 px-3"><x-badge status="lulus">Tuntas</x-badge></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.pendaftar>
