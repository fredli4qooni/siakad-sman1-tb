<x-layouts.admin title="Manajemen Pengguna" heading="Manajemen Akun Pengguna (SSO)">
    <div class="space-y-6">

        <!-- Header Aksi & Penjelasan -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-[#545B52]">
                    Daftar seluruh akun pengguna yang terdaftar pada Identity Provider (IdP) OpenID Connect internal SMAN 1 Terbanggi Besar.
                </p>
            </div>
            @if(auth()->check() && auth()->user()->isAdmin())
                <div>
                    <x-button as="a" href="{{ route('admin.admins.index') }}" variant="primary" class="text-xs font-semibold flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Buat / Kelola Akun Admin
                    </x-button>
                </div>
            @endif
        </div>

        <!-- 4 Statistik Pengguna SSO -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#545B52]">Total Pengguna</div>
                <div class="text-xl font-bold text-[#1C2620] tabular-nums mt-0.5">{{ number_format($stats['total'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#0E6026]">Administrator</div>
                <div class="text-xl font-bold text-[#0E6026] tabular-nums mt-0.5">{{ number_format($stats['admin'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#1A73E8]">Operator</div>
                <div class="text-xl font-bold text-[#1A73E8] tabular-nums mt-0.5">{{ number_format($stats['operator'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#545B52]">Guru</div>
                <div class="text-xl font-bold text-[#1C2620] tabular-nums mt-0.5">{{ number_format($stats['guru'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#0E6026]">Siswa Aktif</div>
                <div class="text-xl font-bold text-[#0E6026] tabular-nums mt-0.5">{{ number_format($stats['siswa'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                <div class="text-[11px] text-[#6B6200]">Calon Siswa</div>
                <div class="text-xl font-bold text-[#6B6200] tabular-nums mt-0.5">{{ number_format($stats['calon_siswa'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Filter Pencarian & Peran -->
        <x-card>
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2">
                    <x-input
                        label="Cari Akun Pengguna"
                        name="keyword"
                        placeholder="Nama atau Alamat Email..."
                        :value="request('keyword')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Filter Peran (Role)</label>
                    <select name="role" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator Sekolah</option>
                        <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru Pengampu</option>
                        <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa Aktif SIAKAD</option>
                        <option value="calon_siswa" {{ request('role') === 'calon_siswa' ? 'selected' : '' }}>Calon Murid Baru</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter Akun
                    </x-button>
                    @if(request()->hasAny(['keyword', 'role']))
                        <a href="{{ route('admin.users.index') }}" class="p-2 text-xs text-[#545B52] hover:text-[#1C2620]">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Akun Pengguna SSO -->
        <x-card title="Daftar Pengguna SSO Aktif">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama Pengguna</th>
                            <th class="py-3 px-3">Email Akun SSO</th>
                            <th class="py-3 px-3">Peran / Role</th>
                            <th class="py-3 px-3">Status Akun</th>
                            <th class="py-3 px-3">Waktu Pendaftaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($users as $user)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3.5 px-3">
                                    <div class="font-semibold text-[#1C2620]">
                                        {{ $user->nama }}
                                        @if($user->id === auth()->id())
                                            <span class="ml-1 px-2 py-0.5 rounded text-[10px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                                (Anda)
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-[#545B52]">
                                    {{ $user->email }}
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($user->role === 'admin')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                            Administrator
                                        </span>
                                    @elseif($user->role === 'operator')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E8F0FE] text-[#1A73E8]">
                                            Operator
                                        </span>
                                    @elseif($user->role === 'guru')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#F3F5F2] text-[#1C2620]">
                                            Guru
                                        </span>
                                    @elseif($user->role === 'siswa')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            Siswa Aktif
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-[#FBF9D6] text-[#6B6200]">
                                            Calon Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($user->status_aktif)
                                        <span class="inline-flex items-center gap-1.5 text-[#0E6026] font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-[#C81210] font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#C81210]"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 tabular-nums text-[#545B52]">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-[#545B52]">
                                    Tidak ada data pengguna yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $users->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
