<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
   <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4
            border-l-4 border-primary-600 dark:border-primary-500
            flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">

    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
        <div class="flex flex-col">
            <h1 class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ $pasien->nm_pasien ?? 'Pasien N/A' }}
            </h1>
            <p class="text-xs sm:text-base text-gray-600 dark:text-gray-400">
                RM: <strong>{{ $pasien->no_rkm_medis ?? '-' }}</strong> |
                No. Rawat: <strong>{{ $noRawat }}</strong>
            </p>
            <!-- Sembunyikan tanggal lahir & umur di mobile -->
            <p class="hidden sm:block text-sm mt-1 text-gray-500 dark:text-gray-400">
                {{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d M Y') : '-' }} ({{ $pasien->umur ?? '-' }} tahun)
            </p>
        </div>
    </div>

    <div class="flex sm:space-x-3 mt-2 sm:mt-0 w-full sm:w-auto">
        <a href="{{ route('monitoring.anestesi.create', ['noRawat' => str_replace('/', '_', $noRawat)]) }}" wire:navigate
           class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2
                  bg-primary-600 dark:bg-primary-700
                  text-white font-semibold rounded-lg shadow
                  hover:bg-primary-700 dark:hover:bg-primary-600
                  transition duration-150 ease-in-out text-sm sm:text-base
                  focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Formulir Baru
        </a>
    </div>
</div>


    {{-- Flash Message (Sudah Disesuaikan) --}}
    @if (session()->has('success'))
    <div class="bg-green-50 dark:bg-green-900 dark:bg-opacity-50
                border border-green-400 dark:border-green-700
                text-green-800 dark:text-green-200
                px-4 py-3 rounded-md shadow-sm flex items-center space-x-2" role="alert">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
        </svg>
        <span class="text-sm sm:text-base">{{ session('success') }}</span>
    </div>
    @endif

    {{-- ---------------- Desktop Table ---------------- --}}
    <div class="hidden md:block bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dokter Anestesi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penata Anestesi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($history as $record)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $record->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $record->dokterAnestesi->nm_dokter ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $record->penataAnestesi->nama ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">

                            <a href="{{ route('monitoring.anestesi.show', ['monitoringId' => $record->id]) }}" wire:navigate class="text-primary-600 hover:text-primary-800
                                      dark:text-primary-400 dark:hover:text-primary-300" title="Lihat Data">
                                Lihat
                            </a>

                            <a href="{{ route('monitoring.anestesi.edit', ['monitoringId' => $record->id]) }}" wire:navigate class="text-yellow-600 hover:text-yellow-800
                                      dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit Data">
                                Edit
                            </a>

                            <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $record->id]) }}" target="_blank" class="text-green-600 hover:text-green-800
                                      dark:text-green-400 dark:hover:text-green-300" title="Cetak Dokumen">
                                Cetak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">Belum ada riwayat monitoring anestesi untuk pasien ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ---------------- Mobile Card ---------------- --}}
    <div class="md:hidden grid grid-cols-1 gap-4">
        @forelse ($history as $record)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 flex flex-col justify-between
                    hover:shadow-xl transition duration-200 border border-gray-100 dark:border-gray-700">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $record->created_at->format('d M Y, H:i') }}</p>
                <h2 class="text-gray-800 dark:text-gray-100 font-semibold mt-1 text-sm">{{ $record->dokterAnestesi->nm_dokter ?? 'Dokter N/A' }}</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">{{ $record->penataAnestesi->nama ?? 'Penata N/A' }}</p>
            </div>
            <div class="mt-4 flex justify-between space-x-2">

                <a href="{{ route('monitoring.anestesi.show', ['monitoringId' => $record->id]) }}" wire:navigate class="flex-1 px-3 py-2
                          bg-yellow-100 dark:bg-yellow-900
                          text-yellow-700 dark:text-yellow-200
                          rounded-md text-sm text-center font-medium
                          hover:bg-yellow-200 dark:hover:bg-yellow-700 transition">
                    Lihat
                </a>

                <a href="{{ route('monitoring.anestesi.edit', ['monitoringId' => $record->id]) }}" wire:navigate class="flex-1 px-3 py-2
                          bg-primary-100 dark:bg-primary-900
                          text-primary-700 dark:text-primary-200
                          rounded-md text-sm text-center font-medium
                          hover:bg-primary-200 dark:hover:bg-primary-700 transition">
                    Edit
                </a>

                <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $record->id]) }}" target="_blank" class="flex-1 px-3 py-2
                          bg-green-100 dark:bg-green-900
                          text-green-700 dark:text-green-200
                          rounded-md text-sm text-center font-medium
                          hover:bg-green-200 dark:hover:bg-green-700 transition">
                    Cetak
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full p-6 text-center text-gray-500 dark:text-gray-400
                    bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-100 dark:border-gray-700">
            Belum ada riwayat monitoring anestesi untuk pasien ini.
        </div>
        @endforelse
    </div>

</div>
