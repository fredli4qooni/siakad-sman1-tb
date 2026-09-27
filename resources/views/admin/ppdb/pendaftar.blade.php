<x-layouts.admin title="Verifikasi Pendaftar" heading="Verifikasi Berkas Calon Siswa">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Periksa kelengkapan formulir dan berkas asli (KK, Akta Lahir, SKL, Rapor) yang diunggah pendaftar.</p>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No. Pendaftaran</th>
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">Asal Sekolah</th>
                            <th class="py-3 px-3">Status Berkas</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono">PPDB-2026-0001</td>
                            <td class="py-3 px-3 tabular-nums">0071234567</td>
                            <td class="py-3 px-3 font-semibold">Budi Santoso</td>
                            <td class="py-3 px-3">SMPN 1 Terbanggi Besar</td>
                            <td class="py-3 px-3"><x-badge status="menunggu">Menunggu Verifikasi</x-badge></td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="primary" class="text-xs py-1">Verifikasi Berkas</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
