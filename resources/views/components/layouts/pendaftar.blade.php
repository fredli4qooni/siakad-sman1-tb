<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FFFFFF]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Siswa' }} — SMAN 1 Terbanggi Besar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-white text-[#1C2620]">
    <!-- Navbar Header Portal Siswa -->
    <header class="border-b border-[#E1E4DE] bg-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('pendaftar.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-sm">
                        TB
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#1C2620] leading-tight">Portal Siswa & PPDB</div>
                        <div class="text-xs text-[#545B52]">SMAN 1 Terbanggi Besar</div>
                    </div>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('pendaftar.dashboard') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('pendaftar.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('pendaftar.formulir') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('pendaftar.formulir*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Formulir Pendaftaran
                </a>
                <a href="{{ route('pendaftar.berkas') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('pendaftar.berkas*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Unggah Berkas
                </a>
                <a href="{{ route('pendaftar.kelulusan') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('pendaftar.kelulusan*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Status Kelulusan
                </a>

                {{-- Tab Khusus jika sudah sinkron ke SIAKAD (Siswa Aktif) --}}
                @if (auth()->check() && (auth()->user()->role === 'siswa' || auth()->user()->siswa))
                    <div class="h-5 w-px bg-[#E1E4DE] mx-1"></div>
                    <a href="{{ route('siakad.siswa.kelas') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('siakad.siswa.kelas*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#0E6026] hover:bg-[#E7F4EA]' }} transition-colors">
                        Kelas Saya
                    </a>
                    <a href="{{ route('siakad.siswa.nilai') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('siakad.siswa.nilai*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#0E6026] hover:bg-[#E7F4EA]' }} transition-colors">
                        Nilai Akademik
                    </a>
                @endif
            </nav>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-semibold text-[#1C2620]">{{ auth()->user()->nama ?? 'Calon Siswa' }}</div>
                    <div class="text-[11px] text-[#545B52] capitalize">{{ auth()->user()->role ?? 'Pendaftar' }}</div>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-[#C81210] hover:underline font-medium px-2 py-1">
                        Keluar SSO
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Konten Halaman Portal -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6">
                <x-alert type="success">{{ session('success') }}</x-alert>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6">
                <x-alert type="danger">{{ session('error') }}</x-alert>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="border-t border-[#E1E4DE] bg-[#F3F5F2] py-6 text-center text-xs text-[#545B52]">
        © {{ date('Y') }} SMAN 1 Terbanggi Besar — Sistem PPDB & SIAKAD
    </footer>
</body>
</html>
