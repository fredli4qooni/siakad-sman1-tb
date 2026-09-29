<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
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
<body class="h-full overflow-hidden flex text-[#1C2620] bg-[#FFFFFF]">
    <!-- Mobile Sidebar Backdrop -->
    <div id="pendaftar-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <!-- Sidebar Kiri Siswa (Desktop Tetap, Mobile Slide-over) -->
    <aside id="pendaftar-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-[#F3F5F2] border-r border-[#E1E4DE] flex flex-col flex-shrink-0 transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static h-full">
        <!-- Brand / Header Sidebar -->
        <div class="h-16 flex-shrink-0 flex items-center gap-3 px-5 border-b border-[#E1E4DE]">
            <img src="{{ asset('images/logo-sma.png') }}" alt="Logo SMAN 1 TB" class="w-8 h-8 object-contain">
            <div>
                <div class="text-sm font-bold text-[#1C2620] leading-tight">Portal Siswa</div>
                <div class="text-[11px] text-[#545B52]">SMAN 1 Terbanggi Besar</div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
            <!-- Modul PPDB -->
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Penerimaan Siswa Baru
                </div>
                <div class="space-y-1">
                    <a href="{{ route('pendaftar.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('pendaftar.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pendaftar.formulir') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('pendaftar.formulir*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Formulir Pendaftaran
                    </a>
                    <a href="{{ route('pendaftar.berkas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('pendaftar.berkas*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Unggah Berkas
                    </a>
                    <a href="{{ route('pendaftar.kelulusan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('pendaftar.kelulusan*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status Kelulusan
                    </a>
                    <a href="{{ route('pendaftar.cetak_bukti') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Bukti Registrasi
                    </a>
                </div>
            </div>

            <!-- Modul SIAKAD (Hanya jika siswa telah diterima & tersinkron) -->
            @if (auth()->check() && (auth()->user()->role === 'siswa' || auth()->user()->siswa))
                <div>
                    <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#0E6026] mb-2">
                        Akademik SIAKAD
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('siakad.siswa.kelas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('siakad.siswa.kelas*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Kelas Saya
                        </a>
                        <a href="{{ route('siakad.siswa.nilai') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('siakad.siswa.nilai*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Nilai Akademik
                        </a>
                    </div>
                </div>
            @endif
        </nav>

        <!-- User Info & Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-[#E1E4DE] flex-shrink-0 bg-[#F3F5F2]">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <div class="text-xs font-semibold text-[#1C2620] truncate">{{ auth()->user()->nama ?? 'Calon Siswa' }}</div>
                    <div class="text-[11px] text-[#545B52] truncate">
                        {{ auth()->user()->siswa ? (auth()->user()->siswa->nis ? 'NIS. ' . auth()->user()->siswa->nis : 'Siswa Aktif') : 'Pendaftar PPDB' }}
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
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-white">
        <!-- Header Atas (Fixed 64px, NEVER scrolls away, perfectly vertically centered) -->
        <header class="h-16 flex-shrink-0 border-b border-[#E1E4DE] bg-white flex items-center justify-between px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" id="pendaftar-sidebar-toggle" class="lg:hidden p-1.5 rounded-lg text-[#545B52] hover:bg-[#E1E4DE]/50" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-[#1C2620]">
                    {{ $heading ?? ($title ?? 'Portal Siswa') }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                @if (auth()->check() && (auth()->user()->role === 'siswa' || auth()->user()->siswa))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span>
                        SIAKAD Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F3F5F2] text-[#545B52] border border-[#E1E4DE]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#545B52]"></span>
                        Pendaftar PPDB
                    </span>
                @endif
            </div>
        </header>

        <!-- Area Scroll Utama -->
        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
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
        const pendaftarToggleBtn = document.getElementById('pendaftar-sidebar-toggle');
        const pendaftarSidebar = document.getElementById('pendaftar-sidebar');
        const pendaftarBackdrop = document.getElementById('pendaftar-sidebar-backdrop');
        if (pendaftarToggleBtn && pendaftarSidebar && pendaftarBackdrop) {
            pendaftarToggleBtn.addEventListener('click', () => {
                pendaftarSidebar.classList.toggle('-translate-x-full');
                pendaftarBackdrop.classList.toggle('hidden');
            });
            pendaftarBackdrop.addEventListener('click', () => {
                pendaftarSidebar.classList.add('-translate-x-full');
                pendaftarBackdrop.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
