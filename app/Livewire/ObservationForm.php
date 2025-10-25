<?php // app/Livewire/ObservationForm.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View; // Import View

class ObservationForm extends Component
{
    // Properti yang di-pass dari induk
    public string $no_rawat;
    public string $selectedDate; // Format Y-m-d

    // Properti state form
    public string $activeTab = 'observasi';
    public $record_time;

    // --- Pindahkan SEMUA properti input dari PatientMonitor ke sini ---
    public $temp_incubator, $temp_skin, $hr, $rr;
    public $blood_pressure_systolic, $blood_pressure_diastolic;
    public $sat_o2, $irama_ekg, $skala_nyeri, $humidifier_inkubator;
    public bool $cyanosis = false;
    public bool $pucat = false;
    public bool $ikterus = false;
    public bool $crt_less_than_2 = false;
    public bool $bradikardia = false;
    public bool $stimulasi = false;
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
    // --- Akhir properti input ---

    // Helper untuk mendapatkan ID siklus saat ini berdasarkan tanggal
    protected function getCurrentCycleId(): ?int
    {
        $targetDate = Carbon::parse($this->selectedDate)->startOfDay();
        $cycleStartTime = $targetDate->copy()->addHours(6);
        if ($targetDate->isToday() && now()->hour < 6) {
            $cycleStartTime->subDay();
        }

        $cycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
                       ->where('start_time', $cycleStartTime)
                       ->first();
        return $cycle?->id;
    }

    // Fungsi ini dipanggil saat properti $selectedDate dari parent berubah
    public function updatedSelectedDate($value)
    {
         $this->selectedDate = $value; // Update properti lokal
         $this->initializeRecordTime();
         $this->reloadRepeaterNames(); // Muat nama repeater sesuai tanggal baru
    }

     // Inisialisasi waktu record awal dan saat tanggal berubah
    private function initializeRecordTime()
    {
         if (Carbon::parse($this->selectedDate)->isToday()) {
             $this->record_time = now()->format('Y-m-d\TH:i');
         } else {
             $this->record_time = Carbon::parse($this->selectedDate)->startOfDay()->addHours(6)->format('Y-m-d\TH:i');
         }
    }

    public function mount(string $no_rawat, string $selectedDate)
    {
        $this->no_rawat = $no_rawat;
        $this->selectedDate = $selectedDate;
        $this->initializeRecordTime();
        // Muat nama repeater awal saat komponen dimuat
        $this->reloadRepeaterNames();
    }

    // --- Pindahkan fungsi saveRecord, resetForm, reloadRepeaterNames ---
    // --- dari PatientMonitor ke sini ---

     public function saveRecord(): void
    {
        // ===============================================
        // 1. NORMALISASI DATA (Perbaikan Case-Sensitive "Puasa")
        // ===============================================
        // Ubah 'Puasa' (atau 'PUASA') menjadi 'puasa' SEBELUM validasi
        $enteralData = $this->enteral_intakes;
        foreach ($enteralData as $index => $enteral) {
            if (isset($enteral['name']) && strtolower($enteral['name']) === 'puasa') {
                $enteralData[$index]['name'] = 'puasa';
            }
        }
        $this->enteral_intakes = $enteralData; // Simpan kembali data yang sudah bersih

        // ===============================================
        // 2. VALIDASI (Perbaikan untuk mengizinkan baris kosong)
        // ===============================================
        $this->validate([
            'record_time' => 'required|date',
            // 'name' hanya wajib jika 'volume' diisi
            'parenteral_intakes.*.name' => 'required_with:parenteral_intakes.*.volume|nullable|string',
            // Izinkan volume 0
            'parenteral_intakes.*.volume' => 'nullable|numeric|min:0',
            'enteral_intakes.*.name' => 'required_with:enteral_intakes.*.volume|nullable|string',
            'enteral_intakes.*.volume' => 'nullable|numeric|min:0|exclude_if:enteral_intakes.*.name,puasa',
        ]);

        // Daftar field Anda (sudah benar)
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

        // =========================
        // Tentukan cycle (Sudah Benar)
        // =========================
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

        // =========================
        // Simpan record (Sudah Benar)
        // =========================
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

        // =========================
        // Simpan parenteral (Perbaikan untuk volume '0')
        // =========================
        foreach ($this->parenteral_intakes as $intake) {
            // Perbaikan: Ganti 'empty' dengan cek 'null' atau string kosong
            // Ini PENTING agar volume '0' bisa disimpan
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
        $this->dispatch('refresh-records');
        $this->dispatch('record-saved');
    }

    public function resetForm(): void
    {
        // Salin seluruh isi fungsi resetForm dari PatientMonitor ke sini
        $this->reset([
            'temp_incubator', 'temp_skin', 'hr', 'rr', 'blood_pressure_systolic',
            // ... (salin semua properti input dari resetForm lama) ...
            'output_drain'
        ]);
         $this->initializeRecordTime(); // Setel ulang waktu ke default tanggal terpilih
    }

    private function reloadRepeaterNames()
    {
        // Salin seluruh isi fungsi reloadRepeaterNames dari PatientMonitor ke sini
        // Ganti $this->currentCycleId dengan $this->getCurrentCycleId()
        // Pastikan query menggunakan $this->no_rawat dan $this->selectedDate
        // ... (Kode reloadRepeaterNames Anda yang sudah dioptimalkan) ...

         // Contoh penyesuaian:
         $currentCycleId = $this->getCurrentCycleId(); // Dapatkan ID siklus
          if ($currentCycleId) {
              $currentRecordIds = MonitoringRecord::where('monitoring_cycle_id', $currentCycleId)->pluck('id');
              // ... sisa logika ...
          }
          // ... logika fallback ke siklus sebelumnya ...
    }
    // --- Akhir fungsi yang dipindah ---

    public function render(): View
    {
        return view('livewire.observation-form');
    }
}
