<x-layouts.admin title="Sync Logs" heading="Audit Log Sinkronisasi PPDB → SIAKAD">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-[#545B52]">Seluruh transaksi otomasi pembuatan data siswa SIAKAD dari calon siswa yang dinyatakan LULUS tercatat di sini.</p>
            </div>
            <div>
                <x-button type="button" variant="primary">
                    Jalankan Sinkronisasi Ulang (Batch Sync)
                </x-button>
            </div>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">ID Log</th>
                            <th class="py-3 px-3">Waktu Sinkron</th>
                            <th class="py-3 px-3">Pendaftar ID</th>
                            <th class="py-3 px-3">Siswa ID (SIAKAD)</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Catatan / Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono">#1</td>
                            <td class="py-3 px-3 tabular-nums">2026-09-27 08:30:15</td>
                            <td class="py-3 px-3 font-medium">Budi Santoso (#101)</td>
                            <td class="py-3 px-3 font-medium text-[#0E6026]">Siswa #1</td>
                            <td class="py-3 px-3"><x-badge status="lulus">BERHASIL</x-badge></td>
                            <td class="py-3 px-3 text-[#545B52]">Data pokok, orang tua, dan tautan akun SSO selesai dimigrasi.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
