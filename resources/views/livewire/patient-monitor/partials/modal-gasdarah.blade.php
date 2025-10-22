@if ($showBloodGasModal)
<div x-data="{ show: @entangle('showBloodGasModal') }" x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm">
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="show = false" class="relative w-full max-w-3xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]">

        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                🩸 Catat Hasil Gas Darah
            </h3>
            <p class="text-sm text-gray-500 mt-1">Masukkan data analisis gas darah (AGD) pasien.</p>
        </div>

        <div class="px-6 py-5 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-5 gap-y-6">

                <div>
                    <label for="form_taken_at" class="block text-sm font-medium text-gray-700">Waktu Pengambilan</label>
                    <input id="form_taken_at" type="datetime-local" wire:model="form.taken_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    @error('form.taken_at') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    <p class="mt-1 text-xs text-gray-500">Disimpan dalam WITA (24 jam).</p>
                </div>

                @php
                $bloodGasFields = [
                ['id' => 'gula_darah', 'label' => 'Gula Darah (BS)', 'step' => '0.1'],
                ['id' => 'ph', 'label' => 'pH', 'step' => '0.01'],
                ['id' => 'pco2', 'label' => 'PCO₂', 'step' => '0.1'],
                ['id' => 'po2', 'label' => 'PO₂', 'step' => '0.1'],
                ['id' => 'hco3', 'label' => 'HCO₃', 'step' => '0.1'],
                ['id' => 'be', 'label' => 'BE', 'step' => '0.1'],
                ['id' => 'sao2', 'label' => 'SaO₂', 'step' => '0.1'],
                ];
                @endphp

                @foreach ($bloodGasFields as $field)
                <div>
                    <label for="form_{{ $field['id'] }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                    <input id="form_{{ $field['id'] }}" type="number" step="{{ $field['step'] }}" wire:model="{{ $field['id'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    @error($field['id']) <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
                @endforeach

            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <button type="button" wire:click="closeBloodGasModal" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition shadow-sm">
                Batal
            </button>
            <button type="button" wire:click="saveBloodGasResult" class="px-5 py-2 text-sm font-semibold rounded-lg bg-teal-600 text-white hover:bg-teal-700 active:scale-[0.98] transition transform shadow-sm">
                <span wire:loading.remove wire:target="saveBloodGasResult">
                    💾 Simpan Hasil
                </span>
                <span wire:loading wire:target="saveBloodGasResult">
                    Menyimpan...
                </span>
            </button>
        </div>

    </div>
</div>
@endif
