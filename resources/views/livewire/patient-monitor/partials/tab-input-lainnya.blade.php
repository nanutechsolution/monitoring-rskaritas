<div>
    <label for="irama_ekg" class="block text-sm font-medium text-gray-700">Irama EKG</label>
    <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('irama_ekg')
    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
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
    <!-- Label -->
    <label class="block text-sm font-medium text-gray-700">Penilaian Nyeri Pediatrik (FLACC/PIPP)</label>

    <!-- Input dan Tombol Inline -->
    <div class="flex items-center">
        <div class="flex-1 rounded-l-md border border-gray-300 bg-gray-100 px-3 py-2 text-gray-700 text-sm">
            {{ $skala_nyeri ?? '-' }}
        </div>
        <button type="button" @click="showPippModal = true" class="rounded-r-md border border-l-0 border-gray-300 bg-white px-3 py-2 hover:bg-purple-50 transition-all" title="Penilaian Nyeri">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6a2 2 0 012 2v16l-5-2-5 2V5a2 2 0 012-2z" />
            </svg>
        </button>
    </div>
    <p class="mt-1 text-xs text-gray-500">Klik ikon untuk menilai nyeri pasien.</p>

    <!-- MODAL PENILAIAN NYERI -->
    <div x-show="showPippModal" x-cloak x-trap.noscroll="showPippModal" @keydown.escape.window="showPippModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <div @click.outside="showPippModal = false" class="bg-white rounded-lg shadow-xl max-w-6xl w-full overflow-hidden">

            <!-- Header Modal -->
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-xl md:text-2xl font-bold text-center w-full">
                    PENILAIAN NYERI<br>PADA PEDIATRIC FLACC
                </h3>
                <button @click="showPippModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>

            <!-- Body Modal -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 max-h-[80vh] overflow-y-auto">

                <!-- Kolom FLACC -->
                <div class="md:col-span-2">
                    <h4 class="font-bold mb-2">Penilaian Nyeri (FLACC)</h4>
                    <div class="overflow-x-auto border rounded-md">
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">Kategori</th>
                                    <th class="px-3 py-2 text-left">0</th>
                                    <th class="px-3 py-2 text-left">1</th>
                                    <th class="px-3 py-2 text-left">2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-3 py-2 font-medium">Face</td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="0" class="mr-2">Tersenyum</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="1" class="mr-2">Sesekali meringis</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_face" value="2" class="mr-2">Sering mengerut</label></td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 font-medium">Legs</td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="0" class="mr-2">Rileks</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="1" class="mr-2">Gelisah</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_legs" value="2" class="mr-2">Menendang</label></td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 font-medium">Activity</td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="0" class="mr-2">Tenang</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="1" class="mr-2">Tegang</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_activity" value="2" class="mr-2">Melengkung</label></td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 font-medium">Cry</td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="0" class="mr-2">Tidak menangis</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="1" class="mr-2">Merintih</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_cry" value="2" class="mr-2">Menjerit</label></td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2 font-medium">Consolability</td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="0" class="mr-2">Tenang</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="1" class="mr-2">Diyakinkan</label></td>
                                    <td class="px-3 py-2"><label class="cursor-pointer"><input type="radio" x-model="flacc_consolability" value="2" class="mr-2">Sulit dibujuk</label></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kolom Wong-Baker -->
                <div class="md:col-span-1">
                    <h4 class="font-bold mb-2 text-center">WONG BAKER FACE</h4>
                    <img src="{{ asset('img/image.png') }}" alt="Wong Baker Face Scale" class="w-full rounded-md border">
                    <div class="mt-4 p-4 bg-gray-100 rounded-md text-center">
                        <span class="text-lg font-medium">TOTAL SKOR:</span>
                        <span class="text-4xl font-bold ml-2 text-blue-600" x-text="totalFlaccScore"></span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end p-4 bg-gray-50 border-t">
                <button type="button" @click="showPippModal = false" class="px-4 py-2 text-sm text-gray-700 bg-white border rounded-md hover:bg-gray-50">
                    Batal
                </button>
                <button type="button" @click="$wire.set('skala_nyeri', totalFlaccScore); showPippModal = false" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Gunakan Skor Ini (<span x-text="totalFlaccScore"></span>)
                </button>
            </div>

        </div>
    </div>
</div>

<div>
    <label for="humidifier_inkubator" class="block text-sm font-medium text-gray-700">Humidifier Inkubator</label>
    <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    @error('humidifier_inkubator')
    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
