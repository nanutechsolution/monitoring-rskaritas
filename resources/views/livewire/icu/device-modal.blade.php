{{-- Ini adalah komponen modal --}}
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
     @keydown.escape.window="$dispatch('close-modal')"> {{-- Tutup pakai event --}}

    {{-- Kontainer Modal --}}
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg"
         @click.outside="$dispatch('close-modal')"> {{-- Tutup pakai event --}}

        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Alat / Tube Baru</h3>
            <button wire:click="$dispatch('close-modal')" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <form wire:submit.prevent="saveDevice">
            <div class="p-6 space-y-4">
                {{-- Pilih Kategori --}}
                <div>
                    <label for="device_category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select wire:model.live="device_category" id="device_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="ALAT">ALAT (Invasif)</option>
                        <option value="TUBE">TUBE</option>
                    </select>
                    @error('device_category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Pilih Nama Alat/Tube --}}
                @if($device_category)
                <div>
                    <label for="device_name" class="block text-sm font-medium text-gray-700">Nama Alat / Tube</label>
                    <select wire:model.live="device_name" id="device_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">-- Pilih Nama --</option>
                        @foreach($filteredDeviceNames as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('device_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Input Teks jika "Lainnya" dipilih --}}
                @if($isOther)
                <div>
                    <label for="other_device_name" class="block text-sm font-medium text-gray-700">Nama Lainnya</label>
                    <input type="text" wire:model.defer="otherDeviceName" id="other_device_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" placeholder="Masukkan nama alat/tube...">
                    @error('otherDeviceName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endif

                {{-- Input Ukuran, Lokasi, Tanggal --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran</label>
                        <input type="text" wire:model.defer="ukuran" id="ukuran" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        @error('ukuran') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                     {{-- Input Lokasi (hanya untuk ALAT) --}}
                    <div class="{{ $device_category === 'ALAT' ? 'md:col-span-1' : 'md:col-span-2' }}">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" wire:model.defer="lokasi" id="lokasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm disabled:bg-gray-100"
                               {{ $device_category !== 'ALAT' ? 'disabled' : '' }}>
                        @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                     <div>
                        <label for="tanggal_pasang" class="block text-sm font-medium text-gray-700">Tgl Pasang</label>
                        <input type="date" wire:model.defer="tanggal_pasang" id="tanggal_pasang" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        @error('tanggal_pasang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                 <button type="button"
                         wire:click="$dispatch('close-modal')"
                         class="bg-white px-4 py-2 rounded-md shadow text-sm font-medium text-gray-700 border hover:bg-gray-50">
                     Batal
                 </button>
                 <button type="submit"
                         class="bg-blue-600 text-white px-4 py-2 rounded-md shadow text-sm font-medium hover:bg-blue-700">
                     Simpan Alat/Tube
                 </button>
            </div>
        </form>

    </div>
</div>
