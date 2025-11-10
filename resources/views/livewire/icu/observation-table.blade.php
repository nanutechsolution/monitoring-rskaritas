<div class="mx-auto">
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mt-6 border border-gray-100 dark:border-gray-700">
    <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">⚖️ Ringkasan Balance Cairan</h3>

        {{-- [UPDATE] Filter Blok Waktu --}}
        <div class="flex items-center space-x-2">
            <label for="filter-duration" class="text-sm font-medium text-gray-700 dark:text-gray-300">Blok Waktu:</label>
            <select id="filter-duration" wire:model.live="filterDuration" class="p-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                {{-- Opsi Per Jam/Blok Jam --}}
                <option value="1">1 Jam (Per Jam)</option>
                <option value="3">3 Jam (Blok Shift Pendek)</option>
                <option value="6">6 Jam (Blok Klinis - Default)</option>
                {{-- Opsi Per Shift Klinis --}}
                <option value="block_shift">Per Shift (Pagi/Sore/Malam)</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" style="min-width: 1200px;">
            <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left w-48 font-bold">PARAMETER (ML)</th>

                    {{-- Header Kolom (Blok Waktu) --}}
                    @foreach($fluidSummary as $data)
                    <th scope="col" class="px-2 py-3 text-center w-16 font-bold">
                        {{ $data['label'] }}
                    </th>
                    @endforeach

                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                {{-- Baris Total Input --}}
                <tr class="bg-green-50 dark:bg-green-900 dark:bg-opacity-30">
                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        Total Input
                    </th>
                    @foreach($fluidSummary as $data)
                    <td class="px-2 py-2 text-center font-semibold text-green-700 dark:text-green-400">
                        {{ $data['masuk'] > 0 ? $data['masuk'] : '' }}
                    </td>
                    @endforeach
                </tr>

                {{-- Baris Total Output --}}
                <tr class="bg-danger-50 dark:bg-danger-900 dark:bg-opacity-30">
                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        Total Output
                    </th>
                    @foreach($fluidSummary as $data)
                    <td class="px-2 py-2 text-center font-semibold text-danger-600 dark:text-danger-400">
                        {{ $data['keluar'] > 0 ? $data['keluar'] : '' }}
                    </td>
                    @endforeach
                </tr>

                {{-- [PROFESIONAL] Baris Balance Netto dengan Color Coding Klinis --}}
                <tr class="bg-blue-100 dark:bg-blue-900 dark:bg-opacity-50 font-extrabold">
                    <th scope="row" class="px-4 py-2 font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        BALANCE NETTO
                    </th>
                    @foreach($fluidSummary as $data)
                        @php
                            $balance = $data['balance'];
                            $textColor = 'text-gray-600 dark:text-gray-400';
                            $bgColorClass = ''; // Untuk highlight kolom jika krisis

                            // Color Logic Klinis:
                            if ($balance > 500) { // Overload / Cairan berlebih
                                $textColor = 'text-blue-700 dark:text-blue-400';
                                $bgColorClass = 'bg-blue-200 dark:bg-blue-800'; // Highlight soft
                            } elseif ($balance < -200) { // Dehidrasi / Kekurangan cairan
                                $textColor = 'text-red-700 dark:text-red-400';
                                $bgColorClass = 'bg-red-200 dark:bg-red-800'; // Highlight soft
                            }
                        @endphp
                        <td class="px-2 py-2 text-center {{ $textColor }} {{ $bgColorClass }} font-extrabold transition duration-150">
                            @if($data['masuk'] !== 0 || $data['keluar'] !== 0)
                                {{ $balance }}
                            @endif
                        </td>
                    @endforeach
                </tr>

            </tbody>
        </table>
    </div>
