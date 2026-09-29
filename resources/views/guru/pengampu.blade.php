<x-layouts.guru title="Kelas Diampu" heading="Kelas & Mata Pelajaran Diampu">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Daftar Kelas & Mata Pelajaran Diampu</h1>
            <p class="text-xs text-[#545B52] mt-1">Hanya rombel dan mata pelajaran berikut yang diberikan hak akses penilaian kepada Anda.</p>
        </div>

        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Nama Rombel</th>
                            <th class="py-3 px-3">Tingkat</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3 text-right">Jumlah Siswa</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($pengampuList as $idx => $p)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">{{ $p->kelas->nama_kelas }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                        Tingkat {{ $p->kelas->tingkat }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-medium text-[#1C2620]">{{ $p->mataPelajaran->nama_mapel }}</span>
                                    <span class="text-xs text-[#0E6026] font-mono">({{ $p->mataPelajaran->kode_mapel }})</span>
                                </td>
                                <td class="py-3 px-3 tabular-nums text-[#545B52]">{{ $p->tahun_ajaran }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-semibold">{{ $p->kelas->siswa->count() }} Siswa</td>
                                <td class="py-3 px-3 text-right">
                                    <x-button as="a" href="{{ route('guru.nilai.input', $p->id) }}" variant="primary" class="text-xs py-1">
                                        Input Nilai
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada mata pelajaran dan rombel yang ditugaskan kepada Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.guru>
