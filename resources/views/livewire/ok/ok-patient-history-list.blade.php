<div x-data="{ showFilter: false }" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl font-semibold text-primary-700 dark:text-primary-300 leading-tight">
                    Riwayat Pasien Intra Anestesi
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Pilih pasien untuk melihat semua .
                </p>
            </div>
            <button @click="showFilter = !showFilter" class="sm:hidden inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                       border border-primary-300 dark:border-primary-700
                       text-primary-700 dark:text-primary-300
                       bg-white dark:bg-gray-800
                       hover:bg-primary-50 dark:hover:bg-gray-700
                       shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                Filter
            </button>
        </div>

        <div x-show="showFilter || window.innerWidth >= 640" x-transition.duration.200ms class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 sm:block" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2 md:col-span-3">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama / RM</label>
                    <div class="relative mt-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Ketik nama atau nomor RM..." class="block w-full pl-10 pr-3 py-2 border rounded-lg shadow-sm sm:text-sm transition
                                      border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700
                                      text-gray-900 dark:text-gray-200
                                      focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="resetFilters" type="button" class="inline-flex items-center px-4 py-2 border shadow-sm text-sm font-medium rounded-lg
                                   border-gray-300 dark:border-gray-600
                                   text-gray-700 dark:text-gray-300
                                   bg-white dark:bg-gray-700
                                   hover:bg-gray-50 dark:hover:bg-gray-600
                                   focus:ring-2 focus:ring-primary-500 transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <div wire:loading.class.delay="opacity-50" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
                <thead class="bg-primary-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Info</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-primary-700 dark:text-primary-300">
                                {{ $patient->pasien->nm_pasien ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                No Rawat: {{ $patient->no_rawat ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                RM: {{ $patient->pasien->no_rkm_medis ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $patient->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }},
                            {{ \Carbon\Carbon::parse($patient->pasien->tgl_lahir)->age }} Th
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3 flex justify-start">
                            <a href="{{ route('monitoring.anestesi.show', ['monitoringId' => $patient->intraAnesthesiaMonitoring->id]) }}" wire:navigate class="text-primary-600 hover:text-primary-800
                                      dark:text-primary-400 dark:hover:text-primary-300" title="Lihat Data">
                                Lihat
                            </a>
                            <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $patient->intraAnesthesiaMonitoring->id]) }}" target="_blank" class="text-green-600 hover:text-green-800
                                      dark:text-green-400 dark:hover:text-green-300 " title="Cetak Dokumen">
                                Cetak
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                            Tidak ada pasien ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="divide-y divide-gray-200 dark:divide-gray-700 md:hidden">
                @forelse ($patients as $patient)
                <div class="px-4 py-4 hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-base font-semibold text-primary-700 dark:text-primary-300">
                                {{ $patient->pasien->nm_pasien ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                No Rawat: {{ $patient->no_rawat ?? '-' }}
                            </p>
                        </div>

                        <div class="space-x-2 flex">
                             <a href="{{ route('monitoring.anestesi.show', ['monitoringId' => $patient->intraAnesthesiaMonitoring->id]) }}" wire:navigate class="flex-1 px-3 py-2
                          bg-yellow-100 dark:bg-yellow-900
                          text-yellow-700 dark:text-yellow-200
                          rounded-md text-sm text-center font-medium
                          hover:bg-yellow-200 dark:hover:bg-yellow-700 transition">
                            Lihat
                        </a>
                        <a href="{{ route('monitoring.anestesi.print', ['monitoringId' => $patient->intraAnesthesiaMonitoring->id]) }}" target="_blank" class="flex-1 px-3 py-2
                          bg-green-100 dark:bg-green-900
                          text-green-700 dark:text-green-200
                          rounded-md text-sm text-center font-medium
                          hover:bg-green-200 dark:hover:bg-green-700 transition">
                            Cetak
                        </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center px-4 py-12 text-gray-500 dark:text-gray-400 text-sm">
                    Tidak ada pasien ditemukan.
                </div>
                @endforelse
            </div>

            @if($patients->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
                {{ $patients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
