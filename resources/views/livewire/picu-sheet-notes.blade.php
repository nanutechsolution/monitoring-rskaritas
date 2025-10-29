<div class="p-4 border rounded-md shadow-sm bg-white">

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-notes'))
        <div class="p-2 mb-3 text-xs text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success-notes') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Kolom Kiri --}}
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Masalah</label>
                <textarea wire:model.blur="masalah" wire:change="saveNotes" rows="5"
                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Program Terapi</label>
                <textarea wire:model.blur="program_terapi" wire:change="saveNotes" rows="5"
                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan Nutrisi (Enteral & Parenteral)</label>
                <textarea wire:model.blur="catatan_nutrisi" wire:change="saveNotes" rows="5"
                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Pemeriksaan Laboratorium (Catatan)</label>
                <textarea wire:model.blur="catatan_lab" wire:change="saveNotes" rows="5"
                          class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
        </div>
    </div>
</div>
