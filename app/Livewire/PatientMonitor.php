<?php

namespace App\Livewire;

use App\Models\BloodGasResult;
use App\Models\Medication;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use App\Models\PatientDevice;
use App\Models\PippAssessment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
class PatientMonitor extends Component
{
    public bool $readyToLoad = false;
    public string $activeTab = 'observasi';

    #[Locked]
    public $patient;
    public string $no_rawat;
    public $record_time;
    public $temp_incubator, $temp_skin, $hr, $rr;
    public $blood_pressure_systolic, $blood_pressure_diastolic;
    public $sat_o2, $irama_ekg, $skala_nyeri, $humidifier_inkubator;
    #[Locked]
    public Collection $records;
    #[Locked]
    public Collection $fluidRecords;
    #[Locked]
    public Collection $medications;
    #[Locked]
    public Collection $bloodGasResults;
    #[Locked]
    public Collection $patientDevices;
    #[Locked]
    public Collection $pippAssessments;
    #[Locked]
    public Collection $recentMedicationNames;
    // Properties for PIPP Modal
    public $pipp_assessment_time;
    public $gestational_age = 0, $behavioral_state = 0, $max_heart_rate = 0;
    public $min_oxygen_saturation = 0, $brow_bulge = 0, $eye_squeeze = 0, $nasolabial_furrow = 0;
    public $pipp_total_score = 0;
    public $assessment_time;
    public $facial_expression = 0, $cry = 0, $breathing_pattern = 0;
    public $arms_movement = 0, $legs_movement = 0, $state_of_arousal = 0;
    public $total_score = 0; // Calculated score

    // Properti untuk Modal Alat
    public $currentCycleId;
    public ?MonitoringCycle $currentCycle = null;

    public bool $cyanosis = false;
    public bool $pucat = false;
    public bool $ikterus = false;
    public bool $crt_less_than_2 = false;
    public bool $bradikardia = false;
    public bool $stimulasi = false;
    public bool $showEventModal = false;
    public bool $event_cyanosis = false;
    public bool $event_pucat = false;
    public bool $event_ikterus = false;
    public bool $event_crt_less_than_2 = false;
    public bool $event_bradikardia = false;
    public bool $event_stimulasi = false;
    public $selectedDate;
    public string $activeOutputTab = 'ringkasan';
    public $respiratory_mode;
    public $spontan_fio2, $spontan_flow;
    public $cpap_fio2, $cpap_flow, $cpap_peep;
    public $hfo_fio2, $hfo_frekuensi, $hfo_map, $hfo_amplitudo, $hfo_it;
    public $monitor_mode, $monitor_fio2, $monitor_peep, $monitor_pip;
    public $monitor_tv_vte, $monitor_rr_spontan, $monitor_p_max, $monitor_ie;
    // Properti untuk Keseimbangan Cairan
    public $intake_ogt, $intake_oral;
    public $output_urine, $output_bab, $output_residu;
    public $output_ngt, $output_drain;

    // Properti untuk Infus (Parenteral) Dinamis
    public array $parenteral_intakes = [];
    public array $enteral_intakes = [];

    // Properti untuk Modal Obat
    public $medication_name, $dose, $route, $given_at;


    // Properti untuk Modal Blood Gas
    public $taken_at;
    public $gula_darah, $ph, $pco2, $po2, $hco3, $be, $sao2;

    public $daily_iwl; // Input untuk IWL harian
    public $totalIntake24h = 0;
    public $totalOutput24h = 0;
    public $totalUrine24h = 0;
    public $balance24h = 0;
    public $previousBalance24h = null;


    public function loadData()
    {
        $this->readyToLoad = true;
        $this->loadRecords();
        $this->loadPatientDevices();
    }

    public function confirmRemoveDevice($deviceId)
    {

        if (!$deviceId) {
            return; // Jika tidak ada ID, hentikan
        }
        $device = PatientDevice::find($deviceId);

        // Validasi keamanan
        if ($device && $device->no_rawat === $this->no_rawat) {

            // Logika "Cabut" yang benar (mengisi removal_date)
            $device->removal_date = now();
            $device->removed_by_user_id = auth()->id();
            $device->save();
            $this->dispatch('refresh-devices');
            $this->dispatch('record-saved', ['message' => 'Alat berhasil dilepas!']);
        }

    }
    public function mount(string $no_rawat): void
    {
        $this->no_rawat = $no_rawat;
        $now = now();
        $cycleStartTime = $now->copy()->startOfDay()->addHours(6);
        if ($now->hour < 6) {
            $cycleStartTime->subDay();
        }

        $this->currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('start_time', $cycleStartTime)
            ->first();

        if ($this->currentCycle) {
            $this->currentCycleId = $this->currentCycle->id;
        } else {
            $this->currentCycleId = null; // Pastikan null jika belum ada
        }
        $this->record_time = now()->format('Y-m-d\TH:i');
        $this->records = new \Illuminate\Database\Eloquent\Collection();
        $this->medications = new \Illuminate\Database\Eloquent\Collection();
        $this->bloodGasResults = new \Illuminate\Database\Eloquent\Collection();
        $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
        $this->pippAssessments = new \Illuminate\Database\Eloquent\Collection();
        $this->recentMedicationNames = new \Illuminate\Support\Collection();
        $this->fluidRecords = new Collection();
        $this->taken_at = now()->format('Y-m-d\TH:i');
        $this->selectedDate = now()->format('Y-m-d');
    }
    public function loadPatientDevices(): void
    {
        if (!$this->readyToLoad || !$this->currentCycle) { // Tambahkan cek $this->currentCycle
            $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
            return;
        }

        $cycleStart = $this->currentCycle->start_time;
        $cycleEnd = $this->currentCycle->end_time;

        $this->patientDevices = PatientDevice::with(['installer', 'remover'])
            ->where('no_rawat', $this->no_rawat)
            ->where('installation_date', '<=', $cycleEnd)
            ->where(function ($query) use ($cycleStart) {
                $query->whereNull('removal_date')
                    ->orWhere('removal_date', '>=', $cycleStart);
            })
            ->orderBy('installation_date')
            ->get();
    }

