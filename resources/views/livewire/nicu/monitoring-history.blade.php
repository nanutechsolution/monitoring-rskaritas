<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 sm:p-6
                border-l-4 border-primary-600 dark:border-primary-500
                flex flex-wrap sm:flex-nowrap justify-between items-center gap-4">
        {{-- Info Pasien (Font lebih kecil di mobile) --}}
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="flex-shrink-0 bg-primary-50 dark:bg-primary-900 rounded-full p-2 sm:p-3 hidden sm:flex">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.22-.1.447-.1.679 0 .414.158.79.41 1.06M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 2.25h.008v.008H12v-.008Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.885 3.424A13.473 13.473 0 0 0 12 3c.31 0 .614.02 1.115.058M6.836 10.323c.032.218.047.44.047.668 0 .41-.14 1.08-.4 1.63M2.25 12c0 .31.02.614.058 1.115m18.267-1.115a13.473 13.473 0 0 1-.058 1.115M17.164 10.323c-.032.218-.047.44-.047.668 0 .41.14 1.08.4 1.63" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-sm sm:text-base font-semibold text-primary-700 dark:text-primary-300">
                    Riwayat Monitoring NICU
                </h2>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $pasien->nm_pasien ?? 'Pasien N/A' }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    RM: <strong>{{ $pasien->no_rkm_medis ?? '-' }}</strong> |
                    No. Rawat: <strong>{{ $noRawat }}</strong>
                </p>
            </div>
        </div>

        {{-- Tombol "Tambah Catatan" (Full-width di mobile, auto di desktop) --}}
        <div class="w-full sm:w-auto flex-shrink-0">
            <a href="{{ route('patient.monitor', ['no_rawat' => str_replace('/', '_', $noRawat)]) }}" wire:navigate
               class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2
                     bg-primary-600 text-white font-semibold rounded-lg shadow-md
                     hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600
                     transition duration-150 ease-in-out text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah Catatan (Hari Ini)
            </a>
        </div>
    </div>

    {{-- 2. Flash Message (Subtle) --}}
    @if (session()->has('success'))
    <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-600 text-green-800 dark:text-green-200 p-4 rounded-lg shadow-sm" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- 3. Desktop List (Sudah bagus, tetap dipertahankan) --}}
    <div class="hidden md:block space-y-3">
        @forelse ($cycles as $cycle)
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl
                    hover:shadow-lg transition-shadow duration-200
                    p-4 flex items-center justify-between space-x-4
                    border border-gray-100 dark:border-gray-700">

            <div class="flex-1 min-w-0">
                <p class="text-base font-semibold text-primary-700 dark:text-primary-300">
                    {{ $cycle->start_time->format('l, d M Y') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                    Siklus: {{ $cycle->start_time->format('H:i') }} s/d {{ $cycle->end_time->format('H:i, d M Y') }}
                </p>
            </div>

            <div class="flex-shrink-0 text-left w-48">
                <span class="text-xs text-gray-500 dark:text-gray-400">Cairan Hilang (IWL)</span>
                <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                    {{ $cycle->daily_iwl !== null ? number_format($cycle->daily_iwl, 0) . ' ml' : 'N/A' }}
                </p>
            </div>

            @php
                $balance = $cycle->calculated_balance_24h;
                $balanceText = 'N/A';
                $balanceColor = 'text-gray-900 dark:text-gray-100'; // Default
                if ($balance !== null) {
                    $balanceValue = number_format(abs($balance), 0);
                    if ($balance > 0) {
                        $balanceText = '+ ' . $balanceValue . ' ml';
                        $balanceColor = 'text-primary-600 dark:text-primary-400'; // Biru
                    } elseif ($balance < 0) {
                        $balanceText = '- ' . $balanceValue . ' ml';
                        $balanceColor = 'text-danger-600 dark:text-danger-400'; // Merah
                    } else {
                        $balanceText = '0 ml';
                        $balanceColor = 'text-green-600 dark:text-green-400'; // Hijau
                    }
                }
            @endphp
            <div class="flex-shrink-0 text-left w-48">
                <span class="text-xs text-gray-500 dark:text-gray-400">Sisa Cairan (24j)</span>
                <p class="text-lg font-bold {{ $balanceColor }}">
                    {{ $balanceText }}
                </p>
            </div>

            <div class="flex-shrink-0 text-right space-x-2">
                <a href="{{ route('monitoring.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}"
                   target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Cetak
                </a>
                <a href="{{ route('patient.monitor', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}"
                   wire:navigate
                   class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded-lg text-xs font-semibold hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition">
                    Buka Catatan
                </a>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat monitoring NICU untuk pasien ini.</p>
        </div>
        @endforelse
    </div>

    {{-- 4. Mobile Card (Dibuat Jauh Lebih Ringkas) --}}
    <div class="md:hidden grid grid-cols-1 gap-3">
        @forelse ($cycles as $cycle)

        {{-- Logika Warna --}}
        @php
            $balance = $cycle->calculated_balance_24h;
            $balanceText = 'N/A';
            $balanceColor = 'text-gray-900 dark:text-gray-100'; // Default
            if ($balance !== null) {
                $balanceValue = number_format(abs($balance), 0);
                if ($balance > 0) {
                    $balanceText = '+ ' . $balanceValue . ' ml';
                    $balanceColor = 'text-primary-600 dark:text-primary-400';
                } elseif ($balance < 0) {
                    $balanceText = '- ' . $balanceValue . ' ml';
                    $balanceColor = 'text-danger-600 dark:text-danger-400';
                } else {
                    $balanceText = '0 ml';
                    $balanceColor = 'text-green-600 dark:text-green-400';
                }
            }
        @endphp

        <div class="relative">
            {{-- Kartu Utama (dapat diklik) - Padding & Font diperkecil --}}
            <a href="{{ route('patient.monitor', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}"
               wire:navigate
               class="block bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4
                      border border-gray-100 dark:border-gray-700
                      hover:shadow-xl hover:border-primary-300 dark:hover:border-primary-600
                      transition-all duration-200">

                {{-- Info Tanggal --}}
                <div>
                    <p class="text-base font-bold text-primary-700 dark:text-primary-300">
                        {{ $cycle->start_time->format('l, d M Y') }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ({{ $cycle->start_time->format('H:i') }} s/d {{ $cycle->end_time->format('H:i, d M Y') }})
                    </p>
                </div>

                {{-- Data (Font diperkecil) --}}
                <div class="grid grid-cols-2 gap-4 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Sisa Cairan (24j)</span>
                        <p class="text-lg font-bold {{ $balanceColor }}">
                            {{ $balanceText }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Cairan Hilang (IWL)</span>
                        <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                            {{ $cycle->daily_iwl !== null ? number_format($cycle->daily_iwl, 0) . ' ml' : 'N/A' }}
                        </p>
                    </div>
                </div>
            </a>

            <a href="{{ route('monitoring.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}"
               target="_blank"
               class="absolute top-3 right-3 p-1.5 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                      rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition"
               title="Cetak Laporan">
               <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0c1.091 0 1.981.84 1.981 1.875m-1.981-1.875A1.875 1.875 0 0 0 16.04 16.125m1.98 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 18 19.875m-1.875-1.875A1.875 1.875 0 0 0 14.25 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 16.125 18m-1.875-1.875A1.875 1.875 0 0 0 12.375 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 14.25 18M12 19.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 12 19.875m-1.875-1.875A1.875 1.875 0 0 0 8.25 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 10.125 18m-1.875-1.875A1.875 1.875 0 0 0 6.375 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 8.25 18m-1.875-1.875A1.875 1.875 0 0 0 4.5 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 6.375 18m-1.875-1.875A1.875 1.875 0 0 0 2.625 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 4.5 18m-1.875-1.875a1.875 1.875 0 0 0-1.875 1.875v.168a1.875 1.875 0 0 0 1.875 1.875h.328a1.875 1.875 0 0 0 1.875-1.875v-.168A1.875 1.875 0 0 0 2.625 18m0 0A1.875 1.875 0 0 1 .75 16.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 .75 19.875v-.168A1.875 1.875 0 0 1 2.625 18m0 0A1.875 1.875 0 0 0 .75 16.125m0 0A1.875 1.875 0 0 1 2.625 14.25m0 0A1.875 1.875 0 0 0 .75 12.375m0 0A1.875 1.875 0 0 1 2.625 10.5m0 0A1.875 1.875 0 0 0 .75 8.625m0 0A1.875 1.875 0 0 1 2.625 6.75m0 0A1.875 1.875 0 0 0 .75 4.875m0 0A1.875 1.875 0 0 1 2.625 3m0 0A1.875 1.875 0 0 0 .75 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168A1.875 1.875 0 0 1 2.625 5.125h-.328A1.875 1.875 0 0 1 .75 3.25v-.168A1.875 1.875 0 0 1 2.625 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 2.625 3.25v-.168A1.875 1.875 0 0 1 4.5 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 4.5 3.25v-.168A1.875 1.875 0 0 1 6.375 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 6.375 3.25v-.168A1.875 1.875 0 0 1 8.25 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 8.25 3.25v-.168A1.875 1.875 0 0 1 10.125 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 10.125 3.25v-.168A1.875 1.875 0 0 1 12 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 12 3.25v-.168A1.875 1.875 0 0 1 14.25 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 14.25 3.25v-.168A1.875 1.875 0 0 1 16.125 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 16.125 3.25v-.168A1.875 1.875 0 0 1 18 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 18 3.25v-.168A1.875 1.875 0 0 1 19.875 1.125m1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328A1.875 1.875 0 0 1 19.875 3.25v-.168A1.875 1.875 0 0 1 21.75 1.125m-1.875 1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328a1.875 1.875 0 0 1-1.875-1.875v-.168A1.875 1.875 0 0 1 19.875 3m0 0A1.875 1.875 0 0 0 21.75 1.125m-1.875 1.875A1.875 1.875 0 0 1 21.75 4.875m0 0A1.875 1.875 0 0 0 19.875 6.75m0 0A1.875 1.875 0 0 1 21.75 8.625m0 0A1.875 1.875 0 0 0 19.875 10.5m0 0A1.875 1.875 0 0 1 21.75 12.375m0 0A1.875 1.875 0 0 0 19.875 14.25m0 0A1.875 1.875 0 0 1 21.75 16.125m0 0A1.875 1.875 0 0 0 19.875 18m0 0A1.875 1.875 0 0 1 21.75 19.875m0 0A1.875 1.875 0 0 0 19.875 21.75m0 0A1.875 1.875 0 0 1 21.75 22.875m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 19.875 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 18 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 16.125 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 14.25 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 12.375 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 10.125 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 8.25 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 6.375 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 4.5 21.75m-1.875-1.875a1.875 1.875 0 0 0 1.875 1.875v-.168a1.875 1.875 0 0 0-1.875-1.875h-.328a1.875 1.875 0 0 0-1.875 1.875v.168A1.875 1.875 0 0 0 2.625 21.75m0 0a1.875 1.875 0 0 1-1.875-1.875v-.168a1.875 1.875 0 0 1 1.875-1.875h.328a1.875 1.875 0 0 1 1.875 1.875v.168a1.875 1.875 0 0 1-1.875 1.875h-.328Z" />
               </svg>
            </a>
        </div>

        @empty
        {{-- Kartu Kosong (Minimalis) --}}
        <div class="col-span-full p-10 text-center text-gray-500 dark:text-gray-400
                    bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Catatan</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai monitoring dengan menekan tombol "Tambah Catatan (Hari Ini)".</p>
        </div>
        @endforelse
    </div>

</div>
