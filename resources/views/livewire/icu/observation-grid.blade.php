<div class="container mx-auto  space-y-6">
    {{-- <div class="bg-white shadow rounded-lg p-2 flex space-x-2">
        <button wire:click="$set('filterShift', 'all')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Semua (24 Jam)
        </button>
        <button wire:click="$set('filterShift', 'pagi')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'pagi' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Shift Pagi (07-14)
        </button>
        <button wire:click="$set('filterShift', 'siang')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'siang' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Shift Siang (14-21)
        </button>
        <button wire:click="$set('filterShift', 'malam')" class="px-4 py-2 rounded-md text-sm font-medium {{ $filterShift == 'malam' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Shift Malam (21-07)
        </button>
    </div> --}}
    <div x-data="{
        chartData: JSON.parse($el.getAttribute('data-chart')),
        initChart() {
            const ctx = document.getElementById('icuChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: this.chartData.labels,
                    datasets: this.chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    spanGaps: false,
                    scales: {
                        x: {
                            title: { display: true, text: 'Waktu Input' }
                        },
                        yTtv: {
                            type: 'linear',
                            position: 'left',
                            title: { display: true, text: 'Nadi / RR / Tensi' },
                            min: 0,
                            suggestedMax: 160,
                        },
                        ySuhu: {
                            type: 'linear',
                            position: 'right',
                            title: { display: true, text: 'Suhu (°C)' },
                            min: 34,
                            suggestedMax: 42,
                            grid: { drawOnChartArea: false },
                        },
                        yCvp: {
                                 type: 'linear',
                                 position: 'right',
                                 title: { display: true, text: 'CVP' },
                                 min: -5,
                                 suggestedMax: 20,
                                 grid: {
                                     drawOnChartArea: false,
                                 },
                                 ticks: {
                                     color: 'rgb(249, 115, 22)',
                                 }
                             }
                    }
                }
            });
        }
    }" x-init="initChart()" data-chart='@json($this->chartData)' class="bg-white shadow rounded-lg p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Tren TTV</h3>
        <div class="h-96">
            <canvas id="icuChart"></canvas>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto relative max-h-[80vh]">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left w-48 sticky left-0 bg-gray-100">
                            Parameter
                        </th>
                        @foreach($this->allRecords as $record)
                        <th scope="col" class="px-4 py-2 text-center w-32 border-l">
                            <span class="font-bold text-blue-600 text-base">
                                {{ $record->observation_time->format('H:i') }}
                            </span>
                            <br>
                            <span class="text-xs text-gray-500 font-medium whitespace-nowrap">
                                {{ $record->inputter->nama ?? 'Sistem' }}
                            </span>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $currentGroup = ''; @endphp

                    @foreach($allParameters as $param)
                    @if ($param['group'] != $currentGroup)
                    <tr class="bg-gray-50 border-t border-b">
                        <td colspan="{{ $this->allRecords->count() + 1 }}" class="px-4 py-1 text-sm font-semibold text-gray-800 sticky left-0 bg-gray-50">
                            {{ $param['group'] }}
                        </td>
                    </tr>
                    @php $currentGroup = $param['group']; @endphp
                    @endif

                    <tr class="bg-white border-b hover:bg-gray-50">

                        {{-- Label Parameter (STICKY) --}}
                        <th scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap sticky left-0 bg-white shadow-sm">
                            {{ $param['label'] }}
                        </th>

                        {{-- Loop semua record (Kolom) --}}
                        @foreach($this->allRecords as $record)
                        <td class="px-4 py-2 text-center border-l">
                            @php
                            $value = null;
                            $key = $param['key'];

                            // --- Logika Penggabungan Data ---
                            if ($key == 'tensi' && $record->tensi_sistol) {
                            $value = $record->tensi_sistol . '/' . $record->tensi_diastol;
                            }
                            elseif ($key == 'gcs' && ($record->gcs_total || $record->gcs_e)) {
                            $gcsTotal = $record->gcs_total ?? ($record->gcs_e + $record->gcs_v + $record->gcs_m);
                            $value = "E{$record->gcs_e}V{$record->gcs_v}M{$record->gcs_m} ({$gcsTotal})";
                            }
                            elseif ($key == 'cairan_masuk' && $record->cairan_masuk_volume) {
                            $value = $record->cairan_masuk_jenis . ' (' . $record->cairan_masuk_volume . ' ml)';
                            }
                            elseif ($key == 'cairan_keluar' && $record->cairan_keluar_volume) {
                            $value = $record->cairan_keluar_jenis . ' (' . $record->cairan_keluar_volume . ' ml)';
                            }
                            elseif ($key == 'pupil' && ($record->pupil_left_size_mm || $record->pupil_right_size_mm)) {
                            $left = ($record->pupil_left_size_mm ?? '-') . '/' . ($record->pupil_left_reflex ?? '-');
                            $right = ($record->pupil_right_size_mm ?? '-') . '/' . ($record->pupil_right_reflex ?? '-');
                            $value = "{$left} | {$right}";
                            }
                            elseif (isset($record->$key) && !in_array($key, ['tensi', 'gcs', 'cairan_masuk', 'cairan_keluar'])) {
                            $value = $record->$key;
                            }
                            @endphp

                            {{-- Tampilkan nilainya --}}
                            <span class="font-semibold">{{ $value }}</span>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- Jika tidak ada data --}}
            @if($this->allRecords->isEmpty())
            <p class="text-center text-gray-500 p-10">Belum ada data inputan real-time untuk hari ini.</p>
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

</div>
