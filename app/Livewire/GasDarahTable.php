<?php

namespace App\Livewire;

use App\Models\BloodGasResult;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class GasDarahTable extends Component
{
    public ?int $cycleId =null;
    public Collection $bloodGasResults;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->bloodGasResults = new Collection();
        $this->loadBloodGasResults();
    }
    /**
     * TAMBAHKAN FUNGSI INI
     * Hook ini akan otomatis berjalan saat $cycleId diperbarui
     * oleh PatientMonitor (saat Anda ganti hari).
     */
    public function updatedCycleId($newCycleId)
    {
        $this->cycleId = $newCycleId;
        $this->loadBloodGasResults(); // Muat ulang data gas darah
    }

    #[On('record-saved')]
    #[On('refresh-blood-gas')]
    public function loadBloodGasResults()
    {
        if (!$this->cycleId) {
            $this->bloodGasResults = new Collection();
            return;
        }

        $this->bloodGasResults = BloodGasResult::with('pegawai')
            ->where('monitoring_cycle_id', $this->cycleId)
            ->orderBy('taken_at', 'desc')
            ->get();
    }

    public function render()
    {
        // Arahkan ke file partial Blade Anda yang sudah ada
        return view('livewire.patient-monitor.partials.output-tabel-gasdarah');
    }
}
