<div class="p-4 border rounded-md shadow-sm bg-white">
    <h3 class="text-lg font-medium mb-4">Alat Terpasang</h3>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success-device'))
        <div class="p-3 my-3 text-green-800 bg-green-100 border border-green-300 rounded-md">
            {{ session('success-device') }}
        </div>
    @endif

    {{-- 1. FORM INPUT --}}
    <form wire:submit="save" class="p-4 space-y-4 bg-gray-50 border rounded-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            {{-- Dropdown Nama Alat --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Alat</label>
                <select wire:model="nama_alat" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    <option value="">-- Pilih Alat --</option>
                    @foreach($alatOptions as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('nama_alat') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <x-form-input label="Ukuran" wire:model="ukuran" type="text" />
            <x-form-input label="Lokasi" wire:model="lokasi" type="text" />
            <x-form-input label="Tanggal Pemasangan" wire:model="tanggal_pemasangan" type="date" />
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                Simpan Alat
            </button>
        </div>
    </form>

    {{-- 2. TABEL LOG (RIWAYAT) --}}
    <div class="mt-6">
        <h4 class="font-semibold mb-2">Riwayat Alat Terpasang (Sheet Ini)</h4>
        <div class="overflow-y-auto border rounded-md max-h-60">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="px-3 py-2 text-left">Nama Alat</th>
                        <th class="px-3 py-2 text-left">Ukuran</th>
                        <th class="px-3 py-2 text-left">Lokasi</th>
                        <th class="px-3 py-2 text-left">Tgl. Pasang</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($this->devices as $device)
                        <tr>
                            <td class="px-3 py-2">{{ $device->nama_alat }}</td>
                            <td class="px-3 py-2">{{ $device->ukuran }}</td>
                            <td class="px-3 py-2">{{ $device->lokasi }}</td>
                            <td class="px-3 py-2">{{ $device->tanggal_pemasangan->format('d/m/Y') }}</td>
                            <td class="px-3 py-2 text-center">
                                <button wire:click="delete({{ $device->id }})"
                                        wire:confirm="Anda yakin ingin menghapus data alat ini?"
                                        class="text-red-600 hover:text-red-900 text-xs">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                                Belum ada data alat terpasang untuk sheet ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
