<?php

namespace App\Livewire;

use App\Models\BloodGasResult;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class GasDarahTable extends Component
{
    public ?int $cycleId = null;
    public Collection $bloodGasResults;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->bloodGasResults = new Collection();
        // HAPUS INI: $this->loadBloodGasResults();
    }

    // HAPUS FUNGSI updatedCycleId($newCycleId)
    // Kita ganti dengan listener di bawah

    /**
     * Listener untuk event 'cycle-updated' dari parent.
     * Ini akan memperbaiki masalah 'lazy' load.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadBloodGasResults();
    }

    /**
     * Listener untuk refresh data (jika ada simpan BGA baru)
     */
    #[On('record-saved')] // Anda bisa hapus ini jika 'refresh-blood-gas' selalu dipanggil
    #[On('refresh-blood-gas')]
    public function refreshTable()
    {
        $this->loadBloodGasResults();
    }

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
        return view('livewire.patient-monitor.partials.output-tabel-gasdarah');
    }
}
