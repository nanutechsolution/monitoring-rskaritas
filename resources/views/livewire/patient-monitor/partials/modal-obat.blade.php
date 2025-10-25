@if ($showMedicationModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeMedicationModal"></div>
    <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
        <h3 class="text-lg font-medium text-gray-900">Tambah Pemberian Obat</h3>
        <div class="mt-4 space-y-4 border-t pt-4">
            <div>
                <label class="block text-sm font-medium">Waktu Pemberian</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ \Carbon\Carbon::parse($given_at)->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <label for="medication_name" class="block text-sm font-medium">Nama Obat</label>
                {{-- Gunakan .defer agar lebih optimal --}}
                <input id="medication_name" type="text" wire:model.defer="medication_name" list="recent-meds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik atau pilih dari riwayat...">
                <datalist id="recent-meds">
                    @foreach($recentMedicationNames as $name)
                    <option value="{{ $name }}">
                        @endforeach
                </datalist>
            </div>
            <div class="grid grid-cols-2 gap-4">
                {{-- Gunakan .defer agar lebih optimal --}}
                <div><label for="dose" class="block text-sm font-medium">Dosis</label><input id="dose" type="text" wire:model.defer="dose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 3x80mg"></div>
                <div><label for="route" class="block text-sm font-medium">Rute</label><input id="route" type="text" wire:model.defer="route" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: IV"></div>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" wire:click="closeMedicationModal" class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>

            {{-- TOMBOL SIMPAN DENGAN LOADING STATE --}}
            <button type="button" wire:click="saveMedication" wire:loading.attr="disabled" {{-- Disable tombol saat loading --}} wire:loading.class="opacity-75 cursor-wait" {{-- Ubah tampilan saat loading --}} class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 transition ease-in-out duration-150">

                {{-- Ikon Spinner (muncul saat loading) --}}
                <svg wire:loading wire:target="saveMedication" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                {{-- Teks Tombol (berganti saat loading) --}}
                <span wire:loading.remove wire:target="saveMedication">Simpan Obat</span>
                <span wire:loading wire:target="saveMedication">Menyimpan...</span>
            </button>
        </div>
    </div>
</div>
@endif
