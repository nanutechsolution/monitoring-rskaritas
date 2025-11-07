@php
use Carbon\Carbon;

// --- Logika PHP Anda (Sudah Benar) ---
$allInfusNames = collect();
foreach ($fluidRecords as $record) {
    $allInfusNames = $allInfusNames->merge($record->parenteralIntakes->pluck('name'));
}
$uniqueInfusNames = $allInfusNames->unique()->values();

$allEnteralNames = collect();
foreach ($fluidRecords as $record) {
    $allEnteralNames = $allEnteralNames->merge($record->enteralIntakes->pluck('name'));
}
$uniqueEnteralNames = $allEnteralNames->unique()->values();

// === KELAS HELPER UNTUK TEMA ===
$headerBg = 'bg-gray-100 dark:bg-gray-700';
$headerText = 'text-gray-700 dark:text-gray-300';
$headerStickyBg = 'bg-gray-100 dark:bg-gray-700';

$rowBg = 'bg-white dark:bg-gray-800';
$rowBgAlt = 'bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50';
$rowStickyBg = 'bg-white dark:bg-gray-800';

// Hover colors
$rowHoverPrimary = 'hover:bg-primary-50 dark:hover:bg-gray-700';
$rowHoverDanger = 'hover:bg-danger-50 dark:hover:bg-gray-700';

// Total row colors
$rowTotalCmBg = 'bg-green-100 dark:bg-green-900 dark:bg-opacity-30';
$rowTotalCmText = 'text-green-800 dark:text-green-200';

$rowTotalCkBg = 'bg-danger-100 dark:bg-danger-900 dark:bg-opacity-30';
$rowTotalCkText = 'text-danger-800 dark:text-danger-200';

// Balance row colors
$rowBalanceBg = 'bg-primary-600 dark:bg-primary-700';
$rowBalanceText = 'text-white';
$balancePositiveBg = 'bg-primary-50 dark:bg-primary-900 dark:bg-opacity-50';
$balancePositiveText = 'text-primary-700 dark:text-primary-200';
$balanceNegativeBg = 'bg-danger-600 dark:bg-danger-700';
$balanceNegativeText = 'text-white';

$border = 'border dark:border-gray-600';

