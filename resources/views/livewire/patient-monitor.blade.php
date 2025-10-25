<div>
    <x-slot name="header">
        <livewire:patient-header :no-rawat="$no_rawat" />
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
                    @include('livewire.patient-monitor.partials.modal-kejadian-cepat')
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
                                        <input type="datetime-local" wire:model.defer="given_at" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed">
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
                    @include('livewire.patient-monitor.partials.modal-gasdarah')
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
                @include('livewire.patient-monitor.partials.alat-terpasang')
            </div>
            <div class="lg:col-span-2 space-y-6" wire:init="loadData">
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
