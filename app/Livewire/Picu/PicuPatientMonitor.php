<?php

namespace App\Livewire\Picu;

use App\Models\PicuMonitoringRecord;
use App\Models\PicuMonitoringCycle;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Illuminate\View\View;

class PicuPatientMonitor extends Component
{
    #[Locked]
    public string $no_rawat;

    #[Locked]
    public $currentCycleId;

    // === Kolom 1: Observasi Pasien ===
    public $temp_skin, $hr, $rr;
    public $blood_pressure_systolic, $blood_pressure_diastolic;
    public $sat_o2, $irama_ekg, $skala_nyeri, $humidifier_inkubator;

    // === Kolom 2: Setting Ventilator ===
    public $spontan_fio2, $spontan_flow;
    public $cpap_fio2, $cpap_flow, $cpap_peep;
    public $hfo_fio2, $hfo_frekuensi, $hfo_map, $hfo_amplitudo, $hfo_it;
    public $monitor_mode, $monitor_fio2, $monitor_peep, $monitor_pip;
    public $monitor_tv_vte, $monitor_rr_spontan, $monitor_p_max, $monitor_ie;

    // === Kolom 3: Catatan Lainnya ===
    public bool $cyanosis = false;
    public bool $pucat = false;
    public bool $ikterus = false;
    public bool $crt_less_than_2 = false;
    public bool $bradikardia = false;
    public bool $stimulasi = false;

    // Properti $rules dan $messages telah dihapus dari sini

    /**
     * Mount: Hanya untuk mengatur no_rawat
     */
    public function mount(string $no_rawat): void
    {
        $this->no_rawat = str_replace('_', '/', $no_rawat);
    }

    /**
     * Fungsi utama untuk menyimpan data
     */
    /**
     * Fungsi utama untuk menyimpan data
     */
    public function saveRecord(): void
    {
        // 1. Validasi data langsung di dalam method
        $this->validate([
            'temp_skin' => 'nullable|numeric|between:30,40',
            'hr' => 'nullable|numeric|between:30,300',
            'rr' => 'nullable|numeric|between:5,120',
            'sat_o2' => 'nullable|numeric|between:0,100',
            'blood_pressure_systolic' => 'nullable|numeric|between:30,250',
            'blood_pressure_diastolic' => 'nullable|numeric|lte:blood_pressure_systolic',
        ], [
            'temp_skin.between' => 'Suhu kulit harus antara :min° dan :max° C.',
            'hr.between' => 'Denyut jantung harus antara :min dan :max x/menit.',
            'rr.between' => 'Laju napas harus antara :min dan :max x/menit.',
            'sat_o2.between' => 'Saturasi O₂ harus antara :min% dan :max%.',
            'blood_pressure_systolic.between' => 'Tekanan sistolik harus antara :min dan :max mmHg.',
            'blood_pressure_diastolic.lte' => 'Tekanan diastolik tidak boleh melebihi sistolik.',
        ]);

        // 2. Daftar semua field yang akan disimpan dari form ini
        $fieldsToSave = [
            'temp_skin',
            'hr',
            'rr',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'sat_o2',
            'irama_ekg',
            'skala_nyeri',
            'humidifier_inkubator',

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

            'cyanosis',
            'pucat',
            'ikterus',
            'crt_less_than_2',
            'bradikardia',
            'stimulasi',
        ];

        // 3. Cek apakah ada data yang diisi (form tidak kosong)
        // --- PERBAIKAN LOGIKA ADA DI SINI ---
        $hasMainData = collect($fieldsToSave)->some(function ($field) {
            $value = $this->$field;

            // Jika nilainya boolean `true`, itu dihitung sebagai data
            if (is_bool($value) && $value === true) {
                return true;
            }
            // Jika nilainya bukan boolean, cek apakah tidak null dan tidak string kosong
            // Ini akan lolos untuk angka 0
            if (!is_bool($value) && $value !== null && $value !== '') {
                return true;
            }

            return false;
        });
        if (!$hasMainData) {
            // Beri error jika form benar-benar kosong
            session()->flash('error', 'Gagal! Minimal satu field observasi atau setting harus diisi.');
            return;
        }

        // 4. Tentukan Siklus (Cycle) Monitoring
        $now = now();
        $cycleStartTime = $now->copy()->startOfDay()->addHours(6); // Siklus jam 6 pagi
        if ($now->hour < 6) {
            $cycleStartTime->subDay();
        }
        $cycleEndTime = $cycleStartTime->copy()->addDay()->subSecond();
        $sheetDate = $cycleStartTime->toDateString();

        $cycle = PicuMonitoringCycle::firstOrCreate(
            ['no_rawat' => $this->no_rawat, 'sheet_date' => $sheetDate, 'start_time' => $cycleStartTime],
            ['end_time' => $cycleEndTime]
        );

        // 5. Siapkan data untuk disimpan
        $dataToSave = collect($fieldsToSave)->mapWithKeys(fn($field) => [
            $field => $this->$field !== null ? $this->$field : null
        ])->toArray();

        // 6. Simpan data ke database
        PicuMonitoringRecord::updateOrCreate(
            [
                'monitoring_cycle_id' => $cycle->id,
                'record_time' => $now, // Gunakan $now sebagai ID unik
            ],
            array_merge(
                [
                    'monitoring_cycle_id' => $cycle->id,
                    'id_user' => auth()->id(), // Asumsi user sudah login
                    'record_time' => $now,
                ],
                $dataToSave // Masukkan semua data dari $fieldsToSave
            )
        );

        // 7. Reset form dan kirim notifikasi
        $this->resetForm();
        session()->flash('success', 'Catatan monitoring berhasil disimpan.');

    }
    /**
     * Mengosongkan semua field di form
     */
    public function resetForm(): void
    {
        $this->reset([
            'temp_skin',
            'hr',
            'rr',
            'blood_pressure_systolic',
            'blood_pressure_diastolic',
            'sat_o2',
            'irama_ekg',
            'skala_nyeri',
            'humidifier_inkubator',

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

            'cyanosis',
            'pucat',
            'ikterus',
            'crt_less_than_2',
            'bradikardia',
            'stimulasi',
        ]);

        // Set default value untuk checkbox setelah reset
        $this->cyanosis = false;
        $this->pucat = false;
        $this->ikterus = false;
        $this->crt_less_than_2 = false;
        $this->bradikardia = false;
        $this->stimulasi = false;
    }


    public function render(): View
    {
        return view('livewire.picu.input-patient-monitor-picu', [
            'currentCycleId' => $this->currentCycleId, // ✅ Inilah yang menyelesaikan error
        ])->layout('layouts.app');
    }
}
