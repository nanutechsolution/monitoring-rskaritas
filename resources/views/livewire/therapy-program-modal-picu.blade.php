<div x-data="{ showTherapyModal: false }">

    <button
        type="button"
        @click="showTherapyModal = true"
        class="flex items-center gap-2 px-5 py-2 bg-teal-600 text-white border rounded-lg shadow hover:shadow-md hover:bg-teal-700 flex-shrink-0 snap-start transition-all"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10l5-5-5-5z"></path>
        </svg>
        <span class="font-medium">Program Terapi</span>
    </button>

    <div
        x-show="showTherapyModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60"
        aria-labelledby="therapy-modal-title" role="dialog" aria-modal="true"
        style="display: none;"
    >
        <div
            x-show="showTherapyModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"

            x-data="{
                masalah: '',
                program: '',
                enteral: '',
                parenteral: '',
                lab: '',
                resetForm() {
                    this.masalah = '';
                    this.program = '';
                    this.enteral = '';
                    this.parenteral = '';
                    this.lab = '';
                }
            }"
            @load-therapy-form.window="
                masalah = $event.detail.masalah;
                program = $event.detail.program;
                enteral = $event.detail.enteral;
                parenteral = $event.detail.parenteral;
                lab = $event.detail.lab;
            "
            @click.away="showTherapyModal = false; resetForm()"
            class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col"
        >

            <div class="px-6 py-4 border-b border-gray-200">
                <h2 id="therapy-modal-title" class="text-xl font-semibold text-gray-800">Program Terapi & Instruksi Dokter</h2>
                <p class="text-sm text-gray-500 mt-1">Lihat riwayat di sebelah kiri, buat atau perbarui instruksi di sebelah kanan.</p>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full">

                    <div class="md:col-span-2 h-full max-h-[calc(80vh-150px)] overflow-y-auto pr-3 space-y-4">
                        <h3 class="text-lg font-medium text-gray-700 mb-2 sticky top-0 bg-white py-2">Riwayat Instruksi</h3>

                        @forelse ($therapy_program_history as $program)
                        <div
                            wire:click="loadHistoryToForm({{ $program->id }})"
                            wire:loading.attr="disabled"
                            wire:target="loadHistoryToForm({{ $program->id }})"
                            @class([
                                'border p-3 rounded-lg bg-white shadow-sm cursor-pointer transition duration-150 hover:bg-gray-50',
                                'border-teal-500 ring-2 ring-teal-100' => $loop->first,
                                'border-gray-200' => !$loop->first
                            ])
                        >
                            <div class="flex justify-between items-center text-sm mb-2 pb-2 border-b">
                                <span class="font-semibold text-teal-700">
                                    Oleh: {{ $program->author_name }} </span>
                                <span class="text-gray-500 text-xs">
                                    {{ $program->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-800 space-y-2">
                                <div><strong class="text-gray-600 block text-xs">1. Masalah Klinis:</strong><p class="pl-2">{!! nl2br(e($program->masalah_klinis)) !!}</p></div>
                                <div><strong class="text-gray-600 block text-xs">2. Program Terapi:</strong><p class="pl-2">{!! nl2br(e($program->program_terapi)) !!}</p></div>
                                <div><strong class="text-gray-600 block text-xs">3. Nutrisi Enteral:</strong><p class="pl-2">{!! nl2br(e($program->nutrisi_enteral)) !!}</p></div>
                                <div><strong class="text-gray-600 block text-xs">4. Nutrisi Parenteral:</strong><p class="pl-2">{!! nl2br(e($program->nutrisi_parenteral)) !!}</p></div>
                                <div><strong class="text-gray-600 block text-xs">5. Pemeriksaan Lab:</strong><p class="pl-2">{!! nl2br(e($program->pemeriksaan_lab)) !!}</p></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 italic">
                            Belum ada riwayat program terapi.
                        </p>
                        @endforelse
                    </div>

                    <div class="md:col-span-3 space-y-5">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-700">Buat / Perbarui Instruksi</h3>
                            <button @click="resetForm()" type="button" class="px-3 py-1 bg-gray-100 text-xs font-medium text-gray-600 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                                Reset / Tulis Baru
                            </button>
                        </div>

                        <div>
                            <label for="therapy_masalah" class="block text-sm font-medium text-gray-700">1. Masalah Klinis Aktif</label>
                            <textarea id="therapy_masalah" x-model="masalah" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="..."></textarea>
                        </div>
                        <div>
                            <label for="therapy_program" class="block text-sm font-medium text-gray-700">2. Program Terapi</label>
                            <textarea id="therapy_program" x-model="program" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="..."></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="therapy_enteral" class="block text-sm font-medium text-gray-700">3. Nutrisi Enteral</label>
                                <textarea id="therapy_enteral" x-model="enteral" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="..."></textarea>
                            </div>
                            <div>
                                <label for="therapy_parenteral" class="block text-sm font-medium text-gray-700">4. Nutrisi Parenteral</label>
                                <textarea id="therapy_parenteral" x-model="parenteral" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="..."></textarea>
                            </div>
                        </div>
                        <div>
                            <label for="therapy_lab" class="block text-sm font-medium text-gray-700">5. Pemeriksaan Penunjang</label>
                            <textarea id="therapy_lab" x-model="lab" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="..."></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="showTherapyModal = false; resetForm()" type="button" class="px-4 py-2 bg-white text-sm font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                    Batal
                </button>
                <button
                    @click="$wire.saveTherapyProgram({
                        'masalah_klinis': masalah,
                        'program_terapi': program,
                        'nutrisi_enteral': enteral,
                        'nutrisi_parenteral': parenteral,
                        'pemeriksaan_lab': lab
                    }).then(() => {
                        // Cek jika tidak ada error, baru tutup modal
                        // (Logika ini bisa disempurnakan jika 'save' mengembalikan status)
                        showTherapyModal = false;
                        resetForm();
                    })"
                    wire:loading.attr="disabled"
                    wire:target="saveTherapyProgram"
                    type="button"
                    class="px-4 py-2 bg-teal-600 text-sm font-medium text-white border border-transparent rounded-md shadow-sm hover:bg-teal-700"
                >
                    <span wire:loading.remove wire:target="saveTherapyProgram">
                        Simpan Program Terapi
                    </span>
                    <span wire:loading wire:target="saveTherapyProgram">
                        Menyimpan...
                    </span>
                </button>
            </div>

        </div>
    </div>

</div>
