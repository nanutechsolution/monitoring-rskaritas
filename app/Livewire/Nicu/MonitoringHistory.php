<?php

namespace App\Livewire\Nicu;

use App\Models\MonitoringCycle;
use App\Models\RegPeriksa; // <-- 1. Import model RegPeriksa (atau yang sesuai)
use App\Models\Pasien;    // <-- 2. Import model Pasien
use Livewire\Component;
use Illuminate\Support\Collection;

class MonitoringHistory extends Component
{
    public $noRawat; // Ganti nama dari no_rawat agar konsisten
    public $pasien;  // Properti baru untuk data pasien
    public Collection $cycles;

    public function mount(string $no_rawat) // Terima parameter lama
    {
        $this->noRawat = str_replace('_', '/', $no_rawat); // Simpan ke properti baru

        // 3. Ambil data pasien
        // Asumsi: Anda menggunakan model RegPeriksa dan Pasien dari Khanza
        $regPeriksa = RegPeriksa::where('no_rawat', $this->noRawat)
                                ->with('pasien') // Eager load relasi pasien
                                ->firstOrFail(); // Gagal jika no_rawat tidak ditemukan

        $this->pasien = $regPeriksa->pasien;

        // Muat semua siklus monitoring
        $this->cycles = MonitoringCycle::where('no_rawat', $this->noRawat)
                            ->orderBy('start_time', 'desc')
                            ->get();
    }

    public function render()
    {
        // Gunakan layout utama aplikasi Anda
        return view('livewire.nicu.monitoring-history')
                ->layout('layouts.app');
    }
}
