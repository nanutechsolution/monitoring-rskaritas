@php
$logo = getSettingLogo();
$wallpaper = getSettingWallpaper();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RS Karitas') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <!-- Background Dinamis -->
    <div class="relative min-h-screen flex items-center justify-center" style="background: {{ $wallpaper ? 'url('.$wallpaper.') center/cover no-repeat' : '#f8fafc' }};">

        <!-- Overlay gelap lembut -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>


        <!-- Card Form Login -->
        <div class="relative z-10 w-full max-w-md p-8 bg-white/90 dark:bg-gray-900/80 backdrop-blur-md shadow-2xl rounded-2xl border border-white/20 animate-fade-in">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="{{ $logo ?? asset('logo/lg_karitas.png') }}" alt="Logo" class="h-20 w-auto drop-shadow-lg">
            </div>

            <!-- Judul -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-300">Selamat Datang</h1>
                <p class="text-sm text-gray-500 dark:text-gray-200 mt-1">Masuk untuk mengakses sistem RS Karitas</p>
            </div>
            {{ $slot }}

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">
                © {{ date('Y') }} RS Karitas — Sistem Informasi Kesehatan
            </div>
        </div>
    </div>

    <!-- Tailwind Animation -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

    </style>
</body>
</html>
