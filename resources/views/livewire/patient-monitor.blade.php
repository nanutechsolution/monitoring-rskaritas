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
                <a href="{{ route('monitoring.report.pdf', ['no_rawat' => str_replace('/', '_', $no_rawat), 'cycle_id' => $currentCycleId]) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white shadow-sm transition-colors">
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
                    <div x-data="{ showBloodGasModal: false }">
                        <!-- Tombol Buka Modal -->
                        <button type="button" @click="showBloodGasModal = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-red-50 flex-shrink-0 snap-start transition-all">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6"></path>
                            </svg>
                            <span class="font-medium text-gray-800">Gas Darah</span>
                        </button>

                        <!-- Modal -->
                        <div x-show="showBloodGasModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm backdrop-saturate-150" @keydown.escape.window="showBloodGasModal = false">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-all" x-show="showBloodGasModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showBloodGasModal = false"></div>

                            <!-- Modal Box -->
                            <div x-show="showBloodGasModal" @click.away="showBloodGasModal = false" x-on:close-blood-gas-modal.window="showBloodGasModal = false" class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl drop-shadow-2xl border border-gray-100 transform transition-all p-0 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                                <!-- Header -->
                                <div class="px-6 py-4 bg-gradient-to-r from-red-600/80 to-red-500/70 text-white flex justify-between items-center shadow-sm">
                                    <div>
                                        <h3 class="text-lg font-semibold flex items-center gap-2">🩸 Catat Hasil Gas Darah</h3>
                                        <p class="text-sm opacity-90">Masukkan data analisis gas darah (AGD) pasien.</p>
                                    </div>
                                    <button @click="showBloodGasModal = false" class="text-white/80 hover:text-white transition">✕</button>
                                </div>

                                <!-- Body -->
                                <div class="px-6 py-5 overflow-y-auto bg-white">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-5 gap-y-6">
                                        <div>
                                            <label for="form_taken_at" class="block text-sm font-medium text-gray-700">Waktu Pengambilan</label>
                                            <input id="form_taken_at" type="datetime-local" wire:model.defer="taken_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                            @error('taken_at') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
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
                                            <label for="form_{{ $field['id'] }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                                            <input id="form_{{ $field['id'] }}" type="number" step="{{ $field['step'] }}" wire:model.defer="{{ $field['id'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                            @error($field['id']) <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                                    <button type="button" @click="showBloodGasModal = false" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                                        Batal
                                    </button>
                                    <button type="button" wire:click="saveBloodGasResult" class="px-5 py-2 text-sm font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 active:scale-[0.98] transition transform shadow">
                                        <span wire:loading.remove wire:target="saveBloodGasResult">💾 Simpan Hasil</span>
                                        <span wire:loading wire:target="saveBloodGasResult">Menyimpan...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penilaian Nyeri PIPP -->
                    <div x-data="{ showPippModal: false }">

                        <button type="button" @click="showPippModal = true" class="flex items-center gap-2 px-5 py-2 bg-white border rounded-lg shadow hover:shadow-md hover:bg-purple-50 flex-shrink-0 snap-start transition-all">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium text-gray-800">Penilaian Nyeri</span>
                        </button>

                        <div x-show="showPippModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm" style="display: none;">
                            <div x-show="showPippModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" @click.away="showPippModal = false" x-data="{
                gestational_age: '0',
                behavioral_state: '0',
                max_heart_rate: '0',
                min_oxygen_saturation: '0',
                brow_bulge: '0',
                eye_squeeze: '0',
                nasolabial_furrow: '0',
                get totalScore() {
                    return parseInt(this.gestational_age || 0)
                         + parseInt(this.behavioral_state || 0)
                         + parseInt(this.max_heart_rate || 0)
                         + parseInt(this.min_oxygen_saturation || 0)
                         + parseInt(this.brow_bulge || 0)
                         + parseInt(this.eye_squeeze || 0)
                         + parseInt(this.nasolabial_furrow || 0);
                }
            }" class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]">

                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                        🍼 Penilaian Nyeri Prematur (PIPP)
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur.</p>
                                </div>


                                <div class="px-6 py-5 overflow-y-auto">
                                    @php
                                    $pippFields = [
                                    ['id' => 'gestational_age', 'label' => 'Usia Gestasi', 'options' => [
                                    '0' => '≥ 36 mgg', '1' => '32–35 mgg + 6h', '2' => '28–31 mgg + 6h', '3' => '< 28 mgg' ]], ['id'=> 'behavioral_state', 'label' => 'Perilaku Bayi (15 detik)', 'options' => [
                                        '0' => 'Aktif/bangun, mata terbuka', '1' => 'Diam/bangun, mata terbuka/tertutup', '2' => 'Aktif/tidur, mata tertutup', '3' => 'Tenang/tidur, gerak minimal'
                                        ]],
                                        ['id' => 'max_heart_rate', 'label' => 'Laju Nadi Maks (peningkatan)', 'options' => [
                                        '0' => '0–4 dpm', '1' => '5–14 dpm', '2' => '15–24 dpm', '3' => '≥25 dpm'
                                        ]],
                                        ['id' => 'min_oxygen_saturation', 'label' => 'Saturasi O₂ Min (penurunan)', 'options' => [
                                        '0' => '92–100%', '1' => '89–91%', '2' => '85–88%', '3' => '<85%' ]], ['id'=> 'brow_bulge', 'label' => 'Tarikan Alis (% waktu)', 'options' => [
                                            '0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                                                ]],
                                                ['id' => 'eye_squeeze', 'label' => 'Kerutan Mata (% waktu)', 'options' => [
                                                '0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                                                    ]],
                                                    ['id' => 'nasolabial_furrow', 'label' => 'Alur Nasolabial (% waktu)', 'options' => [
                                                    '0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                                                        ]],
                                                        ];
                                                        @endphp

                                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-6">
                                                            <div class="col-span-full">
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">🕒 Waktu Penilaian</label>
                                                                <div class="w-full max-w-xs rounded-lg bg-gray-100 text-gray-800 px-3 py-2 text-sm border border-gray-200">
                                                                    {{ \Carbon\Carbon::parse($pipp_assessment_time ?? now())->format('d M Y, H:i') }}
                                                                </div>
                                                            </div>

                                                            @foreach ($pippFields as $field)
                                                            <div>
                                                                <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</label>
                                                                <select id="{{ $field['id'] }}" x-model="{{ $field['id'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                                                    @foreach ($field['options'] as $value => $label)
                                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @endforeach
                                                        </div>

                                                        <div class="mt-8 space-y-4 border-t border-gray-200 pt-6">
                                                            <div class="text-lg font-semibold text-center">
                                                                Total Skor PIPP:
                                                                <span class="text-3xl font-bold transition-all duration-300" :class="{
                                'text-green-600': totalScore <= 6,
                                'text-yellow-600': totalScore > 6 && totalScore <= 12,
                                'text-red-600': totalScore > 12
                            }" x-text="totalScore"></span>
                                                            </div>

                                                            <div class="text-sm text-gray-700 p-4 rounded-lg max-w-lg mx-auto space-y-2">
                                                                <strong class="block mb-2 text-center text-base">💡 Rekomendasi Intervensi</strong>
                                                                <div x-show="totalScore <= 6" class="p-3 bg-green-50 rounded-md text-green-800 border border-green-200"><strong>0–6:</strong> Lanjutkan tatalaksana & pemantauan rutin.</div>
                                                                <div x-show="totalScore > 6 && totalScore <= 12" class="p-3 bg-yellow-50 rounded-md text-yellow-800 border border-yellow-200"><strong>7–12:</strong> Berikan intervensi non-farmakologis (kenyamanan, sukrosa oral).</div>
                                                                <div x-show="totalScore > 12" class="p-3 bg-red-50 rounded-md text-red-800 border border-red-200"><strong>>12:</strong> Pertimbangkan intervensi farmakologis (Parasetamol/Narkotik/Sedasi).</div>
                                                            </div>
                                                        </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                                    <button type="button" @click="showPippModal = false" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition shadow-sm">
                                        Batal
                                    </button>
                                    <button type="button" @click="$wire.savePippScore({
                        gestational_age: gestational_age,
                        behavioral_state: behavioral_state,
                        max_heart_rate: max_heart_rate,
                        min_oxygen_saturation: min_oxygen_saturation,
                        brow_bulge: brow_bulge,
                        eye_squeeze: eye_squeeze,
                        nasolabial_furrow: nasolabial_furrow,
                        total_score: totalScore
                    }).then(() => {
                        showPippModal = false; // Tutup modal setelah berhasil disimpan
                    })" wire:loading.attr="disabled" wire:loading.class="opacity-75" wire:target="savePippScore" class="px-5 py-2 text-sm font-semibold rounded-lg bg-teal-600 text-white hover:bg-teal-700 active:scale-[0.98] transition transform shadow-sm">
                                        <span wire:loading.remove wire:target="savePippScore">
                                            💾 Simpan Skor PIPP
                                        </span>
                                        <span wire:loading wire:target="savePippScore">
                                            Menyimpan...
                                        </span>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <livewire:therapy-program-modal :current-cycle-id="$currentCycleId" :no-rawat="$no_rawat" wire:key="'therapy-modal-'.$currentCycleId" />
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
                            <div x-data="{ currentTime: new Date() }" x-init="setInterval(() => currentTime = new Date(), 1000)" class="mt-1 text-xs text-gray-500 text-right">
                                Waktu Sekarang: <span x-text="currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })"></span>
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
                                <button type="button" @click="newDeviceForm()" class="text-sm bg-teal-600 text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
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
                                            <button type="button" @click="removeDevice({
                                id: {{ $device->id }},
                                name: '{{ e($device->device_name) }}',
                                size: '{{ e($device->size ?? '') }}',
                                location: '{{ e($device->location ?? '') }}',
                                installed: '{{ $device->installation_date->format('d M Y, H:i') }}'
                            })" class="text-sm text-red-600 hover:text-red-800">
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
                        <div x-show="showAddDeviceModal" x-on:keydown.escape.window="showAddDeviceModal = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">

                            <div x-show="showAddDeviceModal" x-transition.opacity class="absolute inset-0 bg-gray-900/75" @click="showAddDeviceModal = false"></div>

                            <div x-show="showAddDeviceModal" x-transition class="relative bg-white rounded-lg shadow-xl w-full max-w-lg">

                                <div class="flex items-start justify-between p-5 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Tambah Alat Baru
                                    </h3>
                                    <button type="button" @click="showAddDeviceModal = false" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div>
                                        <label for="installation_date_add" class="block mb-2 text-sm font-medium text-gray-900">Tgl. Pasang</label>
                                        <input id="installation_date_add" type="datetime-local" x-model="installationDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-teal-500 focus:border-teal-500 block w-full p-2.5">
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
                                    <button type="button" wire:loading.attr="disabled" wire:target="saveDevice" @click="$wire.saveDevice({
                        device_name: deviceName,
                        size: deviceSize,
                        location: deviceLocation,
                        installation_date: installationDate
                    }).then((success) => {
                        if (success) {
                            showAddDeviceModal = false;
                        }
                    })" class="px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300">
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
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-6 space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
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
                                    <button type="button" @click="$wire.confirmRemoveDevice(deviceToRemove.id).then(() => {
                        closeRemoveModal();
                    })" wire:loading.attr="disabled" wire:target="confirmRemoveDevice" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300">
                                        <span wire:loading.remove wire:target="confirmRemoveDevice">Ya, Lepas Sekarang</span>
                                        <span wire:loading wire:target="confirmRemoveDevice">Memproses...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

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
</div>
