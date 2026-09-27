<x-layouts.admin title="Sync Logs" heading="Audit Log Sinkronisasi PPDB → SIAKAD">
    <div class="space-y-6">
        @if(session('success'))
            <x-alert type="success" title="Berhasil">
                {{ session('success') }}
            </x-alert>
        @endif

        @if(session('warning'))
            <x-alert type="warning" title="Peringatan">
                {{ session('warning') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" title="Gagal">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- 3 Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#545B52]">Total Transaksi Sinkronisasi</div>
                <div class="text-2xl font-bold text-[#1C2620] tabular-nums mt-1">{{ number_format($totalSync, 0, ',', '.') }}</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#0E6026]">Berhasil Termigrasi ke SIAKAD</div>
                <div class="text-2xl font-bold text-[#0E6026] tabular-nums mt-1">{{ number_format($berhasilCount, 0, ',', '.') }}</div>
            </div>
            <div class="p-4 rounded-xl border border-[#E1E4DE] bg-white">
                <div class="text-xs text-[#C81210]">Gagal / Butuh Tindakan</div>
                <div class="text-2xl font-bold text-[#C81210] tabular-nums mt-1">{{ number_format($gagalCount, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Tombol Aksi Batch Sync & Penjelasan -->
        <div class="p-5 rounded-2xl bg-[#E7F4EA] border border-[#039834]/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-sm text-[#0E6026]">Sinkronisasi Otomatis Terhubung (SSO OIDC)</h3>
                <p class="text-xs text-[#1C2620] mt-0.5">
                    Data siswa LULUS dipindahkan secara instan ke tabel <code>siswa</code> SIAKAD, peran akun dinaikkan menjadi <code>siswa</code>, dan NIS otomatis diterbitkan.
                </p>
            </div>

            <form action="{{ route('admin.sync.batch') }}" method="POST" onsubmit="return confirm('Jalankan proses sinkronisasi massal untuk seluruh pendaftar berstatus LULUS?')">
                @csrf
                <x-button type="submit" variant="primary" class="whitespace-nowrap text-xs">
                    Jalankan Batch Sync Sekarang
                </x-button>
            </form>
        </div>

        <!-- Filter Pencarian -->
        <x-card>
            <form action="{{ route('admin.sync.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2">
                    <x-input
                        label="Pencarian Log"
                        name="keyword"
                        placeholder="Cari Nama Siswa, NISN, atau No. Pendaftaran"
                        :value="request('keyword')"
                    />
                </div>

                <div>
                    <label class="block text-[13px] font-medium text-[#1C2620] mb-1.5">Status Sinkronisasi</label>
                    <select name="status" class="w-full rounded-lg border border-[#C9CDC3] px-3.5 py-2 text-sm text-[#1C2620] bg-white focus:outline-none focus:border-[#039834]">
                        <option value="semua">Semua Status</option>
                        <option value="BERHASIL" {{ request('status') === 'BERHASIL' ? 'selected' : '' }}>BERHASIL</option>
                        <option value="GAGAL" {{ request('status') === 'GAGAL' ? 'selected' : '' }}>GAGAL</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter Log
                    </x-button>
                    @if(request()->hasAny(['keyword', 'status']))
                        <a href="{{ route('admin.sync.index') }}" class="p-2 text-xs text-[#545B52] hover:text-[#1C2620]">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Tabel Log Transaksi -->
        <x-card title="Riwayat Audit Trail Sinkronisasi">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#1C2620]">
                    <thead>
                        <tr class="border-b border-[#C9CDC3] text-xs font-semibold text-[#545B52]">
                            <th class="py-3 px-3">Waktu</th>
                            <th class="py-3 px-3">Calon Siswa (PPDB)</th>
                            <th class="py-3 px-3">Siswa SIAKAD</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Snapshot Payload / Error</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E1E4DE] text-xs">
                        @forelse($logs as $log)
                            <tr class="hover:bg-[#F3F5F2] transition-colors">
                                <td class="py-3 px-3 tabular-nums text-[#545B52] whitespace-nowrap">
                                    {{ $log->waktu_sinkron ? $log->waktu_sinkron->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-[#1C2620]">
                                        {{ $log->pendaftar->nama_lengkap ?? 'Pendaftar #' . $log->pendaftar_id }}
                                    </div>
                                    <div class="text-[11px] font-mono text-[#545B52]">
                                        {{ $log->pendaftar->no_pendaftaran ?? '-' }} &bull; NISN: {{ $log->pendaftar->nisn ?? '-' }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    @if($log->siswa)
                                        <div class="font-semibold text-[#0E6026]">{{ $log->siswa->nama }}</div>
                                        <div class="text-[11px] font-mono text-[#545B52]">NIS: {{ $log->siswa->nis }}</div>
                                    @else
                                        <span class="text-[#C81210] italic">Belum dibuat</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    @if($log->status === 'BERHASIL')
                                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-[#E7F4EA] text-[#0E6026]">
                                            ✓ BERHASIL
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded text-[11px] font-bold bg-[#FBEAEA] text-[#C81210]">
                                            ✕ GAGAL
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 max-w-xs">
                                    @if($log->status === 'BERHASIL')
                                        <div class="text-[11px] text-[#545B52] truncate font-mono">
                                            NISN: {{ $log->detail_payload['nisn'] ?? '-' }} &bull; NIS: {{ $log->detail_payload['nis'] ?? '-' }}
                                        </div>
                                    @else
                                        <div class="text-[11px] text-[#C81210] font-mono">
                                            {{ $log->error_message ?? 'Kesalahan tidak diketahui' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right">
                                    @if($log->status === 'GAGAL')
                                        <form action="{{ route('admin.sync.retry', $log->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs text-[#0E6026] font-semibold hover:underline">
                                                Coba Ulang
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-[#545B52]">Tersinkron</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-[#545B52]">
                                    Belum ada transaksi log sinkronisasi PPDB ke SIAKAD.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="pt-4 border-t border-[#E1E4DE]">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
