<div x-data="{ openEventModal: false }">
    <button type"button" @click="openEventModal = true"
            class="flex items-center gap-2 px-5 py-2
                   bg-white dark:bg-gray-800
                   border dark:border-gray-700 rounded-lg shadow
                   hover:shadow-md hover:bg-primary-50 dark:hover:bg-gray-700
                   flex-shrink-0 snap-start transition-all">
        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span class="font-medium text-gray-800 dark:text-gray-100">Catat Kejadian</span>
    </button>

    <div x-show="openEventModal" x-cloak x-transition.opacity.duration.300ms
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-sm" @click="openEventModal = false"></div>

        <div x-show="openEventModal" x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             @click.outside="openEventModal = false"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Catat Kejadian</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pilih semua kejadian yang terjadi pada waktu yang sama.
            </p>

            <div class="mt-4 space-y-2 border-t dark:border-gray-700 pt-4">
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">

                    @php
                        // Helper class untuk checkbox
                        $checkboxClasses = 'rounded border-gray-300 dark:border-gray-600
                                            text-primary-600 dark:text-primary-500
                                            focus:ring-primary-500 dark:focus:ring-primary-600
                                            dark:bg-gray-700 dark:checked:bg-primary-500';
                        $labelClasses = 'flex items-center space-x-2 text-gray-700 dark:text-gray-300';
                    @endphp

                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_cyanosis" class="{{ $checkboxClasses }}">
                        <span>Cyanosis</span>
                    </label>
                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_pucat" class="{{ $checkboxClasses }}">
                        <span>Pucat</span>
                    </label>
                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_ikterus" class="{{ $checkboxClasses }}">
                        <span>Ikterus</span>
                    </label>
                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_crt_less_than_2" class="{{ $checkboxClasses }}">
                        <span>CRT &lt; 2 detik</span>
                    </label>
                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_bradikardia" class="{{ $checkboxClasses }}">
                        <span>Bradikardia</span>
                    </label>
                    <label class="{{ $labelClasses }}">
                        <input type="checkbox" wire:model.defer="event_stimulasi" class="{{ $checkboxClasses }}">
                        <span>Stimulasi</span>
                    </label>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="openEventModal = false"
                        class="px-4 py-2 text-sm font-medium
                               text-gray-700 dark:text-gray-300
                               bg-white dark:bg-gray-700
                               border border-gray-300 dark:border-gray-600
                               rounded-md hover:bg-gray-50 dark:hover:bg-gray-600
                               transition shadow-sm">
                    Batal
                </button>

                <button type="button" wire:click="saveEvent" @click="openEventModal = false"
                        wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white
                               bg-primary-600 border border-transparent rounded-md
                               hover:bg-primary-700 transition ease-in-out duration-150
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">

                    <svg wire:loading wire:target="saveEvent" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"></path>
                    </svg>

                    <span wire:loading.remove wire:target="saveEvent">
                        Simpan Kejadian
                    </span>
                    <span wire:loading wire:target="saveEvent">
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
