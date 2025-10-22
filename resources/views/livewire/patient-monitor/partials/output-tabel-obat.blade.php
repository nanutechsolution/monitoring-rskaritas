@props([
    'title' => 'Riwayat Pemberian Obat',
    'medications' => $medications,
    'emptyText' => 'Belum ada obat yang dicatat.'
])

@php
use Carbon\Carbon;

// Ambil jam unik
$jamList = $medications->pluck('given_at')
    ->map(fn($t) => Carbon::parse($t)->format('H:i'))
    ->unique()
    ->sort()
    ->values();

// Ambil nama obat unik
$medNames = $medications->pluck('medication_name')->unique()->values();

// Matriks data: [nama_obat][jam] = ['dose' => ..., 'route' => ..., 'author' => ...]
$matrix = [];
foreach ($medications as $med) {
    $jam = Carbon::parse($med->given_at)->format('H:i');
    $matrix[$med->medication_name][$jam] = [
        'dose' => $med->dose,
        'route' => $med->route,
        'author' => $med->author_name,
    ];
}

// Batas dosis untuk highlight (mg)
$highlightThreshold = 500;
@endphp

<div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
    <div class="p-6">
        <div class="flex items-center justify-between border-b pb-3 mb-3">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                💊 {{ $title }}
            </h3>
            <span class="text-xs text-gray-400">Terakhir diperbarui: {{ now()->format('d M Y H:i') }}</span>
        </div>

        @if($medications->isEmpty())
            <div class="text-center py-6 text-gray-500">{{ $emptyText }}</div>
        @else
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-max text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="sticky left-0 bg-gray-100 px-4 py-3 text-left font-semibold z-10">Obat</th>
                            @foreach($jamList as $jam)
                                <th class="px-4 py-3 font-semibold text-center">{{ $jam }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medNames as $medName)
                            <tr class="hover:bg-blue-50 transition-colors duration-150 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                                <td class="sticky left-0 bg-white px-4 py-3 font-medium text-gray-800 border-r border-gray-100">{{ $medName }}</td>
                                @foreach($jamList as $jam)
                                    @php
                                        $data = $matrix[$medName][$jam] ?? null;
                                        if ($data) {
                                            $doseText = $data['dose'] . ' / ' . $data['route'];
                                            $author = $data['author'];
                                            preg_match('/\d+/', $data['dose'], $matches);
                                            $highlightClass = (isset($matches[0]) && intval($matches[0]) > $highlightThreshold)
                                                ? 'text-red-600 font-semibold'
                                                : 'text-gray-800';
                                        } else {
                                            $doseText = '-';
                                            $author = null;
                                            $highlightClass = 'text-gray-400';
                                        }
                                    @endphp

                                    <td class="px-4 py-3 text-center align-top border-t border-gray-100">
                                        @if($data)
                                            <div class="{{ $highlightClass }}">{{ $doseText }}</div>
                                            <div
                                                class="text-xs text-gray-500 mt-1 tooltip cursor-help"
                                                title="Diberikan oleh: {{ $author }}"
                                            >
                                                <i class="fas fa-user-nurse text-blue-400 mr-1"></i>
                                                {{ Str::limit($author, 15) }}
                                            </div>
                                        @else
                                            <span class="text-gray-300">-</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Optional styling for better tooltip + icons (if not using Tailwind plugins) --}}
<style>
.tooltip[title]:hover::after {
    content: attr(title);
    position: absolute;
    background: rgba(55, 65, 81, 0.9);
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    white-space: nowrap;
    transform: translate(-50%, -125%);
    left: 50%;
    top: -2px;
    pointer-events: none;
    z-index: 50;
}
</style>
