@props([
    'title' => 'Riwayat Gas Darah',
    'bloodGasResults' => $bloodGasResults,
])

@php
use Carbon\Carbon;

// --- Logika PHP Anda (Sudah Benar) ---
$params = [
    'Gula Darah' => 'gula_darah', 'pH' => 'ph', 'PCO₂' => 'pco2', 'PO₂' => 'po2',
    'HCO₃' => 'hco3', 'BE' => 'be', 'SaO₂' => 'sao2'
];

$jamList = $bloodGasResults->pluck('taken_at')
    ->map(fn($t) => Carbon::parse($t)->format('H:i'))
    ->unique()
    ->sort()
    ->values();

// === KELAS HELPER UNTUK TEMA ===
$headerBg = 'bg-gray-100 dark:bg-gray-700';
$headerText = 'text-gray-700 dark:text-gray-300';
$headerStickyBg = 'bg-gray-100 dark:bg-gray-700';

$rowBg = 'bg-white dark:bg-gray-800';
$rowBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
$rowStickyBg = 'bg-white dark:bg-gray-800';
$rowStickyBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
$rowHover = 'hover:bg-primary-50 dark:hover:bg-gray-600 dark:hover:bg-opacity-50';

$border = 'border dark:border-gray-600';

// Kelas Highlight/Badge
$badgeDanger = 'bg-danger-100 dark:bg-danger-900 dark:bg-opacity-50 text-danger-800 dark:text-danger-200 font-semibold';
$badgeSuccess = 'bg-green-100 dark:bg-green-900 dark:bg-opacity-50 text-green-800 dark:text-green-200';
$badgeNeutral = 'text-gray-700 dark:text-gray-300';
$badgeEmpty = 'text-gray-400 dark:text-gray-500';

@endphp

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-primary-700 dark:text-primary-300 flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3 mb-3">
            <svg class="w-5 h-5 text-danger-600 dark:text-danger-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-6 3h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
            {{ $title }}
        </h3>

        @if($bloodGasResults->isEmpty())
            <p class="text-gray-500 dark:text-gray-400 text-center py-10">Belum ada data gas darah untuk siklus ini.</p>
        @else
        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-700">
            <table class="min-w-full border-collapse text-sm">
                <thead class="text-xs {{ $headerText }} uppercase {{ $headerBg }} sticky top-0 z-10">
                    <tr>
                        <th class="sticky left-0 {{ $headerStickyBg }} px-4 py-3 text-left font-semibold z-10 {{ $border }}">Parameter</th>
                        @foreach ($bloodGasResults as $result)
                        <th class="{{ $border }} px-4 py-1 text-center">
                            {{ \Carbon\Carbon::parse($result->taken_at)->format('H:i') }}<br>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-normal italic mt-0.5">{{ $result->author_name }}</span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($params as $label => $field)
                    <tr class="{{ $loop->even ? $rowBgAlt : $rowBg }} {{ $rowHover }}">
                        <td class="sticky left-0 px-4 py-2 font-semibold {{ $border }} z-10
                                   {{ $loop->even ? $rowStickyBgAlt : $rowStickyBg }}
                                   text-gray-900 dark:text-gray-100">
                            {{ $label }}
                        </td>
                        @foreach ($bloodGasResults as $result)
                        @php
                            $value = $result->$field;
                            $badge = $badgeNeutral; // Default
                            if($value !== null) {
                                if($field == 'gula_darah') $badge = $value > 140 ? $badgeDanger : $badgeSuccess;
                                elseif($field == 'ph') $badge = ($value < 7.35 || $value > 7.45) ? $badgeDanger : $badgeSuccess;
                                elseif($field == 'sao2') $badge = $value < 95 ? $badgeDanger : $badgeSuccess;
                            } else {
                                $value = '-';
                                $badge = $badgeEmpty;
                            }
                        @endphp
                        <td class="{{ $border }} px-4 py-2 text-center {{ $badge }}">{{ $value }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
