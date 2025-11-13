<?php

namespace App\Livewire\Picu;

use App\Models\PicuMonitoringRecord;
use App\Models\PicuMonitoringCycle;
use Livewire\Component;
use Carbon\Carbon;

class MonitoringChart extends Component
{
    public string $no_rawat;
    public array $chartData = [];
    public $selectedCycleId = null;

    // Listener untuk merespon data baru dari form input
    public function refreshChart(): void
    {
        $this->loadChartData();
    }

    public function mount(string $noRawat, $cycleId = null): void
    {
        $this->no_rawat = str_replace('_', '/', $noRawat);
        $this->selectedCycleId = $cycleId;
        $this->loadChartData();
    }


    public function loadChartData(): void
    {
        if (empty($this->no_rawat)) {
            $this->chartData = [];
            return;
        }

        // 1. Tentukan Cycle ID yang akan digunakan
        $cycleIdToUse = $this->selectedCycleId;
        $cycle = null;

        if ($cycleIdToUse) {
            // Jika ID dikirim dari tombol, gunakan ID itu
            $cycle = PicuMonitoringCycle::find($cycleIdToUse);
        } else {
            // Jika ID tidak dikirim (default), hitung siklus aktif hari ini
            $now = now();
            $cycleStartTime = $now->copy()->startOfDay()->addHours(6);
            if ($now->hour < 6) {
                $cycleStartTime->subDay();
            }
            $sheetDate = $cycleStartTime->toDateString();

            $cycle = PicuMonitoringCycle::where('no_rawat', $this->no_rawat)
                ->where('sheet_date', $sheetDate)
                ->first();
        }


        if (!$cycle) {
            $this->chartData = [];
            return;
        }

        // 2. Ambil Data Record untuk Cycle ID yang DITEMUKAN
        // Kita juga update $selectedCycleId jika kita menemukannya via fallback
        $this->selectedCycleId = $cycle->id;

        $records = PicuMonitoringRecord::where('monitoring_cycle_id', $cycle->id)
            ->orderBy('record_time', 'asc')
            ->get();

        // Filter Records: Tampilkan jika ada data TTV ATAU Setting Ventilator
        $filteredRecords = $records->filter(function ($record) {
            return !is_null($record->hr)
                || !is_null($record->rr)
                || !is_null($record->temp_skin)
                || !is_null($record->sat_o2)
                || !is_null($record->monitor_peep)
                || !is_null($record->monitor_fio2);
        });

        // 3. Format Data untuk Chart.js
        $labels = [];
        $hrData = [];
        $rrData = [];
        $tempData = [];
        $spo2Data = [];
        $peepData = [];
        $fio2Data = [];

        foreach ($filteredRecords as $record) {
            $time = Carbon::parse($record->record_time)->format('H:i');
            $labels[] = $time;

            $hrData[] = $record->hr;
            $rrData[] = $record->rr;
            $tempData[] = $record->temp_skin;
            $spo2Data[] = $record->sat_o2;

            $peepData[] = $record->monitor_peep;
            $fio2Data[] = $record->monitor_fio2;
        }

        // 4. Set Properti Chart Data
        $this->chartData = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Heart Rate (x/mnt)', 'data' => $hrData, 'borderColor' => '#ef4444', 'tension' => 0.3, 'fill' => false,
                ],
                [
                    'label' => 'Resp. Rate (x/mnt)', 'data' => $rrData, 'borderColor' => '#3b82f6', 'tension' => 0.3, 'fill' => false,
                ],
                [
                    'label' => 'Suhu (°C)', 'data' => $tempData, 'borderColor' => '#f59e0b', 'tension' => 0.3, 'fill' => false,
                ],
                [
                    'label' => 'SpO2 (%)', 'data' => $spo2Data, 'borderColor' => '#10b981', 'tension' => 0.3, 'fill' => false,
                ],
                [
                    'label' => 'PEEP (cmH₂O)', 'data' => $peepData, 'borderColor' => '#8b5cf6', 'tension' => 0.1, 'fill' => false, 'borderDash' => [5, 5], 'pointRadius' => 3, 'pointHoverRadius' => 5,
                ],
                [
                    'label' => 'FiO₂ (%)', 'data' => $fio2Data, 'borderColor' => '#06b6d4', 'tension' => 0.1, 'fill' => false, 'borderDash' => [5, 5], 'pointRadius' => 3, 'pointHoverRadius' => 5,
                ],
            ],
        ];
    }

    public function render()
    {
        // Variabel $records harus didefinisikan untuk view tabel
        $records = PicuMonitoringRecord::where('monitoring_cycle_id', $this->selectedCycleId)
            ->orderBy('record_time', 'desc')
            ->get();
        return view('livewire.picu.monitoring-chart', [
            'records' => $records,
        ])->layout('layouts.app');
    }
}
