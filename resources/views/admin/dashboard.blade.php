<x-layouts.admin title="Dashboard Utama" heading="Ringkasan Sistem SPMB & SIAKAD">
    <div class="space-y-6">

        <!-- 4 Metrik Kartu Utama (Database Real-Time) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Pendaftar SPMB -->
            <x-card padding="p-4" class="shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#545B52]">Total Pendaftar SPMB</span>
                    @if($periodeAktif)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                            Aktif
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F3F5F2] text-[#545B52]">
                            Tutup
                        </span>
                    @endif
                </div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                    {{ number_format($totalPendaftar, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-[#0E6026] mt-0.5 font-medium truncate">
                    {{ $periodeAktif ? 'TP ' . $periodeAktif->tahun_ajaran . ' (' . $periodeAktif->nama_gelombang . ')' : 'Semua Periode Pendaftaran' }}
                </div>
            </x-card>

            <!-- 2. Berkas Terverifikasi -->
            <x-card padding="p-4" class="shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#545B52]">Berkas Terverifikasi</span>
                    <span class="w-2 h-2 rounded-full bg-[#039834]"></span>
                </div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">
                    {{ number_format($terverifikasiCount, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-[#545B52] mt-0.5">
                    <span class="font-semibold text-[#6B6200]">{{ number_format($menungguVerifikasiCount, 0, ',', '.') }}</span> Menunggu Tindakan
                </div>
            </x-card>

            <!-- 3. Dinyatakan LULUS -->
            <x-card padding="p-4" class="shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#545B52]">Dinyatakan LULUS</span>
                    @if($kuotaTarget && $kuotaTarget > 0)
                        <span class="text-[10px] font-semibold text-[#0E6026] bg-[#E7F4EA] px-1.5 py-0.5 rounded">
                            {{ round(($lulusCount / $kuotaTarget) * 100, 1) }}% Kuota
                        </span>
                    @endif
                </div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">
                    {{ number_format($lulusCount, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-[#545B52] mt-0.5">
                    {{ $kuotaTarget ? 'Dari Target ' . number_format($kuotaTarget, 0, ',', '.') . ' Kuota' : 'Target Kuota Belum Ditentukan' }}
                </div>
            </x-card>

            <!-- 4. Tersinkron ke SIAKAD -->
            <x-card padding="p-4" class="shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-[#545B52]">Tersinkron ke SIAKAD</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#E7F4EA] text-[#0E6026]">
                        {{ $persenSync }}% Sukses
                    </span>
                </div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">
                    {{ number_format($syncBerhasilCount, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-[#0E6026] mt-0.5">
                    @if($syncGagalCount > 0)
                        <span class="text-[#C81210] font-semibold">{{ $syncGagalCount }} Gagal Sinkron</span>
                    @else
                        Otomatis Dibuat di SIAKAD
                    @endif
                </div>
            </x-card>
        </div>

        <!-- Bagian Monitoring Real-Time SPMB dan Sinkronisasi -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- 1. Aktivitas Pendaftaran SPMB Terbaru -->
            <x-card title="Aktivitas Pendaftaran Terbaru" subtitle="Calon murid baru yang mendaftar ke sistem SPMB">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-2.5 px-3">No. Daftar</th>
                                <th class="py-2.5 px-3">Nama Siswa</th>
                                <th class="py-2.5 px-3">Asal Sekolah</th>
                                <th class="py-2.5 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            @forelse($pendaftarTerbaru as $p)
                                <tr class="hover:bg-[#F3F5F2] transition-colors">
                                    <td class="py-2.5 px-3 font-mono font-medium text-[#0E6026]">
                                        <a href="{{ route('admin.ppdb.verifikasi.show', $p->id) }}" class="hover:underline">
                                            {{ $p->no_pendaftaran }}
                                        </a>
                                    </td>
                                    <td class="py-2.5 px-3 font-medium text-[#1C2620]">
                                        {{ $p->nama_lengkap }}
                                        <div class="text-[10px] text-[#545B52] font-mono">NISN: {{ $p->nisn }}</div>
                                    </td>
                                    <td class="py-2.5 px-3 text-[#545B52] truncate max-w-[140px]" title="{{ $p->asal_sekolah }}">
                                        {{ $p->asal_sekolah ?: '-' }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($p->isDiterima())
                                            <x-badge status="diterima">Diterima</x-badge>
                                        @elseif($p->isTidakLulus())
                                            <x-badge status="tidak_lulus">Tidak Lulus</x-badge>
                                        @elseif($p->isDijadwalkanFisik() && !$p->isTerverifikasi())
                                            <x-badge status="menunggu">Jadwal Fisik</x-badge>
                                        @elseif($p->isTerverifikasi())
                                            <x-badge status="terverifikasi">Terverifikasi</x-badge>
                                        @elseif($p->isMenungguVerifikasi())
                                            <x-badge status="menunggu">Menunggu</x-badge>
                                        @else
                                            <x-badge status="draft">Draft</x-badge>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-xs text-[#545B52]">
                                        Belum ada aktivitas pendaftaran calon murid baru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 mt-2 border-t border-[#E1E4DE] flex items-center justify-between text-xs">
                    <span class="text-[#545B52]">Menampilkan {{ $pendaftarTerbaru->count() }} pendaftar terbaru</span>
                    <a href="{{ route('admin.ppdb.pendaftar.index') }}" class="font-semibold text-[#0E6026] hover:underline">
                        Lihat Semua Pendaftar ({{ $totalPendaftar }}) &rarr;
                    </a>
                </div>
            </x-card>

            <!-- 2. Log Sinkronisasi SPMB -> SIAKAD -->
            <x-card title="Log Sinkronisasi SPMB → SIAKAD" subtitle="Audit otomatisasi data saat penetapan kelulusan pendaftar">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#1C2620]">
                        <thead>
                            <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                                <th class="py-2.5 px-3">Waktu</th>
                                <th class="py-2.5 px-3">Nama Siswa</th>
                                <th class="py-2.5 px-3">Status Sinkron</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E1E4DE] text-xs">
                            @forelse($logSinkronTerbaru as $log)
                                <tr class="hover:bg-[#F3F5F2] transition-colors">
                                    <td class="py-2.5 px-3 text-[#545B52] whitespace-nowrap">
                                        <div class="font-medium text-[#1C2620]">
                                            {{ $log->waktu_sinkron ? $log->waktu_sinkron->translatedFormat('d M Y, H:i') : $log->created_at->translatedFormat('d M Y, H:i') }}
                                        </div>
                                        <div class="text-[10px] text-[#9FA29E]">
                                            {{ ($log->waktu_sinkron ?? $log->created_at)->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 font-medium text-[#1C2620]">
                                        {{ $log->pendaftar?->nama_lengkap ?? $log->siswa?->nama ?? 'Siswa #' . $log->siswa_id }}
                                        <div class="text-[10px] text-[#545B52] font-mono">
                                            NISN: {{ $log->pendaftar?->nisn ?? $log->siswa?->nisn ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3">
                                        @if($log->status === 'BERHASIL')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#E7F4EA] text-[#0E6026] border border-[#039834]/20">
                                                Berhasil (ID #{{ $log->siswa_id }})
                                            </span>
                                        @elseif($log->status === 'GAGAL')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#FBEAEA] text-[#C81210] border border-[#C81210]/20" title="{{ $log->error_message }}">
                                                Gagal
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#FBF9D6] text-[#6B6200]">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-xs text-[#545B52]">
                                        Belum ada riwayat log sinkronisasi SPMB ke SIAKAD.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-4 mt-2 border-t border-[#E1E4DE] flex items-center justify-between text-xs">
                    <span class="text-[#545B52]">Total log audit: {{ $totalSync }} entri</span>
                    <a href="{{ route('admin.sync.index') }}" class="font-semibold text-[#0E6026] hover:underline">
                        Buka Audit Trail Lengkap &rarr;
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.admin>
