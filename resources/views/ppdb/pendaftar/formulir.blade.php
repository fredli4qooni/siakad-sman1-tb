<x-layouts.pendaftar title="Formulir Pendaftaran — SMAN 1 TB">
    <div class="max-w-4xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1C2620]">Formulir Pendaftaran Siswa Baru</h1>
            <p class="text-xs text-[#545B52] mt-1">Isi data pokok siswa dan orang tua/wali dengan teliti sesuai dokumen resmi kependudukan.</p>
        </div>

        <form action="{{ route('pendaftar.formulir') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Bagian 1: Data Diri Pendaftar -->
            <x-card title="A. Data Calon Peserta Didik" subtitle="Informasi identitas pribadi calon siswa">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="NISN" name="nisn" required class="tabular-nums" placeholder="10 digit NISN" />
                    <x-input label="NIK (Nomor Induk Kependudukan)" name="nik" required class="tabular-nums" placeholder="16 digit NIK KK" />
                    <x-input label="Nama Lengkap" name="nama_lengkap" required placeholder="Sesuai Akta Kelahiran" />
                    <div>
                        <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Jenis Kelamin <span class="text-[#C81210]">*</span></label>
                        <select name="jenis_kelamin" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <x-input label="Tempat Lahir" name="tempat_lahir" required placeholder="Kota / Kabupaten" />
                    <x-input label="Tanggal Lahir" name="tanggal_lahir" type="date" required />
                    <x-input label="Asal Sekolah (SMP/MTs)" name="asal_sekolah" required placeholder="Nama SMP/MTs asal" />
                    <x-input label="Nomor WhatsApp/HP Siswa" name="no_hp" required class="tabular-nums" placeholder="08xxxxxxxxxx" />
                    <div class="sm:col-span-2">
                        <x-input label="Alamat Tempat Tinggal" name="alamat" required placeholder="Alamat lengkap RT/RW, Desa/Kelurahan, Kecamatan" />
                    </div>
                </div>
            </x-card>

            <!-- Bagian 2: Data Orang Tua / Wali -->
            <x-card title="B. Data Orang Tua / Wali" subtitle="Data orang tua untuk sinkronisasi catatan akademik">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Nama Ayah Kandung" name="nama_ayah" required placeholder="Nama lengkap ayah" />
                    <x-input label="Pekerjaan Ayah" name="pekerjaan_ayah" required placeholder="PNS / Wiraswasta / Petani / dll." />
                    <x-input label="Nama Ibu Kandung" name="nama_ibu" required placeholder="Nama lengkap ibu" />
                    <x-input label="Pekerjaan Ibu" name="pekerjaan_ibu" required placeholder="IRT / Guru / Pedagang / dll." />
                    <div class="sm:col-span-2">
                        <x-input label="Nomor Telepon/HP Orang Tua" name="no_hp_ortu" required class="tabular-nums" placeholder="08xxxxxxxxxx" />
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end gap-3 pt-2">
                <x-button type="submit" variant="primary">
                    Simpan Formulir Pendaftaran
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.pendaftar>
