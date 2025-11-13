<div>
    <x-slot name="header">
        <livewire:patient-header :no-rawat="$no_rawat" />
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 px-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm-4 4a4 4 0 00-4 4v5h16v-5a4 4 0 00-4-4H8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                            Monitoring 24 Jam
                            <span class="bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent">PEDIATRIC INTENSIVE CARE UNIT (PICU)</span>
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau kondisi pasien secara real-time dengan mudah</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 justify-end">
                    <a href="{{ route('patient.picu.history' ,['no_rawat' => str_replace('/', '_', $no_rawat) ]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-200 shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('patient.picu.charts', [
    'noRawat' => str_replace('/', '_', $no_rawat),
    'cycleId' => $currentCycleId
]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-indigo-600 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h-6M6 13h12m-6 6h-6"></path>
                        </svg>
                        Lihat Grafik
                    </a>
                    @if($currentCycleId)
                    <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $currentCycleId]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-600 hover:text-white shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10V6a4 4 0 014-4h2a4 4 0 014 4v4"></path>
                        </svg>
                        Cetak
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @if (session()->has('success'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        </div>
        @endif

        @if (session()->has('error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4">
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                <span class="font-medium">Perhatian!</span> {{ session('error') }}
            </div>
        </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 gap-6">
            <form wire:submit="saveRecord" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3">Form Input Observasi</h3>
                    <div class="mt-4" x-data="{ currentTime: new Date() }" x-init="setInterval(() => currentTime = new Date(), 1000)" wire:ignore>
                        <div x-data="{
                                 currentTime: new Date(@json(now()->timestamp * 1000))
                             }" x-init="setInterval(() => currentTime = new Date(currentTime.getTime() + 1000), 1000)" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal & Jam Observasi</label>
                            <div class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 shadow-sm px-3 py-2 sm:text-sm text-gray-700 dark:text-gray-300">
                                <span x-text="currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                                <span> - </span>
                                <span x-text="currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                            </div>
                        </div>
                    </div>
                    @php
                    // --- KUMPULAN SEMUA KELAS HELPER ---
                    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
                    $labelContextClasses = 'ml-2 text-xs font-normal text-primary-600 dark:text-primary-400';
                    $inputWrapperClasses = 'mt-1 flex items-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm
                    bg-white dark:bg-gray-700
                    focus-within:ring-1 focus-within:ring-primary-500 focus-within:border-primary-500';
                    $inputTtvClasses = 'block w-full border-0 bg-transparent focus:ring-0 rounded-l-md
                    text-gray-900 dark:text-gray-200';
                    $addonClasses = 'whitespace-nowrap bg-gray-50 dark:bg-gray-600 px-3 text-gray-500 dark:text-gray-400
                    rounded-r-md border-l border-gray-300 dark:border-gray-600';
                    $inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                    border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-700
                    text-gray-900 dark:text-gray-200
                    focus:border-primary-500 focus:ring-primary-500';
                    $cardClasses = 'space-y-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';
                    $cardTitleClasses = 'text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide';
                    $checkboxClasses = 'rounded border-gray-300 dark:border-gray-600
                    text-primary-600 dark:text-primary-500
                    focus:ring-primary-500 dark:focus:ring-primary-600
                    dark:bg-gray-700 dark:checked:bg-primary-500';
                    $errorClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';

                    // Kelas untuk Tombol Tab
                    $tabButtonBase = 'whitespace-nowrap py-4 px-3 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none';
                    $tabButtonInactive = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600';
                    $tabButtonActive = 'border-primary-500 text-primary-600 dark:text-primary-400 dark:border-primary-400';
                    @endphp
                    <div x-data="{ tab: 'observasi' }" class="mt-6">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs">
                                <button type="button" @click="tab = 'observasi'" :class="{ '{{ $tabButtonActive }}': tab === 'observasi', '{{ $tabButtonInactive }}': tab !== 'observasi' }" class="{{ $tabButtonBase }}">
                                    Observasi Pasien
                                </button>
                                <button type="button" @click="tab = 'setting_ventilator'" :class="{ '{{ $tabButtonActive }}': tab === 'setting_ventilator', '{{ $tabButtonInactive }}': tab !== 'setting_ventilator' }" class="{{ $tabButtonBase }}">
                                    Setting Ventilator
                                </button>
                                <button type="button" @click="tab = 'catatan_lainnya'" :class="{ '{{ $tabButtonActive }}': tab === 'catatan_lainnya', '{{ $tabButtonInactive }}': tab !== 'catatan_lainnya' }" class="{{ $tabButtonBase }}">
                                    Catatan Lainnya
                                </button>
                            </nav>
                        </div>
                        <div class="mt-6">
                            <div x-show="tab === 'observasi'" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <div class="grid grid-cols-2 gap-4 ">
                                        <div>
                                            <label for="temp_skin" class="{{ $labelClasses }}">Temp Skin</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">°C</span>
                                            </div>
                                            @error('temp_skin') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="sat_o2" class="{{ $labelClasses }}">Sat O2</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">%</span>
                                            </div>
                                            @error('sat_o2') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="hr" class="{{ $labelClasses }}">Heart Rate</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">x/mnt</span>
                                            </div>
                                            @error('hr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="rr" class="{{ $labelClasses }}">Resp. Rate</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">x/mnt</span>
                                            </div>
                                            @error('rr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="blood_pressure_systolic" class="{{ $labelClasses }}">Sistolik</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="number" wire:model.defer="blood_pressure_systolic" id="blood_pressure_systolic" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">mmHg</span>
                                            </div>
                                            @error('blood_pressure_systolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="blood_pressure_diastolic" class="{{ $labelClasses }}">Diastolik</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="number" wire:model.defer="blood_pressure_diastolic" id="blood_pressure_diastolic" class="{{ $inputTtvClasses }}">
                                                <span class="{{ $addonClasses }}">mmHg</span>
                                            </div>
                                            @error('blood_pressure_diastolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 ">
                                        <div>
                                            <label for="irama_ekg" class="{{ $labelClasses }}">Irama EKG</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="{{ $inputTtvClasses }}">
                                            </div>
                                            @error('irama_ekg') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="humidifier_inkubator" class="{{ $labelClasses }}">Humidifier Inkubator</label>
                                            <div class="{{ $inputWrapperClasses }}">
                                                <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="{{ $inputTtvClasses }}">
                                            </div>
                                            @error('humidifier_inkubator') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
                                        </div>
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

                                    <label class="{{ $labelClasses }}">Penilaian Nyeri (FLACC)</label>

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

                            <div x-show="tab === 'setting_ventilator'" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ showHFO: false, showSpontan: false, showCPAP: false }">
                                    <div class="flex flex-col space-y-3">
                                        <div>
                                            <div class="{{ $cardClasses }} p-4">
                                                <h5 class="{{ $cardTitleClasses }}">Setting Ventilator</h5>
                                                <div class="grid grid-cols-2 gap-4 items-start">
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">Mode</label>
                                                        <input type="text" wire:model.defer="monitor_mode" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                                                        <input type="text" wire:model.defer="monitor_fio2" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">PEEP (cmH₂O)</label>
                                                        <input type="text" wire:model.defer="monitor_peep" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">PIP (cmH₂O)</label>
                                                        <input type="text" wire:model.defer="monitor_pip" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">TV/Vte (ml)</label>
                                                        <input type="text" wire:model.defer="monitor_tv_vte" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">RR / RR Spontan</label>
                                                        <input type="text" wire:model.defer="monitor_rr_spontan" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">P.Max (cmH₂O)</label>
                                                        <input type="text" wire:model.defer="monitor_p_max" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <label class="block text-sm {{ $labelClasses }}">I : E</label>
                                                        <input type="text" wire:model.defer="monitor_ie" class="{{ $inputClasses }} h-9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-3">
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <label for="spontan-toggle" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                Aktifkan Setting **Spontan**
                                            </label>
                                            <input type="checkbox" id="spontan-toggle" x-model="showSpontan" class="rounded text-primary-600 dark:text-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div x-show="showSpontan" x-transition.duration.300ms>
                                            <div class="{{ $cardClasses }}">
                                                <h5 class="{{ $cardTitleClasses }}">Setting Spontan</h5>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                                                        <input type="text" wire:model.defer="spontan_fio2" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">Flow (Lpm)</label>
                                                        <input type="text" wire:model.defer="spontan_flow" class="{{ $inputClasses }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <label for="cpap-toggle" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                Aktifkan Setting **CPAP**
                                            </label>
                                            <input type="checkbox" id="cpap-toggle" x-model="showCPAP" class="rounded text-primary-600 dark:text-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div x-show="showCPAP" x-transition.duration.300ms>
                                            <div class="{{ $cardClasses }}">
                                                <h5 class="{{ $cardTitleClasses }}">Setting CPAP</h5>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                                                        <input type="text" wire:model.defer="cpap_fio2" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">Flow (Lpm)</label>
                                                        <input type="text" wire:model.defer="cpap_flow" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">PEEP (cmH₂O)</label>
                                                        <input type="text" wire:model.defer="cpap_peep" class="{{ $inputClasses }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                            <label for="hfo-toggle" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                                Aktifkan Setting **HFO** (Opsional)
                                            </label>
                                            <input type="checkbox" id="hfo-toggle" x-model="showHFO" class="rounded text-primary-600 dark:text-primary-500 focus:ring-primary-500">
                                        </div>

                                        <div x-show="showHFO" x-transition.duration.300ms>
                                            <div class="{{ $cardClasses }}">
                                                <h5 class="{{ $cardTitleClasses }}">Setting HFO</h5>
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                                                        <input type="text" wire:model.defer="hfo_fio2" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">Frekuensi (Hz)</label>
                                                        <input type="text" wire:model.defer="hfo_frekuensi" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">MAP (cmH₂O)</label>
                                                        <input type="text" wire:model.defer="hfo_map" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">Amplitudo (ΔP)</label>
                                                        <input type="text" wire:model.defer="hfo_amplitudo" class="{{ $inputClasses }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm {{ $labelClasses }}">IT (%)</label>
                                                        <input type="text" wire:model.defer="hfo_it" class="{{ $inputClasses }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="tab === 'catatan_lainnya'" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 max-w-md">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Catat Kejadian</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Pilih kejadian yang terjadi.
                                    </p>
                                    <div class="mt-4 space-y-2 border-t dark:border-gray-700 pt-4">
                                        <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="cyanosis" class="{{ $checkboxClasses }}">
                                                <span>Cyanosis</span>
                                            </label>
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="pucat" class="{{ $checkboxClasses }}">
                                                <span>Pucat</span>
                                            </label>
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="ikterus" class="{{ $checkboxClasses }}">
                                                <span>Ikterus</span>
                                            </label>
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="crt_less_than_2" class="{{ $checkboxClasses }}">
                                                <span>CRT &lt; 2 detik</span>
                                            </label>
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="bradikardia" class="{{ $checkboxClasses }}">
                                                <span>Bradikardia</span>
                                            </label>
                                            <label class="{{ $labelClasses }} flex items-center space-x-2">
                                                <input type="checkbox" wire:model.defer="stimulasi" class="{{ $checkboxClasses }}">
                                                <span>Stimulasi</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-900/[.85] backdrop-blur-sm px-6 py-3 border-t dark:border-gray-700 z-10">
                    <div class="flex justify-between items-center gap-4">
                        <div class="flex items-center gap-3">

                            @error('record')
                            <span class="text-sm text-danger-600 dark:text-danger-400">
                                {{ $message }}
                            </span>
                            @enderror

                            @if (session()->has('success'))
                            <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-300 px-3 py-1.5 rounded-lg text-sm transition-opacity duration-300" role="alert">
                                <span class="font-medium">Berhasil!</span> {{ session('success') }}
                            </div>
                            @endif

                            @if (session()->has('error'))
                            <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-300 px-3 py-1.5 rounded-lg text-sm transition-opacity duration-300" role="alert">
                                <span class="font-medium">Perhatian!</span> {{ session('error') }}
                            </div>
                            @endif
                        </div>
                        <button type="submit" wire:loading.attr="disabled" class="inline-flex justify-center rounded-md border border-transparent
                                       bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm
                                       hover:bg-primary-700
                                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                       dark:focus:ring-offset-gray-800">
                            <span wire:loading.remove wire:target="saveRecord">Simpan Catatan</span>
                            <span wire:loading wire:target="saveRecord">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
