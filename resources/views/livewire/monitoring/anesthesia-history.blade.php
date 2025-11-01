<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    {{-- Header Modern --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg rounded-xl p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">

        {{-- Info Pasien --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
            <div class="flex flex-col">
                <h1 class="text-2xl sm:text-3xl font-bold">{{ $pasien->nm_pasien ?? 'Pasien N/A' }}</h1>
                <p class="text-sm sm:text-base">
                    RM: <strong>{{ $pasien->no_rkm_medis ?? '-' }}</strong> |
                    No. Rawat: <strong>{{ $noRawat }}</strong>
                </p>
                <p class="text-xs sm:text-sm mt-1 opacity-90">{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d M Y') : '-' }} ({{ $pasien->umur ?? '-' }} tahun)</p>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex space-x-3 mt-2 sm:mt-0">
            <a href="{{ route('monitoring.anestesi.create', ['noRawat' => str_replace('/', '_', $noRawat)]) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white text-blue-700 font-semibold rounded-lg shadow hover:bg-gray-100 transition duration-150 ease-in-out text-sm sm:text-base">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Formulir Baru
            </a>
        </div>
    </div>
    {{-- Flash Message --}}
    @if (session()->has('success'))
    <div class="bg-green-50 border border-green-400 text-green-800 px-4 py-3 rounded-md shadow-sm flex items-center space-x-2" role="alert">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-sm sm:text-base">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ---------------- Desktop Table ---------------- --}}
    <div class="hidden md:block bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dokter Anestesi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penata Anestesi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($history as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->dokterAnestesi->nm_dokter ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->penataAnestesi->nama ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                            {{-- 💡 BARU: Tombol Lihat (Read-Only) --}}
                            <a href="{{ route('monitoring.anestesi.show', ['monitoringId' => $record->id]) }}" wire:navigate class="text-blue-600 hover:text-blue-900" title="Lihat Data">
                                Lihat
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('monitoring.anestesi.edit', ['monitoringId' => $record->id]) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900" title="Edit Data">
                                Edit
                            </a>

                            {{-- Tombol Cetak --}}
                            <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $record->id]) }}" target="_blank" class="text-green-600 hover:text-green-900" title="Cetak Dokumen">
                                Cetak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">Belum ada riwayat monitoring anestesi untuk pasien ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ---------------- Mobile Card ---------------- --}}
    <div class="md:hidden grid grid-cols-1 gap-4">
        @forelse ($history as $record)
        <div class="bg-white shadow-lg rounded-lg p-4 flex flex-col justify-between hover:shadow-xl transition duration-200">
            <div>
                <p class="text-gray-500 text-xs">{{ $record->created_at->format('d M Y, H:i') }}</p>
                <h2 class="text-gray-800 font-semibold mt-1 text-sm">{{ $record->dokterAnestesi->nm_dokter ?? 'Dokter N/A' }}</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $record->penataAnestesi->nama ?? 'Penata N/A' }}</p>
            </div>
            <div class="mt-4 flex justify-between space-x-2">
                <a href="{{ route('monitoring.anestesi.edit', ['monitoringId' => $record->id]) }}" wire:navigate class="flex-1 px-3 py-2 bg-indigo-100 text-indigo-700 rounded-md text-sm text-center hover:bg-indigo-200 transition">
                    Lihat/Edit
                </a>
                <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $record->id]) }}" target="_blank" class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-md text-sm text-center hover:bg-green-200 transition">
                    Cetak
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full p-6 text-center text-gray-500">
            Belum ada riwayat monitoring anestesi untuk pasien ini.
        </div>
        @endforelse
    </div>

</div>
