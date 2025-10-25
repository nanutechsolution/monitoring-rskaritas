<div class="space-y-4">
    <div class="space-y-3 p-4 bg-gray-50 rounded-lg border">
        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Intake (Cairan Masuk)</h5>
        <div x-data="{
    intakes: @entangle('parenteral_intakes').defer,
    addRow() {
        if (!Array.isArray(this.intakes)) {
            this.intakes = [];
        }
        this.intakes.push({ name: '', volume: null });
    },
    removeRow(index) {
        if (!Array.isArray(this.intakes)) {
            this.intakes = [];
            return;
        }
        this.intakes.splice(index, 1);
    }
}" @sync-repeaters.window="$wire.set('parenteral_intakes', intakes, false)"
@repeaters-ready.window="intakes = $wire.parenteral_intakes || []">
            <label class="block text-sm font-medium text-gray-700">Parenteral (Infus)</label>
            <div class="space-y-2">
                {{-- Gunakan <template> untuk perulangan Alpine --}}
                <template x-for="(intake, index) in intakes" :key="index">
                    <div class="flex items-center gap-2">
                        {{-- Gunakan x-model (bukan wire:model) --}}
                        <input type="text" x-model="intake.name" placeholder="Nama Cairan" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="number" step="0.1" x-model="intake.volume" placeholder="Volume (cc)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        {{-- Gunakan @click (bukan wire:click) --}}
                        <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 text-lg leading-none font-bold" title="Hapus">
                            &times;
                        </button>
                    </div>
                </template>
            </div>
            {{-- Gunakan @click (bukan wire:click) --}}
            <button type="button" @click="addRow" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                + Tambah Infus
            </button>
        </div>


        {{-- 2. BAGIAN ENTERAL (OGT/ORAL) --}}
        <div x-data="{
    intakes: @entangle('enteral_intakes').defer,

    addRow() {
        // Jika 'intakes' bukan array (misal: null), jadikan array kosong dulu
        if (!Array.isArray(this.intakes)) {
            this.intakes = [];
        }
        this.intakes.push({ name: '', volume: null });
    },

    removeRow(index) {
        if (!Array.isArray(this.intakes)) {
            this.intakes = [];
            return;
        }
        this.intakes.splice(index, 1);
    }
}"@sync-repeaters.window="$wire.set('enteral_intakes', intakes, false)"
@repeaters-ready.window="intakes = $wire.enteral_intakes || []"
>
            <label class="block text-sm font-medium text-gray-700 mt-3">Enteral (OGT/Oral)</label>
            <div class="space-y-2">
                <template x-for="(intake, index) in intakes" :key="index">
                    <div class="flex items-center gap-2">
                        {{-- Gunakan x-model (bukan wire:model) --}}
                        <input type="text" x-model="intake.name" placeholder="Jenis (ASI / Susu Formula / Puasa)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">

                        {{-- Gunakan x-show (bukan @if) untuk logika "puasa" --}}
                        <input type="number" step="0.1" x-model="intake.volume" placeholder="Volume (ml)" class="w-1/2 form-input text-sm rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" x-show="intake.name.toLowerCase() !== 'puasa'">

                        <input type="text" disabled value="-" class="w-1/2 bg-gray-100 text-center rounded-md border-gray-300 text-sm" x-show="intake.name.toLowerCase() === 'puasa'">

                        {{-- Gunakan @click (bukan wire:click) --}}
                        <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 text-lg leading-none font-bold" title="Hapus">
                            &times;
                        </button>
                    </div>
                </template>
            </div>
            {{-- Gunakan @click (bukan wire:click) --}}
            <button type="button" @click="addRow" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                + Tambah Enteral
            </button>
        </div>

        <hr class="my-3 border-gray-200">

        {{-- Input OGT & Oral Anda sudah benar menggunakan wire:model.defer --}}
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
