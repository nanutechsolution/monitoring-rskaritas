<?php

namespace App\Livewire;

use App\Models\MonitoringRecord;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class VentilatorTable extends Component
{
    public ?int $cycleId= null;
    public Collection $records;

    public function mount(?int $cycleId)
    {
        $this->cycleId = $cycleId;
        $this->records = new Collection();
        $this->loadVentilatorRecords();
    }

    #[On('record-saved')]
    public function loadVentilatorRecords()
    {
        if (!$this->cycleId) {
            $this->records = new Collection();
            return;
        }

        // Query HANYA untuk record yang berisi data ventilator
        $this->records = MonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->where(function ($query) {
                $query->whereNotNull('respiratory_mode')
                    ->orWhereNotNull('monitor_mode')
                    ->orWhereNotNull('spontan_fio2')
                    ->orWhereNotNull('cpap_fio2')
                    ->orWhereNotNull('hfo_fio2');
            })
            ->with('pegawai')
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.patient-monitor.partials.output-tabel-ventilator');
    }
}
