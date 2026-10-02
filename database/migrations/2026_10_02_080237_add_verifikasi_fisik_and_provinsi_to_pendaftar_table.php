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
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->string('no_peserta_ppdb_provinsi', 50)->nullable()->after('no_pendaftaran');
            $table->date('tgl_verifikasi_fisik')->nullable()->after('catatan_verifikasi');
            $table->string('sesi_verifikasi_fisik', 100)->nullable()->after('tgl_verifikasi_fisik');
            $table->string('lokasi_verifikasi_fisik', 150)->nullable()->default('Ruang Panitia PPDB SMAN 1 Terbanggi Besar')->after('sesi_verifikasi_fisik');
            $table->text('catatan_verifikasi_fisik')->nullable()->after('lokasi_verifikasi_fisik');
            $table->string('status_verifikasi_fisik', 30)->default('belum_dijadwalkan')->after('catatan_verifikasi_fisik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'no_peserta_ppdb_provinsi',
                'tgl_verifikasi_fisik',
                'sesi_verifikasi_fisik',
                'lokasi_verifikasi_fisik',
                'catatan_verifikasi_fisik',
                'status_verifikasi_fisik',
            ]);
        });
    }
};