</div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mt-6 border border-gray-100 dark:border-gray-700">
        <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ringkasan Obseravsi</h3>
        </div>
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
                    <tr>
                        <td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">CAIRAN MASUK</td>
                    </tr>

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
                    <tr>
                        <td class="px-4 py-1 sticky left-0 bg-white dark:bg-gray-800" style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input parenteral -</td>
                        <td colspan="{{ $this->uniqueTimestamps->count() }}" class="dark:bg-gray-800"></td>
                    </tr>
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
                    <tr>
                        <td class="px-4 py-1 sticky left-0 bg-white dark:bg-gray-800" style="padding-left: 25px; text-align: left; color: #888;">- Tidak ada input enteral -</td>
                        <td colspan="{{ $this->uniqueTimestamps->count() }}" class="dark:bg-gray-800"></td>
                    </tr>
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
                    <tr>
                        <td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">CAIRAN KELUAR</td>
                    </tr>
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
                    <tr>
                        <td colspan="{{ $this->uniqueTimestamps->count() + 1 }}" class="p-2 text-sm font-bold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 text-left">{{ $param['group'] }}</td>
                    </tr>
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
                                $class = 'whitespace-nowrap';
                                $title = ''; // Untuk hover detail
                                if ($key == 'tensi' && isset($record->tensi_sistol)) {
                                    $map = $record->map ?? ($record->tensi_sistol + 2 * $record->tensi_diastol) / 3;
                                    $value = $record->tensi_sistol . '/' . $record->tensi_diastol;
                                    if ($map < 65) { $class .= ' bg-red-100 dark:bg-red-900 text-red-700 font-bold'; } // Krisis Hipotensi
                                    elseif ($map < 75 || $map > 105) { $class .= ' text-orange-600 font-semibold'; } // Warning
                                    $value .= ' (' . round($map) . ')'; // Tampilkan MAP
                                }
                                elseif ($key == 'gcs' && (isset($record->gcs_e) || isset($record->gcs_v) || isset($record->gcs_m))) {
                                    $gcsTotal = $record->gcs_total ?? (($record->gcs_e ?? 0) + ($record->gcs_v ?? 0) + ($record->gcs_m ?? 0));
                                    $value = ($gcsTotal > 0 ? "({$gcsTotal})" : '-'); // Total GCS sebagai fokus utama
                                    $title = "E:".($record->gcs_e ?? '-').", V:".($record->gcs_v ?? '-').", M:".($record->gcs_m ?? '-'); // Detail EVM di hover
                                    if ($gcsTotal <= 8 && $gcsTotal > 0) { $class .= ' bg-yellow-200 dark:bg-yellow-900 text-gray-900 font-extrabold'; } // Koma/Intubasi Warning
                                }
                                elseif ($key == 'pupil' && (isset($record->pupil_left_size_mm) || isset($record->pupil_right_size_mm))) {
                                    $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-');
                                    $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-');
                                    $value = "{$left} | {$right}";
                                    // Deteksi Anisokor atau Refleks Lambat/Negatif
                                    if (($record->pupil_left_size_mm != $record->pupil_right_size_mm) || in_array($record->pupil_left_reflex, ['L', 'N']) || in_array($record->pupil_right_reflex, ['L', 'N'])) {
                                        $class .= ' text-red-600 font-bold';
                                    }
                                }
                                elseif ($key == 'suhu' && isset($record->suhu)) {
                                    $value = $record->suhu;
                                    if ($value >= 38.0) { $class .= ' text-red-600 font-bold'; } // Febris
                                    elseif ($value <= 36.0) { $class .= ' text-blue-600 font-bold'; } // Hipotermia
                                }
                                elseif ($key == 'nadi' && isset($record->nadi)) {
                                    $value = $record->nadi;
                                    if ($value >= 120) { $class .= ' text-red-600 font-bold'; } // Takikardi Berat
                                    elseif ($value >= 100) { $class .= ' text-orange-600 font-bold'; } // Takikardi
                                    elseif ($value <= 50) { $class .= ' text-blue-600 font-bold'; } // Bradikardi
                                }
                                elseif ($key == 'rr' && isset($record->rr)) {
                                    $value = $record->rr;
                                    if ($value >= 25) { $class .= ' text-red-600 font-bold'; } // Takipnea
                                    elseif ($value <= 10) { $class .= ' text-blue-600 font-bold'; } // Bradipnea
                                }
                                elseif ($key == 'spo2' && isset($record->spo2)) {
                                    $value = $record->spo2 . '%';
                                    if ($record->spo2 <= 92) { $class .= ' bg-red-100 dark:bg-red-900 text-red-700 font-bold'; } // Hipoksia
                                    elseif ($record->spo2 < 95) { $class .= ' text-orange-600 font-bold'; }
                                }
                                // Penanganan Catatan Klinis/Obat (Compact View)
                                elseif ($key == 'clinical_note' && !empty($record->clinical_note)) {
                                    $value = Str::limit($record->clinical_note, 50, '...');
                                    $title = $record->clinical_note;
                                    $class = 'whitespace-pre-wrap max-h-16 overflow-hidden text-left text-sm cursor-help bg-yellow-50 dark:bg-yellow-900/30';
                                }
                                elseif ($key == 'medication_administration' && !empty($record->medication_administration)) {
                                    $value = "💉 Tindakan/Obat (" . count(explode("\n", $record->medication_administration)) . ")";
                                    $title = $record->medication_administration;
                                    $class = 'whitespace-pre-wrap max-h-16 overflow-hidden text-left text-xs cursor-help bg-primary-50 dark:bg-primary-900/30 font-medium';
                                }

                                elseif (isset($record->{$key}) && $record->{$key} !== null && !in_array($key, ['tensi_sistol','tensi_diastol','gcs_e','gcs_v','gcs_m','gcs_total','pupil_left_size_mm','pupil_left_reflex','pupil_right_size_mm','pupil_right_reflex','clinical_note','medication_administration','cairan_masuk_jenis','cairan_masuk_volume','cairan_keluar_jenis','cairan_keluar_volume','is_enteral','is_parenteral'])) {
                                    $value = $record->{$key};
                                }
                            @endphp
                            <span class="{{ $class }}" title="{{ $title }}">{!! $value !!}</span>
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
</div>
