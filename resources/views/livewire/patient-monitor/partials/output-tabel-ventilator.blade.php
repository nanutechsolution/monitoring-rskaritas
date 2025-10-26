@props([
    'title' => 'Data Ventilator & Monitor',
    'records' => $records,
    'emptyText' => 'Belum ada data ventilator/monitor.'
])

@php
use Carbon\Carbon;

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

// Siapkan info user per jam
$userInfoMap = [];
foreach ($records as $r) {
    $jam = Carbon::parse($r->record_time)->format('H:i');
    $userInfoMap[$jam] = [
        'nama' => $r->author_name,
        'nik' => $r->pegawai->nik ?? '-',
        'recorded_at' => Carbon::parse($r->record_time)->format('d/m H:i'),
    ];
}
@endphp

<div class="space-y-6">
    @foreach($sections as $sectionTitle => $columns)
        @php
            // Filter record hanya yang ada data untuk mode ini
            $sectionRecords = $records->filter(function($record) use ($columns) {
                foreach($columns as $field => $label) {
                    if(!empty($record->$field)) return true;
                }
                return false;
            });

            if($sectionRecords->isEmpty()) continue;

            // Ambil jam unik yang ada datanya untuk mode ini
            $jamList = $sectionRecords->map(fn($r) => Carbon::parse($r->record_time)->format('H:i'))
                ->unique()
                ->sort()
                ->values();
        @endphp

        <div class="bg-white shadow-sm  overflow-x-auto p-4">
            <h4 class="text-md font-semibold mb-3">{{ $sectionTitle }}</h4>
            <table class="min-w-max border border-gray-200 text-sm">
                <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th class="border px-2 py-1 text-left sticky left-0 bg-gray-100 z-10">Parameter</th>
                        @foreach($jamList as $jam)
                            @php $u = $userInfoMap[$jam] ?? null; @endphp
                            <th class="border px-2 py-1 text-center relative group">
                                <div class="flex flex-col items-center">
                                    <span>{{ $jam }}</span>
                                    <span class="text-[11px] text-gray-600 font-medium">{{ $u['nama'] ?? '-' }}</span>
                                </div>
                                @if($u)
                                <div class="absolute hidden group-hover:block bg-gray-800 text-white text-xs rounded-lg px-2 py-1 shadow-md -translate-x-1/2 left-1/2 mt-1 z-20">
                                    <div><b>{{ $u['nama'] }}</b></div>
                                    <div>NIK: {{ $u['nik'] }}</div>
                                    <div>Input: {{ $u['recorded_at'] }}</div>
                                </div>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($columns as $field => $label)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                            <td class="border px-2 py-1 font-medium sticky left-0 bg-white z-10">{{ $label }}</td>
                            @foreach($jamList as $jam)
                                @php
                                    // Ambil record sesuai jam
                                    $record = $sectionRecords->first(fn($r) => Carbon::parse($r->record_time)->format('H:i') === $jam);
                                    $value = $record->$field ?? '-';
                                    $highlight = '';
                                    if(is_numeric($value)) {
                                        if(in_array($field, ['spontan_fio2','cpap_fio2','hfo_fio2','monitor_fio2']) && $value > 60) $highlight = 'text-red-600 font-semibold';
                                        if(in_array($field, ['cpap_peep','monitor_peep']) && $value > 8) $highlight = 'text-red-600 font-semibold';
                                        if($field == 'monitor_rr_spontan' && ($value < 30 || $value > 60)) $highlight = 'text-red-600 font-semibold';
                                    }
                                @endphp
                                <td class="border px-2 py-1 text-center {{ $highlight }}">{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    @if($records->isEmpty())
        <div class="text-gray-500 text-center">{{ $emptyText }}</div>
    @endif
</div>
