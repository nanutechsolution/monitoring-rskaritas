<?php

namespace App\Livewire\Monitoring;

use Livewire\Component;
use App\Models\IntraAnesthesiaMonitoring;
use App\Models\Setting;
use App\Models\Pasien;

class AnesthesiaShow extends Component
{
    public ?IntraAnesthesiaMonitoring $monitoring;
    public ?Pasien $pasien;
    public ?Setting $setting;

    // Data untuk Chart
    public $chartLabels = [];
    public $chartDataNadi = [];
    public $chartDataSistolik = [];
    public $chartDataDiastolik = [];
    public $chartDataRR = [];

    public function mount($monitoringId)
    {
        // 1. Ambil data utama dan semua relasinya
        $this->monitoring = IntraAnesthesiaMonitoring::with(
            'vitals',
            'medications',
            'dokterAnestesi',
            'penataAnestesi',
            'registrasi.pasien' // Ambil data pasien
        )->findOrFail($monitoringId);

        // 2. Ambil data pasien
        $this->pasien = $this->monitoring->registrasi->pasien;

        // 3. Ambil data setting (untuk kop surat jika perlu)
        $this->setting = Setting::instance();

        // 4. Siapkan data untuk chart statis
        if ($this->monitoring->vitals->isNotEmpty()) {
            $vitalsCollection = $this->monitoring->vitals->sortBy('waktu');
            $this->chartLabels = $vitalsCollection->pluck('waktu')->all();
            $this->chartDataNadi = $vitalsCollection->pluck('rrn')->all();
            $this->chartDataSistolik = $vitalsCollection->pluck('td_sis')->all();
            $this->chartDataDiastolik = $vitalsCollection->pluck('td_dis')->all();
            $this->chartDataRR = $vitalsCollection->pluck('rr')->all();
        }
    }

    public function render()
    {
        return view('livewire.monitoring.anesthesia-show')
                ->layout('layouts.app'); // Sesuaikan dengan layout Anda
    }
}
