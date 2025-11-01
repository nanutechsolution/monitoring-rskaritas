<?php

namespace App\Livewire\Monitoring;

use Livewire\Component;
use App\Models\RegPeriksa;
use App\Models\IntraAnesthesiaMonitoring;
use App\Models\Pasien; // Kita butuh model Pasien

class AnesthesiaHistory extends Component
{
    public $noRawat;
    public ?Pasien $pasien; // Objek Pasien

    public $history = [];

    public function mount($noRawat)
    {
        $this->noRawat = str_replace('_', '/', $noRawat);

        // 2. Ambil data registrasi DAN data pasien-nya
        $regPeriksa = RegPeriksa::with('pasien')
            ->where('no_rawat', $this->noRawat)
            ->firstOrFail();

        $this->pasien = $regPeriksa->pasien;

        // 3. Ambil riwayat formulir anestesi untuk pasien ini
        // Kita pakai 'with' (eager loading) agar efisien
        $this->history = IntraAnesthesiaMonitoring::with('dokterAnestesi', 'penataAnestesi')
            ->where('no_rawat', $this->noRawat)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        // Tentukan layout utama Anda (cth: 'layouts.app')
        return view('livewire.monitoring.anesthesia-history')
            ->layout('layouts.app');
    }
}
