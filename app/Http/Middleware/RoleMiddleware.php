<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        if (!$user->status_aktif) {
            Auth::logout();
            return redirect()->route('auth.login')->with('error', 'Akun Anda dinonaktifkan.');
        }

        // Jika tidak ada peran spesifik yang disyaratkan
        if (empty($roles)) {
            return $next($request);
        }

        // Cek kecocokan peran (mendukung multi role seperti 'admin', 'operator')
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Jika admin, izinkan akses ke sebagian besar operasi jika relevan
        if ($user->isAdmin()) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses modul ini.');
    }
}
