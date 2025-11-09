<?php

namespace App\Livewire\Picu;

use App\Models\PicuMonitoringCycle;
use App\Models\RegPeriksa;
use Livewire\Component;
use Illuminate\Support\Collection;

class MonitoringHistory extends Component
{
    public $noRawat;
    public $pasien;
    public Collection $cycles;
    public bool $hasOngoingCycle = false;

    public function mount(string $no_rawat)
    {
        $this->noRawat = str_replace('_', '/', $no_rawat);

        $regPeriksa = RegPeriksa::where('no_rawat', $this->noRawat)
            ->with('pasien')
            ->firstOrFail();

        $this->pasien = $regPeriksa->pasien;

        $this->cycles = PicuMonitoringCycle::where('no_rawat', $this->noRawat)
            ->orderBy('start_time', 'desc')
            ->get();

        // Cek apakah ada siklus ongoing
        $this->hasOngoingCycle = $this->cycles->contains(function ($cycle) {
            return $cycle->end_time->isFuture();
        });
    }

    public function render()
    {
        return view('livewire.picu.monitoring-history')
            ->layout('layouts.app');
    }
}
