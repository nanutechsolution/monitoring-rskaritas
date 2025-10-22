    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="temp_incubator" class="block text-sm font-medium text-gray-700">Temp Incubator</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                <input type="text" pattern="[0-9]*([.,][0-9]+)?" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="block w-full border-0 focus:ring-0 rounded-l-md">
                <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
        </div>
        <div>
            <label for="temp_skin" class="block text-sm font-medium text-gray-700">Temp Skin</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="hr" class="block text-sm font-medium text-gray-700">Heart Rate</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
        </div>
        <div>
            <label for="rr" class="block text-sm font-medium text-gray-700">Resp. Rate</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Tekanan Darah (Sistolik/Diastolik)</label>
        <div class="flex items-center gap-2 mt-1"><input type="text" wire:model.defer="blood_pressure_systolic" class="block w-full rounded-md border-gray-300 shadow-sm"><span>/</span><input type="text" wire:model.defer="blood_pressure_diastolic" class="block w-full rounded-md border-gray-300 shadow-sm"></div>
    </div>
