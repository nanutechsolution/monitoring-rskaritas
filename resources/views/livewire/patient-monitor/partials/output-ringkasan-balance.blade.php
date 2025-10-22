     <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
         <div class="p-6 text-gray-900">
             <h3 class="text-lg font-medium border-b pb-3">Ringkasan Balance Cairan 24 Jam</h3>

             <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                 <div>Total Masuk (CM): <span class="font-bold text-blue-600">{{ $totalIntake24h }} ml</span></div>
                 <div>Total Keluar (CK): <span class="font-bold text-red-600">{{ $totalOutput24h }} ml</span></div>

                 <div>Produksi Urine: <span class="font-bold">{{ $totalUrine24h }} ml</span></div>

                 <div class="flex items-center space-x-2">
                     <label for="daily_iwl" class="whitespace-nowrap">IWL:</label>
                     <input type="number" step="0.1" id="daily_iwl" wire:model.defer="daily_iwl" class="form-input py-1 px-2 text-sm w-20 rounded-md border-gray-300 shadow-sm">
                     <button type="button" wire:click="saveDailyIwl" class="text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Simpan</button>
                 </div>

                 <div class="col-span-1 sm:col-span-2 text-gray-600 mt-2">
                     BC 24 Jam Sebelumnya:
                     <span class="font-bold">
                         {{ $previousBalance24h !== null ? ($previousBalance24h >= 0 ? '+' : '') . $previousBalance24h . ' ml' : 'N/A' }}
                     </span>
                 </div>
             </div>

             <div class="mt-4 border-t pt-3 text-center text-sm sm:text-base">
                 Balance Cairan 24 Jam:
                 <span class="text-xl font-bold {{ $balance24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                     {{ $balance24h >= 0 ? '+' : '' }}{{ $balance24h }} ml
                 </span>
             </div>
         </div>
     </div>
