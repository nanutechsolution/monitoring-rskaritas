<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'My App') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 antialiased font-sans">

    <header class="w-full py-6 shadow-sm bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">
                {{ config('app.name', 'My App') }}
            </h1>

            @if(Route::has('login'))
            <nav class="space-x-4 flex items-center">
                @auth
                <a href="{{ url('/dashboard') }}" class="hover:text-blue-500">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="hover:text-blue-500">Login</a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="hover:text-blue-500">Register</a>
                @endif
                @endauth
            </nav>
            @endif
        </div>
    </header>

    <main class="min-h-screen flex flex-col items-center justify-center px-6">
        <div class="text-center max-w-2xl">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">
                Selamat Datang di {{ config('app.name', 'My App') }}
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                Platform modern untuk mengelola dan mengembangkan aplikasi Anda dengan mudah, cepat, dan profesional.
            </p>

            <div class="flex items-center justify-center gap-4">
                @if(Route::has('login'))
                @auth
                <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Masuk ke Dashboard
                </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Login
                    </a>
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="px-6 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-800 transition">
                    Register
                </a>
                @endif
                @endauth
                @endif
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} {{ config('app.name', 'My App') }}. All rights reserved.
    </footer>

</body>
</html>
