{{-- ============================================== --}}
 {{-- MODAL PEMBERIAN OBAT (REFAKTOR ALPINE PENUH) --}}
 {{-- ============================================== --}}
 {{-- Pindahkan state form ke x-data dan inisialisasi waktu --}}
 <div x-data="{
     openMedicationModal: false,
     medicationName: '',
     medicationDose: '',
     medicationRoute: '',
     // Inisialisasi waktu saat ini di sini menggunakan PHP
     medicationGivenAt: '{{ now()->format('Y-m-d\TH:i') }}',
     resetForm() {
         this.medicationName = '';
         this.medicationDose = '';
         this.medicationRoute = '';
         // Reset juga ke waktu sekarang saat form dibersihkan/modal dibuka lagi
         this.medicationGivenAt = '{{ now()->format('Y-m-d\TH:i') }}';
     }
 }">
     {{-- Panggil resetForm saat membuka, agar waktu selalu terbaru --}}
     <button @click="openMedicationModal = true; resetForm()" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-yellow-50 flex-shrink-0 snap-start transition-all">
         <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"></path>
         </svg>
         <span class="font-medium text-gray-800">Pemberian Obat</span>
     </button>

     <div x-show="openMedicationModal"
          x-cloak
          {{-- Transisi container modal --}}
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
              {{-- Transisi modal box --}}
              x-transition:enter="ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave="ease-in duration-200"
              x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              class="relative bg-white rounded-lg shadow-xl w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh]">

             <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                   <h3 class="text-lg font-semibold text-gray-800">Tambah Pemberian Obat</h3>
                   <button type="button" @click="openMedicationModal = false; resetForm()" class="text-gray-400 hover:text-gray-600 transition">
                       <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                   </button>
               </div>

             <div class="p-6 space-y-4 overflow-y-auto">
                 <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Pemberian</label>
                     {{-- Ganti wire:model -> x-model, tambahkan readonly --}}
                     <input
                         type="datetime-local"
                         x-model="medicationGivenAt" {{-- <-- Pakai x-model --}}
                         class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         readonly {{-- <-- Tambahkan readonly --}}
                     >
                     {{-- Error Livewire masih bisa tampil --}}
                     @error('given_at') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                 </div>
                 <div>
                     <label for="medication_name_modal" class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                     {{-- Ganti wire:model -> x-model --}}
                     <input id="medication_name_modal" type="text" x-model="medicationName" list="recent-meds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Ketik atau pilih dari riwayat...">
                     <datalist id="recent-meds">
                         @foreach($recentMedicationNames as $name)
                         <option value="{{ $name }}">
                         @endforeach
                     </datalist>
                      @error('medication_name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                 </div>
                 <div class="grid grid-cols-2 gap-4">
                     <div>
                         <label for="medication_dose_modal" class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                         {{-- Ganti wire:model -> x-model --}}
                         <input id="medication_dose_modal" type="text" x-model="medicationDose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: 3x80mg">
                          @error('dose') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                     </div>
                     <div>
                         <label for="medication_route_modal" class="block text-sm font-medium text-gray-700 mb-1">Rute</label>
                         {{-- Ganti wire:model -> x-model --}}
                         <input id="medication_route_modal" type="text" x-model="medicationRoute" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: IV">
                           @error('route') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                     </div>
                 </div>
             </div>

             <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                 <button type="button" @click="openMedicationModal = false; resetForm()"
                         class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                     Batal
                 </button>
                 {{-- Tombol simpan kirim data Alpine --}}
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
                     class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                     <svg wire:loading wire:target="saveMedication" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                     <span wire:loading.remove wire:target="saveMedication">Simpan Obat</span>
                     <span wire:loading wire:target="saveMedication">Menyimpan...</span>
                 </button>
             </div>
         </div>
     </div>
 </div>
