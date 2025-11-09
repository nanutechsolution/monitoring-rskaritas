<div x-data="{ showTherapyModal: false }">

    <button type="button" @click="showTherapyModal = true" class="flex items-center gap-2 px-5 py-2
bg-primary-600 text-white
border rounded-lg shadow
hover:shadow-md hover:bg-primary-700
flex-shrink-0 snap-start transition-all
focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
dark:focus:ring-offset-gray-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10l5-5-5-5z"></path>
        </svg>
        <span class="font-medium">Program Terapi</span>
    </button>

    <div x-show="showTherapyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm" aria-labelledby="therapy-modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div x-show="showTherapyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-data="{
                masalah: @entangle('masalah').defer,
                program: @entangle('program').defer,
                enteral: @entangle('enteral').defer,
                parenteral: @entangle('parenteral').defer,
                lab: @entangle('lab').defer,
                resetForm() {
                    this.masalah = '';
                    this.program = '';
                    this.enteral = '';
                    this.parenteral = '';
                    this.lab = '';
                    $wire.dispatch('refresh-therapy-form');
                }
            }" @load-therapy-form.window="
                masalah = $event.detail.masalah;
                program = $event.detail.program;
                enteral = $event.detail.enteral;
                parenteral = $event.detail.parenteral;
                lab = $event.detail.lab;
            " @therapy-saved-success.window="
                showTherapyModal = false;
                resetForm();
                if ($event.detail.message) {
                    $wire.dispatch('notify', { message: $event.detail.message });
                }
            " @click.away="showTherapyModal = false; resetForm()" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 id="therapy-modal-title" class="text-xl font-semibold text-gray-800 dark:text-gray-100">Program Terapi & Instruksi Dokter</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Lihat riwayat di sebelah kiri, buat atau perbarui instruksi di sebelah kanan.</p>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full">

                    <div class="md:col-span-2 h-full max-h-[calc(80vh-150px)] overflow-y-auto pr-3 space-y-4
                                 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600
                                 scrollbar-track-gray-100 dark:scrollbar-track-gray-700">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-2 sticky top-0 bg-white dark:bg-gray-800 py-2">Riwayat Instruksi</h3>

                        @forelse ($therapy_program_history as $program)
                        <div wire:click="loadHistoryToForm({{ $program->id }})" wire:loading.attr="disabled" wire:target="loadHistoryToForm({{ $program->id }})" @class([ 'border p-3 rounded-lg bg-white dark:bg-gray-700 shadow-sm cursor-pointer transition duration-150 hover:bg-gray-50 dark:hover:bg-gray-600' , 'border-primary-500 ring-2 ring-primary-100 dark:ring-primary-900'=> $loop->first, // Highlight terbaru
                            'border-gray-200 dark:border-gray-600' => !$loop->first
                            ])
                            >
                            <div class="flex justify-between items-center text-sm mb-2 pb-2 border-b dark:border-gray-600">
                                <span class="font-semibold text-primary-700 dark:text-primary-300">
                                    Oleh: {{ $program->author_name }} </span>
                                <span class="text-gray-500 dark:text-gray-400 text-xs">
                                    {{ $program->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-800 dark:text-gray-200 space-y-2">
                                <div><strong class="text-gray-600 dark:text-gray-400 block text-xs">1. Masalah Klinis:</strong>
                                    <p class="pl-2">{!! nl2br(e($program->masalah_klinis)) !!}</p>
                                </div>
                                <div><strong class="text-gray-600 dark:text-gray-400 block text-xs">2. Program Terapi:</strong>
                                    <p class="pl-2">{!! nl2br(e($program->program_terapi)) !!}</p>
                                </div>
                                <div><strong class="text-gray-600 dark:text-gray-400 block text-xs">3. Nutrisi Enteral:</strong>
                                    <p class="pl-2">{!! nl2br(e($program->nutrisi_enteral)) !!}</p>
                                </div>
                                <div><strong class="text-gray-600 dark:text-gray-400 block text-xs">4. Nutrisi Parenteral:</strong>
                                    <p class="pl-2">{!! nl2br(e($program->nutrisi_parenteral)) !!}</p>
                                </div>
                                <div><strong class="text-gray-600 dark:text-gray-400 block text-xs">5. Pemeriksaan Lab:</strong>
                                    <p class="pl-2">{!! nl2br(e($program->pemeriksaan_lab)) !!}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                            Belum ada riwayat program terapi.
                        </p>
                        @endforelse
                    </div>

                    <div class="md:col-span-3 space-y-5">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200">Buat / Perbarui Instruksi</h3>
                            <button @click="resetForm()" type="button" class="px-3 py-1 bg-gray-100 dark:bg-gray-700
text-xs font-medium text-gray-600 dark:text-gray-300
border border-gray-300 dark:border-gray-600
rounded-md shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600
focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400
dark:focus:ring-offset-gray-800">
                                Reset / Tulis Baru
                            </button>
                        </div>

                        @error('currentCycleId')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400 font-semibold p-2 border border-red-400 rounded-md bg-red-50 dark:bg-red-900/50">
                            {{ $message }}
                        </p>
                        @enderror

                        @php
                        $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
                        $inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                        border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700
                        text-gray-900 dark:text-gray-200
                        focus:border-primary-500 focus:ring-primary-500';
                        @endphp

                        <div>
                            <label for="therapy_masalah" class="{{ $labelClasses }}">1. Masalah Klinis Aktif</label>
                            <textarea id="therapy_masalah" x-model.defer="masalah" rows="3" class="{{ $inputClasses }}" placeholder="..."></textarea>
                            @error('masalah_klinis')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="therapy_program" class="{{ $labelClasses }}">2. Program Terapi</label>
                            <textarea id="therapy_program" x-model.defer="program" rows="4" class="{{ $inputClasses }}" placeholder="..."></textarea>
                            @error('program_terapi')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="therapy_enteral" class="{{ $labelClasses }}">3. Nutrisi Enteral</label>
                                <textarea id="therapy_enteral" x-model.defer="enteral" rows="3" class="{{ $inputClasses }}" placeholder="..."></textarea>
                                @error('nutrisi_enteral')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="therapy_parenteral" class="{{ $labelClasses }}">4. Nutrisi Parenteral</label>
                                <textarea id="therapy_parenteral" x-model.defer="parenteral" rows="3" class="{{ $inputClasses }}" placeholder="..."></textarea>
                                @error('nutrisi_parenteral')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="therapy_lab" class="{{ $labelClasses }}">5. Pemeriksaan Penunjang</label>
                            <textarea id="therapy_lab" x-model.defer="lab" rows="3" class="{{ $inputClasses }}" placeholder="..."></textarea>
                            @error('pemeriksaan_lab')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button @click="showTherapyModal = false; resetForm()" type="button" class="px-4 py-2 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300
 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm
 hover:bg-gray-50 dark:hover:bg-gray-600
 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
 dark:focus:ring-offset-gray-800">
                    Batal
                </button>
                <button @click="$wire.saveTherapyProgram({
                    'masalah_klinis': masalah,
                    'program_terapi': program,
                    'nutrisi_enteral': enteral,
                    'nutrisi_parenteral': parenteral,
                    'pemeriksaan_lab': lab
                })" wire:loading.attr="disabled" wire:target="saveTherapyProgram" type="button" class="px-4 py-2 bg-primary-600 text-sm font-medium text-white border border-transparent
rounded-md shadow-sm hover:bg-primary-700
focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
dark:focus:ring-offset-gray-800">
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
