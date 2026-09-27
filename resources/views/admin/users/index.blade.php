<x-layouts.admin title="Manajemen Pengguna" heading="Manajemen Akun Pengguna (SSO)">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Daftar seluruh akun pengguna terdaftar pada Identity Provider (IdP) SSO.</p>
            <x-button type="button" variant="primary">Tambah Pengguna</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Nama</th>
                            <th class="py-3 px-3">Email Akun</th>
                            <th class="py-3 px-3">Peran / Role</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-semibold">Administrator Sekolah</td>
                            <td class="py-3 px-3 text-[#545B52]">admin@sman1tb.sch.id</td>
                            <td class="py-3 px-3"><x-badge status="aktif">Admin</x-badge></td>
                            <td class="py-3 px-3 text-[#0E6026] font-medium">Aktif</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Ubah</x-button>
                            </td>
                        </tr>
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-semibold">Drs. Ahmad Fauzi, M.Pd.</td>
                            <td class="py-3 px-3 text-[#545B52]">ahmad.fauzi@sman1tb.sch.id</td>
                            <td class="py-3 px-3"><x-badge status="aktif">Guru</x-badge></td>
                            <td class="py-3 px-3 text-[#0E6026] font-medium">Aktif</td>
                            <td class="py-3 px-3 text-right">
                                <x-button type="button" variant="ghost" class="text-xs">Ubah</x-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-layouts.admin>
