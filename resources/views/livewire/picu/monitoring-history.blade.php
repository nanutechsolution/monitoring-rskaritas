<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- Header Pasien --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 sm:p-8
                flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6
                border-l-4 border-primary-600 dark:border-primary-500">

        {{-- Info Judul & Pasien --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-6 w-full sm:w-auto">
            {{-- Icon --}}
            <div class="flex-shrink-0 bg-primary-50 dark:bg-primary-900 rounded-full p-3 hidden sm:flex">
                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>

            <div class="flex flex-col space-y-1">
                <h2 class="text-lg sm:text-xl font-semibold text-primary-700 dark:text-primary-300">
                    Riwayat Monitoring 24 Jam
                </h2>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                   PEDIATRIC INTENSIVE CARE UNIT (PICU)
                </h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">
                    Monitoring & Catatan Harian Pasien
                </p>
                <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mt-1">
                    <span class="font-semibold">Nama:</span> {{ $pasien->nm_pasien ?? 'Pasien N/A' }} |
                    <span class="font-semibold">RM:</span> {{ $pasien->no_rkm_medis ?? '-' }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $noRawat }}
                </p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-3 mt-4 sm:mt-0 w-full sm:w-auto">
            @if($hasOngoingCycle)
            <button disabled class="flex-1 sm:flex-auto px-4 py-2 bg-gray-300 text-gray-500 font-semibold rounded-lg shadow cursor-not-allowed text-sm">
                Tambah Catatan (Hari Ini)
            </button>
            @else
            <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $noRawat)]) }}" wire:navigate
               class="flex-1 sm:flex-auto px-4 py-2 bg-primary-600 text-white font-semibold rounded-lg shadow hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition text-sm text-center">
                Tambah Catatan (Hari Ini)
            </a>
            @endif
        </div>
    </div>

    {{-- Siklus Desktop --}}
    <div class="hidden md:block space-y-3 mt-4">
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
            } elseif ($balance < 0) {
                $balanceText = '- ' . $balanceValue . ' ml';
                $balanceColor = 'text-danger-600 dark:text-danger-400';
            } else {
                $balanceText = '0 ml';
                $balanceColor = 'text-green-600 dark:text-green-400';
            }
        }
        $isCycleFinished = $cycle->end_time->isPast();
        $isCycleOngoing  = !$isCycleFinished;
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl hover:shadow-lg transition-shadow duration-200
                    p-4 flex items-center justify-between space-x-4 border border-gray-100 dark:border-gray-700">

            <div class="flex-1 min-w-0">
                <p class="text-base font-semibold text-primary-700 dark:text-primary-300">
                    {{ $cycle->start_time->format('l, d M Y') }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                    {{ $cycle->start_time->format('H:i') }} s/d {{ $cycle->end_time->format('H:i, d M Y') }}
                    <span class="ml-2 px-2 py-1 text-xs rounded
                        {{ $isCycleOngoing ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                        {{ $isCycleOngoing ? 'Sedang Berjalan' : 'Selesai' }}
                    </span>
                </p>
            </div>

            <div class="flex-shrink-0 text-left w-48">
                <span class="text-xs text-gray-400">Cairan Hilang (IWL)</span>
                <p class="text-lg font-bold text-purple-600">
                    {{ $cycle->daily_iwl !== null ? number_format($cycle->daily_iwl, 0) . ' ml' : 'N/A' }}
                </p>
            </div>

            <div class="flex-shrink-0 text-left w-48">
                <span class="text-xs text-gray-400">Sisa Cairan (24j)</span>
                <p class="text-lg font-bold {{ $balanceColor }}">
                    {{ $balanceText }}
                </p>
            </div>

            <div class="flex-shrink-0 text-right space-x-2">
                <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-xs font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Cetak
                </a>

                @if(!$isCycleFinished)
                <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}" wire:navigate
                   class="inline-flex items-center px-3 py-2 bg-primary-600 text-white rounded-lg text-xs font-semibold hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition">
                    Buka Catatan
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-10 text-center border border-gray-100 dark:border-gray-700 text-gray-500">
            Belum ada riwayat monitoring NICU untuk pasien ini.
        </div>
        @endforelse
    </div>

    {{-- Mobile View --}}
    <div class="md:hidden grid grid-cols-1 gap-3 mt-4">
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
            } elseif ($balance < 0) {
                $balanceText = '- ' . $balanceValue . ' ml';
                $balanceColor = 'text-danger-600 dark:text-danger-400';
            } else {
                $balanceText = '0 ml';
                $balanceColor = 'text-green-600 dark:text-green-400';
            }
        }
        $isCycleFinished = $cycle->end_time->isPast();
        $isCycleOngoing  = !$isCycleFinished;
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <p class="text-base font-semibold text-primary-700 dark:text-primary-300">
                    {{ $cycle->start_time->format('d M') }}
                </p>
                <span class="px-2 py-1 text-xs rounded
                    {{ $isCycleOngoing ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ $isCycleOngoing ? 'Sedang Berjalan' : 'Selesai' }}
                </span>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ $cycle->start_time->format('H:i') }} - {{ $cycle->end_time->format('H:i') }}
            </p>

            <div class="grid grid-cols-2 gap-4 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <span class="text-xs font-medium text-gray-400 dark:text-gray-400">Sisa Cairan (24j)</span>
                    <p class="text-lg font-bold {{ $balanceColor }}">
                        {{ $balanceText }}
                    </p>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-400 dark:text-gray-400">Cairan Hilang (IWL)</span>
                    <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                        {{ $cycle->daily_iwl !== null ? number_format($cycle->daily_iwl, 0) . ' ml' : 'N/A' }}
                    </p>
                </div>
            </div>

            <div class="mt-3 flex gap-2">
                <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'cycle_id' => $cycle->id]) }}" target="_blank"
                   class="flex-1 text-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Cetak
                </a>
                @if(!$isCycleFinished)
                <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $cycle->no_rawat), 'date' => $cycle->start_time->format('Y-m-d')]) }}" wire:navigate
                   class="flex-1 text-center px-3 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600 transition">
                    Buka Catatan
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full p-10 text-center text-gray-500 dark:text-gray-400
                    bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
            Belum ada catatan. Mulai monitoring dengan menekan tombol "Tambah Catatan (Hari Ini)".
        </div>
        @endforelse
    </div>

</div>
