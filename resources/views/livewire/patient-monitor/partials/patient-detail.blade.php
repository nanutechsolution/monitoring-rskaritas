 {{-- DETAIL (Toggle & Responsive Grid) --}}
    <div
        class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm"
        x-show="open"
        x-transition
        x-cloak
    >
        {{-- Kolom 1: Jenis Kelamin --}}
        <div class="space-y-1">
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Jenis Kelamin:</span> {{ $jk ?? '-' }}
            </div>
        </div>

        {{-- Kolom 2: Kelahiran & Usia --}}
        <div class="space-y-1">
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Lahir:</span> {{ $tgl_lahir ?? '-' }}
            </div>
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Usia:</span> {{ $umur_bayi !== null ? $umur_bayi . ' hari' : '-' }}
            </div>
            @if($umur_koreksi !== null)
            <div class="text-gray-500 text-xs">(Koreksi: {{ $umur_koreksi }} minggu)</div>
            @endif
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Berat Lahir:</span> {{ $berat_lahir ? $berat_lahir . ' gr' : '-' }}
            </div>
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Persalinan:</span> {{ $cara_persalinan ?? '-' }}
            </div>
        </div>

        {{-- Kolom 3: Perawatan --}}
        <div class="space-y-1">
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Masuk:</span> {{ $tgl_masuk ?? '-' }}
            </div>
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">DPJP:</span> {{ $nm_dokter ?? '-' }}
            </div>
            <div class="text-gray-600 truncate">
                <span class="font-medium text-gray-800">Dx Awal:</span> {{ $diagnosa_awal ?? '-' }}
            </div>
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Asal:</span> {{ $asal_bangsal ?? '-' }}
            </div>
        </div>

        {{-- Kolom 4: Administrasi --}}
        <div class="space-y-1">
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Jaminan:</span> {{ $jaminan ?? '-' }}
            </div>
            <div class="text-gray-600">
                <span class="font-medium text-gray-800">Status:</span> {{ $status_rujukan ?? '-' }}
            </div>
        </div>
    </div>
