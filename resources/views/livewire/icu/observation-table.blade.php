<div class="bg-white shadow rounded-lg overflow-hidden">
  <div class="overflow-x-auto relative max-h-[80vh]">
        <table class="w-full text-sm text-left text-gray-500">
            {{-- HEADER (THEAD) - Kolom Dinamis Per Menit --}}
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left w-48 sticky left-0 bg-gray-100">Parameter</th>
                    {{-- Ganti loop $this->allRecords menjadi $this->uniqueTimestamps --}}
                    @foreach($this->uniqueTimestamps as $timestamp)
                        <th scope="col" class="px-4 py-2 text-center w-32 border-l">
                            <span class="font-bold text-blue-600 text-base">{{ \Carbon\Carbon::parse($timestamp)->format('H:i') }}</span><br>
                            <span class="text-xs text-gray-500 font-medium whitespace-nowrap">{{ $this->mergedRecordsPerMinute[$timestamp]->inputters }}</span>
                        </th>
                    @endforeach
                </tr>
            </thead>

            {{-- BODY (TBODY) - Baris Dinamis (SUDAH DIPERBAIKI) --}}
            <tbody>
                @php $currentGroup = ''; @endphp
                @foreach($allParameters as $param)
                    {{-- SECTION GRUP CAIRAN (DENGAN SUB-HEADER) --}}
                    @if ($param['group'] == 'CAIRAN')
                        @if ($currentGroup != 'CAIRAN')
                            @php $currentGroup = 'CAIRAN'; @endphp
                            {{-- Sub-Header CAIRAN MASUK --}}
                            <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 text-gray-800 text-left">CAIRAN MASUK</td></tr>
                            {{-- Sub-Sub-Header PARENTERAL --}}
                            <tr>
                                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm" style="padding-left: 10px; font-weight: bold; font-style: italic;">PARENTERAL</th>
                                <td colspan="{{ $this->uniqueTimestamps->count() }}" style="background-color: #fdfdfd;"></td>
                            </tr>
                            @forelse ($this->uniqueParenteralFluids as $fluidName)
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm" style="padding-left: 25px;">{{ $fluidName }}</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l">
                                            @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_parenteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                            @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input parenteral -</td><td colspan="{{ $this->uniqueTimestamps->count() }}"></td></tr>
                            @endforelse
                            {{-- Sub-Sub-Header ENTERAL --}}
                            <tr>
                                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm" style="padding-left: 10px; font-weight: bold; font-style: italic;">ENTERAL</th>
                                <td colspan="{{ $this->uniqueTimestamps->count() }}" style="background-color: #fdfdfd;"></td>
                            </tr>
                            @forelse ($this->uniqueEnteralFluids as $fluidName)
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm" style="padding-left: 25px;">{{ $fluidName }}</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l">
                                            @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->where('is_enteral', true)->where('jenis', $fluidName)->sum('volume'); @endphp
                                            @if($vol > 0) <span class="font-semibold text-green-700">{{ $vol }}</span> @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input enteral -</td><td colspan="{{ $this->uniqueTimestamps->count() }}"></td></tr>
                            @endforelse
                            {{-- Baris TOTAL Cairan Masuk --}}
                            <tr style="background-color: #f0f8ff;"><th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-gray-50 shadow-sm">TOTAL Cairan Masuk</th>
                                @foreach($this->uniqueTimestamps as $timestamp)
                                    <td class="px-4 py-2 text-center border-l">
                                        @php $totalIn = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_in)->sum('volume'); @endphp
                                        @if($totalIn > 0) <span class="font-bold text-green-700">{{ $totalIn }}</span> @endif
                                    </td>
                                @endforeach
                            </tr>
                            {{-- Sub-Header CAIRAN KELUAR --}}
                            <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 text-gray-800 text-left">CAIRAN KELUAR</td></tr>
                            @php $keluarTypes = ['Irigasi CM', 'Irigasi CK', 'Urine', 'NGT', 'Drain/WSD 1', 'Drain/WSD 2', 'Lainnya']; @endphp
                            @foreach($keluarTypes as $type)
                                <tr>
                                    <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm">{{ $type }}</th>
                                    @foreach($this->uniqueTimestamps as $timestamp)
                                        <td class="px-4 py-2 text-center border-l">
                                            @php $vol = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_out)->where('jenis', $type)->sum('volume'); @endphp
                                            @if($vol > 0) <span class="font-semibold text-red-700">{{ $vol }}</span> @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                             <tr style="background-color: #fff0f5;"><th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-gray-50 shadow-sm">TOTAL Cairan Keluar</th>
                                @foreach($this->uniqueTimestamps as $timestamp)
                                    <td class="px-4 py-2 text-center border-l">
                                        @php $totalOut = collect($this->mergedRecordsPerMinute[$timestamp]->fluids_out)->sum('volume'); @endphp
                                        @if($totalOut > 0) <span class="font-bold text-red-700">{{ $totalOut }}</span> @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    {{-- SECTION GRUP LAIN --}}
                    @else
                        @if ($param['group'] != $currentGroup)
                            <tr><td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 text-gray-800 text-left">{{ $param['group'] }}</td></tr>
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
                            <tr>
                                <th class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm">{{ $param['label'] }}</th>
                                @foreach($this->uniqueTimestamps as $timestamp)
                                    <td class="px-4 py-2 text-center border-l">
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
            <p class="text-gray-500 text-center py-10">Belum ada data inputan real-time untuk lembar ini.</p>
        @endif
    </div>
</div>

<div class="bg-white shadow rounded-lg overflow-hidden mt-6">
    <div class="p-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Ringkasan Cairan Per Jam</h3>
    </div>

    <div class="overflow-x-auto">
        {{-- Tabel 25 kolom (Label + 00-23) --}}
        <table class="w-full text-sm text-left text-gray-500" style="min-width: 1200px;">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left w-48">Parameter</th>
                    @foreach($this->hourlyFluidSummary as $hourData)
                    <th scope="col" class="px-2 py-3 text-center w-16">{{ $hourData['hour'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{-- Baris Total Masuk Per Jam --}}
                <tr class="bg-green-50 border-b">
                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                        Total Masuk (ml)
                    </th>
                    @foreach($this->hourlyFluidSummary as $hourData)
                    <td class="px-2 py-2 text-center font-semibold text-green-700">
                        {{ $hourData['masuk'] > 0 ? $hourData['masuk'] : '' }}
                    </td>
                    @endforeach
                </tr>
                {{-- Baris Total Keluar Per Jam --}}
                <tr class="bg-red-50 border-b">
                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap">
                        Total Keluar (ml)
                    </th>
                    @foreach($this->hourlyFluidSummary as $hourData)
                    <td class="px-2 py-2 text-center font-semibold text-red-700">
                        {{ $hourData['keluar'] > 0 ? $hourData['keluar'] : '' }}
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
