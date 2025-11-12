<?php

namespace App\Livewire;

use App\Models\BloodGasResult;
use App\Models\Medication;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use App\Models\PippAssessment;
use Carbon\Carbon;
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
    public Collection $recentMedicationNames;
    // Properties for PIPP Modal
    public $pipp_assessment_time;
    public $gestational_age = 0, $behavioral_state = 0, $max_heart_rate = 0;
    public $min_oxygen_saturation = 0, $brow_bulge = 0, $eye_squeeze = 0, $nasolabial_furrow = 0;
    public $pipp_total_score = 0;
    public $assessment_time;
    public $facial_expression = 0, $cry = 0, $breathing_pattern = 0;
    public $arms_movement = 0, $legs_movement = 0, $state_of_arousal = 0;
    public $total_score = 0;

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
    public bool $isReadOnly = false;
    public $respiratory_mode;
    public $spontan_fio2, $spontan_flow;
    public $cpap_fio2, $cpap_flow, $cpap_peep;
    public $hfo_fio2, $hfo_frekuensi, $hfo_map, $hfo_amplitudo, $hfo_it;
    public $monitor_mode, $monitor_fio2, $monitor_peep, $monitor_pip;
    public $monitor_tv_vte, $monitor_rr_spontan, $monitor_p_max, $monitor_ie;
    public $intake_ogt, $intake_oral;
    public $output_urine, $output_bab, $output_residu;
    public $output_ngt, $output_drain;
    public array $parenteral_intakes = [];
    public array $enteral_intakes = [];
    // Properti untuk Modal Blood Gas
    public $taken_at;
    public $gula_darah, $ph, $pco2, $po2, $hco3, $be, $sao2;
    public $latestTempIncubator = null;
    public $latestTempSkin = null;
    public $latestHR = null;
    public $latestRR = null;
    public $latestBPSystolic = null;
    public $latestBPDiastolic = null;
    public $latestMAP = null;


    public $selectedTab = 'ventilator';

    /**
     *
     * Otomatis berjalan SETIAP KALI user mengganti tab.
     * Fungsinya adalah untuk "membangunkan" child component
     * yang baru saja dibuat 'lazy'.
     */
    public function updatedActiveOutputTab($value)
    {
        $this->dispatch('cycle-updated', cycleId: $this->currentCycleId);
    }

    public function loadData()
    {
        $this->readyToLoad = true;
        $this->loadRecords();
    }

    public function mount(string $no_rawat, $date = null): void
    {
        $date = $date ? Carbon::parse($date) : now();
        $maxDate = now()->endOfDay();
        if ($date->gt($maxDate)) {
            abort(403, 'Tanggal tidak valid');
        }
        $this->no_rawat = str_replace('_', '/', $no_rawat);
        $baseTime = $date ? \Carbon\Carbon::parse($date) : now();
        $this->selectedDate = $baseTime->format('Y-m-d');

        // Tentukan tanggal sheet "hari ini" (dihitung mulai jam 6 pagi)
        $todaySheetDate = now()->startOfDay();
        if (now()->hour < 6) {
            $todaySheetDate->subDay();
        }
        $todaySheetDate = $todaySheetDate->format('Y-m-d');

        // Cari siklus
        $sheetDate = $baseTime->copy()->startOfDay();
        if ($baseTime->hour < 6) {
            $sheetDate->subDay();
        }

        $this->currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $sheetDate->format('Y-m-d'))
            ->first();

        $this->currentCycleId = $this->currentCycle?->id;

        // Ambil nomor rawat aktif yang sedang digunakan hari ini
        $activeCycle = MonitoringCycle::where('sheet_date', $todaySheetDate)
            ->latest()
            ->first();

        // 🔒 Readonly hanya kalau:
        // - bukan sheet hari ini, DAN
        // - nomor rawat saat ini sama dengan yang aktif
        $this->isReadOnly = (
            $this->selectedDate != $todaySheetDate &&
            $activeCycle &&
            $activeCycle->no_rawat === $this->no_rawat
        );

        // Tentukan waktu record default
        if ($this->isReadOnly) {
            $defaultRecordTime = $baseTime->copy()->startOfDay()->addHours(6);
        } else {
            $defaultRecordTime = now();
        }
        $this->record_time = $defaultRecordTime->format('Y-m-d\TH:i');
        $this->taken_at = $defaultRecordTime->format('Y-m-d\TH:i');
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

            // Numeric NICU fields with min/max
            'temp_incubator' => 'nullable|numeric|between:33,38',       // °C
            'temp_skin' => 'nullable|numeric|between:30,37',            // °C
            'hr' => 'nullable|numeric|between:80,200',                  // bpm
            'rr' => 'nullable|numeric|between:20,80',                   // breaths/min
            'blood_pressure_systolic' => 'nullable|numeric|between:50,120',
            'blood_pressure_diastolic' => 'nullable|numeric|between:30,80',
            'sat_o2' => 'nullable|numeric|between:50,100',
            'hfo_fio2' => 'nullable|numeric|between:21,100',
            'cpap_fio2' => 'nullable|numeric|between:21,100',
            'cpap_peep' => 'nullable|numeric|between:2,15',
            'hfo_map' => 'nullable|numeric|between:5,20',
            'hfo_amplitudo' => 'nullable|numeric|between:5,30',
            'hfo_it' => 'nullable|numeric|between:0.3,1.5',
        ], [
            'temp_incubator.between' => 'Suhu inkubator harus antara 33°C sampai 38°C.',
            'temp_skin.between' => 'Suhu kulit harus antara 30°C sampai 37°C.',
            'hr.between' => 'Heart rate harus antara 80 sampai 200 bpm.',
            'rr.between' => 'Respiratory rate harus antara 20 sampai 80 per menit.',
            'blood_pressure_systolic.between' => 'Tensi sistolik harus antara 50 sampai 120 mmHg.',
            'blood_pressure_diastolic.between' => 'Tensi diastolik harus antara 30 sampai 80 mmHg.',
            'sat_o2.between' => 'Saturasi O₂ harus antara 50% sampai 100%.',
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

        $hasMainData = collect($fieldsToCheck)->some(fn($f) => !empty($this->$f) && $this->$f !== null);

        $hasParenteral = collect($this->parenteral_intakes)->some(fn($i) => isset($i['volume']) && $i['volume'] !== '' && $i['volume'] !== null);

        $hasEnteral = collect($this->enteral_intakes)->some(fn($i) => (isset($i['volume']) && $i['volume'] !== '' && $i['volume'] !== null) || strtolower($i['name']) === 'puasa');

        if (!$hasMainData && !$hasParenteral && !$hasEnteral) {
            $this->addError('record', 'Minimal satu data observasi atau intake harus diisi.');
            return;
        }
        $recordTime = now();
        $sheetDate = $recordTime->copy()->startOfDay();
        $cycleStartTime = $recordTime->copy()->startOfDay()->addHours(6);
        if ($recordTime->hour < 6) {
            $sheetDate->subDay();
            $cycleStartTime->subDay();
        }
        $cycleEndTime = $cycleStartTime->copy()->addDay()->subSecond();
        $cycle = MonitoringCycle::firstOrCreate(
            ['no_rawat' => $this->no_rawat, 'sheet_date' => $sheetDate->format('Y-m-d')],
            [
                'sheet_date' => $sheetDate->format('Y-m-d'),
                'start_time' => $cycleStartTime,
                'end_time' => $cycleEndTime,
            ]
        );
        $record = MonitoringRecord::where('monitoring_cycle_id', $cycle->id)
            ->whereBetween('record_time', [
                $recordTime->copy()->startOfMinute(),
                $recordTime->copy()->endOfMinute()
            ])
            ->first();
        $dataToSave = collect($fieldsToCheck)->mapWithKeys(fn($f) => [$f => $this->$f])->toArray();
        if ($record) {
            $record->update($dataToSave);
        } else {
            $record = MonitoringRecord::create([
                'monitoring_cycle_id' => $cycle->id,
                'id_user' => auth()->id(),
                'record_time' => $recordTime,
                ...$dataToSave
            ]);
        }
        $now = now();
        $parenteralData = collect($this->parenteral_intakes)
            ->filter(callback: fn($i) => isset($i['volume']) && $i['volume'] !== '' && $i['volume'] !== null)
            ->map(fn($intake) => [
                'monitoring_record_id' => $record->id,
                'name' => $intake['name'],
                'volume' => $intake['volume'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

        // 2. Siapkan data Enteral
        $enteralData = collect($this->enteral_intakes)
            ->filter(function ($enteral) {
                $isPuasa = isset($enteral['name']) && $enteral['name'] === 'puasa';
                $volumeIsEmpty = !isset($enteral['volume']) || $enteral['volume'] === '' || $enteral['volume'] === null;
                return !$volumeIsEmpty || $isPuasa; // Simpan jika ada volume ATAU jika puasa
            })
            ->map(fn($enteral) => [
                'monitoring_record_id' => $record->id,
                'name' => $enteral['name'],
                'volume' => (isset($enteral['name']) && $enteral['name'] === 'puasa') ? null : $enteral['volume'],
                'created_at' => $now,
                'updated_at' => $now,
            ])->all();

        // 3. Simpan sekaligus! (Hanya 2 query, bukan N+M query)
        if (!empty($parenteralData)) {
            \App\Models\ParenteralIntake::insert($parenteralData);
        }
        if (!empty($enteralData)) {
            \App\Models\EnteralIntake::insert($enteralData);
        }
        $this->resetForm();
        $this->dispatch('record-saved', message: 'Data Observasi berhasil dicatat!');

    }

    public function resetForm(): void
    {
        // 1. Reset semua field biasa
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

        // 2. KOSONGKAN HANYA VOLUME (JANGAN RESET ARRAY-NYA)
        foreach ($this->parenteral_intakes as $index => $intake) {
            $this->parenteral_intakes[$index]['volume'] = null;
        }
        foreach ($this->enteral_intakes as $index => $intake) {
            $this->enteral_intakes[$index]['volume'] = null;
        }

        // 3. Reset waktu
        $this->record_time = now()->format('Y-m-d\TH:i');
    }

    public function render(): View
    {
        return view('livewire.patient-monitor')->layout('layouts.app');
    }


    #[On('refresh-observasi')]
    #[On('record-saved')]
    public function loadRecords(): void
    {
        if (!$this->readyToLoad) {
            $this->recentMedicationNames = new \Illuminate\Support\Collection();
            return;
        }

        // 1. Tentukan Tanggal (Ini sudah benar)
        $currentSheetDate = Carbon::parse($this->selectedDate);

        // 2. Cari Siklus Saat Ini (Cara Anda sudah benar)
        $currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $currentSheetDate->format('Y-m-d'))
            ->first();

        // 5. Muat Data Siklus Saat Ini (Ini semua sudah benar)
        if ($currentCycle) {
            $this->currentCycleId = $currentCycle->id;
            // Ambil SEMUA record HANYA untuk kalkulasi
            $allCycleRecords = MonitoringRecord::with('parenteralIntakes', 'enteralIntakes')
                ->where('monitoring_cycle_id', $currentCycle->id)
                ->get();

            // Ambil nama obat
            $this->recentMedicationNames = Medication::where('monitoring_cycle_id', $currentCycle->id)
                ->distinct()
                ->orderBy('medication_name', 'asc')
                ->pluck('medication_name');
            $recordIds = $allCycleRecords->pluck('id');
            $uniqueInfusionNames = \App\Models\ParenteralIntake::whereIn('monitoring_record_id', $recordIds)
                ->distinct()
                ->pluck('name');
            $uniqueEnteralNames = \App\Models\EnteralIntake::whereIn('monitoring_record_id', $recordIds)
                ->distinct()
                ->pluck('name');
            $this->parenteral_intakes = $uniqueInfusionNames->map(fn($name) => [
                'name' => $name,
                'volume' => ''
            ])->toArray();
            $this->enteral_intakes = $uniqueEnteralNames->map(fn($name) => [
                'name' => $name,
                'volume' => ''
            ])->toArray();

        } else {
            $this->currentCycleId = null;
            $this->recentMedicationNames = new Collection();
            $this->parenteral_intakes = [];
            $this->enteral_intakes = [];
        }
        $this->dispatch('repeaters-ready');
        $this->dispatch('cycle-updated', cycleId: $this->currentCycleId);
    }
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
        // 1. Tentukan tanggal baru
        $newDate = Carbon::parse($value)->format('Y-m-d');
        // 2. Redirect ke URL baru (sama seperti tombol panah)
        return $this->redirect(route('patient.monitor', [
            'no_rawat' => str_replace('/', '_', $this->no_rawat),
            'date' => $newDate
        ]), navigate: true);
    }
    public function goToPreviousDay()
    {
        // 1. Hitung tanggal baru
        $newDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');

        // 2. Redirect ke URL baru.
        // 'navigate: true' membuatnya cepat seperti SPA (Single Page App)
        return $this->redirect(route('patient.monitor', [
            'no_rawat' => str_replace('/', '_', $this->no_rawat),
            'date' => $newDate
        ]), navigate: true);
    }
    public function goToNextDay()
    {
        if (!Carbon::parse($this->selectedDate)->isToday()) {
            // 1. Hitung tanggal baru
            $newDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
            // 2. Redirect ke URL baru.
            return $this->redirect(route('patient.monitor', [
                'no_rawat' => str_replace('/', '_', $this->no_rawat),
                'date' => $newDate
            ]), navigate: true);
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
            $this->dispatch('record-saved', 'Penilaian Nyeri (PIPP) berhasil dicatat!');
        } else {
            $this->dispatch('error-notification', 'Simpan data observasi pertama untuk membuat siklus.');
        }
    }
    public function saveEvent()
    {
        $now = now();
        $cycleStartTime = $now->copy()->startOfDay()->addHours(6);
        if ($now->hour < 6) {
            $cycleStartTime->subDay();
        }
        $cycleEndTime = $cycleStartTime->copy()->addDay()->subSecond();
        $sheetDate = $now->copy()->startOfDay();

        // Ambil atau buat cycle
        $cycle = MonitoringCycle::firstOrCreate(
            ['no_rawat' => $this->no_rawat, 'sheet_date' => $sheetDate, 'start_time' => $cycleStartTime],
            ['end_time' => $cycleEndTime]
        );

        // Format record_time sampai menit saja
        $currentMinute = $now->format('Y-m-d H:i');

        // Cek apakah sudah ada record di menit yang sama
        $existingRecord = MonitoringRecord::where('monitoring_cycle_id', $cycle->id)
            ->whereRaw("DATE_FORMAT(record_time, '%Y-%m-%d %H:%i') = ?", [$currentMinute])
            ->first();

        $data = [
            'monitoring_cycle_id' => $cycle->id,
            'id_user' => auth()->id(),
            'record_time' => $now,
            'cyanosis' => $this->event_cyanosis,
            'pucat' => $this->event_pucat,
            'ikterus' => $this->event_ikterus,
            'crt_less_than_2' => $this->event_crt_less_than_2,
            'bradikardia' => $this->event_bradikardia,
            'stimulasi' => $this->event_stimulasi,
        ];

        if ($existingRecord) {
            // Update record yang sudah ada
            $existingRecord->update($data);
        } else {
            // Buat record baru
            MonitoringRecord::create($data);
        }

        $this->dispatch('record-saved', message: 'Kejadian berhasil dicatat!');
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
            $this->dispatch('error-notification', 'Minimal satu hasil gas darah harus diisi.');
            return false; // Sinyal gagal
        }
        if (!$this->currentCycleId) {
            $this->dispatch('error-notification', 'Simpan data observasi pertama untuk membuat siklus.');
            return false; // Sinyal gagal
        }
        try {
            BloodGasResult::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'id_user' => auth()->id(),
            ] + $validated);
            $this->dispatch('refresh-blood-gas');
            $this->dispatch('record-saved', 'Hasil Gas Darah berhasil dicatat!');
            return true;
        } catch (\Exception $e) {
            $this->dispatch('error-notification', message: "Gagal menyimpan gas darah: " . $e->getMessage());
            return false;
        }
    }

}
