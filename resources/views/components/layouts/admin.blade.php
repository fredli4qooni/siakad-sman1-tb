<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin & Operator' }} — PPDB SIAKAD SMAN 1 TB</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex text-[#1C2620] bg-[#FFFFFF]">
    <!-- Sidebar Kiri -->
    <aside class="w-64 bg-[#F3F5F2] border-r border-[#E1E4DE] flex flex-col flex-shrink-0 min-h-screen">
        <!-- Brand / Header Sidebar -->
        <div class="h-16 flex items-center gap-3 px-5 border-b border-[#E1E4DE]">
            <div class="w-8 h-8 rounded-lg bg-[#0E6026] text-white flex items-center justify-center font-bold text-sm">
                TB
            </div>
            <div>
                <div class="text-sm font-bold text-[#1C2620] leading-tight">Admin & Operator</div>
                <div class="text-[11px] text-[#545B52]">SMAN 1 Terbanggi Besar</div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-6 overflow-y-auto">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard Utama
                </a>
            </div>

            <!-- Modul PPDB -->
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Modul PPDB
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.ppdb.periode.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.ppdb.periode*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Periode & Kuota
                    </a>
                    <a href="{{ route('admin.ppdb.pendaftar.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.ppdb.pendaftar*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Verifikasi Pendaftar
                    </a>
                    <a href="{{ route('admin.ppdb.seleksi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.ppdb.seleksi*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hasil Seleksi
                    </a>
                </div>
            </div>

            <!-- Modul Integrasi (Sync) -->
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Integrasi Data
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.sync.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.sync*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Sync Logs (PPDB → SIAKAD)
                    </a>
                </div>
            </div>

            <!-- Modul SIAKAD -->
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Modul SIAKAD
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.siakad.siswa.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.siakad.siswa*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Data Siswa
                    </a>
                    <a href="{{ route('admin.siakad.kelas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.siakad.kelas*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Manajemen Kelas
                    </a>
                    <a href="{{ route('admin.siakad.guru.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.siakad.guru*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Data Guru
                    </a>
                    <a href="{{ route('admin.siakad.mapel.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.siakad.mapel*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Mata Pelajaran
                    </a>
                    <a href="{{ route('admin.siakad.pengampu.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.siakad.pengampu*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                        Penugasan Mengajar
                    </a>
                </div>
            </div>

            <!-- Modul Auth & Users -->
            <div>
                <div class="px-3 text-[11px] font-semibold uppercase tracking-wider text-[#545B52] mb-2">
                    Sistem & Auth SSO
                </div>
                <div class="space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('admin.users*') ? 'bg-[#E7F4EA] text-[#0E6026] font-semibold' : 'text-[#1C2620] hover:bg-[#E1E4DE]/50 font-medium' }} transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Manajemen Pengguna
                    </a>
                </div>
            </div>
        </nav>

        <!-- User Info & Logout di Bawah Sidebar -->
        <div class="p-4 border-t border-[#E1E4DE]">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <div class="text-xs font-semibold text-[#1C2620] truncate">{{ auth()->user()->nama ?? 'Operator Sekolah' }}</div>
                    <div class="text-[11px] text-[#545B52] capitalize">{{ auth()->user()->role ?? 'Admin' }}</div>
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
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Header Atas -->
        <header class="h-16 border-b border-[#E1E4DE] bg-white flex items-center justify-between px-6 sticky top-0 z-20">
            <div>
                <h1 class="text-lg font-bold text-[#1C2620]">{{ $heading ?? 'Dashboard' }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E7F4EA] text-[#0E6026]">
                    SSO Aktif
                </span>
            </div>
        </header>

        <!-- Flash Message Alerts -->
        @if (session('success'))
            <div class="mx-6 mt-4">
                <x-alert type="success">{{ session('success') }}</x-alert>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-6 mt-4">
                <x-alert type="danger">{{ session('error') }}</x-alert>
            </div>
        @endif

        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
