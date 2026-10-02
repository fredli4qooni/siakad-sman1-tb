<x-layouts.admin title="Verifikasi Pendaftar" heading="Verifikasi Berkas Calon Siswa">
    <div class="space-y-6">

        <!-- 4 Statistik Kartu Ringkas -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Total Pendaftar</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">{{ number_format($totalPendaftar, 0, ',', '.') }}</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#6B6200]">Menunggu Verifikasi</div>
                <div class="text-2xl font-bold text-[#6B6200] tabular-nums mt-1">{{ number_format($menungguVerifikasi, 0, ',', '.') }}</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#0E6026]">Berkas Terverifikasi</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">{{ number_format($terverifikasi, 0, ',', '.') }}</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#039834]">Dinyatakan Lulus</div>
                <div class="text-2xl font-bold text-[#039834] tabular-nums mt-1">{{ number_format($lulus, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Filter Pencarian & Kategori -->
        <x-card>
            <form action="{{ route('admin.ppdb.pendaftar') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2">
                    <x-input
                        label="Pencarian Siswa"
                        name="keyword"
                        placeholder="Cari Nama, NISN, No. Registrasi, atau Asal Sekolah"
                        :value="request('keyword')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Status Pendaftaran</label>
                    <select name="status" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="terverifikasi" {{ request('status') === 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="tidak_lulus" {{ request('status') === 'tidak_lulus' ? 'selected' : '' }}>Tidak Lulus</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter Data
                    </x-button>
                    @if(request()->hasAny(['keyword', 'status', 'periode_id']))
                        <a href="{{ route('admin.ppdb.pendaftar') }}" class="p-2 text-xs text-[#545B52] hover:text-[#1C2620]">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Data Pendaftar -->
        <x-card title="Daftar Calon Peserta Didik Baru">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No. Registrasi</th>
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">Asal Sekolah</th>
                            <th class="py-3 px-3 text-center">Berkas</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($pendaftarList as $p)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-mono font-medium">{{ $p->no_pendaftaran }}</td>
                                <td class="py-3 px-3 tabular-nums font-mono">{{ $p->nisn }}</td>
                                <td class="py-3 px-3 font-semibold">{{ $p->nama_lengkap }}</td>
                                <td class="py-3 px-3 text-[#545B52]">{{ $p->asal_sekolah }}</td>
                                <td class="py-3 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-mono tabular-nums bg-neutral-100 text-[#1C2620]">
                                        {{ $p->berkas->count() }}/4
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    @if(in_array($p->status_pendaftaran, ['lulus', 'diterima']))
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">Diterima</span>
                                    @elseif($p->status_pendaftaran === 'tidak_lulus')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FBEAEA] text-[#C81210]">Tidak Lulus</span>
                                    @elseif($p->isDijadwalkanFisik())
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FBF9D6] text-[#6B6200]">Jadwal Fisik</span>
                                    @elseif($p->status_pendaftaran === 'terverifikasi')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">Terverifikasi</span>
                                    @elseif($p->status_pendaftaran === 'menunggu_verifikasi')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-[#FBF9D6] text-[#6B6200]">Menunggu Verifikasi</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-neutral-100 text-neutral-600">Draft</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <x-button as="a" href="{{ route('admin.ppdb.verifikasi.show', $p->id) }}" variant="secondary" class="text-xs py-1">
                                        Periksa Berkas
                                    </x-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#545B52]">
                                    Tidak ada data calon siswa yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendaftarList->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $pendaftarList->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
