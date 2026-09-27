<x-layouts.admin title="Hasil Seleksi PPDB" heading="Penetapan Hasil Seleksi & Kelulusan">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        <div class="p-4 rounded-xl bg-[#E7F4EA] border border-[#039834]/30 text-xs text-[#0E6026] flex items-center justify-between gap-4">
            <div>
                <strong>Integrasi Otomatis SIAKAD (OIDC SSO):</strong> Calon siswa yang Anda tetapkan <strong>LULUS</strong> akan secara otomatis disinkronisasikan ke modul SIAKAD menjadi Siswa Aktif tanpa entri data manual berulang.
            </div>
            <x-badge status="aktif">SSO Terhubung</x-badge>
        </div>

        <!-- Filter Pencarian -->
        <x-card>
            <form action="{{ route('admin.ppdb.seleksi') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2">
                    <x-input
                        label="Cari Calon Siswa"
                        name="keyword"
                        placeholder="Nama, NISN, atau No. Pendaftaran"
                        :value="request('keyword')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Status Seleksi</label>
                    <select name="status_seleksi" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Keputusan</option>
                        <option value="LULUS" {{ request('status_seleksi') === 'LULUS' ? 'selected' : '' }}>Lulus</option>
                        <option value="TIDAK_LULUS" {{ request('status_seleksi') === 'TIDAK_LULUS' ? 'selected' : '' }}>Tidak Lulus</option>
                        <option value="MENUNGGU" {{ request('status_seleksi') === 'MENUNGGU' ? 'selected' : '' }}>Menunggu Sidang</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Terapkan Filter
                    </x-button>
                    @if(request()->hasAny(['keyword', 'status_seleksi']))
                        <a href="{{ route('admin.ppdb.seleksi') }}" class="p-2 text-xs text-[#545B52] hover:text-[#1C2620]">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Calon Siswa Siap Seleksi -->
        <x-card title="Daftar Calon Siswa Terverifikasi">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">No. Registrasi</th>
                            <th class="py-3 px-3">Nama Lengkap</th>
                            <th class="py-3 px-3">NISN</th>
                            <th class="py-3 px-3">Asal Sekolah</th>
                            <th class="py-3 px-3">Status Saat Ini</th>
                            <th class="py-3 px-3 text-right">Penetapan Keputusan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($pendaftarSeleksi as $p)
                            @php
                                $hasil = $p->hasilSeleksi;
                                $status = $hasil ? $hasil->status : 'MENUNGGU';
                            @endphp
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-mono font-medium">{{ $p->no_pendaftaran }}</td>
                                <td class="py-3 px-3 font-semibold">{{ $p->nama_lengkap }}</td>
                                <td class="py-3 px-3 tabular-nums font-mono">{{ $p->nisn }}</td>
                                <td class="py-3 px-3 text-[#545B52]">{{ $p->asal_sekolah }}</td>
                                <td class="py-3 px-3">
                                    @if($status === 'LULUS')
                                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-[#E7F4EA] text-[#0E6026]">
                                            ✓ LULUS
                                        </span>
                                    @elseif($status === 'TIDAK_LULUS')
                                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-[#FBEAEA] text-[#C81210]">
                                            ✕ TIDAK LULUS
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBF9D6] text-[#6B6200]">
                                            Menunggu Penetapan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <form action="{{ route('admin.ppdb.kelulusan.simpan', $p->id) }}" method="POST" class="inline-flex items-center gap-2">
                                        @csrf
                                        <select name="status" class="rounded-lg border border-[#C9CDC3] px-2 py-1 text-xs text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                                            <option value="LULUS" {{ $status === 'LULUS' ? 'selected' : '' }}>LULUS</option>
                                            <option value="TIDAK_LULUS" {{ $status === 'TIDAK_LULUS' ? 'selected' : '' }}>TIDAK LULUS</option>
                                            <option value="MENUNGGU" {{ $status === 'MENUNGGU' ? 'selected' : '' }}>MENUNGGU</option>
                                        </select>

                                        <input
                                            type="hidden"
                                            name="catatan"
                                            value="Penetapan seleksi reguler panitia PPDB"
                                        />

                                        <x-button type="submit" variant="primary" class="text-xs py-1 px-3">
                                            Simpan
                                        </x-button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada calon peserta didik yang berstatus terverifikasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendaftarSeleksi->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $pendaftarSeleksi->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
