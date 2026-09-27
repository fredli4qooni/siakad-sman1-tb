<x-layouts.guru title="Dashboard Guru — SIAKAD SMAN 1 TB">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-[#E1E4DE]">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] mb-2">
                    Portal Akademik Guru
                </span>
                <h1 class="text-2xl font-bold text-[#1C2620]">
                    Selamat Datang, {{ $guru ? $guru->nama_lengkap . ($guru->gelar ? ', ' . $guru->gelar : '') : auth()->user()->nama }}
                </h1>
                <p class="text-xs text-[#545B52] mt-1">
                    NIP: <span class="font-mono font-medium text-[#1C2620]">{{ $guru->nip ?? '-' }}</span> &bull; Status: <span class="text-[#0E6026] font-semibold">Guru Aktif</span>
                </p>
            </div>
            <div>
                <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]">
                    SSO Terautentikasi
                </span>
            </div>
        </div>

        <!-- Kartu Daftar Kelas & Mapel Binaan -->
        <x-card title="Daftar Rombel & Mata Pelajaran Diampu" subtitle="Pilih kelas dan mata pelajaran untuk melakukan pengisian nilai rapor siswa">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($pengampuList as $p)
                    <div class="p-5 rounded-xl border border-[#E1E4DE] bg-white hover:border-[#039834] transition-colors space-y-3">
                        <div class="flex items-center justify-between pb-2 border-b border-[#E1E4DE]">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                {{ $p->kelas->nama_kelas }}
                            </span>
                            <span class="text-[11px] text-[#545B52] tabular-nums">{{ $p->tahun_ajaran }}</span>
                        </div>

                        <div>
                            <h3 class="font-bold text-base text-[#1C2620]">{{ $p->mataPelajaran->nama_mapel }}</h3>
                            <p class="text-xs text-[#545B52] mt-0.5">
                                Kode: <span class="font-mono font-semibold">{{ $p->mataPelajaran->kode_mapel }}</span> &bull; KKM: <span class="font-bold text-[#1C2620]">{{ $p->mataPelajaran->kkm }}</span>
                            </p>
                            <p class="text-xs text-[#545B52] mt-1">
                                Jumlah Siswa: <strong class="tabular-nums text-[#1C2620]">{{ $p->kelas->siswa->count() }} orang</strong>
                            </p>
                        </div>

                        <div class="pt-2">
                            <x-button as="a" href="{{ route('guru.nilai.input', $p->id) }}" variant="primary" class="w-full text-xs">
                                Input & Kelola Nilai &rarr;
                            </x-button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-10 text-center text-xs text-[#545B52]">
                        Anda belum dialokasikan ke kelas atau mata pelajaran apapun. Hubungi bagian kurikulum / operator sekolah.
                    </div>
                @endforelse
            </div>
        </x-card>
    </div>
</x-layouts.guru>
