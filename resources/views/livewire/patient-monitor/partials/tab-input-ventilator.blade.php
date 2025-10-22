<div>
    <label for="respiratory_mode" class="block text-sm font-medium text-gray-700">
        Mode Pernapasan
    </label>
    <select id="respiratory_mode" wire:model.live="respiratory_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        <option value="">Pilih Mode...</option>
        <option value="spontan">Spontan (Nasal)</option>
        <option value="cpap">CPAP</option>
        <option value="hfo">HFO</option>
        <option value="monitor">Ventilator Konvensional</option>
    </select>
</div>

{{-- ================== SPONTAN ================== --}}
@if ($respiratory_mode === 'spontan')
<div class="space-y-2 p-3 bg-gray-50 rounded-md border">
    <h5 class="text-xs font-bold text-gray-500">SETTING SPONTAN</h5>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">FiO2 (%)</label>
            <input type="text" wire:model.defer="spontan_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">Flow (Lpm)</label>
            <input type="text" wire:model.defer="spontan_flow" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>
</div>
@endif

{{-- ================== CPAP ================== --}}
@if ($respiratory_mode === 'cpap')
<div class="space-y-2 p-3 bg-gray-50 rounded-md border">
    <h5 class="text-xs font-bold text-gray-500">SETTING CPAP</h5>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">FiO2 (%)</label>
            <input type="text" wire:model.defer="cpap_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">Flow (Lpm)</label>
            <input type="text" wire:model.defer="cpap_flow" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">PEEP (cmH2O)</label>
            <input type="text" wire:model.defer="cpap_peep" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>
</div>
@endif

{{-- ================== HFO ================== --}}
@if ($respiratory_mode === 'hfo')
<div class="space-y-2 p-3 bg-gray-50 rounded-md border">
    <h5 class="text-xs font-bold text-gray-500">SETTING HFO</h5>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm">FiO2 (%)</label>
            <input type="text" wire:model.defer="hfo_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">Frekuensi (Hz)</label>
            <input type="text" wire:model.defer="hfo_frekuensi" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">MAP (cmH2O)</label>
            <input type="text" wire:model.defer="hfo_map" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">Amplitudo (ΔP)</label>
            <input type="text" wire:model.defer="hfo_amplitudo" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
        <div>
            <label class="block text-sm">IT (%)</label>
            <input type="text" wire:model.defer="hfo_it" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
        </div>
    </div>
</div>
@endif

{{-- ================== MONITOR ================== --}}
@if ($respiratory_mode === 'monitor')
<div class="space-y-2 p-4 bg-gray-50 rounded-lg border">
    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Setting Ventilator</h5>

    <div class="grid grid-cols-2 gap-4 items-start">
        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">Mode</label>
            <input type="text" wire:model.defer="monitor_mode" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">FiO₂ (%)</label>
            <input type="text" wire:model.defer="monitor_fio2" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">PEEP (cmH₂O)</label>
            <input type="text" wire:model.defer="monitor_peep" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">PIP (cmH₂O)</label>
            <input type="text" wire:model.defer="monitor_pip" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">TV/Vte (ml)</label>
            <input type="text" wire:model.defer="monitor_tv_vte" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">RR / RR Spontan</label>
            <input type="text" wire:model.defer="monitor_rr_spontan" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">P.Max (cmH₂O)</label>
            <input type="text" wire:model.defer="monitor_p_max" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>

        <div class="flex flex-col">
            <label class="block text-sm text-gray-700">I : E</label>
            <input type="text" wire:model.defer="monitor_ie" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
        </div>
    </div>
</div>
@endif
