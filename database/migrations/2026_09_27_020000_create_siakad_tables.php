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
        // 1. Data Guru
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nip', 30)->unique()->nullable();
            $table->string('nama_lengkap', 255);
            $table->string('gelar', 50)->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->string('alamat')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 2. Data Kelas / Rombel
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50); // Contoh: X MIPA 1, X-A
            $table->enum('tingkat', ['X', 'XI', 'XII'])->default('X');
            $table->string('tahun_ajaran', 20); // Contoh: 2026/2027
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->unsignedSmallInteger('kapasitas')->default(36);
            $table->timestamps();
        });

        // 3. Data Mata Pelajaran
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 30)->unique();
            $table->string('nama_mapel', 100);
            $table->unsignedSmallInteger('kkm')->default(75);
            $table->enum('kelompok', ['umum', 'peminatan', 'muatan_lokal'])->default('umum');
            $table->timestamps();
        });

        // 4. Data Siswa SIAKAD (Hasil Sinkronisasi PPDB / Siswa Berjalan)
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nisn', 20)->unique();
            $table->string('nis', 20)->nullable()->unique();
            $table->string('nama', 255);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('tahun_masuk', 10); // Contoh: 2026
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        // 5. Data Pengampu (Guru Mengajar di Kelas untuk Mata Pelajaran tertentu)
        Schema::create('pengampu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('tahun_ajaran', 20); // Contoh: 2026/2027
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->timestamps();

            // Kombinasi unik guru/kelas/mapel per tahun & semester
            $table->unique(['guru_id', 'kelas_id', 'mapel_id', 'tahun_ajaran', 'semester'], 'pengampu_unik');
        });

        // 6. Data Nilai Siswa
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('pengampu_id')->constrained('pengampu')->onDelete('cascade');
            $table->decimal('nilai_tugas', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->text('capaian_kompetensi')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'pengampu_id'], 'nilai_siswa_pengampu_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
        Schema::dropIfExists('pengampu');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('guru');
    }
};
