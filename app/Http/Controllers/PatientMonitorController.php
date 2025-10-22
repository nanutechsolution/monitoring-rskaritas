<?php

namespace App\Http\Controllers;

use App\Livewire\PatientList;
use App\Models\BloodGasResult;
use App\Models\Medication;
use Illuminate\Http\Request;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use App\Models\PatientDevice;
use App\Models\PippAssessment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientMonitorController extends Controller
{
    public function generateReportPdf($no_rawat, $cycle_id)
    {
        $no_rawat = str_replace('_', '/', $no_rawat);

        // 1. Validasi
        $cycle = MonitoringCycle::find($cycle_id);
        if (!$cycle || $cycle->no_rawat !== $no_rawat) {
            abort(404, 'Siklus monitoring tidak ditemukan.');
        }

        // 2. Ambil Data Pasien & Header Lengkap
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
                // Tambahkan select 'umur_kehamilan' di sini jika sudah ketemu
            )
            ->where('rp.no_rawat', $no_rawat)
            ->where('ki.tgl_masuk', '<=', $cycle->start_time)
            // ->where(function ($query) use ($cycle) {
            //     $query->whereNull('ki.tgl_keluar')
            //         ->orWhere('ki.tgl_keluar', '>=', $cycle->start_time->format('Y-m-d'));
            // })
            ->orderBy('ki.tgl_masuk', 'desc')
            ->first();

        // 3. Ambil Data Monitoring & Event Terkait Siklus
        $records = MonitoringRecord::with('parenteralIntakes')
            ->where('monitoring_cycle_id', $cycle->id)
            ->orderBy('record_time', 'asc')
            ->get();
        $medications = Medication::where('monitoring_cycle_id', $cycle->id)->orderBy('given_at', 'asc')->get();
        $bloodGasResults = BloodGasResult::where('monitoring_cycle_id', $cycle->id)->orderBy('taken_at', 'asc')->get();
        $pippAssessments = PippAssessment::where('monitoring_cycle_id', $cycle->id)->orderBy('assessment_time', 'asc')->get();
        $patientDevices = PatientDevice::where('no_rawat', $no_rawat)->orderBy('device_name')->get();

        // 4. Kalkulasi Summary
        $totalIntake = $records->sum(fn($r) => ($r->intake_ogt ?? 0) + ($r->intake_oral ?? 0) + $r->parenteralIntakes->sum('volume'));
        $totalOutput = $records->sum(fn($r) => ($r->output_urine ?? 0) + ($r->output_bab ?? 0) + ($r->output_residu ?? 0) + ($r->output_ngt ?? 0) + ($r->output_drain ?? 0));
        $iwl = $cycle->daily_iwl ?? 0;
        $balance = $totalIntake - $totalOutput - $iwl;
        $totalUrine = $records->sum('output_urine');

        // 5. Siapkan Data untuk View PDF
        $dataForPdf = [
            'patient' => $patientData,
            'cycle' => $cycle,
            'records' => $records,
            'medications' => $medications,
            'bloodGasResults' => $bloodGasResults,
            'pippAssessments' => $pippAssessments,
            'patientDevices' => $patientDevices,
            'summary' => [
                'totalIntake' => $totalIntake,
                'totalOutput' => $totalOutput,
                'totalUrine' => $totalUrine,
                'iwl' => $iwl,
                'balance' => $balance,
            ],
            'umur_bayi' => Carbon::parse($patientData->tgl_lahir)->diffInDays($cycle->start_time),
            'hari_rawat_ke' => Carbon::parse($patientData->tgl_masuk)->diffInDays($cycle->start_time) + 1,
            // 'umur_koreksi' => ... // Tambahkan jika data umur kehamilan sudah ada
        ];

        // 6. Generate & Tampilkan PDF
        $pdf = Pdf::loadView('pdf.monitoring-report', $dataForPdf)
            ->setPaper('a4', 'landscape'); // Atur kertas A4 landscape
        $sanitizedNoRawat = str_replace('/', '_', $no_rawat);
        $pdfFilename = 'monitoring-report-' . $sanitizedNoRawat . '-' . $cycle->start_time->format('Ymd') . '.pdf';

        return $pdf->stream($pdfFilename);

    }

}
