<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal Guru' }} — SIAKAD SMAN 1 Terbanggi Besar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-white text-[#1C2620]">
    <!-- Navbar Header Portal Guru -->
    <header class="border-b border-[#E1E4DE] bg-white sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-sm">
                        TB
                    </div>
                    <div>
                        <div class="text-sm font-bold text-[#1C2620] leading-tight">Portal Guru Akademik</div>
                        <div class="text-xs text-[#545B52]">SIAKAD SMAN 1 Terbanggi Besar</div>
                    </div>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('guru.dashboard') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('guru.pengampu.index') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('guru.pengampu*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Kelas & Mapel Diampu
                </a>
                <a href="{{ route('guru.nilai.index') }}" class="text-sm font-medium px-3 py-2 rounded-lg {{ request()->routeIs('guru.nilai*') ? 'bg-[#E7F4EA] text-[#0E6026]' : 'text-[#1C2620] hover:text-[#0E6026]' }} transition-colors">
                    Input Nilai Siswa
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <div class="text-xs font-semibold text-[#1C2620]">{{ auth()->user()->nama ?? 'Bapak/Ibu Guru' }}</div>
                    <div class="text-[11px] text-[#545B52]">Guru Pengampu</div>
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

    <!-- Mobile Subnav Bar untuk Layar HP -->
    <div class="md:hidden border-b border-[#E1E4DE] bg-[#F3F5F2] px-4 py-2 overflow-x-auto flex items-center gap-2 whitespace-nowrap text-xs">
        <a href="{{ route('guru.dashboard') }}" class="px-2.5 py-1.5 rounded-lg font-medium {{ request()->routeIs('guru.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620]' }}">
            Dashboard
        </a>
        <a href="{{ route('guru.pengampu.index') }}" class="px-2.5 py-1.5 rounded-lg font-medium {{ request()->routeIs('guru.pengampu*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620]' }}">
            Kelas & Mapel
        </a>
        <a href="{{ route('guru.nilai.index') }}" class="px-2.5 py-1.5 rounded-lg font-medium {{ request()->routeIs('guru.nilai*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620]' }}">
            Input Nilai
        </a>
    </div>

    <!-- Konten Halaman Guru -->
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
        © {{ date('Y') }} SMAN 1 Terbanggi Besar — Sistem Informasi Akademik (SIAKAD)
    </footer>
</body>
</html>
