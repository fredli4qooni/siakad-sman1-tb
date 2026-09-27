<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sync_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('waktu_sinkron')->useCurrent();
            $table->string('status', 30)->default('BERHASIL'); // BERHASIL, GAGAL, PENDING
            $table->json('detail_payload')->nullable(); // snapshot data yang disinkronkan
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_log');
    }
};
