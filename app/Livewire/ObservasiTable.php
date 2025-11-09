<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MonitoringRecord;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;

class ObservasiTable extends Component
{
    public ?int $cycleId = null;
    public Collection $records;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->records = new Collection();
        // Hapus loadRecords() dari mount, biarkan 'lazy'
        // $this->loadRecords();
    }

    // Hapus fungsi updatedCycleId($newCycleId)
    // Kita akan ganti dengan listener yang lebih andal

    /**
     * Listener untuk event 'record-saved' dari parent.
     */
    #[On('record-saved')]
    public function refreshTable()
    {
        $this->loadRecords();
    }

    /**
     * Listener untuk event baru 'cycle-updated' dari parent.
     * Ini akan memperbaiki masalah 'lazy' load.
     */
    #[On('cycle-updated')]
    public function updateCycleId($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadRecords();
    }

    public function loadRecords()
    {
        if (!$this->cycleId) {
            $this->records = new Collection();
            return;
        }

        $this->records = MonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->with('pegawai')
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.observasi-table');
    }
}
