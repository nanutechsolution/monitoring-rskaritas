<div wire:poll.5s class="container mx-auto p-6 space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <form wire:submit.prevent="saveTtv">
                <div class="bg-white shadow rounded-lg">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Input Tanda Vital & Observasi (Real-time)</h3>
                    </div>

                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label for="suhu" class="block text-sm font-medium text-gray-700">Suhu (°C)</label>
                            <input type="number" step="0.1" wire:model.defer="ttvState.suhu" id="suhu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.xs') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="nadi" class="block text-sm font-medium text-gray-700">Nadi (x/mnt)</label>
                            <input type="number" wire:model.defer="ttvState.nadi" id="nadi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.nadi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="rr" class="block text-sm font-medium text-gray-700">RR (x/mnt)</label>
                            <input type="number" wire:model.defer="ttvState.rr" id="rr" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.rr') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="spo2" class="block text-sm font-medium text-gray-700">SpO2 (%)</label>
                            <input type="number" wire:model.defer="ttvState.spo2" id="spo2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.spo2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tensi_sistol" class="block text-sm font-medium text-gray-700">Tensi (Sistol)</label>
                            <input type="number" wire:model.defer="ttvState.tensi_sistol" id="tensi_sistol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.tensi_sistol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tensi_diastol" class="block text-sm font-medium text-gray-700">Tensi (Diastol)</label>
                            <input type="number" wire:model.defer="ttvState.tensi_diastol" id="tensi_diastol" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.tensi_diastol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="irama_ekg" class="block text-sm font-medium text-gray-700">Irama EKG</label>
                            <input type="text" wire:model.defer="ttvState.irama_ekg" id="irama_ekg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="cth: Sinus">
                            @error('ttvState.irama_ekg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="cvp" class="block text-sm font-medium text-gray-700">CVP</label>
                            <input type="number" wire:model.defer="ttvState.cvp" id="cvp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.cvp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-5 gap-4 border-t pt-4 mt-2">
                            <div>
                                <label for="et_tt" class="block text-sm font-medium text-gray-700">ET / TT</label>
                                <input type="text" wire:model.defer="ttvState.et_tt" id="et_tt" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.et_tt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="cuff_pressure" class="block text-sm font-medium text-gray-700">Cuff Pressure</label>
                                <input type="number" step="0.1" wire:model.defer="ttvState.cuff_pressure" id="cuff_pressure" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.cuff_pressure') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2 md:col-span-3"></div> {{-- Spacer --}}

                            <div>
                                <label for="vent_mode" class="block text-sm font-medium text-gray-700">Mode Ventilator</label>
                                <input type="text" wire:model.defer="ttvState.ventilator_mode" id="vent_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="cth: PCV, SIMV">
                                @error('ttvState.ventilator_mode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_f" class="block text-sm font-medium text-gray-700">Vent. F (Freq)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_f" id="vent_f" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.ventilator_f') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_tv" class="block text-sm font-medium text-gray-700">Vent. TV (Vol)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_tv" id="vent_tv" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.ventilator_tv') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_fio2" class="block text-sm font-medium text-gray-700">Vent. FiO2 (%)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_fio2" id="vent_fio2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.ventilator_fio2') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_peep" class="block text-sm font-medium text-gray-700">Vent. PEEP</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_peep" id="vent_peep" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.ventilator_peep') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-3 gap-4 border-t pt-4 mt-2">
                            <div>
                                <label for="gcs_e" class="block text-sm font-medium text-gray-700">GCS (E)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_e" id="gcs_e" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.gcs_e') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="gcs_v" class="block text-sm font-medium text-gray-700">GCS (V)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_v" id="gcs_v" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.gcs_v') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="gcs_m" class="block text-sm font-medium text-gray-700">GCS (M)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_m" id="gcs_m" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.gcs_m') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-4 gap-4 border-t pt-4 mt-2">
                            <div>
                                <label for="pupil_left_size" class="block text-sm font-medium text-gray-700">Pupil Kiri (mm)</label>
                                <input type="number" wire:model.defer="ttvState.pupil_left_size_mm" id="pupil_left_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.pupil_left_size_mm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_left_reflex" class="block text-sm font-medium text-gray-700">Reflek Kiri (+/-)</label>
                                <input type="text" wire:model.defer="ttvState.pupil_left_reflex" id="pupil_left_reflex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="+ / -">
                                @error('ttvState.pupil_left_reflex') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_right_size" class="block text-sm font-medium text-gray-700">Pupil Kanan (mm)</label>
                                <input type="number" wire:model.defer="ttvState.pupil_right_size_mm" id="pupil_right_size" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.pupil_right_size_mm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_right_reflex" class="block text-sm font-medium text-gray-700">Reflek Kanan (+/-)</label>
                                <input type="text" wire:model.defer="ttvState.pupil_right_reflex" id="pupil_right_reflex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="+ / -">
                                @error('ttvState.pupil_right_reflex') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="kesadaran" class="block text-sm font-medium text-gray-700">Kesadaran</label>
                            <input type="text" wire:model.defer="ttvState.kesadaran" id="kesadaran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="cth: CM, Apatis">
                            @error('ttvState.kesadaran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="nyeri" class="block text-sm font-medium text-gray-700">Skala Nyeri (0-10)</label>
                            <input type="number" wire:model.defer="ttvState.nyeri" id="nyeri" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.nyeri') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan TTV & Observasi
                        </button>

                        @if (session()->has('message-ttv'))
                        <span class="text-green-600 text-sm font-medium">
                            {{ session('message-ttv') }}
                        </span>
                        @endif
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <form wire:submit.prevent="saveCairanMasuk">
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Input Cairan Masuk</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="cm_jenis" class="block text-sm font-medium text-gray-700">Jenis Cairan Masuk</label>
                                <input type="text" wire:model.defer="cairanMasukState.cairan_masuk_jenis" id="cm_jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="cth: Infus RL, Minum, Enteral">
                                @error('cairanMasukState.cairan_masuk_jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="cm_volume" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                                <input type="number" wire:model.defer="cairanMasukState.cairan_masuk_volume" id="cm_volume" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('cairanMasukState.cairan_masuk_volume') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                            <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                Simpan Masuk
                            </button>
                            @if (session()->has('error-cairan-masuk'))
                            <span class="text-red-600 text-sm font-medium">{{ session('error-cairan-masuk') }}</span>
                            @endif
                        </div>
                    </div>
                </form>

                <form wire:submit.prevent="saveCairanKeluar">
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Input Cairan Keluar</h3>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="ck_jenis" class="block text-sm font-medium text-gray-700">Jenis Cairan Keluar</label>
                                <input type="text" wire:model.defer="cairanKeluarState.cairan_keluar_jenis" id="ck_jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="cth: Urine, NGT, Drain">
                                @error('cairanKeluarState.cairan_keluar_jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="ck_volume" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                                <input type="number" wire:model.defer="cairanKeluarState.cairan_keluar_volume" id="ck_volume" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('cairanKeluarState.cairan_keluar_volume') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                Simpan Keluar
                            </button>
                            @if (session()->has('error-cairan-keluar'))
                            <span class="text-red-600 text-sm font-medium">{{ session('error-cairan-keluar') }}</span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>


            {{-- Notifikasi Sukses Cairan (global) --}}
            @if (session()->has('message-cairan'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow" role="alert">
                <p>{{ session('message-cairan') }}</p>
            </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

                <form wire:submit.prevent="saveClinicalNote">
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Catatan Klinis / Masalah</h3>
                        </div>
                        <div class="p-4">
                            <textarea wire:model.defer="clinicalNoteState" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" placeholder="Tulis observasi penting, masalah, hasil lab singkat, dll..."></textarea>
                            @error('clinicalNoteState') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                            <button type="submit" class="bg-yellow-600 text-white px-5 py-2 rounded-md shadow hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                Simpan Catatan
                            </button>
                            @if (session()->has('message-note'))
                            <span class="text-green-600 text-sm font-medium">{{ session('message-note') }}</span>
                            @endif
                        </div>
                    </div>
                </form>

                <form wire:submit.prevent="saveDoctorInstruction">
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Instruksi Dokter / Tindakan</h3>
                        </div>
                        <div class="p-4">
                            <textarea wire:model.defer="instructionState" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Tulis instruksi obat, tindakan, rencana, dll..."></textarea>
                            @error('instructionState') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                            <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded-md shadow hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Simpan Instruksi
                            </button>
                            @if (session()->has('message-instruction'))
                            <span class="text-green-600 text-sm font-medium">{{ session('message-instruction') }}</span>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="lg:col-span-1 space-y-6">

            <div class="bg-white shadow rounded-lg sticky top-6">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Balance Cairan (24 Jam)</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Masuk (24h):</span>
                        <span class="font-bold text-lg text-green-600">{{ $this->fluidBalance['totalMasuk'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Keluar (24h):</span>
                        <span class="font-bold text-lg text-red-600">{{ $this->fluidBalance['totalKeluar'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">IWL:</span>
                        <span class="font-bold text-lg text-gray-700">{{ $this->fluidBalance['iwl'] }} ml</span>
                    </div>
                    <hr class="my-2 border-t-2 border-dashed">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-semibold">BALANCE (24 Jam):</span>
                        <span class="font-bold text-blue-700">{{ $this->fluidBalance['balance24Jam'] }} ml</span>
                    </div>

                    <div class="flex justify-between items-center text-sm mt-2">
                        <span class="text-gray-600">Balance Sebelumnya:</span>
                        <span class="font-semibold text-gray-800">{{ $this->fluidBalance['previousBalance'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center text-xl mt-1 pt-1 border-t">
                        <span class="font-semibold">BALANCE KUMULATIF:</span>
                        <span class="font-bold text-purple-700">{{ $this->fluidBalance['cumulativeBalance'] }} ml</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Log Input Terakhir</h3>
                </div>

                {{-- max-h-[800px] agar bisa di-scroll jika log-nya panjang --}}
                <div class="p-4 max-h-[800px] overflow-y-auto space-y-4">

                    @forelse ($this->logRecords as $record)
                    <div class="border-b pb-3">
                        <p class="font-semibold text-gray-800">
                            {{-- Tampilkan Waktu & Petugas --}}
                            <span class="text-blue-600">{{ $record->observation_time->format('H:i') }}</span> -
                            <span class="text-gray-700">{{ $record->inputter->nama ?? 'Sistem' }}</span>
                        </p>
                        <div class="text-sm text-gray-600 pl-4 mt-1">

                            {{-- Tampilkan data yg diinput --}}
                            @if($record->cairan_masuk_volume)
                            <span class="font-medium text-green-700">+ MASUK: {{ $record->cairan_masuk_jenis }} ({{ $record->cairan_masuk_volume }} ml)</span>

                            @elseif($record->cairan_keluar_volume)
                            <span class="font-medium text-red-700">- KELUAR: {{ $record->cairan_keluar_jenis }} ({{ $record->cairan_keluar_volume }} ml)</span>

                            @else
                            <span>Input TTV:
                                @if($record->suhu) Suhu: <span class="font-medium">{{ $record->suhu }}°</span>@endif
                                @if($record->nadi) | Nadi: <span class="font-medium">{{ $record->nadi }}</span>@endif
                                @if($record->tensi_sistol) | Tensi: <span class="font-medium">{{ $record->tensi_sistol }}/{{ $record->tensi_diastol }}</span>@endif
                                @if($record->gcs_total) | GCS: <span class="font-medium">{{ $record->gcs_total }}</span>@endif
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Belum ada inputan.</p>
                    @endforelse

                </div>
            </div>

        </div>

    </div>
</div>
