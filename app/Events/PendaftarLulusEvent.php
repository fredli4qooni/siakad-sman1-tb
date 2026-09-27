<?php

namespace App\Events;

use App\Models\Pendaftar;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendaftarLulusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Pendaftar $pendaftar)
    {
    }
}
