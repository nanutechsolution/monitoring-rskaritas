    <div>
        <x-slot name="header">
            <livewire:patient-header :no-rawat="$no_rawat" />
        </x-slot>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 px-4">
                <!-- Header & Navigasi -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <!-- Judul Section -->
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zm-4 4a4 4 0 00-4 4v5h16v-5a4 4 0 00-4-4H8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                                Monitoring 24 Jam
                                <span class="bg-gradient-to-r from-teal-600 to-emerald-500 bg-clip-text text-transparent">PEDIATRIC INTENSIVE CARE UNIT (PICU)</span>
                            </h2>
                            <p class="text-sm text-gray-500 mt-1">Pantau kondisi pasien secara real-time dengan mudah</p>
                        </div>
                    </div>

                    <!-- Kontrol Navigasi & Cetak -->
                    <div class="flex flex-wrap items-center gap-2 justify-end">
                        <!-- Tombol Hari Sebelumnya -->
                        <button wire:click="goToPreviousDay" type="button" title="Hari Sebelumnya" class="flex items-center justify-center p-3 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg shadow transition">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>

                        <!-- Input Tanggal -->
                        <div class="relative">
                            <input type="date" wire:model.blur="selectedDate" class="form-input py-2 px-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm">
                            <button type="button" x-on:click="$wire.set('selectedDate', new Date().toISOString().split('T')[0])" class="absolute right-1 top-1/2 -translate-y-1/2 px-2 py-1 text-xs bg-teal-500 text-white rounded hover:bg-teal-600 transition">
                                Today
                            </button>
                        </div>

                        <!-- Tombol Hari Berikutnya -->
                        <button wire:click="goToNextDay" type="button" title="Hari Berikutnya" class="flex items-center justify-center p-3 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg shadow transition disabled:opacity-50 disabled:cursor-not-allowed" @if(\Carbon\Carbon::parse($selectedDate)->isToday()) disabled @endif>
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

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
                        <livewire:therapy-program-modal-picu :current-cycle-id="$currentCycleId" :no-rawat="$no_rawat" wire:key="'therapy-modal-'.$currentCycleId" />
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <form wire:submit="saveRecord" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium border-b pb-3">Form Input Observasi</h3>
                            <div class="mt-4" x-data="{ currentTime: new Date() }" x-init="setInterval(() => currentTime = new Date(), 1000)">
                                <label class="block text-sm font-medium text-gray-700">Jam Observasi</label>

                                <div class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">
                                    <span x-text="currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
                                </div>
                            </div>

                            <div class="border-b border-gray-200 mt-4">
                                <nav class="bg-gray-50 shadow-sm -mb-px flex space-x-2 sm:space-x-4 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 px-2 py-1" aria-label="Tabs">
                                    <button wire:click.prevent="$set('activeTab', 'observasi')" type="button" class="{{ $activeTab === 'observasi' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Observasi
                                    </button>

                                    <button wire:click.prevent="$set('activeTab', 'ventilator')" type="button" class="{{ $activeTab === 'ventilator' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Ventilator
                                    </button>

                                    <button wire:click.prevent="$set('activeTab', 'cairan')" type="button" class="{{ $activeTab === 'cairan' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Cairan
                                    </button>
                                    <button wire:click.prevent="$set('activeTab', 'lainnya')" type="button" class="{{ $activeTab === 'lainnya' ? 'border-blue-500 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-100' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
                                        Lain-lain
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
                                <div x-show="$wire.activeTab === 'lainnya'" class="space-y-4">
                                    @include('livewire.patient-monitor.partials.tab-input-lainnya')
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 space-y-3 border-t">
                            <div class="text-right">
                                <button type="submit" wire:loading.attr="disabled" @click="$dispatch('sync-repeaters')" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
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
                        <div class="bg-white p-8  shadow-sm text-center">
                            <span class="text-gray-500 font-medium text-lg">
                                Memuat data riwayat...
                            </span>
                        </div>
                    </div>
                    <div wire:loading.remove wire:target="loadData">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                            <div class="px-4 pt-4 sm:px-6">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs Output">
                                        <button wire:click.prevent="$set('activeOutputTab', 'ringkasan')" type="button" class="{{ $activeOutputTab === 'ringkasan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ringkasan & Grafik</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'observasi')" type="button" class="{{ $activeOutputTab === 'observasi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Observasi</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'obat_cairan')" type="button" class="{{ $activeOutputTab === 'obat_cairan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Obat & Cairan</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'penilaian_lab')" type="button" class="{{ $activeOutputTab === 'penilaian_lab' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Penilaian & Lab</button>
                                        <button wire:click.prevent="$set('activeOutputTab', 'vantilator')" type="button" class="{{ $activeOutputTab === 'vantilator' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">Ventilator</button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div x-show="$wire.activeOutputTab === 'ringkasan'" class="space-y-6">
                            @include('livewire.patient-monitor.partials.output-grafik')
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