    public function saveDevice($data)
    {
        $validated = Validator::make($data, [
            'device_name' => 'required|string|max:255',
            'size' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'installation_date' => 'required|date_format:Y-m-d\TH:i',
        ])->validate();

        try {
            // 2. Langsung 'Create', tidak ada logika 'Update'
            PatientDevice::create([
                'no_rawat' => $this->no_rawat,
                'device_name' => $validated['device_name'],
                'size' => $validated['size'],
                'location' => $validated['location'],
                'installation_date' => $validated['installation_date'],
                'installed_by_user_id' => auth()->id(),
            ]);

            $this->dispatch('record-saved', ['message' => 'Alat baru berhasil ditambahkan!']);
            $this->dispatch('refresh-devices');
            return true;

        } catch (\Exception $e) {
            $this->dispatch('error-notification', ['message' => 'Gagal menyimpan: ' . $e->getMessage()]);
            return false;
        }
    }
    #[On('refresh-devices')]
    public function loadPatientDevicesOnly()
    {
        if (!$this->readyToLoad || !$this->currentCycle) {
            $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
            return;
        }

        $cycleStart = $this->currentCycle->start_time;
        $cycleEnd = $this->currentCycle->end_time;

        $this->patientDevices = PatientDevice::with(['installer', 'remover'])
            ->where('no_rawat', $this->no_rawat)
            ->where('installation_date', '<=', $cycleEnd)
            ->where(function ($query) use ($cycleStart) {
                $query->whereNull('removal_date')
                    ->orWhere('removal_date', '>=', $cycleStart);
            })
            ->orderBy('installation_date')
            ->get();
    }
    private function reloadRepeaterNames()
    {
        // Koleksi default jika tidak ada data
        $uniqueInfusionNames = collect();
        $uniqueEnteralNames = collect();

        // 1. Coba cari nama di SIKLUS SAAT INI dulu
        if ($this->currentCycleId) {
            $currentRecordIds = MonitoringRecord::where('monitoring_cycle_id', $this->currentCycleId)->pluck('id');

            if ($currentRecordIds->isNotEmpty()) {
                $uniqueInfusionNames = \App\Models\ParenteralIntake::whereIn('monitoring_record_id', $currentRecordIds)
                    ->distinct()
                    ->pluck('name');
                $uniqueEnteralNames = \App\Models\EnteralIntake::whereIn('monitoring_record_id', $currentRecordIds)
                    ->distinct()
                    ->pluck('name');
            }
        }

        // 2. Jika SIKLUS SAAT INI KOSONG, coba cari di SIKLUS SEBELUMNYA
        if ($uniqueInfusionNames->isEmpty() && $uniqueEnteralNames->isEmpty()) {
            // Tentukan waktu mulai siklus saat ini (logika dari loadRecords)
            $targetDate = Carbon::parse(time: $this->selectedDate)->startOfDay();
            $cycleStartTime = $targetDate->copy()->addHours(6);
            if ($targetDate->isToday() && now()->hour < 6) {
                $cycleStartTime->subDay();
            }

            // Cari siklus sebelumnya
            $previousCycleStartTime = $cycleStartTime->copy()->subDay();
            $previousCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
                ->where('start_time', $previousCycleStartTime)
                ->first();

            // Jika siklus sebelumnya ADA, ambil nama dari sana
            if ($previousCycle) {
                $previousRecordIds = MonitoringRecord::where('monitoring_cycle_id', $previousCycle->id)->pluck('id');

                if ($previousRecordIds->isNotEmpty()) {
                    // Hanya ambil jika nama saat ini benar-benar kosong
                    if ($uniqueInfusionNames->isEmpty()) {
                        $uniqueInfusionNames = \App\Models\ParenteralIntake::whereIn('monitoring_record_id', $previousRecordIds)
                            ->distinct()
                            ->pluck('name');
                    }
                    if ($uniqueEnteralNames->isEmpty()) {
                        $uniqueEnteralNames = \App\Models\EnteralIntake::whereIn('monitoring_record_id', $previousRecordIds)
                            ->distinct()
                            ->pluck('name');
                    }
                }
            }
        }

        // 3. Isi ulang array properti (seperti sebelumnya)
        $this->parenteral_intakes = $uniqueInfusionNames->map(fn($name) => [
            'name' => $name,
            'volume' => null
        ])->toArray();

        $this->enteral_intakes = $uniqueEnteralNames->map(fn($name) => [
            'name' => $name,
            'volume' => null
        ])->toArray();
    }
    public function saveRecord(): void
    {
        $enteralData = $this->enteral_intakes;
        foreach ($enteralData as $index => $enteral) {
            if (isset($enteral['name']) && strtolower($enteral['name']) === 'puasa') {
                $enteralData[$index]['name'] = 'puasa';
            }
        }
        $this->enteral_intakes = $enteralData;
        $this->validate([
            'record_time' => 'required|date',
            'parenteral_intakes.*.name' => 'required_with:parenteral_intakes.*.volume|nullable|string',
            'parenteral_intakes.*.volume' => 'nullable|numeric|min:0',
            'enteral_intakes.*.name' => 'required_with:enteral_intakes.*.volume|nullable|string',
            'enteral_intakes.*.volume' => 'nullable|numeric|min:0|exclude_if:enteral_intakes.*.name,puasa',
        ]);

        $fieldsToCheck = [
            'temp_incubator',
            'temp_skin',
            'hr',
            'rr',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'sat_o2',
            'irama_ekg',
            'skala_nyeri',
            'humidifier_inkubator',
            'cyanosis',
            'pucat',
            'ikterus',
            'crt_less_than_2',
            'bradikardia',
            'stimulasi',
            'respiratory_mode',
            'spontan_fio2',
            'spontan_flow',
            'cpap_fio2',
            'cpap_flow',
            'cpap_peep',
            'hfo_fio2',
            'hfo_frekuensi',
            'hfo_map',
            'hfo_amplitudo',
            'hfo_it',
            'monitor_mode',
            'monitor_fio2',
            'monitor_peep',
            'monitor_pip',
            'monitor_tv_vte',
            'monitor_rr_spontan',
            'monitor_p_max',
            'monitor_ie',
            'intake_ogt',
            'intake_oral',
            'output_urine',
            'output_bab',
            'output_residu',
            'output_ngt',
            'output_drain',
        ];

        // ===============================================
        // 3. LOGIKA PENGECEKAN (Perbaikan untuk menghitung volume '0')
        // ===============================================
        $hasMainData = collect($fieldsToCheck)->some(fn($f) => !empty($this->$f) && $this->$f !== null);

        // Perbaikan: Cek 'isset' dan '!=' '' alih-alih '!empty()' agar '0' terhitung
        $hasParenteral = collect($this->parenteral_intakes)->some(fn($i) => isset($i['volume']) && $i['volume'] !== '' && $i['volume'] !== null);

        $hasEnteral = collect($this->enteral_intakes)->some(fn($i) => (isset($i['volume']) && $i['volume'] !== '' && $i['volume'] !== null) || strtolower($i['name']) === 'puasa');

        if (!$hasMainData && !$hasParenteral && !$hasEnteral) {
            $this->addError('record', 'Minimal satu field harus diisi.');
            return;
        }
        $now = Carbon::parse($this->record_time);
        $cycleStartTime = $now->copy()->startOfDay()->addHours(6);
        if ($now->hour < 6) {
            $cycleStartTime->subDay();
        }
        $cycleEndTime = $cycleStartTime->copy()->addDay()->subSecond();

        $cycle = MonitoringCycle::firstOrCreate(
            ['no_rawat' => $this->no_rawat, 'start_time' => $cycleStartTime],
            ['end_time' => $cycleEndTime]
        );
        $record = MonitoringRecord::updateOrCreate(
            [
                'monitoring_cycle_id' => $cycle->id,
                'record_time' => $now,
            ],
            array_merge(
                [
                    'monitoring_cycle_id' => $cycle->id,
                    'id_user' => auth()->id(),
                    'record_time' => $this->record_time,
                ],
                collect($fieldsToCheck)->mapWithKeys(fn($f) => [
                    $f => $this->$f !== null ? $this->$f : null
                ])->toArray()
            )
        );

        foreach ($this->parenteral_intakes as $intake) {
            if (!isset($intake['volume']) || $intake['volume'] === '' || $intake['volume'] === null) {
                continue; // Lewati baris kosong
            }
            $record->parenteralIntakes()->create([
                'name' => $intake['name'],
                'volume' => $intake['volume'],
            ]);
        }

        // =========================
        // Simpan enteral (Perbaikan untuk volume '0' dan 'Puasa')
        // =========================
        foreach ($this->enteral_intakes as $enteral) {
            // Cek 'puasa' (sudah di-lowercase)
            $isPuasa = isset($enteral['name']) && $enteral['name'] === 'puasa';

            // Cek volume kosong (agar '0' tidak dianggap kosong)
            $volumeIsEmpty = !isset($enteral['volume']) || $enteral['volume'] === '' || $enteral['volume'] === null;

            // Lewati jika volume kosong DAN namanya BUKAN 'puasa'
            if ($volumeIsEmpty && !$isPuasa) {
                continue; // Lewati baris kosong
            }

            $record->enteralIntakes()->create([
                'name' => $enteral['name'],
                // Pastikan volume null jika 'puasa'
                'volume' => $isPuasa ? null : $enteral['volume'],
            ]);
        }

        // =========================
        // Selesai (Sudah Benar)
        // =========================
        $this->resetForm();
        $this->reloadRepeaterNames();
        $this->dispatch('refresh-observasi');
        $this->dispatch('record-saved');
    }

