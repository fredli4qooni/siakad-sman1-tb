<x-layouts.pendaftar title="Kelas Saya — SIAKAD SMAN 1 TB">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-[#E1E4DE]">
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] mb-2">
                    SIAKAD Aktif
                </span>
                <h1 class="text-2xl font-bold text-[#1C2620]">Informasi Kelas & Rombongan Belajar</h1>
                <p class="text-xs text-[#545B52] mt-1">
                    Siswa: <strong class="text-[#1C2620]">{{ $siswa->nama }}</strong> &bull; NISN: <span class="font-mono tabular-nums">{{ $siswa->nisn }}</span> &bull; NIS: <span class="font-mono tabular-nums font-semibold text-[#0E6026]">{{ $siswa->nis ?? '-' }}</span>
                </p>
            </div>
            <div>
                <x-button as="a" href="{{ route('siakad.siswa.nilai') }}" variant="secondary" class="text-xs">
                    Lihat Nilai Rapor &rarr;
                </x-button>
            </div>
        </div>

        @if($kelas)
            <!-- Rincian Kelas & Wali Kelas -->
            <x-card title="Rincian Rombongan Belajar">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                    <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <div class="text-[#545B52]">Nama Rombel</div>
                        <div class="font-bold text-base text-[#1C2620] mt-1">{{ $kelas->nama_kelas }}</div>
                    </div>
                    <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <div class="text-[#545B52]">Tingkat Pendidikan</div>
                        <div class="font-bold text-base text-[#1C2620] mt-1">Tingkat {{ $kelas->tingkat }}</div>
                    </div>
                    <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <div class="text-[#545B52]">Tahun Ajaran</div>
                        <div class="font-bold text-base text-[#1C2620] mt-1">{{ $kelas->tahun_ajaran }}</div>
                    </div>
                    <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <div class="text-[#545B52]">Wali Kelas</div>
                        <div class="font-bold text-sm text-[#1C2620] mt-1">
                            {{ $kelas->waliKelas ? $kelas->waliKelas->nama_lengkap . ($kelas->waliKelas->gelar ? ', ' . $kelas->waliKelas->gelar : '') : 'Belum Ditentukan' }}
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Daftar Teman Sekelas -->
            <x-card title="Daftar Siswa di Kelas {{ $kelas->nama_kelas }}" subtitle="Total {{ $temanSekelas->count() }} siswa terdaftar di rombongan belajar ini">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-2.5 px-3 w-10">No</th>
                                <th class="py-2.5 px-3">NISN</th>
                                <th class="py-2.5 px-3">NIS</th>
                                <th class="py-2.5 px-3">Nama Lengkap</th>
                                <th class="py-2.5 px-3 text-center">L/P</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            @foreach($temanSekelas as $idx => $t)
                                <tr class="hover:bg-[#F3F5F2] transition-colors {{ $t->id === $siswa->id ? 'bg-[#E7F4EA]/40 font-semibold' : '' }}">
                                    <td class="py-2.5 px-3 tabular-nums">{{ $idx + 1 }}</td>
                                    <td class="py-2.5 px-3 font-mono tabular-nums text-[#545B52]">{{ $t->nisn }}</td>
                                    <td class="py-2.5 px-3 font-mono tabular-nums">{{ $t->nis ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-[#1C2620]">
                                        {{ $t->nama }}
                                        @if($t->id === $siswa->id)
                                            <span class="ml-1 text-[10px] font-bold text-[#0E6026]">(Anda)</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-center">{{ $t->jenis_kelamin }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        @else
            <x-alert type="warning" title="Belum Memiliki Kelas">
                Data Anda telah tersinkronisasi sebagai Siswa Aktif di SIAKAD, namun administrator sekolah belum mengalokasikan Anda ke dalam rombongan belajar kelas. Silakan periksa kembali beberapa saat lagi.
            </x-alert>
        @endif
    </div>
</x-layouts.pendaftar>
