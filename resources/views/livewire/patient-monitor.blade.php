<div>
    <x-slot name="header">
        <livewire:patient-header :no-rawat="$no_rawat" />
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 px-4">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm-4 4a4 4 0 00-4 4v5h16v-5a4 4 0 00-4-4H8z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 leading-tight">
                            Monitoring 24 Jam <span class="bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent">NEONATUS INTENSIF CARE UNIT (NICU)</span>
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pemantauan kondisi pasien secara real-time</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-end gap-2">
                    <a href="{{ route('patient.history' ,['no_rawat' => str_replace('/', '_', $no_rawat) ]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-200 shadow transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>

                    @if($currentCycleId)
                    <a href="{{ route('monitoring.report.pdf', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $currentCycleId]) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 border
                              border-primary-600 dark:border-primary-400
                              text-primary-600 dark:text-primary-400
                              rounded-md hover:bg-primary-600 dark:hover:bg-primary-400
                              hover:text-white dark:hover:text-gray-900
                              shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10V6a4 4 0 014-4h2a4 4 0 014 4v4"></path>
                        </svg>
                        Cetak
                    </a>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto py-3 -mx-3 scroll-smooth scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800">
                <div class="flex gap-3 px-3 min-w-max snap-x snap-mandatory">

                    @if ($currentCycleId)
                    @include('livewire.patient-monitor.partials.modal-kejadian-cepat')
                    @include('livewire.patient-monitor.partials.modal-gasdarah')
                    <livewire:therapy-program-modal :currentCycleId="$currentCycleId" :no-rawat="$no_rawat" wire:key="'therapy-modal-'.$currentCycleId" />
                    @endif
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <form wire:submit="saveRecord" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mt-4">
                            <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3">Form Input Observasi</h3>
                            <div x-data="{
        currentTime: new Date(@json(now()->timestamp * 1000))
    }" x-init="setInterval(() => currentTime = new Date(currentTime.getTime() + 1000), 1000)" class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal & Jam Observasi</label>
                                <div class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 shadow-sm px-3 py-2 sm:text-sm text-gray-700 dark:text-gray-300">
                                    <span x-text="currentTime.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                                    <span> - </span>
                                    <span x-text="currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                                </div>
                            </div>

                        </div>
                        <div class="border-b border-gray-200 dark:border-gray-700 mt-4">
                            <nav class="bg-gray-50 dark:bg-gray-900 shadow-sm -mb-px flex space-x-2 sm:space-x-4 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-800 px-2 py-1" aria-label="Tabs">
                                <button wire:click.prevent="$set('activeTab', 'observasi')" type="button" class="{{ $activeTab === 'observasi' ? 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                    Observasi
                                </button>
                                <button wire:click.prevent="$set('activeTab', 'ventilator')" type="button" class="{{ $activeTab === 'ventilator' ? 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                    Ventilator
                                </button>
                                <button wire:click.prevent="$set('activeTab', 'cairan')" type="button" class="{{ $activeTab === 'cairan' ? 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                    Cairan
                                </button>
                            </nav>
                        </div>
                        <div class="space-y-4 mt-4">
                            <div x-show="$wire.activeTab === 'observasi'" class="space-y-4">
                                @include('livewire.patient-monitor.partials.tab-input-observasi')
                            </div>
                            <div x-show="$wire.activeTab === 'ventilator'" class="space-y-4">
                                @include('livewire.patient-monitor.partials.tab-input-ventilator')
                            </div>
                            <div x-show="$wire.activeTab === 'cairan'" class="space-y-4">
                                @include('livewire.patient-monitor.partials.tab-input-cairan')
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 space-y-3 border-t dark:border-gray-600">
                        <div class="text-right">
                            <button type{{"="}} "submit" wire:loading.attr="disabled" @click="$dispatch('sync-repeaters')" @disabled($isReadOnly) class="inline-flex justify-center rounded-md border border-transparent
                       bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm
                       hover:bg-primary-700
                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                       dark:focus:ring-offset-gray-800
                       disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="saveRecord">Simpan Catatan</span>
                                <span wire:loading wire:target="saveRecord">Menyimpan...</span>
                            </button>
                            @error('record')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                            @error('record_time')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="lg:col-span-2" wire:init="loadData">
                <div wire:loading wire:target="loadData" class="w-full">
                    <div class="bg-white dark:bg-gray-800 p-8 shadow-sm text-center">
                        <span class="text-gray-500 dark:text-gray-400 font-medium text-lg">
                            Memuat data riwayat...
                        </span>
                    </div>
                </div>
                <div wire:loading.remove wire:target="loadData">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-t-lg">
                        <div class="px-4 pt-4 sm:px-6">
                            <div class="border-b border-gray-200 dark:border-gray-700">
                                <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs Output">
                                    <button wire:click.prevent="$set('activeOutputTab', 'ringkasan')" type="button" class="{{ $activeOutputTab === 'ringkasan' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ringkasan & Grafik</button>
                                    <button wire:click.prevent="$set('activeOutputTab', 'observasi')" type="button" class="{{ $activeOutputTab === 'observasi' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Observasi</button>
                                    <button wire:click.prevent="$set('activeOutputTab', 'obat_cairan')" type="button" class="{{ $activeOutputTab === 'obat_cairan' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Obat & Cairan</button>
                                    <button wire:click.prevent="$set('activeOutputTab', 'penilaian_lab')" type="button" class="{{ $activeOutputTab === 'penilaian_lab' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Penilaian & Lab</button>
                                    <button wire:click.prevent="$set('activeOutputTab', 'vantilator')" type="button" class="{{ $activeOutputTab === 'vantilator' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ventilator</button>
                                    <button wire:click.prevent="$set('activeOutputTab', 'alat')" type="button" class="{{ $activeOutputTab === 'alat' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Alat Terpasang</button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div x-show="$wire.activeOutputTab === 'ringkasan'" class="space-y-6">
                        <livewire:nicu.hemodynamic-chart-nicu :no_rawat="$no_rawat" :selectedDate="$selectedDate" wire:key="'chart-hemo-'.$currentCycleId" lazy />
                        <livewire:nicu.fluid-balance :no_rawat="$no_rawat" :selectedDate="$selectedDate" :isReadOnly="$isReadOnly" wire:key="'fluid-balance-'.$currentCycleId" lazy />
                    </div>
                    <div x-show="$wire.activeOutputTab === 'observasi'" class="space-y-6">
                        <livewire:observasi-table :cycleId="$currentCycleId" wire:key="'static-observasi-table'" lazy />
                    </div>
                    <div x-show="$wire.activeOutputTab === 'obat_cairan'" class="space-y-6">
                        <livewire:cairan-table :cycleId="$currentCycleId" wire:key="'static-cairan-table'" lazy />
                        <livewire:obat-table :cycleId="$currentCycleId" wire:key="'static-obat-table'" lazy />
                    </div>
                    <div x-show="$wire.activeOutputTab === 'penilaian_lab'" class="space-y-6">
                        <livewire:gas-darah-table :cycleId="$currentCycleId" wire:key="'static-gas-table'" lazy />
                        <livewire:pipp-table :cycleId="$currentCycleId" wire:key="'static-pipp-table'" lazy />
                    </div>
                    <div x-show="$wire.activeOutputTab === 'vantilator'" class="space-y-6">
                        <livewire:ventilator-table :cycleId="$currentCycleId" wire:key="'static-vent-table'" lazy />
                    </div>
                    <div x-show="$wire.activeOutputTab === 'alat'" class="space-y-6">
                        <livewire:nicu.device-list :no_rawat="$no_rawat" :selectedDate="$selectedDate" wire:key="'device-list-'.$currentCycleId" lazy />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
