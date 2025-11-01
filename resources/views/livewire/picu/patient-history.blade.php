<div class="max-w-7xl  mx-auto p-4 sm:p-6 space-y-6">

    {{-- 1. HEADER PASIEN --}}
    <div class="bg-white shadow-lg rounded-lg p-6 border-l-4 border-pink-600">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">{{ $registrasi->pasien->nm_pasien }}</h2>
                <p class="text-lg text-gray-600">
                    <span class="font-semibold">No. RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
                <h3 class="text-xl font-semibold text-pink-700 mt-2">Riwayat Monitoring PICU</h3>
            </div>
            <div class="text-right">
                <a href="{{-- (URL Daftar Pasien Ranap Anda) --}}" wire:navigate class="text-sm text-gray-600 hover:text-blue-600">
                    &larr; Kembali ke Daftar Pasien
                </a>
            </div>
        </div>
    </div>

    {{-- 2. TOMBOL BUKA HARI INI --}}
    <div class="bg-white shadow rounded-lg p-6 text-center">
        <h3 class="text-lg font-medium text-gray-900">Buka Lembar Observasi PICU</h3>
        <p class="text-gray-500 mb-4">Membuka lembar kerja hari ini ({{ \Carbon\Carbon::parse($todayDate)->isoFormat('dddd, D MMMM Y') }}).</p>
        <a href="{{ route('monitoring.picu.workspace', [
                    'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                    'sheetDate' => $todayDate
                ]) }}" wire:navigate class="inline-block bg-pink-600 text-white px-10 py-3 rounded-md shadow-lg font-semibold text-lg hover:bg-pink-700 transition-colors">
            Buka Lembar Kerja Hari Ini
        </a>
    </div>

    {{-- 3. TABEL RIWAYAT LAMPAU --}}
    <div class="bg-white shadow rounded-lg">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat Lampau (PICU)</h3>
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
                        {{-- PERBAIKAN: Gunakan start_datetime --}}
                        <span class="font-semibold">{{ $cycle->start_datetime->isoFormat('dddd, D MMMM Y') }}</span>

                        {{-- PERBAIKAN: Gunakan start_datetime --}}
                        @if($cycle->start_datetime->isSameDay($todayDate))
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-pink-100 text-pink-800 rounded-full">Hari Ini</span>
                        @endif
                    </td>

                    {{-- PERBAIKAN: Gunakan balance_cairan_24h --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ $cycle->balance_cairan_24h ?? 'N/A' }} ml</td>

                    {{-- PERBAIKAN: Gunakan nama kolom yang benar --}}
                    <td class="px-6 py-4 whitespace-nowrap">{{ ($cycle->balance_cairan_24h_sebelumnya ?? 0) + ($cycle->balance_cairan_24h ?? 0) }} ml</td>

                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <a href="{{ route('monitoring.picu.workspace', [
                                    'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                    'sheetDate' => $cycle->start_datetime->toDateString()
                                ]) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 font-medium">
                            Buka
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Belum ada riwayat monitoring PICU untuk pasien ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
