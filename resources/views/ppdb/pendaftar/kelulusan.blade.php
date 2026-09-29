<x-layouts.pendaftar title="Status Kelulusan" heading="Status & Hasil Kelulusan">
    <div class="space-y-6">
        <!-- Header Page with Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E1E4DE]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                        Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}
                    </span>
                    <span class="text-xs text-[#545B52]">&bull; SMAN 1 Terbanggi Besar</span>
                </div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Pengumuman Hasil Seleksi PPDB</h1>
                <p class="text-xs text-[#545B52] mt-0.5">Surat Keputusan Resmi Panitia Penerimaan Peserta Didik Baru</p>
            </div>
            <div class="flex items-center gap-3">
                <x-button as="a" href="{{ route('pendaftar.dashboard') }}" variant="secondary" class="text-xs">
                    Kembali ke Dasbor
                </x-button>
            </div>
        </div>

        @if($hasilSeleksi && $hasilSeleksi->status === 'LULUS')
            <!-- Official Decision Banner (Left-aligned, elegant framed card) -->
            <div class="rounded-xl border border-[#039834] bg-[#E7F4EA] p-6 lg:p-7 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#039834] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-[#0E6026] border border-[#039834]">
                                DINYATAKAN LULUS SELEKSI
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#0E6026] mt-1">
                                Selamat, {{ $pendaftar->nama_lengkap }}!
                            </h2>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap">
                        <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="primary" class="text-xs">
                            Buka Portal SIAKAD
                        </x-button>
                        <x-button as="a" href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" variant="secondary" class="text-xs">
                            Cetak Surat Kelulusan
                        </x-button>
                    </div>
                </div>

                <p class="text-sm text-[#1C2620] leading-relaxed max-w-4xl">
                    Berdasarkan rapat pleno panitia penerimaan peserta didik baru SMAN 1 Terbanggi Besar, Anda resmi dinyatakan <strong>DITERIMA</strong> sebagai calon siswa baru Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}. Data pendaftaran pokok Anda telah otomatis disinkronisasi ke sistem akademik SIAKAD sekolah.
                </p>

                @if($hasilSeleksi->catatan)
                    <div class="p-3.5 rounded-lg bg-white border border-[#039834]/30 text-xs text-[#1C2620]">
                        <span class="font-bold text-[#0E6026]">Catatan Panitia:</span> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif
            </div>

            <!-- Two Column Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Card 1: Data Penetapan Pendaftar -->
                <x-card title="Data Penetapan Seleksi Calon Siswa" subtitle="Rincian identitas pendaftar pada berkas ketetapan panitia">
                    <dl class="divide-y divide-[#E1E4DE] text-xs">
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nomor Registrasi</dt>
                            <dd class="font-mono font-bold text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">NISN</dt>
                            <dd class="tabular-nums font-semibold text-[#1C2620]">{{ $pendaftar->nisn }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">NIK Siswa</dt>
                            <dd class="tabular-nums text-[#1C2620]">{{ $pendaftar->nik ?? '-' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nama Lengkap</dt>
                            <dd class="font-bold text-[#1C2620]">{{ $pendaftar->nama_lengkap }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Asal Sekolah</dt>
                            <dd class="font-medium text-[#1C2620]">{{ $pendaftar->asal_sekolah ?? '-' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Status Berkas</dt>
                            <dd>
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                    Berkas Terverifikasi
                                </span>
                            </dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Keputusan Akhir</dt>
                            <dd>
                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]">
                                    DITERIMA / LULUS SELEKSI
                                </span>
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <!-- Card 2: Status Integrasi SIAKAD & Tindak Lanjut -->
                <x-card title="Status Integrasi SIAKAD & Tindak Lanjut" subtitle="Informasi akun akademik dan alur langkah registrasi ulang">
                    <div class="space-y-4 text-xs">
                        <div class="p-4 rounded-xl bg-[#F3F5F2] border border-[#E1E4DE] space-y-2">
                            <div class="flex items-center justify-between pb-2 border-b border-[#E1E4DE]">
                                <span class="text-[#545B52]">Integrasi Data Pokok:</span>
                                <span class="font-bold text-[#0E6026] flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-[#039834]"></span>
                                    Tersinkron ke SIAKAD
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[#545B52]">Hak Akses Akun SSO:</span>
                                <span class="font-semibold text-[#1C2620]">Siswa Aktif Terverifikasi</span>
                            </div>
                            @if(auth()->user()->siswa && auth()->user()->siswa->kelas)
                                <div class="flex items-center justify-between">
                                    <span class="text-[#545B52]">Rombongan Belajar:</span>
                                    <span class="font-bold text-[#0E6026]">{{ auth()->user()->siswa->kelas->nama_kelas }}</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h4 class="font-bold text-[#1C2620] mb-2">Petunjuk Langkah Selanjutnya:</h4>
                            <ol class="space-y-2 text-[#545B52] list-decimal list-inside leading-relaxed">
                                <li>
                                    <strong class="text-[#1C2620]">Cetak Bukti Kelulusan:</strong> Unduh dan cetak surat bukti registrasi sebagai dokumen fisik kelulusan resmi.
                                </li>
                                <li>
                                    <strong class="text-[#1C2620]">Akses Portal SIAKAD:</strong> Buka menu SIAKAD menggunakan akun login yang sama tanpa perlu mendaftar ulang.
                                </li>
                                <li>
                                    <strong class="text-[#1C2620]">Cek Pembagian Kelas:</strong> Periksa alokasi rombongan belajar dan informasi wali kelas Anda melalui portal siswa.
                                </li>
                            </ol>
                        </div>

                        <div class="pt-2">
                            <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="primary" class="w-full text-xs">
                                Masuk ke Halaman Kelas Saya (SIAKAD)
                            </x-button>
                        </div>
                    </div>
                </x-card>
            </div>
        @elseif($hasilSeleksi && $hasilSeleksi->status === 'TIDAK_LULUS')
            <!-- Official Decision Banner (TIDAK LULUS) -->
            <div class="rounded-xl border border-[#C81210] bg-[#FBEAEA] p-6 lg:p-7 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#C81210] text-white flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-white text-[#C81210] border border-[#C81210]">
                                TIDAK LULUS SELEKSI
                            </span>
                            <h2 class="text-xl sm:text-2xl font-bold text-[#C81210] mt-1">
                                Mohon Maaf, {{ $pendaftar->nama_lengkap }}
                            </h2>
                        </div>
                    </div>
                </div>

                <p class="text-sm text-[#1C2620] leading-relaxed max-w-4xl">
                    Berdasarkan hasil pemeringkatan seleksi dan batas kuota daya tampung peserta didik baru SMAN 1 Terbanggi Besar Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }}, Anda dinyatakan <strong>BELUM MEMENUHI KUALIFIKASI</strong> pada jalur seleksi yang dipilih.
                </p>

                @if($hasilSeleksi->catatan)
                    <div class="p-3.5 rounded-lg bg-white border border-[#C81210]/30 text-xs text-[#1C2620]">
                        <span class="font-bold text-[#C81210]">Catatan Panitia:</span> {{ $hasilSeleksi->catatan }}
                    </div>
                @endif
            </div>

            <!-- Two Column Details Grid for TIDAK LULUS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <x-card title="Data Pendaftar" subtitle="Rincian identitas pendaftar pada berkas seleksi">
                    <dl class="divide-y divide-[#E1E4DE] text-xs">
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nomor Registrasi</dt>
                            <dd class="font-mono font-bold text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">NISN</dt>
                            <dd class="tabular-nums font-semibold text-[#1C2620]">{{ $pendaftar->nisn }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Nama Lengkap</dt>
                            <dd class="font-bold text-[#1C2620]">{{ $pendaftar->nama_lengkap }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Asal Sekolah</dt>
                            <dd class="font-medium text-[#1C2620]">{{ $pendaftar->asal_sekolah ?? '-' }}</dd>
                        </div>
                        <div class="py-3 flex justify-between items-center">
                            <dt class="text-[#545B52]">Status Keputusan</dt>
                            <dd>
                                <span class="px-2.5 py-1 rounded text-xs font-bold bg-[#FBEAEA] text-[#C81210] border border-[#C81210]">
                                    TIDAK LULUS
                                </span>
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <x-card title="Layanan Informasi & Kontak Panitia" subtitle="Bantuan dan informasi lebih lanjut terkait hasil seleksi">
                    <div class="space-y-3 text-xs text-[#545B52] leading-relaxed">
                        <p>
                            Apabila Anda memerlukan informasi tambahan mengenai alur seleksi atau terdapat kekeliruan data verifikasi dokumen, Anda dapat menghubungi sekretariat panitia PPDB:
                        </p>
                        <div class="p-3.5 rounded-xl bg-[#F3F5F2] border border-[#E1E4DE] space-y-1">
                            <div><strong>Sekretariat PPDB:</strong> SMAN 1 Terbanggi Besar</div>
                            <div><strong>Alamat:</strong> Jl. Lintas Sumatera, Terbanggi Besar, Lampung Tengah</div>
                            <div><strong>Jam Layanan:</strong> Senin - Jumat (08.00 - 15.00 WIB)</div>
                        </div>
                    </div>
                </x-card>
            </div>
        @else
            <!-- Status Pending / Menunggu Penetapan -->
            <div class="rounded-xl border border-[#E1E4DE] bg-white p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#F3F5F2] text-[#0E6026] flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#FBF9D6] text-[#6B6200] border border-[#E9E920]">
                            MENUNGGU SIDANG PENETAPAN
                        </span>
                        <h2 class="text-xl font-bold text-[#1C2620] mt-1">
                            Pemeriksaan Berkas Sedang Berlangsung
                        </h2>
                    </div>
                </div>

                <p class="text-xs text-[#545B52] leading-relaxed max-w-3xl">
                    Data pendaftaran dan dokumen berkas Anda telah berhasil kami terima. Hasil seleksi resmi PPDB Tahun Ajaran {{ $pendaftar->periode->tahun_ajaran ?? '2026/2027' }} akan diumumkan secara serentak setelah rapat pleno penetapan kelulusan panitia selesai dilaksanakan.
                </p>

                <div class="pt-2">
                    <x-badge status="menunggu">
                        Status Berkas Saat Ini: {{ ucfirst(str_replace('_', ' ', $pendaftar->status_pendaftaran)) }}
                    </x-badge>
                </div>
            </div>

            <x-card title="Rincian Berkas Registrasi" subtitle="Data yang telah tersimpan dalam sistem pendaftaran">
                <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <dt class="text-[#545B52]">Nomor Pendaftaran</dt>
                        <dd class="font-mono font-bold text-[#1C2620] mt-0.5">{{ $pendaftar->no_pendaftaran }}</dd>
                    </div>
                    <div class="p-3 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <dt class="text-[#545B52]">NISN Siswa</dt>
                        <dd class="tabular-nums font-semibold text-[#1C2620] mt-0.5">{{ $pendaftar->nisn }}</dd>
                    </div>
                    <div class="p-3 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <dt class="text-[#545B52]">Nama Lengkap</dt>
                        <dd class="font-bold text-[#1C2620] mt-0.5">{{ $pendaftar->nama_lengkap }}</dd>
                    </div>
                </dl>
            </x-card>
        @endif
    </div>
</x-layouts.pendaftar>
