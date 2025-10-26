<div class="container mx-auto px-3 sm:px-6 py-4 sm:py-6 space-y-5">

    {{-- ============================= --}}
    {{-- HEADER PASIEN (Sangat Ringkas & Responsif) --}}
    {{-- ============================= --}}
    <div class="bg-white shadow-sm rounded-lg border-l-4 border-blue-600 px-3 py-3 sm:px-6 sm:py-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-3">
            <div class="leading-tight">
                <h2 class="text-base sm:text-xl font-bold text-gray-800 truncate">
                    {{ $registrasi->pasien->nm_pasien }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600">
                    <span class="font-medium">RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-medium">Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
            </div>

            <a href="{{-- isi URL kembali --}}" wire:navigate class="text-[11px] sm:text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                ← Kembali
            </a>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- AKSI HARI INI --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5 text-center">
        <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">Lembar Observasi Hari Ini</h3>
        <p class="text-gray-500 text-xs sm:text-base mb-4 sm:mb-5">
            {{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMMM Y') }} —
            Jika belum ada, sistem akan membuatkan otomatis.
        </p>

        <a href="{{ route('monitoring.icu.workspace', [
                'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                'sheetDate' => $todayDate
            ]) }}" wire:navigate class="inline-flex items-center justify-center gap-1.5 sm:gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-8 py-2 sm:py-3 rounded-md font-medium text-sm sm:text-base shadow hover:shadow-md transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buka Hari Ini
        </a>
    </div>

    {{-- ============================= --}}
    {{-- RIWAYAT MONITORING --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-3 sm:p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800">Riwayat Lampau</h3>
            <span class="text-xs text-gray-400">{{ $allCycles->count() }} catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium tracking-wider">Tanggal</th>
                        <th class="px-3 py-2 text-left font-medium tracking-wider">BC 24 Jam</th>
                        <th class="px-3 py-2 text-left font-medium tracking-wider">BC Kumulatif</th>
                        <th class="px-3 py-2 text-right font-medium tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($allCycles as $cycle)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-3 py-2 whitespace-nowrap font-medium text-gray-800">
                            {{ $cycle->sheet_date->isoFormat('D MMM Y') }}
                            @if($cycle->sheet_date->isSameDay($todayDate))
                            <span class="ml-1 text-[10px] font-semibold bg-blue-100 text-blue-700 px-1 py-0.5 rounded-full">
                                Hari Ini
                            </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-gray-700">{{ $cycle->calculated_balance_24h ?? 'N/A' }} ml</td>
                        <td class="px-3 py-2 text-gray-700">
                            {{ ($cycle->previous_balance ?? 0) + ($cycle->calculated_balance_24h ?? 0) }} ml
                        </td>
                        <td class="px-3 py-2 text-right">
                            <a href="{{ route('monitoring.icu.workspace', [
                                        'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                        'sheetDate' => $cycle->sheet_date->toDateString()
                                    ]) }}" wire:navigate class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium text-xs sm:text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4 4m-4-4l4-4m10 8h4v-8h-4" />
                                </svg>
                                Buka
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-5 text-center text-gray-500 text-sm">
                            Belum ada riwayat monitoring untuk pasien ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
