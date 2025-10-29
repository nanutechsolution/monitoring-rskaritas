<div class="container mx-auto  space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <form wire:submit.prevent="saveTtv">
                <div class="bg-white shadow rounded-lg">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Input Tanda Vital & Observasi (Real-time)</h3>
                    </div>
                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <h4 class="col-span-full font-semibold text-gray-700 text-sm mt-2 mb-1">Tanda Vital Dasar</h4>
                        <div>
                            <label for="suhu" class="block text-sm font-medium text-gray-700">Suhu (°C)</label>
                            <input type="number" step="0.1" wire:model.defer="ttvState.suhu" id="suhu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.suhu') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                            <label for="map" class="block text-sm font-medium text-gray-700">MAP</label>
                            <input type="number" wire:model.defer="ttvState.map" id="map" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('ttvState.map') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                        <div class="col-span-full mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-2">Ventilator & Jalan Napas</h4>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <label for="cuff_pressure" class="block text-sm font-medium text-gray-700">Cuff Pressure</label>
                                <input type="number" step="0.1" wire:model.defer="ttvState.cuff_pressure" id="cuff_pressure" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.cuff_pressure') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
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
                            <div>
                                <label for="vent_pinsp" class="block text-sm font-medium text-gray-700">Vent. P Insp</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_pinsp" id="vent_pinsp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('ttvState.ventilator_pinsp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_ie" class="block text-sm font-medium text-gray-700">Vent. I:E Ratio</label>
                                <input type="text" wire:model.defer="ttvState.ventilator_ie_ratio" id="vent_ie" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="cth: 1:2">
                                @error('ttvState.ventilator_ie_ratio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-span-full mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-2">Neurologis</h4>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-3 gap-4">
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
                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
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
                        <div class="col-span-full mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-2">Observasi Lain</h4>
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
                        <div class="col-span-1 md:col-span-1">
                            <label for="fall_risk" class="block text-sm font-meadium text-gray-700">Risiko Jatuh</label>
                            <input type="text" wire:model.defer="ttvState.fall_risk_assessment" id="fall_risk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Skor/Kategori/Ket">
                            @error('ttvState.fall_risk_assessment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-full mt-4 border-t pt-4">
                            <h4 class="font-semibold text-gray-700 text-sm mb-2">Catatan & Tindakan</h4>
                        </div>
                        <div class="col-span-2 md:col-span-2">
                            <label for="clinical_note" class="block text-sm font-medium text-gray-700">Catatan Klinis / Masalah</label>
                            <textarea wire:model.defer="ttvState.clinical_note" id="clinical_note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500" placeholder="Observasi penting, masalah, hasil lab singkat..."></textarea>
                            @error('ttvState.clinical_note') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-2">
                            <label for="medication_administration" class="block text-sm font-medium text-gray-700">Tindakan / Pemberian Obat</label>
                            <textarea wire:model.defer="ttvState.medication_administration" id="medication_administration" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Catat tindakan atau obat yang diberikan..."></textarea>
                            @error('ttvState.medication_administration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Observasi
                        </button>
                        @if (session()->has('message-ttv'))
                        <span class="text-green-600 text-sm font-medium">
                            {{ session('message-ttv') }}
                        </span>
                        @endif
                    </div>
                </div>
            </form>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-lg h-full flex flex-col" wire:key="parenteral-form" x-data="{
                        activeVolumes: {},
                         newParenteralName: null,
                         newParenteralVolume: null,
                         saveActive() {
                            $wire.saveActiveParenteralVolumes(this.activeVolumes)
                                 .then(() => { this.activeVolumes = {}; });
                         },
                         saveNew() {
                         $wire.addNewParenteral(this.newParenteralName, this.newParenteralVolume)
                                 .then(() => { this.newParenteralName = null; this.newParenteralVolume = null; });
                         }
                     }">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Input Volume Parenteral Aktif</h3>
                    </div>

                    {{-- Form untuk Volume Cairan Aktif --}}
                    <form @submit.prevent="saveActive" class="flex-grow flex flex-col">
                        <div class="p-4 space-y-3 flex-grow">
                            @forelse($this->usedParenteralFluids as $fluid)
                            <div class="grid grid-cols-3 gap-2 items-center">
                                <label for="vol_par_{{ $loop->index }}" class="col-span-1 block text-sm font-medium text-gray-700 truncate">{{ $fluid }}</label>
                                <div class="col-span-2">
                                    <input type="number" x-model="activeVolumes['{{ $fluid }}']" id="vol_par_{{ $loop->index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="ml">
                                    @error('activeParenteralVolumes.' . $fluid) <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada cairan parenteral yang diinput hari ini.</p>
                            @endforelse
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-end items-center mt-auto">
                            <button type="submit" class="bg-green-600 text-white px-5 py-2 rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" @if($this->usedParenteralFluids->isEmpty()) disabled @endif>
                                Simpan Volume Aktif
                            </button>
                        </div>
                    </form>

                    {{-- Form untuk Tambah Cairan Baru --}}
                    <form @submit.prevent="saveNew" class="border-t">
                        <div class="p-4 space-y-2">
                            <h4 class="text-sm font-semibold text-gray-800">Tambah Cairan Baru:</h4>
                            <div>
                                <input type="text" x-model="newParenteralName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Nama Cairan Baru (cth: Aminofluid)">
                                @error('newParenteralState.jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="number" x-model="newParenteralVolume" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Volume Awal (ml)">
                                @error('newParenteralState.volume') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-end items-center rounded-b-lg">
                            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm">
                                Tambah & Simpan Baru
                            </button>
                        </div>
                    </form>

                    @if (session()->has('message-parenteral'))
                    <div class="p-2 border-t bg-green-50 text-green-700 text-sm font-medium text-center">
                        {{ session('message-parenteral') }}
                    </div>
                    @endif
                </div>
                <div class="bg-white shadow rounded-lg h-full flex flex-col" wire:key="enteral-form" x-data="{
                        activeVolumes: {},
                         newEnteralName: null,
                         newEnteralVolume: null,
                         saveActive() {
                            $wire.saveActiveEnteralVolumes(this.activeVolumes)
                                 .then(() => { this.activeVolumes = {}; });
                         },
                         saveNew() {
                         $wire.addNewEnteral(this.newEnteralName, this.newEnteralVolume)
                                 .then(() => { this.newEnteralName = null; this.newEnteralVolume = null; });
                         }
                     }">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Input Volume Enteral / Minum Aktif</h3>
                    </div>

                    {{-- Form untuk Volume Enteral Aktif --}}
                    <form @submit.prevent="saveActive" class="flex-grow flex flex-col">
                        <div class="p-4 space-y-3 flex-grow">
                            @forelse($this->usedEnteralFluids as $fluid)
                            <div class="grid grid-cols-3 gap-2 items-center">
                                <label for="vol_en_{{ $loop->index }}" class="col-span-1 block text-sm font-medium text-gray-700 truncate">{{ $fluid }}</label>
                                <div class="col-span-2">
                                    <input type="number" x-model="activeVolumes['{{ $fluid }}']" id="vol_en_{{ $loop->index }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="ml">
                                    @error('activeEnteralVolumes.' . $fluid) <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 text-center py-4">Belum ada makanan/minuman enteral yang diinput hari ini.</p>
                            @endforelse
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-end items-center mt-auto">
                            <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200" @if($this->usedEnteralFluids->isEmpty()) disabled @endif>
                                Simpan Volume Aktif
                            </button>
                        </div>
                    </form>

                    {{-- Form untuk Tambah Enteral Baru --}}
                    <form @submit.prevent="saveNew" class="border-t">
                        <div class="p-4 space-y-2">
                            <h4 class="text-sm font-semibold text-gray-800">Tambah Enteral / Minum Baru:</h4>
                            <div>
                                <input type="text" x-model="newEnteralName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Nama Baru (cth: Sonde Susu Y, Teh)">
                                @error('newEnteralState.jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="number" x-model="newEnteralVolume" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Volume Awal (ml)">
                                @error('newEnteralState.volume') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 flex justify-end items-center rounded-b-lg">
                            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm">
                                Tambah & Simpan Baru
                            </button>
                        </div>
                    </form>

                    @if (session()->has('message-enteral'))
                    <div class="p-2 border-t bg-blue-50 text-blue-700 text-sm font-medium text-center">
                        {{ session('message-enteral') }}
                    </div>
                    @endif
                </div>
                <div wire:key="keluar-form">
                    <form wire:submit.prevent="saveCairanKeluar">
                        <div class="bg-white shadow rounded-lg h-full flex flex-col">
                            <div class="p-4 border-b">
                                <h3 class="text-lg font-semibold text-gray-900">Input Cairan Keluar</h3>
                            </div>
                            <div class="p-4 space-y-4 flex-grow">
                                <div>
                                    <label for="ck_jenis_select" class="block text-sm font-medium text-gray-700">Jenis Cairan Keluar</label>
                                    <select wire:model.defer="cairanKeluarState.cairan_keluar_jenis" id="ck_jenis_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Irigasi CM">Irigasi CM</option>
                                        <option value="Irigasi CK">Irigasi CK</option>
                                        <option value="Urine">Urine</option>
                                        <option value="NGT">NGT</option>
                                        <option value="Drain/WSD 1">Drain/WSD 1</option>
                                        <option value="Drain/WSD 2">Drain/WSD 2</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @error('cairanKeluarState.cairan_keluar_jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="ck_volume" class="block text-sm font-medium text-gray-700">Volume (ml)</label>
                                    <input type="number" wire:model.defer="cairanKeluarState.cairan_keluar_volume" id="ck_volume" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    @error('cairanKeluarState.cairan_keluar_volume') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg mt-auto">
                                <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    Simpan Keluar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1 space-y-6">
            <form wire:submit.prevent="saveIwl">
                <div class="bg-white shadow rounded-lg">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Input IWL Harian</h3>
                    </div>
                    <div class="p-4">
                        <label for="iwlInput" class="block text-sm font-medium text-gray-700">IWL (ml / 24 jam)</label>
                        <input type="number" step="0.1" wire:model.defer="iwlInput" id="iwlInput" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('iwlInput') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="p-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow text-sm font-medium hover:bg-blue-700">
                            Simpan IWL
                        </button>
                        @if (session()->has('message-iwl'))
                        <span class="text-green-600 text-sm font-medium">{{ session('message-iwl') }}</span>
                        @endif
                    </div>
                </div>
            </form>
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
        </div>
    </div>

</div>
