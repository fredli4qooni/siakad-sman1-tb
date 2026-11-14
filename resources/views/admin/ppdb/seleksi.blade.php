<x-layouts.admin title="Hasil Seleksi SPMB" heading="Penetapan Hasil Seleksi & Kelulusan">
    <div class="space-y-6">

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
                            <th class="py-3 px-3">Verifikasi Fisik</th>
                            <th class="py-3 px-3">Status Saat Ini</th>
                            <th class="py-3 px-3 text-right">Penetapan Siswa</th>
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
                                <td class="py-3 px-3">
                                    @if($p->status_verifikasi_fisik === 'hadir_valid' || $p->isDiterima() || $p->isTerverifikasi())
                                        <span class="inline-flex items-center gap-1 text-[#0E6026] font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span> Hadir & Sah
                                        </span>
                                    @elseif($p->tgl_verifikasi_fisik)
                                        <span class="text-[#6B6200] font-medium">
                                            {{ $p->tgl_verifikasi_fisik->format('d/m/Y') }} ({{ ucfirst($p->status_verifikasi_fisik) }})
                                        </span>
                                    @else
                                        <span class="text-neutral-400 italic">Belum Dijadwalkan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if(in_array($status, ['DITERIMA', 'LULUS']))
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                                            Diterima
                                        </span>
                                    @elseif($status === 'TIDAK_LULUS')
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBEAEA] text-[#C81210]">
                                            Tidak Diterima
                                        </span>
                                        @if($hasil && $hasil->catatan)
                                            <div class="mt-1.5 p-1.5 rounded-lg bg-[#FBEAEA] border border-[#C81210]/20 text-[11px] text-[#C81210] max-w-xs leading-snug">
                                                <span class="font-bold">Alasan:</span> {{ $hasil->catatan }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="px-2.5 py-1 rounded text-xs font-semibold bg-[#FBF9D6] text-[#6B6200]">
                                            Menunggu Penetapan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <div class="inline-flex items-center gap-2 justify-end">
                                        <button
                                            type="button"
                                            onclick="openKeputusanModal('{{ $p->id }}', '{{ addslashes($p->nama_lengkap) }}', '{{ $p->no_pendaftaran }}', '{{ $status }}', '{{ addslashes($hasil?->catatan ?? '') }}')"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold {{ in_array($status, ['DITERIMA', 'LULUS']) ? 'bg-[#E7F4EA] text-[#0E6026] hover:bg-[#d8eedc] border border-[#039834]/30' : ($status === 'TIDAK_LULUS' ? 'bg-[#FBEAEA] text-[#C81210] hover:bg-[#f7d8d8] border border-[#C81210]/30' : 'bg-[#0E6026] text-white hover:bg-[#0A4C1E]') }} transition-colors"
                                            title="Tetapkan Kelulusan atau Berikan Alasan Penolakan"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            {{ $status === 'MENUNGGU' ? 'Tetapkan Hasil' : 'Ubah Keputusan' }}
                                        </button>

                                        <form action="{{ route('admin.ppdb.pendaftar.hapus', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus calon siswa {{ addslashes($p->nama_lengkap) }} ({{ $p->no_pendaftaran }})? Data dan berkas akan dihapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 text-xs font-semibold text-[#C81210] bg-[#FBEAEA] hover:bg-[#FBEAEA]/80 border border-[#C81210]/30 rounded-lg transition-colors" title="Hapus Calon Siswa">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada calon peserta didik yang berstatus siap penetapan.
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

    <!-- Modal Penetapan Hasil Seleksi & Alasan Penolakan -->
    <div id="keputusan-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-[#E1E4DE] m-4">
            <div class="flex items-start justify-between pb-3 border-b border-[#E1E4DE]">
                <div>
                    <h3 class="text-base font-bold text-[#1C2620]">Penetapan Hasil Seleksi & Kelulusan</h3>
                    <p class="text-xs text-[#545B52] mt-0.5">
                        Nama: <strong id="modal-student-name" class="text-[#1C2620]">-</strong> &bull; No. Reg: <span id="modal-student-no" class="font-mono font-medium">-</span>
                    </p>
                </div>
                <button type="button" onclick="closeKeputusanModal()" class="text-[#545B52] hover:text-[#1C2620] text-xl font-bold p-1">
                    &times;
                </button>
            </div>

            <form id="keputusan-form" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">
                        Status Kelulusan / Penerimaan <span class="text-[#C81210]">*</span>
                    </label>
                    <select
                        name="status"
                        id="modal-status-select"
                        onchange="handleStatusChange(this.value)"
                        class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]"
                        required
                    >
                        <option value="DITERIMA">DITERIMA (Siswa Baru Resmi)</option>
                        <option value="TIDAK_LULUS">TIDAK DITERIMA (Ditolak)</option>
                        <option value="MENUNGGU">MENUNGGU (Dalam Proses Sidang)</option>
                    </select>
                </div>

                <!-- Bagian Alasan / Catatan Penolakan -->
                <div id="modal-catatan-container" class="space-y-2">
                    <label class="block text-[13px] font-medium text-[#1C2620]" id="modal-catatan-label">
                        Alasan Penolakan / Catatan Panitia
                    </label>

                    <div id="quick-reasons-box" class="space-y-1.5">
                        <span class="text-[11px] text-[#545B52] block">Pilih template alasan cepat (klik untuk mengisi):</span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" onclick="setQuickReason('Berkas fisik asli tidak sesuai atau tidak lengkap saat verifikasi faktual di sekolah.')" class="px-2 py-1 bg-[#F3F5F2] hover:bg-[#E1E4DE] rounded text-[11px] text-[#1C2620] transition-colors border border-[#E1E4DE]">
                                Berkas fisik tidak lengkap / tidak sesuai
                            </button>
                            <button type="button" onclick="setQuickReason('Tidak hadir pada jadwal validasi berkas fisik yang telah ditentukan di sekolah.')" class="px-2 py-1 bg-[#F3F5F2] hover:bg-[#E1E4DE] rounded text-[11px] text-[#1C2620] transition-colors border border-[#E1E4DE]">
                                Tidak hadir jadwal validasi fisik
                            </button>
                            <button type="button" onclick="setQuickReason('Kuota daya tampung penerimaan murid baru untuk tahun ajaran ini telah terpenuhi.')" class="px-2 py-1 bg-[#F3F5F2] hover:bg-[#E1E4DE] rounded text-[11px] text-[#1C2620] transition-colors border border-[#E1E4DE]">
                                Kuota daya tampung terpenuhi
                            </button>
                            <button type="button" onclick="setQuickReason('Nilai rapor / hasil verifikasi akademik belum memenuhi ambang batas seleksi.')" class="px-2 py-1 bg-[#F3F5F2] hover:bg-[#E1E4DE] rounded text-[11px] text-[#1C2620] transition-colors border border-[#E1E4DE]">
                                Nilai belum memenuhi kriteria
                            </button>
                        </div>
                    </div>

                    <textarea
                        name="catatan"
                        id="modal-catatan-textarea"
                        rows="3"
                        class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-xs text-[#1C2620] placeholder-[#9FA29E] focus:outline-none focus:border-[#039834] focus:ring-1 focus:ring-[#039834]"
                        placeholder="Tulis alasan penolakan secara jelas agar calon siswa mengetahui alasannya..."
                    ></textarea>
                    <p class="text-[11px] text-[#545B52]" id="modal-catatan-hint">
                        Catatan ini akan langsung ditampilkan kepada calon siswa di halaman dasbor dan pengumuman.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-[#E1E4DE]">
                    <button
                        type="button"
                        onclick="closeKeputusanModal()"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-[#545B52] hover:bg-[#F3F5F2]"
                    >
                        Batal
                    </button>
                    <x-button type="submit" variant="primary" class="text-xs">
                        Simpan Keputusan & Alasan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openKeputusanModal(pendaftarId, namaSiswa, noPendaftaran, status, catatan) {
            const modal = document.getElementById('keputusan-modal');
            const form = document.getElementById('keputusan-form');
            const studentName = document.getElementById('modal-student-name');
            const studentNo = document.getElementById('modal-student-no');
            const statusSelect = document.getElementById('modal-status-select');
            const textarea = document.getElementById('modal-catatan-textarea');

            form.action = `/admin/ppdb/seleksi/${pendaftarId}`;
            studentName.innerText = namaSiswa;
            studentNo.innerText = noPendaftaran;

            const currentStatus = (status === 'LULUS' || status === 'DITERIMA') ? 'DITERIMA' : (status === 'TIDAK_LULUS' ? 'TIDAK_LULUS' : 'MENUNGGU');
            statusSelect.value = currentStatus;
            textarea.value = catatan || '';

            handleStatusChange(currentStatus);
            modal.classList.remove('hidden');
        }

        function closeKeputusanModal() {
            const modal = document.getElementById('keputusan-modal');
            modal.classList.add('hidden');
        }

        function setQuickReason(reason) {
            const textarea = document.getElementById('modal-catatan-textarea');
            textarea.value = reason;
            textarea.focus();
        }

        function handleStatusChange(status) {
            const label = document.getElementById('modal-catatan-label');
            const quickBox = document.getElementById('quick-reasons-box');
            const textarea = document.getElementById('modal-catatan-textarea');
            const hint = document.getElementById('modal-catatan-hint');

            if (status === 'TIDAK_LULUS') {
                label.innerHTML = '<span class="text-[#C81210] font-bold">Alasan Penolakan untuk Calon Siswa:</span>';
                quickBox.classList.remove('hidden');
                textarea.placeholder = "Jelaskan alasan mengapa calon siswa belum dapat diterima...";
                hint.innerText = "Alasan ini akan tampil di dasbor calon siswa agar mereka memahami mengapa tidak lolos.";
                if (!textarea.value || textarea.value.includes('Selamat!') || textarea.value.includes('Penetapan')) {
                    textarea.value = 'Berkas fisik asli tidak sesuai atau tidak lengkap saat verifikasi faktual di sekolah.';
                }
            } else if (status === 'DITERIMA') {
                label.innerHTML = '<span class="text-[#0E6026] font-bold">Catatan Penerimaan Siswa (Opsional):</span>';
                quickBox.classList.add('hidden');
                textarea.placeholder = "Catatan atau instruksi tambahan untuk siswa baru...";
                hint.innerText = "Catatan ini akan tampil di dasbor pendaftaran ulang siswa yang diterima.";
                if (!textarea.value || textarea.value.includes('tidak') || textarea.value.includes('Penetapan')) {
                    textarea.value = 'Selamat! Anda resmi diterima sebagai siswa baru SMAN 1 Terbanggi Besar.';
                }
            } else {
                label.innerText = 'Catatan Status (Opsional):';
                quickBox.classList.add('hidden');
                textarea.placeholder = "Catatan internal atau keterangan status...";
                hint.innerText = "Catatan ini disimpan dalam sistem.";
            }
        }
    </script>
</x-layouts.admin>
