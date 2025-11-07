<?php

namespace App\Livewire;

use App\Models\MonitoringRecord;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class CairanTable extends Component
{
    public ?int $cycleId=  null;
    public Collection $fluidRecords;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->fluidRecords = new Collection();
        $this->loadFluidRecords();
    }

    /**
     * TAMBAHKAN FUNGSI INI
     * Hook ini akan otomatis berjalan saat $cycleId diperbarui
     * oleh PatientMonitor (saat Anda ganti hari).
     */
    public function updatedCycleId($newCycleId)
    {
        $this->cycleId = $newCycleId;
        $this->loadFluidRecords(); // Muat ulang data cairan
    }

    #[On('record-saved')]
    public function loadFluidRecords()
    {
        if (!$this->cycleId) {
            $this->fluidRecords = new Collection();
            return;
        }

        // Logika filter cairan dari PatientMonitor.php
        $allCycleRecords = MonitoringRecord::with(['parenteralIntakes', 'enteralIntakes', 'pegawai'])
            ->where('monitoring_cycle_id', $this->cycleId)
            ->get();

        $this->fluidRecords = $allCycleRecords->filter(function ($record) {
            if ($record->parenteralIntakes->sum('volume') > 0) return true;
            if ($record->enteralIntakes->sum('volume') > 0) return true;
            $hasIntake = ($record->intake_ogt ?? 0) > 0 || ($record->intake_oral ?? 0) > 0;
            $hasOutput = ($record->output_urine ?? 0) > 0 ||
                         ($record->output_bab ?? 0) > 0 ||
                         ($record->output_ngt ?? 0) > 0 ||
                         ($record->output_drain ?? 0) > 0;
            return $hasIntake || $hasOutput;
        })->sortByDesc('record_time');
    }

    public function render()
    {
        // Arahkan ke file partial Blade Anda yang sudah ada
        return view('livewire.patient-monitor.partials.output-tabel-cairan');
    }
}
