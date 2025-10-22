<div class="overflow-x-auto bg-white shadow-lg rounded-2xl border p-4">
    <div class="p-6">
        <h5 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">
            💧 Keseimbangan Cairan per Jam
        </h5>

        <table class="min-w-max text-xs border-collapse w-full">
            <thead class="bg-gray-100 text-gray-600 sticky top-0 z-20">
                <tr>
                    <th class="sticky left-0 bg-gray-100 border px-2 py-1 text-left w-48">Jenis Cairan</th>
                    @foreach ($fluidRecords as $record)
                        <th class="border px-2 py-1 text-center">
                            {{ date('H:i', strtotime($record->record_time)) }}
                            <div class="text-[10px] text-gray-500 italic mt-0.5">
                                oleh {{ $record->author_name ?? '-' }}
                            </div>
                        </th>
                    @endforeach
                    <th class="border px-2 py-1 text-center bg-gray-50">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- INTAKE --}}
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700">
                        Parenteral (Infus)
                    </td>
                </tr>

                @php
                    $allInfusNames = collect();
                    foreach ($fluidRecords as $record) {
                        $allInfusNames = $allInfusNames->merge($record->parenteralIntakes->pluck('name'));
                    }
                    $uniqueInfusNames = $allInfusNames->unique()->values();
                @endphp

                @foreach ($uniqueInfusNames as $infusName)
                    <tr class="hover:bg-blue-50 transition-colors duration-100">
                        <td class="sticky left-0 bg-white border px-2 py-1 text-gray-700 italic">{{ $infusName }}</td>
                        @foreach ($fluidRecords as $record)
                            @php
                                $infus = $record->parenteralIntakes->firstWhere('name', $infusName);
                            @endphp
                            <td class="border text-center">
                                {{ $infus ? $infus->volume : '-' }}
                            </td>
                        @endforeach
                        <td class="border text-center font-semibold bg-gray-50">
                            {{ $fluidRecords->sum(fn($r) => ($r->parenteralIntakes->firstWhere('name', $infusName)?->volume ?? 0)) }}
                        </td>
                    </tr>
                @endforeach

                {{-- ENTERAL --}}
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700">
                        Enteral
                    </td>
                </tr>

                @php
                    $allEnteralNames = collect();
                    foreach ($fluidRecords as $record) {
                        $allEnteralNames = $allEnteralNames->merge($record->enteralIntakes->pluck('name'));
                    }
                    $uniqueEnteralNames = $allEnteralNames->unique()->values();
                @endphp

                @foreach ($uniqueEnteralNames as $enteralName)
                    <tr class="hover:bg-blue-50">
                        <td class="sticky left-0 bg-white border px-2 py-1 text-gray-700 italic">{{ $enteralName }}</td>
                        @foreach ($fluidRecords as $record)
                            @php
                                $enteral = $record->enteralIntakes->firstWhere('name', $enteralName);
                            @endphp
                            <td class="border text-center">{{ $enteral ? $enteral->volume : '-' }}</td>
                        @endforeach
                        <td class="border text-center font-semibold bg-gray-50">
                            {{ $fluidRecords->sum(fn($r) => ($r->enteralIntakes->firstWhere('name', $enteralName)?->volume ?? 0)) }}
                        </td>
                    </tr>
                @endforeach

                {{-- OGT --}}
                <tr class="hover:bg-blue-50">
                    <td class="sticky left-0 bg-white border px-2 py-1">OGT</td>
                    @foreach ($fluidRecords as $record)
                        <td class="border text-center">{{ $record->intake_ogt ?: '-' }}</td>
                    @endforeach
                    <td class="border text-center font-semibold bg-gray-50">{{ $fluidRecords->sum('intake_ogt') }}</td>
                </tr>

                {{-- ORAL --}}
                <tr class="hover:bg-blue-50">
                    <td class="sticky left-0 bg-white border px-2 py-1">Oral</td>
                    @foreach ($fluidRecords as $record)
                        <td class="border text-center">{{ $record->intake_oral ?: '-' }}</td>
                    @endforeach
                    <td class="border text-center font-semibold bg-gray-50">{{ $fluidRecords->sum('intake_oral') }}</td>
                </tr>

                {{-- TOTAL CAIRAN MASUK --}}
                <tr class="bg-gray-200 font-bold">
                    <td class="sticky left-0 bg-gray-200 border px-2 py-1">TOTAL CM</td>
                    @foreach ($fluidRecords as $record)
                        <td class="border text-center">{{ $record->totalCairanMasuk() }}</td>
                    @endforeach
                    <td class="border text-center font-semibold">{{ $fluidRecords->sum(fn($r) => $r->totalCairanMasuk()) }}</td>
                </tr>

                {{-- OUTPUT --}}
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700">OUTPUT (Cairan Keluar)</td>
                </tr>

                @foreach (['output_ngt' => 'NGT','output_urine' => 'Urine','output_bab' => 'BAB','output_drain' => 'Drain'] as $field => $label)
                    <tr class="hover:bg-red-50">
                        <td class="sticky left-0 bg-white border px-2 py-1">{{ $label }}</td>
                        @foreach ($fluidRecords as $record)
                            <td class="border text-center">{{ $record->$field ?: '-' }}</td>
                        @endforeach
                        <td class="border text-center font-semibold bg-gray-50">{{ $fluidRecords->sum($field) }}</td>
                    </tr>
                @endforeach

                {{-- TOTAL CAIRAN KELUAR --}}
                <tr class="bg-gray-200 font-bold">
                    <td class="sticky left-0 bg-gray-200 border px-2 py-1">TOTAL CK</td>
                    @foreach ($fluidRecords as $record)
                        <td class="border text-center">{{ $record->totalCairanKeluar() }}</td>
                    @endforeach
                    <td class="border text-center font-semibold">{{ $fluidRecords->sum(fn($r) => $r->totalCairanKeluar()) }}</td>
                </tr>

                {{-- BALANCE --}}
                <tr class="font-bold text-white">
                    <td class="sticky left-0 bg-indigo-600 border px-2 py-1">BALANCE</td>
                    @foreach ($fluidRecords as $record)
                        @php
                            $balance = $record->totalCairanMasuk() - $record->totalCairanKeluar();
                            $bgColor = $balance < 0 ? 'bg-red-600' : 'bg-indigo-50 text-indigo-700';
                        @endphp
                        <td class="border text-center {{ $bgColor }}">{{ $balance }}</td>
                    @endforeach
                    @php
                        $totalBalance = $fluidRecords->sum(fn($r) => $r->totalCairanMasuk() - $r->totalCairanKeluar());
                        $totalBgColor = $totalBalance < 0 ? 'bg-red-600' : 'bg-indigo-50 text-indigo-700 font-semibold';
                    @endphp
                    <td class="border text-center {{ $totalBgColor }}">{{ $totalBalance }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
