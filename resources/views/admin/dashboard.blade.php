<x-layouts.admin title="Dashboard Utama" heading="Ringkasan Sistem PPDB & SIAKAD">
    <div class="space-y-6">
        <!-- 4 Metrik Kartu Atas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card padding="p-4">
                <div class="text-xs text-[#545B52]">Total Pendaftar PPDB</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">348</div>
                <div class="text-[11px] text-[#0E6026] mt-0.5">Tahun Ajaran 2026/2027</div>
            </x-card>

            <x-card padding="p-4">
                <div class="text-xs text-[#545B52]">Berkas Terverifikasi</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">290</div>
                <div class="text-[11px] text-[#545B52] mt-0.5">58 Menunggu Tindakan</div>
            </x-card>

            <x-card padding="p-4">
                <div class="text-xs text-[#545B52]">Dinyatakan LULUS</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">250</div>
                <div class="text-[11px] text-[#545B52] mt-0.5">Dari Target 540 Kuota</div>
            </x-card>

            <x-card padding="p-4">
                <div class="text-xs text-[#545B52]">Tersinkron ke SIAKAD</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">250</div>
                <div class="text-[11px] text-[#0E6026] mt-0.5">100% Otomatis Berhasil</div>
            </x-card>
        </div>

        <!-- Bagian Monitoring Cepat PPDB dan Sinkronisasi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-card title="Aktivitas Pendaftaran Terbaru" subtitle="Pendaftar terbaru yang masuk ke sistem">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-2.5 px-2">No. Daftar</th>
                                <th class="py-2.5 px-2">Nama</th>
                                <th class="py-2.5 px-2">Asal Sekolah</th>
                                <th class="py-2.5 px-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            <tr class="hover:bg-[#F3F5F2]">
                                <td class="py-2.5 px-2 font-mono">PPDB-2026-0348</td>
                                <td class="py-2.5 px-2 font-medium">Ahmad Rizky Pratama</td>
                                <td class="py-2.5 px-2 text-[#545B52]">SMPN 1 Terbanggi Besar</td>
                                <td class="py-2.5 px-2"><x-badge status="menunggu">Menunggu</x-badge></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>

            <x-card title="Log Sinkronisasi PPDB → SIAKAD" subtitle="Audit otomatisasi data saat kelulusan ditetapkan">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-2.5 px-2">Waktu</th>
                                <th class="py-2.5 px-2">Nama Siswa</th>
                                <th class="py-2.5 px-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            <tr class="hover:bg-[#F3F5F2]">
                                <td class="py-2.5 px-2 text-[#545B52]">Hari ini, 08:30</td>
                                <td class="py-2.5 px-2 font-medium">Budi Santoso</td>
                                <td class="py-2.5 px-2"><x-badge status="lulus">Berhasil (ID #1)</x-badge></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.admin>
