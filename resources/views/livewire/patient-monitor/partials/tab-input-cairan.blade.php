@php
    // --- Kelas Helper untuk Konsistensi ---

    // Label Standar
    $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';

    // Input Form Biasa
    $inputClasses = 'mt-1 block w-full rounded-md shadow-sm sm:text-sm
                     border-gray-300 dark:border-gray-600
                     bg-white dark:bg-gray-700
                     text-gray-900 dark:text-gray-200
                     focus:border-primary-500 focus:ring-primary-500';

    // Kartu Background (untuk Intake & Output)
    $cardClasses = 'space-y-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';

    // Judul Kartu
    $cardTitleClasses = 'text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide';

    // Teks Error
    $errorClasses = 'text-xs text-danger-600 dark:text-danger-400 mt-1';
@endphp

<div class="space-y-4">
    <div class="{{ $cardClasses }}">
        <h5 class="{{ $cardTitleClasses }}">Intake (Cairan Masuk)</h5>
        <div x-data="{
                intakes: @entangle('parenteral_intakes').defer,
                addRow() {
                    if (!Array.isArray(this.intakes)) { this.intakes = []; }
                    this.intakes.push({ name: '', volume: null });
                },
                removeRow(index) {
                    if (!Array.isArray(this.intakes)) { this.intakes = []; return; }
                    this.intakes.splice(index, 1);
                }
             }"
             @sync-repeaters.window="$wire.set('parenteral_intakes', intakes, false)"
             @repeaters-ready.window="intakes = $wire.parenteral_intakes || []">

            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parenteral (Infus)</label>
            <div class="space-y-2">
                <template x-for="(intake, index) in intakes" :key="index">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="intake.name" placeholder="Nama Cairan" class="w-1/2 {{ $inputClasses }} text-sm">
                        <input type="number" step="0.1" x-model="intake.volume" placeholder="Volume (cc)" class="w-1/2 {{ $inputClasses }} text-sm">
                        <button type="button" @click="removeRow(index)" class="text-danger-500 hover:text-danger-700 dark:text-danger-400 dark:hover:text-danger-300 text-lg leading-none font-bold" title="Hapus">
                            &times;
                        </button>
                    </div>
                </template>
            </div>
            <button type="button" @click="addRow" class="mt-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
                + Tambah Infus
            </button>
        </div>

        <div x-data="{
                intakes: @entangle('enteral_intakes').defer,
                addRow() {
                    if (!Array.isArray(this.intakes)) { this.intakes = []; }
                    this.intakes.push({ name: '', volume: null });
                },
                removeRow(index) {
                    if (!Array.isArray(this.intakes)) { this.intakes = []; return; }
                    this.intakes.splice(index, 1);
                }
             }"
             @sync-repeaters.window="$wire.set('enteral_intakes', intakes, false)"
             @repeaters-ready.window="intakes = $wire.enteral_intakes || []"
        >
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3">Enteral (OGT/Oral)</label>
            <div class="space-y-2">
                <template x-for="(intake, index) in intakes" :key="index">
                    <div class="flex items-center gap-2">
                        <input type="text" x-model="intake.name" placeholder="Jenis (ASI / Susu / Puasa)" class="w-1/2 {{ $inputClasses }} text-sm">

                        <input type="number" step="0.1" x-model="intake.volume" placeholder="Volume (ml)"
                               class="w-1/2 {{ $inputClasses }} text-sm"
                               x-show="!intake.name || intake.name.toLowerCase() !== 'puasa'">

                        <input type="text" disabled value="-"
                               class="w-1/2 bg-gray-100 dark:bg-gray-600 text-center rounded-md border-gray-300 dark:border-gray-500 text-sm"
                               x-show="intake.name && intake.name.toLowerCase() === 'puasa'">

                        <button type="button" @click="removeRow(index)" class="text-danger-500 hover:text-danger-700 dark:text-danger-400 dark:hover:text-danger-300 text-lg leading-none font-bold" title="Hapus">
                            &times;
                        </button>
                    </div>
                </template>
            </div>
            <button type="button" @click="addRow" class="mt-2 text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
                + Tambah Enteral
            </button>
        </div>

        <hr class="my-3 border-gray-200 dark:border-gray-600">

        <div class="grid grid-cols-2 gap-4 pt-2">
            <div>
                <label class="{{ $labelClasses }}">OGT (cc)</label>
                <input type="number" step="0.1" wire:model.defer="intake_ogt" class="{{ $inputClasses }}" placeholder="cc">
            </div>
            <div>
                <label class="{{ $labelClasses }}">Oral (cc)</label>
                <input type="number" step="0.1" wire:model.defer="intake_oral" class="{{ $inputClasses }}" placeholder="cc">
            </div>
        </div>
    </div>

    <div class="{{ $cardClasses }}">
        <h5 class="{{ $cardTitleClasses }}">Output (Cairan Keluar)</h5>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="{{ $labelClasses }}">Urine (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_urine" class="{{ $inputClasses }}" placeholder="cc">
            </div>
            <div>
                <label class="{{ $labelClasses }}">BAB (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_bab" class="{{ $inputClasses }}" placeholder="cc">
            </div>
            <div>
                <label class="{{ $labelClasses }}">NGT (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_ngt" class="{{ $inputClasses }}" placeholder="cc">
            </div>
            <div>
                <label class="{{ $labelClasses }}">Drain (cc)</label>
                <input type="number" step="0.1" wire:model.defer="output_drain" class="{{ $inputClasses }}" placeholder="cc">
            </div>
        </div>
    </div>
</div>
