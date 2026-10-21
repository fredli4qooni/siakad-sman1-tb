<?php

namespace App\Listeners;

use App\Events\PendaftarLulusEvent;
use App\Models\SyncLog;
use App\Services\SyncService;

class SyncPendaftarLulusListener
{
    public function __construct(protected SyncService $syncService)
    {
    }

    public function handle(PendaftarLulusEvent $event): void
    {
        // Hindari sinkronisasi berulang jika pendaftar sudah berhasil disinkronkan
        $alreadySynced = SyncLog::where('pendaftar_id', $event->pendaftar->id)
            ->where('status', 'BERHASIL')
            ->exists();

        if ($alreadySynced) {
            return;
        }

        $this->syncService->syncPendaftar($event->pendaftar);
    }
}
