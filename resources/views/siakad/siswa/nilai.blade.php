<x-layouts.pendaftar title="Nilai Akademik — SIAKAD SMAN 1 TB">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E1E4DE]">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] mb-2">
                    Transkrip Hasil Belajar
                </span>
                <h1 class="text-2xl font-bold text-[#1C2620]">Daftar Nilai Akademik Siswa</h1>
                <p class="text-xs text-[#545B52] mt-1">
                    Siswa: <strong class="text-[#1C2620]">{{ $siswa->nama }}</strong> &bull; NISN: <span class="font-mono tabular-nums">{{ $siswa->nisn }}</span> &bull; Kelas: <strong>{{ $siswa->kelas->nama_kelas ?? 'Belum Ditempatkan' }}</strong>
                </p>
            </div>
            <div>
                <x-button as="a" href="{{ route('siakad.siswa.kelas') }}" variant="ghost" class="text-xs">
                    &larr; Info Kelas
                </x-button>
            </div>
        </div>

        <!-- 3 Statistik Ringkas Nilai -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Rata-Rata Nilai Akhir</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">
                    {{ $rataRata !== null ? number_format($rataRata, 1) : '-' }}
                </div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Nilai Tertinggi</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                    {{ $tertinggi !== null ? number_format($tertinggi, 1) : '-' }}
                </div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Mata Pelajaran Dinilai</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                    {{ $daftarNilai->whereNotNull('nilai_akhir')->count() }} / {{ $daftarNilai->count() }}
                </div>
            </div>
        </div>

        <x-card title="Rincian Nilai Hasil Belajar Per Mata Pelajaran">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3">Guru Pengampu</th>
                            <th class="py-3 px-3 text-center">KKM</th>
                            <th class="py-3 px-3 text-center">Tugas (30%)</th>
                            <th class="py-3 px-3 text-center">UTS (30%)</th>
                            <th class="py-3 px-3 text-center">UAS (40%)</th>
                            <th class="py-3 px-3 text-center">Nilai Akhir</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3">Capaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($daftarNilai as $idx => $n)
                            @php
                                $mapel = $n->pengampu->mataPelajaran;
                                $guru = $n->pengampu->guru;
                                $isTuntas = $n->nilai_akhir !== null && $n->nilai_akhir >= $mapel->kkm;
                            @endphp
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-[#1C2620]">{{ $mapel->nama_mapel }}</div>
                                    <div class="text-[11px] font-mono text-[#545B52]">{{ $mapel->kode_mapel }}</div>
                                </td>
                                <td class="py-3 px-3 text-[#545B52]">
                                    {{ $guru ? $guru->nama_lengkap . ($guru->gelar ? ', ' . $guru->gelar : '') : '-' }}
                                </td>
                                <td class="py-3 px-3 text-center tabular-nums font-medium">{{ $mapel->kkm }}</td>
                                <td class="py-3 px-3 text-center tabular-nums">{{ $n->nilai_tugas ?? '-' }}</td>
                                <td class="py-3 px-3 text-center tabular-nums">{{ $n->nilai_uts ?? '-' }}</td>
                                <td class="py-3 px-3 text-center tabular-nums">{{ $n->nilai_uas ?? '-' }}</td>
                                <td class="py-3 px-3 text-center tabular-nums font-bold text-sm {{ $isTuntas ? 'text-[#0E6026]' : 'text-[#C81210]' }}">
                                    {{ $n->nilai_akhir !== null ? number_format($n->nilai_akhir, 1) : '-' }}
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($n->nilai_akhir !== null)
                                        @if($isTuntas)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                                Tuntas
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FBEAEA] text-[#C81210]">
                                                Remedial
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-neutral-400 text-xs">Belum Ada Nilai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-xs text-[#545B52] max-w-xs">
                                    {{ $n->capaian_kompetensi ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada data nilai akademik yang diinputkan untuk semester ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.pendaftar>
