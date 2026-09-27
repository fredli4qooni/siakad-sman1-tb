<x-layouts.pendaftar title="Status Kelulusan — SMAN 1 TB">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Status Kelulusan PPDB</h1>
            <p class="text-xs text-[#545B52] mt-1">Hasil seleksi penerimaan peserta didik baru SMAN 1 Terbanggi Besar.</p>
        </div>

        <x-card>
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#E7F4EA] text-[#0E6026] mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#1C2620]">Status: Menunggu Pengumuman Resmi</h2>
                <p class="text-xs text-[#545B52] max-w-md mx-auto mt-2">
                    Proses verifikasi berkas dan sidang penetapan kelulusan sedang berlangsung oleh panitia PPDB. Hasil seleksi akan diumumkan sesuai jadwal kalender akademik.
                </p>

                <div class="mt-6 inline-flex gap-3">
                    <x-badge status="menunggu">Sedang Diverifikasi</x-badge>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.pendaftar>
