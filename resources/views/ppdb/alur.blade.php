<x-layouts.guest title="Alur Pendaftaran & Kuota — PPDB SMAN 1 TB">
    <div class="w-full px-6 sm:px-10 lg:px-16 py-12">
        <div class="mb-8">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E7F4EA] text-[#0E6026] mb-2">
                Informasi Resmi
            </span>
            <h1 class="text-3xl font-bold text-[#1C2620]">Alur Pendaftaran & Ketentuan Kuota</h1>
            <p class="mt-1 text-sm text-[#545B52]">
                Panduan lengkap tahapan pendaftaran penerimaan peserta didik baru SMAN 1 Terbanggi Besar.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-card title="Tahapan Pelaksanaan PPDB">
                    <ol class="space-y-4 text-sm text-[#1C2620]">
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</span>
                            <div>
                                <div class="font-semibold">Pendaftaran Akun Daring</div>
                                <div class="text-xs text-[#545B52] mt-0.5">Calon siswa mendaftarkan akun menggunakan NISN dan email aktif di portal SSO resmi.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</span>
                            <div>
                                <div class="font-semibold">Pengisian Formulir Lengkap</div>
                                <div class="text-xs text-[#545B52] mt-0.5">Mengisi biodata pribadi, data asal sekolah, alamat domisili, dan data orang tua/wali siswa.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</span>
                            <div>
                                <div class="font-semibold">Unggah Dokumen Asli</div>
                                <div class="text-xs text-[#545B52] mt-0.5">Mengunggah scan berkas asli: Kartu Keluarga, Akta Kelahiran, Surat Keterangan Lulus / Ijazah, dan Rapor. Maksimal 2MB per file (PDF/JPG/PNG).</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#E7F4EA] text-[#0E6026] flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">4</span>
                            <div>
                                <div class="font-semibold">Verifikasi Berkas oleh Panitia</div>
                                <div class="text-xs text-[#545B52] mt-0.5">Operator sekolah memverifikasi kesesuaian dokumen yang diunggah secara digital.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-full bg-[#0E6026] text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">5</span>
                            <div>
                                <div class="font-semibold">Pengumuman & Sinkronisasi SIAKAD</div>
                                <div class="text-xs text-[#545B52] mt-0.5">Pendaftar yang lulus seleksi otomatis terdaftar di SIAKAD tanpa perlu entri berkas ulang.</div>
                            </div>
                        </li>
                    </ol>
                </x-card>
            </div>

            <div class="space-y-6">
                <x-card title="Informasi Kuota Penerimaan">
                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-[#F3F5F2] border border-[#E1E4DE]">
                            <div class="text-xs text-[#545B52]">Target Kuota Siswa Baru</div>
                            <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                                {{ $periodeAktif ? number_format($periodeAktif->kuota, 0, ',', '.') : 540 }} Siswa
                            </div>
                            <div class="text-xs text-[#545B52] mt-0.5">
                                Tahun Ajaran {{ $periodeAktif ? $periodeAktif->tahun_ajaran : '2026/2027' }}
                            </div>
                        </div>

                        <div class="text-xs text-[#545B52] space-y-2">
                            <div class="font-medium text-[#1C2620]">Persyaratan Berkas:</div>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Scan Kartu Keluarga (KK) Asli</li>
                                <li>Scan Akta Kelahiran Asli</li>
                                <li>Scan Ijazah / SKL SMP/MTs</li>
                                <li>Scan Rapor Semester 1 - 5</li>
                            </ul>
                        </div>

                        <div class="pt-2">
                            @if($periodeAktif && $periodeAktif->is_aktif)
                                <x-button as="a" href="{{ route('auth.register') }}" variant="primary" class="w-full">
                                    Daftar Sekarang
                                </x-button>
                            @else
                                <x-button as="a" href="{{ route('auth.login') }}" variant="secondary" class="w-full">
                                    Masuk SSO
                                </x-button>
                            @endif
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.guest>
