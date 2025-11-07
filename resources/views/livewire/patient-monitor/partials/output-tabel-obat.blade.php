@props([
'title' => 'Riwayat Pemberian Obat',
'medications' => $medications,
])

@php
use Carbon\Carbon;

// --- Logika PHP Anda (Sudah Benar) ---
$jamList = $medications->pluck('given_at')
    ->map(fn($t) => Carbon::parse($t)->format('H:i'))
    ->unique()
    ->sort()
    ->values();

$medNames = $medications->pluck('medication_name')->unique()->values();

$matrix = [];
foreach ($medications as $med) {
    $jam = Carbon::parse($med->given_at)->format('H:i');
    $matrix[$med->medication_name][$jam] = [
        'dose' => $med->dose,
        'route' => $med->route,
        'author' => $med->author_name, // Pastikan $med->author_name ada
        'nik' => $med->pegawai->nik ?? '-', // Ambil NIK jika ada relasi 'pegawai'
        'timestamp' => Carbon::parse($med->given_at)->translatedFormat('d M Y H:i'),
    ];
}

$highlightThreshold = 500; // Contoh threshold, sesuaikan jika perlu

// === KELAS HELPER UNTUK TEMA ===
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

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
    <div class="p-6">
        <div class="flex items-center justify-between border-b dark:border-gray-700 pb-3 mb-3">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $title }}
            </h3>
            <span class="text-xs text-gray-400 dark:text-gray-500">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
        </div>

        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700">
            <table class="min-w-max text-sm text-gray-700 dark:text-gray-300">
                <thead class="{{ $headerBg }} {{ $headerText }} uppercase text-xs tracking-wider sticky top-0 z-10">
                    <tr>
                        <th class="sticky left-0 {{ $headerStickyBg }} px-4 py-3 text-left font-semibold z-10 {{ $border }}">Obat</th>
                        @foreach($jamList as $jam)
                        <th class="{{ $border }} px-4 py-3 font-semibold text-center">{{ $jam }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($medNames as $medName)
                    <tr class="{{ $loop->even ? $rowBgAlt : $rowBg }} {{ $rowHover }} transition-colors duration-150">
                        <td class="sticky left-0 px-4 py-3 font-medium text-gray-800 dark:text-gray-100 {{ $border }}
                                   {{ $loop->even ? $rowStickyBgAlt : $rowStickyBg }} z-10">
                            {{ $medName }}
                        </td>

                        @foreach($jamList as $jam)
                        @php
                            $data = $matrix[$medName][$jam] ?? null;
                            if ($data) {
                                $doseText = $data['dose'] . ' / ' . $data['route'];
                                $author = $data['author'];
                                $nik = $data['nik'];
                                $timestamp = $data['timestamp'];
                                preg_match('/\d+/', $data['dose'], $matches);
                                $highlightClass = (isset($matches[0]) && intval($matches[0]) > $highlightThreshold)
                                    ? 'text-danger-600 dark:text-danger-400 font-semibold'
                                    : 'text-gray-800 dark:text-gray-100';
                            } else {
                                $doseText = '-';
                                $author = null;
                                $highlightClass = 'text-gray-400 dark:text-gray-500';
                            }
                        @endphp

                        <td class="px-4 py-3 text-center align-top {{ $border }}">
                            @if($data)
                            <div class="{{ $highlightClass }}">{{ $doseText }}</div>

                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 relative group cursor-help">
                                <i class="fas fa-user-nurse text-primary-500 dark:text-primary-400 mr-1"></i>
                                <span>{{ Str::limit($author, 15) }}</span>

                                <div x-data="{ open: false }" x-show="open" x-transition @mouseenter="open = true" @mouseleave="open = false"
                                     class="hidden group-hover:block absolute z-20 bg-gray-800 dark:bg-gray-900 text-white
                                            text-xs rounded-md p-2 w-48 -translate-x-1/2 left-1/2 bottom-full mb-1 shadow-lg
                                            text-left">
                                    <p class="font-semibold">{{ $author }}</p>
                                    <p>NIK: {{ $nik }}</p>
                                    <p class="text-[11px] mt-1">Diberikan: {{ $timestamp }}</p>
                                </div>
                            </div>
                            @else
                            <span class="{{ $highlightClass }}">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $jamList->count() + 1 }}" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                            Belum ada data pemberian obat untuk siklus ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
