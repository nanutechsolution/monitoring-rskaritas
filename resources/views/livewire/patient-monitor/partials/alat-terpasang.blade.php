<div x-data="{
    showAddDeviceModal: false,
    deviceName: '',
    deviceSize: '',
    deviceLocation: '',
    installationDate: '',

    showRemoveModal: false,
    deviceToRemove: null,

    // Fungsi ini dipanggil tombol '+ Tambah Alat'
    newDeviceForm() {
        this.deviceName = '';
        this.deviceSize = '';
        this.deviceLocation = '';
        this.installationDate = '{{ now()->format('Y-m-d\TH:i') }}';
        this.showAddDeviceModal = true;
    },

    // Fungsi ini dipanggil tombol 'Lepas'
    removeDevice(device) {
        this.deviceToRemove = device;
        this.showRemoveModal = true;
    },

    // Fungsi untuk menutup & membersihkan modal lepas
    closeRemoveModal() {
        this.showRemoveModal = false;
        this.deviceToRemove = null;
    }
}">

    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h3 class="text-lg font-medium">Alat Terpasang</h3>
            <button type="button"
                    @click="newDeviceForm()"
                    class="text-sm bg-teal-600 text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                + Tambah Alat
            </button>
        </div>

        <div class="space-y-3">
            @forelse ($patientDevices as $device)
            <div class="border border-gray-200 p-3 rounded-lg shadow-sm" wire:key="device-{{ $device->id }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-semibold text-gray-800 truncate">{{ $device->device_name }}</p>
                        <p class="text-sm text-gray-600">
                            @if($device->size) <span class="font-medium">Size:</span> {{ $device->size }} @endif
                            @if($device->location) <span class="ml-2 font-medium">Lokasi:</span> {{ $device->location }} @endif
                        </p>
                        <div class="text-xs mt-2 space-y-1">
                            <div>
                                <span class="font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                    Dipasang: {{ $device->installation_date->format('d M Y, H:i') }}
                                </span>
                                <span class="text-gray-600 ml-1">(oleh: {{ $device->installer->nama ?? 'N/A' }})</span>
                            </div>
                            @if($device->removal_date)
                            <div>
                                <span class="font-medium bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                    Dilepas: {{ $device->removal_date->format('d M Y, H:i') }}
                                </span>
                                <span class="text-gray-600 ml-1">(oleh: {{ $device->remover->nama ?? 'N/A' }})</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-shrink-0 ml-4 flex space-x-2">
                        @if (is_null($device->removal_date))
                        <button type="button"
                            @click="removeDevice({
                                id: {{ $device->id }},
                                name: '{{ e($device->device_name) }}',
                                size: '{{ e($device->size ?? '') }}',
                                location: '{{ e($device->location ?? '') }}',
                                installed: '{{ $device->installation_date->format('d M Y, H:i') }}'
                            })"
                            class="text-sm text-red-600 hover:text-red-800">
                            Lepas
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center p-4 border border-dashed rounded-lg">
                <p class="text-sm text-gray-500">Belum ada data alat terpasang untuk siklus ini.</p>
            </div>
            @endforelse
        </div>
    </div>
    <div x-show="showAddDeviceModal"
         x-on:keydown.escape.window="showAddDeviceModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">

        <div x-show="showAddDeviceModal" x-transition.opacity class="absolute inset-0 bg-gray-900/75" @click="showAddDeviceModal = false"></div>

        <div x-show="showAddDeviceModal" x-transition class="relative bg-white rounded-lg shadow-xl w-full max-w-lg">

            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Alat Baru
                </h3>
                <button type="button" @click="showAddDeviceModal = false" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label for="installation_date_add" class="block mb-2 text-sm font-medium text-gray-900">Tgl. Pasang</label>
                    <input id="installation_date_add" type="datetime-local"
                        x-model="installationDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
                    @error('installation_date') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="device_name_add" class="block mb-2 text-sm font-medium text-gray-900">Nama Alat</label>
                    <input id="device_name_add" type="text" x-model="deviceName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: CVC, ETT, Kateter Urin">
                    @error('device_name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="size_add" class="block mb-2 text-sm font-medium text-gray-900">Ukuran</label>
                        <input id="size_add" type="text" x-model="deviceSize" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: 7 Fr">
                        @error('size') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="location_add" class="block mb-2 text-sm font-medium text-gray-900">Lokasi</label>
                        <input id="location_add" type="text" x-model="deviceLocation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5" placeholder="Contoh: V. Subklavia Ka">
                        @error('location') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end p-6 space-x-3 border-t border-gray-200 rounded-b">
                <button type="button" @click="showAddDeviceModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button
                    type="button"
                    wire:loading.attr="disabled"
                    wire:target="saveDevice"
                    @click="$wire.saveDevice({
                        device_name: deviceName,
                        size: deviceSize,
                        location: deviceLocation,
                        installation_date: installationDate
                    }).then((success) => {
                        if (success) {
                            showAddDeviceModal = false;
                        }
                    })"
                    class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300">
                    <span wire:loading.remove wire:target="saveDevice">
                        Simpan Alat
                    </span>
                    <span wire:loading wire:target="saveDevice">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    <div x-show="showRemoveModal" x-on:keydown.escape.window="closeRemoveModal()" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div x-show="showRemoveModal" x-transition.opacity class="absolute inset-0 bg-gray-900/75" @click="closeRemoveModal()"></div>
        <div x-show="showRemoveModal" x-transition class="relative bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Konfirmasi Lepas Alat</h3>
                <button type="button" @click="closeRemoveModal()" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-medium text-gray-900">Anda yakin ingin melepas alat ini?</p>
                        <div x-show="deviceToRemove" class="mt-2 border-l-4 border-gray-200 pl-3 text-sm text-gray-700 space-y-1" style="display: none;">
                            <p><strong x-text="deviceToRemove?.name"></strong></p>
                            <p>
                                <span x-show="deviceToRemove?.size">Size: <span x-text="deviceToRemove.size"></span> | </span>
                                <span x-show="deviceToRemove?.location">Lokasi: <span x-text="deviceToRemove.location"></span></span>
                            </S PAN>
                            <p class="text-xs text-gray-500">
                                Dipasang: <span x-text="deviceToRemove?.installed"></span>
                            </p>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">
                            Tindakan ini akan mencatat waktu lepas alat pada <strong>waktu sekarang</strong>.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end p-6 space-x-3 border-t rounded-b">
                <button type="button" @click="closeRemoveModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button
                    type="button"
                    @click="$wire.confirmRemoveDevice(deviceToRemove.id).then(() => {
                        closeRemoveModal();
                    })"
                    wire:loading.attr="disabled"
                    wire:target="confirmRemoveDevice"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                    <span wire:loading.remove wire:target="confirmRemoveDevice">Ya, Lepas Sekarang</span>
                    <span wire:loading wire:target="confirmRemoveDevice">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    </div>
