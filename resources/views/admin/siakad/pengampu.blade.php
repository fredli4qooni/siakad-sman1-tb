<x-layouts.admin title="Guru Pengampu" heading="Alokasi Guru Pengampu Kelas">
    <div class="space-y-6">

        <!-- Form Tambah Penugasan Mengajar -->
        <x-card title="Alokasikan Guru ke Rombel & Mata Pelajaran" subtitle="Penetapan penugasan mengajar untuk pemberian hak input nilai di portal guru">
            <form action="{{ route('admin.siakad.pengampu.simpan') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Guru Pengampu</label>
                    <select name="guru_id" required class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama_lengkap }}{{ $g->gelar ? ', ' . $g->gelar : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Rombel Kelas</label>
                    <select name="kelas_id" required class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="">-- Pilih Rombel --</option>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Mata Pelajaran</label>
                    <select name="mapel_id" required class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }} ({{ $m->kode_mapel }})
                            </option>
                        @endforeach
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

                <div class="sm:col-span-2 lg:col-span-4 flex justify-end pt-2">
                    <x-button type="submit" variant="primary">
                        Tetapkan Guru Pengampu
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Tabel Daftar Penugasan Mengajar -->
        <x-card title="Daftar Penugasan Guru Pengampu">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Guru Pengampu</th>
                            <th class="py-3 px-3">Mata Pelajaran</th>
                            <th class="py-3 px-3">Kelas / Rombel</th>
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($pengampuList as $p)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">
                                    {{ $p->guru->nama_lengkap }}{{ $p->guru->gelar ? ', ' . $p->guru->gelar : '' }}
                                    <div class="text-[11px] font-mono text-[#545B52]">NIP: {{ $p->guru->nip }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-medium text-[#1C2620]">{{ $p->mataPelajaran->nama_mapel }}</span>
                                    <span class="text-xs text-[#0E6026] font-mono font-bold">({{ $p->mataPelajaran->kode_mapel }})</span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                        {{ $p->kelas->nama_kelas }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 tabular-nums text-[#545B52]">{{ $p->tahun_ajaran }}</td>
                                <td class="py-3 px-3 text-right">
                                    <form action="{{ route('admin.siakad.pengampu.hapus', $p->id) }}" method="POST" onsubmit="return confirm('Hapus penugasan mengajar ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-[#C81210] hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada penugasan guru pengampu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
