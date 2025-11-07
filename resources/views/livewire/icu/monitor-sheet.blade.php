<div class="container mx-auto space-y-6" x-data="{
         focusFirstError(errors) {
             if (errors && Object.keys(errors).length > 0) {
                 const firstErrorKey = Object.keys(errors)[0];

                 const errorInput = document.querySelector(`[wire\\:model\\.defer='${firstErrorKey}'], [wire\\:model='${firstErrorKey}'], [wire\\:model\\.live='${firstErrorKey}']`);
                 if (errorInput) {
                     errorInput.focus();
                 }
             }
         }
     }" @window:livewire:updated="$nextTick(() => focusFirstError($wire.errors))">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <form wire:submit.prevent="saveTtv">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input Tanda Vital & Observasi (Real-time)</h3>
                    </div>

                    @php
                        $inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                         border-gray-300 dark:border-gray-600
                                         bg-white dark:bg-gray-700
                                         text-gray-900 dark:text-gray-200
                                         focus:border-primary-500 focus:ring-primary-500';
                        $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
                        $errorClasses = 'text-danger-500 dark:text-danger-400 text-xs';
                        $sectionTitleClasses = 'col-span-full font-semibold text-gray-700 dark:text-gray-300 text-sm mt-2 mb-1';
                        $separatorClasses = 'col-span-full mt-4 border-t dark:border-gray-700 pt-4';
                    @endphp

                    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <h4 class="{{ $sectionTitleClasses }}">Tanda Vital Dasar</h4>
                        <div>
                            <label for="suhu" class="{{ $labelClasses }}">Suhu (°C)</label>
                            <input type="number" step="0.1" wire:model.defer="ttvState.suhu" id="suhu" class="{{ $inputClasses }}">
                            @error('ttvState.suhu') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="nadi" class="{{ $labelClasses }}">Nadi (x/mnt)</label>
                            <input type="number" wire:model.defer="ttvState.nadi" id="nadi" class="{{ $inputClasses }}">
                            @error('ttvState.nadi') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="rr" class="{{ $labelClasses }}">RR (x/mnt)</label>
                            <input type="number" wire:model.defer="ttvState.rr" id="rr" class="{{ $inputClasses }}">
                            @error('ttvState.rr') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="spo2" class="{{ $labelClasses }}">SpO2 (%)</label>
                            <input type="number" wire:model.defer="ttvState.spo2" id="spo2" class="{{ $inputClasses }}">
                            @error('ttvState.spo2') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tensi_sistol" class="{{ $labelClasses }}">Tensi (Sistol)</label>
                            <input type="number" wire:model.defer="ttvState.tensi_sistol" id="tensi_sistol" class="{{ $inputClasses }}">
                            @error('ttvState.tensi_sistol') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tensi_diastol" class="{{ $labelClasses }}">Tensi (Diastol)</label>
                            <input type="number" wire:model.defer="ttvState.tensi_diastol" id="tensi_diastol" class="{{ $inputClasses }}">
                            @error('ttvState.tensi_diastol') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="map" class="{{ $labelClasses }}">MAP</label>
                            <input type="number" wire:model.defer="ttvState.map" id="map" class="{{ $inputClasses }}">
                            @error('ttvState.map') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="irama_ekg" class="{{ $labelClasses }}">Irama EKG</label>
                            <input type="text" wire:model.defer="ttvState.irama_ekg" id="irama_ekg" class="{{ $inputClasses }}" placeholder="cth: Sinus">
                            @error('ttvState.irama_ekg') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="cvp" class="{{ $labelClasses }}">CVP</label>
                            <input type="number" wire:model.defer="ttvState.cvp" id="cvp" class="{{ $inputClasses }}">
                            @error('ttvState.cvp') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>

                        <div class="{{ $separatorClasses }}">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-2">Ventilator & Jalan Napas</h4>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <label for="cuff_pressure" class="{{ $labelClasses }}">Cuff Pressure</label>
                                <input type="number" step="0.1" wire:model.defer="ttvState.cuff_pressure" id="cuff_pressure" class="{{ $inputClasses }}">
                                @error('ttvState.cuff_pressure') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_mode" class="{{ $labelClasses }}">Mode Ventilator</label>
                                <input type="text" wire:model.defer="ttvState.ventilator_mode" id="vent_mode" class="{{ $inputClasses }}" placeholder="cth: PCV, SIMV">
                                @error('ttvState.ventilator_mode') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_f" class="{{ $labelClasses }}">Vent. F (Freq)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_f" id="vent_f" class="{{ $inputClasses }}">
                                @error('ttvState.ventilator_f') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_tv" class="{{ $labelClasses }}">Vent. TV (Vol)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_tv" id="vent_tv" class="{{ $inputClasses }}">
                                @error('ttvState.ventilator_tv') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_fio2" class="{{ $labelClasses }}">Vent. FiO2 (%)</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_fio2" id="vent_fio2" class="{{ $inputClasses }}">
                                @error('ttvState.ventilator_fio2') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_peep" class="{{ $labelClasses }}">Vent. PEEP</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_peep" id="vent_peep" class="{{ $inputClasses }}">
                                @error('ttvState.ventilator_peep') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_pinsp" class="{{ $labelClasses }}">Vent. P Insp</label>
                                <input type="number" wire:model.defer="ttvState.ventilator_pinsp" id="vent_pinsp" class="{{ $inputClasses }}">
                                @error('ttvState.ventilator_pinsp') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="vent_ie" class="{{ $labelClasses }}">Vent. I:E Ratio</label>
                                <input type="text" wire:model.defer="ttvState.ventilator_ie_ratio" id="vent_ie" class="{{ $inputClasses }}" placeholder="cth: 1:2">
                                @error('ttvState.ventilator_ie_ratio') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="{{ $separatorClasses }}">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-2">Neurologis</h4>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-3 gap-4">
                            <div>
                                <label for="gcs_e" class="{{ $labelClasses }}">GCS (E)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_e" id="gcs_e" class="{{ $inputClasses }}">
                                @error('ttvState.gcs_e') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="gcs_v" class="{{ $labelClasses }}">GCS (V)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_v" id="gcs_v" class="{{ $inputClasses }}">
                                @error('ttvState.gcs_v') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="gcs_m" class="{{ $labelClasses }}">GCS (M)</label>
                                <input type="number" wire:model.defer="ttvState.gcs_m" id="gcs_m" class="{{ $inputClasses }}">
                                @error('ttvState.gcs_m') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-4 grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                            <div>
                                <label for="pupil_left_size" class="{{ $labelClasses }}">Pupil Kiri (mm)</label>
                                <input type="number" wire:model.defer="ttvState.pupil_left_size_mm" id="pupil_left_size" class="{{ $inputClasses }}">
                                @error('ttvState.pupil_left_size_mm') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_left_reflex" class="{{ $labelClasses }}">Reflek Kiri (+/-)</label>
                                <input type="text" wire:model.defer="ttvState.pupil_left_reflex" id="pupil_left_reflex" class="{{ $inputClasses }}" placeholder="+ / -">
                                @error('ttvState.pupil_left_reflex') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_right_size" class="{{ $labelClasses }}">Pupil Kanan (mm)</label>
                                <input type="number" wire:model.defer="ttvState.pupil_right_size_mm" id="pupil_right_size" class="{{ $inputClasses }}">
                                @error('ttvState.pupil_right_size_mm') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="pupil_right_reflex" class="{{ $labelClasses }}">Reflek Kanan (+/-)</label>
                                <input type="text" wire:model.defer="ttvState.pupil_right_reflex" id="pupil_right_reflex" class="{{ $inputClasses }}" placeholder="+ / -">
                                @error('ttvState.pupil_right_reflex') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="{{ $separatorClasses }}">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-2">Observasi Lain</h4>
                        </div>
                        <div>
                            <label for="kesadaran" class="{{ $labelClasses }}">Kesadaran</label>
                            <input type="text" wire:model.defer="ttvState.kesadaran" id="kesadaran" class="{{ $inputClasses }}" placeholder="cth: CM, Apatis">
                            @error('ttvState.kesadaran') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="nyeri" class="{{ $labelClasses }}">Skala Nyeri (0-10)</label>
                            <input type="number" wire:model.defer="ttvState.nyeri" id="nyeri" class="{{ $inputClasses }}">
                            @error('ttvState.nyeri') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-1 md:col-span-1">
                            <label for="fall_risk" class="{{ $labelClasses }}">Risiko Jatuh</label>
                            <input type="text" wire:model.defer="ttvState.fall_risk_assessment" id="fall_risk" class="{{ $inputClasses }}" placeholder="Skor/Kategori/Ket">
                            @error('ttvState.fall_risk_assessment') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>

                        <div class="{{ $separatorClasses }}">
                            <h4 class="font-semibold text-gray-700 dark:text-gray-300 text-sm mb-2">Catatan & Tindakan</h4>
                        </div>
                        <div class="col-span-2 md:col-span-2">
                            <label for="clinical_note" class="{{ $labelClasses }}">Catatan Klinis / Masalah</label>
                            <textarea wire:model.defer="ttvState.clinical_note" id="clinical_note" rows="3" class="{{ $inputClasses }} focus:border-yellow-500 focus:ring-yellow-500" placeholder="Observasi penting, masalah, hasil lab singkat..."></textarea>
                            @error('ttvState.clinical_note') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-2 md:col-span-2">
                            <label for="medication_administration" class="{{ $labelClasses }}">Tindakan / Pemberian Obat</label>
                            <textarea wire:model.defer="ttvState.medication_administration" id="medication_administration" rows="3" class="{{ $inputClasses }} focus:border-purple-500 focus:ring-purple-500" placeholder="Catat tindakan atau obat yang diberikan..."></textarea>
                            @error('ttvState.medication_administration') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-between items-center rounded-b-lg">
                        <button type="submit"
                                class="bg-primary-600 text-white px-5 py-2 rounded-md shadow
                                       hover:bg-primary-700 focus:outline-none focus:ring-2
                                       focus:ring-primary-500 focus:ring-offset-2
                                       dark:focus:ring-offset-gray-800">
                            Simpan Observasi
                        </button>
                        @if (session()->has('message-ttv'))
                        <span class="text-green-600 dark:text-green-400 text-sm font-medium">
                            {{ session('message-ttv') }}
                        </span>
                        @endif
                    </div>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg h-full flex flex-col" wire:key="parenteral-form" x-data="{
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
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input Volume Parenteral Aktif</h3>
                    </div>

                    <form @submit.prevent="saveActive" class="flex-grow flex flex-col">
                        <div class="p-4 space-y-3 flex-grow">
                            @forelse($this->usedParenteralFluids as $fluid)
                            <div class="grid grid-cols-3 gap-2 items-center">
                                <label for="vol_par_{{ $loop->index }}" class="col-span-1 block text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $fluid }}</label>
                                <div class="col-span-2">
                                    <input type="number" x-model="activeVolumes['{{ $fluid }}']" id="vol_par_{{ $loop->index }}" class="{{ $inputClasses }} focus:border-green-500 focus:ring-green-500" placeholder="ml">
                                    @error('activeParenteralVolumes.' . $fluid) <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada cairan parenteral yang diinput hari ini.</p>
                            @endforelse
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-end items-center mt-auto">
                            <button type="submit"
                                    class="bg-green-600 text-white px-5 py-2 rounded-md shadow
                                           hover:bg-green-700 focus:outline-none focus:ring-2
                                           focus:ring-green-500 focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800
                                           disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if($this->usedParenteralFluids->isEmpty()) disabled @endif>
                                Simpan Volume Aktif
                            </button>
                        </div>
                    </form>

                    <form @submit.prevent="saveNew" class="border-t dark:border-gray-700">
                        <div class="p-4 space-y-2">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tambah Cairan Baru:</h4>
                            <div>
                                <input type="text" x-model="newParenteralName" class="{{ $inputClasses }} focus:border-green-500 focus:ring-green-500" placeholder="Nama Cairan Baru (cth: Aminofluid)">
                                @error('newParenteralState.jenis') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="number" x-model="newParenteralVolume" class="{{ $inputClasses }} focus:border-green-500 focus:ring-green-500" placeholder="Volume Awal (ml)">
                                @error('newParenteralState.volume') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-end items-center rounded-b-lg">
                            <button type="submit"
                                    class="bg-primary-600 text-white px-5 py-2 rounded-md shadow
                                           hover:bg-primary-700 focus:outline-none focus:ring-2
                                           focus:ring-primary-500 focus:ring-offset-2 text-sm
                                           dark:focus:ring-offset-gray-800">
                                Tambah & Simpan Baru
                            </button>
                        </div>
                    </form>

                    @if (session()->has('message-parenteral'))
                    <div class="p-2 border-t dark:border-gray-700 bg-green-50 dark:bg-green-900 dark:bg-opacity-50 text-green-700 dark:text-green-300 text-sm font-medium text-center">
                        {{ session('message-parenteral') }}
                    </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 shadow rounded-lg h-full flex flex-col" wire:key="enteral-form" x-data="{
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
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input Volume Enteral / Minum Aktif</h3>
                    </div>

                    <form @submit.prevent="saveActive" class="flex-grow flex flex-col">
                        <div class="p-4 space-y-3 flex-grow">
                            @forelse($this->usedEnteralFluids as $fluid)
                            <div class="grid grid-cols-3 gap-2 items-center">
                                <label for="vol_en_{{ $loop->index }}" class="col-span-1 block text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $fluid }}</label>
                                <div class="col-span-2">
                                    <input type="number" x-model="activeVolumes['{{ $fluid }}']" id="vol_en_{{ $loop->index }}" class="{{ $inputClasses }}" placeholder="ml">
                                    @error('activeEnteralVolumes.' . $fluid) <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">Belum ada makanan/minuman enteral yang diinput hari ini.</p>
                            @endforelse
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-end items-center mt-auto">
                            <button type="submit"
                                    class="w-full sm:w-auto bg-primary-600 text-white px-4 sm:px-6 py-2 sm:py-2.5
                                           rounded-md shadow hover:bg-primary-700 focus:outline-none
                                           focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800
                                           disabled:opacity-50 disabled:cursor-not-allowed
                                           transition-all duration-200"
                                    @if($this->usedEnteralFluids->isEmpty()) disabled @endif>
                                Simpan Volume Aktif
                            </button>
                        </div>
                    </form>

                    <form @submit.prevent="saveNew" class="border-t dark:border-gray-700">
                        <div class="p-4 space-y-2">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">Tambah Enteral / Minum Baru:</h4>
                            <div>
                                <input type="text" x-model="newEnteralName" class="{{ $inputClasses }}" placeholder="Nama Baru (cth: Sonde Susu Y, Teh)">
                                @error('newEnteralState.jenis') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="number" x-model="newEnteralVolume" class="{{ $inputClasses }}" placeholder="Volume Awal (ml)">
                                @error('newEnteralState.volume') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-end items-center rounded-b-lg">
                            <button type="submit"
                                    class="bg-primary-600 text-white px-5 py-2 rounded-md shadow
                                           hover:bg-primary-700 focus:outline-none focus:ring-2
                                           focus:ring-primary-500 focus:ring-offset-2 text-sm
                                           dark:focus:ring-offset-gray-800">
                                Tambah & Simpan Baru
                            </button>
                        </div>
                    </form>

                    @if (session()->has('message-enteral'))
                    <div class="p-2 border-t dark:border-gray-700 bg-green-50 dark:bg-green-900 dark:bg-opacity-50 text-green-700 dark:text-green-300 text-sm font-medium text-center">
                        {{ session('message-enteral') }}
                    </div>
                    @endif
                </div>

                <div wire:key="keluar-form">
                    <form wire:submit.prevent="saveCairanKeluar">
                        <div class="bg-white dark:bg-gray-800 shadow rounded-lg h-full flex flex-col">
                            <div class="p-4 border-b dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input Cairan Keluar</h3>
                            </div>
                            <div class="p-4 space-y-4 flex-grow">
                                <div>
                                    <label for="ck_jenis_select" class="{{ $labelClasses }}">Jenis Cairan Keluar</label>
                                    <select wire:model.defer="cairanKeluarState.cairan_keluar_jenis" id="ck_jenis_select" class="{{ $inputClasses }} focus:border-danger-500 focus:ring-danger-500">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Irigasi CM">Irigasi CM</option>
                                        <option value="Irigasi CK">Irigasi CK</option>
                                        <option value="Urine">Urine</option>
                                        <option value="NGT">NGT</option>
                                        <option value="Drain/WSD 1">Drain/WSD 1</option>
                                        <option value="Drain/WSD 2">Drain/WSD 2</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @error('cairanKeluarState.cairan_keluar_jenis') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="ck_volume" class="{{ $labelClasses }}">Volume (ml)</label>
                                    <input type="number" wire:model.defer="cairanKeluarState.cairan_keluar_volume" id="ck_volume" class="{{ $inputClasses }} focus:border-danger-500 focus:ring-danger-500">
                                    @error('cairanKeluarState.cairan_keluar_volume') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-between items-center rounded-b-lg mt-auto">
                                <button type="submit"
                                        class="bg-danger-600 text-white px-5 py-2 rounded-md shadow
                                               hover:bg-danger-700 focus:outline-none focus:ring-2
                                               focus:ring-danger-500 focus:ring-offset-2
                                               dark:focus:ring-offset-gray-800">
                                    Simpan Keluar
                                </button>
                                @if (session()->has('message-keluar'))
                                <span class="text-green-600 dark:text-green-400 text-sm font-medium">
                                    {{ session('message-keluar') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <form wire:submit.prevent="saveIwl">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="p-4 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Input IWL Harian</h3>
                    </div>
                    <div class="p-4">
                        <label for="iwlInput" class="{{ $labelClasses }}">IWL (ml / 24 jam)</label>
                        <input type="number" step="0.1" wire:model.defer="iwlInput" id="iwlInput" class="{{ $inputClasses }}">
                        @error('iwlInput') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 flex justify-between items-center rounded-b-lg">
                        <button type="submit"
                                class="bg-primary-600 text-white px-4 py-2 rounded-md shadow
                                       text-sm font-medium hover:bg-primary-700
                                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                       dark:focus:ring-offset-gray-800">
                            Simpan IWL
                        </button>
                        @if (session()->has('message-iwl'))
                        <span class="text-green-600 dark:text-green-400 text-sm font-medium">{{ session('message-iwl') }}</span>
                        @endif
                    </div>
                </div>
            </form>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg sticky top-6">
                <div class="p-4 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Balance Cairan (24 Jam)</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Masuk (24h):</span>
                        <span class="font-bold text-lg text-green-600 dark:text-green-400">{{ $this->fluidBalance['totalMasuk'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Keluar (24h):</span>
                        <span class="font-bold text-lg text-danger-600 dark:text-danger-400">{{ $this->fluidBalance['totalKeluar'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">IWL:</span>
                        <span class="font-bold text-lg text-gray-700 dark:text-gray-300">{{ $this->fluidBalance['iwl'] }} ml</span>
                    </div>
                    <hr class="my-2 border-t-2 border-dashed dark:border-gray-600">
                    <div class="flex justify-between items-center text-lg">
                        <span class="font-semibold text-gray-800 dark:text-gray-100">BALANCE (24 Jam):</span>
                        <span class="font-bold text-primary-700 dark:text-primary-300">{{ $this->fluidBalance['balance24Jam'] }} ml</span>
                    </div>

                    <div class="flex justify-between items-center text-sm mt-2">
                        <span class="text-gray-600 dark:text-gray-400">Balance Sebelumnya:</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $this->fluidBalance['previousBalance'] }} ml</span>
                    </div>
                    <div class="flex justify-between items-center text-xl mt-1 pt-1 border-t dark:border-gray-600">
                        <span class="font-semibold text-gray-800 dark:text-gray-100">BALANCE KUMULATIF:</span>
                        <span class="font-bold text-purple-700 dark:text-purple-400">{{ $this->fluidBalance['cumulativeBalance'] }} ml</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
