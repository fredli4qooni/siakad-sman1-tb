<?php

namespace App\Listeners;

use App\Events\PendaftarLulusEvent;
use App\Services\SyncService;

class SyncPendaftarLulusListener
{
    public function __construct(protected SyncService $syncService)
    {
    }

    public function handle(PendaftarLulusEvent $event): void
    {
        $this->syncService->syncPendaftar($event->pendaftar);
    }
}
