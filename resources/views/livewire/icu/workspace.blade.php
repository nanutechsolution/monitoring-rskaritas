<div class="container mx-auto p-4 sm:p-6 space-y-6">

    <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 border-l-4 border-blue-600">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
            <div>
                <h2 class="text-xl sm:text-3xl font-bold text-gray-800 leading-snug">
                    {{ $registrasi->pasien->nm_pasien }}
                </h2>
                <p class="text-sm sm:text-lg text-gray-600">
                    <span class="font-semibold">No. RM:</span> {{ $registrasi->pasien->no_rkm_medis }} |
                    <span class="font-semibold">No. Rawat:</span> {{ $registrasi->no_rawat }}
                </p>
                <div class="mt-1 sm:mt-2">
                    <span class="font-semibold text-sm sm:text-lg text-gray-700">Lembar Observasi:</span>
                    <span class="text-sm sm:text-lg text-blue-700 font-bold">{{ $cycle->sheet_date->isoFormat('dddd, D MMMM Y') }}</span>
                    <span class="ml-1 sm:ml-2 px-1.5 sm:px-2 py-0.5 text-[10px] sm:text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                        Hari Rawat ke-{{ $cycle->hari_rawat_ke }}
                    </span>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <a href="{{ route('monitoring.icu.history', ['noRawat' => str_replace('/', '_', $registrasi->no_rawat)]) }}" wire:navigate class="text-xs sm:text-sm text-gray-600 hover:text-blue-600">
                    &larr; Kembali ke Riwayat Pasien
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-2 flex flex-wrap gap-2 sm:space-x-2">
        <button wire:click="$set('activeTab', 'input')" class="flex-1 sm:flex-none px-3 sm:px-5 py-2 rounded-md text-xs sm:text-sm font-medium {{ $activeTab == 'input' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Input Real-time
        </button>
        <button wire:click="$set('activeTab', 'laporan')" class="flex-1 sm:flex-none px-3 sm:px-5 py-2 rounded-md text-xs sm:text-sm font-medium {{ $activeTab == 'laporan' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Laporan & Grafik
        </button>
        <button wire:click="$set('activeTab', 'statis')" class="flex-1 sm:flex-none px-3 sm:px-5 py-2 rounded-md text-xs sm:text-sm font-medium {{ $activeTab == 'statis' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
            Data Statis
        </button>
    </div>

    <div class="mt-6">

        <div>
            @if ($activeTab == 'input')
            <livewire:icu.monitor-sheet :cycle="$cycle" :key="'input-'.$cycle->id" lazy />

            @elseif ($activeTab == 'laporan')

            <livewire:icu.observation-grid :cycle="$cycle" :key="'laporan-'.$cycle->id" lazy />

            @elseif ($activeTab == 'statis')
            {{-- KONTEN TAB DATA STATIS --}}
            <div class="bg-white shadow rounded-lg">
                <form wire:submit.prevent="saveStaticData">

                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Data Statis Harian</h3>
                        <p class="text-sm text-gray-600">Mengisi data terapi, penunjang, catatan, alat, tube, dll.</p>
                    </div>

                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        {{-- Input IWL --}}
                        <div>
                            <label for="daily_iwl" class="block text-sm font-medium text-gray-700">Daily IWL (ml / 24 jam)</label>
                            <input type="number" step="0.1" wire:model.defer="staticState.daily_iwl" id="daily_iwl" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('staticState.daily_iwl') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <hr>

                        {{-- Textarea Terapi Parenteral --}}
                        <div>
                            <label for="terapi_parenteral" class="block text-sm font-medium text-gray-700">Terapi Obat (Parenteral)</label>
                            <textarea wire:model.defer="staticState.terapi_obat_parenteral" id="terapi_parenteral" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.terapi_obat_parenteral') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Textarea Terapi Enteral --}}
                        <div>
                            <label for="terapi_enteral" class="block text-sm font-medium text-gray-700">Terapi Obat (Enteral / Lain-lain)</label>
                            <textarea wire:model.defer="staticState.terapi_obat_enteral_lain" id="terapi_enteral" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.terapi_obat_enteral_lain') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Textarea Pemeriksaan Penunjang --}}
                        <div>
                            <label for="pemeriksaan_penunjang" class="block text-sm font-medium text-gray-700">Pemeriksaan Penunjang (Lab, EKG, dll)</label>
                            <textarea wire:model.defer="staticState.pemeriksaan_penunjang" id="pemeriksaan_penunjang" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.pemeriksaan_penunjang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Textarea Catatan Lain-lain --}}
                        <div>
                            <label for="catatan_lain_lain" class="block text-sm font-medium text-gray-700">Catatan Lain-lain</label>
                            <textarea wire:model.defer="staticState.catatan_lain_lain" id="catatan_lain_lain" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.catatan_lain_lain') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <hr>

                        {{-- Textarea Alat Terpasang --}}
                        <div>
                            <label for="alat_terpasang" class="block text-sm font-medium text-gray-700">ALAT (IV Line, Arteri, CVP, dll)</label>
                            <textarea wire:model.defer="staticState.alat_terpasang" id="alat_terpasang" rows="4" placeholder="cth: IV Line, Tangan Kiri, 27/10/2025" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.alat_terpasang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Textarea Tube Terpasang --}}
                        <div>
                            <label for="tube_terpasang" class="block text-sm font-medium text-gray-700">TUBE (ET, NGT, Kateter, dll)</label>
                            <textarea wire:model.defer="staticState.tube_terpasang" id="tube_terpasang" rows="4" placeholder="cth: Urine Kateter, 27/10/2025, No. 16" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.tube_terpasang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <hr>

                        {{-- Textarea Masalah Keperawatan --}}
                        <div>
                            <label for="masalah_keperawatan" class="block text-sm font-medium text-gray-700">MASALAH (KU, Lab, dll)</label>
                            <textarea wire:model.defer="staticState.masalah_keperawatan" id="masalah_keperawatan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.masalah_keperawatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Textarea Tindakan Obat --}}
                        <div>
                            <label for="tindakan_obat" class="block text-sm font-medium text-gray-700">TINDAKAN (OBAT)</label>
                            <textarea wire:model.defer="staticState.tindakan_obat" id="tindakan_obat" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('staticState.tindakan_obat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="p-4 bg-gray-50 rounded-b-lg flex justify-between items-center">
                        {{-- Notifikasi Sukses --}}
                        <div>
                            @if (session()->has('message-statis'))
                            <span class="text-green-600 text-sm font-medium">
                                {{ session('message-statis') }}
                            </span>
                            @endif
                        </div>
                        {{-- Tombol Simpan --}}
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow text-sm font-medium hover:bg-blue-700">
                            Simpan Data Statis
                        </button>
                    </div>

                </form>
            </div>
            @endif
        </div>
    </div>
</div>
