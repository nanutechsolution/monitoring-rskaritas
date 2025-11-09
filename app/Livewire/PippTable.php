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
    }

    /**
     * Listener untuk event 'cycle-updated' dari parent.
     * Ini akan memperbaiki masalah 'lazy' load.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadPippAssessments();
    }

    /**
     * Listener untuk refresh data (jika ada simpan PIPP baru)
     */
    #[On('record-saved')] // Anda bisa hapus ini jika 'refresh-pip' selalu dipanggil
    #[On('refresh-pip')]
    public function refreshTable()
    {
        $this->loadPippAssessments();
    }

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

    public function render()
    {
        return view('livewire.patient-monitor.partials.output-tabel-pipp');
    }
}
