<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'PPDB & SIAKAD' }} — SMAN 1 Terbanggi Besar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-white text-[#1C2620]">
    <!-- Header Navigasi Publik -->
    <header class="border-b border-[#E1E4DE] bg-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-base">
                        TB
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#1C2620] leading-tight">SMAN 1 Terbanggi Besar</div>
                        <div class="text-xs text-[#545B52]">Portal PPDB & SIAKAD Terintegrasi</div>
                    </div>
                </a>
            </div>

            <nav class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="text-sm font-medium text-[#1C2620] hover:text-[#0E6026] px-3 py-2 rounded-lg transition-colors">
                    Beranda
                </a>
                <a href="{{ url('/ppdb/alur') }}" class="text-sm font-medium text-[#1C2620] hover:text-[#0E6026] px-3 py-2 rounded-lg transition-colors">
                    Alur & Kuota
                </a>
                <a href="{{ url('/ppdb/pengumuman') }}" class="text-sm font-medium text-[#1C2620] hover:text-[#0E6026] px-3 py-2 rounded-lg transition-colors">
                    Pengumuman
                </a>

                @auth
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                        <a href="{{ url('/admin/dashboard') }}" class="text-sm font-medium bg-[#0E6026] text-white px-4 py-2 rounded-lg hover:bg-[#0A4C1E] transition-colors">
                            Dashboard Admin
                        </a>
                    @elseif (auth()->user()->role === 'guru')
                        <a href="{{ url('/guru/dashboard') }}" class="text-sm font-medium bg-[#0E6026] text-white px-4 py-2 rounded-lg hover:bg-[#0A4C1E] transition-colors">
                            Portal Guru
                        </a>
                    @else
                        <a href="{{ url('/pendaftar/dashboard') }}" class="text-sm font-medium bg-[#0E6026] text-white px-4 py-2 rounded-lg hover:bg-[#0A4C1E] transition-colors">
                            Portal Siswa
                        </a>
                    @endif
                @else
                    <a href="{{ route('auth.login') }}" class="text-sm font-medium text-[#0E6026] hover:bg-[#E7F4EA] px-3 py-2 rounded-lg transition-colors">
                        Masuk SSO
                    </a>
                    <a href="{{ route('auth.register') }}" class="text-sm font-medium bg-[#0E6026] text-white px-4 py-2 rounded-lg hover:bg-[#0A4C1E] transition-colors">
                        Daftar PPDB
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="border-t border-[#E1E4DE] bg-[#F3F5F2] py-8 text-center text-xs text-[#545B52]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                © {{ date('Y') }} SMAN 1 Terbanggi Besar — Sistem Informasi PPDB Terintegrasi SIAKAD
            </div>
            <div class="text-xs text-[#9FA29E]">
                Single Sign-On (OIDC) & Sinkronisasi Otomatis
            </div>
        </div>
    </footer>
</body>
</html>
