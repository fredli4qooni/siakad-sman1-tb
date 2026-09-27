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
        // 1. Periode PPDB
        Schema::create('periode_ppdb', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 20); // Contoh: 2026/2027
            $table->string('nama_gelombang', 100)->default('Gelombang 1');
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->unsignedInteger('kuota')->default(540);
            $table->boolean('is_aktif')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 2. Data Calon Siswa (Pendaftar)
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periode_ppdb')->onDelete('restrict');
            $table->string('no_pendaftaran', 50)->unique();
            $table->string('nisn', 20)->index();
            $table->string('nik', 20)->nullable();
            $table->string('nama_lengkap', 255);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->string('agama', 50)->default('Islam');
            $table->string('asal_sekolah', 150);
            $table->text('alamat');
            $table->string('no_hp', 30);
            $table->string('status_pendaftaran', 30)->default('draft'); // draft, terkirim, terverifikasi, ditolak
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
        });

        // 3. Data Orang Tua / Wali
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->string('nama_ayah', 150);
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('penghasilan_ayah', 50)->nullable();
            $table->string('nama_ibu', 150);
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('penghasilan_ibu', 50)->nullable();
            $table->string('nama_wali', 150)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->string('no_hp_ortu', 30);
            $table->text('alamat_ortu')->nullable();
            $table->timestamps();
        });

        // 4. Berkas Persyaratan Pendaftaran
        Schema::create('berkas_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->string('jenis_berkas', 50); // kartu_keluarga, akta_kelahiran, ijazah_skl, rapor
            $table->string('nama_file_asli', 255);
            $table->string('file_path', 255);
            $table->unsignedInteger('ukuran_file')->nullable(); // dalam bytes
            $table->string('mime_type', 50)->nullable();
            $table->string('status_verifikasi', 30)->default('menunggu'); // menunggu, valid, tidak_valid
            $table->text('catatan')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('diverifikasi_pada')->nullable();
            $table->timestamps();
        });

        // 5. Hasil Seleksi Kelulusan
        Schema::create('hasil_seleksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->unique()->constrained('pendaftar')->onDelete('cascade');
            $table->string('status', 30)->default('MENUNGGU'); // MENUNGGU, LULUS, TIDAK_LULUS, CADANGAN
            $table->text('catatan')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('tanggal_pengumuman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_seleksi');
        Schema::dropIfExists('berkas_pendaftaran');
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('pendaftar');
        Schema::dropIfExists('periode_ppdb');
    }
};
