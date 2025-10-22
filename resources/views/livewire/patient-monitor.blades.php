<div>
    <header class="bg-white shadow pt-20">
        <div class=" max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
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
                    <div><span class="font-medium w-28 inline-block">Usia Kehamilan:</span> {{ $umur_kehamilan ?? 'N/A' }} minggu</div>
                    <div><span class="font-medium w-28 inline-block">Umur Bayi:</span> {{ $umur_bayi }} hari</div>
                    @if($umur_koreksi !== null)
                    <div><span class="font-medium w-28 inline-block">Umur Koreksi:</span> {{ $umur_koreksi }} minggu</div>
                    @endif
                    <div><span class="font-medium w-28 inline-block">Hari Rawat Ke:</span> {{ \Carbon\Carbon::parse($patient->tgl_masuk)->diffInDays(now()) + 1 }}</div>
                    <div><span class="font-medium w-28 inline-block">Status:</span> {{ $status_rujukan ?? 'N/A' }}</div>
                    <div><span class="font-medium w-28 inline-block">Asal Ruangan:</span> {{ $asal_bangsal ?? 'N/A' }}</div>
                </div>

                {{-- Kolom 3: Info Medis & Adm --}}
                <div class="space-y-1 md:text-right">
                    <div class="flex items-center space-x-2 md:justify-end">
                        <button wire:click="goToPreviousDay" type="button" title="Hari Sebelumnya" class="p-1 text-gray-500 hover:text-gray-700 disabled:opacity-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <input type="date" wire:model.live="selectedDate" class="form-input py-1 px-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button wire:click="goToNextDay" type="button" title="Hari Berikutnya" class="p-1 text-gray-500 hover:text-gray-700 disabled:opacity-50" @if(\Carbon\Carbon::parse($selectedDate)->isToday()) disabled @endif > {{-- Disable jika sudah hari ini --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        @if($currentCycleId)
                        <a href="{{ route('patient.monitor.report', [
        'no_rawat' => str_replace('/', '_', $no_rawat),
        'cycle_id' => $currentCycleId
    ]) }}" target="_blank">
                            Cetak
                        </a>
                        @endif
                    </div>
                    <div><span class="font-medium">DPJP:</span> {{ $patient->nm_dokter }}</div>
                    <div><span class="font-medium">Diagnosis Awal:</span> {{ $patient->diagnosa_awal }}</div>
                    <div><span class="font-medium">Jaminan:</span> {{ $jaminan ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </header>
    {{-- MAIN CONTENT --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Program Terapi (Instruksi)</h3>
                    <div class="mt-4">
                        <textarea wire:model.defer="therapy_program" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tuliskan semua instruksi di sini. Gunakan judul seperti 'Terapi:', 'Nutrisi:', dan 'Rencana Lab:' untuk merapikan."></textarea>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="button" wire:click="saveTherapyProgram" class="inline-flex justify-center rounded-md border border-transparent bg-teal-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-teal-700">Simpan Program</button>
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
                        <button type="button" wire:click="saveClinicalProblems" class="inline-flex justify-center rounded-md border border-transparent bg-sky-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-sky-700">Simpan Masalah</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- GRID UTAMA (FORM INPUT & DATA DISPLAY) --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <form wire:submit="saveRecord" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium border-b pb-3">Form Input Observasi</h3>
                        <div><label class="block text-sm font-medium">Waktu Pemberian</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ \Carbon\Carbon::parse(time: $record_time)->format('d M Y, H:i') }}</div>
                        </div>
                        <div class="border-b border-gray-200 mt-4">
                            <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs">
                                <button wire:click.prevent="$set('activeTab', 'observasi')" type="button" class="{{ $activeTab === 'observasi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Observasi Utama
                                </button>
                                <button wire:click.prevent="$set('activeTab', 'ventilator')" type="button" class="{{ $activeTab === 'ventilator' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Ventilator
                                </button>
                                <button wire:click.prevent="$set('activeTab', 'cairan')" type="button" class="{{ $activeTab === 'cairan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Balance Cairan
                                </button>
                                <button wire:click.prevent="$set('activeTab', 'lainnya')" type="button" class="{{ $activeTab === 'lainnya' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                                    Lain-lain
                                </button>
                            </nav>
                        </div>

                        <div class="space-y-4 mt-4 min-h-[300px]"> {{-- Beri min-height agar layout stabil --}}
                            <div x-show="$wire.activeTab === 'observasi'" class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="temp_incubator" class="block text-sm font-medium text-gray-700">Temp Incubator</label>
                                        <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm">
                                            <input type="text" pattern="[0-9]*([.,][0-9]+)?" inputmode="decimal" id="temp_incubator" wire:model.defer="temp_incubator" class="block w-full border-0 focus:ring-0 rounded-l-md">
                                            <span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
                                    </div>
                                    <div>
                                        <label for="temp_skin" class="block text-sm font-medium text-gray-700">Temp Skin</label>
                                        <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="temp_skin" wire:model.defer="temp_skin" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">°C</span></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="hr" class="block text-sm font-medium text-gray-700">Heart Rate</label>
                                        <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="hr" wire:model.defer="hr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
                                    </div>
                                    <div>
                                        <label for="rr" class="block text-sm font-medium text-gray-700">Resp. Rate</label>
                                        <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="text" inputmode="decimal" id="rr" wire:model.defer="rr" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">x/mnt</span></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tekanan Darah (Sistolik/Diastolik)</label>
                                    <div class="flex items-center gap-2 mt-1"><input type="text" wire:model.defer="blood_pressure_systolic" class="block w-full rounded-md border-gray-300 shadow-sm"><span>/</span><input type="text" wire:model.defer="blood_pressure_diastolic" class="block w-full rounded-md border-gray-300 shadow-sm"></div>
                                </div>
                                <div>
                                    <label for="sat_o2" class="block text-sm font-medium text-gray-700">Sat O2</label>
                                    <div class="mt-1 flex items-center rounded-md border border-gray-300 shadow-sm"><input type="number" inputmode="decimal" id="sat_o2" wire:model.defer="sat_o2" class="block w-full border-0 focus:ring-0 rounded-l-md"><span class="whitespace-nowrap bg-gray-50 px-3 text-gray-500 rounded-r-md">%</span></div>
                                </div>
                            </div>

                            <div x-show="$wire.activeTab === 'ventilator'" class="space-y-4">

                                <div>
                                    <label for="respiratory_mode" class="block text-sm font-medium text-gray-700">
                                        Mode Pernapasan
                                    </label>
                                    <select id="respiratory_mode" wire:model.live="respiratory_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Pilih Mode...</option>
                                        <option value="spontan">Spontan (Nasal)</option>
                                        <option value="cpap">CPAP</option>
                                        <option value="hfo">HFO</option>
                                        <option value="monitor">Ventilator Konvensional</option>
                                    </select>
                                </div>

                                {{-- ================== SPONTAN ================== --}}
                                @if ($respiratory_mode === 'spontan')
                                <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                    <h5 class="text-xs font-bold text-gray-500">SETTING SPONTAN</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm">FiO2 (%)</label>
                                            <input type="text" wire:model.defer="spontan_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Flow (Lpm)</label>
                                            <input type="text" wire:model.defer="spontan_flow" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- ================== CPAP ================== --}}
                                @if ($respiratory_mode === 'cpap')
                                <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                    <h5 class="text-xs font-bold text-gray-500">SETTING CPAP</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm">FiO2 (%)</label>
                                            <input type="text" wire:model.defer="cpap_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Flow (Lpm)</label>
                                            <input type="text" wire:model.defer="cpap_flow" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">PEEP (cmH2O)</label>
                                            <input type="text" wire:model.defer="cpap_peep" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- ================== HFO ================== --}}
                                @if ($respiratory_mode === 'hfo')
                                <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                    <h5 class="text-xs font-bold text-gray-500">SETTING HFO</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm">FiO2 (%)</label>
                                            <input type="text" wire:model.defer="hfo_fio2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Frekuensi (Hz)</label>
                                            <input type="text" wire:model.defer="hfo_frekuensi" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">MAP (cmH2O)</label>
                                            <input type="text" wire:model.defer="hfo_map" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">Amplitudo (ΔP)</label>
                                            <input type="text" wire:model.defer="hfo_amplitudo" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm">IT (%)</label>
                                            <input type="text" wire:model.defer="hfo_it" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- ================== MONITOR ================== --}}
                                @if ($respiratory_mode === 'monitor')
                                <div class="space-y-2 p-4 bg-gray-50 rounded-lg border">
                                    <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Setting Ventilator</h5>

                                    <div class="grid grid-cols-2 gap-4 items-start">
                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">Mode</label>
                                            <input type="text" wire:model.defer="monitor_mode" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">FiO₂ (%)</label>
                                            <input type="text" wire:model.defer="monitor_fio2" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">PEEP (cmH₂O)</label>
                                            <input type="text" wire:model.defer="monitor_peep" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">PIP (cmH₂O)</label>
                                            <input type="text" wire:model.defer="monitor_pip" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">TV/Vte (ml)</label>
                                            <input type="text" wire:model.defer="monitor_tv_vte" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">RR / RR Spontan</label>
                                            <input type="text" wire:model.defer="monitor_rr_spontan" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">P.Max (cmH₂O)</label>
                                            <input type="text" wire:model.defer="monitor_p_max" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>

                                        <div class="flex flex-col">
                                            <label class="block text-sm text-gray-700">I : E</label>
                                            <input type="text" wire:model.defer="monitor_ie" class="mt-1 w-full h-9 rounded-md border-gray-300 shadow-sm text-sm">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div x-show="$wire.activeTab === 'cairan'" class="space-y-4">
                                <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                    <h5 class="text-xs font-bold text-gray-500">INTAKE (CAIRAN MASUK)</h5>
                                    <label class="block text-sm">Parenteral (Infus)</label>
                                    @foreach ($parenteral_intakes as $index => $intake)
                                    <div class="flex items-center gap-2" wire:key="parenteral-{{ $index }}">
                                        <input type="text" wire:model="parenteral_intakes.{{ $index }}.name" placeholder="Nama Cairan" class="w-1/2 form-input text-sm rounded-md border-gray-300 shadow-sm">
                                        <input type="number" step="0.1" wire:model="parenteral_intakes.{{ $index }}.volume" placeholder="Volume (cc)" class="w-1/2 form-input text-sm rounded-md border-gray-300 shadow-sm">
                                        <button type="button" wire:click="removeParenteralIntake({{ $index }})" class="text-red-500 hover:text-red-700">&times;</button>
                                    </div>
                                    @endforeach
                                    <button type="button" wire:click="addParenteralIntake" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Tambah Infus</button>
                                    <hr class="my-2">
                                    <div class="grid grid-cols-2 gap-4 pt-2">
                                        <div><label class="block text-sm">OGT (cc)</label><input type="number" step="0.1" wire:model.defer="intake_ogt" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                        <div><label class="block text-sm">Oral (cc)</label><input type="number" step="0.1" wire:model.defer="intake_oral" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                    </div>
                                </div>
                                <div class="space-y-2 p-3 bg-gray-50 rounded-md border">
                                    <h5 class="text-xs font-bold text-gray-500">OUTPUT (CAIRAN KELUAR)</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm">Urine (cc)</label><input type="number" step="0.1" wire:model.defer="output_urine" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                        <div><label class="block text-sm">BAB (cc)</label><input type="number" step="0.1" wire:model.defer="output_bab" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                        <div><label class="block text-sm">Residu / Muntah (cc)</label><input type="number" step="0.1" wire:model.defer="output_residu" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                        <div><label class="block text-sm">NGT (cc)</label><input type="number" step="0.1" wire:model.defer="output_ngt" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                        <div><label class="block text-sm">Drain (cc)</label><input type="number" step="0.1" wire:model.defer="output_drain" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" placeholder="cc"></div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="$wire.activeTab === 'lainnya'" class="space-y-4">
                                <div>
                                    <label for="irama_ekg" class="block text-sm font-medium text-gray-700">Irama EKG</label>
                                    <input type="text" id="irama_ekg" wire:model.defer="irama_ekg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Skala Nyeri (PIPP)</label>
                                    <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ $skala_nyeri ?? '-' }}</div>
                                    <p class="mt-1 text-xs text-gray-500">Diisi otomatis setelah penilaian PIPP.</p>
                                </div>
                                <div>
                                    <label for="humidifier_inkubator" class="block text-sm font-medium text-gray-700">Humidifier Inkubator</label>
                                    <input type="text" id="humidifier_inkubator" wire:model.defer="humidifier_inkubator" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="text-right">
                                <button type="submit" wire:loading.attr="disabled" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                                    <span wire:loading.remove wire:target="saveRecord">Simpan Catatan</span>
                                    <span wire:loading wire:target="saveRecord">Menyimpan...</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-4 border-t">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Tombol Obat -->
                            <button type="button" wire:click="openMedicationModal" class="group flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white py-2 px-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-green-50 hover:border-green-400 hover:text-green-700 transition-all duration-150">

                                <span>Obat</span>
                            </button>

                            <!-- Tombol Gas Darah -->
                            <button type="button" wire:click="openBloodGasModal" class="group flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white py-2 px-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-blue-50 hover:border-blue-400 hover:text-blue-700 transition-all duration-150">

                                <span>Gas Darah</span>
                            </button>

                            <!-- Tombol Penilaian PIPP -->
                            <button type="button" wire:click="openPippModal" class="group flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white py-2 px-3 text-sm font-medium text-gray-700 shadow-sm hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700 transition-all duration-150">

                                <span>Penilaian PIPP</span>
                            </button>

                            <!-- Tombol Kejadian Cepat -->
                            <button type="button" wire:click="openEventModal" class="group flex items-center justify-center gap-2 rounded-xl border border-transparent bg-gray-700 py-2 px-3 text-sm font-medium text-white shadow-sm hover:bg-gray-800 transition-all duration-150">

                                <span>Kejadian Cepat</span>
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
                                    <span class="font-semibold">{{ $device->device_name }}</span> <span class="text-gray-600"> @if($device->size) (Size: {{ $device->size }}) @endif @if($device->location) @ {{ $device->location }} @endif </span>
                                    <div class="text-xs text-gray-500"> Dipasang: {{ $device->installation_date ? $device->installation_date->format('d M Y') : 'N/A' }} </div>
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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 pt-4 sm:px-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-4 overflow-x-auto" aria-label="Tabs Output">
                                <button wire:click.prevent="$set('activeOutputTab', 'ringkasan')" type="button" class="{{ $activeOutputTab === 'ringkasan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                                    Ringkasan & Grafik
                                </button>
                                <button wire:click.prevent="$set('activeOutputTab', 'observasi')" type="button" class="{{ $activeOutputTab === 'observasi' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                                    Observasi
                                </button>
                                <button wire:click.prevent="$set('activeOutputTab', 'obat_cairan')" type="button" class="{{ $activeOutputTab === 'obat_cairan' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                                    Obat & Cairan
                                </button>
                                <button wire:click.prevent="$set('activeOutputTab', 'penilaian_lab')" type="button" class="{{ $activeOutputTab === 'penilaian_lab' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                                    Penilaian & Lab
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
                <div x-show="$wire.activeOutputTab === 'ringkasan'" class="space-y-6">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestHR > 160 || $latestHR < 100, 'bg-yellow-600'=> ($latestHR >= 100 && $latestHR <= 110) || ($latestHR>= 150 && $latestHR <= 160), 'bg-green-600'=> $latestHR > 110 && $latestHR < 150, 'bg-gray-400'=> is_null(value: $latestHR)
                                            ])>
                                            <div class="text-sm font-semibold uppercase">Heart Rate (HR)</div>
                                            <div class="text-3xl font-bold">
                                                {{ $latestHR ?? 'N/A' }} <span class="text-lg font-normal">bpm</span>
                                            </div>
                                            <div class="text-xs mt-1">
                                                Normal: 100 - 160
                                            </div>
                        </div>

                        <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestMAP < 40, 'bg-yellow-600'=> $latestMAP >= 40 && $latestMAP < 45, 'bg-green-600'=> $latestMAP >= 45,
                                    'bg-gray-400' => is_null($latestMAP)
                                    ])>
                                    <div class="text-sm font-semibold uppercase">Tensi & MAP</div>
                                    <div class="text-3xl font-bold">
                                        {{ $latestBPSystolic ?? 'N/A' }}/{{ $latestBPDiastolic ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm mt-1 font-semibold">
                                        MAP: {{ $latestMAP ?? 'N/A' }} mmHg
                                    </div>
                                    <div class="text-xs">
                                        Target MAP: 40 - 60
                                    </div>
                        </div>

                        <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestRR > 60 || $latestRR < 20, 'bg-green-600'=> $latestRR >= 20 && $latestRR <= 60, 'bg-gray-400'=> is_null($latestRR)
                                    ])>
                                    <div class="text-sm font-semibold uppercase">Resp. Rate (RR)</div>
                                    <div class="text-3xl font-bold">
                                        {{ $latestRR ?? 'N/A' }} <span class="text-lg font-normal">x/menit</span>
                                    </div>
                                    <div class="text-xs mt-1">
                                        Normal (Neonatus): 20 - 60
                                    </div>
                        </div>

                        <div @class([ 'p-4 rounded-lg shadow-md text-white transition-colors duration-300' , 'bg-red-600'=> $latestTempSkin > 37.5 || $latestTempSkin < 36.5, 'bg-green-600'=> $latestTempSkin >= 36.5 && $latestTempSkin <= 37.5, 'bg-gray-400'=> is_null($latestTempSkin) && is_null($latestTempIncubator)
                                    ])>
                                    <div class="text-sm font-semibold uppercase">Temp. Kulit / Inkubator</div>
                                    <div class="text-3xl font-bold">
                                        {{ $latestTempSkin ? number_format($latestTempSkin, 1) : 'N/A' }} <span class="text-lg font-normal">&deg;C</span>
                                    </div>
                                    <div class="text-xs mt-1">
                                        Inkubator: {{ $latestTempIncubator ? number_format($latestTempIncubator, 1) : 'N/A' }} &deg;C
                                    </div>
                                    <div class="text-xs">
                                        Target Kulit: 36.5 - 37.5 &deg;C
                                    </div>
                        </div>
                    </div>
                    <div wire:ignore x-data="{
        chart: null,
        init() {
            $wire.loadRecords();
        },
        updateChart(event) {
            if (this.chart) {
                this.chart.destroy();
            }
            const chartData = event.detail[0].chartData;
            const ctx = this.$refs.canvas.getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: { datasets: [
                    { label: 'Temp inkubator', data: chartData.temp_incubator, borderColor: 'green', tension: 0.1, spanGaps: true, },
                    { label: 'Temp Skin', data: chartData.temp_skin,spanGaps: true, borderColor: 'blue', tension: 0.1, },
                    { label: 'Heart Rate', data: chartData.hr,spanGaps: true, borderColor: 'red', tension: 0.1, },
                    { label: 'Resp. Rate', data: chartData.rr,spanGaps: true, borderColor: 'black', tension: 0.1, },
                    {label: 'Tensi Sistolik',data: chartData.bp_systolic,borderColor: 'rgba(255,0,0,1)', fill: false,
                        tension: 0.1,
                        pointRadius: 3,
                        spanGaps: true,
                        pointBackgroundColor: 'rgba(255,0,0,1)',
                    },
                    {
                        label: 'Tensi Diastolik',
                        data: chartData.bp_diastolic,
                        borderColor: 'rgba(255,0,0,0.6)',
                        fill: '-1',
                        backgroundColor: 'rgba(255,0,0,0.2)',
                        borderDash: [5,5],
                        spanGaps: true,
                        tension: 0.1,
                        pointRadius: 3,
                        pointBackgroundColor: 'rgba(255,0,0,0.6)',
                    }
                ] },
                options: {
                    responsive: true, maintainAspectRatio: false, animation: false,
                    scales: {
                        x: {
                            type: 'timeseries',
                            time: { unit: 'minute', displayFormats: { minute: 'HH:mm' } },
                            ticks: { source: 'data', maxRotation: 0, autoSkip: true }
                        },
                        y: { beginAtZero: false }
                    }
                }
            });
        },

        showNotification(event) {
            const message = event.detail[0]?.message || '✅ Data berhasil disimpan!';
            const notif = document.createElement('div');
            notif.innerText = message;
            notif.className = 'fixed top-5 right-5 bg-green-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.classList.add('opacity-0');
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        },
        showErrorNotification(event) {
            const message = event.detail[0]?.message || 'Terjadi kesalahan!';
            const notif = document.createElement('div');
            notif.innerText = `❌ ${message}`;
            notif.className = 'fixed top-5 right-5 bg-red-500 text-white py-2 px-4 rounded-lg shadow-lg transition-opacity duration-300 z-50';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.classList.add('opacity-0');
                setTimeout(() => notif.remove(), 300);
            }, 4000);
        }
    }" x-init="init()" @update-chart.window="updateChart($event)" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium">Tren Hemodinamik</h3>
                            <div class="relative mt-4 h-64"><canvas x-ref="canvas"></canvas></div>
                        </div>
                    </div>

                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium border-b pb-3">Ringkasan Balance Cairan 24 Jam</h3>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                <div>Total Masuk (CM): <span class="font-bold text-blue-600">{{ $totalIntake24h }} ml</span></div>
                                <div>Total Keluar (CK): <span class="font-bold text-red-600">{{ $totalOutput24h }} ml</span></div>

                                <div>Produksi Urine: <span class="font-bold">{{ $totalUrine24h }} ml</span></div>

                                <div class="flex items-center space-x-2">
                                    <label for="daily_iwl" class="whitespace-nowrap">IWL:</label>
                                    <input type="number" step="0.1" id="daily_iwl" wire:model.defer="daily_iwl" class="form-input py-1 px-2 text-sm w-20 rounded-md border-gray-300 shadow-sm">
                                    <button type="button" wire:click="saveDailyIwl" class="text-xs bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">Simpan</button>
                                </div>

                                <div class="col-span-1 sm:col-span-2 text-gray-600 mt-2">
                                    BC 24 Jam Sebelumnya:
                                    <span class="font-bold">
                                        {{ $previousBalance24h !== null ? ($previousBalance24h >= 0 ? '+' : '') . $previousBalance24h . ' ml' : 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 border-t pt-3 text-center text-sm sm:text-base">
                                Balance Cairan 24 Jam:
                                <span class="text-xl font-bold {{ $balance24h >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $balance24h >= 0 ? '+' : '' }}{{ $balance24h }} ml
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div x-show="$wire.activeOutputTab === 'observasi'" class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium border-b pb-3">Data Observasi Tercatat</h3>
                            <div class="overflow-x-auto mt-4">
                                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                                    <thead class="text-left">
                                        <thead class="text-left">
                                            <tr>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Jam</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Temp Ink</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Tensi</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">HR</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">RR</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Suhu</th>
                                            </tr>
                                        </thead>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($records as $record)
                                        @php
                                        $hasPrimaryData = $record->temp_incubator || $record->temp_skin || $record->hr || $record->rr ||$record->blood_pressure_systolic || $record->blood_pressure_diastolic
                                        @endphp
                                        @if ($hasPrimaryData)
                                        <tr>
                                            {{-- Selalu tampilkan waktu --}}
                                            <td class="px-3 py-2 font-medium align-top whitespace-nowrap">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</td>
                                            {{-- Tampilkan data primer jika ada --}}
                                            @if ($hasPrimaryData)
                                            <td class="px-3 py-2 align-top">{{ $record->temp_incubator }}</td>
                                            <td class="px-3 py-2 align-top whitespace-nowrap">{{ $record->blood_pressure_systolic }}/{{ $record->blood_pressure_diastolic }}</td>
                                            <td class="px-3 py-2 align-top">{{ $record->hr }}</td>
                                            <td class="px-3 py-2 align-top">{{ $record->rr }}</td>
                                            <td class="px-3 py-2 align-top">{{ $record->temp_skin }}</td>
                                            @endif
                                        </tr>
                                        @endif
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-4 text-gray-500">Belum ada data observasi.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium border-b pb-3">Data Apnea Warna</h3>
                            <div class="overflow-x-auto mt-4">
                                <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                                    <thead class="text-left">
                                        <thead class="text-left">
                                            <tr>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Jam</th>
                                                <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Kejadian</th>
                                            </tr>
                                        </thead>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($records as $record)
                                        @php
                                        $eventText = collect(['cyanosis', 'pucat', 'ikterus', 'bradikardia', 'stimulasi', 'crt_less_than_2'])
                                        ->filter(fn($key) => $record->$key ?? false) // Cek jika true
                                        ->map(fn($key) => ucfirst(str_replace('_', ' ', $key)))
                                        ->implode(', ');
                                        @endphp
                                        {{-- Tampilkan baris HANYA jika ada data primer ATAU ada teks kejadian --}}
                                        @if (!empty($eventText))
                                        <tr>
                                            <td class="px-3 py-2 font-medium align-top whitespace-nowrap">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</td>
                                            @if (!empty($eventText))
                                            <td class="px-3 py-1 text-xs text-gray-600 align-top" colspan="1">
                                                {{ $eventText }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endif
                                        @empty
                                        <tr>
                                            <td colspan="1" class="text-center p-4 text-gray-500">Belum ada data observasi warna.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="$wire.activeOutputTab === 'obat_cairan'" class="space-y-6">
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
                                    <tbody class="divide-y divide-gray-200">@forelse ($medications as $med) <tr>
                                            <td class="whitespace-nowrap px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($med->given_at)->format('H:i') }}</td>
                                            <td class="whitespace-nowrap px-3 py-2">{{ $med->medication_name }}</td>
                                            <td class="whitespace-nowrap px-3 py-2">{{ $med->dose }}</td>
                                            <td class="whitespace-nowrap px-3 py-2">{{ $med->route }}</td>
                                        </tr> @empty <tr>
                                            <td colspan="4" class="text-center p-4 text-gray-500">Belum ada obat yang dicatat.</td>
                                        </tr> @endforelse</tbody>
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
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900 text-center" colspan="4">Output (Keluar)</th>
                                        </tr>
                                        <tr>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Parenteral</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Enteral</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Total CM</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Urine/BAB</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">Residu</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-600">NGT/Drain</th>
                                            <th class="whitespace-nowrap px-3 py-2 font-medium text-gray-900">Total CK</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">@forelse ($records as $record) @php $totalParenteral = $record->parenteralIntakes->sum('volume'); $totalEnteral = ($record->intake_ogt ?? 0) + ($record->intake_oral ?? 0); $totalIntake = $totalParenteral + $totalEnteral; $totalOutput = ($record->output_urine ?? 0) + ($record->output_bab ?? 0) + ($record->output_residu ?? 0) + ($record->output_ngt ?? 0) + ($record->output_drain ?? 0); @endphp @if ($totalIntake > 0 || $totalOutput > 0) <tr>
                                            <td class="whitespace-nowrap px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($record->record_time)->format('H:i') }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">@foreach ($record->parenteralIntakes as $infus) <div class="text-xs">{{ $infus->name }}: {{ $infus->volume }} cc</div> @endforeach</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">@if($record->intake_ogt) <div class="text-xs">OGT: {{ $record->intake_ogt }} cc</div> @endif @if($record->intake_oral) <div class="text-xs">Oral: {{ $record->intake_oral }} cc</div> @endif</td>
                                            <td class="whitespace-nowrap px-3 py-2 font-bold text-blue-600">{{ $totalIntake }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">@if($record->output_urine) <div class="text-xs">Urine: {{ $record->output_urine }} cc</div> @endif @if($record->output_bab) <div class="text-xs">BAB: {{ $record->output_bab }} cc</div> @endif</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">{{ $record->output_residu }}</td>
                                            <td class="whitespace-nowrap px-3 py-2 text-gray-700">@if($record->output_ngt) <div class="text-xs">NGT: {{ $record->output_ngt }} cc</div> @endif @if($record->output_drain) <div class="text-xs">Drain: {{ $record->output_drain }} cc</div> @endif</td>
                                            <td class="whitespace-nowrap px-3 py-2 font-bold text-red-600">{{ $totalOutput }}</td>
                                        </tr> @endif @empty <tr>
                                            <td colspan="8" class="text-center p-4 text-gray-500">Belum ada data keseimbangan cairan.</td>
                                        </tr> @endforelse</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="$wire.activeOutputTab === 'penilaian_lab'" class="space-y-6">
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
                                    <tbody class="divide-y divide-gray-200">@forelse ($bloodGasResults as $result) <tr>
                                            <td class="px-3 py-2 font-medium">{{ \Carbon\Carbon::parse($result->taken_at)->format('H:i') }}</td>
                                            <td class="px-3 py-2">{{ $result->gula_darah }}</td>
                                            <td class="px-3 py-2">{{ $result->ph }}</td>
                                            <td class="px-3 py-2">{{ $result->pco2 }}</td>
                                            <td class="px-3 py-2">{{ $result->po2 }}</td>
                                            <td class="px-3 py-2">{{ $result->hco3 }}</td>
                                            <td class="px-3 py-2">{{ $result->be }}</td>
                                            <td class="px-3 py-2">{{ $result->sao2 }}</td>
                                        </tr> @empty <tr>
                                            <td colspan="8" class="text-center p-4 text-gray-500">Belum ada hasil gas darah.</td>
                                        </tr> @endforelse</tbody>
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
                                    <tbody class="divide-y divide-gray-200">@forelse ($pippAssessments as $score) <tr>
                                            <td class="px-2 py-2 font-medium">{{ \Carbon\Carbon::parse($score->assessment_time)->format('H:i') }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->gestational_age }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->behavioral_state }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->max_heart_rate }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->min_oxygen_saturation }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->brow_bulge }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->eye_squeeze }}</td>
                                            <td class="px-2 py-2 text-center">{{ $score->nasolabial_furrow }}</td>
                                            <td class="px-2 py-2 text-center font-bold text-lg {{ $score->total_score > 12 ? 'text-red-600' : ($score->total_score > 6 ? 'text-yellow-600' : 'text-green-600') }}">{{ $score->total_score }}</td>
                                        </tr> @empty <tr>
                                            <td colspan="9" class="text-center p-4 text-gray-500">Belum ada penilaian PIPP.</td>
                                        </tr> @endforelse</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($showEventModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeEventModal"></div>
        <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-medium text-gray-900">Catat Kejadian</h3>
            <p class="text-sm text-gray-500 mt-1">Pilih semua kejadian yang terjadi pada waktu yang sama.</p>
            <div class="mt-4 space-y-2 border-t pt-4">
                <div class="grid grid-cols-2 gap-x-4 gap-y-2"><label for="event_cyanosis" class="flex items-center space-x-2"><input id="event_cyanosis" type="checkbox" wire:model="event_cyanosis" class="rounded border-gray-300"><span>Cyanosis</span></label><label for="event_pucat" class="flex items-center space-x-2"><input id="event_pucat" type="checkbox" wire:model="event_pucat" class="rounded border-gray-300"><span>Pucat</span></label><label for="event_ikterus" class="flex items-center space-x-2"><input id="event_ikterus" type="checkbox" wire:model="event_ikterus" class="rounded border-gray-300"><span>Ikterus</span></label><label for="event_crt" class="flex items-center space-x-2"><input id="event_crt" type="checkbox" wire:model="event_crt_less_than_2" class="rounded border-gray-300"><span>CRT &lt; 2 detik</span></label><label for="event_bradikardia" class="flex items-center space-x-2"><input id="event_bradikardia" type="checkbox" wire:model="event_bradikardia" class="rounded border-gray-300"><span>Bradikardia</span></label><label for="event_stimulasi" class="flex items-center space-x-2"><input id="event_stimulasi" type="checkbox" wire:model="event_stimulasi" class="rounded border-gray-300"><span>Stimulasi</span></label></div>
            </div>
            <div class="mt-6 flex justify-end space-x-3"><button type="button" wire:click="closeEventModal" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button><button type="button" wire:click="saveEvent" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">Simpan Kejadian</button></div>
        </div>
    </div>
    @endif
    @if ($showPippModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-800/60 transition-opacity" wire:click="closePippModal"></div>
        <!-- Modal -->
        <div x-data="{
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
        }" class="relative bg-white/90 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-2xl p-6 w-full max-w-4xl animate-in fade-in zoom-in duration-200">
            <!-- Header -->
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2 mb-2">
                🍼 Penilaian Nyeri Prematur (PIPP)
            </h3>
            <p class="text-sm text-gray-500 mb-4">Gunakan panduan ini untuk menilai tingkat nyeri bayi prematur berdasarkan parameter klinis.</p>

            <!-- Body -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 border-t border-gray-200 pt-4">
                <!-- Waktu -->
                <div class="col-span-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">🕒 Waktu Penilaian</label>
                    <div class="w-full rounded-lg bg-gray-100 text-gray-800 px-3 py-2 text-sm border border-gray-200">
                        {{ \Carbon\Carbon::parse($pipp_assessment_time)->format('d M Y, H:i') }}
                    </div>
                </div>

                @foreach ([
                ['gestational_age', 'Usia Gestasi', [
                '0: ≥ 36 mgg', '1: 32–35 mgg + 6h', '2: 28–31 mgg + 6h', '3: < 28 mgg' ]], ['behavioral_state', 'Perilaku Bayi (15 detik)' , [ '0: Aktif/bangun, mata terbuka' , '1: Diam/bangun, mata terbuka/tertutup' , '2: Aktif/tidur, mata tertutup' , '3: Tenang/tidur, gerak minimal' ]], ['max_heart_rate', 'Laju Nadi Maks (peningkatan)' , [ '0: 0–4 dpm' , '1: 5–14 dpm' , '2: 15–24 dpm' , '3: ≥25 dpm' ]], ['min_oxygen_saturation', 'Saturasi O₂ Minimum (penurunan)' , [ '0: 92–100%' , '1: 89–91%' , '2: 85–88%' , '3: <85%' ]], ['brow_bulge', 'Tarikan Alis (% waktu)' , [ '0: Tidak ada (<9%)' , '1: Minimum (10–39%)' , '2: Sedang (40–69%)' , '3: Maksimum (≥70%)' ]], ['eye_squeeze', 'Kerutan Mata (% waktu)' , [ '0: Tidak ada (<9%)' , '1: Minimum (10–39%)' , '2: Sedang (40–69%)' , '3: Maksimum (≥70%)' ]], ['nasolabial_furrow', 'Alur Nasolabial (% waktu)' , [ '0: Tidak ada (<9%)' , '1: Minimum (10–39%)' , '2: Sedang (40–69%)' , '3: Maksimum (≥70%)' ]] ] as [$id, $label, $options]) <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <select x-model="{{ $id }}" class="w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @foreach ($options as $i => $opt)
                        <option value="{{ $i }}">{{ $opt }}</option>
                        @endforeach
                    </select>
            </div>
            @endforeach
        </div>
        <!-- Total Skor -->
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
        <!-- Footer -->
        <div class="mt-8 flex justify-end space-x-3">
            <button type="button" wire:click="closePippModal" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                Batal
            </button>
            <button type="button" wire:click="savePippScore" class="px-5 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 active:scale-[0.98] transition transform">
                💾 Simpan Skor PIPP
            </button>
        </div>
    </div>
    @endif

    @if ($showDeviceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeDeviceModal"></div>
        <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h3 class="text-lg font-medium text-gray-900">{{ $editingDeviceId ? 'Edit Alat Terpasang' : 'Tambah Alat Baru' }}</h3>
            <div class="mt-4 space-y-4 border-t pt-4">
                <div><label for="device_name" class="block text-sm font-medium">Nama Alat</label><input id="device_name" type="text" wire:model="device_name" class="mt-1 w-full form-input" placeholder="Contoh: CVC, ETT, Kateter Urin"></div>
                <div class="grid grid-cols-3 gap-4">
                    <div><label for="size" class="block text-sm font-medium">Ukuran</label><input id="size" type="text" wire:model="size" class="mt-1 w-full form-input" placeholder="Contoh: 7 Fr"></div>
                    <div><label for="location" class="block text-sm font-medium">Lokasi</label><input id="location" type="text" wire:model="location" class="mt-1 w-full form-input" placeholder="Contoh: V. Subklavia Ka"></div>
                    <div><label for="installation_date" class="block text-sm font-medium">Tgl. Pasang</label><input id="installation_date" type="date" wire:model="installation_date" class="mt-1 w-full form-input"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-between"><button type="button" wire:click="closeDeviceModal" class="px-4 py-2 text-sm bg-white border rounded-md">Batal</button><button type="button" wire:click="saveDevice" class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-md">{{ $editingDeviceId ? 'Update Alat' : 'Simpan Alat' }}</button></div>
        </div>
    </div>
    @endif
    @if ($showMedicationModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900 opacity-75" wire:click="closeMedicationModal"></div>
        <div class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h3 class="text-lg font-medium text-gray-900">Tambah Pemberian Obat</h3>
            <div class="mt-4 space-y-4 border-t pt-4">
                <div><label class="block text-sm font-medium">Waktu Pemberian</label>
                    <div class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm px-3 py-2 sm:text-sm text-gray-700">{{ \Carbon\Carbon::parse($given_at)->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <label for="medication_name" class="block text-sm font-medium">Nama Obat</label>
                    <input id="medication_name" type="text" wire:model="medication_name" list="recent-meds" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Ketik atau pilih dari riwayat...">
                    <datalist id="recent-meds">
                        @foreach($recentMedicationNames as $name)
                        <option value="{{ $name }}">
                            @endforeach
                    </datalist>
                </div>
                {{-- <div><label for="medication_name" class="block text-sm font-medium">Nama Obat</label><input id="medication_name" type="text" wire:model="medication_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Aminofilin"></div> --}}
                <div class="grid grid-cols-2 gap-4">
                    <div><label for="dose" class="block text-sm font-medium">Dosis</label><input id="dose" type="text" wire:model="dose" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 3x80mg"></div>
                    <div><label for="route" class="block text-sm font-medium">Rute</label><input id="route" type="text" wire:model="route" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: IV"></div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3"><button type="button" wire:click="closeMedicationModal" class="px-4 py-2 text-sm font-medium bg-white border border-gray-300 rounded-md hover:bg-gray-50">Batal</button><button type="button" wire:click="saveMedication" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">Simpan Obat</button></div>
        </div>
    </div>
    @endif
    @if ($showBloodGasModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-800/60 transition-opacity" wire:click="closeBloodGasModal"></div>

        <!-- Modal -->
        <div class="relative bg-white/90 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-2xl p-6 w-full max-w-3xl animate-in fade-in zoom-in duration-200">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                🩸 Catat Hasil Gas Darah
            </h3>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-5 border-t border-gray-200 pt-4">
                <!-- Field waktu -->
                <div>
                    <label for="taken_at" class="text-sm font-medium text-gray-700">Waktu Pengambilan</label>
                    <input id="taken_at" type="datetime-local" wire:model="taken_at" class="mt-1 w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <p class="mt-1 text-xs text-gray-500">Disimpan dalam WITA (24 jam).</p>
                </div>

                @foreach ([
                ['gula_darah', 'Gula Darah (BS)', '0.1'],
                ['ph', 'pH', '0.01'],
                ['pco2', 'PCO₂', '0.1'],
                ['po2', 'PO₂', '0.1'],
                ['hco3', 'HCO₃', '0.1'],
                ['be', 'BE', '0.1'],
                ['sao2', 'SaO₂', '0.1'],
                ] as [$id, $label, $step])
                <div>
                    <label for="{{ $id }}" class="text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input id="{{ $id }}" type="number" step="{{ $step }}" wire:model="{{ $id }}" class="mt-1 w-full rounded-lg border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" wire:click="closeBloodGasModal" class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="button" wire:click="saveBloodGasResult" class="px-5 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 active:scale-[0.98] transition transform">
                    💾 Simpan Hasil
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
