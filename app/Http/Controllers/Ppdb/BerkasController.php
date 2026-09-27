<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ppdb\UploadBerkasRequest;
use App\Models\BerkasPendaftaran;
use App\Models\Pendaftar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BerkasController extends Controller
{
    /**
     * Menampilkan daftar berkas persyaratan calon siswa.
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir')->with('error', 'Silakan lengkapi formulir pendaftaran terlebih dahulu.');
        }

        $berkasList = $pendaftar->berkas()->get()->keyBy('jenis_berkas');

        $dokumenSyarat = [
            'kartu_keluarga' => [
                'nama' => 'Kartu Keluarga (KK)',
                'keterangan' => 'Scan asli Kartu Keluarga yang mencantumkan nama calon peserta didik.',
                'wajib' => true,
            ],
            'akta_kelahiran' => [
                'nama' => 'Akta Kelahiran',
                'keterangan' => 'Scan asli Akta Kelahiran resmi dari Disdukcapil.',
                'wajib' => true,
            ],
            'ijazah_skl' => [
                'nama' => 'Ijazah / Surat Keterangan Lulus (SKL)',
                'keterangan' => 'Scan Ijazah SMP/MTs atau Surat Keterangan Lulus resmi dari sekolah asal.',
                'wajib' => true,
            ],
            'rapor' => [
                'nama' => 'Buku Rapor SMP (Semester 1–5)',
                'keterangan' => 'Scan legalisir nilai rapor semester 1 sampai 5 yang telah digabung dalam 1 file PDF.',
                'wajib' => true,
            ],
        ];

        return view('ppdb.pendaftar.berkas', compact('pendaftar', 'berkasList', 'dokumenSyarat'));
    }

    /**
     * Memproses unggahan berkas persyaratan.
     */
    public function upload(UploadBerkasRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir')->with('error', 'Data pendaftar tidak ditemukan.');
        }

        if (in_array($pendaftar->status_pendaftaran, ['terverifikasi', 'lulus', 'tidak_lulus'])) {
            return back()->with('error', 'Berkas telah diverifikasi panitia dan tidak dapat diganti.');
        }

        $file = $request->file('file_berkas');
        $jenisBerkas = $request->input('jenis_berkas');

        // Cari apakah berkas jenis ini sudah ada sebelumnya
        $berkasLama = $pendaftar->berkas()->where('jenis_berkas', $jenisBerkas)->first();
        if ($berkasLama) {
            if (Storage::disk('public')->exists($berkasLama->file_path)) {
                Storage::disk('public')->delete($berkasLama->file_path);
            }
        }

        // Simpan file baru di storage public terisolasi
        $folder = 'berkas_ppdb/' . $pendaftar->id;
        $path = $file->store($folder, 'public');

        $pendaftar->berkas()->updateOrCreate(
            ['jenis_berkas' => $jenisBerkas],
            [
                'nama_file_asli' => $file->getClientOriginalName(),
                'file_path' => $path,
                'ukuran_file' => $file->getSize(),
                'mime_type' => $file->getClientMimeType(),
                'status_verifikasi' => 'menunggu',
                'catatan' => null,
            ]
        );

        return back()->with('success', 'Berkas ' . str_replace('_', ' ', $jenisBerkas) . ' berhasil diunggah.');
    }

    /**
     * Menghapus berkas yang telah diunggah.
     */
    public function destroy(BerkasPendaftaran $berkas): RedirectResponse
    {
        $user = Auth::user();

        // Validasi kepemilikan berkas atau hak akses admin
        if ($berkas->pendaftar->user_id !== $user->id && !$user->hasAdminAccess()) {
            abort(403, 'Anda tidak berhak menghapus berkas ini.');
        }

        if ($berkas->status_verifikasi === 'valid' && !$user->hasAdminAccess()) {
            return back()->with('error', 'Berkas yang telah divalidasi tidak dapat dihapus.');
        }

        if (Storage::disk('public')->exists($berkas->file_path)) {
            Storage::disk('public')->delete($berkas->file_path);
        }

        $berkas->delete();

        return back()->with('success', 'Berkas berhasil dihapus.');
    }

    /**
     * Mengunduh atau melihat berkas digital.
     */
    public function preview(BerkasPendaftaran $berkas): StreamedResponse
    {
        $user = Auth::user();

        if ($berkas->pendaftar->user_id !== $user->id && !$user->hasAdminAccess()) {
            abort(403, 'Akses ke dokumen ditolak.');
        }

        if (!Storage::disk('public')->exists($berkas->file_path)) {
            abort(404, 'File berkas fisik tidak ditemukan di server.');
        }

        return Storage::disk('public')->response(
            $berkas->file_path,
            $berkas->nama_file_asli,
            ['Content-Type' => $berkas->mime_type ?? 'application/octet-stream']
        );
    }

    /**
     * Mengirimkan berkas untuk diverifikasi oleh panitia.
     */
    public function kirimVerifikasi(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('pendaftar.formulir');
        }

        $berkasCount = $pendaftar->berkas()->whereIn('jenis_berkas', [
            'kartu_keluarga',
            'akta_kelahiran',
            'ijazah_skl',
            'rapor',
        ])->count();

        if ($berkasCount < 4) {
            return back()->with('error', 'Harap unggah seluruh 4 dokumen persyaratan utama terlebih dahulu sebelum mengajukan verifikasi.');
        }

        $pendaftar->update([
            'status_pendaftaran' => 'menunggu_verifikasi',
        ]);

        return redirect()->route('pendaftar.dashboard')
            ->with('success', 'Pendaftaran Anda berhasil diajukan! Panitia PPDB akan segera memverifikasi kelengkapan berkas Anda.');
    }
}