    /**
     * Mencatat event observasi tunggal (seperti Cyanosis, Pucat, dll.)
     */
    public function resetForm(): void
    {
        $this->reset([
            'temp_incubator',
            'temp_skin',
            'hr',
            'rr',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'sat_o2',
            'irama_ekg',
            'skala_nyeri',
            'humidifier_inkubator',
            'cyanosis',
            'pucat',
            'ikterus',
            'crt_less_than_2',
            'bradikardia',
            'stimulasi',
            'respiratory_mode',
            'spontan_fio2',
            'spontan_flow',
            'cpap_fio2',
            'cpap_flow',
            'cpap_peep',
            'hfo_fio2',
            'hfo_frekuensi',
            'hfo_map',
            'hfo_amplitudo',
            'hfo_it',
            'monitor_mode',
            'monitor_fio2',
            'monitor_peep',
            'monitor_pip',
            'monitor_tv_vte',
            'monitor_rr_spontan',
            'monitor_p_max',
            'monitor_ie',
            'intake_ogt',
            'intake_oral',
            'output_urine',
            'output_bab',
            'output_residu',
            'output_ngt',
            'output_drain'
        ]);
        $this->record_time = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.patient-monitor')->layout('layouts.app');
    }

    public $latestTempIncubator = null;
    public $latestTempSkin = null;
    public $latestHR = null;
    public $latestRR = null;
    public $latestBPSystolic = null;
    public $latestBPDiastolic = null;
    public $latestMAP = null;