@endphp

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-6">
    <div class="overflow-x-auto relative max-h-[80vh]">
        <h5 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 uppercase tracking-wide">
            💧 Keseimbangan Cairan per Jam
        </h5>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">

            {{-- HEADER (THEAD) --}}
            <thead class="text-xs {{ $headerText }} uppercase {{ $headerBg }} sticky top-0 z-10">
                <tr>
                    <th scope="col" class="sticky left-0 {{ $headerStickyBg }} {{ $border }} px-2 py-1 text-left w-48 z-20">Jenis Cairan</th>
                    @foreach ($fluidRecords as $record)
                    <th class="{{ $border }} px-2 py-1 text-center">
                        {{ date('H:i', strtotime($record->record_time)) }}
                        <div class="text-[10px] text-gray-500 dark:text-gray-400 italic mt-0.5">
                            oleh {{ $record->author_name ?? '-' }}
                        </div>
                    </th>
                    @endforeach
                    <th class="{{ $border }} px-2 py-1 text-center bg-gray-50 dark:bg-gray-600">TOTAL</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                {{-- INTAKE --}}
                <tr class="{{ $rowBgAlt }} font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700 dark:text-gray-200">
                        Parenteral (Infus)
                    </td>
                </tr>

                @forelse ($uniqueInfusNames as $infusName)
                <tr class="{{ $rowBg }} {{ $rowHoverPrimary }} transition-colors duration-100">
                    <td class="sticky left-0 {{ $rowStickyBg }} {{ $border }} px-2 py-1 text-gray-700 dark:text-gray-300 italic z-10" style="padding-left: 25px;">{{ $infusName }}</td>
                    @foreach ($fluidRecords as $record)
                    @php
                    $infus = $record->parenteralIntakes->firstWhere('name', $infusName);
                    @endphp
                    <td class="{{ $border }} text-center">
                        @if($infus && $infus->volume > 0)
                            <span class="font-semibold text-green-600 dark:text-green-400">{{ $infus->volume }}</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">-</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="{{ $border }} text-center font-semibold {{ $rowBgAlt }} dark:bg-gray-700">
                        {{ $fluidRecords->sum(fn($r) => ($r->parenteralIntakes->firstWhere('name', $infusName)?->volume ?? 0)) }}
                    </td>
                </tr>
                @empty
                <tr class="{{ $rowBg }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} px-4 py-1 italic text-gray-400 dark:text-gray-500 z-10" style="padding-left: 25px;">- Tidak ada input parenteral -</td>
                    <td colspan="{{ count($fluidRecords) + 1 }}"></td>
                </tr>
                @endforelse

                {{-- ENTERAL --}}
                <tr class="{{ $rowBgAlt }} font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700 dark:text-gray-200">
                        Enteral
                    </td>
                </tr>

                @forelse ($uniqueEnteralNames as $enteralName)
                <tr class="{{ $rowBg }} {{ $rowHoverPrimary }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} {{ $border }} px-2 py-1 text-gray-700 dark:text-gray-300 italic z-10" style="padding-left: 25px;">{{ $enteralName }}</td>
                    @foreach ($fluidRecords as $record)
                    @php
                    $enteral = $record->enteralIntakes->firstWhere('name', $enteralName);
                    @endphp
                    <td class="{{ $border }} text-center">
                        @if($enteral && $enteral->volume > 0)
                            <span class="font-semibold text-green-600 dark:text-green-400">{{ $enteral->volume }}</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">-</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="{{ $border }} text-center font-semibold {{ $rowBgAlt }} dark:bg-gray-700">
                        {{ $fluidRecords->sum(fn($r) => ($r->enteralIntakes->firstWhere('name', $enteralName)?->volume ?? 0)) }}
                    </td>
                </tr>
                @empty
                 <tr class="{{ $rowBg }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} px-4 py-1 italic text-gray-400 dark:text-gray-500 z-10" style="padding-left: 25px;">- Tidak ada input enteral -</td>
                    <td colspan="{{ count($fluidRecords) + 1 }}"></td>
                </tr>
                @endforelse

                {{-- OGT --}}
                <tr class="{{ $rowBg }} {{ $rowHoverPrimary }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} {{ $border }} px-2 py-1 text-gray-700 dark:text-gray-300 z-10">OGT</td>
                    @foreach ($fluidRecords as $record)
                    <td class="{{ $border }} text-center">{{ $record->intake_ogt ?: '-' }}</td>
                    @endforeach
                    <td class="{{ $border }} text-center font-semibold {{ $rowBgAlt }} dark:bg-gray-700">{{ $fluidRecords->sum('intake_ogt') }}</td>
                </tr>

                {{-- ORAL --}}
                <tr class="{{ $rowBg }} {{ $rowHoverPrimary }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} {{ $border }} px-2 py-1 text-gray-700 dark:text-gray-300 z-10">Oral</td>
                    @foreach ($fluidRecords as $record)
                    <td class="{{ $border }} text-center">{{ $record->intake_oral ?: '-' }}</td>
                    @endforeach
                    <td class="{{ $border }} text-center font-semibold {{ $rowBgAlt }} dark:bg-gray-700">{{ $fluidRecords->sum('intake_oral') }}</td>
                </tr>

                {{-- TOTAL CAIRAN MASUK --}}
                <tr class="{{ $rowTotalCmBg }} font-bold">
                    <td class="sticky left-0 {{ $rowTotalCmBg }} {{ $border }} dark:border-green-800 px-2 py-1 {{ $rowTotalCmText }} z-10">TOTAL CM</td>
                    @foreach ($fluidRecords as $record)
                    <td class="{{ $border }} dark:border-gray-600 text-center {{ $rowTotalCmText }}">{{ $record->totalCairanMasuk() }}</td>
                    @endforeach
                    <td class="{{ $border }} dark:border-gray-600 text-center font-semibold {{ $rowTotalCmText }}">{{ $fluidRecords->sum(fn($r) => $r->totalCairanMasuk()) }}</td>
                </tr>

                {{-- OUTPUT --}}
                <tr class="{{ $rowBgAlt }} font-semibold">
                    <td colspan="{{ count($fluidRecords) + 2 }}" class="px-2 py-1 text-gray-700 dark:text-gray-200">OUTPUT (Cairan Keluar)</td>
                </tr>

                @foreach (['output_ngt' => 'NGT','output_urine' => 'Urine','output_bab' => 'BAB','output_drain' => 'Drain'] as $field => $label)
                <tr class="{{ $rowBg }} {{ $rowHoverDanger }}">
                    <td class="sticky left-0 {{ $rowStickyBg }} {{ $border }} px-2 py-1 text-gray-700 dark:text-gray-300 z-10">{{ $label }}</td>
                    @foreach ($fluidRecords as $record)
                    <td class="{{ $border }} dark:border-gray-600 text-center">
                        @if($record->$field > 0)
                            <span class="font-semibold text-danger-600 dark:text-danger-400">{{ $record->$field }}</span>
                        @else
                            <span class="text-gray-300 dark:text-gray-600">-</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="{{ $border }} dark:border-gray-600 text-center font-semibold {{ $rowBgAlt }} dark:bg-gray-700">{{ $fluidRecords->sum($field) }}</td>
                </tr>
                @endforeach

                {{-- TOTAL CAIRAN KELUAR --}}
                <tr class="{{ $rowTotalCkBg }} font-bold">
                    <td class="sticky left-0 {{ $rowTotalCkBg }} {{ $border }} dark:border-danger-700 px-2 py-1 {{ $rowTotalCkText }} z-10">TOTAL CK</td>
                    @foreach ($fluidRecords as $record)
                    <td class="{{ $border }} dark:border-gray-600 text-center {{ $rowTotalCkText }}">{{ $record->totalCairanKeluar() }}</td>
                    @endforeach
                    <td class="{{ $border }} dark:border-gray-600 text-center font-semibold {{ $rowTotalCkText }}">{{ $fluidRecords->sum(fn($r) => $r->totalCairanKeluar()) }}</td>
                </tr>

                {{-- BALANCE --}}
                <tr class="font-bold">
                    <td class="sticky left-0 {{ $rowBalanceBg }} {{ $border }} dark:border-primary-600 px-2 py-1 {{ $rowBalanceText }} z-10">BALANCE</td>
                    @foreach ($fluidRecords as $record)
                        @php
                            $balance = $record->totalCairanMasuk() - $record->totalCairanKeluar();
                            $bgColor = $balance < 0 ? ($balanceNegativeBg.' '.$balanceNegativeText) : ($balancePositiveBg.' '.$balancePositiveText);
                        @endphp
                    <td class="{{ $border }} dark:border-gray-600 text-center {{ $bgColor }}">{{ $balance }}</td>
                    @endforeach
                    @php
                        $totalBalance = $fluidRecords->sum(fn($r) => $r->totalCairanMasuk() - $r->totalCairanKeluar());
                        $totalBgColor = $totalBalance < 0 ? ($balanceNegativeBg.' '.$balanceNegativeText) : 'bg-primary-100 dark:bg-primary-800 text-primary-800 dark:text-primary-100 font-semibold' ;
                    @endphp
                    <td class="{{ $border }} dark:border-gray-600 text-center {{ $totalBgColor }}">{{ $totalBalance }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($fluidRecords->isEmpty())
        <p class="text-gray-500 dark:text-gray-400 text-center py-10">Belum ada data cairan masuk/keluar untuk siklus ini.</p>
    @endif
</div>
