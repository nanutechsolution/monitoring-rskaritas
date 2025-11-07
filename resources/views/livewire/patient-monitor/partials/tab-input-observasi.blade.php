    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="temp_incubator" class="block text-sm font-medium text-gray-700">Temp Incubator</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                <input type="text" pattern="[0-9]*([.,][0-9]+)?" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="block w-full border-0 focus:ring-0 rounded-l-md">
                <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
            @error('temp_incubator')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

        </div>
        <div>
            <label for="temp_skin" class="block text-sm font-medium text-gray-700">Temp Skin</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
            @error('temp_skin')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="hr" class="block text-sm font-medium text-gray-700">Heart Rate</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
            @error('hr')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

        </div>
        <div>
            <label for="rr" class="block text-sm font-medium text-gray-700">Resp. Rate</label>
            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
            @error('rr')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror

        </div>
    </div>
    <div class="grid grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Sistolik</label>
            <input type="number" wire:model.lazy="blood_pressure_systolic" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-teal-500 focus:border-teal-500">
            @error('blood_pressure_systolic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Diastolik</label>
            <input type="number" wire:model.lazy="blood_pressure_diastolic" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-teal-500 focus:border-teal-500">
            @error('blood_pressure_diastolic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="sat_o2" class="block text-sm font-medium text-gray-700">Sat O2</label>
        <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">%</span></div>
    </div>
