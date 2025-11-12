    <div>
        <x-slot name="header">
            <livewire:patient-header :no-rawat="$no_rawat" />
        </x-slot>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 px-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm-4 4a4 4 0 00-4 4v5h16v-5a4 4 0 00-4-4H8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                                Monitoring 24 Jam
                                <span class="bg-gradient-to-r from-primary-600 to-primary-700 bg-clip-text text-transparent">PEDIATRIC INTENSIVE CARE UNIT (PICU)</span>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau kondisi pasien secara real-time dengan mudah</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 justify-end">
                        <!-- Tombol Kembali -->
                        <a href="{{ route('patient.picu.history' ,['no_rawat' => str_replace('/', '_', $no_rawat) ]) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-200 shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Kembali
                        </a>

                        <!-- Tombol Cetak -->
                        @if($currentCycleId)
                        <a href="{{ route('monitoring.picu.report.pdf', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $currentCycleId]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-600 hover:text-white shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10V6a4 4 0 014-4h2a4 4 0 014 4v4"></path>
                            </svg>
                            Cetak
                        </a>
                        @endif
                    </div>

                </div>
                <!-- Scrollable Tombol Aksi (Modal) -->
                <div class="overflow-x-auto py-3 scroll-smooth scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                    <div class="flex gap-3 min-w-max snap-x snap-mandatory px-1">
                        @include('livewire.patient-monitor.partials.modal-kejadian-cepat')
                        @include('livewire.patient-monitor.partials.modal-obat')
                        @include('livewire.patient-monitor.partials.modal-gasdarah')
                        @if ($currentCycleId)
                        <livewire:therapy-program-modal-picu :currentCycleId="$currentCycleId" :no-rawat="$no_rawat" wire:key="'therapy-modal-'.$currentCycleId" />
                        @endif
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <form wire:submit="saveRecord" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3">Form Input Observasi</h3>
                            <div class="mt-4" x-data="{ currentTime: new Date() }" x-init="setInterval(() => currentTime = new Date(), 1000)">
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
                                    @php
                                    $activeTabClasses = 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400';
                                    $inactiveTabClasses = 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700';
                                    @endphp
                                    <button wire:click.prevent="$set('activeTab', 'observasi')" type="button" class="{{ $activeTab === 'observasi' ? $activeTabClasses : $inactiveTabClasses }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Observasi
                                    </button>
                                    <button wire:click.prevent="$set('activeTab', 'ventilator')" type="button" class="{{ $activeTab === 'ventilator' ? $activeTabClasses : $inactiveTabClasses }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Ventilator
                                    </button>
                                    <button wire:click.prevent="$set('activeTab', 'cairan')" type="button" class="{{ $activeTab === 'cairan' ? $activeTabClasses : $inactiveTabClasses }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Cairan
                                    </button>
                                </nav>
                            </div>
                            <div class="space-y-4 mt-4">
                                <div x-show="$wire.activeTab === 'observasi'" class="space-y-4">
                                    @include('livewire.patient-monitor.partials.tab-input-observasi-picu')
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
                                <button type="submit" wire:loading.attr="disabled" @click="$dispatch('sync-repeaters')" class="inline-flex justify-center rounded-md border border-transparent
                                           bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm
                                           hover:bg-primary-700
                                           focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                           dark:focus:ring-offset-gray-800">
                                    <span wire:loading.remove wire:target="saveRecord">Simpan Catatan</span>
                                    <span wire:loading wire:target="saveRecord">Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    @include('livewire.patient-monitor.partials.alat-terpasang')
                </div>
                <div class="lg:col-span-2" wire:init="loadData">
                    <div wire:loading wire:target="loadData" class="w-full">
                        <div class="bg-white dark:bg-gray-800 p-8 shadow-sm text-center rounded-lg">
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
                                        @php
                                        $activeOutputTabClasses = 'border-primary-500 text-primary-600 dark:text-primary-400';
                                        $inactiveOutputTabClasses = 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600';
                                        @endphp
                                        <button wire:click.prevent="$set('activeOutputTab', 'ringkasan')" type="button" class="{{ $activeOutputTab === 'ringkasan' ? $activeOutputTabClasses : $inactiveOutputTabClasses }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ringkasan & Grafik</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'observasi')" type="button" class="{{ $activeOutputTab === 'observasi' ? $activeOutputTabClasses : $inactiveOutputTabClasses }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Observasi</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'obat_cairan')" type="button" class="{{ $activeOutputTab === 'obat_cairan' ? $activeOutputTabClasses : $inactiveOutputTabClasses }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Obat & Cairan</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'penilaian_lab')" type="button" class="{{ $activeOutputTab === 'penilaian_lab' ? $activeOutputTabClasses : $inactiveOutputTabClasses }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Penilaian & Lab</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'vantilator')" type="button" class="{{ $activeOutputTab === 'vantilator' ? $activeOutputTabClasses : $inactiveOutputTabClasses }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ventilator</button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div x-show="$wire.activeOutputTab === 'ringkasan'" class="space-y-6">
                            @include('livewire.patient-monitor.partials.output-grafik-picu')
                            @include('livewire.patient-monitor.partials.output-ringkasan-3jam')
                            @include('livewire.patient-monitor.partials.output-ringkasan-balance')
                        </div>
                        <div x-show="$wire.activeOutputTab === 'observasi'" class="space-y-6">
                            @include('livewire.observasi-table-picu')
                        </div>
                        <div x-show="$wire.activeOutputTab === 'obat_cairan'" class="space-y-6">
                            @include('livewire.patient-monitor.partials.output-tabel-cairan')
                            @include('livewire.patient-monitor.partials.output-tabel-obat')
                        </div>
                        <div x-show="$wire.activeOutputTab === 'penilaian_lab'" class="space-y-6">
                            @include('livewire.patient-monitor.partials.output-tabel-gasdarah')
                        </div>
                        <div x-show="$wire.activeOutputTab === 'vantilator'" class="space-y-6">
                            @include('livewire.patient-monitor.partials.output-tabel-ventilator')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
