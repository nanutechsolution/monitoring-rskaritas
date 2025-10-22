@if ($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm">
    <div class="absolute inset-0 bg-gray-800/60 transition-opacity" wire:click="closeModal"></div>

    <div x-data="{
        get totalScore() {
            // Mengambil nilai properti Livewire secara real-time untuk perhitungan
            return parseInt($wire.gestational_age || 0)
                + parseInt($wire.behavioral_state || 0)
                + parseInt($wire.max_heart_rate || 0)
                + parseInt($wire.min_oxygen_saturation || 0)
                + parseInt($wire.brow_bulge || 0)
                + parseInt($wire.eye_squeeze || 0)
                + parseInt($wire.nasolabial_furrow || 0);
        }
    }" class="relative bg-white/90 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-2xl p-6 w-full max-w-4xl animate-in fade-in zoom-in duration-200">

        <form wire:submit.prevent="saveScore">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2 mb-2">
                🍼 Penilaian Nyeri Prematur (PIPP)
            </h3>
            <p class="text-sm text-gray-500 mb-4">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur berdasarkan parameter klinis.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 border-t border-gray-200 pt-4">
                <div class="col-span-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">🕒 Waktu Penilaian</label>
                    <div class="w-full rounded-lg bg-gray-100 text-gray-800 px-3 py-2 text-sm border border-gray-200">
                        {{ \Carbon\Carbon::parse($pipp_assessment_time)->format('d M Y, H:i') }}
                    </div>
                </div>

                {{-- Hapus loop ini di file utama, karena sudah di komponen modal ini --}}
                @foreach ([
                    ['gestational_age', 'Usia Gestasi', [
                    '0: ≥ 36 mgg', '1: 32–35 mgg + 6h', '2: 28–31 mgg + 6h', '3: < 28 mgg' ]],
                    ['behavioral_state', 'Perilaku Bayi (15 detik)' , [
                    '0: Aktif/bangun, mata terbuka' , '1: Diam/bangun, mata terbuka/tertutup' , '2: Aktif/tidur, mata tertutup' , '3: Tenang/tidur, gerak minimal' ]],
                    ['max_heart_rate', 'Laju Nadi Maks (peningkatan)' , [
                    '0: 0–4 dpm (Skor 0)', '1: 5–14 dpm (Skor 1)', '2: 15–24 dpm (Skor 2)', '3: ≥25 dpm (Skor 3)' ]],
                    ['min_oxygen_saturation', 'Saturasi O₂ Minimum (penurunan)' , [
                    '0: 92–100% (Skor 0)', '1: 89–91% (Skor 1)', '2: 85–88% (Skor 2)', '3: <85% (Skor 3)' ]],
                    ['brow_bulge', 'Tarikan Alis (% waktu)' , [
                    '0: Tidak ada (<9%)', '1: Minimum (10–39%)', '2: Sedang (40–69%)', '3: Maksimum (≥70%)' ]],
                    ['eye_squeeze', 'Kerutan Mata (% waktu)' , [
                    '0: Tidak ada (<9%)', '1: Minimum (10–39%)', '2: Sedang (40–69%)', '3: Maksimum (≥70%)' ]],
                    ['nasolabial_furrow', 'Alur Nasolabial (% waktu)' , [
                    '0: Tidak ada (<9%)', '1: Minimum (10–39%)', '2: Sedang (40–69%)', '3: Maksimum (≥70%)' ]]
                ] as [$id, $label, $options])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                    {{-- PENTING: Menggunakan wire:model.live untuk perhitungan skor Alpine real-time --}}
                    <select wire:model.live="{{ $id }}" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @foreach ($options as $i => $opt)
                        {{-- Menggunakan $i untuk value (0, 1, 2, 3) --}}
                        <option value="{{ $i }}">{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>

            <div class="mt-8 text-center space-y-3 border-t border-gray-200 pt-4">
                <div class="text-lg font-semibold">
                    Total Skor PIPP:
                    <span class="text-2xl font-bold transition" :class="{
                        'text-green-600': totalScore <= 6,
                        'text-yellow-600': totalScore >= 7 && totalScore <= 12,
                        'text-red-600': totalScore > 12
                    }" x-text="totalScore"></span>
                </div>

                <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <strong class="block mb-1">💡 Rekomendasi Intervensi:</strong>
                    <div x-show="totalScore <= 6"><strong>0–6:</strong> Lanjutkan tatalaksana & pemantauan rutin.</div>
                    <div x-show="totalScore >= 7 && totalScore <= 12"><strong>7–12:</strong> Berikan intervensi non-farmakologis (kenyamanan, sukrosa oral).</div>
                    <div x-show="totalScore > 12"><strong>>12:</strong> Pertimbangkan intervensi farmakologis (Parasetamol/Narkotik/Sedasi).</div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" wire:click="closeModal" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 active:scale-[0.98] transition transform">
                    💾 Simpan Skor PIPP
                </button>
            </div>
        </form>
    </div>
</div>
@endif
