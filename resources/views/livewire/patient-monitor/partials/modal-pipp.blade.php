
  @if ($showPippModal)
    <div
        x-data="{ show: @entangle('showPippModal') }"
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm"
    >
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="show = false"
            x-data="{
                gestational_age: @entangle('gestational_age'),
                behavioral_state: @entangle('behavioral_state'),
                max_heart_rate: @entangle('max_heart_rate'),
                min_oxygen_saturation: @entangle('min_oxygen_saturation'),
                brow_bulge: @entangle('brow_bulge'),
                eye_squeeze: @entangle('eye_squeeze'),
                nasolabial_furrow: @entangle('nasolabial_furrow'),
                get totalScore() {
                    return parseInt(this.gestational_age || 0)
                         + parseInt(this.behavioral_state || 0)
                         + parseInt(this.max_heart_rate || 0)
                         + parseInt(this.min_oxygen_saturation || 0)
                         + parseInt(this.brow_bulge || 0)
                         + parseInt(this.eye_squeeze || 0)
                         + parseInt(this.nasolabial_furrow || 0);
                }
            }"
            class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]"
        >

            <div class.="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                    🍼 Penilaian Nyeri Prematur (PIPP)
                </h3>
                <p class="text-sm text-gray-500 mt-1">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur.</p>
            </div>

            <div class="px-6 py-5 overflow-y-auto">

                @php
                    $pippFields = [
                        ['id' => 'gestational_age', 'label' => 'Usia Gestasi', 'options' => [
                            '0' => '≥ 36 mgg', '1' => '32–35 mgg + 6h', '2' => '28–31 mgg + 6h', '3' => '< 28 mgg'
                        ]],
                        ['id' => 'behavioral_state', 'label' => 'Perilaku Bayi (15 detik)', 'options' => [
                            '0' => 'Aktif/bangun, mata terbuka', '1' => 'Diam/bangun, mata terbuka/tertutup', '2' => 'Aktif/tidur, mata tertutup', '3' => 'Tenang/tidur, gerak minimal'
                        ]],
                        ['id' => 'max_heart_rate', 'label' => 'Laju Nadi Maks (peningkatan)', 'options' => [
                            '0' => '0–4 dpm', '1' => '5–14 dpm', '2' => '15–24 dpm', '3' => '≥25 dpm'
                        ]],
                        ['id' => 'min_oxygen_saturation', 'label' => 'Saturasi O₂ Min (penurunan)', 'options' => [
                            '0' => '92–100%', '1' => '89–91%', '2' => '85–88%', '3' => '<85%'
                        ]],
                        ['id' => 'brow_bulge', 'label' => 'Tarikan Alis (% waktu)', 'options' => [
                            '0' => 'Tidak ada (<9%)', '1' => 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                        ]],
                        ['id' => 'eye_squeeze', 'label' => 'Kerutan Mata (% waktu)', 'options' => [
                            '0' => 'Tidak ada (<9%)', '1' => 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                        ]],
                        ['id' => 'nasolabial_furrow', 'label' => 'Alur Nasolabial (% waktu)', 'options' => [
                            '0' => 'Tidak ada (<9%)', '1' => 'Minimum (10–39%)', '2' => 'Sedang (40–69%)', '3' => 'Maksimum (≥70%)'
                        ]],
                    ];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-5 gap-y-6">
                    <div class="col-span-full">
                         <label class="block text-sm font-medium text-gray-700 mb-1">🕒 Waktu Penilaian</label>
                         <div class="w-full max-w-xs rounded-lg bg-gray-100 text-gray-800 px-3 py-2 text-sm border border-gray-200">
                             {{ \Carbon\Carbon::parse($pipp_assessment_time)->format('d M Y, H:i') }}
                         </div>
                    </div>

                    @foreach ($pippFields as $field)
                        <div>
                            <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $field['label'] }}</label>
                            <select
                                id="{{ $field['id'] }}"
                                x-model="{{ $field['id'] }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm"
                            >
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
                <button
                    type="button"
                    wire:click="closePippModal"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition shadow-sm"
                >
                    Batal
                </button>
                <button
                    type="button"
                    wire:click="savePippScore"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75"
                    class="px-5 py-2 text-sm font-semibold rounded-lg bg-teal-600 text-white hover:bg-teal-700 active:scale-[0.98] transition transform shadow-sm"
                >
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
@endif
