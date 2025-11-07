<div
    x-data="{ open: window.innerWidth >= 640 ? false : false }"
    class="bg-primary-50 dark:bg-gray-800
           p-5 rounded-2xl shadow-md border border-primary-100 dark:border-gray-700"
>
    <div @click="open = !open"
         class="flex justify-between items-center border-b border-primary-200 dark:border-gray-700 pb-3 cursor-pointer sm:cursor-default">
        <div>
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.607 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div class="font-semibold text-gray-900 dark:text-gray-100 text-lg tracking-wide">
                    {{ $nama_pasien ?? 'Nama Pasien' }}
                </div>
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                RM: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $no_rkm_medis ?? '-' }}</span>
            </div>
        </div>

        {{-- Tombol Expand --}}
        <button
            type="button"
            @click.stop="open = !open"
            class="flex items-center px-3 py-1.5
                   bg-white dark:bg-gray-700
                   hover:bg-primary-100 dark:hover:bg-gray-600
                   text-primary-700 dark:text-primary-300
                   border border-primary-200 dark:border-gray-600
                   rounded-lg text-sm font-medium transition shadow-sm"
        >
            <span x-text="open ? 'Sembunyikan' : 'Lihat Detail'"></span>
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    {{-- DETAIL (Konten Collapsible) --}}
    <div
        class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-sm"
        x-show="open"
        x-transition
        x-cloak
    >
        {{-- Kolom 1 (Kartu Detail Putih) --}}
        <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-primary-700 dark:text-primary-300 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Data Pasien
            </h4>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin:</span> <span class="text-gray-900 dark:text-gray-100">{{ $jk ?? '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Lahir:</span> <span class="text-gray-900 dark:text-gray-100">{{ $tgl_lahir ?? '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Usia:</span> <span class="text-gray-900 dark:text-gray-100">{{ $umur_bayi !== null ? $umur_bayi . ' hari' : '-' }}</span></p>
            @if($umur_koreksi !== null)
                <p class="text-xs text-gray-500 dark:text-gray-400">(Koreksi: {{ $umur_koreksi }} minggu)</p>
            @endif
        </div>

        {{-- Kolom 2 (Kartu Detail Putih) --}}
        <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-primary-700 dark:text-primary-300 mb-2">Kelahiran</h4>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Berat Lahir:</span> <span class="text-gray-900 dark:text-gray-100">{{ $berat_lahir ? $berat_lahir . ' gr' : '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Persalinan:</span> <span class="text-gray-900 dark:text-gray-100">{{ $cara_persalinan ?? '-' }}</span></p>
        </div>

        {{-- Kolom 3 (Kartu Detail Putih) --}}
        <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-primary-700 dark:text-primary-300 mb-2">Perawatan</h4>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Masuk:</span> <span class="text-gray-900 dark:text-gray-100">{{ $tgl_masuk ?? '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">DPJP:</span> <span class="text-gray-900 dark:text-gray-100">{{ $nm_dokter ?? '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Dx Awal:</span> <span class="text-gray-900 dark:text-gray-100">{{ $diagnosa_awal ?? '-' }}</span></p>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Asal:</span> <span class="text-gray-900 dark:text-gray-100">{{ $asal_bangsal ?? '-' }}</span></p>
        </div>

        {{-- Kolom 4 (Kartu Detail Putih) --}}
        <div class="bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-primary-700 dark:text-primary-300 mb-2">Administrasi</h4>
            <p><span class="font-medium text-gray-700 dark:text-gray-300">Jaminan:</span> <span class="text-gray-900 dark:text-gray-100">{{ $jaminan ?? '-' }}</span></p>
        </div>
    </div>
</div>
