<div>
    <x-slot name="header">
        @include('livewire.patient-monitor.partials.header-pasien')
    </x-slot>

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
                    <div x-data="{ openEventModal: false }">
                        <!-- Tombol buka modal -->
                        <button type="button" @click="openEventModal = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-green-50 transition-all">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium text-gray-800">Catat Kejadian</span>
                        </button>

                        <!-- Modal -->
                        <div x-show="openEventModal" x-cloak x-transition.opacity.scale.80 class="fixed inset-0 z-50 flex items-center justify-center">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gray-900 opacity-75" @click="openEventModal = false"></div>

                            <!-- Konten modal -->
                            <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md transition-all transform scale-100">
                                <h3 class="text-lg font-medium text-gray-900">Catat Kejadian</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Pilih semua kejadian yang terjadi pada waktu yang sama.
                                </p>

                                <div class="mt-4 space-y-2 border-t pt-4">
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_cyanosis" class="rounded border-gray-300">
                                            <span>Cyanosis</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_pucat" class="rounded border-gray-300">
                                            <span>Pucat</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_ikterus" class="rounded border-gray-300">
                                            <span>Ikterus</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_crt_less_than_2" class="rounded border-gray-300">
                                            <span>CRT &lt; 2 detik</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_bradikardia" class="rounded border-gray-300">
                                            <span>Bradikardia</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" wire:model.defer="event_stimulasi" class="rounded border-gray-300">
                                            <span>Stimulasi</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Tombol aksi -->
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="openEventModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Batal
                                    </button>

                                    <button type="button" wire:click="saveEvent" @click="openEventModal = false" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 transition ease-in-out duration-150 overflow-hidden group">
                                        <!-- Spinner saat loading -->
                                        <svg wire:loading wire:target="saveEvent" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white absolute left-3 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"></path>
                                        </svg>

                                        <!-- Teks tombol -->
                                        <span wire:loading.remove wire:target="saveEvent">
                                            Simpan Kejadian
                                        </span>
                                        <span wire:loading wire:target="saveEvent">
                                            Menyimpan...
                                        </span>

                                        <!-- Efek kilau animasi saat diklik -->
                                        <span class="absolute inset-0 bg-indigo-400 opacity-0 group-active:opacity-30 transition-opacity duration-200 rounded-md"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ open: false }">
                        <!-- Tombol Buka Modal -->
                        <button @click="open = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-yellow-50 flex-shrink-0 snap-start transition-all">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8"></path>
                            </svg>
                            <span class="font-medium text-gray-800">Pemberian Obat</span>
                        </button>

                        <!-- Modal -->
                        <div x-show="open" x-cloak x-transition.opacity.scale.80 class="fixed inset-0 z-50 flex items-center justify-center" @keydown.escape.window="open = false">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gray-900 opacity-75" @click="open = false"></div>
                            <!-- Isi Modal -->
                            <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
                                <h3 class="text-lg font-medium text-gray-900">Tambah Pemberian Obat</h3>

                                <div class="mt-4 space-y-4 border-t pt-4">
                                    <div>
                                        <label class="block text-sm font-medium">Waktu Pemberian</label>
                                        <input type="datetime-local" wire:model.defer="given_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Nama Obat</label>
                                        <input id="medication_name" type="text" wire:model.defer="medication_name" list="recent-meds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik atau pilih dari riwayat...">
                                        <datalist id="recent-meds">
                                            @foreach($recentMedicationNames as $name)
                                            <option value="{{ $name }}">
                                                @endforeach
                                        </datalist>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium">Dosis</label>
                                            <input type="text" wire:model.defer="dose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 3x80mg">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium">Rute</label>
                                            <input type="text" wire:model.defer="route" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: IV">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex justify-end space-x-3">
                                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Batal
                                    </button>

                                    <button type="button" wire:click="saveMedication" @click="open = false" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-wait" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700 transition ease-in-out duration-150">

                                        <svg wire:loading wire:target="saveMedication" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>

                                        <span wire:loading.remove wire:target="saveMedication">Simpan Obat</span>
                                        <span wire:loading wire:target="saveMedication">Menyimpan...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-data="{ openGasModal: false }">
                        <!-- Tombol Buka Modal -->
                        <button type="button" @click="openGasModal = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="font-medium text-gray-800">Gas Darah</span>
                        </button>

                        <!-- Modal -->
                        <div x-show="openGasModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gray-900 opacity-75 transition-opacity" @click="openGasModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-75" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-75" x-transition:leave-end="opacity-0"></div>

                            <!-- Box Modal -->
                            <div x-show="openGasModal" @click.away="openGasModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-6 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-6 scale-95" class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl border border-gray-200 transition-all transform">
                                <!-- Header -->
                                <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                                            🩸 Catat Hasil Gas Darah
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Masukkan data analisis gas darah (AGD) pasien.
                                        </p>
                                    </div>
                                    <button @click="openGasModal = false" class="text-gray-500 hover:text-red-600 transition">
                                        ✕
                                    </button>
                                </div>

                                <!-- Body -->
                                <div class="mt-5 space-y-4 overflow-y-auto max-h-[70vh]">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Waktu Pengambilan</label>
                                            <input type="datetime-local" wire:model.defer="taken_at" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                            @error('taken_at')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @php
                                        $bloodGasFields = [
                                        ['id' => 'gula_darah', 'label' => 'Gula Darah (BS)', 'step' => '0.1'],
                                        ['id' => 'ph', 'label' => 'pH', 'step' => '0.01'],
                                        ['id' => 'pco2', 'label' => 'PCO₂', 'step' => '0.1'],
                                        ['id' => 'po2', 'label' => 'PO₂', 'step' => '0.1'],
                                        ['id' => 'hco3', 'label' => 'HCO₃', 'step' => '0.1'],
                                        ['id' => 'be', 'label' => 'BE', 'step' => '0.1'],
                                        ['id' => 'sao2', 'label' => 'SaO₂', 'step' => '0.1'],
                                        ];
                                        @endphp

                                        @foreach ($bloodGasFields as $field)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                            <input type="number" step="{{ $field['step'] }}" wire:model.defer="{{ $field['id'] }}" class="w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                            @error($field['id'])
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="mt-6 flex justify-end space-x-3 border-t border-gray-200 pt-4">
                                    <button type="button" @click="openGasModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition">
                                        Batal
                                    </button>

                                    <button type="button" wire:click="saveBloodGasResult" wire:loading.attr="disabled" class="relative inline-flex items-center px-5 py-2 text-sm font-semibold text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 active:scale-95 transition transform overflow-hidden group">
                                        <!-- Spinner -->
                                        <svg wire:loading wire:target="saveBloodGasResult" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white absolute left-3 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"></path>
                                        </svg>

                                        <span wire:loading.remove wire:target="saveBloodGasResult">💾 Simpan Hasil</span>
                                        <span wire:loading wire:target="saveBloodGasResult">Menyimpan...</span>

                                        <!-- Efek klik -->
                                        <span class="absolute inset-0 bg-red-400 opacity-0 group-active:opacity-30 transition-opacity duration-200 rounded-md"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


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
    {{-- @include('livewire.patient-monitor.partials.modal-kejadian-cepat') --}}
    @include('livewire.patient-monitor.partials.modal-pipp')
    @include('livewire.patient-monitor.partials.modal-alat')
    {{-- @include('livewire.patient-monitor.partials.modal-obat') --}}
    {{-- @include('livewire.patient-monitor.partials.modal-gasdarah') --}}
    @include('livewire.patient-monitor.partials.modal-progam-terapi')
</div>
