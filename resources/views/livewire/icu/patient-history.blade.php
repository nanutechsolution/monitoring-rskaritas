<div class="container mx-auto px-4 sm:px-6 py-6 space-y-6">

    {{-- ============================= --}}
    {{-- HEADER PASIEN --}}
    {{-- ============================= --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white shadow-lg rounded-2xl p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold">{{ $registrasi->pasien->nm_pasien }}</h2>
                <p class="text-sm sm:text-base opacity-90 mt-1">
                    <span class="font-semibold">No. RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
                <h3 class="text-lg sm:text-xl font-semibold mt-2 opacity-95">Riwayat Monitoring ICU</h3>
            </div>
            <div class="text-right w-full sm:w-auto">
                <a href="{{-- isi URL kembali --}}" wire:navigate class="inline-flex items-center text-sm font-medium bg-white/10 hover:bg-white/20 transition-all rounded-full px-4 py-2 backdrop-blur">
                    ← Kembali ke Daftar Pasien
                </a>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- AKSI HARI INI --}}
    {{-- ============================= --}}
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-md border border-gray-100 p-6 text-center">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Lembar Observasi Hari Ini</h3>
        <p class="text-gray-500 mb-5 text-sm sm:text-base">
            {{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMMM Y') }} —
            Jika belum ada, sistem akan membuatkan otomatis.
        </p>

        <a href="{{ route('monitoring.icu.workspace', [
                'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                'sheetDate' => $todayDate
            ]) }}" wire:navigate class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 sm:px-10 py-3 rounded-full font-semibold text-base sm:text-lg shadow-md hover:shadow-lg transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buka Lembar Hari Ini
        </a>
    </div>

    {{-- ============================= --}}
    {{-- RIWAYAT MONITORING --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Riwayat Lampau</h3>
            <span class="text-sm text-gray-400">{{ $allCycles->count() }} catatan</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs sm:text-sm">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left font-medium tracking-wider">Tanggal Lembar</th>
                        <th class="px-4 sm:px-6 py-3 text-left font-medium tracking-wider">BC 24 Jam</th>
                        <th class="px-4 sm:px-6 py-3 text-left font-medium tracking-wider">BC Kumulatif</th>
                        <th class="px-4 sm:px-6 py-3 text-right font-medium tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($allCycles as $cycle)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap font-medium text-gray-800">
                            {{ $cycle->sheet_date->isoFormat('dddd, D MMMM Y') }}
                            @if($cycle->sheet_date->isSameDay($todayDate))
                            <span class="ml-2 inline-block text-xs font-semibold bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                                Hari Ini
                            </span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-gray-700">
                            {{ $cycle->calculated_balance_24h ?? 'N/A' }} ml
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-gray-700">
                            {{ ($cycle->previous_balance ?? 0) + ($cycle->calculated_balance_24h ?? 0) }} ml
                        </td>
                        <td class="px-4 sm:px-6 py-3 whitespace-nowrap text-right">
                            <a href="{{ route('monitoring.icu.workspace', [
                                        'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                        'sheetDate' => $cycle->sheet_date->toDateString()
                                    ]) }}" wire:navigate class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m0 0l4 4m-4-4l4-4m10 8h4v-8h-4" />
                                </svg>
                                Buka
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                            Belum ada riwayat monitoring untuk pasien ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
