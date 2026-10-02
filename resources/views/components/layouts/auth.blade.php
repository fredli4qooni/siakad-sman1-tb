<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F3F5F2]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Autentikasi SSO' }} — SMAN 1 Terbanggi Besar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 text-[#1C2620] relative" style="background-image: linear-gradient(135deg, rgba(14, 96, 38, 0.86) 0%, rgba(20, 36, 25, 0.92) 100%), url('{{ asset('images/latar-belakang.jpeg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo / Brand Sekolah -->
        <div class="flex justify-center">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center p-2.5 bg-white rounded-2xl shadow-lg hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo-sma.png') }}" alt="Logo SMAN 1 TB" class="w-14 h-14 object-contain">
            </a>
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-white drop-shadow-xs">
            {{ $heading ?? 'Single Sign-On (SSO)' }}
        </h2>
        <p class="mt-1 text-center text-xs text-[#E7F4EA]">
            SMAN 1 Terbanggi Besar — PPDB & SIAKAD Terintegrasi
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-8 rounded-2xl shadow-2xl border border-white/20">
            {{ $slot }}
        </div>

        <div class="mt-6 text-center text-xs text-white/80">
            <a href="{{ url('/') }}" class="text-white hover:underline font-medium inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda Sekolah
            </a>
        </div>
    </div>
</body>
</html>
