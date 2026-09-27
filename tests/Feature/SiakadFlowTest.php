<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Pengampu;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiakadFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Guru $guruA;
    protected User $userGuruA;
    protected Guru $guruB;
    protected User $userGuruB;
    protected Kelas $kelas;
    protected MataPelajaran $mapel;
    protected Pengampu $pengampuA;
    protected Siswa $siswa;
    protected User $userSiswa;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Admin
        $this->admin = User::factory()->create(['role' => 'admin', 'status_aktif' => true]);

        // 2. Guru A
        $this->userGuruA = User::factory()->create(['role' => 'guru', 'status_aktif' => true]);
        $this->guruA = Guru::create([
            'user_id' => $this->userGuruA->id,
            'nip' => '198001012005011001',
            'nama_lengkap' => 'Drs. Ahmad Fauzi',
            'gelar' => 'M.Pd.',
            'no_hp' => '081234567890',
        ]);

        // 3. Guru B
        $this->userGuruB = User::factory()->create(['role' => 'guru', 'status_aktif' => true]);
        $this->guruB = Guru::create([
            'user_id' => $this->userGuruB->id,
            'nip' => '198502022008012002',
            'nama_lengkap' => 'Siti Rahmawati',
            'gelar' => 'S.Pd.',
            'no_hp' => '081298765432',
        ]);

        // 4. Kelas
        $this->kelas = Kelas::create([
            'nama_kelas' => 'X MIPA 1',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $this->guruA->id,
            'kapasitas' => 36,
        ]);

        // 5. Mapel
        $this->mapel = MataPelajaran::create([
            'kode_mapel' => 'MAT-X',
            'nama_mapel' => 'Matematika Wajib',
            'kkm' => 75,
            'kelompok' => 'umum',
        ]);

        // 6. Pengampu A (Guru A teaches Matematika in X MIPA 1)
        $this->pengampuA = Pengampu::create([
            'guru_id' => $this->guruA->id,
            'kelas_id' => $this->kelas->id,
            'mapel_id' => $this->mapel->id,
            'tahun_ajaran' => '2026/2027',
        ]);

        // 7. Siswa
        $this->userSiswa = User::factory()->create(['role' => 'siswa', 'status_aktif' => true]);
        $this->siswa = Siswa::create([
            'user_id' => $this->userSiswa->id,
            'nisn' => '0098765432',
            'nis' => '20260001',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Lintas Sumatera No. 45',
            'kelas_id' => $this->kelas->id,
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);
    }

    public function test_admin_can_manage_classes_and_plot_students(): void
    {
        // 1. Admin accesses kelas management
        $response = $this->actingAs($this->admin)->get('/admin/siakad/kelas');
        $response->assertStatus(200)->assertSee('X MIPA 1');

        // 2. Admin creates a new class
        $this->actingAs($this->admin)->post('/admin/siakad/kelas', [
            'nama_kelas' => 'X MIPA 2',
            'tingkat' => 'X',
            'tahun_ajaran' => '2026/2027',
            'kapasitas' => 36,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('kelas', ['nama_kelas' => 'X MIPA 2']);

        // 3. Admin plots unassigned student to class
        $siswaBaru = Siswa::create([
            'user_id' => User::factory()->create(['role' => 'siswa'])->id,
            'nisn' => '0011223399',
            'nis' => '20260002',
            'nama' => 'Siswa Baru Tersinkron',
            'jenis_kelamin' => 'P',
            'tahun_masuk' => '2026',
            'status_aktif' => true,
        ]);

        $this->actingAs($this->admin)->post("/admin/siakad/siswa/{$siswaBaru->id}/ploting", [
            'kelas_id' => $this->kelas->id,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswaBaru->id,
            'kelas_id' => $this->kelas->id,
        ]);
    }

    public function test_admin_can_manage_guru_mapel_and_pengampu(): void
    {
        // 1. Admin adds new subject
        $this->actingAs($this->admin)->post('/admin/siakad/mapel', [
            'kode_mapel' => 'BIO-X',
            'nama_mapel' => 'Biologi',
            'kkm' => 75,
            'kelompok' => 'peminatan',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('mata_pelajaran', ['kode_mapel' => 'BIO-X']);

        // 2. Admin assigns Guru B to Biologi in X MIPA 1
        $mapelBio = MataPelajaran::where('kode_mapel', 'BIO-X')->first();

        $this->actingAs($this->admin)->post('/admin/siakad/pengampu', [
            'guru_id' => $this->guruB->id,
            'kelas_id' => $this->kelas->id,
            'mapel_id' => $mapelBio->id,
            'tahun_ajaran' => '2026/2027',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('pengampu', [
            'guru_id' => $this->guruB->id,
            'kelas_id' => $this->kelas->id,
            'mapel_id' => $mapelBio->id,
        ]);
    }

    public function test_guru_can_view_assigned_classes_and_input_grades_with_auto_calculation(): void
    {
        // 1. Guru A views dashboard
        $response = $this->actingAs($this->userGuruA)->get('/guru/dashboard');
        $response->assertStatus(200)
            ->assertSee('Matematika Wajib')
            ->assertSee('X MIPA 1');

        // 2. Guru A opens grade sheet
        $responseSheet = $this->actingAs($this->userGuruA)->get("/guru/nilai/{$this->pengampuA->id}");
        $responseSheet->assertStatus(200)
            ->assertSee('Budi Santoso');

        // 3. Guru A inputs grades: Tugas: 80, UTS: 85, UAS: 90
        // Formula: 80*0.3 + 85*0.3 + 90*0.4 = 24 + 25.5 + 36 = 85.5
        $responseSave = $this->actingAs($this->userGuruA)->post("/guru/nilai/{$this->pengampuA->id}", [
            'nilai' => [
                $this->siswa->id => [
                    'tugas' => 80,
                    'uts' => 85,
                    'uas' => 90,
                    'capaian_kompetensi' => 'Sangat menguasai konsep aljabar linear',
                ],
            ],
        ]);

        $responseSave->assertSessionHas('success');

        $this->assertDatabaseHas('nilai', [
            'siswa_id' => $this->siswa->id,
            'pengampu_id' => $this->pengampuA->id,
            'nilai_tugas' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
            'nilai_akhir' => 85.5,
            'capaian_kompetensi' => 'Sangat menguasai konsep aljabar linear',
        ]);
    }

    public function test_guru_cannot_input_grades_for_unassigned_classes(): void
    {
        // Guru B tries to access or post to Pengampu A (which is taught by Guru A)
        $response = $this->actingAs($this->userGuruB)->get("/guru/nilai/{$this->pengampuA->id}");
        $response->assertStatus(403);

        $responsePost = $this->actingAs($this->userGuruB)->post("/guru/nilai/{$this->pengampuA->id}", [
            'nilai' => [
                $this->siswa->id => ['tugas' => 90, 'uts' => 90, 'uas' => 90],
            ],
        ]);
        $responsePost->assertStatus(403);
    }

    public function test_student_can_view_class_info_and_academic_report(): void
    {
        // Pre-create a grade for this student
        Nilai::create([
            'siswa_id' => $this->siswa->id,
            'pengampu_id' => $this->pengampuA->id,
            'nilai_tugas' => 80,
            'nilai_uts' => 85,
            'nilai_uas' => 90,
            'nilai_akhir' => 85.5,
            'capaian_kompetensi' => 'Kompetensi tercapai dengan baik',
        ]);

        // 1. Student views class
        $responseKelas = $this->actingAs($this->userSiswa)->get('/siakad/siswa/kelas');
        $responseKelas->assertStatus(200)
            ->assertSee('X MIPA 1')
            ->assertSee('Drs. Ahmad Fauzi')
            ->assertSee('Budi Santoso');

        // 2. Student views report card
        $responseNilai = $this->actingAs($this->userSiswa)->get('/siakad/siswa/nilai');
        $responseNilai->assertStatus(200)
            ->assertSee('Matematika Wajib')
            ->assertSee('85.5')
            ->assertSee('Tuntas');
    }
}
