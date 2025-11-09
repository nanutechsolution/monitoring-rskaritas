<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- Header Pasien --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 sm:p-6
                border-l-4 border-primary-600 dark:border-primary-500
                flex flex-wrap sm:flex-nowrap justify-between items-center gap-4">
        <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="flex-shrink-0 bg-primary-50 dark:bg-primary-900 rounded-full p-2 sm:p-3 hidden sm:flex">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.22-.1.447-.1.679 0 .414.158.79.41 1.06M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 2.25h.008v.008H12v-.008Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.885 3.424A13.473 13.473 0 0 0 12 3c.31 0 .614.02 1.115.058M6.836 10.323c.032.218.047.44.047.668 0 .41-.14 1.08-.4 1.63M2.25 12c0 .31.02.614.058 1.115m18.267-1.115a13.473 13.473 0 0 1-.058 1.115M17.164 10.323c-.032.218-.047.44-.047.668 0 .41.14 1.08.4 1.63" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-sm sm:text-base font-semibold text-primary-700 dark:text-primary-300">
                    Riwayat Monitoring PICU
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

        {{-- Tombol Tambah Catatan --}}
        <div class="w-full sm:w-auto flex-shrink-0">
            @if($hasOngoingCycle)
            @else
            <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $noRawat)]) }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center px-4 py-2
                         bg-primary-600 text-white font-semibold rounded-lg shadow-md
                         hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600
                         transition duration-150 ease-in-out text-sm">
                Tambah Catatan (Hari Ini)
            </a>
            @endif
        </div>
    </div>

    {{-- Desktop List --}}
    <div class="hidden md:block space-y-3">
        @forelse ($cycles as $cycle)
        @php
        $balance = $cycle->calculated_balance_24h;
        $balanceText = 'N/A';
        $balanceColor = 'text-gray-900 dark:text-gray-100';
        if ($balance !== null) {
        $balanceValue = number_format(abs($balance), 0);
        if ($balance > 0) {
        $balanceText = '+ ' . $balanceValue . ' ml';
        $balanceColor = 'text-primary-600 dark:text-primary-400';
        } elseif ($balance < 0) { $balanceText='- ' . $balanceValue . ' ml' ; $balanceColor='text-danger-600 dark:text-danger-400' ; } else { $balanceText='0 ml' ; $balanceColor='text-green-600 dark:text-green-400' ; } } $isCycleFinished=$cycle->end_time->isPast(); // Selesai
            $isCycleOngoing = !$isCycleFinished; // Sedang berjalan
            @endphp

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl
                    hover:shadow-lg transition-shadow duration-200
                    p-4 flex items-center justify-between space-x-4
                    border border-gray-100 dark:border-gray-700">

                <div class="flex-1 min-w-0">
                    <p class="text-base font-semibold text-primary-700 dark:text-primary-300">
                        {{ $cycle->start_time->format('l, d M Y') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                        {{ $cycle->start_time->format('H:i') }} s/d {{ $cycle->end_time->format('H:i, d M Y') }}
                        @if($isCycleOngoing)
                        <span class="ml-2 px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Sedang Berjalan</span>
                        @else
                        <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Selesai</span>
                        @endif
                    </p>
                </div>

                <div class="flex-shrink-0 text-left w-48">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Cairan Hilang (IWL)</span>
                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                        {{ $cycle->daily_iwl !== null ? number_format($cycle->daily_iwl, 0) . ' ml' : 'N/A' }}
                    </p>
                </div>

                <div class="flex-shrink-0 text-left w-48">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Sisa Cairan (24j)</span>
                    <p class="text-lg font-bold {{ $balanceColor }}">
                        {{ $balanceText }}
                    </p>
                </div>

                <div class="flex-shrink-0 text-right space-x-2">
                    {{-- Tombol Cetak --}}
                    <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}" target="_blank" class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        Cetak
                    </a>

                    {{-- Tombol Buka / Lihat Catatan --}}
                    @if($isCycleFinished)
                    @else
                    <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}" wire:navigate class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded-lg text-xs font-semibold hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition">
                        Buka Catatan
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 text-center border border-gray-100 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat monitoring PICU untuk pasien ini.</p>
            </div>
            @endforelse
    </div>

    {{-- Mobile View --}}
    <div class="md:hidden grid grid-cols-1 gap-3">
        @forelse($cycles as $cycle)
        @php
        $balance = $cycle->calculated_balance_24h;
        $balanceText = 'N/A';
        $balanceColor = 'text-gray-900 dark:text-gray-100';
        if ($balance !== null) {
        $balanceValue = number_format(abs($balance), 0);
        if ($balance > 0) {
        $balanceText = '+ ' . $balanceValue . ' ml';
        $balanceColor = 'text-primary-600 dark:text-primary-400';
        } elseif ($balance < 0) { $balanceText='- ' . $balanceValue . ' ml' ; $balanceColor='text-danger-600 dark:text-danger-400' ; } else { $balanceText='0 ml' ; $balanceColor='text-green-600 dark:text-green-400' ; } } $isCycleFinished=$cycle->end_time->isPast();
            $isCycleOngoing = !$isCycleFinished;
            @endphp

            <div class="relative">
                <div class="block bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4
                        border border-gray-100 dark:border-gray-700
                        hover:shadow-xl hover:border-primary-300 dark:hover:border-primary-600
                        transition-all duration-200">

                    <div class="flex justify-between items-center">
                        <p class="text-base font-bold text-primary-700 dark:text-primary-300">
                            {{ $cycle->start_time->format('d M') }}
                        </p>
                        @if($isCycleOngoing)
                        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">Sedang Berjalan</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Selesai</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $cycle->start_time->format('H:i') }} - {{ $cycle->end_time->format('H:i') }}
                    </p>

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

                    <div class="mt-3 flex space-x-2">
                        {{-- Tombol Desktop / Cetak --}}
                        <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}" target="_blank" class="flex-1 text-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Cetak
                        </a>

                        {{-- Tombol Buka / Lihat --}}
                        @if($isCycleFinished)
                        @else
                        <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}" wire:navigate class="flex-1 text-center px-3 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition">
                            Buka Catatan
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full p-10 text-center text-gray-500 dark:text-gray-400
                    bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Catatan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai monitoring dengan menekan tombol "Tambah Catatan (Hari Ini)".</p>
            </div>
            @endforelse
    </div>

</div>
