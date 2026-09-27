<x-layouts.pendaftar title="Unggah Berkas Persyaratan — SMAN 1 TB">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Unggah Berkas Persyaratan PPDB</h1>
            <p class="text-xs text-[#545B52] mt-1">Format file yang diperbolehkan: PDF, JPG, PNG dengan ukuran maksimum 2MB per berkas.</p>
        </div>

        <x-card title="Daftar Dokumen Persyaratan">
            <div class="space-y-6">
                <!-- Berkas 1: Kartu Keluarga -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold text-sm text-[#1C2620]">1. Kartu Keluarga (KK)</div>
                        <div class="text-xs text-[#545B52]">Scan asli dokumen Kartu Keluarga yang masih berlaku</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge status="draft">Belum Diunggah</x-badge>
                        <input type="file" class="text-xs text-[#545B52]" />
                    </div>
                </div>

                <!-- Berkas 2: Akta Kelahiran -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold text-sm text-[#1C2620]">2. Akta Kelahiran</div>
                        <div class="text-xs text-[#545B52]">Scan akta lahir calon peserta didik baru</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge status="draft">Belum Diunggah</x-badge>
                        <input type="file" class="text-xs text-[#545B52]" />
                    </div>
                </div>

                <!-- Berkas 3: SKL / Ijazah SMP -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold text-sm text-[#1C2620]">3. Ijazah / Surat Keterangan Lulus (SKL)</div>
                        <div class="text-xs text-[#545B52]">Scan ijazah atau surat keterangan lulus SMP/MTs</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge status="draft">Belum Diunggah</x-badge>
                        <input type="file" class="text-xs text-[#545B52]" />
                    </div>
                </div>

                <!-- Berkas 4: Buku Rapor -->
                <div class="p-4 rounded-xl border border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold text-sm text-[#1C2620]">4. Nilai Rapor SMP (Semester 1–5)</div>
                        <div class="text-xs text-[#545B52]">Scan legalisir nilai rapor semester 1 sampai 5</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge status="draft">Belum Diunggah</x-badge>
                        <input type="file" class="text-xs text-[#545B52]" />
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-[#E1E4DE] flex justify-end">
                <x-button type="button" variant="primary">
                    Unggah Seluruh Berkas
                </x-button>
            </div>
        </x-card>
    </div>
</x-layouts.pendaftar>
