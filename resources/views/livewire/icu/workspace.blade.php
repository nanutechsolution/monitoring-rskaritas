<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">

    <div x-data="{ isDetailOpen: window.innerWidth >= 640 ? true : false }" class="bg-white dark:bg-gray-800 shadow-lg rounded-lg border-l-4 border-primary-600 dark:border-primary-500">

        <div @click="isDetailOpen = !isDetailOpen" class="flex justify-between items-center p-4 sm:p-6 cursor-pointer sm:cursor-default">
            <div class="leading-tight">
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100 leading-snug">
                    {{ $registrasi->pasien->nm_pasien }}
                </h2>
                <p class="text-sm sm:text-lg text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">No. RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
            </div>

            <div class="sm:hidden text-primary-600 dark:text-primary-400">
                <svg x-show="!isDetailOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
                <svg x-show="isDetailOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                </svg>
            </div>
        </div>

        <div x-show="isDetailOpen" x-cloak x-transition class="px-4 sm:px-6 pb-4 sm:pb-6 border-t border-gray-100 dark:border-gray-700">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 pt-4">
                <div class="flex-grow">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-2 text-sm text-gray-700 dark:text-gray-300">

                        {{-- Grup Lokasi/Status --}}
                        <div>
                            <p><span class="font-semibold inline-block w-28">Instalasi</span>:
                                {{ $currentInstallasiName ?? 'N/A' }}
                            </p>
                            <p><span class="font-semibold inline-block w-28">Ruang</span>:
                                {{ $currentRoomName ?? 'N/A' }}
                            </p>
                            <p><span class="font-semibold inline-block w-28">Asal Ruangan</span>:
                                {{ $originatingWardName ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Grup Status Rawat --}}
                        <div>
                            <p><span class="font-semibold inline-block w-28">Lembar Observasi</span>:
                                <span class="font-bold text-primary-700 dark:text-primary-300">{{ $cycle->sheet_date->isoFormat('D MMM Y') }}</span>
                            </p>
                            <p><span class="font-semibold inline-block w-28">Hari Rawat Ke</span>:
                                {{ $cycle->hari_rawat_ke ?? 'N/A' }}
                            </p>
                            <p><span class="font-semibold inline-block w-28">Cara Bayar</span>:
                                {{ $registrasi->penjab->png_jawab ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Grup Klinis Penting --}}
                        <div>
                            <p><span class="font-semibold inline-block w-28">Berat Badan</span>:
                                {{ $patientWeight ? $patientWeight . ' kg' : 'N/A' }}
                            </p>
                            <p class="@if($patientAllergy && $patientAllergy !== 'Tidak ada' && $patientAllergy !== '-') text-danger-600 dark:text-danger-400 font-bold @endif">
                                <span class="font-semibold inline-block w-28 @if(!($patientAllergy && $patientAllergy !== 'Tidak ada' && $patientAllergy !== '-')) text-gray-700 dark:text-gray-300 @endif">Alergi</span>:
                                {{ $patientAllergy ?: 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="flex-shrink-0 flex flex-row sm:flex-col sm:items-end gap-2 mt-4 sm:mt-0 pt-4 sm:pt-0 border-t border-gray-100 sm:border-t-0 dark:border-gray-700">
                    <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $registrasi->no_rawat)]) }}" wire:navigate class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 p-1 font-medium transition-colors">
                        &larr; Kembali ke Riwayat
                    </a>
                    <a href="{{ route('monitoring.icu.print', [
                                'noRawat' => str_replace('/', '_', $registrasi->no_rawat),
                                'sheetDate' => $cycle->sheet_date->toDateString()
                            ]) }}" target="_blank" class="inline-block bg-gray-600 dark:bg-gray-600 text-white dark:text-gray-200
                              px-3 py-1 rounded-md shadow text-xs font-medium
                              hover:bg-gray-700 dark:hover:bg-gray-500 transition-colors
                              focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2
                              dark:focus:ring-offset-gray-800">
                        Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-2 flex flex-wrap gap-2 sm:space-x-2">
        @php
        $activeClasses = 'bg-primary-600 text-white';
        $inactiveClasses = 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600';
        $baseClasses = 'flex-1 sm:flex-none px-3 sm:px-5 py-2 rounded-md text-xs sm:text-sm font-medium transition-colors';
        @endphp

        <button wire:click="$set('activeTab', 'input')" class="{{ $baseClasses }} {{ $activeTab == 'input' ? $activeClasses : $inactiveClasses }}">
            Input Real-time
        </button>
        <button wire:click="$set('activeTab', 'laporan')" class="{{ $baseClasses }} {{ $activeTab == 'laporan' ? $activeClasses : $inactiveClasses }}">
            Laporan & Grafik
        </button>
        <button wire:click="$set('activeTab', 'statis')" class="{{ $baseClasses }} {{ $activeTab == 'statis' ? $activeClasses : $inactiveClasses }}">
            Data Statis
        </button>
        <button wire:click="$set('activeTab', 'log')" class="{{ $baseClasses }} {{ $activeTab == 'log' ? $activeClasses : $inactiveClasses }}">
            Log Input
        </button>
    </div>

    <div class="mt-6">
        <div>
            @if ($activeTab == 'input')
            <livewire:icu.monitor-sheet :cycle="$cycle" :key="'input-'.$cycle->id" lazy />

            @elseif ($activeTab == 'laporan')
            <livewire:icu.observation-grid :cycle="$cycle" :key="'laporan-'.$cycle->id" lazy />

            @elseif ($activeTab == 'statis')
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <form wire:submit.prevent="saveStaticData">
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Edit Data Statis Harian</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Mengisi data terapi, penunjang, catatan, alat, tube, dll.</p>
                    </div>

                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        @php
                        $inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                        border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-gray-200
                        focus:border-primary-500 focus:ring-primary-500';
                        $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
                        @endphp

                        <div>
                            <label for="ventilator_notes" class="{{ $labelClasses }}">Catatan Pola Ventilasi Harian</label>
                            <textarea wire:model.defer="staticState.ventilator_notes" id="ventilator_notes" rows="3" class="{{ $inputClasses }}" placeholder="Tulis ringkasan setting ventilator, rencana weaning, dll..."></textarea>
                            @error('staticState.ventilator_notes') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <hr class="dark:border-gray-700">

                        <div>
                            <label for="terapi_parenteral" class="{{ $labelClasses }}">Terapi Obat (Parenteral)</label>
                            <textarea wire:model.defer="staticState.terapi_obat_parenteral" id="terapi_parenteral" rows="4" class="{{ $inputClasses }}"></textarea>
                            @error('staticState.terapi_obat_parenteral') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 pt-2 pl-4 border-l-2 border-gray-200 dark:border-gray-600">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">Target Nutrisi Parenteral (24 Jam)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pl-4">
                                <div>
                                    <label for="par_vol" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Volume (ml)</label>
                                    <input type="number" step="0.1" wire:model.defer="staticState.parenteral_target_volume" id="par_vol" class="{{ $inputClasses }}">
                                    @error('staticState.parenteral_target_volume') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="par_kal" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Kalori (kkal)</label>
                                    <input type="number" wire:model.defer="staticState.parenteral_target_kalori" id="par_kal" class="{{ $inputClasses }}">
                                    @error('staticState.parenteral_target_kalori') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="par_pro" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Protein (g)</label>
                                    <input type="number" wire:model.defer="staticState.parenteral_target_protein" id="par_pro" class="{{ $inputClasses }}">
                                    @error('staticState.parenteral_target_protein') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="par_lem" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Lemak (g)</label>
                                    <input type="number" wire:model.defer="staticState.parenteral_target_lemak" id="par_lem" class="{{ $inputClasses }}">
                                    @error('staticState.parenteral_target_lemak') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="terapi_enteral" class="{{ $labelClasses }}">Terapi Obat (Enteral / Lain-lain)</label>
                            <textarea wire:model.defer="staticState.terapi_obat_enteral_lain" id="terapi_enteral" rows="4" class="{{ $inputClasses }}"></textarea>
                            @error('staticState.terapi_obat_enteral_lain') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2 pt-2 pl-4 border-l-2 border-gray-200 dark:border-gray-600">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">Target Nutrisi Enteral (24 Jam)</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pl-4">
                                <div>
                                    <label for="ent_vol" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Volume (ml)</label>
                                    <input type="number" step="0.1" wire:model.defer="staticState.enteral_target_volume" id="ent_vol" class="{{ $inputClasses }}">
                                    @error('staticState.enteral_target_volume') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="ent_kal" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Kalori (kkal)</label>
                                    <input type="number" wire:model.defer="staticState.enteral_target_kalori" id="ent_kal" class="{{ $inputClasses }}">
                                    @error('staticState.enteral_target_kalori') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="ent_pro" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Protein (g)</label>
                                    <input type="number" wire:model.defer="staticState.enteral_target_protein" id="ent_pro" class="{{ $inputClasses }}">
                                    @error('staticState.enteral_target_protein') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="ent_lem" class="block text-xs font-medium text-gray-600 dark:text-gray-400">Lemak (g)</label>
                                    <input type="number" wire:model.defer="staticState.enteral_target_lemak" id="ent_lem" class="{{ $inputClasses }}">
                                    @error('staticState.enteral_target_lemak') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="pemeriksaan_penunjang" class="{{ $labelClasses }}">Pemeriksaan Penunjang (Lab, EKG, dll)</label>
                            <textarea wire:model.defer="staticState.pemeriksaan_penunjang" id="pemeriksaan_penunjang" rows="4" class="{{ $inputClasses }}"></textarea>
                            @error('staticState.pemeriksaan_penunjang') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="catatan_lain_lain" class="{{ $labelClasses }}">Catatan Lain-lain</label>
                            <textarea wire:model.defer="staticState.catatan_lain_lain" id="catatan_lain_lain" rows="4" class="{{ $inputClasses }}"></textarea>
                            @error('staticState.catatan_lain_lain') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <hr class="dark:border-gray-700">

                        <div class="pt-2">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-md font-semibold text-gray-800 dark:text-gray-100">Alat Terpasang & Tube</h4>
                                <button type="button" wire:click="$set('showDeviceModal', true)" class="bg-primary-600 text-white px-3 py-1 rounded text-xs hover:bg-primary-700
                                                   focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    + Tambah
                                </button>
                            </div>
                            <div class="space-y-3 pl-4 border-l-2 border-gray-200 dark:border-gray-600">
                                @forelse ($cycle->devices as $device)
                                <div class="text-sm border-b pb-2 dark:border-gray-700">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $device->device_name }}
                                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400">({{ $device->device_category }})</span>
                                    </p>
                                    <div class="grid grid-cols-3 gap-x-4 text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        <span><span class="font-medium">Ukuran:</span> {{ $device->ukuran ?: '-' }}</span>
                                        <span><span class="font-medium">Lokasi:</span> {{ $device->lokasi ?: '-' }}</span>
                                        <span><span class="font-medium">Tgl Pasang:</span> {{ $device->tanggal_pasang ? $device->tanggal_pasang->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                                @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 py-2">Belum ada alat/tube terpasang yang dicatat.</p>
                                @endforelse
                            </div>
                        </div>

                        <hr class="mt-4 dark:border-gray-700">
                        <div>
                            <label for="wound_notes" class="{{ $labelClasses }}">Catatan Luka</label>
                            <textarea wire:model.defer="staticState.wound_notes" id="wound_notes" rows="3" class="{{ $inputClasses }}" placeholder="Catat observasi luka, jenis perawatan, dll..."></textarea>
                            @error('staticState.wound_notes') <span class="text-danger-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg flex justify-between items-center">
                        <div>
                            @if (session()->has('message-statis'))
                            <span class="text-green-600 dark:text-green-400 text-sm font-medium">
                                {{ session('message-statis') }}
                            </span>
                            @endif
                        </div>
                        <button type="submit" class="bg-primary-600 text-white px-5 py-2 rounded-md shadow text-sm font-medium
                                           hover:bg-primary-700
                                           focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800">
                            Simpan Data Statis
                        </button>
                    </div>

                </form>
            </div>

            @elseif ($activeTab == 'log')
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-4">
                    <livewire:icu.observation-table :cycle="$cycle" :key="'table-'.$cycle->id" lazy />
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-100 dark:border-gray-700 h-full flex flex-col">
                        <div class="flex items-center justify-between p-4 border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10 rounded-t-xl">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Log Terakhir
                            </h3>
                        </div>
                        <div class="p-4 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700 flex-1">
                            @forelse ($this->logRecords as $record)
                            <div class="py-3">
                                <p class="font-medium text-gray-800 dark:text-gray-100 text-sm">
                                    <span class="text-primary-600 dark:text-primary-400">{{ $record->observation_time->format('H:i') }}</span> –
                                    {{ $record->inputter->nama ?? 'Sistem' }}
                                </p>

                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1 pl-1 leading-snug space-y-1">
                                    @if(!empty($record->cairan_masuk_volume))
                                    <div>
                                        <span class="font-semibold text-green-700 dark:text-green-500">+ Masuk:</span>
                                        {{ $record->cairan_masuk_jenis }} ({{ $record->cairan_masuk_volume }} ml)
                                    </div>
                                    @endif

                                    @if(!empty($record->cairan_keluar_volume))
                                    <div>
                                        <span class="font-semibold text-danger-600 dark:text-danger-400">− Keluar:</span>
                                        {{ $record->cairan_keluar_jenis }} ({{ $record->cairan_keluar_volume }} ml)
                                    </div>
                                    @endif

                                    @if(!empty($record->clinical_note))
                                    <div>
                                        <span class="font-semibold text-yellow-700 dark:text-yellow-500">Catatan:</span>
                                        <span class="whitespace-pre-wrap">{{ Str::limit($record->clinical_note, 150) }}</span>
                                    </div>
                                    @endif

                                    @if(!empty($record->medication_administration))
                                    <div>
                                        <span class="font-semibold text-purple-700 dark:text-purple-400">Tindakan/Obat:</span>
                                        <span class="whitespace-pre-wrap">{{ Str::limit($record->medication_administration, 150) }}</span>
                                    </div>
                                    @endif

                                    @php
                                    $ttvData = collect([
                                    $record->suhu ? "Suhu: {$record->suhu}°C" : null,
                                    $record->nadi ? "Nadi: {$record->nadi}" : null,
                                    $record->tensi_sistol ? "Tensi: {$record->tensi_sistol}/{$record->tensi_diastol}" : null,
                                    $record->map ? "MAP: {$record->map}" : null,
                                    $record->rr ? "RR: {$record->rr}" : null,
                                    $record->spo2 ? "SpO₂: {$record->spo2}%" : null,
                                    ($record->gcs_e || $record->gcs_v || $record->gcs_m) ? "GCS: E{$record->gcs_e}V{$record->gcs_v}M{$record->gcs_m}" : null,
                                    $record->pupil_left_size_mm ? "Pupil Kiri: {$record->pupil_left_size_mm}/{$record->pupil_left_reflex}" : null,
                                    $record->pupil_right_size_mm ? "Pupil Kanan: {$record->pupil_right_size_mm}/{$record->pupil_right_reflex}" : null,
                                    ])->filter()->implode(' | ');
                                    @endphp

                                    @if($ttvData)
                                    <div>
                                        <span class="font-semibold text-primary-700 dark:text-primary-400">Input TTV/Obs:</span>
                                        {!! $ttvData !!}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-6 text-sm">Belum ada data input terbaru.</p>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($showDeviceModal)
    <livewire:icu.device-modal :cycleId="$cycle->id" wire:key="'device-modal-'.$cycle->id" />
    @endif
</div>
