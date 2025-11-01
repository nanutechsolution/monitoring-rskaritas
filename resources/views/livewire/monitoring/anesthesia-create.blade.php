<div x-data @validation-failed.window="
        $nextTick(() => {
            const firstError = document.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus({ preventScroll: true });
            }
        })
    " class="max-w-7xl mx-auto">
    {{-- 1. Ringkasan Error Validasi (Masih berguna untuk error non-field) --}}
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Error Validasi</p>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
    @endif


    {{-- 2. Form Utama --}}
    <form wire:submit.prevent="save">
        <div class="bg-white shadow-xl rounded-lg p-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-3 sm:space-y-0 bg-white shadow-md rounded-xl p-4 sm:p-5">

                {{-- Judul --}}
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Formulir Monitoring Intra Anestesi
                </h1>

                {{-- Tombol Simpan --}}
                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out disabled:opacity-50">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" wire:loading.remove wire:target="save">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save">Simpan Formulir</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>

            </div>
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                <ol class="flex items-center w-full">
                    @php($steps = ['Staf', 'Waktu', 'Jln. Nafas', 'Vital', 'Obat', 'Regional'])
                    @foreach($steps as $index => $title)
                    @php($stepNumber = $index + 1)
                    <li class="flex w-full items-center {{ $stepNumber < $totalSteps ? "after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1" : '' }}">
                        <span class="flex items-center justify-center w-10 h-10 rounded-full shrink-0
                                {{ $currentStep == $stepNumber ? 'bg-blue-600 text-white' : ($currentStep > $stepNumber ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-500') }}">
                            {{ $stepNumber }}
                        </span>
                        <span class="ml-2 hidden sm:inline-block {{ $currentStep == $stepNumber ? 'font-semibold text-blue-600' : '' }}">{{ $title }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>
            @if ($currentStep == 1)
            {{-- 3. Bagian Data Pasien & Staf --}}
            <fieldset class="bg-white shadow-md rounded-xl p-6 mb-6 border border-gray-100">
                <legend class="px-2 font-semibold text-lg text-gray-800">Langkah 1: Data Pasien & Staf</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

                    {{-- No. Rekam Medis --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Rekam Medis</label>
                        <input type="text" wire:model.defer="no_rekam_medis" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" wire:model.defer="nama_lengkap" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" wire:model.defer="tanggal_lahir" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm bg-gray-100 cursor-not-allowed" readonly>
                    </div>

                    {{-- Dokter Anestesi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Dokter Anestesi (DPJP)</label>
                        <select wire:model.defer="kd_dokter_anestesi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('kd_dokter_anestesi') border-red-500 @enderror">
                            <option value="">Pilih Dokter</option>
                            @foreach($allDokterAnestesi as $dokter)
                            <option value="{{ $dokter->kd_dokter }}">{{ $dokter->nm_dokter }}</option>
                            @endforeach
                        </select>
                        @error('kd_dokter_anestesi')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Penata Anestesi --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penata Anestesi</label>
                        <select wire:model.defer="nip_penata_anestesi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nip_penata_anestesi') border-red-500 @enderror">
                            <option value="">Pilih Penata</option>
                            @foreach($allPenataAnestesi as $penata)
                            <option value="{{ $penata->nip }}">{{ $penata->nama }}</option>
                            @endforeach
                        </select>
                        @error('nip_penata_anestesi')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </fieldset>

            {{-- 4. Bagian Persiapan & Premedikasi --}}
            <fieldset class="bg-white shadow-md rounded-xl p-6 mb-6 border border-gray-100">
                <legend class="px-2 font-semibold text-lg text-gray-800">Persiapan & Premedikasi</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <input type="text" wire:model.defer="infus_perifer_1_tempat_ukuran" placeholder="Infus Perifer 1 (Tempat & Ukuran)" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="infus_perifer_2_tempat_ukuran" placeholder="Infus Perifer 2 (Tempat & Ukuran)" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="cvc" placeholder="CVC" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="premedikasi_oral" placeholder="Premedikasi Oral" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="premedikasi_iv" placeholder="Premedikasi IV" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="induksi_intravena" placeholder="Induksi Intravena" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" wire:model.defer="induksi_inhalasi" placeholder="Induksi Inhalasi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <select wire:model.defer="posisi" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Pilih Posisi</option>
                        <option value="Terlentang">Terlentang</option>
                        <option value="Lithotomi">Lithotomi</option>
                        <option value="Prone">Prone</option>
                        <option value="Lateral Ka">Lateral Ka</option>
                        <option value="Lateral Ki">Lateral Ki</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                    <div class="flex items-center space-x-2 mt-1">
                        <input id="perlindungan_mata" wire:model.defer="perlindungan_mata" type="checkbox" class="h-5 w-5 text-indigo-600 border-gray-300 rounded">
                        <label for="perlindungan_mata" class="text-sm text-gray-900">Perlindungan Mata</label>
                    </div>
                </div>
            </fieldset>
            @endif


            @if ($currentStep == 2)
            {{-- 5. Bagian Waktu (Penting) - DENGAN VALIDASI --}}
            <fieldset class="border rounded-md p-4 mb-6">
                <legend class="px-2 font-semibold text-lg">Manajemen Waktu</legend>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mulai Anestesia (X)</label>
                        <input type="datetime-local" wire:model.defer="mulai_anestesia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('mulai_anestesia') border-red-500 @enderror">
                        @error('mulai_anestesia')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Selesai Anestesia (X)</label>
                        <input type="datetime-local" wire:model.defer="selesai_anestesia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mulai Pembedahan (O)</label>
                        <input type="datetime-local" wire:model.defer="mulai_pembedahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('mulai_pembedahan') border-red-500 @enderror">
                        @error('mulai_pembedahan')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Selesai Pembedahan (O)</label>
                        <input type="datetime-local" wire:model.defer="selesai_pembedahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </fieldset>
            @endif
            @if ($currentStep == 3)
            {{-- 6. Bagian Jalan Nafas & Ventilasi --}}
            <fieldset class="border rounded-md p-4 mb-6">
                <legend class="px-2 font-semibold text-lg">Jalan Nafas & Ventilasi</legend>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4">
                    {{-- Jalan Nafas --}}
                    <input type="text" wire:model.defer="jalan_nafas_facemask_no" placeholder="Face mask No." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_ett_no" placeholder="ETT No." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_ett_jenis" placeholder="ETT Jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_ett_fiksasi_cm" placeholder="ETT Fiksasi (cm)" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_lma_no" placeholder="LMA No." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_lma_jenis" placeholder="LMA Jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="jalan_nafas_lain_lain" placeholder="Jalan Nafas Lain-lain" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                    <div class="flex items-center">
                        <input id="jalan_nafas_oro_nasopharing" wire:model.defer="jalan_nafas_oro_nasopharing" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="jalan_nafas_oro_nasopharing" class="ml-2 block text-sm text-gray-900">Oro/Nasopharing</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_trakheostomi" wire:model.defer="jalan_nafas_trakheostomi" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="jalan_nafas_trakheostomi" class="ml-2 block text-sm text-gray-900">Trakheostomi</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_bronkoskopi_fiberoptik" wire:model.defer="jalan_nafas_bronkoskopi_fiberoptik" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="jalan_nafas_bronkoskopi_fiberoptik" class="ml-2 block text-sm text-gray-900">Bronkoskopi Fiberoptik</label>
                    </div>
                    <div class="flex items-center">
                        <input id="jalan_nafas_glidescope" wire:model.defer="jalan_nafas_glidescope" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="jalan_nafas_glidescope" class="ml-2 block text-sm text-gray-900">Glidescope</label>
                    </div>

                    <hr class="col-span-full my-2">

                    {{-- Intubasi --}}
                    <select wire:model.defer="intubasi_kondisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Kondisi Intubasi</option>
                        <option value="Sesudah tidur">Sesudah tidur</option>
                        <option value="Blind">Blind</option>
                    </select>
                    <select wire:model.defer="intubasi_jalan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Jalan Intubasi</option>
                        <option value="Oral">Oral</option>
                        <option value="Nasal Ka">Nasal Ka</option>
                        <option value="Nasal Ki">Nasal Ki</option>
                    </select>
                    <input type="text" wire:model.defer="intubasi_level_ett" placeholder="Level ETT" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                    <div class="flex items-center">
                        <input id="intubasi_dengan_stilet" wire:model.defer="intubasi_dengan_stilet" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="intubasi_dengan_stilet" class="ml-2 block text-sm text-gray-900">Dengan Stilet</label>
                    </div>
                    <div class="flex items-center">
                        <input id="intubasi_cuff" wire:model.defer="intubasi_cuff" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="intubasi_cuff" class="ml-2 block text-sm text-gray-900">Cuff</label>
                    </div>
                    <div class="flex items-center">
                        <input id="intubasi_pack" wire:model.defer="intubasi_pack" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="intubasi_pack" class="ml-2 block text-sm text-gray-900">Pack</label>
                    </div>
                    <div class="flex items-center">
                        <input id="sulit_ventilasi" wire:model.defer="sulit_ventilasi" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="sulit_ventilasi" class="ml-2 block text-sm text-gray-900">Sulit Ventilasi</label>
                    </div>
                    <div class="flex items-center">
                        <input id="sulit_intubasi" wire:model.defer="sulit_intubasi" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="sulit_intubasi" class="ml-2 block text-sm text-gray-900">Sulit Intubasi</label>
                    </div>

                    <hr class="col-span-full my-2">

                    {{-- Ventilasi --}}
                    <select wire:model.defer="ventilasi_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Mode Ventilasi</option>
                        <option value="Spontan">Spontan</option>
                        <option value="Kendali">Kendali</option>
                        <option value="Ventilator">Ventilator</option>
                    </select>
                    <input type="text" wire:model.defer="ventilator_tv" placeholder="Ventilator: TV" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="ventilator_rr" placeholder="Ventilator: RR" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <input type="text" wire:model.defer="ventilator_peep" placeholder="Ventilator: PEEP" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </fieldset>
            @endif

            @if ($currentStep == 4)
            @if (!empty($vitals) && count($vitals) > 0)
            <fieldset class="border rounded-lg p-6 bg-white shadow-md">
                <legend class="px-2 text-xl font-semibold text-gray-700">Pemantauan Vital Pasien</legend>

                <div class="mt-4">
                    <div wire:ignore x-data x-init="
            const labels = @js($chartLabels ?? []);
            const nadi = @js($chartDataNadi ?? []);
            const sistolik = @js($chartDataSistolik ?? []);
            const diastolik = @js($chartDataDiastolik ?? []);
            const rr = @js($chartDataRR ?? []);

            const ctx = document.getElementById('anesthesiaChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Nadi (RRN)', data: nadi, borderColor: '#EF4444', backgroundColor: 'rgba(239,68,68,0.2)', tension: 0.2, fill: true },
                        { label: 'Sistolik', data: sistolik, borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.2)', tension: 0.2, fill: true },
                        { label: 'Diastolik', data: diastolik, borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.2)', tension: 0.2, fill: true },
                        { label: 'RR', data: rr, borderColor: '#F59E0B', backgroundColor: 'rgba(245,158,11,0.2)', tension: 0.2, fill: true }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 14 } } },
                        title: { display: true, text: 'Grafik Vital Pasien', font: { size: 16 } }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            title: { display: true, text: 'Nilai' }
                        },
                        x: {
                            title: { display: true, text: 'Waktu' }
                        }
                    }
                }
            });
        ">
                        <canvas id="anesthesiaChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </fieldset>
            @endif

            {{-- 7. Bagian Vitals (DINAMIS) - DENGAN VALIDASI --}}
            <fieldset class="border rounded-md p-4 mb-6">
                <legend class="px-2 font-semibold text-lg">Pemantauan Tanda Vital</legend>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-2 text-xs text-gray-500">Waktu</th>
                                <th class="px-2 py-2 text-xs text-gray-500">Nadi (RRN)</th>
                                <th class="px-2 py-2 text-xs text-gray-500">TD-Sis</th>
                                <th class="px-2 py-2 text-xs text-gray-500">TD-Dis</th>
                                <th class="px-2 py-2 text-xs text-gray-500">RR</th>
                                <th class="px-2 py-2 text-xs text-gray-500">SpO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500">PE CO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500">FiO2</th>
                                <th class="px-2 py-2 text-xs text-gray-500">Lain-lain</th>
                                <th class="px-2 py-2 text-xs text-gray-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($vitals as $index => $vital)
                            <tr wire:key="vital-{{ $index }}">
                                <td>
                                    <input type="time" wire:model.defer="vitals.{{ $index }}.waktu" class="w-24 text-sm rounded-md border-gray-300 shadow-sm @error('vitals.'.$index.'.waktu') border-red-500 @enderror">
                                </td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.rrn" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.td_sis" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.td_dis" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.rr" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.spo2" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.pe_co2" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="number" wire:model.defer="vitals.{{ $index }}.fio2" class="w-20 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td><input type="text" wire:model.defer="vitals.{{ $index }}.lain_lain" class="w-28 text-sm rounded-md border-gray-300 shadow-sm"></td>
                                <td>
                                    @if($loop->count > 1) {{-- Hanya tampilkan tombol hapus jika ada lebih dari 1 baris --}}
                                    <button type="button" wire:click.prevent="removeVital({{ $index }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    @endif
                                </td>
                            </tr>
                            {{-- Pesan error inline untuk baris vital --}}
                            <tr>
                                <td colspan="10">
                                    @error('vitals.'.$index.'.waktu')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-gray-500 py-3">Tidak ada data vital. Klik 'Tambah Baris Vital' untuk memulai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <button type="button" wire:click.prevent="addVital" class="mt-3 px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600">+ Tambah Baris Vital</button>
            </fieldset>
            @endif

            @if ($currentStep == 5)
            {{-- 8. Bagian Obat (DINAMIS) --}}
            <fieldset class="border rounded-md p-4 mb-6">
                <legend class="px-2 font-semibold text-lg">Obat-obatan / Infus / Gas</legend>
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-xs text-gray-500 text-left">Waktu</th>
                            <th class="px-2 py-2 text-xs text-gray-500 text-left">Nama Obat/Infus/Gas</th>
                            <th class="px-2 py-2 text-xs text-gray-500 text-left">Dosis</th>
                            <th class="px-2 py-2 text-xs text-gray-500 text-left">Rute</th>
                            <th class="px-2 py-2 text-xs text-gray-500 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($medications as $index => $med)
                        <tr wire:key="med-{{ $index }}">

                            {{-- 1. Kolom Waktu --}}
                            <td>
                                <input type="time" wire:model.defer="medications.{{ $index }}.waktu" class="w-24 text-sm rounded-md border-gray-300 shadow-sm">
                            </td>

                            {{-- 2. Kolom Nama Obat (INI YANG BENAR) --}}
                            <td>
                                {{-- Panggil komponen child Anda --}}
                                <livewire:drug-search-autocomplete wire:key="med-search-{{ $index }}" :index="$index" :initial-value="$med['nama_obat_infus_gas']" />

                                {{-- Input tersembunyi ini untuk menerima nilai dari komponen child --}}
                                <input type="hidden" wire:model.defer="medications.{{ $index }}.nama_obat_infus_gas">
                            </td>

                            {{-- 3. Kolom Dosis --}}
                            <td>
                                <input type="text" wire:model.defer="medications.{{ $index }}.dosis" class="w-32 text-sm rounded-md border-gray-300 shadow-sm">
                            </td>

                            {{-- 4. Kolom Rute --}}
                            <td>
                                <input type="text" wire:model.defer="medications.{{ $index }}.rute" class="w-24 text-sm rounded-md border-gray-300 shadow-sm" placeholder="cth: IV">
                            </td>

                            {{-- 5. Kolom Aksi --}}
                            <td>
                                @if($loop->count > 1)
                                <button type="button" wire:click.prevent="removeMedication({{ $index }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                @endif
                            </td>
                        </tr>

                        <tr wire:key="med-error-{{ $index }}">
                            <td class="py-0 px-2">
                                @error('medications.'.$index.'.waktu')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            <td class="py-0 px-2">
                                @error('medications.'.$index.'.nama_obat_infus_gas')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            <td colspan="3"></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-3">Tidak ada data obat. Klik 'Tambah Baris Obat' untuk memulai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <button type="button" wire:click.prevent="addMedication" class="mt-3 px-3 py-1 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600">+ Tambah Baris Obat</button>
            </fieldset>
            @endif
            @if ($currentStep == 6)
            {{-- 9. Bagian Akhir (Regional, Total Cairan, Catatan) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri: Regional --}}
                <fieldset class="border rounded-md p-4">
                    <legend class="px-2 font-semibold text-lg">Teknik Regional / Blok Perifer</legend>
                    <div class="space-y-3">
                        <input type="text" wire:model.defer="regional_jenis" placeholder="Jenis" class="block w-full rounded-md border-gray-300 shadow-sm">
                        <input type="text" wire:model.defer="regional_lokasi" placeholder="Lokasi" class="block w-full rounded-md border-gray-300 shadow-sm">
                        <input type="text" wire:model.defer="regional_jenis_jarum_no" placeholder="Jenis Jarum / No." class="block w-full rounded-md border-gray-300 shadow-sm">
                        <div class="flex items-center">
                            <input id="regional_kateter" wire:model.defer="regional_kateter" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="regional_kateter" class="ml-2 block text-sm text-gray-900">Kateter? (Ya)</label>
                        </div>
                        <input type="text" wire:model.defer="regional_fiksasi_cm" placeholder="Fiksasi (cm)" class="block w-full rounded-md border-gray-300 shadow-sm">
                        <textarea wire:model.defer="regional_obat_obat" placeholder="Obat-obat" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        <textarea wire:model.defer="regional_komplikasi" placeholder="Komplikasi" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        <select wire:model.defer="regional_hasil" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Hasil</option>
                            <option value="Total Blok">Total Blok</option>
                            <option value="Partial">Partial</option>
                            <option value="Batal">Batal</option>
                        </select>
                    </div>
                </fieldset>

                {{-- Kolom Kanan: Total & Catatan --}}
                <div class="space-y-6">
                    <fieldset class="border rounded-md p-4">
                        <legend class="px-2 font-semibold text-lg">Total & Lama Pembedahan</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="number" wire:model.defer="total_cairan_infus_ml" placeholder="Total Cairan Infus (ml)" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <input type="number" wire:model.defer="total_darah_ml" placeholder="Total Darah (ml)" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <input type="number" wire:model.defer="total_urin_ml" placeholder="Total Urin (ml)" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <input type="number" wire:model.defer="total_perdarahan_ml" placeholder="Total Perdarahan (ml)" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <input type="text" wire:model.defer="lama_pembiusan" placeholder="Lama Pembiusan (cth: 1 jam 30 mnt)" class="block w-full rounded-md border-gray-300 shadow-sm">
                            <input type="text" wire:model.defer="lama_pembedahan" placeholder="Lama Pembedahan (cth: 1 jam 15 mnt)" class="block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </fieldset>

                    <fieldset class="border rounded-md p-4">
                        <legend class="px-2 font-semibold text-lg">Masalah Intra Anestesi</legend>
                        <textarea wire:model.defer="masalah_intra_anestesi" placeholder="Tuliskan masalah intra anestesi di sini..." rows="5" class="block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    </fieldset>
                </div>
            </div>
            @endif
            {{-- Tombol Simpan Bawah --}}
            <div class="flex justify-between mt-8">
                {{-- Tombol "Previous" --}}
                @if ($currentStep > 1)
                <button type="button" wire:click="previousStep" class="px-6 py-2 bg-gray-600 text-white rounded-lg shadow-md hover:bg-gray-700">
                    Kembali
                </button>
                @else
                <div></div> {{-- Placeholder agar 'Next' tetap di kanan --}}
                @endif

                {{-- Tombol "Next" --}}
                @if ($currentStep < $totalSteps) <button type="button" wire:click="nextStep" wire:loading.attr="disabled" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700">
                    <span wire:loading.remove wire:target="nextStep">Lanjut</span>
                    <span wire:loading wire:target="nextStep">Memvalidasi...</span>
                    </button>
                    @endif

                    {{-- Tombol "Simpan" (Hanya di langkah terakhir) --}}
                    @if ($currentStep == $totalSteps)
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700">
                        <span wire:loading.remove wire:target="save">Simpan Formulir</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </button>
                    @endif
            </div>
        </div>
    </form>
</div>
