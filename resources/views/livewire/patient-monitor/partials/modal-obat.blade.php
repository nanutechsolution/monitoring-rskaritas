  @if ($showMedicationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeMedicationModal"></div>
        <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h3 class="text-lg font-medium text-gray-900">Tambah Pemberian Obat</h3>
            <div class="mt-4 space-y-4 border-t pt-4">
                <div><label class="block text-sm font-medium">Waktu Pemberian</label>
                    <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ \Carbon\Carbon::parse($given_at)->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <label for="medication_name" class="block text-sm font-medium">Nama Obat</label>
                    <input id="medication_name" type="text" wire:model="medication_name" list="recent-meds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik atau pilih dari riwayat...">
                    <datalist id="recent-meds">
                        @foreach($recentMedicationNames as $name)
                        <option value="{{ $name }}">
                            @endforeach
                    </datalist>
                </div>
                {{-- <div><label for="medication_name" class="block text-sm font-medium">Nama Obat</label><input id="medication_name" type="text" wire:model="medication_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Aminofilin"></div> --}}
                <div class="grid grid-cols-2 gap-4">
                    <div><label for="dose" class="block text-sm font-medium">Dosis</label><input id="dose" type="text" wire:model="dose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 3x80mg"></div>
                    <div><label for="route" class="block text-sm font-medium">Rute</label><input id="route" type="text" wire:model="route" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: IV"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3"><button type="button" wire:click="closeMedicationModal" class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button><button type="button" wire:click="saveMedication" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">Simpan Obat</button></div>
        </div>
    </div>
    @endif
