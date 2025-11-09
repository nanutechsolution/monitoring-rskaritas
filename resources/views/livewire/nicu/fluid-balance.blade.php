<div class="space-y-6">
    @php
    // --- Kelas Helper untuk Tema ---
    $headerBg = 'bg-gray-100 dark:bg-gray-700';
    $headerText = 'text-gray-700 dark:text-gray-300';
    $rowBg = 'bg-white dark:bg-gray-800';
    $rowBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
    $border = 'border dark:border-gray-600';

    $balancePositiveText = 'text-primary-700 dark:text-primary-300';
    $balanceNegativeText = 'text-danger-600 dark:text-danger-400';
    @endphp

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3 text-primary-700 dark:text-primary-300">
                Ringkasan Balance Cairan per 3 Jam
            </h3>

            @if(empty($balancePer3Hours) || !$currentCycle)
            <div class="mt-4 text-center text-gray-500 dark:text-gray-400 py-6">
                Belum ada data cairan yang diinput untuk siklus ini.
            </div>
            @else
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="text-xs {{ $headerText }} uppercase {{ $headerBg }}">
                        <tr>
                            <th class="px-3 py-2 text-left {{ $border }}">Jam Blok</th>
                            <th class="px-3 py-2 text-center {{ $border }}">Total Masuk (CM)</th>
                            <th class="px-3 py-2 text-center {{ $border }}">Total Keluar (CK)</th>
                            <th class="px-3 py-2 text-center {{ $border }}">Balance Blok</th>
                            <th class="px-3 py-2 text-center {{ $border }}">Balance Kumulatif</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        {{-- Mulai kumulatif dari balance hari sebelumnya --}}
                        @php $cumulativeBalance = $previousBalance24h ?? 0; @endphp

                        @foreach($balancePer3Hours as $index => $summary)
                        @php
                        $cumulativeBalance += $summary['balance'];
                        $isShiftPagi = in_array($index, [0, 1]); // 06:00 - 12:00
                        $isShiftSiang = in_array($index, [2, 3]); // 12:00 - 18:00
                        @endphp
                        <tr class="{{ ($isShiftPagi || $isShiftSiang) ? $rowBg : $rowBgAlt }}">
                            <td class="px-3 py-2 font-medium {{ $border }} text-gray-900 dark:text-gray-100">{{ $summary['label'] }}</td>
                            <td class="px-3 py-2 text-center {{ $border }} text-green-600 dark:text-green-400 font-semibold">
                                {{ $summary['masuk'] > 0 ? $summary['masuk'] : '-' }}
                            </td>
                            <td class="px-3 py-2 text-center {{ $border }} text-danger-600 dark:text-danger-400 font-semibold">
                                {{ $summary['keluar'] > 0 ? $summary['keluar'] : '-' }}
                            </td>
                            <td class="px-3 py-2 text-center {{ $border }} font-bold {{ $summary['balance'] >= 0 ? $balancePositiveText : $balanceNegativeText }}">
                                {{ $summary['balance'] >= 0 ? '+' : '' }}{{ (float) $summary['balance'] }}
                            </td>
                            <td class="px-3 py-2 text-center {{ $border }} font-bold {{ $cumulativeBalance >= 0 ? 'text-gray-800 dark:text-gray-100' : $balanceNegativeText }}">
                                {{ $cumulativeBalance >= 0 ? '+' : '' }}{{ (float) $cumulativeBalance }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3 text-primary-700 dark:text-primary-300">
                Ringkasan Balance 24 Jam
            </h3>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kolom 1: Input IWL --}}
                <div class="space-y-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border dark:border-gray-600">
                    <label for="daily_iwl" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Estimasi IWL (ml/24j)
                    </label>
                    <input type="number" step="any" id="daily_iwl" wire:model="daily_iwl" @disabled(!$currentCycle || $isReadOnly) class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                              border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-700
                              text-gray-900 dark:text-gray-200
                              focus:border-primary-500 focus:ring-primary-500
                              disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-gray-800" placeholder="Masukkan IWL">
                    <button type="button" wire:click="saveDailyIwl" wire:loading.attr="disabled" @disabled(!$currentCycle || $isReadOnly) class="mt-2 w-full inline-flex justify-center rounded-md border border-transparent
                               bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm
                               hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800
                               disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="saveDailyIwl">Simpan IWL</span>
                        <span wire:loading wire:target="saveDailyIwl">Menyimpan...</span>
                    </button>
                    @error('daily_iwl') <span class="text-xs text-danger-600 dark:text-danger-400 mt-1">{{ $message }}</span> @enderror
                </div>

                {{-- Kolom 2: Total Intake/Output --}}
                <div class="space-y-4 pt-4 md:pt-0">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Intake (24j)</dt>
                        <dd class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($totalIntake24h, 0) }} ml
                        </dd>
                    </dl>
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Output (24j)</dt>
                        <dd class="mt-1 text-2xl font-semibold text-danger-600 dark:text-danger-400">
                            {{ number_format($totalOutput24h, 0) }} ml
                        </dd>
                    </dl>
                </div>

                {{-- Kolom 3: Total Balance --}}
                <div class="space-y-4 pt-4 md:pt-0">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Urine (24j)</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-700 dark:text-gray-200">
                            {{ number_format($totalUrine24h, 0) }} ml
                        </dd>
                    </dl>

                    @php
                    $balanceClass = $balance24h >= 0 ? $balancePositiveText : $balanceNegativeText;
                    @endphp
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Balance Cairan (24j)</dt>
                        <dd class="mt-1 text-3xl font-bold {{ $balanceClass }}">
                            {{ $balance24h >= 0 ? '+' : '' }}{{ number_format($balance24h, 0) }} ml
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
