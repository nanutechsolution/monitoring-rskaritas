@props(['records' => $records, 'title' => 'Data Observasi Tercatat'])

@php
use Carbon\Carbon;

$allJamList = $records->pluck('record_time')
->map(fn($r) => Carbon::parse($r)->format('H:i'))
->unique()
->sortDesc()
->values();

$categories = [
'Parameter Vital' => [
'Temp Inkubator' => 'temp_incubator',
'Temp Skin' => 'temp_skin',
'Heart Rate' => 'hr',
'Respiratory Rate' => 'rr',
'Tensi' => 'tensi',
],
'Parameter Monitor' => [
'Sat O2' => 'sat_o2',
'Irama EKG' => 'irama_ekg',
'Skala Nyeri' => 'skala_nyeri',
'Humidifier Inkubator' => 'humidifier_inkubator',
],
'Parameter Klinis' => [
'Cyanosis' => 'cyanosis',
'Pucat' => 'pucat',
'Ikterus' => 'ikterus',
'CRT < 2 detik'=> 'crt_less_than_2',
    'Bradikardia' => 'bradikardia',
    'Stimulasi' => 'stimulasi',
    ],
    ];

    // Matriks data observasi
    $observasiMap = [];
    foreach($records as $r){
    $jam = Carbon::parse($r->record_time)->format('H:i');
    $observasiMap[$jam] = [
    'Temp Inkubator' => $r->temp_incubator ?? '-',
    'Temp Skin' => $r->temp_skin ?? '-',
    'Heart Rate' => $r->hr ?? '-',
    'Respiratory Rate' => $r->rr ?? '-',
    'Tensi' => ($r->blood_pressure_systolic ? $r->blood_pressure_systolic.'/'.$r->blood_pressure_diastolic : '-'),
    'Sat O2' => $r->sat_o2 ?? '-',
    'Irama EKG' => $r->irama_ekg ?? '-',
    'Skala Nyeri' => $r->skala_nyeri ?? '-',
    'Humidifier Inkubator' => $r->humidifier_inkubator ?? '-',
    'Cyanosis' => $r->cyanosis ? '+' : '-',
    'Pucat' => $r->pucat ? '+' : '-',
    'Ikterus' => $r->ikterus ? '+' : '-',
    'CRT < 2 detik'=> $r->crt_less_than_2 ? '+' : '-',
        'Bradikardia' => $r->bradikardia ? '+' : '-',
        'Stimulasi' => $r->stimulasi ? '+' : '-',
        ];
        }

        // Filter jam per kategori
        $categoryJamList = [];
        foreach($categories as $catName => $params){
        $categoryJamList[$catName] = collect($allJamList)->filter(function($jam) use ($params, $observasiMap){
        foreach($params as $p => $k){
        if(($observasiMap[$jam][$p] ?? '-') !== '-') return true;
        }
        return false;
        })->values();
        }

        $categoryBg = [
        'Parameter Vital' => 'bg-blue-50',
        'Parameter Monitor' => 'bg-yellow-50',
        'Parameter Klinis' => 'bg-green-50',
        ];
        @endphp

        <div class="space-y-6">
            @foreach($categories as $catName => $params)
            @if($categoryJamList[$catName]->isNotEmpty())
            <div class="overflow-x-auto border border-gray-100 shadow-sm">
                <div class="p-4 {{ $categoryBg[$catName] }}">
                    <h3 class="text-lg font-semibold mb-3">{{ $catName }}</h3>
                    <table class="min-w-max border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="sticky p-6 left-0 bg-gray-100 px-3 py-2 font-medium text-left z-10">Parameter</th>
                                @foreach($categoryJamList[$catName] as $jam)
                                <th class="border px-2 py-1 text-center font-medium">{{ $jam }}</th>
                                @endforeach
                            </tr>
                            <tr>
                                <th class="sticky left-0 bg-gray-100 px-3 py-1 text-xs text-gray-500">Petugas</th>
                                @foreach($categoryJamList[$catName] as $jam)
                                @php
                                $record = $records->first(fn($r) => Carbon::parse($r->record_time)->format('H:i') === $jam);
                                $author = $record?->author_name ?? '-';
                                $userId = $record?->id_user ?? '-';
                                $timestamp = $record?->record_time ? Carbon::parse($record->record_time)->translatedFormat('d M Y H:i') : null;
                                @endphp
                                <th class="border px-2 py-1 text-center text-xs italic text-gray-500 relative group">
                                    @if($author !== '-')
                                    <span class="cursor-help group-hover:underline">{{ $author }}</span>
                                    <div x-data="{ open: false }" x-show="open" x-transition @mouseenter="open = true" @mouseleave="open = false" class="hidden group-hover:block absolute z-20 bg-gray-800 text-white text-xs rounded-md p-2 w-40 -translate-x-1/2 left-1/2 bottom-full mb-1 shadow-lg">
                                        <p class="font-semibold">{{ $author }}</p>
                                        <p>ID: {{ $userId }}</p>
                                        @if($timestamp)
                                        <p class="text-[11px] mt-1">{{ $timestamp }}</p>
                                        @endif
                                    </div>
                                    @else
                                    -
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($params as $displayName => $key)
                            <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-50' }}">
                                <td class="sticky left-0 bg-white px-3 py-2 font-medium z-10">{{ $displayName }}</td>
                                @foreach($categoryJamList[$catName] as $jam)
                                @php
                                $val = $observasiMap[$jam][$displayName] ?? '-';
                                $cls = '';
                                if (is_numeric($val)) {
                                if (in_array($displayName, ['Temp Inkubator','Temp Skin']) && ($val < 36 || $val> 38)) $cls = 'text-red-600 font-semibold';
                                    if ($displayName == 'Heart Rate' && ($val < 100 || $val> 180)) $cls = 'text-red-600 font-semibold';
                                        if ($displayName == 'Respiratory Rate' && ($val < 30 || $val> 60)) $cls = 'text-red-600 font-semibold';
                                            if ($displayName == 'Sat O2' && $val < 90) $cls='text-red-600 font-semibold' ; } else { if ($val=='+' ) $cls='text-green-600 font-bold' ; if ($val=='-' ) $cls='text-red-600 font-bold' ; } @endphp <td class="border px-2 py-1 text-center {{ $cls }}">{{ $val }}</td>
                                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
            @endforeach
        </div>
