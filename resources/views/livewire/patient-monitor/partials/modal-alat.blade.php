@if ($showDeviceModal)
<div x-data="{ show: @entangle('showDeviceModal') }" x-show="show" x-on:keydown.escape.window="show = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;" {{-- Mencegah FOUC (flash of unstyled content) --}}>
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/75" wire:click="closeDeviceModal" {{-- Tutup modal jika klik di luar --}}></div>
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-lg shadow-xl w-full max-w-lg">
        <form wire:submit="saveDevice">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    {{ $editingDeviceId ? 'Edit Alat Terpasang' : 'Tambah Alat Baru' }}
                </h3>
                <button type="button" wire:click="closeDeviceModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="installation_date" class="block mb-2 text-sm font-medium text-gray-900">Tgl. Pasang</label>
                    <input id="installation_date" type="datetime-local" wire:model="installation_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5 {{ !$editingDeviceId ? 'bg-gray-200 cursor-not-allowed' : '' }}" {{ !$editingDeviceId ? 'readonly' : '' }}>
                    @error('installation_date') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="device_name" class="block mb-2 text-sm font-medium text-gray-900">Nama Alat</label>
                    <input id="device_name" type="text" wire:model="device_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: CVC, ETT, Kateter Urin">
                    @error('device_name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="size" class="block mb-2 text-sm font-medium text-gray-900">Ukuran</label>
                        <input id="size" type="text" wire:model="size" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: 7 Fr">
                        @error('size') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="location" class="block mb-2 text-sm font-medium text-gray-900">Lokasi</label>
                        <input id="location" type="text" wire:model="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: V. Subklavia Ka">
                        @error('location') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end p-6 space-x-3 border-t border-gray-200 rounded-b">
                <button type="button" wire:click="closeDeviceModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300">
                    <span wire:loading.remove wire:target="saveDevice">
                        {{ $editingDeviceId ? 'Update Alat' : 'Simpan Alat' }}
                    </span>
                    <span wire:loading wire:target="saveDevice">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
