<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\PeriodePpdb;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan formulir login terpusat (Modul Auth IdP).
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna di titik masuk tunggal (SSO IdP).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'identity' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $identity = $credentials['identity'];
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Cari berdasarkan email, NISN (pada tabel pendaftar/siswa), atau NIP (pada tabel guru)
        $user = User::where('email', $identity)
            ->orWhereHas('pendaftar', fn($q) => $q->where('nisn', $identity))
            ->orWhereHas('siswa', fn($q) => $q->where('nisn', $identity)->orWhere('nis', $identity))
            ->orWhereHas('guru', fn($q) => $q->where('nip', $identity))
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withInput($request->only('identity', 'remember'))
                ->withErrors(['identity' => 'Kredensial yang diberikan tidak cocok dengan data kami.']);
        }

        if (!$user->status_aktif) {
            return back()->withInput($request->only('identity'))
                ->withErrors(['identity' => 'Akun Anda telah dinonaktifkan oleh administrator sekolah.']);
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        // Jika terdapat permintaan OIDC yang tertunda (user dialihkan dari RP ke IdP untuk login)
        if (session()->has('oidc_auth_request')) {
            $params = session()->pull('oidc_auth_request');
            return redirect()->route('oidc.authorize', $params);
        }

        return $this->redirectBasedOnRole($user)->with('success', 'Selamat datang kembali, ' . $user->nama);
    }

    /**
     * Menampilkan formulir pendaftaran akun mandiri untuk calon siswa.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    /**
     * Memproses pembuatan akun pendaftar calon siswa baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => ['required', 'string', 'digits:10', 'unique:pendaftar,nisn', 'unique:siswa,nisn'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nisn.digits' => 'NISN harus berjumlah tepat 10 digit angka.',
            'nisn.unique' => 'NISN ini telah terdaftar dalam sistem.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $periodeAktif = PeriodePpdb::where('is_aktif', true)->first();
        if (!$periodeAktif) {
            return back()->withInput()->with('error', 'Saat ini belum ada periode pendaftaran PPDB yang aktif.');
        }

        DB::transaction(function () use ($validated, $periodeAktif, &$user) {
            // 1. Buat User Akun SSO
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'calon_siswa',
                'status_aktif' => true,
            ]);

            // 2. Buat Draft Profil Pendaftar dengan nomor pendaftaran otomatis
            $nomorUrut = Pendaftar::where('periode_id', $periodeAktif->id)->count() + 1;
            $noPendaftaran = 'PPDB-' . date('Y') . '-' . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);

            Pendaftar::create([
                'user_id' => $user->id,
                'periode_id' => $periodeAktif->id,
                'no_pendaftaran' => $noPendaftaran,
                'nisn' => $validated['nisn'],
                'nama_lengkap' => $validated['nama'],
                'jenis_kelamin' => 'L', // default sementara sampai formulir dilengkapi
                'tempat_lahir' => '-',
                'tanggal_lahir' => now()->subYears(15)->format('Y-m-d'),
                'agama' => 'Islam',
                'asal_sekolah' => '-',
                'alamat' => '-',
                'no_hp' => '-',
                'status_pendaftaran' => 'draft',
            ]);
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('pendaftar.formulir')->with('success', 'Akun berhasil dibuat. Silakan lengkapi formulir pendaftaran Anda.');
    }

    /**
     * Single Logout (SLO): mengakhiri sesi SSO secara global.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesi SSO Anda telah berhasil diakhiri.');
    }

    /**
     * Arahkan pengguna ke dasbor yang sesuai dengan perannya.
     */
    protected function redirectBasedOnRole(User $user): RedirectResponse
    {
        if ($user->hasAdminAccess()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        }

        if ($user->isSiswa()) {
            return redirect()->route('siakad.siswa.kelas');
        }

        return redirect()->route('pendaftar.dashboard');
    }
}
