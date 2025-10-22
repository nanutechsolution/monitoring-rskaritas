@if($showTherapyModal)
<div x-data="{ show: @entangle('showTherapyModal') }" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60" aria-labelledby="therapy-modal-title" role="dialog" aria-modal="true">
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] flex flex-col">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 id="therapy-modal-title" class="text-xl font-semibold text-gray-800">Program Terapi & Instruksi Dokter</h2>
            <p class="text-sm text-gray-500 mt-1">Lihat riwayat di sebelah kiri, buat atau perbarui instruksi di sebelah kanan.</p>
        </div>

        <div class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full">

                <div class="md:col-span-2 h-full max-h-[calc(80vh-150px)] overflow-y-auto pr-3 space-y-4">
                    <h3 class="text-lg font-medium text-gray-700 mb-2 sticky top-0 bg-white py-2">Riwayat Instruksi</h3>
                    @forelse ($therapy_program_history as $program)
                    <div wire:click="loadHistoryToForm({{ $program->id }})" @class([ 'border p-3 rounded-lg bg-white shadow-sm cursor-pointer transition duration-150 hover:bg-gray-50' , 'border-teal-500 ring-2 ring-teal-100'=> $loop->first,
                        'border-gray-200' => !$loop->first
                        ])
                        >
                        <div class="flex justify-between items-center text-sm mb-2 pb-2 border-b">
                            <span class="font-semibold text-teal-700">
                                Oleh: {{ $program->author_name }}
                            </span>
                            <span class="text-gray-500 text-xs">
                                {{ $program->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        {{-- INI KODE PENGGANTINYA --}}
                        <div class="text-sm text-gray-800 space-y-2">
                            <div>
                                <strong class="text-gray-600 block text-xs">1. Masalah Klinis:</strong>
                                <p class="pl-2">{!! nl2br(e($program->masalah_klinis)) !!}</p>
                            </div>
                            <div>
                                <strong class="text-gray-600 block text-xs">2. Program Terapi:</strong>
                                <p class="pl-2">{!! nl2br(e($program->program_terapi)) !!}</p>
                            </div>
                            <div>
                                <strong class="text-gray-600 block text-xs">3. Nutrisi Enteral:</strong>
                                <p class="pl-2">{!! nl2br(e($program->nutrisi_enteral)) !!}</p>
                            </div>
                            <div>
                                <strong class="text-gray-600 block text-xs">4. Nutrisi Parenteral:</strong>
                                <p class="pl-2">{!! nl2br(e($program->nutrisi_parenteral)) !!}</p>
                            </div>
                            <div>
                                <strong class="text-gray-600 block text-xs">5. Pemeriksaan Lab:</strong>
                                <p class="pl-2">{!! nl2br(e($program->pemeriksaan_lab)) !!}</p>
                            </div>
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
                        <button wire:click="resetFormFields" type="button" class="px-3 py-1 bg-gray-100 text-xs font-medium text-gray-600 border border-gray-300 rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                            Reset / Tulis Baru
                        </button>
                    </div>
                    <div>
                        <label for="therapy_masalah" class.="block text-sm font-medium text-gray-700">1. Masalah Klinis Aktif</label>
                        <textarea id="therapy_masalah" wire:model.defer="therapy_program_masalah" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: Hipotensi, gangguan pernapasan..."></textarea>
                        @error('therapy_program_masalah') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="therapy_program" class="block text-sm font-medium text-gray-700">2. Program Terapi</label>
                        <textarea id="therapy_program" wire:model.defer="therapy_program_program" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: Pemberian cairan 100ml/jam..."></textarea>
                        @error('therapy_program_program') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="therapy_enteral" class="block text-sm font-medium text-gray-700">3. Nutrisi Enteral</label>
                            <textarea id="therapy_enteral" wire:model.defer="therapy_program_enteral" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: Susu formula 100ml..."></textarea>
                            @error('therapy_program_enteral') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="therapy_parenteral" class="block text-sm font-medium text-gray-700">4. Nutrisi Parenteral</label>
                            <textarea id="therapy_parenteral" wire:model.defer="therapy_program_parenteral" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: Glukosa 5%..."></textarea>
                            @error('therapy_program_parenteral') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="therapy_lab" class="block text-sm font-medium text-gray-700">5. Pemeriksaan Penunjang</label>
                        <textarea id="therapy_lab" wire:model.defer="therapy_program_lab" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: DPL per 24 jam..."></textarea>
                        @error('therapy_program_lab') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <button wire:click="closeTherapyModal" type="button" class="px-4 py-2 bg-white text-sm font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                Batal
            </button>
            <button wire:click="saveTherapyProgram" type="button" class="px-4 py-2 bg-teal-600 text-sm font-medium text-white border border-transparent rounded-md shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                Simpan Program Terapi
            </button>
        </div>

    </div>
</div>
@endif
