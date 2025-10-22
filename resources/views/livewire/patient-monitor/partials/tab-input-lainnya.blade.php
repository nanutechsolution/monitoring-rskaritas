  <div>
      <label for="sat_o2" class="block text-sm font-medium text-gray-700">Sat O2</label>
      <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">%</span></div>
  </div>
  <div>
      <label for="irama_ekg" class="block text-sm font-medium text-gray-700">Irama EKG</label>
      <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
  </div>
  <div>
      <label class="block text-sm font-medium text-gray-700">Skala Nyeri (PIPP)</label>
      <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ $skala_nyeri ?? '-' }}</div>
      <p class="mt-1 text-xs text-gray-500">Diisi otomatis setelah penilaian PIPP.</p>
  </div>
  <div>
      <label for="humidifier_inkubator" class="block text-sm font-medium text-gray-700">Humidifier Inkubator</label>
      <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
  </div>
