<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
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
        // 1. Ubah no_rawat dari URL kembali ke format DB
        $this->noRawatDb = str_replace('_', '/', $noRawat);

        // 2. Load data pasien
        $this->registrasi = RegPeriksa::with('pasien')
            ->where('no_rawat', $this->noRawatDb)
            ->firstOrFail();

        // 3. Load SEMUA riwayat lembar observasi untuk pasien ini
        $this->allCycles = MonitoringCycleIcu::where('no_rawat', $this->noRawatDb)
            ->orderBy('sheet_date', 'desc') // Urutkan dari yg terbaru
            ->get();
    }

    public function getTodayHospitalDate(): string
    {
        $currentTime = now();
        $hospitalDayStartHour = 7; 

        if ($currentTime->hour < $hospitalDayStartHour) {
            return $currentTime->subDay()->toDateString();
        }

        return $currentTime->toDateString();
    }

    public function render()
    {
        return view('livewire.icu.patient-history', [
            'todayDate' => $this->getTodayHospitalDate()
        ])->layout('layouts.app');
    }
}
