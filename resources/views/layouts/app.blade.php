<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>
</head>
<body class="font-sans antialiased">

    <div x-data="{ show: false, message: '', type: 'success' }" x-init="
        Livewire.on('notification-sent', (payload) => {
            message = payload.message;
            type = payload.type || 'success';
            show = true;
            setTimeout(() => show = false, 4000);
        });
    " x-show="show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed top-0 right-0 z-50 p-4 mt-4 mr-4 max-w-sm w-full" style="display: none;">
        <div :class="{
                'bg-green-100 border-green-400 text-green-700': type === 'success',
                'bg-red-100 border-red-400 text-red-700': type === 'error',
                'bg-yellow-100 border-yellow-400 text-yellow-700': type === 'warning',
                'bg-blue-100 border-blue-400 text-blue-700': type === 'info'
            }" class="border-l-4 p-4 rounded-md shadow-lg flex justify-between items-center" role="alert">
            <p class="font-bold mr-2" x-text="message"></p>
            <button @click="show = false" class="text-xl font-semibold leading-none">&times;</button>
        </div>
    </div>

    <div class="min-h-screen bg-gray-100">
        <livewire:layout.navigation />
        <!-- Page Heading -->
        @if (isset($header))
        <header class="">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')



</body>
</html>
