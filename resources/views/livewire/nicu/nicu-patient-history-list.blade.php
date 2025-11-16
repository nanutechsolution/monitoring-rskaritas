<div x-data="{ showFilter: true }" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl font-semibold text-primary-700 dark:text-primary-300 leading-tight">
                    Riwayat Monitoring 24 jam Pasien NICU
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Cari pasien berdasarkan nama, RM, atau nomor rawat.
                </p>
            </div>
        </div>
        {{-- FILTER / SEARCH --}}
        <div x-show="showFilter" x-transition.duration.200ms class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 sm:block" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                <div class="sm:col-span-2 md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama / RM / No Rawat</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama atau nomor RM..." class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 p-2">
                </div>

                <div class="flex justify-end items-end">
                    <button wire:click="resetPage" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg shadow">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- TABLE DESKTOP --}}
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
                                RM: {{ $patient->pasien->no_rkm_medis ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                No Rawat: {{ $patient->no_rawat ?? '-' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $patient->jk_desc ?? '-' }}, Umur: {{ $patient->umur ?? '-' }} Th
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('patient.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" class="px-2 py-1 text-xs rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700">
                                Lihat Detail NICU
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

            {{-- CARD MOBILE --}}
            <div class="md:hidden space-y-4">
                @forelse ($patients as $patient)
                <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow hover:shadow-md transition">
                    <div class="flex justify-between items-center mb-2">
                        <div class="font-semibold text-primary-700 dark:text-primary-300">{{ $patient->pasien->nm_pasien ?? '-' }}</div>
                        <a href="{{ route('patient.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" class="px-2 py-1 text-xs rounded bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-200 hover:bg-indigo-200 dark:hover:bg-indigo-700">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">RM: {{ $patient->pasien->no_rkm_medis ?? '-' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">No Rawat: {{ $patient->no_rawat ?? '-' }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Jenis Kelamin: {{ $patient->jk_desc ?? '-' }}, Umur: {{ $patient->umur ?? '-' }} Th</div>
                </div>
                @empty
                <div class="text-center text-gray-500 dark:text-gray-400 text-sm">
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
