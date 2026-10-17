<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal SIAKAD' }} — SMAN 1 Terbanggi Besar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden flex text-[#1C2620] bg-[#FFFFFF]">
    <!-- Mobile Sidebar Backdrop -->
    <div id="siakad-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

    <!-- Sidebar Kiri SIAKAD Siswa -->
    <aside id="siakad-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-[#F3F5F2] border-r border-[#E1E4DE] flex flex-col flex-shrink-0 transition-transform duration-200 -translate-x-full lg:translate-x-0 lg:static h-full">
        <!-- Brand / Header Sidebar -->
        <div class="h-16 flex-shrink-0 flex items-center gap-3 px-5 border-b border-[#E1E4DE]">
            <img src="{{ asset('images/logo-sma.png') }}" alt="Logo SMAN 1 TB" class="w-8 h-8 object-contain">
            <div>
                <div class="text-sm font-bold text-[#1C2620] leading-tight">Portal SIAKAD</div>
                <div class="text-[11px] text-[#0E6026] font-semibold">SMAN 1 Terbanggi Besar</div>
            </div>
        </div>

        <!-- Navigation Links Khusus SIAKAD -->
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#0E6026] mb-2">
                    Akademik & Kependidikan
                </div>
                <div class="space-y-1">
                    <a href="{{ route('siakad.siswa.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('siakad.siswa.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Data Induk Siswa
                    </a>
                    <a href="{{ route('siakad.siswa.kelas') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('siakad.siswa.kelas*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Kelas & Semester
                    </a>
                </div>
            </div>

        </nav>

        <!-- User Info & Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-[#E1E4DE] flex-shrink-0 bg-[#F3F5F2]">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <div class="text-xs font-semibold text-[#1C2620] truncate">{{ auth()->user()->nama ?? 'Siswa Aktif' }}</div>
                    <div class="text-[11px] text-[#0E6026] font-medium truncate">
                        {{ auth()->user()->siswa ? (auth()->user()->siswa->nis ? 'NIS. ' . auth()->user()->siswa->nis : 'Siswa Aktif') : 'Siswa SIAKAD' }}
                    </div>
                </div>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" title="Logout SIAKAD" class="text-xs text-[#C81210] hover:underline font-medium">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-[#F3F5F2]">
        <!-- Header Atas (Fixed 64px) -->
        <header class="h-16 flex-shrink-0 border-b border-[#E1E4DE] bg-white flex items-center justify-between px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button type="button" id="siakad-sidebar-toggle" class="lg:hidden p-1.5 rounded-lg text-[#545B52] hover:bg-[#E1E4DE]/50" aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-bold text-[#1C2620]">
                    {{ $heading ?? ($title ?? 'Portal SIAKAD') }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#E7F4EA] text-[#0E6026]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#039834]"></span>
                    SIAKAD Aktif
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
                @if (session('info'))
                    <x-alert type="info" title="Informasi">{{ session('info') }}</x-alert>
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
        const siakadToggleBtn = document.getElementById('siakad-sidebar-toggle');
        const siakadSidebar = document.getElementById('siakad-sidebar');
        const siakadBackdrop = document.getElementById('siakad-sidebar-backdrop');
        if (siakadToggleBtn && siakadSidebar && siakadBackdrop) {
            siakadToggleBtn.addEventListener('click', () => {
                siakadSidebar.classList.toggle('-translate-x-full');
                siakadBackdrop.classList.toggle('hidden');
            });
            siakadBackdrop.addEventListener('click', () => {
                siakadSidebar.classList.add('-translate-x-full');
                siakadBackdrop.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
