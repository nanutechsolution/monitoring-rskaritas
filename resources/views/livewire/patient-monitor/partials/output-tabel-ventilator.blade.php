@props(['records', 'title' => 'Data Ventilator & Monitor'])

@php
use Carbon\Carbon;

// --- Logika PHP Anda (Sudah Benar) ---
$sections = [
    'Spontan' => [
        'spontan_fio2' => 'FiO2',
        'spontan_flow' => 'Flow'
    ],
    'CPAP' => [
        'cpap_fio2' => 'FiO2',
        'cpap_flow' => 'Flow',
        'cpap_peep' => 'PEEP',
    ],
    'HFO' => [
        'hfo_fio2' => 'FiO2',
        'hfo_frekuensi' => 'Frekuensi',
        'hfo_map' => 'MAP',
        'hfo_amplitudo' => 'Amplitudo',
        'hfo_it' => 'I:T',
    ],
    'Monitor' => [
        'monitor_mode' => 'Mode',
        'monitor_fio2' => 'FiO2',
        'monitor_peep' => 'PEEP',
        'monitor_pip' => 'PIP',
        'monitor_tv_vte' => 'TV/VTE',
        'monitor_rr_spontan' => 'RR Spontan',
        'monitor_p_max' => 'P Max',
        'monitor_ie' => 'I:E',
    ],
];

$userInfoMap = [];
foreach ($records as $r) {
    $jam = Carbon::parse($r->record_time)->format('H:i');
    $userInfoMap[$jam] = [
        'nama' => $r->author_name,
        'nik' => $r->pegawai->nik ?? '-',
        'recorded_at' => Carbon::parse($r->record_time)->format('d/m H:i'),
    ];
}

// --- Kelas Helper untuk Tema ---
$headerBg = 'bg-gray-100 dark:bg-gray-700';
$headerText = 'text-gray-700 dark:text-gray-300';
$headerStickyBg = 'bg-gray-100 dark:bg-gray-700';

$rowBg = 'bg-white dark:bg-gray-800';
$rowBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
$rowStickyBg = 'bg-white dark:bg-gray-800';
$rowStickyBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
$rowHover = 'hover:bg-primary-50 dark:hover:bg-gray-600 dark:hover:bg-opacity-50';

$border = 'border dark:border-gray-600';
@endphp

<div class="space-y-6">
    @foreach($sections as $sectionTitle => $columns)
        @php
            $sectionRecords = $records->filter(function($record) use ($columns) {
                foreach($columns as $field => $label) {
                    if(!empty($record->$field)) return true;
                }
                return false;
            });

            if($sectionRecords->isEmpty()) continue;

            $jamList = $sectionRecords->map(fn($r) => Carbon::parse($r->record_time)->format('H:i'))
                ->unique()
                ->sort()
                ->values();
        @endphp

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700 p-4">
            <h4 class="text-md font-semibold mb-3 text-primary-700 dark:text-primary-300">{{ $sectionTitle }}</h4>

            <div class="overflow-x-auto">
                <table class="min-w-max {{ $border }} border-gray-200 text-sm">
                    <thead class="{{ $headerBg }} sticky top-0 z-10 {{ $headerText }}">
                        <tr>
                            <th class="{{ $border }} px-2 py-1 text-left sticky left-0 {{ $headerStickyBg }} z-10">Parameter</th>
                            @foreach($jamList as $jam)
                                @php $u = $userInfoMap[$jam] ?? null; @endphp
                                <th class="{{ $border }} px-2 py-1 text-center relative group">
                                    <div class="flex flex-col items-center">
                                        <span>{{ $jam }}</span>
                                        <span class="text-[11px] text-gray-600 dark:text-gray-400 font-medium">{{ $u['nama'] ?? '-' }}</span>
                                    </div>
                                    @if($u)
                                    <div class="absolute hidden group-hover:block
                                                bg-gray-800 dark:bg-gray-900 text-white
                                                text-xs rounded-lg px-2 py-1 shadow-md
                                                -translate-x-1/2 left-1/2 mt-1 z-20">
                                        <div><b>{{ $u['nama'] }}</b></div>
                                        <div>NIK: {{ $u['nik'] }}</div>
                                        <div>Input: {{ $u['recorded_at'] }}</div>
                                    </div>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @foreach($columns as $field => $label)
                            <tr class="{{ $loop->even ? $rowBgAlt : $rowBg }} {{ $rowHover }}">
                                <td class="{{ $border }} px-2 py-1 font-medium sticky left-0 z-10
                                           {{ $loop->even ? $rowStickyBgAlt : $rowStickyBg }}
                                           text-gray-900 dark:text-gray-100">
                                    {{ $label }}
                                </td>
                                @foreach($jamList as $jam)
                                    @php
                                        $record = $sectionRecords->first(fn($r) => Carbon::parse($r->record_time)->format('H:i') === $jam);
                                        $value = $record->$field ?? '-';
                                        $highlight = '';
                                        if(is_numeric($value)) {
                                            // DIUBAH: Menggunakan 'danger' untuk highlight
                                            if(in_array($field, ['spontan_fio2','cpap_fio2','hfo_fio2','monitor_fio2']) && $value > 60) $highlight = 'text-danger-600 dark:text-danger-400 font-semibold';
                                            if(in_array($field, ['cpap_peep','monitor_peep']) && $value > 8) $highlight = 'text-danger-600 dark:text-danger-400 font-semibold';
                                            if($field == 'monitor_rr_spontan' && ($value < 30 || $value > 60)) $highlight = 'text-danger-600 dark:text-danger-400 font-semibold';
                                        }
                                    @endphp
                                    <td class="{{ $border }} px-2 py-1 text-center {{ $highlight }}">{{ $value }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($records->isEmpty())
        <div class="text-gray-500 dark:text-gray-400 text-center">{{ $emptyText }}</div>
    @endif
</div>
