<div x-data="{ showFilter: false }">
    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-semibold text-primary-700 dark:text-primary-300 leading-tight">
                        Pasien Perawatan Intensif
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Pilih jenis monitor pasien atau gunakan filter pencarian.
                    </p>
                </div>
                {{-- Tombol Filter (Mobile) --}}
                <button @click="showFilter = !showFilter" class="sm:hidden inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                           border border-primary-300 dark:border-primary-700
                           text-primary-700 dark:text-primary-300
                           bg-white dark:bg-gray-800
                           hover:bg-primary-50 dark:hover:bg-gray-700
                           shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M3 12h18M3 20h18" />
                    </svg>
                    Filter
                </button>
            </div>

            {{-- FILTER SECTION --}}
            <div x-show="showFilter || window.innerWidth >= 640" x-transition.duration.200ms class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 sm:block" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    {{-- Filter Tanggal --}}
                    <div>
                        <label for="filterDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Masuk</label>
                        <input type="date" id="filterDate" wire:model.live="filterDate" class="mt-1 block w-full rounded-lg shadow-sm sm:text-sm
                                      border-gray-300 dark:border-gray-600
                                      bg-white dark:bg-gray-700
                                      text-gray-900 dark:text-gray-200
                                      focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    {{-- Filter Bangsal --}}
                    <div>
                        <label for="filterWard" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bangsal</label>
                        <select id="filterWard" wire:model.live="filterWard" class="mt-1 block w-full rounded-lg shadow-sm sm:text-sm
                                       border-gray-300 dark:border-gray-600
                                       bg-white dark:bg-gray-700
                                       text-gray-900 dark:text-gray-200
                                       focus:border-primary-500 focus:ring-primary-500">
                            <option value="">-- Semua Bangsal --</option>
                            @foreach($wards as $ward)
                            <option value="{{ $ward }}">{{ $ward }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Search Input --}}
                    <div class="sm:col-span-2 md:col-span-1">
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

                    <div class="flex justify-end items-end">
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

            {{-- DAFTAR PASIEN --}}
            <div wire:loading.class.delay="opacity-50" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">

                {{-- TABLE (Desktop) --}}
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
                    <thead class="bg-primary-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Info</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Lokasi & Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($patients as $patient)
                        <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-primary-700 dark:text-primary-300">{{ $patient->nm_pasien }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">RM: {{ $patient->no_rkm_medis }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $patient->jk_desc }} <span class="text-xs text-gray-400">({{ $patient->umur }} thn)</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-800 dark:text-gray-100">{{ $patient->nm_bangsal }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Masuk:
                                    {{ \Carbon\Carbon::parse($patient->tgl_masuk)->isoFormat('D MMM YY, HH:mm') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700">ICU</a>
                                    <a href="{{ route('patient.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-700">NICU</a>
                                    <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-700">PICU</a>
                                    <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-200 hover:bg-amber-200 dark:hover:bg-amber-700">Anestesi</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 text-sm">
                                <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4 16h16" />
                                </svg>
                                Tidak ada pasien ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- CARD (Mobile) --}}
                <div class="divide-y divide-gray-200 dark:divide-gray-700 md:hidden">
                    @forelse ($patients as $patient)
                    <div class="px-4 py-4 hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-base font-semibold text-primary-700 dark:text-primary-300">{{ $patient->nm_pasien }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">RM: {{ $patient->no_rkm_medis }}</p>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                {{ $patient->nm_bangsal }}
                            </span>
                        </div>
                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                            <p>{{ $patient->jk_desc }}, {{ $patient->umur }} thn</p>
                            <p>Masuk: {{ \Carbon\Carbon::parse($patient->tgl_masuk)->isoFormat('D MMM YY, HH:mm') }}</p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-2">
                            <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700">ICU</a>
                            <a href="{{ route('patient.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-700">NICU</a>
                            <a href="{{ route('monitoring.picu', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-700">PICU</a>
                            <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs rounded bg-amber-100 dark:bg-amber-900 text-amber-700 dark:text-amber-200 hover:bg-amber-200 dark:hover:bg-amber-700">Anestesi</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center px-4 py-12 text-gray-500 dark:text-gray-400 text-sm">
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4 16h16" />
                        </svg>
                        Tidak ada pasien ditemukan.
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($patients->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
                    {{ $patients->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
