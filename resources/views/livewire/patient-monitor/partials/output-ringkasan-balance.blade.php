@php
    // --- Kelas Helper untuk Konsistensi ---
    $inputClasses = 'form-input py-1 px-2 text-sm w-20 rounded-md shadow-sm
                     border-gray-300 dark:border-gray-600
                     bg-white dark:bg-gray-700
                     text-gray-900 dark:text-gray-200
                     focus:border-primary-500 focus:ring-primary-500';

    $buttonClasses = 'text-xs bg-gray-200 dark:bg-gray-600 dark:text-gray-300
                      px-2 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-500
                      focus:outline-none focus:ring-2 focus:ring-primary-500
                      focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-colors';
@endphp

<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3 text-gray-800 dark:text-gray-100">
            Ringkasan Balance Cairan 24 Jam
        </h3>

        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
            <div>Total Masuk (CM): <span class="font-bold text-green-600 dark:text-green-400">{{ $totalIntake24h }} ml</span></div>
            <div>Total Keluar (CK): <span class="font-bold text-danger-600 dark:text-danger-400">{{ $totalOutput24h }} ml</span></div>

            <div>Produksi Urine: <span class="font-bold text-gray-800 dark:text-gray-100">{{ $totalUrine24h }} ml</span></div>

            <div class="flex items-center space-x-2">
                <label for="daily_iwl" class="whitespace-nowrap">IWL:</label>
                <input type="number" step="0.1" id="daily_iwl" wire:model.defer="daily_iwl" class="{{ $inputClasses }}">
                <button type="button" wire:click="saveDailyIwl" class="{{ $buttonClasses }}">Simpan</button>
            </div>

            <div class="col-span-1 sm:col-span-2 text-gray-600 dark:text-gray-400 mt-2">
                BC 24 Jam Sebelumnya:
                <span class="font-bold text-gray-800 dark:text-gray-200">
                    {{ $previousBalance24h !== null ? ($previousBalance24h >= 0 ? '+' : '') . $previousBalance24h . ' ml' : 'N/A' }}
                </span>
            </div>
        </div>

        <div class="mt-4 border-t dark:border-gray-700 pt-3 text-center text-sm sm:text-base text-gray-800 dark:text-gray-100">
            Balance Cairan 24 Jam:
            <span class="text-xl font-bold {{ $balance24h >= 0 ? 'text-green-600 dark:text-green-400' : 'text-danger-600 dark:text-danger-400' }}">
                {{ $balance24h >= 0 ? '+' : '' }}{{ $balance24h }} ml
            </span>
        </div>
    </div>
</div>
