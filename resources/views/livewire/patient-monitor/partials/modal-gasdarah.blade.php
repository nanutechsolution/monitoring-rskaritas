
 <div x-data="{
     showBloodGasModal: false,
     taken_at: '{{ now()->format('Y-m-d\TH:i') }}',
     gula_darah: null,
     ph: null,
     pco2: null,
     po2: null,
     hco3: null,
     be: null,
     sao2: null,
     resetForm() {
         this.taken_at = '{{ now()->format('Y-m-d\TH:i') }}';
         this.gula_darah = null; this.ph = null; this.pco2 = null;
         this.po2 = null; this.hco3 = null; this.be = null; this.sao2 = null;
     }
  }">
     <button type.="button" @click="showBloodGasModal = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-red-50 flex-shrink-0 snap-start transition-all">
         <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6"></path></svg>
         <span class="font-medium text-gray-800">Gas Darah</span>
     </button>

     <div x-show="showBloodGasModal"
          x-cloak
          x-transition:enter="ease-out duration-300"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="ease-in duration-200"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm"
          @keydown.escape.window="showBloodGasModal = false">

         <div x-show="showBloodGasModal"
              @click.away="showBloodGasModal = false; resetForm()"
              x-transition:enter="ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave="ease-in duration-200"
              x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
              x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              class="relative w-full max-w-3xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh] overflow-hidden">
             <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                 <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                     <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-6 3h12a2 2 0 002-2V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                     Catat Hasil Analisis Gas Darah (AGD)
                 </h3>
                 <button @click="showBloodGasModal = false; resetForm()" class="text-gray-400 hover:text-gray-600 transition">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                 </button>
             </div>

             <div class="px-6 py-5 overflow-y-auto">
                 <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-5 gap-y-6">
                     <div class="sm:col-span-2 md:col-span-4">
                         <label for="form_taken_at_gas" class="block text-sm font-medium text-gray-700 mb-1">Waktu Pengambilan Sampel</label>
                         <input id="form_taken_at_gas" type="datetime-local" x-model="taken_at"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                         @error('taken_at') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                     </div>

                     @php
                     $bloodGasFields = [
                         ['id' => 'ph', 'label' => 'pH', 'step' => '0.001', 'placeholder' => '7.35-7.45'],
                         ['id' => 'pco2', 'label' => 'PCO₂', 'step' => '0.1', 'unit' => 'mmHg', 'placeholder' => '35-45'],
                         ['id' => 'po2', 'label' => 'PO₂', 'step' => '0.1', 'unit' => 'mmHg', 'placeholder' => '80-100'],
                         ['id' => 'hco3', 'label' => 'HCO₃', 'step' => '0.1', 'unit' => 'mEq/L', 'placeholder' => '22-26'],
                         ['id' => 'be', 'label' => 'BE', 'step' => '0.1', 'unit' => 'mEq/L', 'placeholder' => '-2 to +2'],
                         ['id' => 'sao2', 'label' => 'SaO₂', 'step' => '0.1', 'unit' => '%', 'placeholder' => '>95'],
                         ['id' => 'gula_darah', 'label' => 'Gula Darah', 'step' => '1', 'unit' => 'mg/dL', 'placeholder' => '70-140'],
                     ];
                     @endphp

                     @foreach ($bloodGasFields as $field)
                     <div>
                         <label for="form_gas_{{ $field['id'] }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}
                             @if(isset($field['unit']))<span class="text-xs text-gray-500">({{ $field['unit'] }})</span>@endif
                         </label>
                         <input id="form_gas_{{ $field['id'] }}" type="number"
                                step="{{ $field['step'] }}"
                                x-model="{{ $field['id'] }}"
                                placeholder="{{ $field['placeholder'] ?? '' }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm appearance-none [-moz-appearance:textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none">
                         @error($field['id']) <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                     </div>
                     @endforeach
                 </div>
             </div>

             <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end items-center space-x-3">
                 <button type="button" @click="showBloodGasModal = false; resetForm()"
                         class="px-4 py-2 text-sm font-medium rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                     Batal
                 </button>
                 <button
                     type="button"
                     @click="$wire.saveBloodGasResult({
                         taken_at: taken_at,
                         gula_darah: gula_darah,
                         ph: ph,
                         pco2: pco2,
                         po2: po2,
                         hco3: hco3,
                         be: be,
                         sao2: sao2
                     }).then((success) => {
                         if (success) {
                             showBloodGasModal = false;
                             resetForm();
                         }
                     })"
                     wire:loading.attr="disabled" wire:target="saveBloodGasResult" wire:loading.class="opacity-75 cursor-wait"
                     class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition shadow-sm">
                     <svg wire:loading wire:target="saveBloodGasResult" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                     <span wire:loading.remove wire:target="saveBloodGasResult">Simpan Hasil</span>
                     <span wire:loading wire:target="saveBloodGasResult">Menyimpan...</span>
                 </button>
             </div>
         </div>
     </div>
 </div>
