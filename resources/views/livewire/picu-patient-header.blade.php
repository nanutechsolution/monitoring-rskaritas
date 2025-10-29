<div class{{"p-4 border rounded-md shadow-sm bg-white mb-6"}}>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-header'))
    <div class="p-2 mb-3 text-xs text-green-800 bg-green-100 border border-green-300 rounded-md">
        {{ session('success-header') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">

        {{-- =============================================== --}}
        {{-- === KOLOM 1: DATA PASIEN (Read-Only) === --}}
        {{-- =============================================== --}}
        <div class="space-y-2">
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">Nama Bayi/Anak</span>
                <span class="font-bold text-gray-900">: {{ $regPeriksa->pasien->nm_pasien ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">Tanggal Lahir</span>
                <span class="text-gray-900">: {{ $regPeriksa->pasien->tgl_lahir ? \Carbon\Carbon::parse($regPeriksa->pasien->tgl_lahir)->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">No. RM</span>
                <span class="text-gray-900">: {{ $regPeriksa->pasien->no_rkm_medis ?? '-' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">Jenis Kelamin</span>
                <span class="text-gray-900">: {{ $regPeriksa->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">Umur</span>
                <span class="text-gray-900">: {{ $regPeriksa->umur ?? '-' }}</span>
            </div>
            <div class{{"flex"}}>
                <span class="w-32 font-medium text-gray-600">Hari Rawat Ke</span>
                <span class="text-gray-900">: {{ $hariRawat }}</span>
            </div>
            <div class="flex">
                <span class="w-32 font-medium text-gray-600">Dokter DPJP</span>
                <span class="text-gray-900">: {{ $monitoringSheet->dokter->nm_dokter ?? $monitoringSheet->dokter_dpjp }}</span>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- === KOLOM 2: DATA MEDIS (Bisa Diedit) === --}}
        {{-- =============================================== --}}
        <div class="space-y-3">
            <x-form-input label="Diagnosis" wire:model.blur="diagnosis" wire:change="saveHeader" type="text" />

            <x-form-input label="Umur Kehamilan (Minggu)" wire:model.blur="umur_kehamilan" wire:change="saveHeader" type="text" />

            <x-form-input label="Umur Koreksi (Bulan/Tahun)" wire:model.blur="umur_koreksi" wire:change="saveHeader" type="text" />

            <x-form-input label="Berat Badan Lahir (Kilogram)" wire:model.blur="berat_badan_lahir" wire:change="saveHeader" type="text" />
        </div>

        {{-- =============================================== --}}
        {{-- === KOLOM 3: DATA ADMISI (Bisa Diedit) === --}}
        {{-- =============================================== --}}
        <div class="space-y-3">
            <x-form-input label="Cara Persalinan" wire:model.blur="cara_persalinan" wire:change="saveHeader" type="text" />

            <x-form-input label="Rujukan" wire:model.blur="rujukan" wire:change="saveHeader" type="text" />

            <x-form-input label="Asal Ruangan" wire:model.blur="asal_ruangan" wire:change="saveHeader" type="text" />

            <x-form-input label="Jaminan" wire:model.blur="jaminan" wire:change="saveHeader" type="text" />
        </div>
    </div>
</div>
