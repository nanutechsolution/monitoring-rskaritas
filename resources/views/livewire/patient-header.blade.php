<div
    x-data="{ open: false }"
    class="bg-gradient-to-r from-blue-50 via-indigo-50 to-purple-50 p-5 rounded-2xl shadow-md border border-indigo-100"
>
    {{-- HEADER UTAMA --}}
    <div class="flex justify-between items-center border-b border-indigo-200 pb-3">
        <div>
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.485 0 4.797.607 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div class="font-semibold text-gray-900 text-lg tracking-wide">
                    {{ $nama_pasien ?? 'Nama Pasien' }}
                </div>
            </div>
            <div class="text-gray-500 text-sm mt-1">RM: <span class="font-medium">{{ $no_rkm_medis ?? '-' }}</span></div>
        </div>

        {{-- Tombol Expand --}}
        <button
            @click="open = !open"
            class="flex items-center px-3 py-1.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg text-sm font-medium transition"
        >
            <span x-text="open ? 'Sembunyikan' : 'Lihat Detail'"></span>
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    {{-- DETAIL --}}
    <div
        class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 text-sm"
        x-show="open"
        x-transition
        x-cloak
    >
        {{-- Kolom 1 --}}
        <div class="bg-white border border-indigo-100 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-indigo-700 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Data Pasien
            </h4>
            <p><span class="font-medium text-gray-700">Jenis Kelamin:</span> {{ $jk ?? '-' }}</p>
            <p><span class="font-medium text-gray-700">Lahir:</span> {{ $tgl_lahir ?? '-' }}</p>
            <p><span class="font-medium text-gray-700">Usia:</span> {{ $umur_bayi !== null ? $umur_bayi . ' hari' : '-' }}</p>
            @if($umur_koreksi !== null)
                <p class="text-xs text-gray-500">(Koreksi: {{ $umur_koreksi }} minggu)</p>
            @endif
        </div>

        {{-- Kolom 2 --}}
        <div class="bg-white border border-blue-100 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-blue-700 mb-2">Kelahiran</h4>
            <p><span class="font-medium text-gray-700">Berat Lahir:</span> {{ $berat_lahir ? $berat_lahir . ' gr' : '-' }}</p>
            <p><span class="font-medium text-gray-700">Persalinan:</span> {{ $cara_persalinan ?? '-' }}</p>
        </div>

        {{-- Kolom 3 --}}
        <div class="bg-white border border-purple-100 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-purple-700 mb-2">Perawatan</h4>
            <p><span class="font-medium text-gray-700">Masuk:</span> {{ $tgl_masuk ?? '-' }}</p>
            <p><span class="font-medium text-gray-700">DPJP:</span> {{ $nm_dokter ?? '-' }}</p>
            <p><span class="font-medium text-gray-700">Dx Awal:</span> {{ $diagnosa_awal ?? '-' }}</p>
            <p><span class="font-medium text-gray-700">Asal:</span> {{ $asal_bangsal ?? '-' }}</p>
        </div>

        {{-- Kolom 4 --}}
        <div class="bg-white border border-pink-100 rounded-xl p-3 shadow-sm">
            <h4 class="font-semibold text-pink-700 mb-2">Administrasi</h4>
            <p><span class="font-medium text-gray-700">Jaminan:</span> {{ $jaminan ?? '-' }}</p>
            {{-- <p><span class="font-medium text-gray-700">Status:</span> {{ $status_rujukan ?? '-' }}</p> --}}
        </div>
    </div>
</div>
