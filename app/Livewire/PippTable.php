<?php

namespace App\Livewire;

use App\Models\PippAssessment;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class PippTable extends Component
{
    public ?int $cycleId = null;
    public Collection $pippAssessments;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->pippAssessments = new Collection();
        $this->loadPippAssessments();
    }

    #[On('record-saved')]
    #[On('refresh-pip')]
    public function loadPippAssessments()
    {
        if (!$this->cycleId) {
            $this->pippAssessments = new Collection();
            return;
        }

        $this->pippAssessments = PippAssessment::with('pegawai')
            ->where('monitoring_cycle_id', $this->cycleId)
            ->orderBy('assessment_time', 'desc')
            ->get();
    }
    public function updatedCycleId($newCycleId)
    {
        $this->cycleId = $newCycleId;
        $this->loadPippAssessments();
    }
    public function render()
    {
        // Arahkan ke file partial Blade Anda yang sudah ada
        return view('livewire.patient-monitor.partials.output-tabel-pipp');
    }
}
