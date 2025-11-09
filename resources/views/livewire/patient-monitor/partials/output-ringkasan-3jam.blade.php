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

<!-- KARTU BARU: Ringkasan per 3 Jam -->
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3 text-primary-700 dark:text-primary-300">
            Ringkasan Balance Cairan per 3 Jam
        </h3>

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
                            // Tambahkan balance blok ini ke kumulatif
                            $cumulativeBalance += $summary['balance'];

                            // Tentukan grup shift untuk styling (misal: Pagi 06-12, Siang 12-18, Malam 18-06)
                            $isShiftPagi = in_array($index, [0, 1]);
                            $isShiftSiang = in_array($index, [2, 3]);
                            // $isShiftMalam = sisanya
                        @endphp

                        <tr class="{{ ($isShiftPagi || $isShiftSiang) ? $rowBg : $rowBgAlt }}">
                            <td class="px-3 py-2 font-medium {{ $border }} text-gray-900 dark:text-gray-100">{{ $summary['label'] }}</td>

                            <td class="px-3 py-2 text-center {{ $border }} text-green-600 dark:text-green-400 font-semibold">
                                {{ $summary['masuk'] > 0 ? $summary['masuk'] : '-' }}
                            </td>

                            <td class="px-3 py-2 text-center {{ $border }} text-danger-600 dark:text-danger-400 font-semibold">
                                {{ $summary['keluar'] > 0 ? $summary['keluar'] : '-' }}
                            </td>

                            <td class="px-3 py-2 text-center {{ $border }} font-bold
                                {{ $summary['balance'] >= 0 ? $balancePositiveText : $balanceNegativeText }}">
                                {{ $summary['balance'] >= 0 ? '+' : '' }}{{ $summary['balance'] }}
                            </td>

                            <td class="px-3 py-2 text-center {{ $border }} font-bold
                                {{ $cumulativeBalance >= 0 ? 'text-gray-800 dark:text-gray-100' : $balanceNegativeText }}">
                                {{ $cumulativeBalance >= 0 ? '+' : '' }}{{ $cumulativeBalance }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
