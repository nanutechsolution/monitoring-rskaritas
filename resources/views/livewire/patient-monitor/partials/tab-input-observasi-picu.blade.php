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
            <label for="temp_skin" class="{{ $labelClasses }}">Temp Skin</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">°C</span>
            </div>
            @error('temp_skin') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="sat_o2" class="{{ $labelClasses }}">Sat O2</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">%</span>
            </div>
            @error('sat_o2') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
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

    <div class="grid grid-cols-2 gap-4 ">
        <div>
            <label for="irama_ekg" class="{{ $labelClasses }}">Irama EKG</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="{{ $inputClasses }}">
            </div>
            @error('irama_ekg') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="humidifier_inkubator" class="{{ $labelClasses }}">Humidifier Inkubator</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="{{ $inputClasses }}">
            </div>
            @error('humidifier_inkubator') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>
    <div x-data="{
        showPippModal: false,
        flacc_face: null,
        flacc_legs: null,
        flacc_activity: null,
        flacc_cry: null,
        flacc_consolability: null,
        get totalFlaccScore() {
            return [this.flacc_face, this.flacc_legs, this.flacc_activity, this.flacc_cry, this.flacc_consolability]
                .map(v => parseInt(v || 0))
                .reduce((a, b) => a + b, 0);
        }
    }" class="space-y-3">

        <label class="{{ $labelClasses }}">Penilaian Nyeri Pediatrik (FLACC/PIPP)</label>

        <div class="flex items-center">
            <div class="flex-1 rounded-l-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-3 py-2 text-gray-700 dark:text-gray-200 text-sm">
                {{ $skala_nyeri ?? '-' }}
            </div>
            <button type="button" @click="showPippModal = true" class="rounded-r-md border border-l-0 border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       px-3 py-2
                       hover:bg-primary-50 dark:hover:bg-gray-600
                       transition-all" title="Penilaian Nyeri">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6a2 2 0 012 2v16l-5-2-5 2V5a2 2 0 012-2z" />
                </svg>
            </button>
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik ikon untuk menilai nyeri pasien.</p>
        <div x-show="showPippModal" x-cloak x-trap.noscroll="showPippModal" @keydown.escape.window="showPippModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

            <div @click.outside="showPippModal = false" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-6xl w-full overflow-hidden">

                <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                    <h3 class="text-xl md:text-2xl font-bold text-center w-full text-gray-900 dark:text-gray-100">
                        PENILAIAN NYERI<br>PADA PEDIATRIC FLACC
                    </h3>
                    <button @click="showPippModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 text-2xl leading-none">&times;</button>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 max-h-[80vh] overflow-y-auto">

                    <div class="md:col-span-2">
                        <h4 class="font-bold mb-2 text-gray-900 dark:text-gray-100">Penilaian Nyeri (FLACC)</h4>
                        <div class="overflow-x-auto border dark:border-gray-700 rounded-md">
                            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-gray-700 dark:text-gray-300">Kategori</th>
                                        <th class="px-3 py-2 text-left text-gray-700 dark:text-gray-300">0</th>
                                        <th class="px-3 py-2 text-left text-gray-700 dark:text-gray-300">1</th>
                                        <th class="px-3 py-2 text-left text-gray-700 dark:text-gray-300">2</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">Face</td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="0" class="mr-2 text-primary-600 focus:ring-primary-500">Tersenyum</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="1" class="mr-2 text-primary-600 focus:ring-primary-500">Sesekali meringis</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="2" class="mr-2 text-primary-600 focus:ring-primary-500">Sering mengerut</label></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">Legs</td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="0" class="mr-2 text-primary-600 focus:ring-primary-500">Rileks</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="1" class="mr-2 text-primary-600 focus:ring-primary-500">Gelisah</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="2" class="mr-2 text-primary-600 focus:ring-primary-500">Menendang</label></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">Activity</td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="0" class="mr-2 text-primary-600 focus:ring-primary-500">Tenang</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="1" class="mr-2 text-primary-600 focus:ring-primary-500">Tegang</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="2" class="mr-2 text-primary-600 focus:ring-primary-500">Melengkung</label></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">Cry</td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="0" class="mr-2 text-primary-600 focus:ring-primary-500">Tidak menangis</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="1" class="mr-2 text-primary-600 focus:ring-primary-500">Merintih</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="2" class="mr-2 text-primary-600 focus:ring-primary-500">Menjerit</label></td>
                                    </tr>
                                    <tr>
                                        <td class="px-3 py-2 font-medium text-gray-900 dark:text-gray-100">Consolability</td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="0" class="mr-2 text-primary-600 focus:ring-primary-500">Tenang</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="1" class="mr-2 text-primary-600 focus:ring-primary-500">Diyakinkan</label></td>
                                        <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="2" class="mr-2 text-primary-600 focus:ring-primary-500">Sulit dibujuk</label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <h4 class="font-bold mb-2 text-center text-gray-900 dark:text-gray-100">WONG BAKER FACE</h4>
                        <img src="{{ asset('img/image.png') }}" alt="Wong Baker Face Scale" class="w-full rounded-md border dark:border-gray-700">
                        <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md text-center">
                            <span class="text-lg font-medium text-gray-900 dark:text-gray-100">TOTAL SKOR:</span>
                            <span class="text-4xl font-bold ml-2 text-primary-600 dark:text-primary-400" x-text="totalFlaccScore"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end p-4 bg-gray-50 dark:bg-gray-900 border-t dark:border-gray-700">
                    <button type="button" @click="showPippModal = false" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                        Batal
                    </button>
                    <button type="button" @click="$wire.set('skala_nyeri', totalFlaccScore); showPippModal = false" class="ml-3 px-4 py-2 text-sm font-medium text-white
                               bg-primary-600 rounded-md hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                        Gunakan Skor Ini (<span x-text="totalFlaccScore"></span>)
                    </button>
                </div>

            </div>
        </div>
    </div>




</div>
