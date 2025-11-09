<div x-data="{ showPippModal: false }">

    {{-- 1. TABEL PIPP (Kode tabel Anda) --}}
    @php
    use Carbon\Carbon;
    @endphp
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-100 dark:border-gray-700">
        <div class="p-6 text-gray-900 dark:text-gray-100">

            <div class="flex items-center justify-between border-b dark:border-gray-700 pb-3 mb-3">
                <h3 class="text-lg font-medium text-primary-700 dark:text-primary-300">
                    Riwayat Penilaian Nyeri (PIPP)
                </h3>
                <button type="button"  @click="showPippModal = true; $wire.setPippTime()" @disabled($isReadOnly) class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded-lg shadow-sm
                               hover:bg-primary-700 focus:outline-none focus:ring-2
                               focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    + Nilai Nyeri
                </button>
            </div>

            <div class="overflow-x-auto mt-4 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-100 dark:scrollbar-track-gray-700">
                <table class="min-w-full divide-y-2 divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 text-sm">
                    <thead class="text-left bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Jam</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Usia Gest</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Perilaku</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">HR Max</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">SpO2 Min</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Alis</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Mata</th>
                            <th class="px-2 py-2 text-gray-700 dark:text-gray-300">Nasolabial</th>
                            <th class="px-2 py-2 font-bold text-gray-700 dark:text-gray-300">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($pippAssessments as $score)
                        <tr class="hover:bg-primary-50 dark:hover:bg-gray-700">
                            <th class="px-2 py-2 text-gray-900 dark:text-gray-100 font-medium">
                                {{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}<br>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $score->pegawai->nama ?? 'N/A' }}</span>
                            </th>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->gestational_age }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->behavioral_state }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->max_heart_rate }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->min_oxygen_saturation }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->brow_bulge }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->eye_squeeze }}</td>
                            <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300">{{ $score->nasolabial_furrow }}</td>
                            <td class="px-2 py-2 text-center font-bold text-lg
                                        {{ $score->total_score > 12 ? 'text-danger-600 dark:text-danger-400' : ($score->total_score > 6 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                {{ $score->total_score }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center p-4 text-gray-500 dark:text-gray-400">Belum ada penilaian PIPP.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 3. MODAL PIPP (Kode Anda, sudah di-refactor) --}}
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
                                                {{-- Tampilkan waktu dari properti PHP --}}
                                                {{ \Carbon\Carbon::parse($pipp_assessment_time ?? now())->format('d M Y, H:i') }}
                                            </div>
                                        </div>

                                        @foreach ($pippFields as $field)
                                        <div>
                                            <label for="{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $field['label'] }}</label>
                                            <select id="{{ $field['id'] }}" x-model="{{ $field['id'] }}" class="...">
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

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t ...">
                <button type="button" @click="showPippModal = false" class="...">
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
                        }).then((success) => {
                            if(success) {
                                showPippModal = false;
                            }
                        })" wire:loading.attr="disabled" wire:target="savePippScore" class="...">
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
