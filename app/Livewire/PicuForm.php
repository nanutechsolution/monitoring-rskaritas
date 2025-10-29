<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoring;
use App\Models\RegPeriksa;
use Carbon\Carbon;

class PicuForm extends Component
{
    public $noRawat;
    public $sheetDate; // <-- PROPERTI BARU
    public $regPeriksa;
    public $monitoringSheet;

    /**
     * Mount lifecycle hook.
     * LOGIKA BARU: Menerima noRawat dan sheetDate (opsional)
     */
    public function mount($noRawat, $sheetDate = null)
    {
        $this->noRawat = $noRawat;
        $this->sheetDate = $sheetDate;

        // 1. Ambil data registrasi pasien
        $this->regPeriksa = RegPeriksa::where('no_rawat', $this->noRawat)->firstOrFail();

        // 2. Tentukan tanggal target
        // Jika sheetDate dikirim (dari history), gunakan itu.
        // Jika tidak (dari link hari ini), gunakan now().
        $targetDate = $this->sheetDate ? Carbon::parse($this->sheetDate) : now();

        // 3. Tentukan rentang waktu sheet (mulai jam 06:00)
        $startTime = $this->calculateSheetStartTime($targetDate);
        $endTime = $startTime->copy()->addDay()->subSecond(); // 24 jam dikurangi 1 detik

        // 4. LOGIKA UTAMA: Cari ATAU BUAT BARU (firstOrCreate)
        // Ini memastikan lembar observasi ada, baik dibuka dari history
        // atau dibuat baru untuk hari ini.
        $this->monitoringSheet = PicuMonitoring::with('dokter')->firstOrCreate(
            [
                // Kunci untuk mencari
                'no_rawat' => $this->noRawat,
                'start_datetime' => $startTime,
            ],
            [
                // Data untuk diisi JIKA membuat baru
                'end_datetime' => $endTime,
                'dokter_dpjp' => $this->regPeriksa->kd_dokter,
                'diagnosis' => $this->regPeriksa->penyakit_awal, // Ambil dari diagnosa awal (contoh)
            ]
        );
    }

    /**
     * Helper untuk menentukan jam mulai sheet.
     * (Tidak ada perubahan di fungsi ini)
     */
    private function calculateSheetStartTime(Carbon $now): Carbon
    {
        $shiftStartHour = 6; // Jam 6 pagi

        if ($now->hour >= $shiftStartHour) {
            // Jika jam 06:00 atau lebih, sheet dimulai hari ini jam 06:00
            return $now->copy()->startOfDay()->addHours($shiftStartHour);
        } else {
            // Jika jam 00:00 - 05:59, sheet dimulai KEMARIN jam 06:00
            return $now->copy()->subDay()->startOfDay()->addHours($shiftStartHour);
        }
    }

    public function render()
    {
        // Cek apakah user melihat sheet hari ini atau sheet lama
        $isToday = $this->monitoringSheet->start_datetime->isSameDay($this->calculateSheetStartTime(now()));

        return view('livewire.picu-form', [
            'isToday' => $isToday
        ])->layout('layouts.app');
    }
}
