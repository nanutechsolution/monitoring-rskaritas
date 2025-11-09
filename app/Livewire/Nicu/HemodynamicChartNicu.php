<?php

namespace App\Livewire\Nicu;

use Livewire\Component;
use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use Carbon\Carbon;
use Livewire\Attributes\On;

class HemodynamicChartNicu extends Component
{
    public $no_rawat;
    public $selectedDate;

    public function mount($no_rawat, $selectedDate)
    {
        $this->no_rawat = $no_rawat;
        $this->selectedDate = $selectedDate;

        $this->loadChartData(); // Load awal saat mount
    }

    #[On('date-changed')]
    #[On('record-saved')]
    public function refreshChart()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $targetDate = Carbon::parse($this->selectedDate);

        $currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $targetDate->format('Y-m-d'))
            ->first();

        $chartData = [];

        if ($currentCycle) {
            $records = MonitoringRecord::where('monitoring_cycle_id', $currentCycle->id)
                ->orderBy('record_time', 'asc')
                ->get();

            $chartData = collect([
                'temp_incubator' => 'temp_incubator',
                'temp_skin' => 'temp_skin',
                'hr' => 'hr',
                'rr' => 'rr',
                'bp_systolic' => 'blood_pressure_systolic',
                'bp_diastolic' => 'blood_pressure_diastolic',
            ])->mapWithKeys(fn($dbField, $key) => [
                $key => $records
                    ->filter(fn($r) => is_numeric($r->$dbField))
                    ->map(fn($r) => [
                        'x' => Carbon::parse($r->record_time)->toIso8601String(),
                        'y' => (float)$r->$dbField,
                    ])
                    ->values()
            ])->toArray();
        }

        $this->dispatch('update-hemo-chart', chartData: $chartData);
    }

    public function render()
    {
        return view('livewire.nicu.hemodynamic-chart-nicu');
    }
}
