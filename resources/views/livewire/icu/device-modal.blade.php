<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
     @keydown.escape.window="$dispatch('close-modal')">

    {{-- Kontainer Modal --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg"
         @click.outside="$dispatch('close-modal')">

        {{-- Header Modal --}}
        <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tambah Alat / Tube Baru</h3>
            <button wire:click="$dispatch('close-modal')" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">&times;</button>
        </div>

        <form wire:submit.prevent="saveDevice">
            <div class="p-6 space-y-4">
                {{-- Kategori --}}
                <div>
                    <label for="device_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                    <select wire:model.live="device_category" id="device_category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="ALAT">ALAT (Invasif)</option>
                        <option value="TUBE">TUBE</option>
                    </select>
                    @error('device_category') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Nama Alat / Tube --}}
                @if($device_category)
                <div>
                    <label for="device_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Alat / Tube</label>
                    <select wire:model.live="device_name" id="device_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Nama --</option>
                        @foreach($filteredDeviceNames as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('device_name') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Nama Lainnya --}}
                @if($isOther)
                <div>
                    <label for="other_device_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lainnya</label>
                    <input type="text" wire:model.defer="otherDeviceName" id="other_device_name"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100" placeholder="Masukkan nama alat/tube...">
                    @error('otherDeviceName') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Input Ukuran, Lokasi, Tanggal (Grid Responsive) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Ukuran --}}
                    <div>
                        <label for="ukuran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ukuran</label>
                        <input type="text" wire:model.defer="ukuran" id="ukuran"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error('ukuran') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Lokasi (Responsif Span 2 jika bukan ALAT) --}}
                    <div class="{{ $device_category === 'ALAT' ? 'sm:col-span-1' : 'sm:col-span-2' }}">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                        <input type="text" wire:model.defer="lokasi" id="lokasi"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm disabled:bg-gray-100 dark:disabled:bg-gray-800 dark:disabled:border-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                               {{ $device_category !== 'ALAT' ? 'disabled' : '' }}>
                        @error('lokasi') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Tanggal Pasang --}}
                    <div>
                        <label for="tanggal_pasang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tgl Pasang</label>
                        <input type="date" wire:model.defer="tanggal_pasang" id="tanggal_pasang"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        @error('tanggal_pasang') <span class="text-danger-600 dark:text-danger-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-b-lg flex justify-end space-x-3">
                <button type="button"
                             wire:click="$dispatch('close-modal')"
                             class="bg-white dark:bg-gray-600 px-4 py-2 rounded-md shadow text-sm font-medium text-gray-700 dark:text-gray-200 border dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500">
                    Batal
                </button>
                <button type="submit"
                             class="bg-primary-600 text-white px-4 py-2 rounded-md shadow text-sm font-medium hover:bg-primary-700">
                    Simpan Alat/Tube
                </button>
            </div>
        </form>

    </div>
</div>
