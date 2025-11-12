@php

// Label Standar
$labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

// Input Form Biasa
$inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
border-gray-300 dark:border-gray-600
bg-white dark:bg-gray-700
text-gray-900 dark:text-gray-200
focus:border-primary-500 focus:ring-primary-500';

// Teks Error
$errorClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';
@endphp

<div>
    <label for="irama_ekg" class="{{ $labelClasses }}">Irama EKG</label>
    <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="{{ $inputClasses }}">
    @error('irama_ekg')
    <p class="{{ $errorClasses }}">{{ $message }}</p>
    @enderror
</div>

<div x-data="{
        showPippModal: false,
        flacc_face: null,
        flacc_legs: null,
        flacc_activity: null,
        flacc_cry: null,
        flacc_consolability: null,
        get totalFlaccScore() {
            return [this.flacc_face, this.flacc_legs, this.flacc_activity, this.flacc_cry, this.flacc_consolability]
                .map(v => parseInt(v || 0))
                .reduce((a, b) => a + b, 0);
        }
    }" class="space-y-3">

    <label class="{{ $labelClasses }}">Penilaian Nyeri Pediatrik (FLACC/PIPP)</label>

    <div class="flex items-center">
        <div class="flex-1 rounded-l-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-3 py-2 text-gray-700 dark:text-gray-200 text-sm">
            {{ $skala_nyeri ?? '-' }}
        </div>
    </div>
</div>
<div>
    <label for="humidifier_inkubator" class="{{ $labelClasses }}">Humidifier Inkubator</label>
    <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="{{ $inputClasses }}">
    @error('humidifier_inkubator')
    <p class="{{ $errorClasses }}">{{ $message }}</p>
    @enderror
</div>
