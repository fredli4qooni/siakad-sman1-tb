<x-layouts.pendaftar title="Formulir Pendaftaran — SMAN 1 TB">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-[#1C2620]">Formulir Pendaftaran Siswa Baru</h1>
                <p class="text-xs text-[#545B52] mt-1">Lengkapi data pokok siswa dan orang tua/wali dengan teliti sesuai dokumen resmi kependudukan.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-[#545B52]">No. Registrasi:</span>
                <span class="px-2.5 py-1 rounded bg-[#E7F4EA] text-[#0E6026] font-mono font-bold text-xs">
                    {{ $pendaftar->no_pendaftaran }}
                </span>
            </div>
        </div>

        @if(in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus']))
            <x-alert type="warning" title="Formulir Terkunci">
                Data formulir Anda telah berstatus <strong>{{ strtoupper($pendaftar->status_pendaftaran) }}</strong> dan tidak dapat diubah lagi secara mandiri. Hubungi panitia PPDB jika terdapat perbaikan data mendesak.
            </x-alert>
        @endif

        <form action="{{ route('pendaftar.formulir.simpan') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Bagian 1: Data Diri Pendaftar -->
            <x-card title="A. Data Calon Peserta Didik" subtitle="Informasi identitas pribadi calon siswa">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input
                        label="NISN (Nomor Induk Siswa Nasional)"
                        name="nisn"
                        required
                        class="tabular-nums"
                        placeholder="10 digit NISN"
                        :value="old('nisn', $pendaftar->nisn === '0000000000' ? '' : $pendaftar->nisn)"
                    />

                    <x-input
                        label="NIK (Nomor Induk Kependudukan)"
                        name="nik"
                        required
                        class="tabular-nums"
                        placeholder="16 digit NIK sesuai Kartu Keluarga"
                        :value="old('nik', $pendaftar->nik)"
                    />

                    <x-input
                        label="Nama Lengkap"
                        name="nama_lengkap"
                        required
                        placeholder="Sesuai Akta Kelahiran"
                        :value="old('nama_lengkap', $pendaftar->nama_lengkap)"
                    />

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Jenis Kelamin <span class="text-[#C81210]">*</span>
                        </label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                            <option value="L" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-xs text-[#C81210] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-input
                        label="Tempat Lahir"
                        name="tempat_lahir"
                        required
                        placeholder="Kota / Kabupaten tempat lahir"
                        :value="old('tempat_lahir', $pendaftar->tempat_lahir === '-' ? '' : $pendaftar->tempat_lahir)"
                    />

                    <x-input
                        label="Tanggal Lahir"
                        name="tanggal_lahir"
                        type="date"
                        required
                        :value="old('tanggal_lahir', $pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('Y-m-d') : '')"
                    />

                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                            Agama <span class="text-[#C81210]">*</span>
                        </label>
                        <select name="agama" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                            @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agm)
                                <option value="{{ $agm }}" {{ old('agama', $pendaftar->agama) === $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-input
                        label="Asal Sekolah (SMP/MTs)"
                        name="asal_sekolah"
                        required
                        placeholder="Nama SMP/MTs Asal"
                        :value="old('asal_sekolah', $pendaftar->asal_sekolah === '-' ? '' : $pendaftar->asal_sekolah)"
                    />

                    <x-input
                        label="Nomor WhatsApp/HP Siswa"
                        name="no_hp"
                        required
                        class="tabular-nums"
                        placeholder="Contoh: 081234567890"
                        :value="old('no_hp', $pendaftar->no_hp === '-' ? '' : $pendaftar->no_hp)"
                    />

                    <div class="sm:col-span-2">
                        <x-input
                            label="Alamat Tempat Tinggal"
                            name="alamat"
                            required
                            placeholder="Alamat lengkap RT/RW, Dusun/Lingkungan, Desa/Kelurahan, Kecamatan, Kab/Kota"
                            :value="old('alamat', $pendaftar->alamat === '-' ? '' : $pendaftar->alamat)"
                        />
                    </div>
                </div>
            </x-card>

            <!-- Bagian 2: Data Orang Tua / Wali -->
            <x-card title="B. Data Orang Tua / Wali" subtitle="Data orang tua untuk sinkronisasi catatan akademik">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input
                        label="Nama Ayah Kandung"
                        name="nama_ayah"
                        required
                        placeholder="Nama lengkap ayah"
                        :value="old('nama_ayah', $orangTua->nama_ayah)"
                    />

                    <x-input
                        label="Pekerjaan Ayah"
                        name="pekerjaan_ayah"
                        required
                        placeholder="Contoh: PNS / Wiraswasta / Petani"
                        :value="old('pekerjaan_ayah', $orangTua->pekerjaan_ayah)"
                    />

                    <x-input
                        label="Nama Ibu Kandung"
                        name="nama_ibu"
                        required
                        placeholder="Nama lengkap ibu"
                        :value="old('nama_ibu', $orangTua->nama_ibu)"
                    />

                    <x-input
                        label="Pekerjaan Ibu"
                        name="pekerjaan_ibu"
                        required
                        placeholder="Contoh: Ibu Rumah Tangga / Guru / Pedagang"
                        :value="old('pekerjaan_ibu', $orangTua->pekerjaan_ibu)"
                    />

                    <x-input
                        label="Nama Wali (Opsional)"
                        name="nama_wali"
                        placeholder="Kosongkan jika tinggal bersama orang tua"
                        :value="old('nama_wali', $orangTua->nama_wali)"
                    />

                    <x-input
                        label="Pekerjaan Wali (Opsional)"
                        name="pekerjaan_wali"
                        placeholder="Pekerjaan wali jika ada"
                        :value="old('pekerjaan_wali', $orangTua->pekerjaan_wali)"
                    />

                    <div class="sm:col-span-2">
                        <x-input
                            label="Nomor Telepon/HP Orang Tua/Wali"
                            name="no_hp_ortu"
                            required
                            class="tabular-nums"
                            placeholder="Contoh: 081298765432"
                            :value="old('no_hp_ortu', $orangTua->no_hp_ortu)"
                        />
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-between pt-2">
                <x-button as="a" href="{{ route('pendaftar.dashboard') }}" variant="ghost">
                    Kembali ke Dasbor
                </x-button>

                @if(!in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus']))
                    <x-button type="submit" variant="primary">
                        Simpan dan Lanjutkan ke Unggah Berkas
                    </x-button>
                @endif
            </div>
        </form>
    </div>
</x-layouts.pendaftar>
