<?php

namespace App\Livewire\Nicu;

use App\Models\MonitoringCycle;
use App\Models\MonitoringRecord;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;

class HemodynamicChartNicu extends Component
{
    #[Locked]
    public $no_rawat;
    public $selectedDate;

    /**
     * Terima properti DAN MUAT DATA AWAL.
     */
    public function mount($no_rawat, $selectedDate)
    {
        $this->no_rawat = $no_rawat;
        $this->selectedDate = $selectedDate;

    }

    /**
     * [LISTENER] Dengar event dari parent.
     */
    #[On('record-saved')]
    #[On('date-changed')]
    public function refreshChartData($selectedDate = null)
    {
        // Update tanggal jika dikirim oleh event
        if ($selectedDate) {
            $this->selectedDate = $selectedDate;
        }

        // Muat ulang data
        $this->loadChartData();
    }

    /**
     * Fungsi utama untuk mengambil data DAN mengirimnya ke browser.
     */
    public function loadChartData()
    {
        // 1. Tentukan Tanggal Sheet
        $targetDate = Carbon::parse($this->selectedDate);
        // $sheetDate = $targetDate->copy()->startOfDay();
        // if ($targetDate->hour < 6) {
        //     $sheetDate->subDay();
        // }

        // 2. Cari Siklus menggunakan 'sheet_date'
        $currentCycle = MonitoringCycle::where('no_rawat', $this->no_rawat)
            ->where('sheet_date', $targetDate->format('Y-m-d'))
            ->first();
        // 3. Muat Data & Format
        if ($currentCycle) {
            $chartRecords = MonitoringRecord::where('monitoring_cycle_id', $currentCycle->id)
                ->orderBy('record_time', 'asc')
                ->get();
            // Logika kalkulasi TTV
            $chartData = collect([
                'temp_incubator' => 'temp_incubator',
                'temp_skin'      => 'temp_skin',
                'hr'             => 'hr',
                'rr'             => 'rr',
                'bp_systolic'    => 'blood_pressure_systolic',
                'bp_diastolic'   => 'blood_pressure_diastolic',
            ])
            ->mapWithKeys(fn($dbField, $chartKey) => [
                $chartKey => $chartRecords
                    ->filter(fn($r) => is_numeric($r->$dbField) && $r->$dbField !== null)
                    ->map(fn($r) => [
                        'x' => Carbon::parse($r->record_time)->toIso8601String(),
                        'y' => (float) $r->$dbField,
                    ])
                    ->values()
            ])->toArray();

            // 4. Selalu dispatch event.
            $this->dispatch('update-hemo-chart', chartData: $chartData);

        } else {
            // Kirim data kosong jika tidak ada siklus
            $this->dispatch('update-hemo-chart', chartData: []);
        }
    }

    /**
     * Tampilkan skeleton loading (karena kita pakai 'lazy').
     */
    public function placeholder()
    {
        return <<<'HTML'
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 rounded-lg">
            <div class="p-6 h-96 animate-pulse">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3 mb-4"></div>
                <div class="relative mt-4 h-72 bg-gray-200 dark:bg-gray-700 rounded"></div>
            </div>
        </div>
        HTML;
    }

    public function render()
    {
        return view('livewire.nicu.hemodynamic-chart-nicu');
    }
}
