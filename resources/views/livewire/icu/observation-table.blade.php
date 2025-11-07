<div class="container mx-auto space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto relative max-h-[80vh]">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                {{-- HEADER (THEAD) --}}
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left w-48 sticky left-0 bg-gray-100 dark:bg-gray-700">Parameter</th>
                        @foreach($this->uniqueTimestamps as $timestamp)
                            <th scope="col" class="px-4 py-2 text-center w-32 border-l dark:border-l-gray-600">
                                <span class="font-bold text-primary-600 dark:text-primary-400 text-base">{{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}</span><br>
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">{{ $this->mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @php $currentGroup = ''; @endphp
                    @foreach($allParameters as $param)
                        @if ($param['group'] == 'CAIRAN')
                            @if ($currentGroup != 'CAIRAN')
                                @php $currentGroup = 'CAIRAN'; @endphp
                                <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">CAIRAN MASUK</td></tr>

                                {{-- Sub-Header PARENTERAL --}}
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm" style="padding-left: 10px; font-weight: bold; font-style: italic;">PARENTERAL</th>
                                    <td colspan="{{ $this->uniqueTimestamps->count() }}" class="bg-gray-50 dark:bg-gray-700 bg-opacity-50"></td>
                                </tr>
                                @forelse ($this->uniqueParenteralFluids as $fluidName)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm" style="padding-left: 25px;">{{ $fluidName }}</th>
                                        @foreach($this->uniqueTimestamps as $timestamp)
                                            <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                                @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_parenteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                                @if($vol > 0) <span class="font-semibold text-green-600 dark:text-green-400">{{ $vol }}</span> @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr><td class="px-4 py-1 sticky left-0 bg-white dark:bg-gray-800" style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input parenteral -</td><td colspan="{{ $this->uniqueTimestamps->count() }}" class="dark:bg-gray-800"></td></tr>
                                @endforelse

                                {{-- Sub-Header ENTERAL --}}
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm" style="padding-left: 10px; font-weight: bold; font-style: italic;">ENTERAL</th>
                                    <td colspan="{{ $this->uniqueTimestamps->count() }}" class="bg-gray-50 dark:bg-gray-700 bg-opacity-50"></td>
                                </tr>
                                @forelse ($this->uniqueEnteralFluids as $fluidName)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm" style="padding-left: 25px;">{{ $fluidName }}</th>
                                        @foreach($this->uniqueTimestamps as $timestamp)
                                            <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                                @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_enteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                                @if($vol > 0) <span class="font-semibold text-green-600 dark:text-green-400">{{ $vol }}</span> @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr><td class="px-4 py-1 sticky left-0 bg-white dark:bg-gray-800" style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input enteral -</td><td colspan="{{ $this->uniqueTimestamps->count() }}" class="dark:bg-gray-800"></td></tr>
                                @endforelse

                                {{-- Baris TOTAL Cairan Masuk --}}
                                <tr class="bg-primary-50 dark:bg-primary-900 dark:bg-opacity-50">
                                    <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-primary-50 dark:bg-primary-900 dark:bg-opacity-50 shadow-sm">TOTAL Cairan Masuk</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                            @php $totalIn = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->sum('volume'); @endphp
                                            @if($totalIn > 0) <span class="font-bold text-green-600 dark:text-green-400">{{ $totalIn }}</span> @endif
                                        </td>
                                    @endforeach
                                </tr>

                                {{-- Sub-Header CAIRAN KELUAR --}}
                                <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">CAIRAN KELUAR</td></tr>
                                @php $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2', 'Lainnya']; @endphp
                                @foreach($keluarTypes as $type)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                                        <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm">{{ $type }}</th>
                                        @foreach($this->uniqueTimestamps as $timestamp)
                                            <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                                @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_out)->where('jenis', $type)->sum('volume'); @endphp
                                                @if($vol > 0) <span class="font-semibold text-danger-600 dark:text-danger-400">{{ $vol }}</span> @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach

                                <tr class="bg-danger-50 dark:bg-danger-900 dark:bg-opacity-50">
                                    <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-danger-50 dark:bg-danger-900 dark:bg-opacity-50 shadow-sm">TOTAL Cairan Keluar</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                            @php $totalOut = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_out)->sum('volume'); @endphp
                                            @if($totalOut > 0) <span class="font-bold text-danger-600 dark:text-danger-400">{{ $totalOut }}</span> @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endif

                        {{-- SECTION GRUP LAIN --}}
                        @else
                            @if ($param['group'] != $currentGroup)
                                <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">{{ $param['group'] }}</td></tr>
                                @php $currentGroup = $param['group']; @endphp
                            @endif
                            @php
                                $key = $param['key'];
                                $hasData = $this->mergedRecordsPerMinute->contains(function ($mergedRecord) use ($key) {
                                    if ($key == 'tensi') { return $mergedRecord->tensi_sistol !== null; }
                                    elseif ($key == 'gcs') { return $mergedRecord->gcs_e !== null || $mergedRecord->gcs_v !== null || $mergedRecord->gcs_m !== null; }
                                    elseif ($key == 'pupil') { return $mergedRecord->pupil_left_size_mm !== null || $mergedRecord->pupil_right_size_mm !== null; }
                                    elseif (in_array($key, ['clinical_note', 'medication_administration'])) { return !empty($mergedRecord->{$key}); }
                                    else { return isset($mergedRecord->{$key}) && $mergedRecord->{$key} !== null; }
                                });
                            @endphp
                            @if($hasData)
                                <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                                    <th class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 shadow-sm">{{ $param['label'] }}</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l dark:border-l-gray-700">
                                            @php
                                                $record = $this->mergedRecordsPerMinute[$timestamp];
                                                $value = null;
                                                if ($key == 'tensi' && isset($record->tensi_sistol)) { $value = $record->tensi_sistol . '/' . $record->tensi_diastol; }
                                                elseif ($key == 'gcs' && (isset($record->gcs_e) || isset($record->gcs_v) || isset($record->gcs_m))) { $gcsTotal = $record->gcs_total ?? (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0)); $value = "E".($record->gcs_e ?? '-')."V".($record->gcs_v ?? '-')."M".($record->gcs_m ?? '-').($gcsTotal > 0 ? "($gcsTotal)" : ''); }
                                                elseif ($key == 'pupil' && (isset($record->pupil_left_size_mm) || isset($record->pupil_right_size_mm))) { $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-'); $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-'); $value = "{$left}|{$right}"; }
                                                elseif ($key == 'clinical_note' && !empty($record->clinical_note)) { $value = $record->clinical_note; }
                                                elseif ($key == 'medication_administration' && !empty($record->medication_administration)) { $value = $record->medication_administration; }
                                                elseif (isset($record->{$key}) && $record->{$key} !== null && !in_array($key, ['tensi_sistol','tensi_diastol','gcs_e','gcs_v','gcs_m','gcs_total','pupil_left_size_mm','pupil_left_reflex','pupil_right_size_mm','pupil_right_reflex','clinical_note','medication_administration','cairan_masuk_jenis','cairan_masuk_volume','cairan_keluar_jenis','cairan_keluar_volume','is_enteral','is_parenteral'])) { $value = $record->{$key}; }
                                            @endphp
                                            <span class="{!! $key == 'clinical_note' || $key == 'medication_administration' ? 'whitespace-pre-wrap' : 'whitespace-nowrap' !!}">{!! $value !!}</span>
                                        </td>
                                    @endforeach
                                </tr>
                            @endif
                        @endif
                    @endforeach
                </tbody>
            </table>
            @if($this->uniqueTimestamps->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-10">Belum ada data inputan real-time untuk lembar ini.</p>
            @endif
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mt-6 border border-gray-100 dark:border-gray-700">
        <div class="p-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ringkasan Cairan Per Jam</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" style="min-width: 1200px;">
                <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left w-48">Parameter</th>
                        @foreach($this->hourlyFluidSummary as $hourData)
                        <th scope="col" class="px-2 py-3 text-center w-16">{{ $hourData['hour'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    {{-- Baris Total Masuk Per Jam --}}
                    <tr class="bg-green-50 dark:bg-green-900 dark:bg-opacity-30">
                        <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            Total Masuk (ml)
                        </th>
                        @foreach($this->hourlyFluidSummary as $hourData)
                        <td class="px-2 py-2 text-center font-semibold text-green-700 dark:text-green-400">
                            {{ $hourData['masuk'] > 0 ? $hourData['masuk'] : '' }}
                        </td>
                        @endforeach
                    </tr>
                    {{-- Baris Total Keluar Per Jam --}}
                    <tr class="bg-danger-50 dark:bg-danger-900 dark:bg-opacity-30">
                        <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                            Total Keluar (ml)
                        </th>
                        @foreach($this->hourlyFluidSummary as $hourData)
                        <td class="px-2 py-2 text-center font-semibold text-danger-600 dark:text-danger-400">
                            {{ $hourData['keluar'] > 0 ? $hourData['keluar'] : '' }}
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
