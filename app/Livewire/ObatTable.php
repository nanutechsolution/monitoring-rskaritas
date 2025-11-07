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
        $this->loadMedications();
    }


    public function updatedCycleId($newCycleId)
    {
        $this->cycleId = $newCycleId;
        $this->loadMedications(); // Muat ulang data
    }

    #[On('record-saved')]
    #[On('refresh-medications')]
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
        // Arahkan ke file partial Blade Anda yang sudah ada
        return view('livewire.patient-monitor.partials.output-tabel-obat');
    }
}
