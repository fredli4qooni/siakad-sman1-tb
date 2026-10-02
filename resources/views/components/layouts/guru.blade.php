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
<body class="h-full overflow-hidden flex text-[#1C2620] bg-[#FFFFFF]">
    <!-- Mobile Sidebar Backdrop -->
    <div id="guru-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <!-- Sidebar Kiri Guru (Desktop Tetap, Mobile Slide-over) -->
    <aside id="guru-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-[#F3F5F2] border-r border-[#E1E4DE] flex flex-col flex-shrink-0 transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static h-full">
        <!-- Brand / Header Sidebar -->
        <div class="h-16 flex-shrink-0 flex items-center gap-3 px-5 border-b border-[#E1E4DE]">
            <img src="{{ asset('images/logo-sma.png') }}" alt="Logo SMAN 1 TB" class="w-8 h-8 object-contain">
            <div>
                <div class="text-sm font-bold text-[#1C2620] leading-tight">Portal Guru</div>
                <div class="text-[11px] text-[#545B52]">SIAKAD SMAN 1 TB</div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Menu Akademik
                </div>
                <div class="space-y-1">
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('guru.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dasbor Guru
                    </a>
                    <a href="{{ route('guru.pengampu.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('guru.pengampu*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Kelas & Mapel Diampu
                    </a>
                    <a href="{{ route('guru.nilai.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('guru.nilai*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Input Nilai Siswa
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Info & Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-[#E1E4DE] flex-shrink-0 bg-[#F3F5F2]">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <div class="text-xs font-semibold text-[#1C2620] truncate">{{ auth()->user()->nama ?? 'Bapak/Ibu Guru' }}</div>
                    <div class="text-[11px] text-[#545B52] truncate">
                        {{ auth()->user()->guru ? (auth()->user()->guru->nip ? 'NIP. ' . auth()->user()->guru->nip : 'Guru Pengampu') : 'Guru Pengampu' }}
                    </div>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" title="Logout SSO" class="text-xs text-[#C81210] hover:underline font-medium">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-[#F3F5F2]">
        <!-- Header Atas (Fixed 64px, NEVER scrolls away, perfectly vertically centered) -->
        <header class="h-16 flex-shrink-0 border-b border-[#E1E4DE] bg-white flex items-center justify-between px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" id="guru-sidebar-toggle" class="lg:hidden p-1.5 rounded-lg text-[#545B52] hover:bg-[#E1E4DE]/50" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-[#1C2620]">
                    {{ $heading ?? ($title ?? 'Portal Guru') }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span>
                    SSO Guru Aktif
                </span>
            </div>
        </header>

        <!-- Area Scroll Utama -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8" style="background-image: linear-gradient(rgba(243, 245, 242, 0.75), rgba(243, 245, 242, 0.85)), url('{{ asset('images/latar-belakang.jpeg') }}'); background-size: cover; background-position: center top; background-attachment: fixed;">
            <div class="w-full space-y-6">
                <!-- Flash Message Alerts -->
                @if (session('success'))
                    <x-alert type="success" title="Berhasil">{{ session('success') }}</x-alert>
                @endif
                @if (session('warning'))
                    <x-alert type="warning" title="Peringatan">{{ session('warning') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="danger" title="Terjadi Kesalahan">{{ session('error') }}</x-alert>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        const guruToggleBtn = document.getElementById('guru-sidebar-toggle');
        const guruSidebar = document.getElementById('guru-sidebar');
        const guruBackdrop = document.getElementById('guru-sidebar-backdrop');
        if (guruToggleBtn && guruSidebar && guruBackdrop) {
            guruToggleBtn.addEventListener('click', () => {
                guruSidebar.classList.toggle('-translate-x-full');
                guruBackdrop.classList.toggle('hidden');
            });
            guruBackdrop.addEventListener('click', () => {
                guruSidebar.classList.add('-translate-x-full');
                guruBackdrop.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
