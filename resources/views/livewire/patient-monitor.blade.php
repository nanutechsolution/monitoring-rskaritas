<div>
    {{-- ====================================================================== --}}
    {{-- 1. HEADER HALAMAN (Info Pasien & Navigasi Siklus) --}}
    {{-- ====================================================================== --}}
    {{-- A. INFO PASIEN (HANYA TEKS STATIS) --}}
    <x-slot name="header">
        @include('livewire.patient-monitor.partials.header-pasien')
    </x-slot>

    {{-- ====================================================================== --}}
    {{-- 2. KONTEN BODY UTAMA --}}
    {{-- ====================================================================== --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <!-- Navigasi Tanggal + Cetak -->
            <div class="flex flex-wrap items-center justify-end gap-2 mb-4">
                <!-- Tombol Hari Sebelumnya -->
                <button wire:click="goToPreviousDay" type="button" title="Hari Sebelumnya" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-600 transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Input tanggal -->
                <input type="date" wire:model.blur="selectedDate" class="form-input py-2 px-3 text-sm rounded-md border-gray-300 shadow-sm transition-colors focus:ring-teal-500 focus:border-teal-500">

                <!-- Tombol Hari Berikutnya -->
                <button wire:click="goToNextDay" type="button" title="Hari Berikutnya" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" @if(\Carbon\Carbon::parse($selectedDate)->isToday()) disabled @endif>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Tombol Cetak -->
                @if($currentCycleId)
                <a href="{{ route('patient.monitor.report', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $currentCycleId]) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10V6a4 4 0 014-4h2a4 4 0 014 4v4"></path>
                    </svg>
                    Cetak
                </a>

                @endif
            </div>

            <!-- Tombol Aksi (Modal) dengan Scroll Snap -->
            <div class="overflow-x-auto py-3 -mx-3 scroll-smooth scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <div class="flex gap-3 px-3 min-w-max snap-x snap-mandatory">
                    <!-- Catat Kejadian -->
                    <button type="button" wire:click="openEventModal" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-green-50 transition-all">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="font-medium text-gray-800">Catat Kejadian</span>
                    </button>

                    <!-- Tambah Obat -->
                    <button type="button" wire:click="openMedicationModal" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-yellow-50 flex-shrink-0 snap-start transition-all">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"></path>
                        </svg>
                        <span class="font-medium text-gray-800">Pemberian Obat</span>
                    </button>

                    <!-- Hasil Gas Darah -->
                    <button type="button" wire:click="openBloodGasModal" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-red-50 flex-shrink-0 snap-start transition-all">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6"></path>
                        </svg>
                        <span class="font-medium text-gray-800">Gas Darah</span>
                    </button>

                    <!-- Penilaian Nyeri PIPP -->
                    <button type="button" wire:click="openPippModal" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-purple-50 flex-shrink-0 snap-start transition-all">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="font-medium text-gray-800">Penilaian Nyeri</span>
                    </button>

                    <!-- Program Terapi / Instruksi -->
                    <button type="button" wire:click="openTherapyModal" class="flex items-center gap-2 px-5 py-2 bg-teal-600 text-white border rounded-lg shadow hover:shadow-md hover:bg-teal-700 flex-shrink-0 snap-start transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v10l5-5-5-5z"></path>
                        </svg>
                        <span class="font-medium">Program Terapi</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <form wire:submit="saveRecord" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium border-b pb-3">Form Input Observasi</h3>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Jam Observasi</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm">
                                {{ \Carbon\Carbon::parse($record_time)->format('d M Y, H:i') }}
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

                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @include('livewire.patient-monitor.partials.alat-terpasang')
                </div>
            </div>
            <div class="lg:col-span-2 space-y-6" wire:init="loadData">
                {{-- 1. Tambahkan placeholder loading --}}
                <div wire:loading wire:target="loadData" class="w-full">
                    <div class="bg-white p-8 rounded-lg shadow-sm text-center">
                        <span class="text-gray-500 font-medium text-lg">
                            Memuat data riwayat...
                        </span>
                    </div>
                </div>
                <div wire:loading.remove wire:target="loadData">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                        @include('livewire.patient-monitor.partials.output-tabel-observasi')
                    </div>
                    <div x-show="$wire.activeOutputTab === 'obat_cairan'" class="space-y-6">
                        @include('livewire.patient-monitor.partials.output-tabel-obat')
                        @include('livewire.patient-monitor.partials.output-tabel-cairan')
                    </div>
                    <div x-show="$wire.activeOutputTab === 'penilaian_lab'" class="space-y-6">
                        @include('livewire.patient-monitor.partials.output-tabel-pipp')
                        @include('livewire.patient-monitor.partials.output-tabel-gasdarah')
                    </div>
                    <div x-show="$wire.activeOutputTab === 'vantilator'" class="space-y-6">
                        @include('livewire.patient-monitor.partials.output-tabel-ventilator')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('livewire.patient-monitor.partials.modal-kejadian-cepat')
    @include('livewire.patient-monitor.partials.modal-pipp')
    @include('livewire.patient-monitor.partials.modal-alat')
    @include('livewire.patient-monitor.partials.modal-obat')
    @include('livewire.patient-monitor.partials.modal-gasdarah')
    @include('livewire.patient-monitor.partials.modal-progam-terapi')
</div>
