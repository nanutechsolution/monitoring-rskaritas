<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\MonitoringRecord;

class ObservasiTable extends Component
{
    public $cycleId;
    public $records;

    protected $listeners = ['refresh-observasi' => 'loadRecords'];

    public function mount($cycleId)
    {
        $this->cycleId = $cycleId;
        $this->loadRecords();
    }

    public function loadRecords()
    {
        $this->records = MonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.observasi-table');
    }
}
