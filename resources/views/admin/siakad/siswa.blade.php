<x-layouts.admin title="Data Siswa SIAKAD" heading="Manajemen Data Siswa SIAKAD">
    <div class="space-y-6">

        <!-- Header Actions & Statistik Ringkas -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-[#545B52]">
                    Kelola data pokok siswa aktif, akun Single Sign-On (SSO), serta penempatan rombongan belajar (kelas).
                </p>
            </div>
            <div>
                <button
                    type="button"
                    onclick="openTambahSiswaModal()"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-[#0E6026] text-white hover:bg-[#0A4C1E] transition-colors shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Siswa Baru
                </button>
            </div>
        </div>

        <!-- 4 Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white shadow-2xs">
                <div class="text-xs text-[#545B52]">Total Siswa Terdaftar</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">{{ number_format($totalSiswa, 0, ',', '.') }}</div>
                <div class="text-[11px] text-[#545B52] mt-0.5">Siswa di database SIAKAD</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white shadow-2xs">
                <div class="text-xs text-[#0E6026]">Siswa Status Aktif</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">{{ number_format($totalAktif, 0, ',', '.') }}</div>
                <div class="text-[11px] text-[#0E6026] mt-0.5">Dapat login via portal SSO</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white shadow-2xs">
                <div class="text-xs text-[#6B6200]">Belum Masuk Rombel</div>
                <div class="text-2xl font-bold text-[#6B6200] tabular-nums mt-1">{{ number_format($belumDitempatkan, 0, ',', '.') }}</div>
                <div class="text-[11px] text-[#6B6200] mt-0.5">Perlu penempatan kelas</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white shadow-2xs">
                <div class="text-xs text-[#545B52]">Komposisi Gender</div>
                <div class="text-lg font-bold text-[#1C2620] mt-1.5 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded bg-[#E7F4EA] text-[#0E6026]">
                        L: {{ $totalLaki }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded bg-[#F3F5F2] text-[#545B52]">
                        P: {{ $totalPerempuan }}
                    </span>
                </div>
                <div class="text-[11px] text-[#545B52] mt-1">Laki-laki &bull; Perempuan</div>
            </div>
        </div>

        <!-- Filter Pencarian & Kelas -->
        <x-card>
            <form action="{{ route('admin.siakad.siswa.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                <div class="sm:col-span-5">
                    <x-input
                        label="Cari Siswa"
                        name="keyword"
                        placeholder="Nama Siswa, NISN, NIS, atau Email SSO"
                        :value="request('keyword')"
                    />
                </div>

                <div class="sm:col-span-3">
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Filter Rombel / Kelas</label>
                    <select name="kelas_id" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Rombel</option>
                        <option value="belum_ada" {{ request('kelas_id') === 'belum_ada' ? 'selected' : '' }}>-- Belum Memiliki Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Status Akun</label>
                    <select name="status_aktif" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Status</option>
                        <option value="1" {{ request('status_aktif') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status_aktif') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter
                    </x-button>
                    @if(request()->hasAny(['keyword', 'kelas_id', 'status_aktif']))
                        <a href="{{ route('admin.siakad.siswa.index') }}" class="p-2 text-xs font-semibold text-[#545B52] hover:text-[#1C2620] whitespace-nowrap">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Siswa & Aksi CRUD -->
        <x-card title="Daftar Siswa SIAKAD">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Identitas Siswa</th>
                            <th class="py-3 px-3">Akun SSO & Kontak</th>
                            <th class="py-3 px-3 text-center">L/P</th>
                            <th class="py-3 px-3">Tahun Masuk</th>
                            <th class="py-3 px-3">Rombel Kelas</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($siswaList as $s)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <!-- Identitas Siswa -->
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-[#1C2620] text-sm">{{ $s->nama }}</div>
                                    <div class="text-[11px] text-[#545B52] font-mono mt-0.5">
                                        NISN: <span class="font-semibold text-[#1C2620]">{{ $s->nisn }}</span>
                                        @if($s->nis)
                                            &bull; NIS: <span class="font-semibold text-[#0E6026]">{{ $s->nis }}</span>
                                        @endif
                                    </div>
                                    @if($s->syncLog)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/20">
                                                Sinkron SPMB
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Akun SSO & Kontak -->
                                <td class="py-3.5 px-3">
                                    <div class="font-medium text-[#1C2620]">{{ $s->user?->email ?? '-' }}</div>
                                    @if($s->alamat)
                                        <div class="text-[11px] text-[#545B52] truncate max-w-xs mt-0.5" title="{{ $s->alamat }}">
                                            {{ $s->alamat }}
                                        </div>
                                    @else
                                        <div class="text-[11px] text-[#9FA29E] italic mt-0.5">Alamat belum diisi</div>
                                    @endif
                                </td>

                                <!-- Jenis Kelamin -->
                                <td class="py-3.5 px-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $s->jenis_kelamin === 'L' ? 'bg-[#E7F4EA] text-[#0E6026]' : 'bg-[#FBEAEA] text-[#C81210]' }}">
                                        {{ $s->jenis_kelamin }}
                                    </span>
                                </td>

                                <!-- Tahun Masuk -->
                                <td class="py-3.5 px-3 tabular-nums font-medium text-[#545B52]">
                                    {{ $s->tahun_masuk }}
                                </td>

                                <!-- Rombel Kelas -->
                                <td class="py-3.5 px-3">
                                    @if($s->kelas)
                                        <div>
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                                {{ $s->kelas->nama_kelas }} (Tingkat {{ $s->kelas->tingkat }})
                                            </span>
                                            <div class="text-[10px] text-[#545B52] mt-0.5">
                                                TP {{ $s->kelas->tahun_ajaran }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-[#FBF9D6] text-[#6B6200]">
                                            Belum Ditempatkan
                                        </span>
                                    @endif

                                    <!-- Quick Change Ploting -->
                                    <form action="{{ route('admin.siakad.siswa.ploting', $s->id) }}" method="POST" class="inline-flex items-center gap-1.5 mt-1.5">
                                        @csrf
                                        <select name="kelas_id" class="rounded border border-[#C9CDC3] px-1.5 py-0.5 text-[11px] text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                                            <option value="">-- Pindah Kelas --</option>
                                            @foreach($kelasList as $k)
                                                <option value="{{ $k->id }}" {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" title="Simpan Perubahan Kelas" class="text-[10px] font-bold text-[#0E6026] hover:underline px-1 py-0.5 bg-[#E7F4EA] rounded border border-[#039834]/30">
                                            Ubah
                                        </button>
                                    </form>
                                </td>

                                <!-- Status Aktif -->
                                <td class="py-3.5 px-3 text-center">
                                    @if($s->status_aktif)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-[#F3F5F2] text-[#545B52] border border-[#E1E4DE]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#545B52]"></span>
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi CRUD -->
                                <td class="py-3.5 px-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Detail Siswa -->
                                        <button
                                            type="button"
                                            onclick='openDetailSiswaModal(@json($s))'
                                            class="text-xs font-semibold text-[#545B52] hover:text-[#1C2620] hover:underline"
                                            title="Lihat Detail Profil Siswa"
                                        >
                                            Detail
                                        </button>

                                        <!-- Edit Siswa -->
                                        <button
                                            type="button"
                                            onclick='openEditSiswaModal(@json($s))'
                                            class="text-xs font-semibold text-[#0E6026] hover:underline"
                                            title="Edit Data Siswa"
                                        >
                                            Edit
                                        </button>

                                        <!-- Reset Password -->
                                        <button
                                            type="button"
                                            onclick="openResetPasswordModal('{{ $s->id }}', '{{ addslashes($s->nama) }}')"
                                            class="text-xs font-semibold text-[#1A73E8] hover:underline"
                                            title="Reset Kata Sandi SSO"
                                        >
                                            Reset
                                        </button>

                                        <!-- Hapus Siswa -->
                                        <form action="{{ route('admin.siakad.siswa.hapus', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ addslashes($s->nama) }}? Data siswa dan pendaftaran SPMB terkait (jika ada) akan ikut dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="hapus_pendaftar" value="1">
                                            <button type="submit" class="text-xs font-semibold text-[#C81210] hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada data siswa yang cocok dengan kriteria pencarian atau filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($siswaList->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $siswaList->links() }}
                </div>
            @endif
        </x-card>
    </div>

    <!-- ==================== MODAL TAMBAH SISWA BARU ==================== -->
    <div id="modal-tambah-siswa" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden overflow-y-auto p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-[#E1E4DE] my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-[#E1E4DE]">
                <div>
                    <h3 class="text-base font-bold text-[#1C2620]">Tambah Siswa Baru</h3>
                    <p class="text-xs text-[#545B52] mt-0.5">Entri data pokok siswa baru dan buatkan akun Single Sign-On (SSO).</p>
                </div>
                <button type="button" onclick="closeTambahSiswaModal()" class="text-[#545B52] hover:text-[#1C2620] text-xl font-bold p-1">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.siakad.siswa.simpan') }}" method="POST" class="space-y-4 mt-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <x-input
                            label="Nama Lengkap Siswa"
                            name="nama"
                            required
                            placeholder="Contoh: Muhammad Rizki Pratama"
                            :value="old('nama')"
                        />
                    </div>

                    <div>
                        <x-input
                            label="NISN (10 Digit)"
                            name="nisn"
                            required
                            maxlength="10"
                            placeholder="Contoh: 0081234567"
                            :value="old('nisn')"
                        />
                    </div>

                    <div>
                        <x-input
                            label="Nomor Induk Siswa (NIS)"
                            name="nis"
                            placeholder="Contoh: 262710001 (Opsional)"
                            :value="old('nis')"
                        />
                    </div>

                    <div>
                        <x-input
                            type="email"
                            label="Alamat Email Akun SSO"
                            name="email"
                            required
                            placeholder="siswa@sman1tb.sch.id"
                            :value="old('email')"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Jenis Kelamin <span class="text-[#C81210]">*</span>
                        </label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]" required>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Penempatan Rombel / Kelas
                        </label>
                        <select name="kelas_id" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                            <option value="">-- Belum Ditempatkan --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input
                            label="Tahun Masuk"
                            name="tahun_masuk"
                            required
                            maxlength="4"
                            placeholder="Contoh: {{ date('Y') }}"
                            :value="old('tahun_masuk', date('Y'))"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input
                            type="password"
                            label="Kata Sandi Akun SSO (Opsional)"
                            name="password"
                            placeholder="Kosongkan untuk kata sandi default: password123"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Alamat Tempat Tinggal
                        </label>
                        <textarea
                            name="alamat"
                            rows="2"
                            placeholder="Nama jalan, RT/RW, kelurahan/desa, kecamatan"
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        >{{ old('alamat') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status_aktif" value="1" checked class="rounded border-[#C9CDC3] text-[#0E6026] focus:ring-[#039834]">
                            <span class="text-xs font-semibold text-[#1C2620]">Akun Siswa Aktif (Dapat mengakses portal SIAKAD)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-[#E1E4DE]">
                    <button
                        type="button"
                        onclick="closeTambahSiswaModal()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-[#545B52] hover:bg-[#F3F5F2]"
                    >
                        Batal
                    </button>
                    <x-button type="submit" variant="primary" class="text-xs">
                        Simpan Siswa Baru
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT SISWA ==================== -->
    <div id="modal-edit-siswa" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden overflow-y-auto p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-[#E1E4DE] my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-[#E1E4DE]">
                <div>
                    <h3 class="text-base font-bold text-[#1C2620]">Edit Data Siswa</h3>
                    <p class="text-xs text-[#545B52] mt-0.5">Perbarui biodata pokok siswa dan akun SSO yang terhubung.</p>
                </div>
                <button type="button" onclick="closeEditSiswaModal()" class="text-[#545B52] hover:text-[#1C2620] text-xl font-bold p-1">
                    &times;
                </button>
            </div>

            <form id="form-edit-siswa" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Nama Lengkap Siswa <span class="text-[#C81210]">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            id="edit-nama"
                            required
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            NISN (10 Digit) <span class="text-[#C81210]">*</span>
                        </label>
                        <input
                            type="text"
                            name="nisn"
                            id="edit-nisn"
                            required
                            maxlength="10"
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Nomor Induk Siswa (NIS)
                        </label>
                        <input
                            type="text"
                            name="nis"
                            id="edit-nis"
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Alamat Email Akun SSO <span class="text-[#C81210]">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="edit-email"
                            required
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        />
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Jenis Kelamin <span class="text-[#C81210]">*</span>
                        </label>
                        <select name="jenis_kelamin" id="edit-jenis_kelamin" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]" required>
                            <option value="L">Laki-laki (L)</option>
                            <option value="P">Perempuan (P)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Penempatan Rombel / Kelas
                        </label>
                        <select name="kelas_id" id="edit-kelas_id" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                            <option value="">-- Belum Ditempatkan --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Tahun Masuk <span class="text-[#C81210]">*</span>
                        </label>
                        <input
                            type="text"
                            name="tahun_masuk"
                            id="edit-tahun_masuk"
                            required
                            maxlength="4"
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <x-input
                            type="password"
                            label="Ganti Kata Sandi (Opsional)"
                            name="password"
                            placeholder="Biarkan kosong jika tidak ingin mengubah kata sandi"
                        />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Alamat Tempat Tinggal
                        </label>
                        <textarea
                            name="alamat"
                            id="edit-alamat"
                            rows="2"
                            class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        ></textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="status_aktif" id="edit-status_aktif" value="1" class="rounded border-[#C9CDC3] text-[#0E6026] focus:ring-[#039834]">
                            <span class="text-xs font-semibold text-[#1C2620]">Status Siswa Aktif (Dapat login ke SIAKAD)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-[#E1E4DE]">
                    <button
                        type="button"
                        onclick="closeEditSiswaModal()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-[#545B52] hover:bg-[#F3F5F2]"
                    >
                        Batal
                    </button>
                    <x-button type="submit" variant="primary" class="text-xs">
                        Perbarui Data Siswa
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL DETAIL SISWA ==================== -->
    <div id="modal-detail-siswa" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden overflow-y-auto p-4">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-xl border border-[#E1E4DE] my-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-[#E1E4DE]">
                <div>
                    <h3 class="text-base font-bold text-[#1C2620]" id="detail-nama">-</h3>
                    <p class="text-xs text-[#545B52] mt-0.5">Detail Data Induk Kependidikan & Akun Siswa</p>
                </div>
                <button type="button" onclick="closeDetailSiswaModal()" class="text-[#545B52] hover:text-[#1C2620] text-xl font-bold p-1">
                    &times;
                </button>
            </div>

            <div class="space-y-4 mt-4 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <span class="text-[#545B52] block text-[11px]">NISN</span>
                        <span class="font-mono font-bold text-sm text-[#1C2620]" id="detail-nisn">-</span>
                    </div>
                    <div class="p-3 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE]">
                        <span class="text-[#545B52] block text-[11px]">Nomor Induk Siswa (NIS)</span>
                        <span class="font-mono font-bold text-sm text-[#0E6026]" id="detail-nis">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                        <span class="text-[#545B52] block text-[11px]">Jenis Kelamin</span>
                        <span class="font-semibold text-[#1C2620]" id="detail-jk">-</span>
                    </div>
                    <div class="p-3 bg-white rounded-xl border border-[#E1E4DE]">
                        <span class="text-[#545B52] block text-[11px]">Tahun Masuk</span>
                        <span class="font-semibold text-[#1C2620]" id="detail-tahun">-</span>
                    </div>
                </div>

                <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE] space-y-1">
                    <span class="text-[#545B52] block text-[11px]">Rombongan Belajar (Kelas)</span>
                    <span class="font-bold text-sm text-[#0E6026]" id="detail-kelas">-</span>
                </div>

                <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE] space-y-1">
                    <span class="text-[#545B52] block text-[11px]">Akun Email SSO</span>
                    <span class="font-medium text-[#1C2620]" id="detail-email">-</span>
                </div>

                <div class="p-3.5 bg-white rounded-xl border border-[#E1E4DE] space-y-1">
                    <span class="text-[#545B52] block text-[11px]">Alamat Tempat Tinggal</span>
                    <span class="text-[#1C2620]" id="detail-alamat">-</span>
                </div>

                <div class="p-3.5 bg-[#F3F5F2] rounded-xl border border-[#E1E4DE] flex items-center justify-between">
                    <div>
                        <span class="text-[#545B52] block text-[11px]">Status Kependidikan</span>
                        <span class="font-semibold" id="detail-status">-</span>
                    </div>
                    <div>
                        <span class="text-[#545B52] block text-[11px]">Sumber Data</span>
                        <span class="font-semibold text-[#0E6026]" id="detail-sumber">-</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 mt-4 border-t border-[#E1E4DE]">
                <button
                    type="button"
                    onclick="closeDetailSiswaModal()"
                    class="px-4 py-2 rounded-lg text-xs font-semibold bg-[#F3F5F2] text-[#1C2620] hover:bg-[#E1E4DE]"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL RESET PASSWORD ==================== -->
    <div id="modal-reset-password" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-[#E1E4DE]">
            <h3 class="text-base font-bold text-[#1C2620]">Reset Kata Sandi Akun Siswa</h3>
            <p class="text-xs text-[#545B52] mt-1" id="reset-modal-subtitle">
                Masukkan kata sandi baru untuk akun SSO siswa ini.
            </p>

            <form id="form-reset-password" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <x-input
                        type="password"
                        label="Kata Sandi Baru"
                        name="password"
                        required
                        minlength="6"
                        placeholder="Minimal 6 karakter"
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

    <!-- Javascript Handlers -->
    <script>
        function openTambahSiswaModal() {
            document.getElementById('modal-tambah-siswa').classList.remove('hidden');
        }

        function closeTambahSiswaModal() {
            document.getElementById('modal-tambah-siswa').classList.add('hidden');
        }

        function openEditSiswaModal(siswa) {
            const modal = document.getElementById('modal-edit-siswa');
            const form = document.getElementById('form-edit-siswa');

            form.action = `/admin/siakad/siswa/${siswa.id}`;
            document.getElementById('edit-nama').value = siswa.nama || '';
            document.getElementById('edit-nisn').value = siswa.nisn || '';
            document.getElementById('edit-nis').value = siswa.nis || '';
            document.getElementById('edit-email').value = (siswa.user && siswa.user.email) ? siswa.user.email : '';
            document.getElementById('edit-jenis_kelamin').value = siswa.jenis_kelamin || 'L';
            document.getElementById('edit-kelas_id').value = siswa.kelas_id || '';
            document.getElementById('edit-tahun_masuk').value = siswa.tahun_masuk || '';
            document.getElementById('edit-alamat').value = siswa.alamat || '';
            document.getElementById('edit-status_aktif').checked = Boolean(siswa.status_aktif);

            modal.classList.remove('hidden');
        }

        function closeEditSiswaModal() {
            document.getElementById('modal-edit-siswa').classList.add('hidden');
        }

        function openDetailSiswaModal(siswa) {
            const modal = document.getElementById('modal-detail-siswa');

            document.getElementById('detail-nama').innerText = siswa.nama || '-';
            document.getElementById('detail-nisn').innerText = siswa.nisn || '-';
            document.getElementById('detail-nis').innerText = siswa.nis || 'Belum Diberikan';
            document.getElementById('detail-jk').innerText = siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('detail-tahun').innerText = siswa.tahun_masuk || '-';

            if (siswa.kelas) {
                document.getElementById('detail-kelas').innerText = `${siswa.kelas.nama_kelas} (Tingkat ${siswa.kelas.tingkat}) - TP ${siswa.kelas.tahun_ajaran}`;
            } else {
                document.getElementById('detail-kelas').innerText = 'Belum Ditempatkan ke Kelas';
            }

            document.getElementById('detail-email').innerText = (siswa.user && siswa.user.email) ? siswa.user.email : '-';
            document.getElementById('detail-alamat').innerText = siswa.alamat || 'Alamat belum dilengkapi.';
            document.getElementById('detail-status').innerText = siswa.status_aktif ? 'Aktif' : 'Non-Aktif';
            document.getElementById('detail-sumber').innerText = siswa.sync_log ? 'Sinkronisasi Otomatis SPMB' : 'Entri Manual SIAKAD';

            modal.classList.remove('hidden');
        }

        function closeDetailSiswaModal() {
            document.getElementById('modal-detail-siswa').classList.add('hidden');
        }

        function openResetPasswordModal(siswaId, namaSiswa) {
            const modal = document.getElementById('modal-reset-password');
            const form = document.getElementById('form-reset-password');
            const subtitle = document.getElementById('reset-modal-subtitle');

            form.action = `/admin/siakad/siswa/${siswaId}/reset-password`;
            subtitle.innerText = `Mengubah kata sandi untuk akun SSO siswa: ${namaSiswa}`;
            modal.classList.remove('hidden');
        }

        function closeResetPasswordModal() {
            document.getElementById('modal-reset-password').classList.add('hidden');
        }
    </script>
</x-layouts.admin>
