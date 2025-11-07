<div x-data @validation-failed.window="
        $nextTick(() => {
            const firstError = document.querySelector('.border-danger-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus({ preventScroll: true });
            }
        })
    " class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    @if ($errors->any())
    <div class="bg-danger-50 dark:bg-danger-900 dark:bg-opacity-50
                border-l-4 border-danger-500 dark:border-danger-600
                text-danger-800 dark:text-danger-200
                p-4 mb-4" role="alert">
        <p class="font-bold">Error Validasi</p>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-danger-50 dark:bg-danger-900 dark:bg-opacity-50
                border-l-4 border-danger-500 dark:border-danger-600
                text-danger-800 dark:text-danger-200
                p-4 mb-4" role="alert">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @php
        $inputClasses = 'mt-1 block w-full rounded-lg shadow-sm sm:text-sm
                         border-gray-300 dark:border-gray-600
                         bg-white dark:bg-gray-700
                         text-gray-900 dark:text-gray-200
                         focus:border-primary-500 focus:ring-primary-500';
        $inputReadonlyClasses = 'mt-1 block w-full rounded-lg shadow-sm sm:text-sm
                                 border-gray-300 dark:border-gray-600
                                 bg-gray-100 dark:bg-gray-700
                                 text-gray-500 dark:text-gray-400
                                 cursor-not-allowed';
        $labelClasses = 'block text-sm font-medium text-gray-700 dark:text-gray-300';
        $errorClasses = 'text-danger-500 dark:text-danger-400 text-sm mt-1';
        $fieldsetClasses = 'bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 border border-gray-100 dark:border-gray-700';
        $legendClasses = 'px-2 font-semibold text-lg text-gray-800 dark:text-gray-100';
        $checkboxClasses = 'h-5 w-5 text-primary-600 dark:text-primary-500 border-gray-300 dark:border-gray-600 rounded focus:ring-primary-500';
    @endphp

    {{-- 2. Form Utama --}}
    <form wire:submit.prevent="save">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-3 sm:space-y-0
                        bg-white dark:bg-gray-800 shadow-md rounded-xl p-4 sm:p-5
                        border border-gray-100 dark:border-gray-700">

                {{-- Judul --}}
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-gray-100">
                    Formulir Monitoring Intra Anestesi
                </h1>

                {{-- Tombol Simpan (Aksi Utama) --}}
                <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center px-5 py-2
                               bg-primary-600 text-white font-semibold rounded-lg shadow
                               hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50
                               transition duration-150 ease-in-out disabled:opacity-50
                               dark:focus:ring-offset-gray-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" wire:loading.remove wire:target="save">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Simpan Formulir</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

            </div>

            <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <ol class="flex items-center w-full">
                    @php($steps = ['Staf', 'Waktu', 'Jln. Nafas', 'Vital', 'Obat', 'Regional'])
                    @foreach($steps as $index => $title)
                    @php($stepNumber = $index + 1)
                    <li class="flex w-full items-center
                               {{ $stepNumber < $totalSteps ? "after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 dark:after:border-gray-600 after:border-1" : '' }}">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full shrink-0
                                   {{ $currentStep == $stepNumber ? 'bg-primary-600 text-white' :
                                      ($currentStep > $stepNumber ? 'bg-green-600 text-white' :
                                      'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-300') }}">
                            {{ $stepNumber }}
                        </span>
                        <span class="ml-2 hidden sm:inline-block {{ $currentStep == $stepNumber ? 'font-semibold text-primary-600 dark:text-primary-300' : 'text-gray-700 dark:text-gray-400' }}">{{ $title }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

            @if ($currentStep == 1)
            {{-- 3. Bagian Data Pasien & Staf --}}
            <fieldset class="{{ $fieldsetClasses }}">
                <legend class="{{ $legendClasses }}">Langkah 1: Data Pasien & Staf</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                    <div>
                        <label class="{{ $labelClasses }}">No. Rekam Medis</label>
                        <input type="text" wire:model.defer="no_rekam_medis" class="{{ $inputReadonlyClasses }}" readonly>
                    </div>
                    <div>
                        <label class="{{ $labelClasses }}">Nama Lengkap</label>
                        <input type="text" wire:model.defer="nama_lengkap" class="{{ $inputReadonlyClasses }}" readonly>
                    </div>
                    <div>
                        <label class="{{ $labelClasses }}">Tanggal Lahir</label>
                        <input type="date" wire:model.defer="tanggal_lahir" class="{{ $inputReadonlyClasses }}" readonly>
                    </div>

                    <div>
                        <label for="kd_dokter_anestesi" class="{{ $labelClasses }}">Dokter Anestesi (DPJP)</label>
                        <select id="kd_dokter_anestesi" wire:model.defer="kd_dokter_anestesi" class="{{ $inputClasses }} @error('kd_dokter_anestesi') border-danger-500 @enderror">
                            <option value="">Pilih Dokter</option>
                            @foreach($allDokterAnestesi as $dokter)
                            <option value="{{ $dokter->kd_dokter }}">{{ $dokter->nm_dokter }}</option>
                            @endforeach
                        </select>
                        @error('kd_dokter_anestesi') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="nip_penata_anestesi" class="{{ $labelClasses }}">Penata Anestesi</label>
                        <select id="nip_penata_anestesi" wire:model.defer="nip_penata_anestesi" class="{{ $inputClasses }} @error('nip_penata_anestesi') border-danger-500 @enderror">
                            <option value="">Pilih Penata</option>
                            @foreach($allPenataAnestesi as $penata)
                            <option value="{{ $penata->nip }}">{{ $penata->nama }}</option>
                            @endforeach
                        </select>
                        @error('nip_penata_anestesi') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                    </div>

                </div>
            </fieldset>

            {{-- 4. Bagian Persiapan & Premedikasi --}}
            <fieldset class="{{ $fieldsetClasses }} mt-6">
                <legend class="{{ $legendClasses }}">Persiapan & Premedikasi</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <input type="text" wire:model.defer="infus_perifer_1_tempat_ukuran" placeholder="Infus Perifer 1 (Tempat & Ukuran)" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="infus_perifer_2_tempat_ukuran" placeholder="Infus Perifer 2 (Tempat & Ukuran)" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="cvc" placeholder="CVC" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="premedikasi_oral" placeholder="Premedikasi Oral" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="premedikasi_iv" placeholder="Premedikasi IV" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="induksi_intravena" placeholder="Induksi Intravena" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="induksi_inhalasi" placeholder="Induksi Inhalasi" class="{{ $inputClasses }}">
                    <select wire:model.defer="posisi" class="{{ $inputClasses }}">
                        <option value="">Pilih Posisi</option>
                        <option value="Terlentang">Terlentang</option>
                        <option value="Lithotomi">Lithotomi</option>
                        <option value="Prone">Prone</option>
                        <option value="Lateral Ka">Lateral Ka</option>
                        <option value="Lateral Ki">Lateral Ki</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                    <div class="flex items-center space-x-2 mt-1">
                        <input id="perlindungan_mata" wire:model.defer="perlindungan_mata" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="perlindungan_mata" class="text-sm text-gray-900 dark:text-gray-100">Perlindungan Mata</label>
                    </div>
                </div>
            </fieldset>
            @endif

            @if ($currentStep == 2)
            {{-- 5. Bagian Waktu (Penting) --}}
            <fieldset class="{{ $fieldsetClasses }}">
                <legend class="{{ $legendClasses }}">Langkah 2: Manajemen Waktu</legend>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="{{ $labelClasses }}">Mulai Anestesia (X)</label>
                        <input type="datetime-local" wire:model.defer="mulai_anestesia" class="{{ $inputClasses }} @error('mulai_anestesia') border-danger-500 @enderror">
                        @error('mulai_anestesia') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClasses }}">Selesai Anestesia (X)</label>
                        <input type="datetime-local" wire:model.defer="selesai_anestesia" class="{{ $inputClasses }}">
                    </div>
                    <div>
                        <label class="{{ $labelClasses }}">Mulai Pembedahan (O)</label>
                        <input type="datetime-local" wire:model.defer="mulai_pembedahan" class="{{ $inputClasses }} @error('mulai_pembedahan') border-danger-500 @enderror">
                        @error('mulai_pembedahan') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClasses }}">Selesai Pembedahan (O)</label>
                        <input type="datetime-local" wire:model.defer="selesai_pembedahan" class="{{ $inputClasses }}">
                    </div>
                </div>
            </fieldset>
            @endif

            @if ($currentStep == 3)
            {{-- 6. Bagian Jalan Nafas & Ventilasi --}}
            <fieldset class="{{ $fieldsetClasses }}">
                <legend class="{{ $legendClasses }}">Langkah 3: Jalan Nafas & Ventilasi</legend>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4">
                    <input type="text" wire:model.defer="jalan_nafas_facemask_no" placeholder="Face mask No." class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_ett_no" placeholder="ETT No." class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_ett_jenis" placeholder="ETT Jenis" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_ett_fiksasi_cm" placeholder="ETT Fiksasi (cm)" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_lma_no" placeholder="LMA No." class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_lma_jenis" placeholder="LMA Jenis" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="jalan_nafas_lain_lain" placeholder="Jalan Nafas Lain-lain" class="{{ $inputClasses }}">

                    <div class="flex items-center">
                        <input id="jalan_nafas_oro_nasopharing" wire:model.defer="jalan_nafas_oro_nasopharing" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="jalan_nafas_oro_nasopharing" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Oro/Nasopharing</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_trakheostomi" wire:model.defer="jalan_nafas_trakheostomi" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="jalan_nafas_trakheostomi" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Trakheostomi</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_bronkoskopi_fiberoptik" wire:model.defer="jalan_nafas_bronkoskopi_fiberoptik" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="jalan_nafas_bronkoskopi_fiberoptik" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Bronkoskopi Fiberoptik</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_glidescope" wire:model.defer="jalan_nafas_glidescope" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="jalan_nafas_glidescope" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Glidescope</label>
                    </div>

                    <hr class="col-span-full my-2 dark:border-gray-700">

                    {{-- Intubasi --}}
                    <select wire:model.defer="intubasi_kondisi" class="{{ $inputClasses }}">
                        <option value="">Kondisi Intubasi</option>
                        <option value="Sesudah tidur">Sesudah tidur</option>
                        <option value="Blind">Blind</option>
                    </select>
                    <select wire:model.defer="intubasi_jalan" class="{{ $inputClasses }}">
                        <option value="">Jalan Intubasi</option>
                        <option value="Oral">Oral</option>
                        <option value="Nasal Ka">Nasal Ka</option>
                        <option value="Nasal Ki">Nasal Ki</option>
                    </select>
                    <input type="text" wire:model.defer="intubasi_level_ett" placeholder="Level ETT" class="{{ $inputClasses }}">

                    <div class="flex items-center">
                        <input id="intubasi_dengan_stilet" wire:model.defer="intubasi_dengan_stilet" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="intubasi_dengan_stilet" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Dengan Stilet</label>
                    </div>
                    <div class="flex items-center">
                        <input id="intubasi_cuff" wire:model.defer="intubasi_cuff" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="intubasi_cuff" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Cuff</label>
                    </div>
                    <div class="flex items-center">
                        <input id="intubasi_pack" wire:model.defer="intubasi_pack" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="intubasi_pack" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Pack</label>
                    </div>
                    <div class="flex items-center">
                        <input id="sulit_ventilasi" wire:model.defer="sulit_ventilasi" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="sulit_ventilasi" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Sulit Ventilasi</label>
                    </div>
                    <div class="flex items-center">
                        <input id="sulit_intubasi" wire:model.defer="sulit_intubasi" type="checkbox" class="{{ $checkboxClasses }}">
                        <label for="sulit_intubasi" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Sulit Intubasi</label>
                    </div>

                    <hr class="col-span-full my-2 dark:border-gray-700">

                    {{-- Ventilasi --}}
                    <select wire:model.defer="ventilasi_mode" class="{{ $inputClasses }}">
                        <option value="">Mode Ventilasi</option>
                        <option value="Spontan">Spontan</option>
                        <option value="Kendali">Kendali</option>
                        <option value="Ventilator">Ventilator</option>
                    </select>
                    <input type="text" wire:model.defer="ventilator_tv" placeholder="Ventilator: TV" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="ventilator_rr" placeholder="Ventilator: RR" class="{{ $inputClasses }}">
                    <input type="text" wire:model.defer="ventilator_peep" placeholder="Ventilator: PEEP" class="{{ $inputClasses }}">
                </div>
            </fieldset>
            @endif

            @if ($currentStep == 4)
            {{-- 7. Bagian Vitals (DINAMIS) --}}
            <fieldset class="{{ $fieldsetClasses }}">
                <legend class="{{ $legendClasses }}">Langkah 4: Pemantauan Tanda Vital</legend>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Waktu</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Nadi (RRN)</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">TD-Sis</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">TD-Dis</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">RR</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">SpO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">PE CO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">FiO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Lain-lain</th>
                                <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            @forelse ($vitals as $index => $vital)
                            <tr wire:key="vital-{{ $index }}" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td>
                                    <input type="time" wire:model.defer="vitals.{{ $index }}.waktu" class="w-24 text-sm {{ $inputClasses }} @error('vitals.'.$index.'.waktu') border-danger-500 @enderror">
                                </td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.rrn" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.td_sis" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.td_dis" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.rr" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.spo2" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.pe_co2" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.fio2" class="w-20 text-sm {{ $inputClasses }}"></td>
                                <td><input type="text" wire:model.defer="vitals.{{ $index }}.lain_lain" class="w-28 text-sm {{ $inputClasses }}"></td>
                                <td class="text-center">
                                    @if($loop->count > 1)
                                    <button type="button" wire:click.prevent="removeVital({{ $index }})" class="text-danger-600 dark:text-danger-400 hover:text-danger-800 dark:hover:text-danger-300">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                            <tr wire:key="vital-error-{{ $index }}">
                                <td colspan="10" class="py-0 px-2">
                                    @error('vitals.'.$index.'.waktu') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray-500 dark:text-gray-400 py-3">Tidak ada data vital. Klik 'Tambah Baris Vital' untuk memulai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" wire:click.prevent="addVital"
                        class="mt-3 px-3 py-1 bg-primary-600 text-white text-sm rounded-md
                               hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    + Tambah Baris Vital
                </button>
            </fieldset>
            @endif

            @if ($currentStep == 5)
            {{-- 8. Bagian Obat (DINAMIS) --}}
            <fieldset class="{{ $fieldsetClasses }}">
                <legend class="{{ $legendClasses }}">Langkah 5: Obat-obatan / Infus / Gas</legend>
                <table class="min-w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300 text-left">Waktu</th>
                            <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300 text-left">Nama Obat/Infus/Gas</th>
                            <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300 text-left">Dosis</th>
                            <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300 text-left">Rute</th>
                            <th class="px-2 py-2 text-xs text-gray-500 dark:text-gray-300 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @forelse ($medications as $index => $med)
                        <tr wire:key="med-{{ $index }}" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td>
                                <input type="time" wire:model.defer="medications.{{ $index }}.waktu" class="w-24 text-sm {{ $inputClasses }} @error('medications.'.$index.'.waktu') border-danger-500 @enderror">
                            </td>
                            <td>
                                <livewire:drug-search-autocomplete wire:key="med-search-{{ $index }}" :index="$index" :initial-value="$med['nama_obat_infus_gas']" />
                                <input type="hidden" wire:model.defer="medications.{{ $index }}.nama_obat_infus_gas">
                            </td>
                            <td>
                                <input type="text" wire:model.defer="medications.{{ $index }}.dosis" class="w-32 text-sm {{ $inputClasses }}">
                            </td>
                            <td>
                                <input type="text" wire:model.defer="medications.{{ $index }}.rute" class="w-24 text-sm {{ $inputClasses }}" placeholder="cth: IV">
                            </td>
                            <td class="text-center">
                                @if($loop->count > 1)
                                <button type="button" wire:click.prevent="removeMedication({{ $index }})" class="text-danger-600 dark:text-danger-400 hover:text-danger-800 dark:hover:text-danger-300">Hapus</button>
                                @endif
                            </td>
                        </tr>
                        <tr wire:key="med-error-{{ $index }}">
                            <td class="py-0 px-2">
                                @error('medications.'.$index.'.waktu') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </td>
                            <td class="py-0 px-2">
                                @error('medications.'.$index.'.nama_obat_infus_gas') <span class="{{ $errorClasses }}">{{ $message }}</span> @enderror
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 dark:text-gray-400 py-3">Tidak ada data obat. Klik 'Tambah Baris Obat' untuk memulai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <button type="button" wire:click.prevent="addMedication"
                        class="mt-3 px-3 py-1 bg-primary-600 text-white text-sm rounded-md
                               hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    + Tambah Baris Obat
                </button>
            </fieldset>
            @endif

            @if ($currentStep == 6)
            {{-- 9. Bagian Akhir (Regional, Total Cairan, Catatan) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <fieldset class="{{ $fieldsetClasses }}">
                    <legend class="{{ $legendClasses }}">Langkah 6: Teknik Regional / Blok Perifer</legend>
                    <div class="space-y-3 mt-4">
                        <input type="text" wire:model.defer="regional_jenis" placeholder="Jenis" class="{{ $inputClasses }}">
                        <input type="text" wire:model.defer="regional_lokasi" placeholder="Lokasi" class="{{ $inputClasses }}">
                        <input type="text" wire:model.defer="regional_jenis_jarum_no" placeholder="Jenis Jarum / No." class="{{ $inputClasses }}">
                        <div class="flex items-center">
                            <input id="regional_kateter" wire:model.defer="regional_kateter" type="checkbox" class="{{ $checkboxClasses }}">
                            <label for="regional_kateter" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Kateter? (Ya)</label>
                        </div>
                        <input type="text" wire:model.defer="regional_fiksasi_cm" placeholder="Fiksasi (cm)" class="{{ $inputClasses }}">
                        <textarea wire:model.defer="regional_obat_obat" placeholder="Obat-obat" rows="3" class="{{ $inputClasses }}"></textarea>
                        <textarea wire:model.defer="regional_komplikasi" placeholder="Komplikasi" rows="3" class="{{ $inputClasses }}"></textarea>
                        <select wire:model.defer="regional_hasil" class="{{ $inputClasses }}">
                            <option value="">Hasil</option>
                            <option value="Total Blok">Total Blok</option>
                            <option value="Partial">Partial</option>
                            <option value="Batal">Batal</option>
                        </select>
                    </div>
                </fieldset>

                <div class="space-y-6">
                    <fieldset class="{{ $fieldsetClasses }}">
                        <legend class="{{ $legendClasses }}">Total & Lama Pembedahan</legend>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <input type="number" wire:model.defer="total_cairan_infus_ml" placeholder="Total Cairan Infus (ml)" class="{{ $inputClasses }}">
                            <input type="number" wire:model.defer="total_darah_ml" placeholder="Total Darah (ml)" class="{{ $inputClasses }}">
                            <input type="number" wire:model.defer="total_urin_ml" placeholder="Total Urin (ml)" class="{{ $inputClasses }}">
                            <input type="number" wire:model.defer="total_perdarahan_ml" placeholder="Total Perdarahan (ml)" class="{{ $inputClasses }}">
                            <input type="text" wire:model.defer="lama_pembiusan" placeholder="Lama Pembiusan (cth: 1 jam 30 mnt)" class="{{ $inputClasses }}">
                            <input type="text" wire:model.defer="lama_pembedahan" placeholder="Lama Pembedahan (cth: 1 jam 15 mnt)" class="{{ $inputClasses }}">
                        </div>
                    </fieldset>

                    <fieldset class="{{ $fieldsetClasses }}">
                        <legend class="{{ $legendClasses }}">Masalah Intra Anestesi</legend>
                        <textarea wire:model.defer="masalah_intra_anestesi" placeholder="Tuliskan masalah intra anestesi di sini..." rows="5" class="{{ $inputClasses }} mt-4"></textarea>
                    </fieldset>
                </div>
            </div>
            @endif

            {{-- Tombol Navigasi Bawah --}}
            <div class="flex justify-between mt-8">
                @if ($currentStep > 1)
                <button type="button" wire:click="previousStep"
                        class="px-6 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg shadow-md
                               hover:bg-gray-700 dark:hover:bg-gray-600
                               focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    Kembali
                </button>
                @else
                <div></div> {{-- Placeholder agar 'Next' tetap di kanan --}}
                @endif

                @if ($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg shadow-md
                               hover:bg-primary-700
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <span wire:loading.remove wire:target="nextStep">Lanjut</span>
                    <span wire:loading wire:target="nextStep">Memvalidasi...</span>
                </button>
                @endif

                @if ($currentStep == $totalSteps)
                <button type="submit" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg shadow-md
                               hover:bg-green-700
                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2
                               dark:focus:ring-offset-gray-800">
                    <span wire:loading.remove wire:target="save">Simpan Formulir</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
                @endif
            </div>
        </div>
    </form>
</div>
