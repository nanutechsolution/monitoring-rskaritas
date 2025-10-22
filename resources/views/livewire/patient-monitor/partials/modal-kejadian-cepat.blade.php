 @if ($showEventModal)
 <div class="fixed inset-0 z-50 flex items-center justify-center">
     <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeEventModal"></div>
     <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
         <h3 class="text-lg font-medium text-gray-900">Catat Kejadian</h3>
         <p class="text-sm text-gray-500 mt-1">Pilih semua kejadian yang terjadi pada waktu yang sama.</p>
         <div class="mt-4 space-y-2 border-t pt-4">
             <div class="grid grid-cols-2 gap-x-4 gap-y-2"><label for="event_cyanosis" class="flex items-center space-x-2"><input id="event_cyanosis" type="checkbox" wire:model="event_cyanosis" class="rounded border-gray-300"><span>Cyanosis</span></label><label for="event_pucat" class="flex items-center space-x-2"><input id="event_pucat" type="checkbox" wire:model="event_pucat" class="rounded border-gray-300"><span>Pucat</span></label>

    {{-- Checkbox Kejadian Lain --}}
    <label class="flex items-center space-x-2 col-span-2">
        <input type="checkbox" wire:model="event_lain_checked" class="rounded border-gray-300">
        <span>Kejadian Lain</span>
    </label>

    {{-- Input teks muncul jika checkbox dicentang --}}
    <input
        type="text"
        wire:model="event_lain_text"
        class="border rounded-md p-1 col-span-2 mt-1"
        placeholder="Tulis kejadian lain..."
        @if(!$event_lain_checked) hidden @endif
    ><label for="event_ikterus" class="flex items-center space-x-2"><input id="event_ikterus" type="checkbox" wire:model="event_ikterus" class="rounded border-gray-300"><span>Ikterus</span></label><label for="event_crt" class="flex items-center space-x-2"><input id="event_crt" type="checkbox" wire:model="event_crt_less_than_2" class="rounded border-gray-300"><span>CRT &lt; 2 detik</span></label><label for="event_bradikardia" class="flex items-center space-x-2"><input id="event_bradikardia" type="checkbox" wire:model="event_bradikardia" class="rounded border-gray-300"><span>Bradikardia</span></label><label for="event_stimulasi" class="flex items-center space-x-2"><input id="event_stimulasi" type="checkbox" wire:model="event_stimulasi" class="rounded border-gray-300"><span>Stimulasi</span></label></div>
         </div>
         <div class="mt-6 flex justify-end space-x-3"><button type="button" wire:click="closeEventModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button><button type="button" wire:click="saveEvent" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">Simpan Kejadian</button></div>
     </div>
 </div>
 @endif
