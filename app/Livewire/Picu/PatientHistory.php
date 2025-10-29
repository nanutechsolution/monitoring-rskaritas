<?php

namespace App\Livewire\Picu;

use App\Models\MonitoringCyclePicu;
use App\Models\RegPeriksa;
use Illuminate\Support\Collection;
use Livewire\Component;

class PatientHistory extends Component
{
    public RegPeriksa $registrasi;
    public Collection $allCycles;
    public string $noRawatDb;

    public function mount(string $noRawat)
    {
        $this->noRawatDb = str_replace('_', '/', $noRawat);

        $this->registrasi = RegPeriksa::with('pasien')
            ->where('no_rawat', $this->noRawatDb)
            ->firstOrFail();

        // Load SEMUA riwayat lembar observasi PICU
        $this->allCycles = MonitoringCyclePicu::where('no_rawat', $this->noRawatDb)
            ->orderBy('sheet_date', 'desc')
            ->get();
    }

    /**
     * Helper untuk mendapatkan tanggal "Hari RS" hari ini.
     */
    public function getTodayHospitalDate(): string
    {
        $currentTime = now();
        $hospitalDayStartHour = 6; // Ganti hari jam 06:00

        if ($currentTime->hour < $hospitalDayStartHour) {
            return $currentTime->subDay()->toDateString();
        }

        return $currentTime->toDateString();
    }

    public function render()
    {
        return view('livewire.picu.patient-history', [
            'todayDate' => $this->getTodayHospitalDate()
        ])->layout('layouts.app');
    }
}
