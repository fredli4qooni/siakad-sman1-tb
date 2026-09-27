<?php

namespace App\Http\Controllers\Siakad;

use App\Http\Controllers\Controller;
use App\Models\SyncLog;
use App\Services\SyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SyncLogController extends Controller
{
    /**
     * Menampilkan audit trail riwayat sinkronisasi PPDB -> SIAKAD.
     */
    public function index(Request $request): View
    {
        $query = SyncLog::with(['pendaftar', 'siswa', 'user']);

        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $query->where(function ($q) use ($keyword) {
                $q->whereHas('pendaftar', fn($p) => $p->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('nisn', 'like', "%{$keyword}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$keyword}%"))
                    ->orWhereHas('siswa', fn($s) => $s->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('nis', 'like', "%{$keyword}%"));
            });
        }

        $logs = $query->orderByDesc('waktu_sinkron')->paginate(20)->withQueryString();

        $totalSync = SyncLog::count();
        $berhasilCount = SyncLog::where('status', 'BERHASIL')->count();
        $gagalCount = SyncLog::where('status', 'GAGAL')->count();

        return view('admin.sync.index', compact(
            'logs',
            'totalSync',
            'berhasilCount',
            'gagalCount'
        ));
    }

    /**
     * Menjalankan proses Batch Sync untuk seluruh calon siswa yang telah dinyatakan LULUS.
     */
    public function batchSync(Request $request, SyncService $syncService): RedirectResponse
    {
        $periodeId = $request->input('periode_id');
        $result = $syncService->syncBatch($periodeId ? (int) $periodeId : null);

        $msg = "Batch Sync selesai: {$result['berhasil']} siswa berhasil disinkronkan dari total {$result['total']} pendaftar lulus.";
        if ($result['gagal'] > 0) {
            $msg .= " ({$result['gagal']} gagal diproses).";
            return back()->with('warning', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Mencoba ulang (Retry) proses sinkronisasi untuk log yang gagal.
     */
    public function retry(SyncLog $log, SyncService $syncService): RedirectResponse
    {
        $result = $syncService->retrySync($log);

        if ($result['success']) {
            return back()->with('success', 'Sinkronisasi ulang berhasil: ' . $result['message']);
        }

        return back()->with('error', 'Gagal menyinkronkan ulang: ' . $result['message']);
    }
}
