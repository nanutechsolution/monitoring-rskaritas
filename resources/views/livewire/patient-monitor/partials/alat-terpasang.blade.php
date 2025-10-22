<div class="p-6 text-gray-900">
    <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h3 class="text-lg font-medium">Alat Terpasang</h3>
        <button type-="button" wire:click="openDeviceModal()" class="text-sm bg-teal-600 text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
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
                        @if($device->size)
                        <span class="font-medium">Size:</span> {{ $device->size }}
                        @endif
                        @if($device->location)
                        <span class="ml-2 font-medium">Lokasi:</span> {{ $device->location }}
                        @endif
                    </p>

                    <div class="text-xs mt-2 space-y-1">

                        <div>
                            <span class="font-medium bg-green-100 text-green-800 px-2 py-0.5 rounded-full">
                                Dipasang: {{ $device->installation_date->format('d M Y, H:i') }}
                            </span>

                            <span class="text-gray-600 ml-1">
                                (oleh: {{ $device->installer_name }})
                            </span>
                        </div>

                        @if($device->removal_date)
                        <div>
                            <span class="font-medium bg-red-100 text-red-800 px-2 py-0.5 rounded-full">
                                Dilepas: {{ $device->removal_date->format('d M Y, H:i') }}
                            </span>

                            <span class="text-gray-600 ml-1">
                                (oleh: {{ $device->remover_name }})
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex-shrink-0 ml-4 flex space-x-2">
                    {{-- <button type="button" wire:click="openDeviceModal({{ $device->id }})" class="text-sm text-blue-600 hover:text-blue-800">Edit</button> --}}

                    @if (is_null($device->removal_date))
                    <button type="button" wire:click="openRemoveModal({{ $device->id }})" class="text-sm text-red-600 hover:text-red-800">
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

        @if ($showRemoveDeviceModal)
        <div x-data="{ show: @entangle('showRemoveDeviceModal') }" x-show="show" x-on:keydown.escape.window="show = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div x-show="show" x-transition.opacity class="absolute inset-0 bg-gray-900/75" wire:click="closeRemoveModal"></div>

            <div x-show="show" x-transition class="relative bg-white rounded-lg shadow-xl w-full max-w-md">

                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Konfirmasi Lepas Alat
                    </h3>
                    <button type="button" wire:click="closeRemoveModal" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-medium text-gray-900">Anda yakin ingin melepas alat ini?</p>

                            @if ($deviceToRemoveDetails)
                            <div class="mt-2 border-l-4 border-gray-200 pl-3 text-sm text-gray-700 space-y-1">
                                <p><strong>{{ $deviceToRemoveDetails->device_name }}</strong></p>
                                <p>
                                    @if($deviceToRemoveDetails->size) Size: {{ $deviceToRemoveDetails->size }} | @endif
                                    @if($deviceToRemoveDetails->location) Lokasi: {{ $deviceToRemoveDetails->location }} @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    Dipasang: {{ $deviceToRemoveDetails->installation_date->format('d M Y, H:i') }}
                                </p>
                            </div>
                            @endif

                            <p class="mt-3 text-sm text-gray-600">
                                Tindakan ini akan mencatat waktu lepas alat pada <strong>waktu sekarang</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end p-6 space-x-3 border-t rounded-b">
                    <button type="button" wire:click="closeRemoveModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="button" wire:click="confirmRemoveDevice" {{-- Panggil fungsi konfirmasi --}} class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                        <span wire:loading.remove wire:target="confirmRemoveDevice">
                            Ya, Lepas Sekarang
                        </span>
                        <span wire:loading wire:target="confirmRemoveDevice">
                            Memproses...
                        </span>
                    </button>
                </div>

            </div>
        </div>
        @endif
    </div>
</div>
