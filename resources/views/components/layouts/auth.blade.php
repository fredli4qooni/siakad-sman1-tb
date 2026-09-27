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
<body class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 text-[#1C2620]">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo / Brand Sekolah -->
        <div class="flex justify-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-[#0E6026] text-white flex items-center justify-center font-bold text-lg">
                    TB
                </div>
            </a>
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-[#1C2620]">
            {{ $heading ?? 'Single Sign-On (SSO)' }}
        </h2>
        <p class="mt-1 text-center text-xs text-[#545B52]">
            SMAN 1 Terbanggi Besar — PPDB & SIAKAD Terintegrasi
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-8 rounded-xl border border-[#E1E4DE]">
            {{ $slot }}
        </div>

        <div class="mt-6 text-center text-xs text-[#545B52]">
            <a href="{{ url('/') }}" class="text-[#0E6026] hover:underline font-medium">
                Kembali ke Beranda Sekolah
            </a>
        </div>
    </div>
</body>
</html>
