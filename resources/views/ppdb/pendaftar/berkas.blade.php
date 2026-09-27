<x-layouts.pendaftar title="Unggah Berkas Persyaratan — SMAN 1 TB">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Unggah Berkas Persyaratan PPDB</h1>
                <p class="text-xs text-[#545B52] mt-1">
                    Format file yang diperbolehkan: PDF, JPG, PNG dengan ukuran maksimum 2MB per berkas.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-[#545B52]">No. Registrasi:</span>
                <span class="px-2.5 py-1 rounded bg-[#E7F4EA] text-[#0E6026] font-mono font-bold text-xs">
                    {{ $pendaftar->no_pendaftaran }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" title="Perhatian">
                {{ session('error') }}
            </x-alert>
        @endif

        @if(in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus']))
            <x-alert type="warning" title="Pendaftaran Telah Diproses">
                Berkas Anda telah berstatus <strong>{{ strtoupper($pendaftar->status_pendaftaran) }}</strong> dan tidak dapat diunggah ulang.
            </x-alert>
        @endif

        <x-card title="Daftar Dokumen Persyaratan Wajib" subtitle="Pastikan dokumen yang diunggah terbaca dengan jelas dan tidak buram">
            <div class="space-y-4">
                @foreach($dokumenSyarat as $key => $syarat)
                    @php
                        $berkas = $berkasList->get($key);
                        $isUploaded = $berkas !== null;
                    @endphp

                    <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-sm text-[#1C2620]">{{ $syarat['nama'] }}</h3>
                                    @if($syarat['wajib'])
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-neutral-100 text-[#545B52]">Wajib</span>
                                    @endif
                                </div>
                                <p class="text-xs text-[#545B52]">{{ $syarat['keterangan'] }}</p>

                                @if($isUploaded)
                                    <div class="pt-2 flex flex-wrap items-center gap-3 text-xs">
                                        <a href="{{ route('pendaftar.berkas.preview', $berkas->id) }}" target="_blank" class="font-medium text-[#0E6026] hover:underline flex items-center gap-1">
                                            <span>&#128196;</span>
                                            <span>{{ $berkas->nama_file_asli }}</span>
                                        </a>
                                        <span class="text-neutral-400">&bull;</span>
                                        <span class="text-[#545B52] tabular-nums">{{ number_format($berkas->ukuran_file / 1024, 0, ',', '.') }} KB</span>
                                        <span class="text-neutral-400">&bull;</span>
                                        <span class="text-[#545B52]">{{ $berkas->created_at->format('d/m/Y H:i') }}</span>
                                    </div>

                                    @if($berkas->catatan)
                                        <div class="mt-2 p-2.5 rounded bg-[#FBEAEA] border border-[#C81210]/20 text-xs text-[#C81210]">
                                            <strong>Catatan Verifikator:</strong> {{ $berkas->catatan }}
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="flex items-center gap-3 self-end sm:self-center">
                                @if($isUploaded)
                                    @if($berkas->status_verifikasi === 'valid')
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            Valid
                                        </span>
                                    @elseif($berkas->status_verifikasi === 'tidak_valid')
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBEAEA] text-[#C81210]">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBF9D6] text-[#6B6200]">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif

                                    @if(!in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus']))
                                        <form action="{{ route('pendaftar.berkas.hapus', $berkas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-[#C81210] hover:underline px-2 py-1">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="px-2.5 py-1 rounded text-xs font-semibold bg-neutral-100 text-neutral-600">
                                        Belum Diunggah
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if(!$isUploaded && !in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus']))
                            <div class="mt-3 pt-3 border-t border-[#E1E4DE]">
                                <form action="{{ route('pendaftar.berkas.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-center gap-3">
                                    @csrf
                                    <input type="hidden" name="jenis_berkas" value="{{ $key }}" />
                                    <input
                                        type="file"
                                        name="file_berkas"
                                        required
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        class="block w-full text-xs text-[#545B52] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-[#C9CDC3] file:text-xs file:font-semibold file:bg-white file:text-[#1C2620] hover:file:bg-[#F3F5F2] cursor-pointer"
                                    />
                                    <x-button type="submit" variant="secondary" class="text-xs py-1.5 whitespace-nowrap">
                                        Unggah Dokumen
                                    </x-button>
                                </form>
                                @error('file_berkas')
                                    @if(old('jenis_berkas') === $key)
                                        <p class="text-xs text-[#C81210] mt-1">{{ $message }}</p>
                                    @endif
                                @enderror
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Bagian Konfirmasi & Pengajuan Verifikasi -->
            @php
                $uploadedCount = $berkasList->whereIn('jenis_berkas', array_keys($dokumenSyarat))->count();
                $allUploaded = $uploadedCount >= count($dokumenSyarat);
            @endphp

            <div class="mt-8 pt-6 border-t border-[#E1E4DE] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-xs text-[#545B52]">
                    Progress Berkas: <strong class="text-[#1C2620] tabular-nums">{{ $uploadedCount }} dari {{ count($dokumenSyarat) }}</strong> dokumen wajib terunggah.
                </div>

                <div class="flex items-center gap-3">
                    <x-button as="a" href="{{ route('pendaftar.formulir') }}" variant="ghost">
                        &larr; Data Formulir
                    </x-button>

                    @if(!in_array($pendaftar->status_pendaftaran, ['menunggu_verifikasi', 'terverifikasi', 'lulus', 'tidak_lulus']))
                        @if($allUploaded)
                            <form action="{{ route('pendaftar.berkas.kirim') }}" method="POST">
                                @csrf
                                <x-button type="submit" variant="primary">
                                    Ajukan Verifikasi Berkas &rarr;
                                </x-button>
                            </form>
                        @else
                            <x-button type="button" variant="primary" disabled class="opacity-50 cursor-not-allowed">
                                Lengkapi Seluruh Berkas
                            </x-button>
                        @endif
                    @else
                        <span class="text-xs font-semibold text-[#0E6026] flex items-center gap-1.5">
                            <span>&#10003;</span> Berkas Telah Diajukan
                        </span>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
</x-layouts.pendaftar>
