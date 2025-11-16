<div x-data="{ showFilter: false }" class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl font-semibold text-primary-700 dark:text-primary-300 leading-tight">
                 Riwayat Pasien Monitoring 24 Jam PICU
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Pilih filter atau cari pasien PICU.
                </p>
            </div>
            {{-- Filter Mobile --}}
            <button @click="showFilter = !showFilter" class="sm:hidden px-3 py-2 text-sm rounded-md border text-primary-700 dark:text-primary-300 bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700">
                Filter
            </button>
        </div>

        {{-- FILTER --}}
        <div x-show="showFilter || window.innerWidth >= 640" x-transition.duration.200ms class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 sm:block" x-cloak>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                {{-- Search --}}
                <div class="sm:col-span-2 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cari Nama / RM</label>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama atau nomor RM..." class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500 p-2 shadow-sm">
                </div>

                {{-- Reset --}}
                <div class="flex justify-start md:justify-end">
                    <button wire:click="resetFilters" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg shadow transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>


        {{-- LIST PASIEN --}}
        <div wire:loading.class.delay="opacity-50" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">

            {{-- TABLE DESKTOP --}}
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 hidden md:table">
                <thead class="bg-primary-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Pasien</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">No Rawat</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-primary-800 dark:text-primary-200 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 font-semibold text-primary-700 dark:text-primary-300">{{ $patient->pasien->nm_pasien ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $patient->no_rawat }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('patient.picu.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm shadow">Lihat Riwayat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada pasien PICU ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- CARD MOBILE --}}
            <div class="md:hidden space-y-4">
                @forelse ($patients as $patient)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-primary-700 dark:text-primary-300">{{ $patient->pasien->nm_pasien ?? '-' }}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">No Rawat: {{ $patient->no_rawat }}</p>
                    </div>
                    <a href="{{ route('patient.picu.history', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm shadow">Lihat</a>
                </div>
                @empty
                <div class="text-center text-gray-500 dark:text-gray-400">Tidak ada pasien PICU ditemukan.</div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
                {{ $patients->links() }}
            </div>

        </div>
    </div>
</div>
