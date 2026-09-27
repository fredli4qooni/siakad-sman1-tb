<x-layouts.admin title="Data Guru" heading="Manajemen Guru Pengajar">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <p class="text-xs text-[#545B52]">Data seluruh tenaga pendidik sekolah yang terdaftar pada sistem SSO.</p>
            <x-button type="button" variant="primary">Tambah Guru</x-button>
        </div>
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">NIP</th>
                            <th class="py-3 px-3">Nama Lengkap & Gelar</th>
                            <th class="py-3 px-3">Email Akun SSO</th>
                            <th class="py-3 px-3">Nomor Telepon</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        <tr class="hover:bg-[#F3F5F2]">
                            <td class="py-3 px-3 font-mono tabular-nums">197508122000031001</td>
                            <td class="py-3 px-3 font-semibold">Drs. Ahmad Fauzi, M.Pd.</td>
                            <td class="py-3 px-3 text-[#545B52]">ahmad.fauzi@sman1tb.sch.id</td>
                            <td class="py-3 px-3 tabular-nums">081272345678</td>
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
