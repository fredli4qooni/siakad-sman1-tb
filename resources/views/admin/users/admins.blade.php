<x-layouts.admin title="Kelola Akun Admin" heading="Manajemen Akun Administrator & Operator">
    <div class="space-y-6">

        <!-- 3 Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Super Administrator</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">{{ number_format($totalAdmin, 0, ',', '.') }} Akun</div>
                <div class="text-[11px] text-[#545B52] mt-0.5">Akses penuh seluruh modul sistem</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Operator Sekolah</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">{{ number_format($totalOperator, 0, ',', '.') }} Akun</div>
                <div class="text-[11px] text-[#545B52] mt-0.5">Pengelola SPMB dan verifikasi berkas</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#0E6026]">Total Akun Aktif</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">{{ number_format($totalAktif, 0, ',', '.') }} Akun</div>
                <div class="text-[11px] text-[#0E6026] mt-0.5">Dapat login via portal SSO</div>
            </div>
        </div>

        <!-- Formulir Pembuatan Akun Admin Baru -->
        <x-card title="Buat Akun Admin / Operator Baru" subtitle="Hanya administrator yang berhak menerbitkan akun baru untuk pengelola sistem sekolah">
            <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <x-input
                            label="Nama Lengkap Pengelola"
                            name="nama"
                            required
                            placeholder="Contoh: Budi Santoso, S.Kom."
                            :value="old('nama')"
                        />
                    </div>

                    <div>
                        <x-input
                            type="email"
                            label="Alamat Email Akun SSO"
                            name="email"
                            required
                            placeholder="nama.pengelola@sman1tb.sch.id"
                            :value="old('email')"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Peran / Hak Akses <span class="text-[#C81210]">*</span>
                        </label>
                        <select name="role" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]" required>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                            <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator Sekolah (SPMB & Verifikasi)</option>
                        </select>
                    </div>

                    <div>
                        <x-input
                            type="password"
                            label="Kata Sandi (Password)"
                            name="password"
                            required
                            placeholder="Minimal 8 karakter"
                        />
                    </div>

                    <div>
                        <x-input
                            type="password"
                            label="Konfirmasi Kata Sandi"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi kata sandi"
                        />
                    </div>

                    <div class="flex items-end">
                        <x-button type="submit" variant="primary" class="w-full py-2.5 flex items-center justify-center gap-2 font-semibold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Simpan & Terbitkan Akun
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Filter Pencarian & Tabel Daftar Pengelola -->
        <x-card title="Daftar Administrator & Operator Terdaftar" subtitle="Seluruh akun pengelola yang memiliki akses ke modul panel admin">
            <!-- Filter Pencarian -->
            <form action="{{ route('admin.admins.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between pb-4 border-b border-[#E1E4DE]">
                <div class="w-full sm:w-80">
                    <input
                        type="text"
                        name="keyword"
                        class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        placeholder="Cari nama atau email pengelola..."
                        value="{{ request('keyword') }}"
                    />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <x-button type="submit" variant="secondary" class="text-xs">
                        Cari
                    </x-button>
                    @if(request('keyword'))
                        <a href="{{ route('admin.admins.index') }}" class="text-xs text-[#545B52] hover:text-[#1C2620] px-2 py-1">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama Pengelola</th>
                            <th class="py-3 px-3">Email Akun SSO</th>
                            <th class="py-3 px-3">Peran / Role</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Terdaftar Sejak</th>
                            <th class="py-3 px-3 text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($adminUsers as $admin)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3.5 px-3">
                                    <div class="font-semibold text-[#1C2620] flex items-center gap-2">
                                        {{ $admin->nama }}
                                        @if($admin->id === auth()->id())
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/30">
                                                Akun Anda
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-3 font-mono text-[#545B52]">
                                    {{ $admin->email }}
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($admin->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026] border border-[#0E6026]/10">
                                            Administrator
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E8F0FE] text-[#1A73E8] border border-[#1A73E8]/10">
                                            Operator Sekolah
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($admin->status_aktif)
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
                                    {{ $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    @if($admin->id === auth()->id())
                                        <span class="text-xs text-neutral-400 italic">Sedang Digunakan</span>
                                    @else
                                        <div class="inline-flex items-center gap-2">
                                            <!-- Toggle Status Aktif/Nonaktif -->
                                            <form action="{{ route('admin.admins.toggle', $admin->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $admin->status_aktif ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun {{ $admin->nama }}?')"
                                                    class="text-xs font-semibold {{ $admin->status_aktif ? 'text-[#6B6200] hover:underline' : 'text-[#0E6026] hover:underline' }}"
                                                >
                                                    {{ $admin->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>

                                            <!-- Tombol Reset Password Dialog -->
                                            <button
                                                type="button"
                                                onclick="openResetPasswordModal('{{ $admin->id }}', '{{ addslashes($admin->nama) }}')"
                                                class="text-xs font-semibold text-[#1A73E8] hover:underline"
                                            >
                                                Reset Password
                                            </button>

                                            <!-- Hapus Akun -->
                                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $admin->nama }} secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-[#C81210] hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada akun administrator/operator yang cocok dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($adminUsers->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $adminUsers->links() }}
                </div>
            @endif
        </x-card>
    </div>

    <!-- Modal Reset Password -->
    <div id="reset-password-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E1E4DE] m-4">
            <h3 class="text-base font-bold text-[#1C2620]">Reset Kata Sandi Akun</h3>
            <p class="text-xs text-[#545B52] mt-1" id="reset-modal-subtitle">
                Masukkan kata sandi baru untuk akun pengelola ini.
            </p>

            <form id="reset-password-form" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <x-input
                        type="password"
                        label="Kata Sandi Baru"
                        name="password"
                        required
                        minlength="8"
                        placeholder="Minimal 8 karakter"
                    />
                </div>

                <div>
                    <x-input
                        type="password"
                        label="Ulangi Kata Sandi Baru"
                        name="password_confirmation"
                        required
                        minlength="8"
                        placeholder="Konfirmasi kata sandi baru"
                    />
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E1E4DE]">
                    <button
                        type="button"
                        onclick="closeResetPasswordModal()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-[#545B52] hover:bg-[#F3F5F2]"
                    >
                        Batal
                    </button>
                    <x-button type="submit" variant="primary" class="text-xs">
                        Perbarui Kata Sandi
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openResetPasswordModal(userId, userName) {
            const modal = document.getElementById('reset-password-modal');
            const form = document.getElementById('reset-password-form');
            const subtitle = document.getElementById('reset-modal-subtitle');

            form.action = `/admin/admins/${userId}/password`;
            subtitle.innerText = `Mengubah kata sandi untuk akun pengelola: ${userName}`;
            modal.classList.remove('hidden');
        }

        function closeResetPasswordModal() {
            const modal = document.getElementById('reset-password-modal');
            modal.classList.add('hidden');
        }
    </script>
</x-layouts.admin>
