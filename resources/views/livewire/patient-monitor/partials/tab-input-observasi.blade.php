@php
$labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

$inputWrapperClasses = 'mt-1 flex items-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm
bg-white dark:bg-gray-700
focus-within:ring-1 focus-within:ring-primary-500 focus-within:border-primary-500';

$inputClasses = 'block w-full border-0 bg-transparent focus:ring-0 rounded-l-md
text-gray-900 dark:text-gray-200';

$addonClasses = 'whitespace-nowrap bg-gray-50 dark:bg-gray-600 px-3 text-gray-500 dark:text-gray-400
rounded-r-md border-l border-gray-300 dark:border-gray-600';

$errorClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';

@endphp
<div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
    <div class="grid grid-cols-2 gap-4 ">
        <div>
            <label for="temp_incubator" class="{{ $labelClasses }}">Temp Incubator</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" pattern="[0-9]*([.,][0-9]+)?" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">°C</span>
            </div>
            @error('temp_incubator') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="temp_skin" class="{{ $labelClasses }}">Temp Skin</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">°C</span>
            </div>
            @error('temp_skin') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="hr" class="{{ $labelClasses }}">Heart Rate</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">x/mnt</span>
            </div>
            @error('hr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="rr" class="{{ $labelClasses }}">Resp. Rate</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">x/mnt</span>
            </div>
            @error('rr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="blood_pressure_systolic" class="{{ $labelClasses }}">Sistolik</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" wire:model.lazy="blood_pressure_systolic" id="blood_pressure_systolic" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">mmHg</span>
            </div>
            @error('blood_pressure_systolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="blood_pressure_diastolic" class="{{ $labelClasses }}">Diastolik</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" wire:model.lazy="blood_pressure_diastolic" id="blood_pressure_diastolic" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">mmHg</span>
            </div>
            @error('blood_pressure_diastolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="sat_o2" class="{{ $labelClasses }}">Sat O2</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">%</span>
            </div>
            @error('sat_o2') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="irama_ekg" class="{{ $labelClasses }}">Irama EKG</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="{{ $inputClasses }}">
            </div>
            @error('irama_ekg') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>
    <div class="space-y-3" x-data="{
        showPippModal: false,
        gestational_age: '0',
        behavioral_state: '0',
        max_heart_rate: '0',
        min_oxygen_saturation: '0',
        brow_bulge: '0',
        eye_squeeze: '0',
        nasolabial_furrow: '0',
        get totalScore() {
            return parseInt(this.gestational_age || 0)
                + parseInt(this.behavioral_state || 0)
                + parseInt(this.max_heart_rate || 0)
                + parseInt(this.min_oxygen_saturation || 0)
                + parseInt(this.brow_bulge || 0)
                + parseInt(this.eye_squeeze || 0)
                + parseInt(this.nasolabial_furrow || 0);
        }
    }">

        <label class="{{ $labelClasses }}">Penilaian Nyeri</label>

        <div class="flex items-center space-x-2">
            <div class="flex-1 rounded-l-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-3 py-2 text-gray-700 dark:text-gray-200 text-sm">
                {{ $skala_nyeri ?? '-' }}
            </div>

            <!-- Tombol modal di samping input -->
            <button type="button" @click="showPippModal = true" class="flex items-center gap-2 py-2 px-4
                bg-white dark:bg-gray-800
                border border-gray-300 dark:border-gray-600 rounded-r-md shadow
                hover:shadow-md hover:bg-primary-50 dark:hover:bg-gray-700 transition">
                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium text-gray-800 dark:text-gray-100">PIPP</span>
            </button>
        </div>

        <!-- Modal -->
        <div x-show="showPippModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm" style="display: none;">
            <div @click.away="showPippModal = false" x-transition.scale class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-xl flex flex-col max-h-[90vh]">

                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        🍼 Penilaian Nyeri Prematur (PIPP)
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur.</p>
                </div>

                <div class="px-6 py-5 overflow-y-auto">
                    @php
                    $pippFields = [
                    ['id' => 'gestational_age', 'label' => 'Usia Gestasi', 'options' => ['0' => '≥ 36 mgg', '1' => '32–35 mgg + 6h', '2' => '28–31 mgg + 6h', '3' => '< 28 mgg' ]], ['id'=> 'behavioral_state', 'label' => 'Perilaku Bayi (15 detik)', 'options' => ['0' => 'Aktif/bangun, mata terbuka', '1' => 'Diam/bangun, mata terbuka/tertutup', '2' => 'Aktif/tidur, mata tertutup', '3' => 'Tenang/tidur, gerak minimal' ]],
                        ['id' => 'max_heart_rate', 'label' => 'Laju Nadi Maks (peningkatan)', 'options' => ['0' => '0–4 dpm', '1' => '5–14 dpm', '2' => '15–24 dpm', '3' => '≥25 dpm' ]],
                        ['id' => 'min_oxygen_saturation', 'label' => 'Saturasi O₂ Min (penurunan)', 'options' => ['0' => '92–100%', '1' => '89–91%', '2' => '85–88%', '3' => '<85%' ]], ['id'=> 'brow_bulge', 'label' => 'Tarikan Alis (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                ['id' => 'eye_squeeze', 'label' => 'Kerutan Mata (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                    ['id' => 'nasolabial_furrow', 'label' => 'Alur Nasolabial (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                        ];
                                        @endphp

                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-6">
                                            <div class="col-span-full">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🕒 Waktu Penilaian</label>
                                                <div class="w-full max-w-xs rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600">
                                                    {{ \Carbon\Carbon::parse($pipp_assessment_time ?? now())->format('d M Y, H:i') }}
                                                </div>
                                            </div>

                                            @foreach ($pippFields as $field)
                                            <div>
                                                <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $field['label'] }}</label>
                                                <select id="{{ $field['id'] }}" x-model="{{ $field['id'] }}" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                                           border-gray-300 dark:border-gray-600
                                                           bg-white dark:bg-gray-700
                                                           text-gray-900 dark:text-gray-200
                                                           focus:border-primary-500 focus:ring-primary-500">
                                                    @foreach ($field['options'] as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-8 space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                                            <div class="text-lg font-semibold text-center text-gray-800 dark:text-gray-100">
                                                Total Skor PIPP:
                                                <span class="text-3xl font-bold transition-all duration-300" :class="{
                                                'text-green-600': totalScore <= 6,
                                                'text-yellow-600': totalScore > 6 && totalScore <= 12,
                                                'text-red-600': totalScore > 12
                                            }" x-text="totalScore"></span>
                                            </div>

                                            <div class="text-sm text-gray-700 dark:text-gray-300 p-4 rounded-lg max-w-lg mx-auto space-y-2">
                                                <strong class="block mb-2 text-center text-base">💡 Rekomendasi Intervensi</strong>
                                                <div x-show="totalScore <= 6" class="p-3 bg-green-50 dark:bg-green-900 dark:bg-opacity-50 rounded-md text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800"><strong>0–6:</strong> Lanjutkan tatalaksana & pemantauan rutin.</div>
                                                <div x-show="totalScore > 6 && totalScore <= 12" class="p-3 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-50 rounded-md text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800"><strong>7–12:</strong> Berikan intervensi non-farmakologis (kenyamanan, sukrosa oral).</div>
                                                <div x-show="totalScore > 12" class="p-3 bg-red-50 dark:bg-danger-900 dark:bg-opacity-50 rounded-md text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800"><strong>>12:</strong> Pertimbangkan intervensi farmakologis (Parasetamol/Narkotik/Sedasi).</div>
                                            </div>
                                        </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" @click="showPippModal = false" class="px-4 py-2 text-sm font-medium rounded-lg border bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition shadow-sm">
                        Batal
                    </button>
                    <button type="button" @click="$wire.savePippScore({
                        gestational_age: gestational_age,
                        behavioral_state: behavioral_state,
                        max_heart_rate: max_heart_rate,
                        min_oxygen_saturation: min_oxygen_saturation,
                        brow_bulge: brow_bulge,
                        eye_squeeze: eye_squeeze,
                        nasolabial_furrow: nasolabial_furrow,
                        total_score: totalScore
                    }).then(() => { showPippModal = false })" wire:loading.attr="disabled" wire:loading.class="opacity-75" class="px-5 py-2 text-sm font-semibold rounded-lg
                    bg-primary-600 text-white hover:bg-primary-700 active:scale-[0.98] transition transform shadow-sm">
                        <span wire:loading.remove wire:target="savePippScore">💾 Simpan Skor PIPP</span>
                        <span wire:loading wire:target="savePippScore">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label for="humidifier_inkubator" class="{{ $labelClasses }}">Humidifier Inkubator</label>
        <div class="{{ $inputWrapperClasses }}">
            <input type="text" wire:model.lazy="humidifier_inkubator" id="humidifier_inkubator" class="{{ $inputClasses }}">
        </div>
        @error('humidifier_inkubator') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
    </div>
</div>
