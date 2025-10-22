<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header & Search Form -->
            <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                        Pasien Perawatan Intensif
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Pilih pasien untuk membuka lembar monitor.
                    </p>
                </div>

                <form wire:submit="runSearch" class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 sm:flex-auto">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" wire:model="search" placeholder="Cari nama atau No. RM..."
                               class="block w-full sm:w-64 md:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <button type="submit"
                            class="mt-2 sm:mt-0 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <span wire:loading.remove wire:target="runSearch">Cari</span>
                        <span wire:loading wire:target="runSearch">Mencari...</span>
                    </button>
                </form>
            </div>

            <!-- List Pasien -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul role="list" class="divide-y divide-gray-200">

                    @if ($searchPerformed)
                        @forelse ($patients as $patient)
                            <li class="hover:bg-blue-50/50 transition duration-150 ease-in-out">
                                <a href="{{ route('patient.monitor', ['no_rawat' => $patient->no_rawat]) }}" wire:navigate class="block w-full px-4 py-4 sm:px-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-lg font-semibold text-blue-700 truncate">
                                                {{ $patient->nm_pasien }}
                                            </p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <span class="font-medium">RM:</span> {{ $patient->no_rkm_medis }}
                                            </p>
                                            <div class="mt-2 flex items-center text-sm text-gray-600">
                                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 10a7 7 0 10-14 0c0 2.493 1.698 4.988 3.355 6.584a13.733 13.733 0 002.273 1.765 11.842 11.842 0 00.757.433.62.62 0 00.28.14l.018.008.006.003zM10 11.25a1.25 1.25 0 100-2.5 1.25 1.25 0 000 2.5z" clip-rule="evenodd" />
                                                </svg>
                                                {{ $patient->nm_bangsal }}
                                            </div>
                                        </div>
                                        <div class="ml-0 sm:ml-4 flex-shrink-0">
                                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <div class="text-center px-4 sm:px-6 py-12">
                                <svg class="mx-auto h-12 w-12 sm:h-14 sm:w-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Hasil Tidak Ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Tidak ada pasien yang cocok dengan kriteria pencarian Anda.</p>
                            </div>
                        @endforelse
                    @else
                        <div class="text-center px-4 sm:px-6 py-12">
                            <svg class="mx-auto h-12 w-12 sm:h-14 sm:w-14 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Mulai Mencari</h3>
                            <p class="mt-1 text-sm text-gray-500">Masukkan nama atau nomor rekam medis pasien untuk memulai.</p>
                        </div>
                    @endif

                </ul>
            </div>

        </div>
    </div>
</div>
