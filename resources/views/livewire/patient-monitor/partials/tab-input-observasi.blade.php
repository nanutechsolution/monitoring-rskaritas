@php
$labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

$inputWrapperClasses = 'mt-1 flex items-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm
bg-white dark:bg-gray-700
focus-within:ring-1 focus-within:ring-primary-500 focus-within:border-primary-500';

$inputClasses = 'block w-full border-0 bg-transparent focus:ring-0 rounded-l-md
text-gray-900 dark:text-gray-200';

$addonClasses = 'whitespace-nowrap bg-gray-50 dark:bg-gray-600 px-3 text-gray-500 dark:text-gray-400
rounded-r-md border-l border-gray-300 dark:border-gray-600';

$errorClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';

@endphp
<div class="space-y-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
    <div class="grid grid-cols-2 gap-4 ">
        <div>
            <label for="temp_incubator" class="{{ $labelClasses }}">Temp Incubator</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" pattern="[0-9]*([.,][0-9]+)?" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">°C</span>
            </div>
            @error('temp_incubator') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="temp_skin" class="{{ $labelClasses }}">Temp Skin</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">°C</span>
            </div>
            @error('temp_skin') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="hr" class="{{ $labelClasses }}">Heart Rate</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">x/mnt</span>
            </div>
            @error('hr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="rr" class="{{ $labelClasses }}">Resp. Rate</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">x/mnt</span>
            </div>
            @error('rr') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="blood_pressure_systolic" class="{{ $labelClasses }}">Sistolik</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" wire:model.lazy="blood_pressure_systolic" id="blood_pressure_systolic" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">mmHg</span>
            </div>
            @error('blood_pressure_systolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="blood_pressure_diastolic" class="{{ $labelClasses }}">Diastolik</label>
            <div class="{{ $inputWrapperClasses }}">
                <input type="number" wire:model.lazy="blood_pressure_diastolic" id="blood_pressure_diastolic" class="{{ $inputClasses }}">
                <span class="{{ $addonClasses }}">mmHg</span>
            </div>
            @error('blood_pressure_diastolic') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="sat_o2" class="{{ $labelClasses }}">Sat O2</label>
        <div class="{{ $inputWrapperClasses }}">
            <input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="{{ $inputClasses }}">
            <span class="{{ $addonClasses }}">%</span>
        </div>
        @error('sat_o2') <p class="{{ $errorClasses }}">{{ $message }}</p> @enderror
    </div>
</div>
