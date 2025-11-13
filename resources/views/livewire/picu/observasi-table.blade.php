<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h3 class="text-lg font-semibold border-b dark:border-gray-700 pb-3">
            Riwayat Monitoring Pasien
        </h3>

        @if ($selectedFilterCycleId)
            <span class="text-indigo-600 dark:text-indigo-400">
                (Siklus ID: {{ $selectedFilterCycleId }} - Tampilan Detail)
            </span>
        @else
            <span class="text-gray-500 dark:text-gray-400">
                (Semua Siklus Riwayat)
            </span>
        @endif

        <div wire:loading.delay.long wire:target="loadCycles" class="w-full text-center py-8">
            <span class="text-gray-500 dark:text-gray-400">Memuat data riwayat...</span>
        </div>

        <div wire:loading.remove wire:target="loadCycles" class="mt-4">

            {{-- OUTER LOOP: Mengulang List Siklus (24 Jam) --}}
            @forelse ($cycles as $cycle)

            <div class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg shadow-md">

                @php
                $records = optional($cycle->records)->sortBy('record_time') ?? collect();
                @endphp

                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-t-lg flex flex-wrap items-center justify-between text-sm">

                    <div class="font-bold text-gray-900 dark:text-gray-100 min-w-[200px] mb-2 md:mb-0">
                        Periode: {{ \Carbon\Carbon::parse($cycle->start_time)->format('d M Y, H:i') }}
                        <span class="block text-xs font-normal text-gray-500">
                            ({{ $records->count() }} Catatan Per Jam)
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-x-6 gap-y-2 text-xs font-medium text-gray-800 dark:text-gray-200">
                        <span>
                            HR Range:
                            <span class="text-primary-600">{{ $cycle->records_min_hr ?? '-' }}</span> -
                            <span class="text-primary-600">{{ $cycle->records_max_hr ?? '-' }}</span>
                        </span>
                        <span>
                            SpO₂ Min:
                            <span class="text-blue-600">{{ $cycle->records_min_sat_o2 ?? '-' }}%</span>
                        </span>
                        <span>
                            Suhu Range:
                            <span class="text-yellow-600">{{ $cycle->records_min_temp_skin ?? '-' }}</span> -
                            <span class="text-yellow-600">{{ $cycle->records_max_temp_skin ?? '-' }} °C</span>
                        </span>

                        @if ($cycle->records_max_cyanosis || $cycle->records_max_bradikardia)
                        <span class="font-bold text-red-600 dark:text-red-400">
                            ⚠️ KRITIS: Cyanosis / Bradikardia Terjadi
                        </span>
                        @endif
                    </div>

                    <div class="text-right mt-2 md:mt-0">
                        <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $cycle->id]) }}" target="_blank" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 transition ml-3" title="Cetak Laporan PDF">
                            Cetak PDF
                        </a>
                    </div>
                </div>
                @if ($records->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                        <thead class="bg-white dark:bg-gray-800 sticky top-0">
                            <tr>
                                <th scope="col" class="w-1/6 px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10 border-r dark:border-gray-700">
                                    Parameter
                                </th>

                                @foreach ($records as $record)
                                <th scope="col" class="px-3 py-2 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    <span wire:click="openDetails({{ $record->id }})" title="Lihat Detail Penuh">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</span>
                                </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse ($this->getMetricList() as $key => $label)
                            <tr wire:key="row-{{ $cycle->id }}-{{ $key }}">
                                <td class="w-1/6 px-3 py-2 text-sm font-medium text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800 z-10 border-r dark:border-gray-700">
                                    {{ $label }}
                                </td>

                                {{-- Ulangi di setiap kolom waktu --}}
                                @foreach ($records as $record)
                                @php
                                $value = $record->{$key} ?? '-';
                                $class = '';
                                // Styling Kritis
                                if ($key == 'hr' && $value !== null && ($value > 150 || $value < 60)) { $class='bg-red-100 dark:bg-red-900 font-bold text-red-600' ; } if ($key=='sat_o2' && $value !==null && $value < 90) { $class='bg-yellow-100 dark:bg-yellow-800 font-bold text-yellow-600' ; } if ($key=='author_name' ) { $fullName=$record->author_name;
                                    // Batasi tampilan nama hingga 15 karakter
                                    $displayValue = mb_strlen($fullName) > 15
                                    ? mb_substr($fullName, 0, 12) . '...'
                                    : $fullName;
                                    $value = $displayValue;
                                    $titleAttribute = 'title="' . htmlspecialchars($fullName) . '"';
                                    } else {
                                    $titleAttribute = '';
                                    }
                                    if ($key == 'tensi_combined') {
                                    $systolic = $record->blood_pressure_systolic ?? null; // Dapatkan nilai mentah
                                    $diastolic = $record->blood_pressure_diastolic ?? null;

                                    if ($systolic !== null || $diastolic !== null) {
                                    // Gabungkan S/D
                                    $value = ($systolic ?? '-') . '/' . ($diastolic ?? '-');
                                    } else {
                                    $value = '-';
                                    }
                                    if (($systolic !== null && ($systolic > 140 || $systolic < 80)) || ($diastolic !==null && ($diastolic> 90 || $diastolic < 40)) ) { $class='bg-red-100 dark:bg-red-900 font-bold text-red-600' ; } }
                                    if ($key == 'critical_events_summary') {
            $activeEvents = [];
            // Cek semua kolom tinyint(1)
            if ($record->cyanosis) $activeEvents[] = 'Cyanosis';
            if ($record->bradikardia) $activeEvents[] = 'Bradikardia';
            if ($record->pucat) $activeEvents[] = 'Pucat';
            if ($record->ikterus) $activeEvents[] = 'Ikterus';
            if ($record->crt_less_than_2) $activeEvents[] = 'CRT < 2';
            if ($record->stimulasi) $activeEvents[] = 'Stimulasi';

            if (!empty($activeEvents)) {
                $value = implode(', ', $activeEvents);
                $class = 'bg-red-100 dark:bg-red-900 font-bold text-red-600';
            } else {
                $value = 'Stabil';
            }
        }
                                    @endphp
                                     <td class="whitespace-nowrap px-3 py-2 text-center text-sm text-gray-700 dark:text-gray-300 {{ $class }}">
                                            <span {!! $titleAttribute !!}>
                                                {{ $value }}
                                            </span>
                                            </td>
                                            @endforeach
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">Belum ada catatan per jam dalam siklus ini.</div>
                @endif
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-lg">
                Tidak ada riwayat monitoring yang ditemukan untuk pasien ini.
            </div>
            @endforelse
        </div>
    </div>
</div>
