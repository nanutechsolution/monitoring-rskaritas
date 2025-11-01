<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
                    Pasien Perawatan Intensif
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Pilih jenis monitor untuk pasien yang sesuai atau gunakan filter di bawah.
                </p>
            </div>

            <div class="mb-6 p-4 bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    {{-- Filter Tanggal Masuk --}}
                    <div>
                        <label for="filterDate" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <input type="date" id="filterDate" wire:model.live="filterDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    {{-- Filter Bangsal --}}
                    <div>
                        <label for="filterWard" class="block text-sm font-medium text-gray-700">Bangsal</label>
                        <select id="filterWard" wire:model.live="filterWard" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Semua Bangsal --</option>
                            @foreach($wards as $ward)
                            <option value="{{ $ward }}">{{ $ward }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Search Input --}}
                    <div class="sm:col-span-2 md:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama / RM</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Ketik lalu tunggu..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                        </div>
                    </div>
                    {{-- Tombol Reset --}}
                    <div class="flex justify-end items-end">
                        <button wire:click="resetFilters" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>


            <div wire:loading.class.delay="opacity-50" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                {{-- Tampilkan tabel hanya di layar medium (md) ke atas --}}
                <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi & Masuk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi Monitor</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($patients->total() > 0) {{-- Cek total hasil pagination --}}
                        @foreach ($patients as $patient)
                        <tr class="hover:bg-indigo-50/50 transition duration-150 ease-in-out group">
                            {{-- Kolom Pasien --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-indigo-700 group-hover:text-indigo-800">{{ $patient->nm_pasien }}</div>
                                <div class="text-xs text-gray-500">RM: {{ $patient->no_rkm_medis }}</div>
                            </td>
                            {{-- Kolom Info --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div>{{ $patient->jk_desc }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->umur }} thn</div>
                            </td>
                            {{-- Kolom Lokasi & Masuk --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-800">{{ $patient->nm_bangsal }}</div>
                                <div class="text-xs text-gray-500">
                                    Masuk: {{ \Carbon\Carbon::parse($patient->tgl_masuk)->isoFormat('D MMM YY, HH:mm') }}
                                </div>
                            </td>
                            {{-- Kolom Aksi Monitor --}}
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate title="Buka Modul ICU" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-100 rounded">
                                        ICU
                                    </a>
                                    <a href="{{ route('monitoring.nicu', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate title="Monitor NICU" class="text-purple-600 hover:text-purple-900 p-1 hover:bg-purple-100 rounded">NICU</a>
                                    <a href="{{ route('monitoring.picu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate title="Monitor PICU" class="text-green-600 hover:text-green-900 p-1 hover:bg-green-100 rounded">PICU</a>
                                    <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate title="Monitor Intra Anestesi" class="text-orange-600 hover:text-orange-900 p-1 hover:bg-orange-100 rounded">Intra Anestesi</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        {{-- Empty State Tabel --}}
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Hasil Tidak Ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Tidak ada pasien yang cocok dengan filter atau pencarian Anda.</p>
                                <div class="mt-4">
                                    <button wire:click="resetFilters" type="button" class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Reset Filter
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                {{-- Tampilkan card hanya di layar kecil (di bawah md) --}}
                <div class="divide-y divide-gray-200 md:hidden">
                    @if($patients->total() > 0)
                    @foreach ($patients as $patient)
                    {{-- CARD PASIEN (Mobile View) --}}
                    <div class="px-4 py-4"> {{-- Hapus <a> pembungkus --}}
                        {{-- Bagian Info Pasien --}}
                        <div class="flex items-center justify-between">
                            <div class="truncate">
                                <p class="text-base font-semibold text-indigo-700 truncate">{{ $patient->nm_pasien }}</p>
                                <p class="text-xs text-gray-500">RM: {{ $patient->no_rkm_medis }}</p>
                            </div>
                            <div class="ml-2 flex-shrink-0">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $patient->nm_bangsal }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-2 space-y-1">
                            <p class="flex items-center text-xs text-gray-600"><svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" ...></svg>{{ $patient->jk_desc }}, {{ $patient->umur }} thn</p>
                            <p class="flex items-center text-xs text-gray-600"><svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" ...></svg>Masuk: {{ \Carbon\Carbon::parse($patient->tgl_masuk)->isoFormat('D MMM YY, HH:mm') }}</p>
                        </div>
                        {{-- Tombol Aksi di Bawah Info --}}
                        <div class="mt-3 pt-3 border-t border-gray-100 flex flex-wrap gap-2">
                            <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate title="Buka Modul ICU" class="px-2 py-1 text-xs font-medium rounded bg-indigo-100 text-indigo-700 hover:bg-indigo-200">Monitor ICU</a>
                            <a href="{{ route('monitoring.nicu', ['no_rawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-700 hover:bg-purple-200">Monitor NICU</a>
                            <a href="{{ route('monitoring.picu.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-700 hover:bg-green-200">Monitor PICU</a>
                            <a href="{{ route('monitoring.anestesi.history', ['noRawat' => str_replace('/', '_', $patient->no_rawat)]) }}" wire:navigate class="px-2 py-1 text-xs font-medium rounded bg-orange-100 text-orange-700 hover:bg-orange-200">Monitor Anestesi</a>
                        </div>
                    </div>
                    @endforeach
                    @else
                    {{-- Empty State Mobile --}}
                    <div class="text-center px-4 py-12">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Hasil Tidak Ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">Coba kata kunci atau filter lain.</p>
                        <div class="mt-4"> <button wire:click="resetFilters" type="button" class="inline-flex items-center px-3 py-1 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"> Reset Filter </button> </div>
                    </div>
                    @endif
                </div>

                {{-- Pagination Links --}}
                @if($patients->hasPages())
                <div class="px-4 py-3 border-t border-gray-200 sm:px-6 bg-white"> {{-- Tambah bg-white --}}
                    {{ $patients->links() }}
                </div>
                @endif
            </div>
            {{-- AKHIR LIST PASIEN --}}

        </div>
    </div>
</div>
