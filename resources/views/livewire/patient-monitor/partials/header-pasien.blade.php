<div x-data="{ open: false }" class="space-y-4">
    <!-- Header ringkas -->
    <div @click="open = !open" class="cursor-pointer bg-gray-100 px-4 py-3 rounded-md flex justify-between items-center shadow-sm">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ $patient->nm_pasien }}</h2>
            <span class="text-sm text-gray-500">RM: {{ $patient->no_rkm_medis }}</span>
        </div>
        <svg x-bind:class="{ 'rotate-180': open }" class="h-5 w-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
    <!-- Konten collapse -->
    <div x-show="open" x-transition.duration.300ms class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6 divide-x divide-gray-200 p-4 bg-white rounded-md shadow-sm">

        {{-- ====================================================== --}}
        {{-- Kolom 1: IDENTITAS PASIEN                              --}}
        {{-- ====================================================== --}}
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $patient->nm_pasien }}</h3>
                <span class="text-sm text-gray-500">RM: {{ $patient->no_rkm_medis }}</span>
            </div>
            <dl class="space-y-2">
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Tgl Lahir</dt>
                    <dd class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($patient->tgl_lahir)->isoFormat('D MMM YYYY') }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Jenis Kelamin</dt>
                    <dd class="font-medium text-gray-800">{{ $patient->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Berat Lahir</dt>
                    <dd class="font-medium text-gray-800">{{ $berat_lahir ?? 'N/A' }} {{ $berat_lahir ? 'gram' : '' }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Persalinan</dt>
                    <dd class="font-medium text-gray-800">{{ $cara_persalinan ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        {{-- ====================================================== --}}
        {{-- Kolom 2: STATUS & USIA                                 --}}
        {{-- ====================================================== --}}
        <div class="space-y-4 md:pl-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Umur Bayi</dt>
                    <dd class="text-2xl font-bold text-teal-600">{{ $umur_bayi }} <span class="text-lg font-medium">hari</span></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hari Rawat Ke</dt>
                    <dd class="text-2xl font-bold text-teal-600">{{ \Carbon\Carbon::parse($patient->tgl_masuk)->diffInDays(now()) + 1 }}</dd>
                </div>
            </div>

            <dl class="space-y-2">
                @if($umur_koreksi !== null)
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Umur Koreksi</dt>
                    <dd class="font-medium text-gray-800">{{ $umur_koreksi }} minggu</dd>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium text-gray-800">{{ $status_rujukan ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between text-sm">
                    <dt class="text-gray-500">Asal Ruangan</dt>
                    <dd class="font-medium text-gray-800">{{ $asal_bangsal ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

        {{-- ====================================================== --}}
        {{-- Kolom 3: INFO KLINIS & ADMINISTRATIF                  --}}
        {{-- ====================================================== --}}
        <div class="space-y-4 md:pl-6">
            <div>
                <span class="inline-flex items-center rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-700 ring-1 ring-inset ring-teal-200">
                    @if($currentCycleId)
                    @php $cycle = \App\Models\MonitoringCycle::find($currentCycleId); @endphp
                    {{ \Carbon\Carbon::parse($cycle->start_time)->isoFormat('dddd, D MMM YYYY') }}
                    @else
                    {{ \Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D MMM YYYY') }}
                    @endif
                </span>
            </div>

            <dl class="space-y-3">
                <div>
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Diagnosis Awal</dt>
                    <dd class="text-sm font-medium text-gray-800 mt-1">{{ $patient->diagnosa_awal }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">DPJP</dt>
                    <dd class="text-sm font-medium text-gray-800 mt-1">{{ $patient->nm_dokter }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jaminan</dt>
                    <dd class="text-sm font-medium text-gray-800 mt-1">{{ $jaminan ?? 'N/A' }}</dd>
                </div>
            </dl>
        </div>

    </div>
</div>
