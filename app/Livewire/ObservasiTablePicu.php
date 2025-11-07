<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuMonitoringRecord;

class ObservasiTablePicu extends Component
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
        $this->records = PicuMonitoringRecord::where('monitoring_cycle_id', $this->cycleId)
            ->orderByDesc('record_time')
            ->get();
    }

    public function render()
    {
        return view('livewire.observasi-table-picu');
    }
}
