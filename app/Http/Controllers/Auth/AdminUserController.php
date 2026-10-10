<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SimpanAdminRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Tampilkan halaman kelola akun administrator & operator beserta formulir pembuatannya.
     */
    public function index(Request $request): View
    {
        $keyword = $request->query('keyword');

        $adminUsers = User::whereIn('role', ['admin', 'operator'])
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->orderByRaw("CASE WHEN role = 'admin' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalAdmin = User::where('role', 'admin')->count();
        $totalOperator = User::where('role', 'operator')->count();
        $totalAktif = User::whereIn('role', ['admin', 'operator'])->where('status_aktif', true)->count();

        return view('admin.users.admins', compact(
            'adminUsers',
            'totalAdmin',
            'totalOperator',
            'totalAktif'
        ));
    }

    /**
     * Simpan akun administrator / operator baru ke database SSO.
     */
    public function store(SimpanAdminRequest $request): RedirectResponse
    {
        $user = User::create([
            'nama' => $request->validated('nama'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role' => $request->validated('role'),
            'status_aktif' => true,
        ]);

        $roleLabel = $user->role === 'admin' ? 'Administrator' : 'Operator';

        return redirect()->route('admin.admins.index')->with(
            'success',
            "Akun {$roleLabel} baru atas nama '{$user->nama}' ({$user->email}) berhasil dibuat."
        );
    }

    /**
     * Aktifkan atau nonaktifkan status akun admin/operator.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        // Proteksi: tidak dapat menonaktifkan akun sendiri yang sedang digunakan
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri yang sedang aktif digunakan.');
        }

        // Pastikan hanya akun ber-role admin atau operator
        if (!in_array($user->role, ['admin', 'operator'], true)) {
            return back()->with('error', 'Operasi ini hanya berlaku untuk akun Administrator atau Operator.');
        }

        $user->update([
            'status_aktif' => !$user->status_aktif,
        ]);

        $statusText = $user->status_aktif ? 'diaktifkan kembali' : 'dinonaktifkan';

        return back()->with('success', "Akun '{$user->nama}' berhasil {$statusText}.");
    }

    /**
     * Reset kata sandi akun admin/operator.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        // Pastikan hanya akun ber-role admin atau operator
        if (!in_array($user->role, ['admin', 'operator'], true)) {
            return back()->with('error', 'Operasi reset kata sandi ini hanya berlaku untuk akun Administrator atau Operator.');
        }

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "Kata sandi untuk akun '{$user->nama}' berhasil diperbarui.");
    }

    /**
     * Hapus akun admin/operator dari sistem.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Proteksi: tidak dapat menghapus akun sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Proteksi: hanya akun admin atau operator yang dapat dihapus dari panel ini
        if (!in_array($user->role, ['admin', 'operator'], true)) {
            return back()->with('error', 'Hanya akun administrator atau operator yang dapat dihapus dari menu ini.');
        }

        $nama = $user->nama;
        $user->delete();

        return back()->with('success', "Akun administrator/operator '{$nama}' telah berhasil dihapus.");
    }

    /**
     * Tampilkan daftar seluruh pengguna terdaftar pada Identity Provider (IdP) SSO.
     */
    public function indexAll(Request $request): View
    {
        $keyword = $request->query('keyword');
        $role = $request->query('role');

        $users = User::query()
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%");
                });
            })
            ->when($role && $role !== 'semua', function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'operator' => User::where('role', 'operator')->count(),
            'guru' => User::where('role', 'guru')->count(),
            'siswa' => User::where('role', 'siswa')->count(),
            'calon_siswa' => User::where('role', 'calon_siswa')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }
}
