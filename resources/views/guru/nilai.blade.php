<x-layouts.guru title="Input Nilai Siswa" heading="Input Nilai Rapor Siswa">
    <div class="space-y-6">

        <!-- Header Rincian Mapel & Kelas -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-2xl bg-white border border-[#E1E4DE]">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                        {{ $pengampu->kelas->nama_kelas }}
                    </span>
                    <span class="text-xs text-[#545B52]">&bull; {{ $pengampu->tahun_ajaran }}</span>
                </div>
                <h1 class="text-2xl font-bold text-[#1C2620]">
                    {{ $pengampu->mataPelajaran->nama_mapel }}
                </h1>
                <p class="text-xs text-[#545B52]">
                    Kode: <span class="font-mono font-semibold">{{ $pengampu->mataPelajaran->kode_mapel }}</span> &bull;
                    Standar KKM: <span class="font-bold text-[#1C2620]">{{ $pengampu->mataPelajaran->kkm }}</span> &bull;
                    Bobot Penilaian: <span class="text-[#0E6026] font-medium">30% Tugas + 30% UTS + 40% UAS</span>
                </p>
            </div>

            <div>
                <x-button as="a" href="{{ route('guru.dashboard') }}" variant="ghost" class="text-xs">
                    Kembali ke Dasbor
                </x-button>
            </div>
        </div>

        <!-- Form Lembar Input Nilai -->
        <form action="{{ route('guru.nilai.simpan', $pengampu->id) }}" method="POST">
            @csrf

            <x-card title="Lembar Penilaian Hasil Belajar Siswa">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-3 px-3 w-10">No</th>
                                <th class="py-3 px-3 w-32">NISN</th>
                                <th class="py-3 px-3">Nama Siswa</th>
                                <th class="py-3 px-3 w-28 text-center">Tugas (30%)</th>
                                <th class="py-3 px-3 w-28 text-center">UTS (30%)</th>
                                <th class="py-3 px-3 w-28 text-center">UAS (40%)</th>
                                <th class="py-3 px-3 w-28 text-center">Nilai Akhir</th>
                                <th class="py-3 px-3 w-24 text-center">Ketuntasan</th>
                                <th class="py-3 px-3">Capaian Kompetensi / Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            @forelse($pengampu->kelas->siswa as $idx => $siswa)
                                @php
                                    $n = $nilaiList->get($siswa->id);
                                    $kkm = $pengampu->mataPelajaran->kkm;
                                    $isTuntas = $n && $n->nilai_akhir !== null && $n->nilai_akhir >= $kkm;
                                @endphp
                                <tr class="hover:bg-[#F3F5F2] transition-colors">
                                    <td class="py-3 px-3 tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-3 px-3 font-mono tabular-nums text-[#545B52]">{{ $siswa->nisn }}</td>
                                    <td class="py-3 px-3 font-semibold text-[#1C2620]">{{ $siswa->nama }}</td>

                                    <!-- Nilai Tugas -->
                                    <td class="py-2 px-2">
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0"
                                            max="100"
                                            name="nilai[{{ $siswa->id }}][tugas]"
                                            value="{{ old('nilai.' . $siswa->id . '.tugas', $n?->nilai_tugas) }}"
                                            class="w-full rounded-lg border border-[#C9CDC3] px-2 py-1.5 text-xs text-center tabular-nums focus:border-[#039834] focus:outline-none"
                                            placeholder="0-100"
                                        />
                                    </td>

                                    <!-- Nilai UTS -->
                                    <td class="py-2 px-2">
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0"
                                            max="100"
                                            name="nilai[{{ $siswa->id }}][uts]"
                                            value="{{ old('nilai.' . $siswa->id . '.uts', $n?->nilai_uts) }}"
                                            class="w-full rounded-lg border border-[#C9CDC3] px-2 py-1.5 text-xs text-center tabular-nums focus:border-[#039834] focus:outline-none"
                                            placeholder="0-100"
                                        />
                                    </td>

                                    <!-- Nilai UAS -->
                                    <td class="py-2 px-2">
                                        <input
                                            type="number"
                                            step="0.1"
                                            min="0"
                                            max="100"
                                            name="nilai[{{ $siswa->id }}][uas]"
                                            value="{{ old('nilai.' . $siswa->id . '.uas', $n?->nilai_uas) }}"
                                            class="w-full rounded-lg border border-[#C9CDC3] px-2 py-1.5 text-xs text-center tabular-nums focus:border-[#039834] focus:outline-none"
                                            placeholder="0-100"
                                        />
                                    </td>

                                    <!-- Nilai Akhir Terhitung -->
                                    <td class="py-3 px-3 text-center tabular-nums font-bold text-sm {{ $isTuntas ? 'text-[#0E6026]' : 'text-[#C81210]' }}">
                                        {{ $n?->nilai_akhir !== null ? number_format($n->nilai_akhir, 1) : '-' }}
                                    </td>

                                    <!-- Status KKM -->
                                    <td class="py-3 px-3 text-center">
                                        @if($n?->nilai_akhir !== null)
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
                                            <span class="text-neutral-400 text-[11px]">-</span>
                                        @endif
                                    </td>

                                    <!-- Catatan Capaian Kompetensi -->
                                    <td class="py-2 px-2">
                                        <input
                                            type="text"
                                            name="nilai[{{ $siswa->id }}][capaian_kompetensi]"
                                            value="{{ old('nilai.' . $siswa->id . '.capaian_kompetensi', $n?->capaian_kompetensi) }}"
                                            placeholder="Deskripsi pencapaian kompetensi siswa"
                                            class="w-full rounded-lg border border-[#C9CDC3] px-2 py-1.5 text-xs focus:border-[#039834] focus:outline-none"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-xs text-[#545B52]">
                                        Belum ada siswa yang ditempatkan di rombel kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pengampu->kelas->siswa->count() > 0)
                    <div class="mt-6 pt-4 border-t border-[#E1E4DE] flex justify-end">
                        <x-button type="submit" variant="primary">
                            Simpan Seluruh Nilai Rombel
                        </x-button>
                    </div>
                @endif
            </x-card>
        </form>
    </div>
</x-layouts.guru>
