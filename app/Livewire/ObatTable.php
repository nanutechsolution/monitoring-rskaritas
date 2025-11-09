<?php

namespace App\Livewire;

use App\Models\Medication;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ObatTable extends Component
{
    public ?int $cycleId = null;
    public Collection $medications;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->medications = new Collection();
        // HAPUS INI: $this->loadMedications();
    }

    // HAPUS FUNGSI updatedCycleId($newCycleId)
    // Kita ganti dengan listener di bawah

    /**
     * Listener untuk event 'cycle-updated' dari parent.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadMedications();
    }

    /**
     * Listener untuk event 'record-saved' atau 'refresh-medications'
     */
    #[On('record-saved')]
    #[On('refresh-medications')]
    public function refreshTable()
    {
        $this->loadMedications();
    }

    public function loadMedications()
    {
        if (!$this->cycleId) {
            $this->medications = new Collection();
            return;
        }

        $this->medications = Medication::with('pegawai')
            ->where('monitoring_cycle_id', $this->cycleId)
            ->orderBy('given_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.patient-monitor.partials.output-tabel-obat');
    }
}
