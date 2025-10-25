<?php

namespace App\Livewire;

use App\Models\BloodGasResult;
use App\Models\Medication;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use App\Models\PatientDevice;
use App\Models\PippAssessment;
use App\Models\TherapyProgram;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
    #[Locked]
    public Collection $therapy_program_history;
    // Properties for PIPP Modal
    public bool $showPippModal = false;
    public $pipp_assessment_time;
    public $gestational_age = 0, $behavioral_state = 0, $max_heart_rate = 0;
    public $min_oxygen_saturation = 0, $brow_bulge = 0, $eye_squeeze = 0, $nasolabial_furrow = 0;
    public $pipp_total_score = 0;

    public $assessment_time;
    public $facial_expression = 0, $cry = 0, $breathing_pattern = 0;
    public $arms_movement = 0, $legs_movement = 0, $state_of_arousal = 0;
    public $total_score = 0; // Calculated score

    // Properti untuk Modal Alat
    public bool $showDeviceModal = false;
    public $device_name, $size, $location, $installation_date;
    public $editingDeviceId = null; // Untuk mode edit/hapus
    public $therapy_program;
    public $clinical_problems;
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
    public bool $showMedicationModal = false;
    public $medication_name, $dose, $route, $given_at;


    // Properti untuk Modal Blood Gas
    public bool $showBloodGasModal = false;
    public $taken_at;
    public $gula_darah, $ph, $pco2, $po2, $hco3, $be, $sao2;


    public $daily_iwl; // Input untuk IWL harian
    public $totalIntake24h = 0;
    public $totalOutput24h = 0;
    public $totalUrine24h = 0;
    public $balance24h = 0;
    public $previousBalance24h = null;


    public $current_therapy_program_text = '';
    /**
     * Method ini akan dipanggil oleh wire:poll untuk menyegarkan jam.
     */
    public function updateRecordTime()
    {
        // Hanya perbarui waktu jika user sedang melihat tab 'observasi' utama
        // dan belum memilih tanggal di masa lalu.
        // if ($this->activeTab === 'observasi' && Carbon::parse($this->selectedDate)->isToday()) {
        // $this->record_time = now()->format('Y-m-d\TH:i');
        // }
    }
    public function loadData()
    {
        $this->readyToLoad = true;

        // Panggil fungsi pemuat data Anda yang sudah ada
        $this->loadRecords();
        $this->loadPatientDevices();
    }

    /**
     * Mengambil data chart dari QuickChart menggunakan cURL
     *
     * @param array $chartData Konfigurasi chart
     * @return string|null Base64 data URI atau null jika gagal
     */
    private function generateChartWithCurl($chartData)
    {
        try {
            $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartData));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $chartUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Mengembalikan hasil sebagai string
            curl_setopt($ch, CURLOPT_TIMEOUT, 15); // Timeout 15 detik

            // --- INI BAGIAN PENTING UNTUK MASALAH LOKAL ---
            // Matikan verifikasi SSL.
            // !! PERINGATAN: Jangan gunakan di produksi jika tidak perlu !!
            // !! Ini hanya untuk mengatasi masalah SSL di server lokal !!
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            // --------------------------------------------------

            $imageData = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($imageData) {
                // Berhasil, kembalikan data URI
                return 'data:image/png;base64,' . base64_encode($imageData);
            } else {
                // Gagal, catat error cURL
                Log::error("Gagal generate chart (cURL error): " . $error);
                return null;
            }

        } catch (\Exception $e) {
            // Gagal karena exception lain
            Log::error("Gagal generate chart (Exception): " . $e->getMessage());
            return null;
        }
    }
    public $showRemoveDeviceModal = false;
    public $deviceToRemoveId = null;
    public $deviceToRemoveDetails = null; // Untuk menyimpan data alat yg akan dilepas

    /**
     * [BARU] Membuka modal konfirmasi lepas alat
     */
    public function openRemoveModal($deviceId)
    {
        $device = PatientDevice::find($deviceId);
        if ($device && $device->no_rawat === $this->no_rawat) {
            $this->deviceToRemoveId = $device->id;
            $this->deviceToRemoveDetails = $device; // Simpan data alat untuk ditampilkan
            $this->showRemoveDeviceModal = true;
        }
    }

    /**
     * [BARU] Menutup modal konfirmasi lepas alat
     */
    public function closeRemoveModal()
    {
        $this->showRemoveDeviceModal = false;
        $this->deviceToRemoveId = null;
        $this->deviceToRemoveDetails = null;
    }

    /**
     * [DIUBAH] Fungsi 'removeDevice' Anda diganti namanya
     * menjadi 'confirmRemoveDevice' dan dipanggil dari modal.
     */
    public function confirmRemoveDevice()
    {
        $deviceId = $this->deviceToRemoveId; // Ambil ID dari properti
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

        // Tutup modal setelah selesai
        $this->closeRemoveModal();
    }

    public function generateReportPdf($no_rawat, $cycle_id)
    {
        $setting = DB::table('setting')->first();
        $no_rawat = str_replace('_', '/', $no_rawat);

        // 1. Validasi
        $cycle = MonitoringCycle::find($cycle_id);
        if (!$cycle || $cycle->no_rawat !== $no_rawat) {
            abort(404, 'Siklus monitoring tidak ditemukan.');
        }
        $previousCycle = MonitoringCycle::where('no_rawat', $cycle->no_rawat)
            ->where('end_time', '<', $cycle->start_time)
            ->orderBy('end_time', 'desc')
            ->first();

        // 2. Ambil Data Pasien
        $patientData = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->leftJoin('pasien_bayi as pb', 'p.no_rkm_medis', '=', 'pb.no_rkm_medis')
            ->join('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->join('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                'p.tgl_lahir',
                'p.jk',
                'pb.berat_badan as berat_lahir',
                'pb.proses_lahir as cara_persalinan',
                'ki.tgl_masuk',
                'ki.diagnosa_awal',
                'd.nm_dokter',
                'pj.png_jawab as jaminan',
                'rp.stts as status_rujukan',
                'b.nm_bangsal as asal_bangsal'
            )
            ->where('rp.no_rawat', $no_rawat)
            ->where('ki.tgl_masuk', '<=', $cycle->start_time)
            ->orderBy('ki.tgl_masuk', 'desc')
            ->first();


        // 3. Ambil Data Event
        $medications = Medication::where('monitoring_cycle_id', $cycle->id)->orderBy('given_at', 'asc')->get();
        $bloodGasResults = BloodGasResult::where('monitoring_cycle_id', $cycle->id)->orderBy('taken_at', 'asc')->get();
        $pippAssessments = PippAssessment::where('monitoring_cycle_id', $cycle->id)->orderBy('assessment_time', 'asc')->get();

        $cycleStart = $cycle->start_time;
        $cycleEnd = $cycle->end_time;
        $patientDevices = \App\Models\PatientDevice::where('no_rawat', $no_rawat)
            // 1. Dipasang SEBELUM siklus ini berakhir
            ->where('installation_date', '<=', $cycleEnd)
            // 2. DAN (Belum dicabut ATAU dicabut SETELAH siklus ini mulai)
            ->where(function ($query) use ($cycleStart) {
                $query->whereNull('removal_date')
                    ->orWhere('removal_date', '>=', $cycleStart);
            })

            ->orderBy('installation_date')
            ->get();
        $cpptRecords = \App\Models\PemeriksaanRanap::with('pegawai')
            ->where('no_rawat', $cycle->no_rawat)
            ->whereRaw("CONCAT(tgl_perawatan, ' ', jam_rawat) >= ?", [$cycle->start_time])
            ->whereRaw("CONCAT(tgl_perawatan, ' ', jam_rawat) <= ?", [$cycle->end_time])
            ->orderBy('tgl_perawatan', 'asc')
            ->orderBy('jam_rawat', 'asc')
            ->get();
        // 4. Bangun Matriks Data Per Jam
        $records = MonitoringRecord::with('parenteralIntakes')
            ->where('monitoring_cycle_id', $cycle->id)
            ->orderBy('record_time', 'asc')
            ->get();

        // Tentukan jam untuk kolom (mulai jam 6 pagi sampai 5 pagi berikutnya)
        $reportHours = $records
            ->pluck('record_time')
            ->map(fn($time) => \Carbon\Carbon::parse($time)->format('H:i'))
            ->unique()
            ->values();


        // Tentukan parameter untuk baris
        $hemoParameters = [
            'temp_incubator' => 'Temp Ink',
            'temp_skin' => 'Temp. Skin',
            'hr' => 'Heart Rate',
            'rr' => 'Respiratory Rate',
            'tensi' => 'Tekanan Darah',
            'sat_o2' => 'Sat O2',
            'irama_ekg' => 'Irama EKG',
            'skala_nyeri' => 'Skala Nyeri',
            'humidifier_inkubator' => 'Humidifier Inkubator'
        ];

        $observationParameters = [
            'cyanosis' => 'Cyanosis (+/-)',
            'pucat' => 'Pucat (+/-)',
            'ikterus' => 'Ikterus (+/-)',
            'crt_less_than_2' => 'CRT < 2 detik (+/-)',
            'bradikardia' => 'Bradikardia (+/-)',
            'stimulasi' => 'Stimulasi (+/-)'
        ];

        $fluidParameters = [
            'parenteral' => 'Parenteral',
            'intake_ogt' => 'OGT',
            'intake_oral' => 'Oral',
            'total_cm' => 'TOTAL CM',
            'output_ngt' => 'NGT',
            'output_urine' => 'Urine',
            'output_bab' => 'BAB',
            'output_drain' => 'Drain',
            'output_residu' => 'Residu/Muntah',
            'total_ck' => 'TOTAL CK'
        ];

        $ventilatorParams = [
            'SPONTAN' => [
                'spontan_fio2' => 'FiO₂',
                'spontan_flow' => 'Flow',
            ],
            'CPAP' => [
                'cpap_fio2' => 'FiO₂',
                'cpap_flow' => 'Flow',
                'cpap_peep' => 'PEEP',
            ],
            'HFO' => [
                'hfo_fio2' => 'FiO₂',
                'hfo_frekuensi' => 'Frekuensi',
                'hfo_map' => 'MAP',
                'hfo_amplitudo' => 'Amplitudo',
                'hfo_it' => 'IT',
            ],
            'MONITOR' => [
                'monitor_mode' => 'Mode',
                'monitor_fio2' => 'FiO₂',
                'monitor_peep' => 'PEEP',
                'monitor_pip' => 'PIP',
                'monitor_tv_vte' => 'TV/Vte',
                'monitor_rr_spontan' => 'RR Spontan',
                'monitor_p_max' => 'P. Max',
                'monitor_ie' => 'I : E',
            ],
        ];

        $bloodGasParameters = [
            'gula_darah' => 'Gula Darah',
            'ph' => 'pH',
            'pco2' => 'PCO₂',
            'po2' => 'PO₂',
            'hco3' => 'HCO₃⁻',
            'be' => 'BE',
            'sao2' => 'SaO₂',
        ];
        // Inisialisasi matriks data kosong
        $bloodGasMatrix = [];

        foreach ($bloodGasResults as $result) {
            $hour = Carbon::parse($result->taken_at)->format('H:i');
            foreach ($bloodGasParameters as $param => $label) {
                if (!isset($bloodGasMatrix[$param])) {
                    $bloodGasMatrix[$param] = [];
                }
                $bloodGasMatrix[$param][$hour] = $result->$param ?? '';
            }
        }
        $bloodGasHours = collect($bloodGasResults)
            ->map(fn($r) => Carbon::parse($r->taken_at)->format('H:i'))
            ->unique()
            ->values();
        $hemoMatrix = [];
        $observationMatrix = [];
        $fluidMatrix = [];
        $ventilatorMatrix = [];

        foreach ($reportHours as $hour) {
            foreach (array_keys($hemoParameters) as $param) {
                $hemoMatrix[$param][$hour] = '';
            }
            foreach (array_keys($observationParameters) as $param) {
                $observationMatrix[$param][$hour] = '';
            }
            foreach (array_keys($fluidParameters) as $param) {
                $fluidMatrix[$param][$hour] = 0;
            }
            foreach ($ventilatorParams as $group => $params) {
                foreach (array_keys($params) as $param) {
                    $ventilatorMatrix[$param][$hour] = '';
                }
            }
        }
        $parenteralMatrix = [];
        $uniqueInfusions = collect();

        foreach ($records as $record) {
            $recordHour = Carbon::parse($record->record_time)->format('H:i');
            if ($reportHours->contains($recordHour)) {
                // Isi Matriks Hemodinamik
                if ($record->temp_incubator)
                    $hemoMatrix['temp_incubator'][$recordHour] = $record->temp_incubator;
                if ($record->temp_skin)
                    $hemoMatrix['temp_skin'][$recordHour] = $record->temp_skin;
                if ($record->hr)
                    $hemoMatrix['hr'][$recordHour] = $record->hr;
                if ($record->rr)
                    $hemoMatrix['rr'][$recordHour] = $record->rr;
                if ($record->blood_pressure_systolic)
                    $hemoMatrix['tensi'][$recordHour] = $record->blood_pressure_systolic . '/' . $record->blood_pressure_diastolic;
                if ($record->sat_o2)
                    $hemoMatrix['sat_o2'][$recordHour] = $record->sat_o2;
                if ($record->irama_ekg)
                    $hemoMatrix['irama_ekg'][$recordHour] = $record->irama_ekg;
                if ($record->skala_nyeri)
                    $hemoMatrix['skala_nyeri'][$recordHour] = $record->skala_nyeri;
                if ($record->humidifier_inkubator)
                    $hemoMatrix['humidifier_inkubator'][$recordHour] = $record->humidifier_inkubator;

                // Isi Matriks Observasi Warna
                if ($record->cyanosis)
                    $observationMatrix['cyanosis'][$recordHour] = '+';
                if ($record->pucat)
                    $observationMatrix['pucat'][$recordHour] = '+';
                if ($record->ikterus)
                    $observationMatrix['ikterus'][$recordHour] = '+';
                if ($record->crt_less_than_2)
                    $observationMatrix['crt_less_than_2'][$recordHour] = '+';
                if ($record->bradikardia)
                    $observationMatrix['bradikardia'][$recordHour] = '+';
                if ($record->stimulasi)
                    $observationMatrix['stimulasi'][$recordHour] = '+';
                if ($record->intake_ogt)
                    $fluidMatrix['intake_ogt'][$recordHour] += (float) ($record->intake_ogt ?? 0);
                if ($record->intake_oral)
                    $fluidMatrix['intake_oral'][$recordHour] += (float) ($record->intake_oral ?? 0);
                if ($record->output_ngt)
                    $fluidMatrix['output_ngt'][$recordHour] += (float) ($record->output_ngt ?? 0);
                if ($record->output_urine)
                    $fluidMatrix['output_urine'][$recordHour] += (float) ($record->output_urine ?? 0);
                if ($record->output_bab)
                    $fluidMatrix['output_bab'][$recordHour] += (float) ($record->output_bab ?? 0);
                if ($record->output_drain)
                    $fluidMatrix['output_drain'][$recordHour] += (float) ($record->output_drain ?? 0);
                // if ($record->output_residu)
                //     $fluidMatrix['output_residu'][$recordHour] += (float) ($record->output_residu ?? 0);

                foreach ($ventilatorParams as $group => $params) {
                    foreach (array_keys($params) as $param) {
                        if (!empty($record->$param)) {
                            $ventilatorMatrix[$param][$recordHour] = $record->$param;
                        }
                    }
                }
                foreach ($record->parenteralIntakes as $intake) {
                    $infusionName = $intake->name;
                    $uniqueInfusions->push($infusionName);
                    if (!isset($parenteralMatrix[$infusionName])) {
                        foreach ($reportHours as $h) {
                            $parenteralMatrix[$infusionName][$h] = 0;
                        }
                    }
                    $parenteralMatrix[$infusionName][$recordHour] += (float) $intake->volume;
                }

            }
        }
        $filteredHours = $reportHours->filter(function ($hour) use ($hemoMatrix, $ventilatorMatrix, $observationMatrix, $fluidMatrix) {
            foreach ($hemoMatrix as $paramValues) {
                if (!empty($paramValues[$hour]))
                    return true;
            }
            foreach ($ventilatorMatrix as $paramValues) {
                if (!empty($paramValues[$hour]))
                    return true;
            }
            foreach ($observationMatrix as $paramValues) {
                if (!empty($paramValues[$hour]))
                    return true;
            }
            foreach ($fluidMatrix as $paramValues) {
                if (!empty($paramValues[$hour]))
                    return true;
            }
            return false;
        })->values();

        $activeObservationHours = collect($reportHours)->filter(function ($hour) use ($observationMatrix) {
            foreach ($observationMatrix as $valuesByHour) {
                if (!empty($valuesByHour[$hour])) {
                    return true;
                }
            }
            return false;
        })->values();

        $activeVentilatorHours = collect($reportHours)->filter(function ($hour) use ($ventilatorMatrix) {
            foreach ($ventilatorMatrix as $valuesByHour) {
                if (!empty($valuesByHour[$hour])) {
                    return true;
                }
            }
            return false;
        })->values();
        $uniqueInfusions = $uniqueInfusions->unique()->values();

        // Normalisasi data sebelum bikin grafik
        $records->transform(function ($r) {
            $r->temp_incubator = is_numeric($r->temp_incubator) && $r->temp_incubator < 100 ? (float) $r->temp_incubator : null;
            $r->temp_skin = is_numeric($r->temp_skin) && $r->temp_skin < 100 ? (float) $r->temp_skin : null;
            $r->hr = is_numeric($r->hr) && $r->hr < 300 ? (int) $r->hr : null;
            $r->rr = is_numeric($r->rr) && $r->rr < 150 ? (int) $r->rr : null;
            $r->blood_pressure_systolic = is_numeric($r->blood_pressure_systolic) && $r->blood_pressure_systolic < 300
                ? (int) $r->blood_pressure_systolic
                : null;
            // --- TAMBAHAN NORMALISASI ---
            $r->blood_pressure_diastolic = is_numeric($r->blood_pressure_diastolic) && $r->blood_pressure_diastolic < 200
                ? (int) $r->blood_pressure_diastolic
                : null;
            return $r;
        });

        // Hitung Total CM dan Total CK per jam di matriks
        foreach ($reportHours as $hour) {
            $parenteralTotalForHour = 0;
            foreach ($uniqueInfusions as $infusionName) {
                $parenteralTotalForHour += $parenteralMatrix[$infusionName][$hour];
            }

            $fluidMatrix['total_cm'][$hour] = $parenteralTotalForHour + $fluidMatrix['intake_ogt'][$hour] + $fluidMatrix['intake_oral'][$hour];
            $fluidMatrix['total_ck'][$hour] = $fluidMatrix['output_ngt'][$hour] + $fluidMatrix['output_urine'][$hour] + $fluidMatrix['output_bab'][$hour] + $fluidMatrix['output_drain'][$hour] + $fluidMatrix['output_residu'][$hour];
        }

        // 4. Kalkulasi Summary (sekarang bisa ambil dari total matriks)
        $totalIntake = array_sum($fluidMatrix['total_cm']);
        $totalOutput = array_sum($fluidMatrix['total_ck']);
        $iwl = $cycle->daily_iwl ?? 0;
        $balance = $totalIntake - $totalOutput - $iwl;
        $totalUrine = array_sum($fluidMatrix['output_urine']);


        // ---------------------------------------------------
        // 5. Buat Chart (LOGIKA BARU)
        // ---------------------------------------------------
        $vitalsRecords = $records->filter(function ($r) {
            return !is_null($r->hr) || !is_null($r->rr);
        })->values(); // values() untuk mereset index array
        $vitalsLabels = $vitalsRecords->pluck('record_time')->map(fn($t) => Carbon::parse($t)->format('H:i'))->toArray();
        $hrData = $vitalsRecords->pluck('hr')->toArray();
        $rrData = $vitalsRecords->pluck('rr')->toArray();
        // --- CHART 1: VITAL SIGNS (HR & RR) ---
        $chartVitalsData = [
            'type' => 'line',
            'data' => [
                'labels' => $vitalsLabels,
                'datasets' => [
                    [
                        'label' => 'Heart Rate (x/menit)',
                        'data' => $hrData,
                        'borderColor' => 'red',
                        'fill' => false,
                        'spanGaps' => true,
                    ],
                    [
                        'label' => 'Respiratory Rate (x/menit)',
                        'data' => $rrData,
                        'borderColor' => 'black',
                        'fill' => false,
                        'spanGaps' => true,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                    // 'annotation' => [...]  <-- hilangkan dulu
                ],
                'scales' => [
                    'y' => [
                        'title' => ['display' => true, 'text' => 'Nilai'],
                        'min' => 0,
                        'max' => max(max($hrData ?? [0]), max($rrData ?? [0])) + 20,
                    ],
                ],
            ],
        ];


        // --- PERSIAPAN DATA UNTUK CHART 2: SUHU ---
        // Filter $records HANYA untuk yg memiliki data Suhu
        $tempRecords = $records->filter(function ($r) {
            return !is_null($r->temp_incubator) || !is_null($r->temp_skin);
        })->values();

        $tempLabels = $tempRecords->pluck('record_time')->map(fn($t) => Carbon::parse($t)->format('H:i'));
        $tempIncubatorData = $tempRecords->pluck('temp_incubator');
        $tempSkinData = $tempRecords->pluck('temp_skin');
        // --- CHART 2: SUHU ---
        $chartTempData = [
            'type' => 'line',
            'data' => [
                'labels' => $tempLabels,
                'datasets' => [
                    [
                        'label' => 'Temp. Inkubator (°C)',
                        'data' => $tempIncubatorData,
                        'borderColor' => 'green',
                        'fill' => false,
                    ],
                    [
                        'label' => 'Temp. Skin (°C)',
                        'data' => $tempSkinData,
                        'borderColor' => 'blue',
                        'fill' => false,
                    ],
                ],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                    'annotation' => [
                        'annotations' => [
                            [
                                'type' => 'box',
                                'yMin' => 36.5, // Batas normal suhu (ganti sesuai kebutuhan)
                                'yMax' => 37.5,
                                'backgroundColor' => 'rgba(0, 255, 0, 0.05)',
                                'borderColor' => 'rgba(0, 255, 0, 0.1)',
                            ]
                        ]
                    ]
                ],
                'scales' => [
                    'y' => [
                        'title' => ['display' => true, 'text' => 'Suhu (°C)'],
                        'min' => 35,
                        'max' => 40
                    ]
                ]
            ]
        ];

        // --- PERSIAPAN DATA UNTUK CHART 3: TEKANAN DARAH ---
        // Filter $records HANYA untuk yg memiliki data Tensi
        $bpRecords = $records->filter(function ($r) {
            return !is_null($r->blood_pressure_systolic) || !is_null($r->blood_pressure_diastolic);
        })->values();

        $bpLabels = $bpRecords->pluck('record_time')->map(fn($t) => Carbon::parse($t)->format('H:i'));
        $bpSystolicData = $bpRecords->pluck('blood_pressure_systolic');
        $bpDiastolicData = $bpRecords->pluck('blood_pressure_diastolic');

        // --- CHART 3: TEKANAN DARAH ---
        $chartBpData = [
            'type' => 'line',
            'data' => [
                'labels' => $bpLabels, // Menggunakan label yg sudah difilter
                'datasets' => [
                    [
                        'label' => 'Sistolik (mmHg)',
                        'data' => $bpSystolicData, // Menggunakan data yg sudah difilter
                        'borderColor' => 'darkred',
                        'fill' => false,
                    ],
                    [
                        'label' => 'Diastolik (mmHg)',
                        'data' => $bpDiastolicData, // Menggunakan data yg sudah difilter
                        'borderColor' => 'orange',
                        'fill' => false,
                    ],
                ],
            ],
            'options' => [
                'plugins' => ['legend' => ['position' => 'bottom']],
                'scales' => [
                    'y' => [
                        'title' => ['display' => true, 'text' => 'Tekanan Darah (mmHg)'],
                        'min' => 30, // Sesuaikan rentang
                        'max' => 100
                    ]
                ]
            ]
        ];

        // --- Generate Base64 untuk semua chart ---
        $chartVitalsBase64 = null;
        $chartTempBase64 = null;
        $chartBpBase64 = null;

        $chartVitalsBase64 = $this->generateChartWithCurl($chartVitalsData);
        $chartTempBase64 = $this->generateChartWithCurl($chartTempData);
        $chartBpBase64 = $this->generateChartWithCurl($chartBpData);
        // 1. Inisialisasi variabel (ini sudah benar)
        $masalahOnly = '';
        $programOnly = '';
        $nutrisiEnteralOnly = '';
        $nutrisiParenteralOnly = '';
        $pemeriksaanLabOnly = '';

        $latestProgram = $cycle->therapyPrograms() // Asumsi nama relasinya 'therapyPrograms'
            ->latest()          // Ambil yang terbaru
            ->first();

        // 3. Langsung isi variabel dari 5 kolom terpisah (TANPA RegEx)
        if ($latestProgram) {
            $masalahOnly = $latestProgram->masalah_klinis;
            $programOnly = $latestProgram->program_terapi;
            $nutrisiEnteralOnly = $latestProgram->nutrisi_enteral;
            $nutrisiParenteralOnly = $latestProgram->nutrisi_parenteral;
            $pemeriksaanLabOnly = $latestProgram->pemeriksaan_lab;
        }
        // ---------------------------------------------------
        // 6. Siapkan Data untuk PDF (DATA BARU)
        // ---------------------------------------------------
        $dataForPdf = [
            'patient' => $patientData,
            'cycle' => $cycle,
            'records' => $records,
            'setting' => $setting,

            // --- GANTI DARI 1 CHART MENJADI 3 ---
            'chartVitalsBase64' => $chartVitalsBase64,
            'chartTempBase64' => $chartTempBase64,
            'chartBpBase64' => $chartBpBase64,
            // 'chartBase64' => $chartBase64, // (Yang lama dihapus)

            'medications' => $medications,
            'parenteralMatrix' => $parenteralMatrix,
            'uniqueInfusions' => $uniqueInfusions,
            'bloodGasResults' => $bloodGasResults,
            'pippAssessments' => $pippAssessments,
            'fluidMatrix' => $fluidMatrix,
            'fluidParameters' => $fluidParameters,
            'hemoMatrix' => $hemoMatrix,
            'hemoParameters' => $hemoParameters,
            'observationMatrix' => $observationMatrix,
            'observationParameters' => $observationParameters,
            'reportHours' => $filteredHours,
            'activeObservationHours' => $activeObservationHours,
            'activeVentilatorHours' => $activeVentilatorHours,
            'ventilatorMatrix' => $ventilatorMatrix,
            'ventilatorParams' => $ventilatorParams,
            'previousCycle' => $previousCycle,
            'patientDevices' => $patientDevices,
            'bloodGasMatrix' => $bloodGasMatrix,
            'bloodGasParameters' => $bloodGasParameters,
            'bloodGasHours' => $bloodGasHours,
            'therapySections' => [
                'masalahOnly' => $masalahOnly,
                'programOnly' => $programOnly,
                'nutrisiEnteralOnly' => $nutrisiEnteralOnly,
                'nutrisiParenteralOnly' => $nutrisiParenteralOnly,
                'pemeriksaanLabOnly' => $pemeriksaanLabOnly,
            ],
            'summary' => [
                'totalIntake' => $totalIntake,
                'totalOutput' => $totalOutput,
                'totalUrine' => $totalUrine,
                'iwl' => $iwl,
                'balance' => $balance,
            ],
            'umur_bayi' => Carbon::parse($patientData->tgl_lahir)->diffInDays($cycle->start_time),
            'hari_rawat_ke' => Carbon::parse($patientData->tgl_masuk)->diffInDays($cycle->start_time) + 1,
        ];
        $dataForSoap = [
            'dataForSoap' => $cpptRecords,
            'patient' => $patientData,

        ];
        // 7. Generate PDF
        $pdfMonitoring = Pdf::loadView('pdf.monitoring-report', $dataForPdf)
            ->setPaper('a3', 'potrait');
        $monitoringBinary = $pdfMonitoring->output();
        $pdfSoap = Pdf::loadView('pdf.soap', $dataForSoap)
            ->setPaper('a4', 'potrait');
        // dd(strlen($pdfSoap->output()));
        $soapBinary = $pdfSoap->output(); // <-- string PDF binary
        // --- 4. Gabungkan dua PDF ---
        $merger = new Merger;
        $merger->addRaw($monitoringBinary);
        $merger->addRaw($soapBinary);
        $mergedPdf = $merger->merge();

        // --- 5. Simpan / stream hasilnya ---
        $sanitizedNoRawat = str_replace('/', '_', $no_rawat);
        $pdfFilename = 'monitoring-soap-' . $sanitizedNoRawat . '-' . $cycle->start_time->format('Ymd') . '.pdf';

        return response($mergedPdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $pdfFilename . '"');
    }


    public $umur_kehamilan;
    public $berat_lahir;
    public $umur_bayi;
    public $umur_koreksi;
    public $cara_persalinan;
    public $info_rujukan;
    public $jaminan;
    public $status_rujukan;

    public $asal_bangsal;
    public $asal_poli;
    public function mount(string $no_rawat): void
    {
        $this->no_rawat = $no_rawat;
        $rawData = DB::table('reg_periksa as rp')
            ->join('pasien as p', 'rp.no_rkm_medis', '=', 'p.no_rkm_medis')
            ->join('kamar_inap as ki', 'rp.no_rawat', '=', 'ki.no_rawat')
            ->join('dokter as d', 'rp.kd_dokter', '=', 'd.kd_dokter')
            ->join('penjab as pj', 'rp.kd_pj', '=', 'pj.kd_pj')
            ->leftJoin('pasien_bayi as pb', 'p.no_rkm_medis', '=', 'pb.no_rkm_medis')
            ->join('kamar as k', 'ki.kd_kamar', '=', 'k.kd_kamar')
            ->join('bangsal as b', 'k.kd_bangsal', '=', 'b.kd_bangsal')
            ->select(
                'p.nm_pasien',
                'p.no_rkm_medis',
                'p.tgl_lahir',
                'p.jk',
                'pb.berat_badan',
                'pb.proses_lahir',
                'ki.tgl_masuk',
                'ki.diagnosa_awal',
                'd.nm_dokter',
                'pj.png_jawab as jaminan',
                'rp.tgl_registrasi',
                'rp.no_rawat',
                'rp.stts as status_rujukan',
                'b.nm_bangsal as asal_bangsal'
            )
            ->where('ki.stts_pulang', '-')
            ->where('rp.no_rawat', $this->no_rawat)
            ->orderBy('ki.tgl_masuk', 'desc')
            ->first();
        if (!$rawData) {
            abort(404, 'Data registrasi atau kamar inap pasien tidak ditemukan.');
        }
        $this->patient = $rawData;
        $this->berat_lahir = $rawData?->berat_badan ?? null;
        $this->cara_persalinan = $rawData?->proses_lahir ?? 'Unknown';
        $this->jaminan = $rawData?->jaminan ?? null;
        $this->status_rujukan = $rawData?->status_rujukan ?? null;
        $this->asal_bangsal = $rawData?->asal_bangsal ?? null;

        // Kalkulasi Usia
        $tanggalLahir = isset($rawData->tgl_lahir) ? Carbon::parse($rawData->tgl_lahir) : null;

        $this->umur_bayi = $tanggalLahir ? $tanggalLahir->diffInDays(now()) : null;
        $mingguKehamilan = (int) $this->umur_kehamilan;
        $mingguKehamilan = isset($this->umur_kehamilan) ? (int) $this->umur_kehamilan : 0;
        if ($mingguKehamilan > 0 && $mingguKehamilan < 37) {
            $mingguPrematur = 40 - $mingguKehamilan;
            $tanggalCukupBulan = $tanggalLahir->copy()->addWeeks($mingguPrematur);
            $this->umur_koreksi = $tanggalCukupBulan->diffInWeeks(now());
        } else {
            $this->umur_koreksi = null;
        }

        if (!$this->patient) {
            abort(404);
        }

        // --- CARI SIKLUS SAAT INI DI MOUNT ---
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
        $this->therapy_program_history = new Collection();
        // $this->loadPatientDevices();
        $this->taken_at = now()->format('Y-m-d\TH:i');
        $this->selectedDate = now()->format('Y-m-d');
    }
    public function loadPatientDevices(): void
    {
        if (!$this->readyToLoad) {
            $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
            return; // Hentikan eksekusi
        }
        if (!$this->currentCycle) {
            $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
            return;
        }

        // 2. Tentukan batas waktu siklus (ini sudah timestamp)
        $cycleStart = $this->currentCycle->start_time; // Misal: '2025-10-22 06:00:00'
        $cycleEnd = $this->currentCycle->end_time;     // Misal: '2025-10-23 05:59:59'
        // 3. Kueri Logika Klinis yang AKURAT
        $this->patientDevices = PatientDevice::where('no_rawat', $this->no_rawat)
            // Logika 1: Alat harus dipasang SEBELUM siklus ini BERAKHIR
            // (Kita asumsikan installation_date sekarang adalah DATETIME)
            ->where('installation_date', '<=', $cycleEnd)
            // Logika 2: DAN (Alatnya belum dicabut ATAU dicabut SETELAH siklus ini DIMULAI)
            ->where(function ($query) use ($cycleStart) {
                // Masih terpasang (belum dicabut)
                $query->whereNull('removal_date')
                    // ATAU dicabutnya nanti (setelah siklus mulai),
                    // yang berarti alat itu aktif di dalam siklus ini.
                    ->orWhere('removal_date', '>=', $cycleStart);
            })
            ->orderBy('installation_date')
            ->get();
    }
    public function openDeviceModal($deviceId = null)
    {
        // Reset form
        $this->reset('device_name', 'size', 'location', 'installation_date', 'editingDeviceId');

        if ($deviceId) {
            $device = PatientDevice::find($deviceId);
            if ($device && $device->no_rawat === $this->no_rawat) {
                $this->editingDeviceId = $deviceId;
                $this->device_name = $device->device_name;
                $this->size = $device->size;
                $this->location = $device->location;
                $this->installation_date = $device->installation_date->format('Y-m-d\TH:i');
            }
        } else {
            // Mode Tambah Baru - set default tanggal
            $this->installation_date = now()->format('Y-m-d\TH:i');
        }

        $this->showDeviceModal = true;
    }

    public function closeDeviceModal()
    {
        $this->showDeviceModal = false;
        $this->reset('device_name', 'size', 'location', 'installation_date', 'editingDeviceId');
    }

    public function saveDevice()
    {
        // 1. Validasi data yang bisa diisi pengguna
        $this->validate([
            'device_name' => 'required|string',
            'size' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // 2. Siapkan data dasar (yang selalu di-update)
        $data = [
            'no_rawat' => $this->no_rawat,
            'device_name' => $this->device_name,
            'size' => $this->size,
            'location' => $this->location,
        ];

        if ($this->editingDeviceId) {
            $device = PatientDevice::find($this->editingDeviceId);
            if ($device && $device->no_rawat === $this->no_rawat) {
                $device->update($data); // Hanya update $data
                $this->dispatch('record-saved', ['message' => 'Data alat berhasil diperbarui!']);
            }
        } else {
            $this->validate([
                'installation_date' => 'required|date_format:Y-m-d\TH:i',
            ]);

            // Tambahkan 'installation_date' HANYA saat membuat data baru
            $data['installation_date'] = $this->installation_date;
            $data['installed_by_user_id'] = auth()->id();
            PatientDevice::create($data);
            $this->dispatch('record-saved', ['message' => 'Alat baru berhasil ditambahkan!']);
        }

        $this->closeDeviceModal();
        $this->dispatch('refresh-devices');
    }
    #[On('refresh-devices')]
    public function loadPatientDevicesOnly()
    {
        if (!$this->readyToLoad) {
            $this->patientDevices = new \Illuminate\Database\Eloquent\Collection();
            return;
        }

        // Ambil logika kueri dari fungsi loadPatientDevices() Anda yang lama
        $cycleStart = $this->currentCycle->start_time;
        $cycleEnd = $this->currentCycle->end_time;

        $this->patientDevices = PatientDevice::where('no_rawat', $this->no_rawat)
            ->where('installation_date', '<=', $cycleEnd)
            ->where(function ($query) use ($cycleStart) {
                $query->whereNull('removal_date')
                    ->orWhere('removal_date', '>=', $cycleStart);
            })
            ->orderBy('installation_date')
            ->get();
    }

    /**
     * Mencatat waktu cabut alat (TIDAK MENGHAPUS).
     */

    /**
     * Helper untuk memuat ulang daftar nama infus/enteral
     * berdasarkan SEMUA data di siklus saat ini.
     */
    /**
     * Helper untuk memuat ulang daftar nama infus/enteral.
     * Prioritas: Nama dari siklus saat ini.
     * Fallback: Nama dari siklus sebelumnya.
     */
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
        // $this->dispatch('refresh-records');
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
    public function loadRecords(): void
    {

        if (!$this->readyToLoad) {
            $this->records = new \Illuminate\Database\Eloquent\Collection();
            $this->medications = new \Illuminate\Database\Eloquent\Collection();
            $this->bloodGasResults = new \Illuminate\Database\Eloquent\Collection();
            $this->pippAssessments = new \Illuminate\Database\Eloquent\Collection();
            $this->recentMedicationNames = new \Illuminate\Support\Collection();
            $this->fluidRecords = new Collection();
            $this->therapy_program_history = new Collection();
            return;
        }
        // Pastikan kita mulai dari awal hari tanggal yang dipilih
        $targetDate = Carbon::parse(time: $this->selectedDate)->startOfDay();

        // Asumsi default: siklus dimulai jam 6 pagi pada tanggal yang dipilih
        $cycleStartTime = $targetDate->copy()->addHours(6);



        // Kasus khusus: Jika tanggal yang dipilih adalah HARI INI, DAN jam SEKARANG < 6 pagi,
        // maka siklus yang relevan dimulai KEMARIN jam 6 pagi.
        if ($targetDate->isToday() && now()->hour < 6) {
            $cycleStartTime->subDay();
        }
        // Untuk tanggal selain hari ini, $cycleStartTime sudah benar (mulai jam 6 di tanggal tsb)

        // Cari siklus saat ini (berdasarkan $cycleStartTime yang sudah benar)
        $currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('start_time', $cycleStartTime)
            ->first();

        // Cari siklus sebelumnya
        $previousCycleStartTime = $cycleStartTime->copy()->subDay();
        $previousCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('start_time', $previousCycleStartTime)
            ->first();

        // 1. Hitung & Simpan Balance Siklus Kemarin (jika perlu)
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
            $this->therapy_program = $currentCycle->therapy_program;
            $this->clinical_problems = $currentCycle->clinical_problems;
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

            $this->therapy_program_history = TherapyProgram::with('pegawai')
                ->where('monitoring_cycle_id', $this->currentCycleId)
                ->orderByDesc('created_at')
                ->get();


        } else {
            $this->currentCycleId = null;
            $this->therapy_program = '';
            $this->clinical_problems = '';
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
            $this->current_therapy_program_text = '';
            $this->therapy_program_history = new Collection();
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
    public function openPippModal()
    {
        $this->reset(
            'gestational_age',
            'behavioral_state',
            'max_heart_rate',
            'min_oxygen_saturation',
            'brow_bulge',
            'eye_squeeze',
            'nasolabial_furrow',
            'pipp_total_score'
        );
        $this->pipp_assessment_time = now()->format('Y-m-d\TH:i');
        $this->showPippModal = true;
    }

    public function closePippModal()
    {
        $this->showPippModal = false;
    }

    public function savePippScore()
    {
        // Calculate total score
        $total = (int) $this->gestational_age + (int) $this->behavioral_state + (int) $this->max_heart_rate +
            (int) $this->min_oxygen_saturation + (int) $this->brow_bulge + (int) $this->eye_squeeze +
            (int) $this->nasolabial_furrow;
        $this->pipp_total_score = $total;

        $this->validate([
            'pipp_assessment_time' => 'required|date',
            'gestational_age' => 'required|integer|min:0|max:3',
            'behavioral_state' => 'required|integer|min:0|max:3',
            'max_heart_rate' => 'required|integer|min:0|max:3',
            'min_oxygen_saturation' => 'required|integer|min:0|max:3',
            'brow_bulge' => 'required|integer|min:0|max:3',
            'eye_squeeze' => 'required|integer|min:0|max:3',
            'nasolabial_furrow' => 'required|integer|min:0|max:3',
        ]);

        if ($this->currentCycleId) {
            PippAssessment::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'id_user' => auth()->id(),
                'assessment_time' => $this->pipp_assessment_time,
                'gestational_age' => $this->gestational_age,
                'behavioral_state' => $this->behavioral_state,
                'max_heart_rate' => $this->max_heart_rate,
                'min_oxygen_saturation' => $this->min_oxygen_saturation,
                'brow_bulge' => $this->brow_bulge,
                'eye_squeeze' => $this->eye_squeeze,
                'nasolabial_furrow' => $this->nasolabial_furrow,
                'total_score' => $this->pipp_total_score,
            ]);
            $this->skala_nyeri = $this->pipp_total_score;
            $this->closePippModal();
            $this->dispatch('refresh-pip');
            // $this->loadRecords(); // Reload data
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



    public $showTherapyModal = false;
    /**
     * Fungsi untuk membuka modal dan mengisi 5 textarea
     * dengan data dari riwayat program terapi TERBARU.
     */
    public function openTherapyModal()
    {
        $this->showTherapyModal = true;

        // 1. Ambil data program TERBARU dari riwayat yang sudah di-load
        //    (Logika Anda di sini sudah benar)
        $latestProgram = $this->therapy_program_history->first();

        // 2. Cek apakah ada riwayat
        if ($latestProgram) {

            // 3. TIDAK PERLU PARSING / SPLITTING LAGI
            //    Sekarang kita langsung ambil dari 5 kolom yang terpisah
            //    sesuai dengan struktur tabel baru.

            $this->therapy_program_masalah = $latestProgram->masalah_klinis;
            $this->therapy_program_program = $latestProgram->program_terapi;
            $this->therapy_program_enteral = $latestProgram->nutrisi_enteral;
            $this->therapy_program_parenteral = $latestProgram->nutrisi_parenteral;
            $this->therapy_program_lab = $latestProgram->pemeriksaan_lab;

        } else {
            // 4. Jika TIDAK ADA riwayat, pastikan 5 properti itu kosong.
            //    (Lebih baik menggunakan reset() agar bersih)
            $this->reset([
                'therapy_program_masalah',
                'therapy_program_program',
                'therapy_program_enteral',
                'therapy_program_parenteral',
                'therapy_program_lab',
            ]);
        }
    }

    // Fungsi ini sudah benar, biarkan saja
    public function closeTherapyModal()
    {
        $this->showTherapyModal = false;
    }
    public $therapy_program_program;
    public $therapy_program_enteral;
    public $therapy_program_parenteral;
    public $therapy_program_masalah;
    public $therapy_program_lab;

    /**
     * GANTI FUNGSI LAMA ANDA DENGAN YANG INI
     */
    public function saveTherapyProgram()
    {
        if (!$this->currentCycleId) {
            $this->dispatch('error-notification', message: 'Simpan data observasi pertama untuk membuat siklus terapi.');
            return;
        }

        // 1. Validasi (Sama seperti sebelumnya)
        $this->validate([
            'therapy_program_masalah' => 'required|string',
            'therapy_program_program' => 'required|string',
            'therapy_program_enteral' => 'required|string',
            'therapy_program_parenteral' => 'required|string',
            'therapy_program_lab' => 'required|string',
        ]);

        $latestProgram = TherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
            ->latest()
            ->first();

        $currentUserId = Auth::id();
        if (
            $latestProgram &&
            $latestProgram->masalah_klinis === $this->therapy_program_masalah &&
            $latestProgram->program_terapi === $this->therapy_program_program &&
            $latestProgram->nutrisi_enteral === $this->therapy_program_enteral &&
            $latestProgram->nutrisi_parenteral === $this->therapy_program_parenteral &&
            $latestProgram->pemeriksaan_lab === $this->therapy_program_lab &&
            $latestProgram->id_user === $currentUserId
        ) {
            // Jika SAMA PERSIS, baru kita stop.
            $this->dispatch('notify', 'Info: Tidak ada perubahan pada program terapi.');
            $this->closeTherapyModal();
            return;
        }

        // 4. Simpan sebagai riwayat BARU
        TherapyProgram::create([
            'monitoring_cycle_id' => $this->currentCycleId,
            'no_rawat' => $this->no_rawat,
            'id_user' => $currentUserId,
            'masalah_klinis' => $this->therapy_program_masalah,
            'program_terapi' => $this->therapy_program_program,
            'nutrisi_enteral' => $this->therapy_program_enteral,
            'nutrisi_parenteral' => $this->therapy_program_parenteral,
            'pemeriksaan_lab' => $this->therapy_program_lab
        ]);

        $this->dispatch('refresh-therapy-history');
        $this->dispatch('record-saved', message: 'Program Terapi berhasil disimpan!');
        $this->closeTherapyModal();
    }

    #[On('refresh-therapy-history')]
    public function loadTherapyHistoryOnly()
    {
        if ($this->currentCycleId) {
            $this->therapy_program_history = TherapyProgram::where('monitoring_cycle_id', $this->currentCycleId)
                ->with('pegawai') // Eager load data perawat/dokter
                ->orderBy('created_at', 'desc') // Tampilkan yang terbaru di atas
                ->get();
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
        // $this->dispatch('refresh-records');
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
    public function openMedicationModal()
    {
        $this->reset('medication_name', 'dose', 'route');
        $this->given_at = now()->format('Y-m-d\TH:i');
        $this->showMedicationModal = true;
    }

    public function closeMedicationModal()
    {
        $this->showMedicationModal = false;
    }

    public function saveMedication()
    {
        $this->validate([
            'medication_name' => 'required|string',
            'dose' => 'required|string',
            'route' => 'required|string',
            'given_at' => 'required|date',
        ]);

        if ($this->currentCycleId) {
            Medication::create([
                'monitoring_cycle_id' => $this->currentCycleId,
                'id_user' => auth()->id(),
                'medication_name' => $this->medication_name,
                'dose' => $this->dose,
                'route' => $this->route,
                'given_at' => $this->given_at,
            ]);

            $this->closeMedicationModal();
            $this->dispatch('refresh-medications');
            $this->dispatch('record-saved', ['message' => 'Pemberian obat berhasil dicatat!']);
        } else {
            $this->dispatch('error-notification', ['message' => 'Simpan data observasi pertama untuk membuat siklus.']);
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

    public function saveBloodGasResult()
    {
        $this->validate([
            'taken_at' => 'required|date',
            'gula_darah' => 'nullable|numeric',
            'ph' => 'nullable|numeric',
            'pco2' => 'nullable|numeric',
            'po2' => 'nullable|numeric',
            'hco3' => 'nullable|numeric',
            'be' => 'nullable|numeric',
            'sao2' => 'nullable|numeric',
        ]);

        $fields = [
            $this->gula_darah,
            $this->ph,
            $this->pco2,
            $this->po2,
            $this->hco3,
            $this->be,
            $this->sao2,
        ];

        if (collect($fields)->filter(fn($v) => $v !== null && $v !== '')->isEmpty()) {
            $this->dispatch('error-notification', ['message' => 'Minimal satu hasil gas darah harus diisi.']);
            return;
        }

        if (!$this->currentCycleId) {
            $this->dispatch('error-notification', ['message' => 'Simpan data observasi pertama untuk membuat siklus.']);
            return;
        }

        BloodGasResult::create([
            'monitoring_cycle_id' => $this->currentCycleId,
            'id_user' => auth()->id(),
            'taken_at' => $this->taken_at,
            'gula_darah' => $this->gula_darah,
            'ph' => $this->ph,
            'pco2' => $this->pco2,
            'po2' => $this->po2,
            'hco3' => $this->hco3,
            'be' => $this->be,
            'sao2' => $this->sao2,
        ]);

        $this->dispatch('refresh-blood-gas');
        $this->dispatch('record-saved', ['message' => 'Hasil Gas Darah berhasil dicatat!']);
        $this->dispatch('close-blood-gas-modal');
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
