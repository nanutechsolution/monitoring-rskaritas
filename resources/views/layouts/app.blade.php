<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Skrip Anti-Flicker Dark Mode (Sudah Benar) -->
    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Styles & Scripts Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">

        <!-- Skrip Dark Mode Toggle (Sudah Benar) -->
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

        <livewire:layout.navigation />

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- ================================== -->
        <!--     KOMPONEN TOAST GLOBAL          -->
        <!-- (Dipindah ke dalam div utama)      -->
        <!-- ================================== -->
        <div
            x-data="{
                visible: false,
                message: '',
                type: 'success',
                timeout: null,
                showToast(event) {
                    this.type = event.detail.type || 'success';
                    this.message = event.detail.message || 'Tidak ada pesan.';
                    this.visible = true;
                    if (this.timeout) clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => this.visible = false, 5000);
                }
            }"
            @show-toast.window="showToast($event)"
            style="display: none;"
            x-show="visible"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed bottom-4 right-4 z-50 w-full max-w-xs"
        >
            <div
                class="flex items-center p-4 rounded-lg shadow-lg text-white"
                :class="{
                    'bg-green-600 dark:bg-green-700': type === 'success',
                    'bg-red-600 dark:bg-red-700': type === 'danger',
                    'bg-blue-600 dark:bg-blue-700': type === 'info'
                }"
            >
                <!-- Ikon -->
                <div class="flex-shrink-0">
                    <svg x-show="type === 'success'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <svg x-show="type === 'danger'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <svg x-show="type === 'info'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <!-- Pesan -->
                <div class="ml-3 text-sm font-medium" x-text="message"></div>
                <!-- Tombol Close -->
                <button @click="visible = false" class="ml-auto -mx-1.5 -my-1.5 p-1.5 rounded-full inline-flex items-center justify-center hover:bg-white hover:bg-opacity-20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Skrip Eksternal -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@2.2.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

</body>
</html>
