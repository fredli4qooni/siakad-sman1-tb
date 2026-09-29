<x-layouts.admin title="Verifikasi Berkas Siswa" heading="Pemeriksaan & Verifikasi Dokumen">
    <div class="space-y-6">

        <!-- Header Ringkas Calon Siswa -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl bg-white border border-[#E1E4DE]">
            <div>
                <span class="text-xs text-[#545B52]">No. Registrasi: <strong class="font-mono text-[#1C2620]">{{ $pendaftar->no_pendaftaran }}</strong> &bull; Gelombang: <strong>{{ $pendaftar->periode->tahun_ajaran }}</strong></span>
                <h1 class="text-xl font-bold text-[#1C2620] mt-0.5">{{ $pendaftar->nama_lengkap }}</h1>
                <p class="text-xs text-[#545B52]">
                    NISN: <span class="tabular-nums font-mono">{{ $pendaftar->nisn }}</span> &bull; NIK: <span class="tabular-nums font-mono">{{ $pendaftar->nik }}</span> &bull; Asal: {{ $pendaftar->asal_sekolah }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if($pendaftar->status_pendaftaran === 'lulus')
                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026]">Lulus Seleksi</span>
                @elseif($pendaftar->status_pendaftaran === 'terverifikasi')
                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026]">Terverifikasi</span>
                @elseif($pendaftar->status_pendaftaran === 'menunggu_verifikasi')
                    <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#FBF9D6] text-[#6B6200]">Menunggu Verifikasi</span>
                @else
                    <span class="px-3 py-1.5 rounded-lg text-xs font-medium bg-neutral-100 text-neutral-600">Draft</span>
                @endif

                <x-button as="a" href="{{ route('admin.ppdb.pendaftar') }}" variant="ghost" class="text-xs">
                    Kembali
                </x-button>
            </div>
        </div>

        <!-- Detail Biodata Pokok & Orang Tua -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Data Pokok Calon Siswa">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                        <span class="text-[#545B52]">Jenis Kelamin</span>
                        <span class="font-medium text-[#1C2620]">{{ $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                        <span class="text-[#545B52]">Tempat, Tgl Lahir</span>
                        <span class="font-medium text-[#1C2620]">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                        <span class="text-[#545B52]">Agama</span>
                        <span class="font-medium text-[#1C2620]">{{ $pendaftar->agama }}</span>
                    </div>
                    <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                        <span class="text-[#545B52]">No. HP / WA Siswa</span>
                        <span class="font-mono text-[#1C2620]">{{ $pendaftar->no_hp }}</span>
                    </div>
                    <div class="flex justify-between pt-1">
                        <span class="text-[#545B52]">Alamat Lengkap</span>
                        <span class="font-medium text-[#1C2620] text-right max-w-xs">{{ $pendaftar->alamat }}</span>
                    </div>
                </div>
            </x-card>

            <x-card title="Data Orang Tua / Wali">
                @if($pendaftar->orangTua)
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                            <span class="text-[#545B52]">Nama Ayah</span>
                            <span class="font-medium text-[#1C2620]">{{ $pendaftar->orangTua->nama_ayah }}</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                            <span class="text-[#545B52]">Pekerjaan Ayah</span>
                            <span class="font-medium text-[#1C2620]">{{ $pendaftar->orangTua->pekerjaan_ayah }}</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                            <span class="text-[#545B52]">Nama Ibu</span>
                            <span class="font-medium text-[#1C2620]">{{ $pendaftar->orangTua->nama_ibu }}</span>
                        </div>
                        <div class="flex justify-between border-b border-[#E1E4DE] pb-1.5">
                            <span class="text-[#545B52]">Pekerjaan Ibu</span>
                            <span class="font-medium text-[#1C2620]">{{ $pendaftar->orangTua->pekerjaan_ibu }}</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span class="text-[#545B52]">Kontak Ortu</span>
                            <span class="font-mono text-[#1C2620]">{{ $pendaftar->orangTua->no_hp_ortu }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-[#C81210]">Data orang tua belum dilengkapi pendaftar.</p>
                @endif
            </x-card>
        </div>

        <!-- Tabel Pemeriksaan & Verifikasi Dokumen Berkas -->
        <x-card title="Pemeriksaan Berkas Persyaratan Digital" subtitle="Periksa berkas secara teliti dan beri catatan apabila dokumen tidak sah/buram">
            <div class="space-y-4">
                @foreach($dokumenSyarat as $key => $namaDokumen)
                    @php
                        $berkas = $berkasList->get($key);
                        $isUploaded = $berkas !== null;
                    @endphp

                    <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-[#E1E4DE]">
                            <div>
                                <h3 class="font-semibold text-sm text-[#1C2620]">{{ $namaDokumen }}</h3>
                                @if($isUploaded)
                                    <div class="flex items-center gap-2 mt-1 text-xs">
                                        <a href="{{ route('pendaftar.berkas.preview', $berkas->id) }}" target="_blank" class="font-medium text-[#0E6026] hover:underline flex items-center gap-1">
                                            <span>&#128196;</span>
                                            <span>Lihat / Unduh Dokumen ({{ $berkas->nama_file_asli }})</span>
                                        </a>
                                        <span class="text-neutral-400">&bull;</span>
                                        <span class="text-[#545B52] tabular-nums">{{ number_format($berkas->ukuran_file / 1024, 0, ',', '.') }} KB</span>
                                    </div>
                                @else
                                    <span class="text-xs text-[#C81210] font-medium">Calon siswa belum mengunggah dokumen ini.</span>
                                @endif
                            </div>

                            <div>
                                @if($isUploaded)
                                    @if($berkas->status_verifikasi === 'valid')
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            ✓ Valid
                                        </span>
                                    @elseif($berkas->status_verifikasi === 'tidak_valid')
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBEAEA] text-[#C81210]">
                                            ✕ Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBF9D6] text-[#6B6200]">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if($isUploaded)
                            <!-- Form Verifikasi Berkas Individual -->
                            <form action="{{ route('admin.ppdb.verifikasi.berkas', [$pendaftar->id, $berkas->id]) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end pt-1">
                                @csrf
                                <div class="sm:col-span-3">
                                    <label class="block text-[11px] font-medium text-[#1C2620] mb-1">Keputusan Verifikasi</label>
                                    <select name="status_verifikasi" class="w-full rounded-lg border border-[#C9CDC3] px-3 py-1.5 text-xs text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                                        <option value="valid" {{ $berkas->status_verifikasi === 'valid' ? 'selected' : '' }}>Setujui (Valid)</option>
                                        <option value="tidak_valid" {{ $berkas->status_verifikasi === 'tidak_valid' ? 'selected' : '' }}>Tolak (Tidak Valid)</option>
                                    </select>
                                </div>

                                <div class="sm:col-span-7">
                                    <label class="block text-[11px] font-medium text-[#1C2620] mb-1">Catatan Koreksi (Tampil ke Siswa)</label>
                                    <input
                                        type="text"
                                        name="catatan"
                                        value="{{ $berkas->catatan }}"
                                        placeholder="Contoh: File buram, silakan scan ulang dokumen asli"
                                        class="w-full rounded-lg border border-[#C9CDC3] px-3 py-1.5 text-xs text-[#1C2620] focus:outline-none focus:border-[#039834]"
                                    />
                                </div>

                                <div class="sm:col-span-2">
                                    <x-button type="submit" variant="secondary" class="w-full text-xs py-1.5">
                                        Simpan
                                    </x-button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Finalisasi Verifikasi Berkas -->
            @php
                $validCount = $pendaftar->berkas()->where('status_verifikasi', 'valid')->count();
                $allValid = $validCount >= 4;
            @endphp

            <div class="mt-8 pt-6 border-t border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-[#545B52]">
                    Status Verifikasi: <strong class="text-[#1C2620] tabular-nums">{{ $validCount }} dari 4</strong> berkas berstatus VALID.
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.ppdb.verifikasi.finalisasi', $pendaftar->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="draft" />
                        <x-button type="submit" variant="ghost" class="text-xs text-[#C81210]">
                            Kembalikan ke DRAFT
                        </x-button>
                    </form>

                    <form action="{{ route('admin.ppdb.verifikasi.finalisasi', $pendaftar->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="terverifikasi" />
                        <x-button type="submit" variant="primary" class="text-xs" :disabled="!$allValid">
                            Tetapkan Berkas TERVERIFIKASI
                        </x-button>
                    </form>
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
