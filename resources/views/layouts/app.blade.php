<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Dark mode sebelum render --}}
    <script>
        window.theme = {
            darkMode: JSON.parse(localStorage.getItem('darkMode'))
                      ?? window.matchMedia('(prefers-color-scheme: dark)').matches,
            toggle() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', JSON.stringify(this.darkMode));
                document.documentElement.classList.toggle('dark', this.darkMode);
            },
            init() {
                document.documentElement.classList.toggle('dark', this.darkMode);
            }
        };
        window.theme.init();
    </script>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    {{-- Notifikasi Livewire --}}
    <div x-data="{ show: false, message: '', type: 'success' }" x-init="
        Livewire.on('notification-sent', payload => { message = payload.message; type = payload.type || 'success'; show = true; setTimeout(() => show = false, 4000); });
        Livewire.on('record-saved-toast', payload => { message = payload.message; type = 'success'; show = true; setTimeout(() => show = false, 4000); });
        Livewire.on('error-notification', payload => { message = payload.message; type = 'error'; show = true; setTimeout(() => show = false, 4000); });
    " x-show="show" x-transition class="fixed top-0 right-0 z-50 p-4 mt-4 mr-4 max-w-sm w-full" style="display: none;">
        <div :class="{
            'bg-green-50 dark:bg-green-900 dark:bg-opacity-50 border-green-500 dark:border-green-600 text-green-800 dark:text-green-100': type === 'success',
            'bg-red-50 dark:bg-red-900 dark:bg-opacity-50 border-red-500 dark:border-red-600 text-red-800 dark:text-red-100': type === 'error'
        }" class="border-l-4 p-4 rounded-lg shadow-md flex justify-between items-center" role="alert">
            <p class="font-bold mr-2" x-text="message"></p>
            <button @click="show = false" class="text-xl font-semibold leading-none text-current opacity-70 hover:opacity-100">&times;</button>
        </div>
    </div>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        {{-- Partial nav --}}
        <livewire:layout.navigation />

        @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
