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

    /**
     * Helper untuk mendapatkan tanggal "Hari RS" hari ini.
     * (Penting agar tombol "Buka Hari Ini" tidak salah)
     */
    public function getTodayHospitalDate(): string
    {
        $currentTime = now();
        $hospitalDayStartHour = 7; // Ganti hari jam 07:00

        if ($currentTime->hour < $hospitalDayStartHour) {
            // Jika jam 00:00 - 06:59, masih ikut tanggal kemarin
            return $currentTime->subDay()->toDateString();
        }

        return $currentTime->toDateString();
    }

    public function render()
    {
        return view('livewire.icu.patient-history', [
            // Kirim tanggal hari ini ke view
            'todayDate' => $this->getTodayHospitalDate()
        ])->layout('layouts.app');
    }
}
