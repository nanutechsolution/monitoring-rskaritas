@php

    // Label Standar
    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

    // Select Dropdown
    $selectClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                     border-gray-300 dark:border-gray-600
                     bg-white dark:bg-gray-700
                     text-gray-900 dark:text-gray-200
                     focus:border-primary-500 focus:ring-primary-500';

    // Input di dalam Kartu
    $inputClasses = 'mt-1 w-full rounded-md shadow-sm text-sm
                     border-gray-300 dark:border-gray-600
                     bg-white dark:bg-gray-700
                     text-gray-900 dark:text-gray-200
                     focus:border-primary-500 focus:ring-primary-500';

    // Kartu Background (untuk Spontan, CPAP, dll)
    $cardClasses = 'space-y-2 p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600';

    // Judul Kartu
    $cardTitleClasses = 'text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide';
@endphp

<div
    x-data="{ mode: @entangle('respiratory_mode').live }"
    class="space-y-3"
>
    <div>
        <label for="respiratory_mode" class="{{ $labelClasses }}">
            Mode Pernapasan
        </label>
        <select
            id="respiratory_mode"
            x-model="mode"
            class="{{ $selectClasses }}"
        >
            <option value="">Pilih Mode...</option>
            <option value="spontan">Spontan (Nasal)</option>
            <option value="cpap">CPAP</option>
            <option value="hfo">HFO</option>
            <option value="monitor">Ventilator Konvensional</option>
        </select>
    </div>

    <div x-show="mode === 'spontan'" x-transition>
        <div class="{{ $cardClasses }}">
            <h5 class="{{ $cardTitleClasses }}">Setting Spontan</h5>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                    <input type="text" wire:model.defer="spontan_fio2" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">Flow (Lpm)</label>
                    <input type="text" wire:model.defer="spontan_flow" class="{{ $inputClasses }}">
                </div>
            </div>
        </div>
    </div>

    <div x-show="mode === 'cpap'" x-transition>
        <div class="{{ $cardClasses }}">
            <h5 class="{{ $cardTitleClasses }}">Setting CPAP</h5>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                    <input type="text" wire:model.defer="cpap_fio2" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">Flow (Lpm)</label>
                    <input type="text" wire:model.defer="cpap_flow" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">PEEP (cmH₂O)</label>
                    <input type="text" wire:model.defer="cpap_peep" class="{{ $inputClasses }}">
                </div>
            </div>
        </div>
    </div>

    <div x-show="mode === 'hfo'" x-transition>
        <div class="{{ $cardClasses }}">
            <h5 class="{{ $cardTitleClasses }}">Setting HFO</h5>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                    <input type="text" wire:model.defer="hfo_fio2" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">Frekuensi (Hz)</label>
                    <input type="text" wire:model.defer="hfo_frekuensi" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">MAP (cmH₂O)</label>
                    <input type="text" wire:model.defer="hfo_map" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">Amplitudo (ΔP)</label>
                    <input type="text" wire:model.defer="hfo_amplitudo" class="{{ $inputClasses }}">
                </div>
                <div>
                    <label class="block text-sm {{ $labelClasses }}">IT (%)</label>
                    <input type="text" wire:model.defer="hfo_it" class="{{ $inputClasses }}">
                </div>
            </div>
        </div>
    </div>

    <div x-show="mode === 'monitor'" x-transition>
        <div class="{{ $cardClasses }} p-4">
            <h5 class="{{ $cardTitleClasses }}">Setting Ventilator</h5>
            <div class="grid grid-cols-2 gap-4 items-start">
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">Mode</label>
                    <input type="text" wire:model.defer="monitor_mode" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">FiO₂ (%)</label>
                    <input type="text" wire:model.defer="monitor_fio2" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">PEEP (cmH₂O)</label>
                    <input type="text" wire:model.defer="monitor_peep" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">PIP (cmH₂O)</label>
                    <input type="text" wire:model.defer="monitor_pip" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">TV/Vte (ml)</label>
                    <input type="text" wire:model.defer="monitor_tv_vte" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">RR / RR Spontan</label>
                    <input type="text" wire:model.defer="monitor_rr_spontan" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">P.Max (cmH₂O)</label>
                    <input type="text" wire:model.defer="monitor_p_max" class="{{ $inputClasses }} h-9">
                </div>
                <div class="flex flex-col">
                    <label class="block text-sm {{ $labelClasses }}">I : E</label>
                    <input type="text" wire:model.defer="monitor_ie" class="{{ $inputClasses }} h-9">
                </div>
            </div>
        </div>
    </div>
</div>
