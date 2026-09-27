<x-layouts.admin title="Data Siswa SIAKAD" heading="Daftar Siswa Aktif SIAKAD">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        <!-- 2 Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Total Siswa Aktif SIAKAD</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">{{ number_format($totalSiswa, 0, ',', '.') }} Siswa</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#6B6200]">Belum Ditempatkan ke Rombel</div>
                <div class="text-2xl font-bold text-[#6B6200] tabular-nums mt-1">{{ number_format($belumDitempatkan, 0, ',', '.') }} Siswa</div>
            </div>
        </div>

        <!-- Filter Pencarian & Kelas -->
        <x-card>
            <form action="{{ route('admin.siakad.siswa.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2">
                    <x-input
                        label="Cari Siswa"
                        name="keyword"
                        placeholder="Nama Siswa, NISN, atau NIS"
                        :value="request('keyword')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Filter Rombel / Kelas</label>
                    <select name="kelas_id" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Rombel</option>
                        <option value="belum_ada" {{ request('kelas_id') === 'belum_ada' ? 'selected' : '' }}>-- Belum Memiliki Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter Siswa
                    </x-button>
                    @if(request()->hasAny(['keyword', 'kelas_id']))
                        <a href="{{ route('admin.siakad.siswa.index') }}" class="p-2 text-xs text-[#545B52] hover:text-[#1C2620]">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Siswa & Ploting Kelas -->
        <x-card title="Daftar Siswa & Penempatan Kelas">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">NIS</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">L/P</th>
                            <th class="py-3 px-3">Tahun Masuk</th>
                            <th class="py-3 px-3">Rombel Kelas Saat Ini</th>
                            <th class="py-3 px-3 text-right">Penempatan Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($siswaList as $s)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-mono tabular-nums">{{ $s->nisn }}</td>
                                <td class="py-3 px-3 font-mono tabular-nums text-[#545B52]">{{ $s->nis ?? '-' }}</td>
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">{{ $s->nama }}</td>
                                <td class="py-3 px-3 font-medium">{{ $s->jenis_kelamin }}</td>
                                <td class="py-3 px-3 tabular-nums text-[#545B52]">{{ $s->tahun_masuk }}</td>
                                <td class="py-3 px-3">
                                    @if($s->kelas)
                                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            {{ $s->kelas->nama_kelas }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-[#FBF9D6] text-[#6B6200]">
                                            Belum Ditempatkan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <form action="{{ route('admin.siakad.siswa.ploting', $s->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <select name="kelas_id" class="rounded-lg border border-[#C9CDC3] px-2 py-1 text-xs text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                                            <option value="">-- Kosongkan --</option>
                                            @foreach($kelasList as $k)
                                                <option value="{{ $k->id }}" {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-button type="submit" variant="secondary" class="text-xs py-1 px-2.5">
                                            Simpan
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada data siswa yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswaList->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $siswaList->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
