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
                    <button wire:click="goToPreviousDay" type="button" title="Hari Sebelumnya" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md text-gray-600 dark:text-gray-300 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <input type="date" wire:model.blur="selectedDate" class="form-input py-2 px-3 text-sm rounded-md shadow-sm
                                  border-gray-300 dark:border-gray-600
                                  bg-white dark:bg-gray-700
                                  text-gray-900 dark:text-gray-200
                                  focus:ring-primary-500 focus:border-primary-500">

                    <button wire:click="goToNextDay" type="button" title="Hari Berikutnya" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md text-gray-600 dark:text-gray-300 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" @if(\Carbon\Carbon::parse($selectedDate)->isToday()) disabled @endif>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

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
                    @include('livewire.patient-monitor.partials.modal-kejadian-cepat')
                    @include('livewire.patient-monitor.partials.modal-obat')
                    @include('livewire.patient-monitor.partials.modal-gasdarah')

                    <div x-data="{ showPippModal: false }">
                        <button type="button" @click="showPippModal = true" class="flex items-center gap-2 px-5 py-2
                                       bg-white dark:bg-gray-800
                                       border dark:border-gray-700 rounded-lg shadow
                                       hover:shadow-md hover:bg-primary-50 dark:hover:bg-gray-700
                                       flex-shrink-0 snap-start transition-all">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-medium text-gray-800 dark:text-gray-100">Penilaian Nyeri</span>
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
                                 }" class="relative w-full max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow-xl flex flex-col max-h-[90vh]">

                                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                                        🍼 Penilaian Nyeri Prematur (PIPP)
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur.</p>
                                </div>


                                <div class="px-6 py-5 overflow-y-auto">
                                    @php
                                    $pippFields = [
                                    ['id' => 'gestational_age', 'label' => 'Usia Gestasi', 'options' => ['0' => '≥ 36 mgg', '1' => '32–35 mgg + 6h', '2' => '28–31 mgg + 6h', '3' => '< 28 mgg' ]], ['id'=> 'behavioral_state', 'label' => 'Perilaku Bayi (15 detik)', 'options' => ['0' => 'Aktif/bangun, mata terbuka', '1' => 'Diam/bangun, mata terbuka/tertutup', '2' => 'Aktif/tidur, mata tertutup', '3' => 'Tenang/tidur, gerak minimal' ]],
                                        ['id' => 'max_heart_rate', 'label' => 'Laju Nadi Maks (peningkatan)', 'options' => ['0' => '0–4 dpm', '1' => '5–14 dpm', '2' => '15–24 dpm', '3' => '≥25 dpm' ]],
                                        ['id' => 'min_oxygen_saturation', 'label' => 'Saturasi O₂ Min (penurunan)', 'options' => ['0' => '92–100%', '1' => '89–91%', '2' => '85–88%', '3' => '<85%' ]], ['id'=> 'brow_bulge', 'label' => 'Tarikan Alis (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                                ['id' => 'eye_squeeze', 'label' => 'Kerutan Mata (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                                    ['id' => 'nasolabial_furrow', 'label' => 'Alur Nasolabial (% waktu)', 'options' => ['0' => 'Tidak ada (<9%)', '1'=> 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)' ]],
                                                        ];
                                                        @endphp

                                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-6">
                                                            <div class="col-span-full">
                                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">🕒 Waktu Penilaian</label>
                                                                <div class="w-full max-w-xs rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-2 text-sm border border-gray-200 dark:border-gray-600">
                                                                    {{ \Carbon\Carbon::parse($pipp_assessment_time ?? now())->format('d M Y, H:i') }}
                                                                </div>
                                                            </div>

                                                            @foreach ($pippFields as $field)
                                                            <div>
                                                                <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $field['label'] }}</label>
                                                                <select id="{{ $field['id'] }}" x-model="{{ $field['id'] }}" class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                                           border-gray-300 dark:border-gray-600
                                                           bg-white dark:bg-gray-700
                                                           text-gray-900 dark:text-gray-200
                                                           focus:border-primary-500 focus:ring-primary-500">
                                                                    @foreach ($field['options'] as $value => $label)
                                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @endforeach
                                                        </div>

                                                        <div class="mt-8 space-y-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                                                            <div class="text-lg font-semibold text-center text-gray-800 dark:text-gray-100">
                                                                Total Skor PIPP:
                                                                <span class="text-3xl font-bold transition-all duration-300" :class="{
                                                'text-green-600': totalScore <= 6,
                                                'text-yellow-600': totalScore > 6 && totalScore <= 12,
                                                'text-red-600': totalScore > 12
                                            }" x-text="totalScore"></span>
                                                            </div>

                                                            <div class="text-sm text-gray-700 dark:text-gray-300 p-4 rounded-lg max-w-lg mx-auto space-y-2">
                                                                <strong class="block mb-2 text-center text-base">💡 Rekomendasi Intervensi</strong>
                                                                <div x-show="totalScore <= 6" class="p-3 bg-green-50 dark:bg-green-900 dark:bg-opacity-50 rounded-md text-green-800 dark:text-green-200 border border-green-200 dark:border-green-800"><strong>0–6:</strong> Lanjutkan tatalaksana & pemantauan rutin.</div>
                                                                <div x-show="totalScore > 6 && totalScore <= 12" class="p-3 bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-50 rounded-md text-yellow-800 dark:text-yellow-200 border border-yellow-200 dark:border-yellow-800"><strong>7–12:</strong> Berikan intervensi non-farmakologis (kenyamanan, sukrosa oral).</div>
                                                                <div x-show="totalScore > 12" class="p-3 bg-red-50 dark:bg-danger-900 dark:bg-opacity-50 rounded-md text-red-800 dark:text-red-200 border border-red-200 dark:border-red-800"><strong>>12:</strong> Pertimbangkan intervensi farmakologis (Parasetamol/Narkotik/Sedasi).</div>
                                                            </div>
                                                        </div>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                                    <button type="button" @click="showPippModal = false" class="px-4 py-2 text-sm font-medium rounded-lg
                                                   border border-gray-300 dark:border-gray-600
                                                   bg-white dark:bg-gray-700
                                                   text-gray-700 dark:text-gray-300
                                                   hover:bg-gray-100 dark:hover:bg-gray-600
                                                   transition shadow-sm">
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
                                            showPippModal = false;
                                        })" wire:loading.attr="disabled" wire:loading.class="opacity-75" wire:target="savePippScore" class="px-5 py-2 text-sm font-semibold rounded-lg
                                               bg-primary-600 text-white
                                               hover:bg-primary-700
                                               active:scale-[0.98] transition transform shadow-sm
                                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                                               dark:focus:ring-offset-gray-800">
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
                <form wire:submit="saveRecord" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mt-4">
                            <h3 class="text-lg font-medium border-b dark:border-gray-700 pb-3">Form Input Observasi</h3>
                            <div class="mt-4" x-data="{ currentTime: new Date() }" x-init="setInterval(() => currentTime = new Date(), 1000)">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Observasi</label>
                                <div class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 shadow-sm px-3 py-2 sm:text-sm text-gray-700 dark:text-gray-300">
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
                                <button wire:click.prevent="$set('activeTab', 'lainnya')" type="button" class="{{ $activeTab === 'lainnya' ? 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700' }} whitespace-nowrap py-2 px-3 border-b-2 font-medium text-sm rounded-t-md transition-colors">
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
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div x-show="$wire.activeOutputTab === 'ringkasan'" class="space-y-6">
                        @include('livewire.patient-monitor.partials.output-grafik')
                        @include('livewire.patient-monitor.partials.output-ringkasan-3jam')
                        @include('livewire.patient-monitor.partials.output-ringkasan-balance')
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
                </div>
            </div>
        </div>
    </div>
</div>
