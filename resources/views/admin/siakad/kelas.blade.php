<x-layouts.admin title="Manajemen Kelas" heading="Manajemen Kelas & Rombongan Belajar">
    <div class="space-y-6">
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

        <!-- Formulir Tambah Kelas Baru -->
        <x-card title="Tambah Rombongan Belajar (Kelas)" subtitle="Konfigurasi kelas akademik, kapasitas siswa, dan penugasan wali kelas">
            <form action="{{ route('admin.siakad.kelas.simpan') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <x-input
                        label="Nama Rombel / Kelas"
                        name="nama_kelas"
                        required
                        placeholder="Contoh: X-1 atau X MIPA 1"
                        :value="old('nama_kelas')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Tingkat</label>
                    <select name="tingkat" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="X">Kelas X (Fase E)</option>
                        <option value="XI">Kelas XI (Fase F)</option>
                        <option value="XII">Kelas XII (Fase F)</option>
                    </select>
                </div>

                <div>
                    <x-input
                        label="Tahun Ajaran"
                        name="tahun_ajaran"
                        required
                        placeholder="2026/2027"
                        :value="old('tahun_ajaran', '2026/2027')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Wali Kelas</label>
                    <select name="wali_kelas_id" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="">-- Belum Ditentukan --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama_lengkap }}{{ $g->gelar ? ', ' . $g->gelar : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input
                        label="Kapasitas Maksimal"
                        name="kapasitas"
                        type="number"
                        min="10"
                        max="50"
                        required
                        class="tabular-nums"
                        :value="old('kapasitas', 36)"
                    />
                </div>

                <div class="sm:col-span-2 lg:col-span-5 flex justify-end pt-2">
                    <x-button type="submit" variant="primary">
                        Simpan Rombel Kelas
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Tabel Daftar Kelas -->
        <x-card title="Daftar Rombongan Belajar Aktif">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama Kelas</th>
                            <th class="py-3 px-3">Tingkat</th>
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3">Wali Kelas</th>
                            <th class="py-3 px-3 text-right">Kapasitas</th>
                            <th class="py-3 px-3 text-right">Siswa Terdaftar</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($kelasList as $k)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">{{ $k->nama_kelas }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                        Tingkat {{ $k->tingkat }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-[#545B52] tabular-nums">{{ $k->tahun_ajaran }}</td>
                                <td class="py-3 px-3 text-[#1C2620]">
                                    @if($k->waliKelas)
                                        {{ $k->waliKelas->nama_lengkap }}{{ $k->waliKelas->gelar ? ', ' . $k->waliKelas->gelar : '' }}
                                    @else
                                        <span class="text-neutral-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right tabular-nums text-[#545B52]">{{ $k->kapasitas }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-bold {{ $k->siswa_count >= $k->kapasitas ? 'text-[#C81210]' : 'text-[#0E6026]' }}">
                                    {{ $k->siswa_count }} Siswa
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.siakad.siswa.index', ['kelas_id' => $k->id]) }}" class="text-xs font-semibold text-[#0E6026] hover:underline">
                                        Lihat Siswa &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada data rombongan belajar yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
