<x-layouts.admin title="Manajemen Guru" heading="Data Guru & Tenaga Pendidik">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        <!-- Form Tambah Guru Baru -->
        <x-card title="Tambah Data Guru & Akun SSO" subtitle="Guru otomatis didaftarkan akun Single Sign-On (SSO) untuk login ke portal nilai">
            <form action="{{ route('admin.siakad.guru.simpan') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <x-input
                        label="Nama Lengkap"
                        name="nama_lengkap"
                        required
                        placeholder="Contoh: Ahmad Fauzi"
                        :value="old('nama_lengkap')"
                    />
                </div>

                <div>
                    <x-input
                        label="Gelar Akademik"
                        name="gelar"
                        placeholder="Contoh: S.Pd., M.Pd."
                        :value="old('gelar')"
                    />
                </div>

                <div>
                    <x-input
                        label="NIP Guru"
                        name="nip"
                        required
                        class="tabular-nums"
                        placeholder="18 digit NIP"
                        :value="old('nip')"
                    />
                </div>

                <div>
                    <x-input
                        label="Alamat Email (Login SSO)"
                        name="email"
                        type="email"
                        required
                        placeholder="nama@sman1tb.sch.id"
                        :value="old('email')"
                    />
                </div>

                <div>
                    <x-input
                        label="No. HP / WhatsApp"
                        name="no_hp"
                        class="tabular-nums"
                        placeholder="081234567890"
                        :value="old('no_hp')"
                    />
                </div>

                <div class="sm:col-span-2 lg:col-span-5 flex justify-end pt-2">
                    <x-button type="submit" variant="primary">
                        Simpan Data Guru & Buat Akun
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Tabel Daftar Guru -->
        <x-card title="Daftar Guru Terdaftar">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">NIP</th>
                            <th class="py-3 px-3">Nama Lengkap & Gelar</th>
                            <th class="py-3 px-3">Email Akun SSO</th>
                            <th class="py-3 px-3">No. HP</th>
                            <th class="py-3 px-3 text-right">Penugasan Mengajar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($guruList as $g)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 font-mono tabular-nums text-[#545B52]">{{ $g->nip }}</td>
                                <td class="py-3 px-3 font-semibold text-[#1C2620]">
                                    {{ $g->nama_lengkap }}{{ $g->gelar ? ', ' . $g->gelar : '' }}
                                </td>
                                <td class="py-3 px-3 text-[#545B52]">{{ $g->user->email ?? '-' }}</td>
                                <td class="py-3 px-3 font-mono tabular-nums text-[#545B52]">{{ $g->no_hp ?? '-' }}</td>
                                <td class="py-3 px-3 text-right tabular-nums font-semibold text-[#0E6026]">
                                    {{ $g->pengampu_count }} Kelas/Mapel
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada data guru terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
