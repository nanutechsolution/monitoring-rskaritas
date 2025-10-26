<div class="container mx-auto p-6 space-y-6">

    <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-blue-600">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $registrasi->pasien->nm_pasien }}</h2>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">No. RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
                <h3 class="text-xl font-semibold text-gray-700 mt-2">Riwayat Monitoring ICU</h3>
            </div>
            <div class="text-right">
                {{-- Tombol kembali ke daftar pasien ranap --}}
                <a href="{{-- (Isi URL ke Daftar Pasien Ranap Anda) --}}" wire:navigate
                   class="text-sm text-gray-600 hover:text-blue-600">
                    &larr; Kembali ke Daftar Pasien
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 text-center">
        <h3 class="text-lg font-medium text-gray-900">Buka Lembar Observasi</h3>
        <p class="text-gray-500 mb-4">Membuka lembar kerja hari ini ({{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMMM Y') }}). Jika belum ada, akan dibuatkan otomatis.</p>

        <a href="{{ route('monitoring.icu.workspace', [
                'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                'sheetDate' => $todayDate
            ]) }}"
           wire:navigate
           class="inline-block bg-blue-600 text-white px-10 py-3 rounded-md shadow-lg font-semibold text-lg hover:bg-blue-700 transition-colors">
            Buka Lembar Kerja Hari Ini
        </a>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Lampau</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Lembar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BC 24 Jam (Net)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BC Kumulatif (Akhir)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($allCycles as $cycle)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold">{{ $cycle->sheet_date->isoFormat('dddd, D MMMM Y') }}</span>
                            @if($cycle->sheet_date->isSameDay($todayDate))
                                <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Hari Ini</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $cycle->calculated_balance_24h ?? 'N/A' }} ml</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ($cycle->previous_balance ?? 0) + ($cycle->calculated_balance_24h ?? 0) }} ml</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('monitoring.icu.workspace', [
                                    'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                    'sheetDate' => $cycle->sheet_date->toDateString()
                                ]) }}"
                               wire:navigate
                               class="text-indigo-600 hover:text-indigo-900 font-medium">
                                Buka
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Belum ada riwayat monitoring untuk pasien ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
