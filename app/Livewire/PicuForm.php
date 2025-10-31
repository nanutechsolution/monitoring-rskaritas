<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoring;
use App\Models\RegPeriksa;
use Carbon\Carbon;

class PicuForm extends Component
{
    public $noRawat;
    public $sheetDate;
    public $regPeriksa;
    public $monitoringSheet;
    public string $activeTab = 'observasi';

    /**
     * Mount lifecycle hook.
     */
    public function mount($noRawat, $sheetDate = null)
    {
        $this->noRawat = $noRawat;
        $this->sheetDate = $sheetDate;

        // 1. Ambil data registrasi pasien
        $this->regPeriksa = RegPeriksa::where('no_rawat', $this->noRawat)->firstOrFail();

        // 2. Tentukan tanggal target
        $targetDate = $this->sheetDate ? Carbon::parse($this->sheetDate) : now();

        // 3. Tentukan rentang waktu sheet (mulai jam 06:00)
        $startTime = $this->calculateSheetStartTime($targetDate);
        $endTime = $startTime->copy()->addDay()->subSecond();

        // 4. LOGIKA UTAMA: Cari ATAU BUAT BARU
        // === PERBAIKAN: Hapus .with('dokter') ===
        $this->monitoringSheet = PicuMonitoring::firstOrCreate(
            [
                // Kunci untuk mencari
                'no_rawat' => $this->noRawat,
                'start_datetime' => $startTime,
            ],
            [
                // Data untuk diisi JIKA membuat baru
                'end_datetime' => $endTime,
                'diagnosis' => $this->regPeriksa->penyakit_awal, 
            ]
        );
    }

    /**
     * Helper untuk menentukan jam mulai sheet.
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
