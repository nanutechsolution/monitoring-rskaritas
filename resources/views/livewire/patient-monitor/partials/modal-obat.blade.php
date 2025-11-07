<div x-data="{
         openMedicationModal: false,
         medicationName: '',
         medicationDose: '',
         medicationRoute: '',
         medicationGivenAt: '{{ now()->format('Y-m-d\TH:i') }}',
         resetForm() {
             this.medicationName = '';
             this.medicationDose = '';
             this.medicationRoute = '';
             this.medicationGivenAt = '{{ now()->format('Y-m-d\TH:i') }}';
         }
     }">

    {{-- Tombol Buka Modal --}}
    <button @click="openMedicationModal = true; resetForm()"
            class="flex items-center gap-2 px-5 py-2
                   bg-white dark:bg-gray-800
                   border dark:border-gray-700 rounded-lg shadow
                   hover:shadow-md hover:bg-primary-50 dark:hover:bg-gray-700
                   flex-shrink-0 snap-start transition-all">
        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"></path>
        </svg>
        <span class="font-medium text-gray-800 dark:text-gray-100">Pemberian Obat</span>
    </button>

    {{-- Modal --}}
    <div x-show="openMedicationModal"
         x-cloak
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm"
         @keydown.escape.window="openMedicationModal = false">

        <div x-show="openMedicationModal"
             @click.away="openMedicationModal = false; resetForm()"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Tambah Pemberian Obat</h3>
                <button type="button" @click="openMedicationModal = false; resetForm()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            @php
                // --- Kelas Helper untuk Konsistensi ---
                $inputModalClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                     border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-700
                                     text-gray-900 dark:text-gray-200
                                     focus:border-primary-500 focus:ring-primary-500';
                $labelModalClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1';
                $errorModalClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';
            @endphp

            <div class="p-6 space-y-4 overflow-y-auto">
                <div>
                    <label class="{{ $labelModalClasses }}">Waktu Pemberian</label>
                    <input
                        type="datetime-local"
                        x-model="medicationGivenAt"
                        class="{{ $inputModalClasses }} bg-gray-100 dark:bg-gray-600 cursor-not-allowed"
                        readonly
                    >
                    @error('given_at') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="medication_name_modal" class="{{ $labelModalClasses }}">Nama Obat</label>
                    <input id="medication_name_modal" type="text" x-model="medicationName" list="recent-meds" class="{{ $inputModalClasses }}" placeholder="Ketik atau pilih dari riwayat...">
                    <datalist id="recent-meds">
                        @foreach($recentMedicationNames as $name)
                        <option value="{{ $name }}">
                        @endforeach
                    </datalist>
                     @error('medication_name') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="medication_dose_modal" class="{{ $labelModalClasses }}">Dosis</label>
                        <input id="medication_dose_modal" type="text" x-model="medicationDose" class="{{ $inputModalClasses }}" placeholder="Contoh: 3x80mg">
                         @error('dose') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="medication_route_modal" class="{{ $labelModalClasses }}">Rute</label>
                        <input id="medication_route_modal" type="text" x-model="medicationRoute" class="{{ $inputModalClasses }}" placeholder="Contoh: IV">
                         @error('route') <span class="{{ $errorModalClasses }}">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                <button type="button" @click="openMedicationModal = false; resetForm()"
                        class="px-4 py-2 text-sm font-medium rounded-md
                               border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-700 dark:text-gray-300
                               hover:bg-gray-50 dark:hover:bg-gray-600
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
                               dark:focus:ring-offset-gray-800
                               transition shadow-sm">
                    Batal
                </button>

                <button
                    type="button"
                    @click="$wire.saveMedication({
                        medication_name: medicationName,
                        dose: medicationDose,
                        route: medicationRoute,
                        given_at: medicationGivenAt
                    }).then((success) => {
                        if (success) {
                            openMedicationModal = false;
                            resetForm();
                        }
                    })"
                    wire:loading.attr="disabled" wire:target="saveMedication" wire:loading.class="opacity-75 cursor-wait"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent
                           bg-primary-600 text-white
                           hover:bg-primary-700
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
                           dark:focus:ring-offset-gray-800
                           transition shadow-sm">
                    <svg wire:loading wire:target="saveMedication" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span wire:loading.remove wire:target="saveMedication">Simpan Obat</span>
                    <span wire:loading wire:target="saveMedication">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
</div>
