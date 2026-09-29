<x-layouts.admin title="Periode & Kuota PPDB" heading="Manajemen Periode & Kuota PPDB">
    <div class="space-y-6">

        <!-- Formulir Tambah/Edit Periode -->
        <x-card title="Buka / Atur Periode PPDB" subtitle="Konfigurasi jadwal pembukaan gelombang dan target kuota penerimaan rombel">
            <form action="{{ route('admin.ppdb.periode.simpan') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <x-input
                            label="Tahun Ajaran"
                            name="tahun_ajaran"
                            required
                            placeholder="Contoh: 2026/2027"
                            :value="old('tahun_ajaran', '2026/2027')"
                        />
                    </div>
                    <div>
                        <x-input
                            label="Nama Gelombang"
                            name="nama_gelombang"
                            required
                            placeholder="Contoh: Gelombang 1 Reguler"
                            :value="old('nama_gelombang', 'Gelombang 1 Reguler')"
                        />
                    </div>
                    <div>
                        <x-input
                            label="Tanggal Buka"
                            name="tanggal_buka"
                            type="date"
                            required
                            :value="old('tanggal_buka', date('Y-07-01'))"
                        />
                    </div>
                    <div>
                        <x-input
                            label="Tanggal Tutup"
                            name="tanggal_tutup"
                            type="date"
                            required
                            :value="old('tanggal_tutup', date('Y-07-15'))"
                        />
                    </div>
                    <div>
                        <x-input
                            label="Target Kuota Siswa"
                            name="kuota"
                            type="number"
                            min="1"
                            max="2000"
                            required
                            class="tabular-nums"
                            :value="old('kuota', 540)"
                        />
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                        <input type="checkbox" name="is_aktif" value="1" checked class="rounded border-[#C9CDC3] text-[#0E6026] focus:ring-[#039834]">
                        <span class="font-medium text-[#1C2620]">Jadikan Periode Ini Sebagai Periode Aktif</span>
                    </label>

                    <x-button type="submit" variant="primary">
                        Simpan Periode Baru
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Daftar Seluruh Periode PPDB -->
        <x-card title="Daftar Gelombang PPDB">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Tahun Ajaran</th>
                            <th class="py-3 px-3">Gelombang</th>
                            <th class="py-3 px-3">Tanggal Buka</th>
                            <th class="py-3 px-3">Tanggal Tutup</th>
                            <th class="py-3 px-3 text-right">Target Kuota</th>
                            <th class="py-3 px-3 text-right">Pendaftar Masuk</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($periodeList as $p)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-semibold">{{ $p->tahun_ajaran }}</td>
                                <td class="py-3 px-3 text-[#545B52]">{{ $p->nama_gelombang }}</td>
                                <td class="py-3 px-3 tabular-nums">{{ $p->tanggal_buka->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 tabular-nums">{{ $p->tanggal_tutup->format('d/m/Y') }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-bold">{{ number_format($p->kuota, 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-semibold text-[#0E6026]">
                                    {{ number_format($p->pendaftar_count, 0, ',', '.') }} Siswa
                                </td>
                                <td class="py-3 px-3 text-center">
                                    @if($p->is_aktif)
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-neutral-100 text-neutral-600">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <form action="{{ route('admin.ppdb.periode.toggle', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium {{ $p->is_aktif ? 'text-[#C81210]' : 'text-[#0E6026]' }} hover:underline">
                                            {{ $p->is_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-xs text-[#545B52]">
                                    Belum ada data periode PPDB yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
