<div class="p-4 border rounded-md shadow-sm bg-white" x-data="{ vent_mode: @entangle('vent_mode') ,
showFlaccModal: false,
         flacc_face: 0,
         flacc_legs: 0,
         flacc_activity: 0,
         flacc_cry: 0,
         flacc_consolability: 0,

         {{-- Alpine.js 'getter' untuk menghitung total skor secara real-time --}}
         get totalFlaccScore() {
             return parseInt(this.flacc_face) +
                    parseInt(this.flacc_legs) +
                    parseInt(this.flacc_activity) +
                    parseInt(this.flacc_cry) +
                    parseInt(this.flacc_consolability);
         }
     }">

    {{-- Header Form --}}
    <div class="flex items-center justify-between pb-3 border-b">
        <h2 class="text-xl font-semibold">
            Input Tanda Vital (Jam: {{ $jamGridSaatIni }}:00)
        </h2>
        <div>
            {{-- Tombol Refresh manual --}}
            <button wire:click="loadCurrentCycle" class="px-3 py-2 text-sm text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200">
                Refresh
            </button>
            {{-- Tombol Simpan --}}
            <button wire:click="save" class="px-4 py-2 ml-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
    <div class="p-3 my-3 text-green-800 bg-green-100 border border-green-300 rounded-md">
        {{ session('success') }}
    </div>
    @endif

    {{-- Form Input (Grid 2 Kolom) --}}
    <form wire:submit="save" class="mt-4">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- KOLOM KIRI: TANDA VITAL --}}
            <div class="space-y-4">
                <x-form-input label="Temp. Inkubator (°C)" wire:model="temp_inkubator" type="number" step="0.1" />
                <x-form-input label="Temp. Skin (°C)" wire:model="temp_skin" type="number" step="0.1" />
                <x-form-input label="Heart Rate (x/mnt)" wire:model="heart_rate" type="number" />
                <x-form-input label="Resp. Rate (x/mnt)" wire:model="respiratory_rate" type="number" />
                <x-form-input label="Tekanan Darah (mmHg)" wire:model="tekanan_darah" type="text" placeholder="cth: 80/50" />
                <x-form-input label="Sat. O2 (%)" wire:model="sat_o2" type="number" />
                <x-form-input label="Irama EKG" wire:model="irama_ekg" type="text" />
                <div class="flex items-end space-x-2">
                    <div class="flex-grow">
                        {{-- Input ini sekarang diisi oleh modal --}}
                        <x-form-input label="Skala Nyeri" wire:model="skala_nyeri" type="number" min="0" max="10" />
                    </div>
                    {{-- Tombol untuk memicu modal --}}
                    <button type_button" @click.prevent="showFlaccModal = true" class="px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 h-fit">
                        Hitung Skor
                    </button>
                </div>
                <x-form-input label="Huidifier Inkubator" wire:model="huidifier_inkubator" type="text" />
            </div>

            {{-- KOLOM KANAN: OBSERVASI APNEA WARNA --}}
            <div class="p-4 space-y-4 border rounded-md bg-gray-50">
                <h3 class="font-medium">Observasi Apnea Warna</h3>
                <x-form-checkbox label="Cyanosis (+/-)" wire:model="cyanosis" />
                <x-form-checkbox label="Pucat (+/-)" wire:model="pucat" />
                <x-form-checkbox label="Icterus (+/-)" wire:model="icterus" />
                <x-form-checkbox label="CRT < 2 detik (+/-)" wire:model="crt_lt_2" />
                <x-form-checkbox label="Bradikardia (+/-)" wire:model="bradikardia" />
                <x-form-checkbox label="Stimulasi (+/-)" wire:model="stimulasi" />
            </div>
            <div class="p-4 border rounded-md">
                <h3 class="text-lg font-medium mb-4">Terapi Oksigen / Ventilator</h3>

                {{-- Pilihan Mode (Radio Button) --}}
                <div class="flex flex-wrap gap-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="vent_mode" value="SPONTAN" x-model="vent_mode" class="text-blue-600">
                        <span class="ml-2">Spontan / Nasal</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="vent_mode" value="CPAP" x-model="vent_mode" class="text-blue-600">
                        <span class="ml-2">CPAP</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="vent_mode" value="HFO" x-model="vent_mode" class="text-blue-600">
                        <span class="ml-2">HFO</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="vent_mode" value="MEKANIK" x-model="vent_mode" class="text-blue-600">
                        <span class="ml-2">Ventilator Mekanik</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="vent_mode" value="" x-model="vent_mode" class="text-gray-400">
                        <span class="ml-2 text-gray-500">(Tidak Ada)</span>
                    </label>
                </div>

                {{-- Form SPONTAN / NASAL --}}
                <div x-show="vent_mode === 'SPONTAN'" class="p-3 space-y-3 bg-gray-50 border rounded-md">
                    <h4 class="font-semibold">Setting Nasal High/Low Flow</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <x-form-input label="FiO2 (%)" wire:model="vent_fio2_nasal" type="number" step="0.1" />
                        <x-form-input label="Flow (lpm)" wire:model="vent_flow_nasal" type="number" step="0.1" />
                    </div>
                </div>

                {{-- Form CPAP --}}
                <div x-show="vent_mode === 'CPAP'" class="p-3 space-y-3 bg-gray-50 border rounded-md">
                    <h4 class="font-semibold">Setting CPAP</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <x-form-input label="FiO2 (%)" wire:model="vent_fio2_cpap" type="number" step="0.1" />
                        <x-form-input label="Flow (lpm)" wire:model="vent_flow_cpap" type="number" step="0.1" />
                        <x-form-input label="PEEP" wire:model="vent_peep_cpap" type="number" step="0.1" />
                    </div>
                </div>

                {{-- Form HFO --}}
                <div x-show="vent_mode === 'HFO'" class="p-3 space-y-3 bg-gray-50 border rounded-md">
                    <h4 class="font-semibold">Setting HFO</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <x-form-input label="FiO2 (%)" wire:model="vent_fio2_hfo" type="number" step="0.1" />
                        <x-form-input label="Frekuensi" wire:model="vent_frekuensi_hfo" type="number" />
                        <x-form-input label="MAP" wire:model="vent_map_hfo" type="number" step="0.1" />
                        <x-form-input label="Amplitudo" wire:model="vent_amplitudo_hfo" type="number" />
                        <x-form-input label="I:T" wire:model="vent_it_hfo" type="text" />
                    </div>
                </div>

                {{-- Form Ventilator Mekanik --}}
                <div x-show="vent_mode === 'MEKANIK'" class="p-3 space-y-3 bg-gray-50 border rounded-md">
                    <h4 class="font-semibold">Setting Ventilator Mekanik</h4>
                    <div class="grid grid-cols-4 gap-4">
                        <x-form-input label="Mode" wire:model="vent_mode_mekanik" type="text" />
                        <x-form-input label="FiO2 (%)" wire:model="vent_fio2_mekanik" type="number" step="0.1" />
                        <x-form-input label="PEEP" wire:model="vent_peep_mekanik" type="number" step="0.1" />
                        <x-form-input label="PIP" wire:model="vent_pip_mekanik" type="number" step="0.1" />
                        <x-form-input label="TV/Vte" wire:model="vent_tv_vte_mekanik" type="text" />
                        <x-form-input label="RR / RR Spontan" wire:model="vent_rr_spontan_mekanik" type="text" />
                        <x-form-input label="P. Max" wire:model="vent_p_max_mekanik" type="number" step="0.1" />
                        <x-form-input label="I : E" wire:model="vent_ie_mekanik" type="text" />
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div x-show="showFlaccModal" x-cloak class="fixed inset-0 z-40 bg-black bg-opacity-50 transition-opacity" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Konten Modal --}}
    <div x-show="showFlaccModal" x-cloak x-trap.noscroll="showFlaccModal" @keydown.escape.window="showFlaccModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full overflow-hidden" @click.outside="showFlaccModal = false">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl font-semibold">Kalkulator Skala Nyeri</h3>
                <button @click="showFlaccModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 max-h-[80vh] overflow-y-auto">
                {{-- KOLOM 1: KALKULATOR FLACC --}}
                <div class="md:col-span-2">
                    <h4 class="font-bold mb-2">PENILAIAN NYERI PADA PEDIATRIC FLACC</h4>
                    <div class="overflow-x-auto border rounded-md">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">Kategori</th>
                                    <th class="px-3 py-2 text-left">Skor 0</th>
                                    <th class="px-3 py-2 text-left">Skor 1</th>
                                    <th class="px-3 py-2 text-left">Skor 2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                {{-- Face --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">Face (Wajah)</td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_face" value="0" class="mr-2">Tersenyum</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_face" value="1" class="mr-2">Sesekali meringis</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_face" value="2" class="mr-2">Sering mengerut</label></td>
                                </tr>
                                {{-- Legs --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">Legs (Kaki)</td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_legs" value="0" class="mr-2">Rileks</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_legs" value="1" class="mr-2">Gelisah, tegang</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_legs" value="2" class="mr-2">Menendang-nendang</glabel>
                                    </td>
                                </tr>
                                {{-- Activity --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">Activity (Aktivitas)</td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_activity" value="0" class="mr-2">Berbaring tenang</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_activity" value="1" class="mr-2">Menggeliat, tegang</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_activity" value="2" class="mr-2">Tubuh melengkung, kaku</label></td>
                                </tr>
                                {{-- Cry --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">Cry (Menangis)</td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_cry" value="0" class="mr-2">Tidak menangis</glabel>
                                    </td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_cry" value="1" class="mr-2">Merintih, merengek</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_cry" value="2" class="mr-2">Menangis terus, menjerit</label></td>
                                </tr>
                                {{-- Consolability --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">Consolability</td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_consolability" value="0" class="mr-2">Tenang, santai</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_consolability" value="1" class="mr-2">Diyakinkan dgn sentuhan</label></td>
                                    <td class="px-3 py-2"><label><input type="radio" x-model="flacc_consolability" value="2" class="mr-2">Sulit dibujuk</label></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KOLOM 2: WONG BAKER & SKOR --}}
                <div class="md:col-span-1">
                    <h4 class="font-bold mb-2">WONG BAKER FACE</h4>
                    {{-- Ini memanggil gambar dari public/img/image.png --}}
                    <img src="{{ asset('img/image.png') }}" alt="Wong Baker Face Scale" class="w-full rounded-md border">

                    <div class="mt-4 p-4 bg-gray-100 rounded-md text-center">
                        <span class="text-lg font-medium">TOTAL SKOR FLACC:</span>
                        <span class="text-4xl font-bold ml-2" x-text="totalFlaccScore"></span>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex justify-end p-4 bg-gray-50 border-t">
                <button type="button" @click="showFlaccModal = false" class="px-4 py-2 text-sm text-gray-700 bg-white border rounded-md hover:bg-gray-50">
                    Batal
                </button>
                <button type="button" @click="$wire.set('skala_nyeri', totalFlaccScore); showFlaccModal = false" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Gunakan Skor Ini (<span x-text="totalFlaccScore"></span>)
                </button>
            </div>
        </div>
    </div>
</div>
