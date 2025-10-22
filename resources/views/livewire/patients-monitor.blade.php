<div>
 <x-slot name="header">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-2 text-sm text-gray-700">
        {{-- Kolom 1: Info Pasien --}}
        <div class="space-y-1">
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ $patient->nm_pasien }} <span class="text-base font-normal text-gray-600">(RM: {{ $patient->no_rkm_medis }})</span>
            </h2>
            <div><span class="font-medium w-28 inline-block">Tgl Lahir:</span> {{ \Carbon\Carbon::parse($patient->tgl_lahir)->isoFormat('D MMM YYYY') }}</div>
            <div><span class="font-medium w-28 inline-block">Jenis Kelamin:</span> {{ $patient->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
            <div><span class="font-medium w-28 inline-block">Berat Lahir:</span> {{ $berat_lahir ?? 'N/A' }} {{ $berat_lahir ? 'gram' : '' }}</div>
            <div><span class="font-medium w-28 inline-block">Persalinan:</span> {{ $cara_persalinan ?? 'N/A' }}</div>
        </div>

        {{-- Kolom 2: Info Usia & Rawat --}}
        <div class="space-y-1">
             {{-- <div><span class="font-medium w-28 inline-block">Usia Kehamilan:</span> {{ $umur_kehamilan ?? 'N/A' }} minggu</div> --}}
             <div><span class="font-medium w-28 inline-block">Umur Bayi:</span> {{ $umur_bayi }} hari</div>
             {{-- Tampilkan Umur Koreksi jika sudah dihitung --}}
             @if($umur_koreksi !== null)
             <div><span class="font-medium w-28 inline-block">Umur Koreksi:</span> {{ $umur_koreksi }} minggu</div>
             @endif
             <div><span class="font-medium w-28 inline-block">Hari Rawat Ke:</span> {{ \Carbon\Carbon::parse($patient->tgl_masuk)->diffInDays(now()) + 1 }}</div>
             <div><span class="font-medium w-28 inline-block">Status:</span> {{ $status_rujukan ?? 'N/A' }}</div>
             <div><span class="font-medium w-28 inline-block">Asal Ruangan:</span> {{ $asal_bangsal ?? 'N/A' }}</div> {{-- Ganti jadi Asal Ruangan/Bangsal --}}
        </div>

        {{-- Kolom 3: Info Medis & Adm --}}
        <div class="space-y-1 md:text-right">
             <div class="font-medium text-gray-800">
                 {{-- Tanggal Monitoring --}}
                 @if($currentCycleId) @php $cycle = \App\Models\MonitoringCycle::find($currentCycleId); @endphp {{ \Carbon\Carbon::parse($cycle->start_time)->isoFormat('dddd, D MMMM YYYY') }} @else {{ now()->isoFormat('dddd, D MMMM YYYY') }} @endif
             </div>
             <div><span class="font-medium">DPJP:</span> {{ $patient->nm_dokter }}</div>
             <div><span class="font-medium">Diagnosis Awal:</span> {{ $patient->diagnosa_awal }}</div>
             <div><span class="font-medium">Jaminan:</span> {{ $jaminan ?? 'N/A' }}</div>
        </div>
    </div>
</x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium">Program Terapi (Instruksi)</h3>
                <div class="mt-4">
                    <textarea wire:model.defer="therapy_program" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tuliskan semua instruksi di sini. Gunakan judul seperti 'Terapi:', 'Nutrisi:', dan 'Rencana Lab:' untuk merapikan."></textarea>

                </div>
                <div class="mt-4 text-right">
                    <button type="button" wire:click="saveTherapyProgram" class="inline-flex justify-center rounded-md border border-transparent bg-teal-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-teal-700">
                        Simpan Program
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h3 class="text-lg font-medium">Masalah Klinis Aktif</h3>
                <div class="mt-4">
                    <textarea wire:model.defer="clinical_problems" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tuliskan daftar masalah klinis pasien..."></textarea>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" wire:click="saveClinicalProblems" class="inline-flex justify-center rounded-md border border-transparent bg-sky-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-sky-700">
                        Simpan Masalah
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1">
            <form wire:submit="saveRecord" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Form Input Observasi</h3>

                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jam Observasi</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm">
                                {{ \Carbon\Carbon::parse($record_time)->format('d M Y, H:i') }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="temp_incubator" class="block text-sm font-medium text-gray-700">Temp Incubator</label>
                                <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                    <input type="number" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="block w-full border-0 focus:ring-0">
                                    <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500">°C</span>
                                </div>
                            </div>
                            <div>
                                <label for="temp_skin" class="block text-sm font-medium text-gray-700">Temp Skin</label>
                                <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                    <input type="number" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="block w-full border-0 focus:ring-0">
                                    <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500">°C</span>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="hr" class="block text-sm font-medium text-gray-700">Heart Rate</label>
                                <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                    <input type="number" inputmode="decimal" id="hr" wire:model.defer="hr" class="block w-full border-0 focus:ring-0">
                                    <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500">x/mnt</span>
                                </div>
                            </div>
                            <div>
                                <label for="rr" class="block text-sm font-medium text-gray-700">Resp. Rate</label>
                                <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                    <input type="number" inputmode="decimal" id="rr" wire:model.defer="rr" class="block w-full border-0 focus:ring-0">
                                    <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500">x/mnt</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tekanan Darah (Sistolik/Diastolik)</label>
                            <div class="flex items-center gap-2 mt-1">
                                <input type="text" wire:model.defer="blood_pressure_systolic" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <span>/</span>
                                <input type="text" wire:model.defer="blood_pressure_diastolic" class="block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label for="sat_o2" class="block text-sm font-medium text-gray-700">Sat O2</label>
                            <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                <input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="block w-full border-0 focus:ring-0">
                                <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500">%</span>
                            </div>
                        </div>
                        <div>
                            <label for="irama_ekg" class="block text-sm font-medium text-gray-700">Irama EKG</label>
                            <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Skala Nyeri (PIPP)</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">
                                {{-- Tampilkan skor dari properti Livewire, beri '-' jika kosong --}}
                                {{ $skala_nyeri ?? '-' }}
                            </div>
                            {{-- Opsional: Tambahkan hint --}}
                            <p class="mt-1 text-xs text-gray-500">Diisi otomatis setelah penilaian PIPP.</p>
                        </div>
                        <div>
                            <label for="humidifier_inkubator" class="block text-sm font-medium text-gray-700">Humidifier Inkubator</label>
                            <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="border-t pt-4">
                            <button type="button" wire:click="openEventModal" class="w-full inline-flex justify-center rounded-md border border-transparent bg-gray-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-700">
                                Catat Kejadian Cepat
                            </button>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-800">Terapi Oksigen / Ventilator</h4>
                        <div>
                            <label for="respiratory_mode" class="block text-sm font-medium text-gray-700">Mode Pernapasan</label>
                            <select id="respiratory_mode" wire:model.live="respiratory_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Pilih Mode...</option>
                                <option value="spontan">Spontan (Nasal)</option>
                                <option value="cpap">CPAP</option>
                                <option value="hfo">HFO</option>
                                <option value="monitor">Ventilator Konvensional</option>
                            </select>
                        </div>
                        @if ($respiratory_mode === 'spontan')
                        <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                            <h5 class="text-xs font-bold text-gray-500">SETTING SPONTAN</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm">FiO2</label><input type="text" wire:model.defer="spontan_fio2" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">Flow</label><input type="text" wire:model.defer="spontan_flow" class="mt-1 w-full form-input"></div>
                            </div>
                        </div>
                        @endif
                        @if ($respiratory_mode === 'cpap')
                        <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                            <h5 class="text-xs font-bold text-gray-500">SETTING CPAP</h5>
                            <div class="grid grid-cols-3 gap-4">
                                <div><label class="block text-sm">FiO2</label><input type="text" wire:model.defer="cpap_fio2" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">Flow</label><input type="text" wire:model.defer="cpap_flow" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">PEEP</label><input type="text" wire:model.defer="cpap_peep" class="mt-1 w-full form-input"></div>
                            </div>
                        </div>
                        @endif

                        @if ($respiratory_mode === 'hfo')
                        <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                            <h5 class="text-xs font-bold text-gray-500">SETTING HFO</h5>
                            <div class="grid grid-cols-3 gap-4">
                                <div><label class="block text-sm">FiO2</label><input type="text" wire:model.defer="hfo_fio2" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">Frekuensi</label><input type="text" wire:model.defer="hfo_frekuensi" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">MAP</label><input type="text" wire:model.defer="hfo_map" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">Amplitudo</label><input type="text" wire:model.defer="hfo_amplitudo" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">IT</label><input type="text" wire:model.defer="hfo_it" class="mt-1 w-full form-input"></div>
                            </div>
                        </div>
                        @endif

                        @if ($respiratory_mode === 'monitor')
                        <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                            <h5 class="text-xs font-bold text-gray-500">SETTING VENTILATOR</h5>
                            <div class="grid grid-cols-3 gap-4">
                                <div><label class="block text-sm">Mode</label><input type="text" wire:model.defer="monitor_mode" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">FiO2</label><input type="text" wire:model.defer="monitor_fio2" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">PEEP</label><input type="text" wire:model.defer="monitor_peep" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">PIP</label><input type="text" wire:model.defer="monitor_pip" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">TV/Vte</label><input type="text" wire:model.defer="monitor_tv_vte" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">RR/RR Spontan</label><input type="text" wire:model.defer="monitor_rr_spontan" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">P.Max</label><input type="text" wire:model.defer="monitor_p_max" class="mt-1 w-full form-input"></div>
                                <div><label class="block text-sm">I:E</label><input type="text" wire:model.defer="monitor_ie" class="mt-1 w-full form-input"></div>
                            </div>
                        </div>
                        @endif


                        <div class="space-y-4 border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-800">Keseimbangan Cairan (Balance)</h4>

                            <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                <h5 class="text-xs font-bold text-gray-500">INTAKE (CAIRAN MASUK)</h5>

                                <label class="block text-sm">Parenteral (Infus)</label>
                                @foreach ($parenteral_intakes as $index => $intake)
                                <div class="flex items-center gap-2" wire:key="parenteral-{{ $index }}">
                                    <input type="text" wire:model="parenteral_intakes.{{ $index }}.name" placeholder="Nama Cairan" class="w-1/2 form-input text-sm">
                                    <input type="number" step="0.1" wire:model="parenteral_intakes.{{ $index }}.volume" placeholder="Volume (cc)" class="w-1/2 form-input text-sm">
                                    <button type="button" wire:click="removeParenteralIntake({{ $index }})" class="text-red-500 hover:text-red-700">&times;</button>
                                </div>
                                @endforeach
                                <button type="button" wire:click="addParenteralIntake" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Tambah Infus</button>

                                <hr class="my-2">
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <div>
                                        <label class="block text-sm">OGT</label>
                                        <input type="number" step="0.1" wire:model.defer="intake_ogt" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Oral</label>
                                        <input type="number" step="0.1" wire:model.defer="intake_oral" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                <h5 class="text-xs font-bold text-gray-500">OUTPUT (CAIRAN KELUAR)</h5>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm">Urine</label>
                                        <input type="number" step="0.1" wire:model.defer="output_urine" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                    <div>
                                        <label class="block text-sm">BAB</label>
                                        <input type="number" step="0.1" wire:model.defer="output_bab" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Residu / Muntah</label>
                                        <input type="number" step="0.1" wire:model.defer="output_residu" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                    <div>
                                        <label class="block text-sm">NGT</label>
                                        <input type="number" step="0.1" wire:model.defer="output_ngt" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Drain</label>
                                        <input type="number" step="0.1" wire:model.defer="output_drain" class="mt-1 w-full form-input" placeholder="cc">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-gray-50 px-4 py-3 space-y-3">
                    <button type="button" wire:click="openMedicationModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        + Tambah Pemberian Obat
                    </button>
                    <button type="button" wire:click="openBloodGasModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        + Catat Hasil Gas Darah
                    </button>
                    <button type="button" wire:click="openNipsModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        + Lakukan Penilaian Nyeri (NIPS)
                    </button>
                    <button type="button" wire:click="openPippModal" class="w-full inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        + Lakukan Penilaian Nyeri (PIPP)
                    </button>
                    <div class="text-right">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                            Simpan Catatan
                        </button>
                    </div>
                </div>
            </form>
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-medium">Alat Terpasang</h3>
                        <button type="button" wire:click="openDeviceModal()" class="text-sm bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            + Tambah Alat
                        </button>
                    </div>
                    <div class="mt-4 space-y-2">
                        @forelse ($patientDevices as $device)
                        <div class="text-sm border p-2 rounded flex justify-between items-start" wire:key="device-{{ $device->id }}">
                            <div>
                                <span class="font-semibold">{{ $device->device_name }}</span>
                                <span class="text-gray-600">
                                    @if($device->size) (Size: {{ $device->size }}) @endif
                                    @if($device->location) @ {{ $device->location }} @endif
                                </span>
                                <div class="text-xs text-gray-500">
                                    Dipasang: {{ $device->installation_date ? $device->installation_date->format('d M Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" wire:click="openDeviceModal({{ $device->id }})" class="text-blue-500 hover:text-blue-700 text-xs">Edit</button>
                                <button type="button" wire:click="removeDevice({{ $device->id }})" wire:confirm="Anda yakin ingin menghapus alat ini?" class="text-red-500 hover:text-red-700 text-xs">Hapus</button>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">Belum ada data alat terpasang.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Ringkasan Balance Cairan 24 Jam</h3>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                        <div>Total Masuk (CM): <span class="font-bold text-blue-600">{{ $totalIntake24h }} ml</span></div>
                        <div>Total Keluar (CK): <span class="font-bold text-red-600">{{ $totalOutput24h }} ml</span></div>
                        <div>Produksi Urine: <span class="font-bold">{{ $totalUrine24h }} ml</span></div>
                        <div class="flex items-center space-x-2">
                            <label for="daily_iwl">IWL:</label>
                            <input type="number" step="0.1" id="daily_iwl" wire:model.defer="daily_iwl" class="form-input py-1 px-2 text-sm w-20">
                            <button type="button" wire:click="saveDailyIwl" class="text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Simpan</button>
                        </div>
                        <div class="col-span-2 text-gray-600">
                            BC 24 Jam Sebelumnya:
                            <span class="font-bold">
                                {{ $previousBalance24h !== null ? ($previousBalance24h >= 0 ? '+' : '') . $previousBalance24h . ' ml' : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 border-t pt-3 text-center">
                        Balance Cairan 24 Jam:
                        <span class="text-xl font-bold {{ $balance24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $balance24h >= 0 ? '+' : '' }}{{ $balance24h }} ml
                        </span>
                    </div>
                </div>
            </div>
            <div wire:ignore x-data="{
                        chart: null,
                        init() {
                            // Minta data awal. Chart akan dibuat saat data pertama kali datang.
                            $wire.loadRecords();

                            // ==========================================================
            // === TAMBAHKAN LISTENER NOTIFIKASI DI SINI ===
            // ==========================================================
            Livewire.on('record-saved', (event) => {
                const message = event[0]?.message || '✅ Data berhasil disimpan!';
                const notif = document.createElement('div');
                notif.innerText = message;
                // Atur posisi di kanan atas
                notif.className = 'fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
                document.body.appendChild(notif);
                setTimeout(() => {
                    notif.classList.add('opacity-0');
                    setTimeout(() => notif.remove(), 300);
                }, 3000);
            });

            Livewire.on('error-notification', (event) => {
                const message = event[0]?.message || 'Terjadi kesalahan!';
                const notif = document.createElement('div');
                notif.innerText = `❌ ${message}`;
                notif.className = 'fixed top-5 right-5 bg-red-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
                document.body.appendChild(notif);
                setTimeout(() => {
                    notif.classList.add('opacity-0');
                    setTimeout(() => notif.remove(), 300);
                }, 4000);
            });
        },
                        updateChart(event) {
                            if (this.chart) {
                                this.chart.destroy();
                            }

                            const chartData = event.detail[0].chartData;
                            const ctx = this.$refs.canvas.getContext('2d');

                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    // Kita tidak perlu 'labels' lagi karena menggunakan timeseries
                                    datasets: [
                                        {
                                            label: 'Temp inkubator',
                                            data: chartData.temp_incubator,
                                            borderColor: 'green', // Warna hijau
                                            tension: 0.1,
                                        },
                                        {
                                            label: 'Temp Skin',
                                            data: chartData.temp_skin,
                                            borderColor: 'blue', // Warna Biru
                                            tension: 0.1,
                                        },
                                        {
                                            label: 'Heart Rate',
                                            data: chartData.hr,
                                            borderColor: 'red', // Warna Merah
                                            tension: 0.1,
                                        },
                                        {
                                            label: 'Resp. Rate',
                                            data: chartData.rr,
                                            borderColor: 'black', // Warna Hitam
                                            tension: 0.1,
                                        },
                                        {
                                            label: 'Tensi Sistolik',
                                            data: chartData.bp_systolic,
                                            borderColor: 'rgba(255, 0, 0, 0.8)', // Warna Merah (Solid)
                                            tension: 0.1,
                                        },
                                        {
                                            label: 'Tensi Diastolik',
                                            data: chartData.bp_diastolic,
                                            borderColor: 'rgba(255, 0, 0, 0.4)', // Warna Merah (Lebih Terang)
                                            borderDash: [5, 5], // Garis putus-putus
                                            tension: 0.1,
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: false,
                                    scales: {
                                        x: {
                                            type: 'timeseries',
                                            time: {
                                                unit: 'minute',
                                                displayFormats: { minute: 'HH:mm' }
                                            },
                                            ticks: { source: 'auto', maxRotation: 0, autoSkip: true }
                                        },
                                        y: {
                                            beginAtZero: false
                                        }
                                    }
                                }
                            });
                        }
                    }" x-init="init()" @update-chart.window="updateChart($event)" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Tren Hemodinamik</h3>
                    <div class="relative mt-4 h-64">
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Data Observasi Tercatat</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Jam</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Temp Inkubator</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Tensi</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">HR</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">RR</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Suhu</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">SpO2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($records as $record)
                                @php
                                // Cek apakah ini baris "snapshot lengkap" (punya data HR atau RR)
                                // atau hanya baris "event" (hanya punya data observasi warna/apnea)
                                $isFullRecord = $record->hr || $record->rr || $record->temp_skin;

                                $eventText = collect(['cyanosis', 'pucat', 'ikterus', 'bradikardia', 'stimulasi','crt_less_than_2'])
                                ->filter(fn($key) => $record->$key)
                                ->map(fn($key) => ucfirst(str_replace('_', ' ', $key)))
                                ->implode(', ');
                                @endphp

                                @if ($isFullRecord)
                                {{-- TAMPILKAN BARIS LENGKAP SEPERTI BIASA --}}
                                <tr>
                                    <td class="px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</td>
                                    <td class="px-3 py-2">{{ $record->temp_incubator }}</td>
                                    <td class="px-3 py-2">{{ $record->blood_pressure_systolic }}/{{ $record->blood_pressure_diastolic }}</td>
                                    <td class="px-3 py-2">{{ $record->hr }}</td>
                                    <td class="px-3 py-2">{{ $record->rr }}</td>
                                    <td class="px-3 py-2">{{ $record->temp_skin }}</td>
                                    <td class="px-3 py-2">{{ $record->sat_o2 }}</td>
                                </tr>
                                @if ($eventText)
                                {{-- Jika baris lengkap ini juga punya event, tampilkan sebagai sub-baris --}}
                                <tr>
                                    <td class="pl-8 pr-3 py-1 text-xs text-gray-500" colspan="7">
                                        <span class="font-semibold">Kejadian:</span> {{ $eventText }}
                                    </td>
                                </tr>
                                @endif
                                @else
                                {{-- TAMPILKAN SEBAGAI BARIS EVENT YANG LEBIH SIMPEL --}}
                                <tr>
                                    <td class="px-3 py-1" colspan="7">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-bold">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</span> -
                                            <span class="font-semibold">Kejadian:</span> {{ $eventText }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center p-4">Belum ada data observasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Riwayat Pemberian Obat</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium">Jam</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium">Nama Obat</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium">Dosis</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium">Rute</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($medications as $med)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($med->given_at)->format('H:i') }}</td>
                                    <td class="whitespace-nowrap px-3 py-2">{{ $med->medication_name }}</td>
                                    <td class="whitespace-nowrap px-3 py-2">{{ $med->dose }}</td>
                                    <td class="whitespace-nowrap px-3 py-2">{{ $med->route }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-gray-500">Belum ada obat yang dicatat untuk siklus ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Riwayat Keseimbangan Cairan</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left bg-gray-50">
                                <tr>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900" rowspan="2">Jam</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900 text-center" colspan="3">Intake (Masuk)</th>
                                    {{-- PERUBAHAN: colspan sekarang 4 untuk mengakomodasi kolom baru --}}
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900 text-center" colspan="4">Output (Keluar)</th>
                                </tr>
                                <tr>
                                    {{-- Intake headers (no change) --}}
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Parenteral</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Enteral</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Total CM</th>

                                    {{-- PERUBAHAN: Tambahkan header untuk NGT & Drain --}}
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Urine/BAB</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Residu</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">NGT/Drain</th>
                                    <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Total CK</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($records as $record)
                                @php
                                $totalParenteral = $record->parenteralIntakes->sum('volume');
                                $totalEnteral = ($record->intake_ogt ?? 0) + ($record->intake_oral ?? 0);
                                $totalIntake = $totalParenteral + $totalEnteral;

                                // PERUBAHAN: Tambahkan NGT & Drain ke kalkulasi total output
                                $totalOutput = ($record->output_urine ?? 0) + ($record->output_bab ?? 0) + ($record->output_residu ?? 0) + ($record->output_ngt ?? 0) + ($record->output_drain ?? 0);
                                @endphp

                                @if ($totalIntake > 0 || $totalOutput > 0)
                                <tr>
                                    <td class="whitespace-nowrap px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</td>

                                    {{-- Intake Details (no change) --}}
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">
                                        @foreach ($record->parenteralIntakes as $infus) <div class="text-xs">{{ $infus->name }}: {{ $infus->volume }} cc</div> @endforeach
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">
                                        @if($record->intake_ogt) <div class="text-xs">OGT: {{ $record->intake_ogt }} cc</div> @endif
                                        @if($record->intake_oral) <div class="text-xs">Oral: {{ $record->intake_oral }} cc</div> @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 font-bold text-blue-600">{{ $totalIntake }}</td>

                                    {{-- PERUBAHAN: Tambahkan tampilan data NGT & Drain --}}
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">
                                        @if($record->output_urine) <div class="text-xs">Urine: {{ $record->output_urine }} cc</div> @endif
                                        @if($record->output_bab) <div class="text-xs">BAB: {{ $record->output_bab }} cc</div> @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ $record->output_residu }}</td>
                                    <td class="whitespace-nowrap px-3 py-2 text-gray-700">
                                        @if($record->output_ngt) <div class="text-xs">NGT: {{ $record->output_ngt }} cc</div> @endif
                                        @if($record->output_drain) <div class="text-xs">Drain: {{ $record->output_drain }} cc</div> @endif
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-2 font-bold text-red-600">{{ $totalOutput }}</td>
                                </tr>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">Belum ada data keseimbangan cairan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Riwayat Hasil Gas Darah</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="px-3 py-2">Jam</th>
                                    <th class="px-3 py-2">Gula Darah</th>
                                    <th class="px-3 py-2">pH</th>
                                    <th class="px-3 py-2">PCO2</th>
                                    <th class="px-3 py-2">PO2</th>
                                    <th class="px-3 py-2">HCO3</th>
                                    <th class="px-3 py-2">BE</th>
                                    <th class="px-3 py-2">SaO2</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($bloodGasResults as $result)
                                <tr>
                                    <td class="px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($result->taken_at)->format('H:i') }}</td>
                                    <td class="px-3 py-2">{{ $result->gula_darah }}</td>
                                    <td class="px-3 py-2">{{ $result->ph }}</td>
                                    <td class="px-3 py-2">{{ $result->pco2 }}</td>
                                    <td class="px-3 py-2">{{ $result->po2 }}</td>
                                    <td class="px-3 py-2">{{ $result->hco3 }}</td>
                                    <td class="px-3 py-2">{{ $result->be }}</td>
                                    <td class="px-3 py-2">{{ $result->sao2 }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">Belum ada hasil gas darah yang dicatat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Riwayat Penilaian Nyeri (NIPS)</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="px-3 py-2">Jam</th>
                                    <th class="px-3 py-2">Ekspresi</th>
                                    <th class="px-3 py-2">Tangis</th>
                                    <th class="px-3 py-2">Nafas</th>
                                    <th class="px-3 py-2">Lengan</th>
                                    <th class="px-3 py-2">Kaki</th>
                                    <th class="px-3 py-2">Kesadaran</th>
                                    <th class="px-3 py-2 font-bold">Total Skor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($painAssessments as $score)
                                <tr>
                                    <td class="px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->facial_expression }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->cry }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->breathing_pattern }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->arms_movement }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->legs_movement }}</td>
                                    <td class="px-3 py-2 text-center">{{ $score->state_of_arousal }}</td>
                                    <td class="px-3 py-2 text-center font-bold text-lg {{ $score->total_score >= 4 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $score->total_score }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-4 text-gray-500">Belum ada penilaian nyeri yang dicatat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium border-b pb-3">Riwayat Penilaian Nyeri (PIPP)</h3>
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="px-2 py-2">Jam</th>
                                    <th class="px-2 py-2">Usia Gest</th>
                                    <th class="px-2 py-2">Perilaku</th>
                                    <th class="px-2 py-2">HR Max</th>
                                    <th class="px-2 py-2">SpO2 Min</th>
                                    <th class="px-2 py-2">Alis</th>
                                    <th class="px-2 py-2">Mata</th>
                                    <th class="px-2 py-2">Nasolabial</th>
                                    <th class="px-2 py-2 font-bold">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($pippAssessments as $score)
                                <tr>
                                    <td class="px-2 py-2 font-medium">{{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->gestational_age }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->behavioral_state }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->max_heart_rate }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->min_oxygen_saturation }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->brow_bulge }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->eye_squeeze }}</td>
                                    <td class="px-2 py-2 text-center">{{ $score->nasolabial_furrow }}</td>
                                    <td class="px-2 py-2 text-center font-bold text-lg {{ $score->total_score > 12 ? 'text-red-600' : ($score->total_score > 6 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $score->total_score }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center p-4 text-gray-500">Belum ada penilaian PIPP yang dicatat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@if ($showPippModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closePippModal"></div>
    <div x-data="{
                gestational_age: @entangle('gestational_age'),
                behavioral_state: @entangle('behavioral_state'),
                max_heart_rate: @entangle('max_heart_rate'),
                min_oxygen_saturation: @entangle('min_oxygen_saturation'),
                brow_bulge: @entangle('brow_bulge'),
                eye_squeeze: @entangle('eye_squeeze'),
                nasolabial_furrow: @entangle('nasolabial_furrow'),
                get totalScore() {
                    return parseInt(this.gestational_age || 0) + parseInt(this.behavioral_state || 0) +
                           parseInt(this.max_heart_rate || 0) + parseInt(this.min_oxygen_saturation || 0) +
                           parseInt(this.brow_bulge || 0) + parseInt(this.eye_squeeze || 0) +
                           parseInt(this.nasolabial_furrow || 0);
                }
             }" class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-3xl" {{-- Ubah max-w- jadi lebih lebar --}}>
        <h3 class="text-lg font-medium text-gray-900">Penilaian Nyeri Prematur (PIPP)</h3>
        <div class="mt-4 space-y-4 border-t pt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Waktu Penilaian</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($pipp_assessment_time)->format('d M Y, H:i') }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                {{-- Usia Gestasi --}}
                <div><label class="block text-sm font-medium">Usia Gestasi</label><select x-model="gestational_age" class="mt-1 w-full form-select">
                        <option value="0">0: ≥ 36 mgg</option>
                        <option value="1">1: 32-35 mgg + 6 hari</option>
                        <option value="2">2: 28-31 mgg + 6 hari</option>
                        <option value="3">3: < 28 mgg</option>
                    </select></div>
                {{-- Perilaku Bayi --}}
                <div><label class="block text-sm font-medium">Perilaku Bayi (selama 15 detik)</label><select x-model="behavioral_state" class="mt-1 w-full form-select">
                        <option value="0">0: Aktif/bangun, mata terbuka</option>
                        <option value="1">1: Diam/bangun, mata terbuka/tertutup</option>
                        <option value="2">2: Aktif/tidur, mata tertutup</option>
                        <option value="3">3: Tenang/tidur, gerak minimal</option>
                    </select></div>
                {{-- Laju Nadi Maks --}}
                <div><label class="block text-sm font-medium">Laju Nadi Maks (peningkatan)</label><select x-model="max_heart_rate" class="mt-1 w-full form-select">
                        <option value="0">0: 0-4 dpm</option>
                        <option value="1">1: 5-14 dpm</option>
                        <option value="2">2: 15-24 dpm</option>
                        <option value="3">3: ≥ 25 dpm</option>
                    </select></div>
                {{-- Saturasi Oksigen Min --}}
                <div><label class="block text-sm font-medium">Saturasi Oksigen Min (penurunan)</label><select x-model="min_oxygen_saturation" class="mt-1 w-full form-select">
                        <option value="0">0: 92-100%</option>
                        <option value="1">1: Turun 89-91%</option>
                        <option value="2">2: Turun 85-88%</option>
                        <option value="3">3: < 85%</option>
                    </select></div>
                {{-- Tarikan Alis --}}
                <div><label class="block text-sm font-medium">Tarikan Alis (% Waktu)</label><select x-model="brow_bulge" class="mt-1 w-full form-select">
                        <option value="0">0: Tidak ada (<9%)< /option>
                        <option value="1">1: Minimum (10-39%)</option>
                        <option value="2">2: Moderate (40-69%)</option>
                        <option value="3">3: Maksimum (≥70%)</option>
                    </select></div>
                {{-- Kerutan Mata --}}
                <div><label class="block text-sm font-medium">Kerutan Mata (% Waktu)</label><select x-model="eye_squeeze" class="mt-1 w-full form-select">
                        <option value="0">0: Tidak ada (<9%)< /option>
                        <option value="1">1: Minimum (10-39%)</option>
                        <option value="2">2: Moderate (40-69%)</option>
                        <option value="3">3: Maksimum (≥70%)</option>
                    </select></div>
                {{-- Alur Nasolabial --}}
                <div><label class="block text-sm font-medium">Alur Nasolabial (% Waktu)</label><select x-model="nasolabial_furrow" class="mt-1 w-full form-select">
                        <option value="0">0: Tidak ada (<9%)< /option>
                        <option value="1">1: Minimum (10-39%)</option>
                        <option value="2">2: Moderate (40-69%)</option>
                        <option value="3">3: Maksimum (≥70%)</option>
                    </select></div>
            </div>

            {{-- Display Total Score & Intervention --}}
            <div class="mt-4 border-t pt-3 text-center space-y-2">
                <div>
                    Total Skor PIPP:
                    <span class="text-xl font-bold" :class="{ 'text-red-600': totalScore > 12, 'text-yellow-600': totalScore > 6, 'text-green-600': totalScore <= 6 }" x-text="totalScore">
                    </span>
                    {{-- <span class="text-sm text-gray-500">(>6 = Nyeri Ringan/Sedang, >12 = Nyeri Berat)</span> --}}
                </div>

                {{-- Intervention Guidance --}}
                <div class="text-sm text-gray-700 bg-gray-100 p-2 rounded">
                    <strong class="block mb-1">Intervensi Sesuai Skor:</strong>
                    <div x-show="totalScore <= 6">
                        <strong>0-6:</strong> Lanjutkan Tatalaksana dan penilaian.
                    </div>
                    <div x-show="totalScore >= 7 && totalScore <= 12">
                        <strong>7-12:</strong> Intervensi Non-farmakologis (kenyamanan keperawatan, sukrosa oral).
                    </div>
                    <div x-show="totalScore > 12">
                        <strong>&gt;12:</strong> Pemberian Intervensi farmakologis (Parasetamol, Narkotik, Sedasi).
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <button type="button" wire:click="closePippModal" class="px-4 py-2 text-sm bg-white border rounded-md">Batal</button>
            <button type="button" wire:click="savePippScore" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md">Simpan Skor PIPP</button>
        </div>
    </div>
</div>
@endif
@if ($showNipsModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeNipsModal"></div>
    <div x-data="{
                // Mirror Livewire properties for Alpine calculation
                facial_expression: @entangle('facial_expression'),
                cry: @entangle('cry'),
                breathing_pattern: @entangle('breathing_pattern'),
                arms_movement: @entangle('arms_movement'),
                legs_movement: @entangle('legs_movement'),
                state_of_arousal: @entangle('state_of_arousal'),
                // Computed property for total score
                get totalScore() {
                    return parseInt(this.facial_expression || 0) +
                           parseInt(this.cry || 0) +
                           parseInt(this.breathing_pattern || 0) +
                           parseInt(this.arms_movement || 0) +
                           parseInt(this.legs_movement || 0) +
                           parseInt(this.state_of_arousal || 0);
                }
             }" class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl">
        <h3 class="text-lg font-medium text-gray-900">Penilaian Nyeri Neonatus (NIPS)</h3>
        <div class="mt-4 space-y-4 border-t pt-4">
            <div>
                <label class="block text-sm font-medium">Waktu Penilaian</label>
                <input type="datetime-local" wire:model="assessment_time" class="mt-1 w-full form-input">
                <p class="mt-1 text-xs text-gray-500">Waktu akan disimpan dalam WITA (24 jam).</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label class="block text-sm font-medium">Ekspresi Muka</label>
                    <select x-model="facial_expression" class="mt-1 w-full form-select">
                        <option value="0">0: Rileks, otot wajah tenang</option>
                        <option value="1">1: Meringis, alis/dahi berkerut</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Menangis</label>
                    <select x-model="cry" class="mt-1 w-full form-select">
                        <option value="0">0: Tidak menangis</option>
                        <option value="1">1: Merengek, sesekali</option>
                        <option value="2">2: Menangis terus-menerus</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Pola Nafas</label>
                    <select x-model="breathing_pattern" class="mt-1 w-full form-select">
                        <option value="0">0: Rileks, pola nafas biasa</option>
                        <option value="1">1: Berubah, tidak teratur, cepat, menahan nafas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Gerakan Lengan</label>
                    <select x-model="arms_movement" class="mt-1 w-full form-select">
                        <option value="0">0: Rileks, tidak ada gerakan</option>
                        <option value="1">1: Fleksi/Ekstensi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Gerakan Kaki</label>
                    <select x-model="legs_movement" class="mt-1 w-full form-select">
                        <option value="0">0: Rileks, tidak ada gerakan</option>
                        <option value="1">1: Fleksi/Ekstensi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Tingkat Kesadaran</label>
                    <select x-model="state_of_arousal" class="mt-1 w-full form-select">
                        <option value="0">0: Tidur/Tenang</option>
                        <option value="1">1: Rewel, gelisah</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 border-t pt-3 text-center">
                Total Skor NIPS:
                <span class="text-xl font-bold" :class="{ 'text-red-600': totalScore >= 4, 'text-green-600': totalScore < 4 }" x-text="totalScore">
                </span>
                <span class="text-sm text-gray-500">(>3 = Nyeri)</span>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <button type="button" wire:click="closeNipsModal" class="px-4 py-2 text-sm bg-white border rounded-md">Batal</button>
            <button type="button" wire:click="saveNipsScore" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md">Simpan Skor</button>
        </div>
    </div>
</div>
@endif
@if ($showDeviceModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeDeviceModal"></div>
    <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
        <h3 class="text-lg font-medium text-gray-900">{{ $editingDeviceId ? 'Edit Alat Terpasang' : 'Tambah Alat Baru' }}</h3>
        <div class="mt-4 space-y-4 border-t pt-4">
            <div>
                <label for="device_name" class="block text-sm font-medium">Nama Alat</label>
                <input id="device_name" type="text" wire:model="device_name" class="mt-1 w-full form-input" placeholder="Contoh: CVC, ETT, Kateter Urin">
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="size" class="block text-sm font-medium">Ukuran</label>
                    <input id="size" type="text" wire:model="size" class="mt-1 w-full form-input" placeholder="Contoh: 7 Fr">
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium">Lokasi</label>
                    <input id="location" type="text" wire:model="location" class="mt-1 w-full form-input" placeholder="Contoh: V. Subklavia Ka">
                </div>
                <div>
                    <label for="installation_date" class="block text-sm font-medium">Tgl. Pasang</label>
                    <input id="installation_date" type="date" wire:model="installation_date" class="mt-1 w-full form-input">
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <button type="button" wire:click="closeDeviceModal" class="px-4 py-2 text-sm bg-white border rounded-md">Batal</button>
            <button type="button" wire:click="saveDevice" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md">
                {{ $editingDeviceId ? 'Update Alat' : 'Simpan Alat' }}
            </button>
        </div>
    </div>
</div>
@endif

@if ($showEventModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeEventModal"></div>

    <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-medium text-gray-900">Catat Kejadian</h3>
        <p class="text-sm text-gray-500 mt-1">Pilih semua kejadian yang terjadi pada waktu yang sama.</p>

        <div class="mt-4 space-y-2 border-t pt-4">
            <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                <label for="event_cyanosis" class="flex items-center space-x-2">
                    <input id="event_cyanosis" type="checkbox" wire:model="event_cyanosis" class="rounded border-gray-300">
                    <span>Cyanosis</span>
                </label>
                <label for="event_pucat" class="flex items-center space-x-2">
                    <input id="event_pucat" type="checkbox" wire:model="event_pucat" class="rounded border-gray-300">
                    <span>Pucat</span>
                </label>
                <label for="event_ikterus" class="flex items-center space-x-2">
                    <input id="event_ikterus" type="checkbox" wire:model="event_ikterus" class="rounded border-gray-300">
                    <span>Ikterus</span>
                </label>
                <label for="event_crt" class="flex items-center space-x-2">
                    <input id="event_crt" type="checkbox" wire:model="event_crt_less_than_2" class="rounded border-gray-300">
                    <span>CRT &lt; 2 detik</span>
                </label>
                <label for="event_bradikardia" class="flex items-center space-x-2">
                    <input id="event_bradikardia" type="checkbox" wire:model="event_bradikardia" class="rounded border-gray-300">
                    <span>Bradikardia</span>
                </label>
                <label for="event_stimulasi" class="flex items-center space-x-2">
                    <input id="event_stimulasi" type="checkbox" wire:model="event_stimulasi" class="rounded border-gray-300">
                    <span>Stimulasi</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" wire:click="closeEventModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Batal
            </button>
            <button type="button" wire:click="saveEvent" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                Simpan Kejadian
            </button>
        </div>
    </div>
</div>
@endif




@if ($showMedicationModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeMedicationModal"></div>
    <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
        <h3 class="text-lg font-medium text-gray-900">Tambah Pemberian Obat</h3>
        <div class="mt-4 space-y-4 border-t pt-4">

            <div>
                <label class="block text-sm font-medium">Waktu Pemberian</label>
                <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($given_at)->format('d M Y, H:i') }}
                </div>
            </div>
            <div>
                <label for="medication_name" class="block text-sm font-medium">Nama Obat</label>
                <input id="medication_name" type="text" wire:model="medication_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Aminofilin">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="dose" class="block text-sm font-medium">Dosis</label>
                    <input id="dose" type="text" wire:model="dose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 3x80mg">
                </div>
                <div>
                    <label for="route" class="block text-sm font-medium">Rute</label>
                    <input id="route" type="text" wire:model="route" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: IV">
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" wire:click="closeMedicationModal" class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
            <button type="button" wire:click="saveMedication" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">Simpan Obat</button>
        </div>
    </div>
</div>
@endif



@if ($showBloodGasModal)
<div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeBloodGasModal"></div>
    <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl">
        <h3 class="text-lg font-medium text-gray-900">Catat Hasil Gas Darah</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 border-t pt-4">
            <div>
                <label for="taken_at" class="block text-sm font-medium">Waktu Pengambilan</label>
                <input id="taken_at" type="datetime-local" wire:model="taken_at" class="mt-1 w-full form-input">
                <p class="mt-1 text-xs text-gray-500">Waktu akan disimpan dalam WITA (24 jam).</p>
            </div>
            <div>
                <label for="gula_darah" class="block text-sm font-medium">Gula Darah (BS)</label>
                <input id="gula_darah" type="number" step="0.1" wire:model="gula_darah" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="ph" class="block text-sm font-medium">pH</label>
                <input id="ph" type="number" step="0.01" wire:model="ph" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="pco2" class="block text-sm font-medium">PCO2</label>
                <input id="pco2" type="number" step="0.1" wire:model="pco2" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="po2" class="block text-sm font-medium">PO2</label>
                <input id="po2" type="number" step="0.1" wire:model="po2" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="hco3" class="block text-sm font-medium">HCO3</label>
                <input id="hco3" type="number" step="0.1" wire:model="hco3" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="be" class="block text-sm font-medium">BE</label>
                <input id="be" type="number" step="0.1" wire:model="be" class="mt-1 w-full form-input">
            </div>
            <div>
                <label for="sao2" class="block text-sm font-medium">SaO2</label>
                <input id="sao2" type="number" step="0.1" wire:model="sao2" class="mt-1 w-full form-input">
            </div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" wire:click="closeBloodGasModal" class="px-4 py-2 text-sm bg-white border rounded-md">Batal</button>
            <button type="button" wire:click="saveBloodGasResult" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md">Simpan Hasil</button>
        </div>
    </div>
</div>
@endif

</div>
</div>
