<x-layouts.admin title="Mata Pelajaran" heading="Master Mata Pelajaran & KKM">
    <div class="space-y-6">

        <!-- Form Tambah Mapel Baru -->
        <x-card title="Tambah Mata Pelajaran Baru" subtitle="Definisikan mata pelajaran kurikulum dan standar Kriteria Ketuntasan Minimal (KKM)">
            <form action="{{ route('admin.siakad.mapel.simpan') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <x-input
                        label="Kode Mata Pelajaran"
                        name="kode_mapel"
                        required
                        placeholder="Contoh: MAT-W, BIND-X"
                        :value="old('kode_mapel')"
                    />
                </div>

                <div>
                    <x-input
                        label="Nama Mata Pelajaran"
                        name="nama_mapel"
                        required
                        placeholder="Contoh: Matematika Wajib"
                        :value="old('nama_mapel')"
                    />
                </div>

                <div>
                    <x-input
                        label="Nilai KKM (0–100)"
                        name="kkm"
                        type="number"
                        min="0"
                        max="100"
                        required
                        class="tabular-nums"
                        :value="old('kkm', 75)"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Kelompok Kurikulum</label>
                    <select name="kelompok" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="umum">Muatan Umum (Fase E/F)</option>
                        <option value="peminatan">Peminatan / Pilihan</option>
                        <option value="muatan_lokal">Muatan Lokal (Lampung)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-4 flex justify-end pt-2">
                    <x-button type="submit" variant="primary">
                        Simpan Mata Pelajaran
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Tabel Daftar Mata Pelajaran -->
        <x-card title="Daftar Mata Pelajaran Terdaftar">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Kode</th>
                            <th class="py-3 px-3">Nama Mata Pelajaran</th>
                            <th class="py-3 px-3">Kelompok</th>
                            <th class="py-3 px-3 text-right">KKM</th>
                            <th class="py-3 px-3 text-right">Guru Pengampu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($mapelList as $m)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-mono font-semibold text-[#0E6026]">{{ $m->kode_mapel }}</td>
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">{{ $m->nama_mapel }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                        {{ ucfirst(str_replace('_', ' ', $m->kelompok)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right tabular-nums font-bold text-[#1C2620]">{{ $m->kkm }}</td>
                                <td class="py-3 px-3 text-right tabular-nums text-[#545B52]">
                                    {{ $m->pengampu_count }} Kelas
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada mata pelajaran yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