    #[On('refresh-observasi')]
    public function loadRecords(): void
    {
        if (!$this->readyToLoad) {
            $this->records = new \Illuminate\Database\Eloquent\Collection();
            $this->medications = new \Illuminate\Database\Eloquent\Collection();
            $this->bloodGasResults = new \Illuminate\Database\Eloquent\Collection();
            $this->pippAssessments = new \Illuminate\Database\Eloquent\Collection();
            $this->recentMedicationNames = new \Illuminate\Support\Collection();
            $this->fluidRecords = new Collection();
            return;
        }
        // Pastikan kita mulai dari awal hari tanggal yang dipilih
        $targetDate = Carbon::parse(time: $this->selectedDate)->startOfDay();
        // Asumsi default: siklus dimulai jam 6 pagi pada tanggal yang dipilih
        $cycleStartTime = $targetDate->copy()->addHours(6);
        if ($targetDate->isToday() && now()->hour < 6) {
            $cycleStartTime->subDay();
        }
        $currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('start_time', $cycleStartTime)
            ->first();
        $previousCycleStartTime = $cycleStartTime->copy()->subDay();
        $previousCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('start_time', $previousCycleStartTime)
            ->first();
        if ($previousCycle && is_null($previousCycle->calculated_balance_24h)) {
            $this->calculateAndSaveBalance($previousCycle);
            // Muat ulang data previousCycle setelah disimpan
            $previousCycle->refresh();
        }

        // 2. Muat Balance Siklus Sebelumnya untuk Ditampilkan
        $this->previousBalance24h = $previousCycle ? $previousCycle->calculated_balance_24h : null;

        // 3. Muat Data Siklus Saat Ini
        if ($currentCycle) {
            $this->currentCycleId = $currentCycle->id;
            $this->daily_iwl = $currentCycle->daily_iwl;

            $this->medications = Medication::with('pegawai')->where('monitoring_cycle_id', $currentCycle->id)->orderBy('given_at', 'desc')->get();
            $this->bloodGasResults = BloodGasResult::with('pegawai')->where('monitoring_cycle_id', $currentCycle->id)->orderBy('taken_at', 'desc')->get();
            $this->pippAssessments = PippAssessment::with('pegawai')->where('monitoring_cycle_id', $currentCycle->id)
                ->orderBy('assessment_time', 'desc')
                ->get();
            // Ambil SEMUA record siklus ini untuk kalkulasi total
            $allCycleRecords = MonitoringRecord::with('parenteralIntakes', 'enteralIntakes', 'pegawai')
                ->where('monitoring_cycle_id', $currentCycle->id)
                ->get();
            $this->recentMedicationNames = Medication::where('monitoring_cycle_id', $currentCycle->id)
                ->distinct()
                ->orderBy('medication_name', 'asc')
                ->pluck('medication_name');
            // Kalkulasi total untuk TAMPILAN siklus saat ini
            $this->totalIntake24h = $allCycleRecords->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume') + $r->enteralIntakes->sum('volume'));
            $this->totalOutput24h = $allCycleRecords->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
            $this->totalUrine24h = $allCycleRecords->sum('output_urine');
            $this->balance24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);
            $filteredFluidRecords = $allCycleRecords->filter(function ($record) {
                // 1. Cek relasi (ini cepat karena sudah di-eager load)
                if ($record->parenteralIntakes->sum('volume') > 0) {
                    return true;
                }
                if ($record->enteralIntakes->sum('volume') > 0) {
                    return true;
                }

                // 2. Cek kolom langsung di tabel monitoring_records
                // (Menggunakan '?? 0' agar aman jika nilainya NULL)
                $hasIntake = ($record->intake_ogt ?? 0) > 0 ||
                    ($record->intake_oral ?? 0) > 0;

                $hasOutput = ($record->output_urine ?? 0) > 0 ||
                    ($record->output_bab ?? 0) > 0 ||
                        // ($record->output_residu ?? 0) > 0 || // <-- Termasuk kolom ini
                    ($record->output_ngt ?? 0) > 0 ||
                    ($record->output_drain ?? 0) > 0;

                // Kembalikan true (tampilkan record) HANYA JIKA ada intake ATAU output
                return $hasIntake || $hasOutput;
            });
            $this->fluidRecords = $filteredFluidRecords->sortByDesc('record_time');
            $this->records = $allCycleRecords->sortByDesc('record_time');
            // Data untuk Grafik (ASC)
            $chartRecords = $allCycleRecords->sortBy('record_time');

            $dataset = fn($field) => $chartRecords->map(fn($r) => [
                'x' => $r->record_time,
                'y' => is_numeric($r->$field) ? (float) $r->$field : null,
            ]);

            $chartData = [
                'temp_incubator' => $dataset('temp_incubator'),
                'temp_skin' => $dataset('temp_skin'),
                'hr' => $dataset('hr'),
                'rr' => $dataset('rr'),
                'bp_systolic' => $dataset('blood_pressure_systolic'),
                'bp_diastolic' => $dataset('blood_pressure_diastolic'),
            ];
            $chartData = collect([
                'temp_incubator' => 'temp_incubator',
                'temp_skin' => 'temp_skin',
                'hr' => 'hr',
                'rr' => 'rr',
                'bp_systolic' => 'blood_pressure_systolic',
                'bp_diastolic' => 'blood_pressure_diastolic',
            ])
                ->mapWithKeys(fn($dbField, $chartKey) => [
                    $chartKey => $chartRecords
                        ->filter(fn($r) => is_numeric($r->$dbField) && $r->$dbField !== null)
                        ->map(fn($r) => [
                            'x' => $r->record_time,
                            'y' => (float) $r->$dbField,
                        ])
                        ->values()
                ])->toArray();

            // Kirim ke frontend
            $latestRecord = $allCycleRecords->sortByDesc('record_time')->first();
            if ($latestRecord) {
                $this->latestTempIncubator = $latestRecord->temp_incubator;
                $this->latestTempSkin = $latestRecord->temp_skin;
                $this->latestHR = $latestRecord->hr;
                $this->latestRR = $latestRecord->rr;
                $this->latestBPSystolic = $latestRecord->blood_pressure_systolic;
                $this->latestBPDiastolic = $latestRecord->blood_pressure_diastolic;
                // Hitung MAP (jika Sistolik dan Diastolik ada)
                if (!is_null($this->latestBPSystolic) && !is_null($this->latestBPDiastolic)) {
                    $systolic = (float) $this->latestBPSystolic;
                    $diastolic = (float) $this->latestBPDiastolic;
                    // Rumus MAP: Diastolik + 1/3 (Sistolik - Diastolik)
                    $this->latestMAP = round($diastolic + (1 / 3) * ($systolic - $diastolic));
                } else {
                    $this->latestMAP = null;
                }
            } else {
                // Reset nilai jika tidak ada record
                $this->latestTempIncubator = $this->latestTempSkin = $this->latestHR = $this->latestRR = null;
                $this->latestBPSystolic = $this->latestBPDiastolic = $this->latestMAP = null;
            }
            $this->dispatch('update-chart', ['chartData' => $chartData]);
            // 1. Ambil semua ID record dari siklus ini
            $recordIds = $allCycleRecords->pluck('id');
            // 2. Cari semua nama infus unik yang pernah tercatat di siklus ini
            $uniqueInfusionNames = \App\Models\ParenteralIntake::whereIn('monitoring_record_id', $recordIds)
                ->distinct()
                ->pluck('name');
            $uniqueEnteralNames = \App\Models\EnteralIntake::whereIn('monitoring_record_id', $recordIds)
                ->distinct()
                ->pluck('name');
            // 3. Isi ulang array $parenteral_intakes hanya dengan NAMA, volume dikosongkan
            $this->parenteral_intakes = $uniqueInfusionNames->map(fn($name) => [
                'name' => $name,
                'volume' => ''
            ])->toArray();
            // 3. Isi ulang array $enternal hanya dengan NAMA, volume dikosongkan
            $this->enteral_intakes = $uniqueEnteralNames->map(fn($name) => [
                'name' => $name,
                'volume' => ''
            ])->toArray();
        } else {
            $this->currentCycleId = null;
            $this->medications = new Collection();
            $this->daily_iwl = null;
            $this->balance24h = 0;
            $this->totalIntake24h = 0;
            $this->totalOutput24h = 0;
            $this->totalUrine24h = 0;
            $this->records = new Collection();
            $this->medications = new Collection();
            $this->bloodGasResults = new Collection();
            $this->pippAssessments = new Collection();
            $this->recentMedicationNames = new Collection();
            $this->parenteral_intakes = [];
            $this->enteral_intakes = [];
            $this->dispatch('update-chart', ['chartData' => []]);
        }
        $this->reloadRepeaterNames();
        $this->dispatch('repeaters-ready');
    }
    /**
     * FUNGSI BARU 1:
     * Mengambil data dari riwayat yang diklik dan mengisinya ke form input.
     */
    public function loadHistoryToForm($programId)
    {
        // Cari program dari riwayat yang sudah di-load (ini lebih efisien)
        $program = $this->therapy_program_history->find($programId);

        if ($program) {
            $this->therapy_program_masalah = $program->masalah_klinis;
            $this->therapy_program_program = $program->program_terapi;
            $this->therapy_program_enteral = $program->nutrisi_enteral;
            $this->therapy_program_parenteral = $program->nutrisi_parenteral;
            $this->therapy_program_lab = $program->pemeriksaan_lab;
        }
    }

    /**
     * FUNGSI BARU 2:
     * Mengosongkan 5 field input di form.
     */
    public function resetFormFields()
    {
        $this->reset([
            'therapy_program_masalah',
            'therapy_program_program',
            'therapy_program_enteral',
            'therapy_program_parenteral',
            'therapy_program_lab',
        ]);
    }
    public function updatedSelectedDate($value)
    {
        if (Carbon::parse($value)->isToday()) {
            $this->record_time = now()->format('Y-m-d\TH:i');
        } else {
            $this->record_time = Carbon::parse($value)->startOfDay()->addHours(6)->format('Y-m-d\TH:i');
        }
        $this->loadRecords();
    }
    public function goToPreviousDay()
    {
        $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');
        $this->updatedSelectedDate($this->selectedDate);
    }

    public function goToNextDay()
    {
        if (!Carbon::parse($this->selectedDate)->isToday()) {
            $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
            $this->updatedSelectedDate($this->selectedDate);
        }
    }

    public function savePippScore($data)
    {
        $totalScore = $data['total_score'];
        $this->skala_nyeri = $totalScore;

        if ($this->currentCycleId) {
            PippAssessment::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'id_user' => auth()->id(),
                'assessment_time' => $this->pipp_assessment_time ?? now(),
                'gestational_age' => $data['gestational_age'],
                'behavioral_state' => $data['behavioral_state'],
                'max_heart_rate' => $data['max_heart_rate'],
                'min_oxygen_saturation' => $data['min_oxygen_saturation'],
                'brow_bulge' => $data['brow_bulge'],
                'eye_squeeze' => $data['eye_squeeze'],
                'nasolabial_furrow' => $data['nasolabial_furrow'],
                'total_score' => $totalScore,
            ]);

            $this->dispatch('refresh-pip');
            $this->dispatch('record-saved', ['message' => 'Penilaian Nyeri (PIPP) berhasil dicatat!']);
        } else {
            $this->dispatch('error-notification', ['message' => 'Simpan data observasi pertama untuk membuat siklus.']);
        }
    }

    #[On('refresh-pip')]
    public function loadPippOnly()
    {
        if ($this->currentCycleId) {
            $this->pippAssessments = PippAssessment::with('pegawai')
                ->where('monitoring_cycle_id', $this->currentCycleId)
                ->orderBy('assessment_time', 'desc')
                ->get();
        }
    }
    /**
     * Fungsi helper untuk menghitung dan menyimpan balance siklus yang sudah selesai.
     */
    private function calculateAndSaveBalance(MonitoringCycle $cycle)
    {
        $records = MonitoringRecord::with('parenteralIntakes')->where('monitoring_cycle_id', $cycle->id)->get();

        $totalIntake = $records->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume'));
        $totalOutput = $records->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
        $iwl = $cycle->daily_iwl ?? 0;

        $cycle->calculated_balance_24h = $totalIntake - $totalOutput - $iwl;
        $cycle->save();
    }
    public function saveDailyIwl()
    {
        if ($this->currentCycleId) {
            $this->validate(['daily_iwl' => 'nullable|numeric']);

            $cycle = MonitoringCycle::find($this->currentCycleId);
            $cycle->daily_iwl = $this->daily_iwl ?: null; // Simpan IWL harian
            $cycle->calculated_balance_24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);
            $cycle->save();
            $this->balance24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);
            $this->dispatch('record-saved', ['message' => 'Estimasi IWL Harian berhasil disimpan!']);
        } else {
            $this->dispatch('error-notification', ['message' => 'Siklus belum aktif.']);
        }
    }

    public $selectedTab = 'ventilator';

    /**
     * Menyimpan SEMUA kejadian yang dipilih di modal dalam satu record.
     */
    public function saveEvent()
    {
        // Cari atau buat siklus monitoring saat ini
        $now = now();
        $cycleStartTime = $now->copy()->startOfDay()->addHours(6);
        if ($now->hour < 6) {
            $cycleStartTime->subDay();
        }
        $cycleEndTime = $cycleStartTime->copy()->addDay()->subSecond();

        $cycle = MonitoringCycle::firstOrCreate(
            ['no_rawat' => $this->no_rawat, 'start_time' => $cycleStartTime],
            ['end_time' => $cycleEndTime]
        );

        // Buat satu record baru dengan semua data event
        MonitoringRecord::create([
            'monitoring_cycle_id' => $cycle->id,
            'id_user' => auth()->id(),
            'record_time' => $now,
            'cyanosis' => $this->event_cyanosis,
            'pucat' => $this->event_pucat,
            'ikterus' => $this->event_ikterus,
            'crt_less_than_2' => $this->event_crt_less_than_2,
            'bradikardia' => $this->event_bradikardia,
            'stimulasi' => $this->event_stimulasi,
        ]);
        $this->dispatch('refresh-observasi');
        $this->dispatch('record-saved', message: 'Kejadian berhasil dicatat!');
    }
    #[On('refresh-records')]
    public function loadRecordsOnly()
    {
        if (!$this->currentCycleId) {
            $this->records = new \Illuminate\Database\Eloquent\Collection();
            $this->fluidRecords = new \Illuminate\Database\Eloquent\Collection();
            $this->totalIntake24h = 0;
            $this->totalOutput24h = 0;
            $this->totalUrine24h = 0;
            $this->balance24h = 0;
            return;
        }

        $allCycleRecords = MonitoringRecord::with('parenteralIntakes', 'enteralIntakes', 'pegawai')
            ->where('monitoring_cycle_id', $this->currentCycleId)
            ->get();

        // 2. Kalkulasi ulang total untuk tampilan
        $this->totalIntake24h = $allCycleRecords->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume') + $r->enteralIntakes->sum('volume'));
        $this->totalOutput24h = $allCycleRecords->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
        $this->totalUrine24h = $allCycleRecords->sum('output_urine');
        $this->balance24h = $this->totalIntake24h - $this->totalOutput24h - ($this->daily_iwl ?? 0);

        // 3. Filter ulang data cairan
        $filteredFluidRecords = $allCycleRecords->filter(function ($record) {
            if ($record->parenteralIntakes->sum('volume') > 0)
                return true;
            if ($record->enteralIntakes->sum('volume') > 0)
                return true;
            $hasIntake = ($record->intake_ogt ?? 0) > 0 || ($record->intake_oral ?? 0) > 0;
            $hasOutput = ($record->output_urine ?? 0) > 0 || ($record->output_bab ?? 0) > 0 || ($record->output_ngt ?? 0) > 0 || ($record->output_drain ?? 0) > 0;
            return $hasIntake || $hasOutput;
        });

        // 4. Update koleksi [Locked] Anda
        $this->fluidRecords = $filteredFluidRecords->sortByDesc('record_time');
        $this->records = $allCycleRecords->sortByDesc('record_time');

        // 5. Update data Grafik (urutan ASC)
        $chartRecords = $allCycleRecords->sortBy('record_time');
        $chartData = collect([
            'temp_incubator' => 'temp_incubator',
            'temp_skin' => 'temp_skin',
            'hr' => 'hr',
            'rr' => 'rr',
            'bp_systolic' => 'blood_pressure_systolic',
            'bp_diastolic' => 'blood_pressure_diastolic',
        ])
            ->mapWithKeys(fn($dbField, $chartKey) => [
                $chartKey => $chartRecords
                    ->filter(fn($r) => is_numeric($r->$dbField) && $r->$dbField !== null)
                    ->map(fn($r) => ['x' => $r->record_time, 'y' => (float) $r->$dbField])
                    ->values()
            ])->toArray();

        $this->dispatch('update-chart', ['chartData' => $chartData]);

        // 6. Update Vitals Terbaru di Header
        $latestRecord = $allCycleRecords->sortByDesc('record_time')->first();
        if ($latestRecord) {
            $this->latestTempIncubator = $latestRecord->temp_incubator;
            $this->latestTempSkin = $latestRecord->temp_skin;
            $this->latestHR = $latestRecord->hr;
            $this->latestRR = $latestRecord->rr;
            $this->latestBPSystolic = $latestRecord->blood_pressure_systolic;
            $this->latestBPDiastolic = $latestRecord->blood_pressure_diastolic;
            // Hitung MAP
            if (!is_null($this->latestBPSystolic) && !is_null($this->latestBPDiastolic)) {
                $systolic = (float) $this->latestBPSystolic;
                $diastolic = (float) $this->latestBPDiastolic;
                $this->latestMAP = round($diastolic + (1 / 3) * ($systolic - $diastolic));
            } else {
                $this->latestMAP = null;
            }
        } else {
            // Reset jika tidak ada record
            $this->latestTempIncubator = $this->latestTempSkin = $this->latestHR = $this->latestRR = null;
            $this->latestBPSystolic = $this->latestBPDiastolic = $this->latestMAP = null;
        }

        $this->reloadRepeaterNames();
    }
    public function saveMedication($data)
    {
        $validated = Validator::make($data, [
            'medication_name' => 'required|string|max:255',
            'dose' => 'required|string|max:100',
            'route' => 'required|string|max:100',
            'given_at' => 'required|date_format:Y-m-d\TH:i',
        ])->validate();

        if ($this->currentCycleId) {
            try {
                Medication::create([
                    'monitoring_cycle_id' => $this->currentCycleId,
                    'id_user' => auth()->id(),
                    // Ambil nilai dari $validated (atau $data), bukan $this->...
                    'medication_name' => $validated['medication_name'],
                    'dose' => $validated['dose'],
                    'route' => $validated['route'],
                    'given_at' => $validated['given_at'],
                ]);

                $this->dispatch('refresh-medications'); // Refresh tabel riwayat obat
                $this->dispatch('record-saved', ['message' => 'Pemberian obat berhasil dicatat!']);
                return true;
            } catch (\Exception $e) {
                $this->dispatch('error-notification', ['message' => 'Gagal menyimpan obat: ' . $e->getMessage()]);
                return false;
            }
        } else {
            $this->dispatch('error-notification', ['message' => 'Simpan data observasi pertama untuk membuat siklus.']);
            return false;
        }
    }
    #[On('refresh-medications')]
    public function loadMedicationsOnly()
    {
        if ($this->currentCycleId) {
            // Muat ulang HANYA data obat
            $this->medications = Medication::with('pegawai')
                ->where('monitoring_cycle_id', $this->currentCycleId)
                ->orderBy('given_at', 'desc')
                ->get();
        }
    }

    public function saveBloodGasResult($data)
    {
        $validated = Validator::make($data, [
            'taken_at' => 'required|date_format:Y-m-d\TH:i',
            'gula_darah' => 'nullable|numeric',
            'ph' => 'nullable|numeric|between:6.5,8.0',
            'pco2' => 'nullable|numeric|min:0',
            'po2' => 'nullable|numeric|min:0',
            'hco3' => 'nullable|numeric|min:0',
            'be' => 'nullable|numeric',
            'sao2' => 'nullable|numeric|between:0,100',
        ])->validate();
        $results = collect($validated)->except('taken_at');
        if ($results->filter(fn($v) => $v !== null && $v !== '')->isEmpty()) {
            $this->dispatch('error-notification', ['message' => 'Minimal satu hasil gas darah harus diisi.']);
            return false; // Sinyal gagal
        }

        if (!$this->currentCycleId) {
            $this->dispatch('error-notification', ['message' => 'Simpan data observasi pertama untuk membuat siklus.']);
            return false; // Sinyal gagal
        }
        try {
            BloodGasResult::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'id_user' => auth()->id(),
            ] + $validated);
            $this->dispatch('refresh-blood-gas');
            $this->dispatch('record-saved', ['message' => 'Hasil Gas Darah berhasil dicatat!']);
            $this->dispatch('close-blood-gas-modal');
            return true;
        } catch (\Exception $e) {
            $this->dispatch('error-notification', ['message' => 'Gagal menyimpan gas darah: ' . $e->getMessage()]);
            return false; // Sinyal gagal
        }
    }


    #[On('refresh-blood-gas')]
    public function loadBloodGasOnly()
    {
        if ($this->currentCycleId) {
            $this->bloodGasResults = BloodGasResult::with('pegawai')
                ->where('monitoring_cycle_id', $this->currentCycleId)
                ->orderBy('taken_at', 'desc')
                ->get();
        }
    }
}
