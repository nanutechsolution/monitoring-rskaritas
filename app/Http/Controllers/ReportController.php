<?php

namespace App\Http\Controllers;

use App\Models\MonitoringCycle;
use App\Models\Medication;
use App\Models\BloodGasResult;
use App\Models\BloodGasResultPicu;
use App\Models\IntraAnesthesiaMonitoring;
use App\Models\KamarInap;
use App\Models\MedicationPicu;
use App\Models\MonitoringCycleIcu;
use App\Models\PicuMonitoring;
use App\Models\PippAssessment;
use App\Models\MonitoringRecord;
use App\Models\PemeriksaanRanap;
use App\Models\PicuMonitoringCycle;
use App\Models\PicuMonitoringRecord;
use App\Models\PippAssessmentPicu;
use App\Models\RegPeriksa;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{


    /**
     * Menampilkan halaman cetak untuk Monitoring Anestesi
     */
    public function printAnesthesia($monitoringId)
    {
        // 1. Ambil data (Sama seperti sebelumnya)
        $monitoring = IntraAnesthesiaMonitoring::with(
            'vitals',
            'medications',
            'dokterAnestesi',
            'penataAnestesi',
            'registrasi.pasien' // Pastikan kita mengambil data pasien
        )->findOrFail($monitoringId);

        // 2. Ambil data setting rumah sakit (Sama seperti sebelumnya)
        $setting = Setting::instance();
        $logoBase64 = null;
        if ($setting->logo && strlen($setting->logo) > 0) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_buffer($finfo, $setting->logo); // misal image/jpeg
            finfo_close($finfo);
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode($setting->logo);
        }


        // 3. 💡 BARU: Siapkan Data Grafik sebagai Gambar
        // dompdf tidak bisa menjalankan JavaScript (Chart.js).
        // Kita akan menggunakan API quickchart.io untuk membuat gambar grafik.
        $chartData = null;
        if ($monitoring->vitals->isNotEmpty()) {
            $vitalsCollection = $monitoring->vitals->sortBy('waktu');
            $chartConfig = [
                'type' => 'line',
                'data' => [
                    'labels' => $vitalsCollection->pluck('waktu')->all(),
                    'datasets' => [
                        ['label' => 'Nadi', 'data' => $vitalsCollection->pluck('rrn')->map(fn($v) => $v ?? 0), 'borderColor' => '#EF4444', 'fill' => false],
                        ['label' => 'Sis', 'data' => $vitalsCollection->pluck('td_sis')->map(fn($v) => $v ?? 0), 'borderColor' => '#3B82F6', 'fill' => false],
                        ['label' => 'Dis', 'data' => $vitalsCollection->pluck('td_dis')->map(fn($v) => $v ?? 0), 'borderColor' => '#10B981', 'fill' => false],
                        ['label' => 'RR', 'data' => $vitalsCollection->pluck('rr')->map(fn($v) => $v ?? 0), 'borderColor' => '#F59E0B', 'fill' => false],
                    ]
                ]
            ];

            $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
            $chartImage = file_get_contents($chartUrl);
            $chartData = 'data:image/png;base64,' . base64_encode($chartImage);
        }

        // 4. 💡 BARU: Render View ke PDF
        $pdf = Pdf::loadView('pdf.ok.anesthesia-print', [
            'monitoring' => $monitoring,
            'pasien' => $monitoring->registrasi->pasien,
            'setting' => $setting,
            'chartImageUrl' => $chartData,
            'logoBase64' => $logoBase64,
        ]);

        // Atur ukuran kertas dan orientasi
        $pdf->setPaper('A4', 'portrait');

        // 5. Alirkan (stream) PDF ke browser
        return $pdf->stream('monitoring-intra-anestesi-' . $monitoring->no_rekam_medis . '.pdf');
    }
    /**
     * Method untuk generate PDF report.
     * Menerima parameter dari route.
     */
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

        $registrasi = RegPeriksa::with(['pasien', 'poliklinik', 'penjab', 'dpjpRanap.dokter'])
            ->where('no_rawat', $no_rawat)->firstOrFail();
        $dpjpDokters = $registrasi->dpjpRanap->map(fn($dpjp) => $dpjp->dokter)->filter();
        $diagnosaPasien = DB::table('diagnosa_pasien')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->where('diagnosa_pasien.no_rawat', $cycle->no_rawat)
            ->where('diagnosa_pasien.status', 'Ranap')
            ->orderBy('diagnosa_pasien.prioritas', 'asc')
            ->pluck('penyakit.nm_penyakit');
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
        $diagnosaPasien = DB::table('diagnosa_pasien')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->where('diagnosa_pasien.no_rawat', $cycle->no_rawat)
            ->where('diagnosa_pasien.status', 'Ranap')
            ->orderBy('diagnosa_pasien.prioritas', 'asc')
            ->pluck('penyakit.nm_penyakit');
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
                'spontan_fio2' => 'FiO2',
                'spontan_flow' => 'Flow',
            ],
            'CPAP' => [
                'cpap_fio2' => 'FiO2',
                'cpap_flow' => 'Flow',
                'cpap_peep' => 'PEEP',
            ],
            'HFO' => [
                'hfo_fio2' => 'FiO2',
                'hfo_frekuensi' => 'Frekuensi',
                'hfo_map' => 'MAP',
                'hfo_amplitudo' => 'Amplitudo',
                'hfo_it' => 'IT',
            ],
            'MONITOR' => [
                'monitor_mode' => 'Mode',
                'monitor_fio2' => 'FiO2',
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
            'pco2' => 'PCO2',
            'po2' => 'PO2',
            'hco3' => 'HCO3',
            'be' => 'BE',
            'sao2' => 'SaO2',
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
                ],
                'scales' => [
                    'y' => [
                        'title' => ['display' => true, 'text' => 'Nilai'],
                        'min' => 0,
                        'max' => max(
                            !empty($hrData) ? max($hrData) : 0,
                            !empty($rrData) ? max($rrData) : 0
                        ) + 20,
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
            'dpjpDokters' => $dpjpDokters,
            'diagnosaPasien' => $diagnosaPasien,

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
            'isPICU' => true,
            'title' => 'MONITORING 24 JAM NEONATUS INTENSIF CARE UNIT (NICU)',
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
            'umur_bayi' => Carbon::parse($patientData->tgl_lahir)
                ->diffInDays(Carbon::parse($cycle->start_time)),
            'hari_rawat_ke' => Carbon::parse($patientData->tgl_masuk)
                ->startOfDay()
                ->diffInDays(Carbon::parse($cycle->start_time)->startOfDay()) + 1,
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
        $soapBinary = $pdfSoap->output();
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
    /**
     * Method untuk generate PDF report.
     * Menerima parameter dari route.
     */
    public function generateReportPicuPdf($no_rawat, $cycle_id)
    {
        $setting = DB::table('setting')->first();
        $no_rawat = str_replace('_', '/', $no_rawat);

        // 1. Validasi
        $cycle = PicuMonitoringCycle::find($cycle_id);
        if (!$cycle || $cycle->no_rawat !== $no_rawat) {
            abort(404, 'Siklus monitoring tidak ditemukan.');
        }
        $previousCycle = PicuMonitoringCycle::where('no_rawat', $cycle->no_rawat)
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
        $medications = MedicationPicu::where('monitoring_cycle_id', $cycle->id)->orderBy('given_at', 'asc')->get();
        $bloodGasResults = BloodGasResultPicu::where('monitoring_cycle_id', $cycle->id)->orderBy('taken_at', 'asc')->get();
        $pippAssessments = [];

        $cycleStart = $cycle->start_time;
        $cycleEnd = $cycle->end_time;
        $patientDevices = \App\Models\PicuPatientDevice::where('no_rawat', $no_rawat)
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
        $records = PicuMonitoringRecord::with('parenteralIntakes')
            ->where('monitoring_cycle_id', $cycle->id)
            ->orderBy('record_time', 'asc')
            ->get();
        $registrasi = RegPeriksa::with(['pasien', 'poliklinik', 'penjab', 'dpjpRanap.dokter'])
            ->where('no_rawat', $cycle->no_rawat)->firstOrFail();
        $dpjpDokters = $registrasi->dpjpRanap->map(fn($dpjp) => $dpjp->dokter)->filter();
        $diagnosaPasien = DB::table('diagnosa_pasien')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->where('diagnosa_pasien.no_rawat', $cycle->no_rawat)
            ->where('diagnosa_pasien.status', 'Ranap')
            ->orderBy('diagnosa_pasien.prioritas', 'asc')
            ->pluck('penyakit.nm_penyakit');
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
                'spontan_fio2' => 'FiO2',
                'spontan_flow' => 'Flow',
            ],
            'CPAP' => [
                'cpap_fio2' => 'FiO2',
                'cpap_flow' => 'Flow',
                'cpap_peep' => 'PEEP',
            ],
            'HFO' => [
                'hfo_fio2' => 'FiO2',
                'hfo_frekuensi' => 'Frekuensi',
                'hfo_map' => 'MAP',
                'hfo_amplitudo' => 'Amplitudo',
                'hfo_it' => 'IT',
            ],
            'MONITOR' => [
                'monitor_mode' => 'Mode',
                'monitor_fio2' => 'FiO2',
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
            'pco2' => 'PCO2',
            'po2' => 'PO2',
            'hco3' => 'HCO3',
            'be' => 'BE',
            'sao2' => 'SaO2',
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
                        'max' => max(
                            !empty($hrData) ? max($hrData) : 0,
                            !empty($rrData) ? max($rrData) : 0
                        ) + 20,
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

            'dpjpDokters' => $dpjpDokters,
            'diagnosaPasien' => $diagnosaPasien,

            // --- GANTI DARI 1 CHART MENJADI 3 ---
            'chartVitalsBase64' => $chartVitalsBase64,
            'chartTempBase64' => $chartTempBase64,
            'chartBpBase64' => $chartBpBase64,
            'isPICU' => false,
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
            'title' => "PEDIATRIC INTENSIF CARE UNIT (PICU)",
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
            'hari_rawat_ke' => Carbon::parse($patientData->tgl_masuk)
                ->startOfDay()
                ->diffInDays(Carbon::parse($cycle->start_time)->startOfDay()) + 1,

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
        $soapBinary = $pdfSoap->output();
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


    /**
     * Generate PDF report for a specific ICU monitoring cycle.
     */
    public function printPdf(string $noRawat, string $sheetDate)
    {
        $noRawatDb = str_replace('_', '/', $noRawat);
        // 1. Load Data Cycle (Mirip logic mount() di Workspace)
        $cycle = MonitoringCycleIcu::where('no_rawat', $noRawatDb)
            ->where('sheet_date', $sheetDate)
            ->with(relations: [
                'registrasi.pasien',
                'records' => function ($query) {
                    $query->with('inputter:nik,nama')->orderBy('observation_time', 'asc');
                },
                'devices'
            ])
            ->firstOrFail();
        $cpptRecords = \App\Models\PemeriksaanRanap::with('pegawai')
            ->where('no_rawat', $cycle->no_rawat)
            ->whereRaw("CONCAT(tgl_perawatan, ' ', jam_rawat) >= ?", [$cycle->start_time])
            ->whereRaw("CONCAT(tgl_perawatan, ' ', jam_rawat) <= ?", [$cycle->end_time])
            ->orderBy('tgl_perawatan', 'asc')
            ->orderBy('jam_rawat', 'asc')
            ->get();
        $diagnosaPasien = DB::table('diagnosa_pasien')
            ->join('penyakit', 'diagnosa_pasien.kd_penyakit', '=', 'penyakit.kd_penyakit')
            ->where('diagnosa_pasien.no_rawat', $cycle->no_rawat)
            ->where('diagnosa_pasien.status', 'Ranap') // Hanya ambil diagnosa Ranap
            ->orderBy('diagnosa_pasien.prioritas', 'asc') // Urutkan berdasarkan prioritas
            ->pluck('penyakit.nm_penyakit');
        $allRawRecords = $cycle->records()
            ->with('inputter:nik,nama')
            ->orderBy('observation_time', 'asc')
            ->get();
        $recordsGroupedByMinute = $allRawRecords->groupBy(function ($record) {
            return $record->observation_time->format('Y-m-d H:i');
        });

        // 3. Buat daftar unik timestamp per menit untuk header kolom
        $uniqueTimestamps = $recordsGroupedByMinute->keys();
        // 4. Siapkan data 'merged' per menit untuk tbody
        $mergedRecordsPerMinute = $uniqueTimestamps->mapWithKeys(function ($timestamp) use ($recordsGroupedByMinute) {
            $recordsInMinute = $recordsGroupedByMinute[$timestamp];
            $mergedData = [
                'observation_time' => $recordsInMinute->first()->observation_time,
                'inputters' => $recordsInMinute->pluck('inputter.nama')->filter()->unique()->implode(', '),
                'fluids_in' => [],
                'fluids_out' => [],
                'notes' => [],
                'meds' => [],
                'suhu' => null,
                'nadi' => null,
                'tensi_sistol' => null,
                'tensi_diastol' => null,
                'map' => null,
                'rr' => null,
                'spo2' => null,
                'gcs_e' => null,
                'gcs_v' => null,
                'gcs_m' => null,
                'gcs_total' => null,
                'kesadaran' => null,
                'nyeri' => null,
                'cvp' => null,
                'cuff_pressure' => null,
                'fall_risk_assessment' => null,
                'irama_ekg' => null,
                'ventilator_mode' => null,
                'ventilator_f' => null,
                'ventilator_tv' => null,
                'ventilator_fio2' => null,
                'ventilator_peep' => null,
                'ventilator_ie_ratio' => null,
                'pupil_left_size_mm' => null,
                'pupil_left_reflex' => null,
                'pupil_right_size_mm' => null,
                'pupil_right_reflex' => null,
            ];

            // Ambil data TTV/Obs TERAKHIR dalam menit itu
            $mergedData['suhu'] = $recordsInMinute->last(fn($r) => $r->suhu !== null)?->suhu;
            $mergedData['nadi'] = $recordsInMinute->last(fn($r) => $r->nadi !== null)?->nadi;
            $mergedData['tensi_sistol'] = $recordsInMinute->last(fn($r) => $r->tensi_sistol !== null)?->tensi_sistol;
            $mergedData['tensi_diastol'] = $recordsInMinute->last(fn($r) => $r->tensi_sistol !== null)?->tensi_diastol;
            $mergedData['map'] = $recordsInMinute->last(fn($r) => $r->map !== null)?->map;
            $mergedData['rr'] = $recordsInMinute->last(fn($r) => $r->rr !== null)?->rr;
            $mergedData['spo2'] = $recordsInMinute->last(fn($r) => $r->spo2 !== null)?->spo2;
            $mergedData['gcs_e'] = $recordsInMinute->last(fn($r) => $r->gcs_e !== null)?->gcs_e;
            $mergedData['gcs_v'] = $recordsInMinute->last(fn($r) => $r->gcs_v !== null)?->gcs_v;
            $mergedData['gcs_m'] = $recordsInMinute->last(fn($r) => $r->gcs_m !== null)?->gcs_m;
            $mergedData['gcs_total'] = $recordsInMinute->last(fn($r) => $r->gcs_total !== null)?->gcs_total; // Also get total if available
            $mergedData['kesadaran'] = $recordsInMinute->last(fn($r) => $r->kesadaran !== null)?->kesadaran;
            $mergedData['nyeri'] = $recordsInMinute->last(fn($r) => $r->nyeri !== null)?->nyeri;
            $mergedData['cvp'] = $recordsInMinute->last(fn($r) => $r->cvp !== null)?->cvp;
            $mergedData['cuff_pressure'] = $recordsInMinute->last(fn($r) => $r->cuff_pressure !== null)?->cuff_pressure;
            $mergedData['fall_risk_assessment'] = $recordsInMinute->last(fn($r) => $r->fall_risk_assessment !== null)?->fall_risk_assessment;
            $mergedData['irama_ekg'] = $recordsInMinute->last(fn($r) => $r->irama_ekg !== null)?->irama_ekg;
            $mergedData['ventilator_mode'] = $recordsInMinute->last(fn($r) => $r->ventilator_mode !== null)?->ventilator_mode;
            $mergedData['ventilator_f'] = $recordsInMinute->last(fn($r) => $r->ventilator_f !== null)?->ventilator_f;
            $mergedData['ventilator_tv'] = $recordsInMinute->last(fn($r) => $r->ventilator_tv !== null)?->ventilator_tv;
            $mergedData['ventilator_fio2'] = $recordsInMinute->last(fn($r) => $r->ventilator_fio2 !== null)?->ventilator_fio2;
            $mergedData['ventilator_peep'] = $recordsInMinute->last(fn($r) => $r->ventilator_peep !== null)?->ventilator_peep;
            $mergedData['ventilator_ie_ratio'] = $recordsInMinute->last(fn($r) => $r->ventilator_ie_ratio !== null)?->ventilator_ie_ratio;
            $lastPupilLeftSize = $recordsInMinute->last(fn($r) => $r->pupil_left_size_mm !== null);
            $lastPupilLeftReflex = $recordsInMinute->last(fn($r) => $r->pupil_left_reflex !== null);
            $lastPupilRightSize = $recordsInMinute->last(fn($r) => $r->pupil_right_size_mm !== null);
            $lastPupilRightReflex = $recordsInMinute->last(fn($r) => $r->pupil_right_reflex !== null);
            $mergedData['pupil_left_size_mm'] = $lastPupilLeftSize?->pupil_left_size_mm;
            $mergedData['pupil_left_reflex'] = $lastPupilLeftReflex?->pupil_left_reflex;
            $mergedData['pupil_right_size_mm'] = $lastPupilRightSize?->pupil_right_size_mm;
            $mergedData['pupil_right_reflex'] = $lastPupilRightReflex?->pupil_right_reflex;
            // Kumpulkan cairan, catatan, tindakan (kode ini sudah benar)
            foreach ($recordsInMinute as $record) {
                if ($record->cairan_masuk_volume !== null && $record->cairan_masuk_volume > 0) {
                    $mergedData['fluids_in'][] = [
                        'jenis' => $record->cairan_masuk_jenis,
                        'volume' => $record->cairan_masuk_volume,
                        'is_parenteral' => $record->is_parenteral,
                        'is_enteral' => $record->is_enteral,
                    ];
                }
                if ($record->cairan_keluar_volume !== null && $record->cairan_keluar_volume > 0) {
                    $mergedData['fluids_out'][] = [
                        'jenis' => $record->cairan_keluar_jenis,
                        'volume' => $record->cairan_keluar_volume,
                    ];
                }
                if (!empty($record->clinical_note)) { // Cek string tidak kosong
                    $mergedData['notes'][] = $record->clinical_note;
                }
                if (!empty($record->medication_administration)) { // Cek string tidak kosong
                    $mergedData['meds'][] = $record->medication_administration;
                }
            }
            $mergedData['clinical_note'] = implode("\n", $mergedData['notes']);
            $mergedData['medication_administration'] = implode("\n", $mergedData['meds']);

            return [$timestamp => (object) $mergedData];
        });
        $registrasi = RegPeriksa::with(['pasien', 'poliklinik', 'penjab', 'dpjpRanap.dokter'])
            ->where('no_rawat', $noRawatDb)->firstOrFail();
        $dpjpDokters = $registrasi->dpjpRanap->map(fn($dpjp) => $dpjp->dokter)->filter();
        $kamarInapHistory = KamarInap::where('no_rawat', $noRawatDb)
            ->orderBy('tgl_masuk', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->with('kamar.bangsal') // Eager load relasi yang dibutuhkan
            ->get();
        $firstKamarInap = KamarInap::where('no_rawat', $noRawatDb)
            ->orderBy('tgl_masuk', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->first();

        // Tanggal mulai rawat inap
        $startDateCarbon = $firstKamarInap
            ? \Carbon\Carbon::parse($firstKamarInap->tgl_masuk)
            : \Carbon\Carbon::parse($registrasi->tgl_registrasi); // fallback jika tidak ada kamar inap

        // Tanggal target = tanggal sheet PDF
        $targetDateCarbon = \Carbon\Carbon::parse($sheetDate);

        // Hitung hari rawat ke
        $hospitalDayNumber = $startDateCarbon->diffInDays($targetDateCarbon) + 1;
        if ($kamarInapHistory->count() > 1) {
            // Kasus A: Pasien Pindahan Kamar Inap
            // Ambil record ke-2 dari belakang (ruangan sebelumnya)
            $previousKamarInap = $kamarInapHistory->skip(1)->first();

            $originatingWardName = $previousKamarInap->kamar->bangsal->nm_bangsal ?? 'N/A';

        } elseif ($kamarInapHistory->count() === 1) {
            // Kasus B: Pasien baru masuk Kamar Inap (tidak ada riwayat pindah kamar)
            // Ruangan asal adalah Poliklinik/IGD tempat registrasi
            $originatingWardName = $this->registrasi->poliklinik->nm_poli ?? 'N/A';
        } else {
            // Kasus C: Tidak ada riwayat Kamar Inap sama sekali (error atau belum masuk)
            $originatingWardName = $this->registrasi->poliklinik->nm_poli ?? 'N/A';
        }
        $currentKamarInap = $kamarInapHistory->first();
        $currentKamarInap = $kamarInapHistory->first(); // Record terbaru
        $currentRoomName = 'N/A';
        // $currentInstallasiName = 'N/A';
        $currentInstallasiName = 'RAWAT INAP';

        if ($currentKamarInap && $currentKamarInap->kamar) {
            $bangsalName = $currentKamarInap->kamar->bangsal->nm_bangsal ?? 'N/A';

            $currentRoomName = ($bangsalName)
                . ' / ' . ($currentKamarInap->kamar->kd_kamar ?? '');
        }
        $uniqueParenteralFluids = $allRawRecords
            ->where('is_parenteral', true)
            ->whereNotNull('cairan_masuk_volume')
            ->pluck('cairan_masuk_jenis')
            ->unique()
            ->sort();

        $uniqueEnteralFluids = $allRawRecords
            ->where('is_enteral', true)
            ->whereNotNull('cairan_masuk_volume')
            ->pluck('cairan_masuk_jenis')
            ->unique()
            ->sort();
        $totalMasuk = $allRawRecords->sum('cairan_masuk_volume');
        $totalKeluar = $allRawRecords->sum('cairan_keluar_volume');
        $iwl = $cycle->daily_iwl ?? 0;
        $balance24Jam = $totalMasuk - ($totalKeluar + $iwl);
        $previousBalance = $cycle->previous_balance ?? 0;
        $allParameters = $this->getReportParameters();
        $setting = DB::table('setting')->first();
        // 4. Load View Cetak dengan Data
        $dataForPdf = [
            'cycle' => $cycle,
            'registrasi' => $registrasi,
            'allParameters' => $allParameters,
            'currentInstallasiName' => $currentInstallasiName,
            'currentRoomName' => $currentRoomName,
            'originatingWardName' => $originatingWardName,
            'allRecords' => $cycle->records,
            'setting' => $setting,
            'uniqueParenteralFluids' => $uniqueParenteralFluids,
            'uniqueEnteralFluids' => $uniqueEnteralFluids,
            'uniqueTimestamps' => $uniqueTimestamps,
            'mergedRecordsPerMinute' => $mergedRecordsPerMinute,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'iwl' => $iwl,
            'balance24Jam' => $balance24Jam,
            'previousBalance' => $previousBalance,
            'dpjpDokters' => $dpjpDokters,
            'diagnosaPasien' => $diagnosaPasien,
            'hospitalDayNumber' => $hospitalDayNumber,
        ];
        $dataForSoap = [
            'dataForSoap' => $cpptRecords,
            'patient' => $registrasi,

        ];

        $pdfMonitoring = Pdf::loadView('pdf.icu.print-report', $dataForPdf)
            ->setPaper('a3', 'landscape');
        $monitoringBinary = $pdfMonitoring->output();

        $pdfSoap = Pdf::loadView('pdf.soap', $dataForSoap)
            ->setPaper('a4', 'potrait');
        $soapBinary = $pdfSoap->output();
        $merger = new Merger;
        $merger->addRaw($monitoringBinary);
        $merger->addRaw($soapBinary);
        $mergedPdf = $merger->merge();
        // --- 5. Simpan / stream hasilnya ---
        $sanitizedNoRawat = str_replace('/', '_', $noRawat);
        $pdfFilename = 'laporan-icu-' . $sanitizedNoRawat . '-' . $cycle->start_time->format('Ymd') . '.pdf';

        return response($mergedPdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $pdfFilename . '"');
    }

    /**
     * Helper: Daftar Parameter untuk Laporan Cetak.
     * (Salin dari ObservationTable.php atau definisikan sesuai kebutuhan cetak)
     */
    private function getReportParameters(): array
    {
        return [
            ['key' => 'suhu', 'label' => 'Suhu (°C)', 'group' => 'HEMODINAMIK'],
            ['key' => 'nadi', 'label' => 'Nadi', 'group' => 'HEMODINAMIK'],
            ['key' => 'tensi', 'label' => 'Tensi', 'group' => 'HEMODINAMIK'],
            ['key' => 'map', 'label' => 'MAP', 'group' => 'HEMODINAMIK'],// --- RESPIRASI ---
            ['key' => 'ventilator_mode', 'label' => 'Mode Ventilator', 'group' => 'RESPIRASI'], // Pindah ke atas grup
            ['key' => 'ventilator_pinsp', 'label' => 'Vent. P Insp', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_peep', 'label' => 'Vent. PEEP', 'group' => 'RESPIRASI'],
            ['key' => 'rr', 'label' => 'RR (x/mnt)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_f', 'label' => 'Vent. F (Freq)', 'group' => 'RESPIRASI'], // Dekatkan RR & Freq
            ['key' => 'ventilator_tv', 'label' => 'Vent. TV (Vol)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_fio2', 'label' => 'Vent. FiO2 (%)', 'group' => 'RESPIRASI'],
            ['key' => 'ventilator_ie_ratio', 'label' => 'Vent. I:E Ratio', 'group' => 'RESPIRASI'],
            // cvp, spo2, cuff_pressure dipindah ke OBSERVASI

            // --- OBSERVASI ---
            ['key' => 'kesadaran', 'label' => 'Kesadaran', 'group' => 'OBSERVASI'],
            ['key' => 'irama_ekg', 'label' => 'Irama EKG', 'group' => 'OBSERVASI'], // Pindah ke sini
            ['key' => 'nyeri', 'label' => 'Skala Nyeri', 'group' => 'OBSERVASI'], // Ganti label
            ['key' => 'cvp', 'label' => 'CVP', 'group' => 'OBSERVASI'], // Pindah ke sini
            ['key' => 'spo2', 'label' => 'SpO2 (%)', 'group' => 'OBSERVASI'], // Pindah ke sini
            ['key' => 'cuff_pressure', 'label' => 'Cuff Pressure', 'group' => 'OBSERVASI'], // Pindah ke sini
            ['key' => 'pupil', 'label' => 'Pupil (Ki/Ka)', 'group' => 'OBSERVASI'],
            ['key' => 'gcs', 'label' => 'GCS', 'group' => 'OBSERVASI'],
            ['key' => 'fall_risk_assessment', 'label' => 'Risiko Jatuh', 'group' => 'OBSERVASI'],
            // --- CAIRAN ---
            ['key' => 'cairan_masuk', 'label' => 'Cairan Masuk', 'group' => 'CAIRAN'],
            ['key' => 'cairan_keluar', 'label' => 'Cairan Keluar', 'group' => 'CAIRAN'],
            // --- CATATAN ---
            ['key' => 'clinical_note', 'label' => 'Catatan Klinis/Masalah', 'group' => 'CATATAN'],
            ['key' => 'medication_administration', 'label' => 'Tindakan/Obat', 'group' => 'CATATAN'],
        ];
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
}
