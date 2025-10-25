{{-- resources/views/livewire/patient-header.blade.php --}}
<div class="bg-white p-4 shadow rounded-lg border border-gray-200">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
        {{-- Kolom 1: Nama & RM --}}
        <div>
            <div class="font-semibold text-gray-800">{{ $nama_pasien ?? 'Nama Pasien' }}</div>
            <div class="text-gray-500">RM: {{ $no_rkm_medis ?? '-' }}</div>
            <div class="text-gray-500">{{ $jk ?? '-' }}</div>
        </div>

        {{-- Kolom 2: Kelahiran & Usia --}}
        <div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">Lahir:</span> {{ $tgl_lahir ?? '-' }}</div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">Usia:</span> {{ $umur_bayi !== null ? $umur_bayi . ' hari' : '-' }}</div>
            @if($umur_koreksi !== null)
            <div class="text-gray-500 text-xs">(Koreksi: {{ $umur_koreksi }} minggu)</div>
            @endif
            <div class="text-gray-600"><span class="font-medium text-gray-800">Berat Lahir:</span> {{ $berat_lahir ? $berat_lahir . ' gr' : '-' }}</div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">Persalinan:</span> {{ $cara_persalinan ?? '-' }}</div>
        </div>

        {{-- Kolom 3: Perawatan --}}
        <div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">Masuk:</span> {{ $tgl_masuk ?? '-' }}</div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">DPJP:</span> {{ $nm_dokter ?? '-' }}</div>
            <div class="text-gray-600"><span class="font-medium text-gray-800">Dx Awal:</span> {{ $diagnosa_awal ?? '-' }}</div>
             <div class="text-gray-600"><span class="font-medium text-gray-800">Asal:</span> {{ $asal_bangsal ?? '-' }}</div>
        </div>

         {{-- Kolom 4: Administrasi --}}
        <div>
             <div class="text-gray-600"><span class="font-medium text-gray-800">Jaminan:</span> {{ $jaminan ?? '-' }}</div>
             <div class="text-gray-600"><span class="font-medium text-gray-800">Status:</span> {{ $status_rujukan ?? '-' }}</div>
             {{-- Tambahkan info lain jika perlu --}}
        </div>
    </div>
</div>
