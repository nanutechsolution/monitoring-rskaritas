<div class="space-y-4">
    {{-- INTAKE --}}
    <div class="space-y-3 p-4 bg-gray-50 rounded-lg border">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Intake (Cairan Masuk)</h5>
        {{-- Parenteral --}}
        <label class="block text-sm font-medium text-gray-700">Parenteral (Infus)</label>
        <div class="space-y-2">
            @foreach ($parenteral_intakes as $index => $intake)
            <div class="flex items-center gap-2" wire:key="parenteral-{{ $index }}">
                <input type="text" wire:model.lazy="parenteral_intakes.{{ $index }}.name" placeholder="Nama Cairan" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <input type="number" step="0.1" wire:model.lazy="parenteral_intakes.{{ $index }}.volume" placeholder="Volume (cc)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="button" wire:click="removeParenteralIntake({{ $index }})" class="text-red-500 hover:text-red-700 text-lg leading-none font-bold" title="Hapus">
                    &times;
                </button>
            </div>
            @endforeach
        </div>
        <button type="button" wire:click="addParenteralIntake" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
            + Tambah Infus
        </button>
        {{-- Enteral --}}
        <label class="block text-sm font-medium text-gray-700 mt-3">Enteral (OGT/Oral)</label>
        <div class="space-y-2">
            @foreach ($enteral_intakes as $index => $intake)
            <div class="flex items-center gap-2" wire:key="enteral-{{ $index }}">
                <input type="text" wire:model.lazy="enteral_intakes.{{ $index }}.name" placeholder="Jenis (ASI / Susu Formula / Puasa)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                {{-- volume disembunyikan kalau puasa --}}
                @if (strtolower($enteral_intakes[$index]['name'] ?? '') !== 'puasa')
                <input type="number" step="0.1" wire:model.lazy="enteral_intakes.{{ $index }}.volume" placeholder="Volume (ml)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                @else
                <input type="text" disabled value="-" class="w-1/2 bg-gray-100 text-center rounded-md border-gray-300 text-sm">
                @endif

                <button type="button" wire:click="removeEnteralIntake({{ $index }})" class="text-red-500 hover:text-red-700 text-lg leading-none font-bold" title="Hapus">
                    &times;
                </button>
            </div>
            @endforeach
        </div>
        <button type="button" wire:click="addEnteralIntake" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
            + Tambah Enteral
        </button>

        <hr class="my-3 border-gray-200">

        {{-- OGT & Oral --}}
        <div class="grid grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">OGT (cc)</label>
                <input type="number" step="0.1" wire:model.defer="intake_ogt" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Oral (cc)</label>
                <input type="number" step="0.1" wire:model.defer="intake_oral" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
        </div>
    </div>

    {{-- OUTPUT --}}
    <div class="space-y-3 p-4 bg-gray-50 rounded-lg border">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Output (Cairan Keluar)</h5>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Urine (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_urine" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">BAB (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_bab" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
            {{-- <div>
                <label class="block text-sm font-medium text-gray-700">Residu / Muntah (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_residu" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div> --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">NGT (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_ngt" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Drain (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_drain" class="mt-1 w-full rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="cc">
            </div>
        </div>
    </div>
</div>
