<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuCycle;
use Livewire\Attributes\On;

class PicuVitalChart extends Component
{
    public $monitoringSheetId;
    public $chartData; 

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        $this->buildChartData(); 
    }

    /**
     * Membangun data untuk Tipe Kategori
     */
    public function buildChartData()
    {
        // 1. Ambil SEMUA data, urutkan
        $allCycles = PicuCycle::where('picu_monitoring_id', $this->monitoringSheetId)
            // Hanya ambil baris yg punya setidaknya 1 data TTV
            ->where(function ($query) {
                $query->whereNotNull('heart_rate')
                      ->orWhereNotNull('temp_skin')
                      ->orWhereNotNull('respiratory_rate')
                      ->orWhereNotNull('sat_o2');
            })
            ->orderBy('waktu_observasi', 'asc') 
            ->get();

        // 2. Siapkan array untuk data 'Categorical'
        $labels = [];         // Untuk Sumbu X (cth: "14:16:38")
        $hrData = [];         // Untuk Sumbu Y (cth: 120)
        $rrData = [];
        $tempData = [];
        $satO2Data = [];
        $tensiSistolik = [];
        $tensiDiastolik = [];
        $fio2Data = [];
        $peepMapData = [];

        foreach ($allCycles as $cycle) {
            // === INI KUNCINYA ===
            // Buat label Sumbu X dari waktu observasi
            $labels[] = $cycle->waktu_observasi->format('H:i'); // cth: "14:16:38"

            // Buat array data sederhana
            $hrData[]    = $cycle->heart_rate;
            $rrData[]    = $cycle->respiratory_rate;
            $tempData[]  = $cycle->temp_skin;
            $satO2Data[] = $cycle->sat_o2;

            // Pecah Tekanan Darah
            if ($cycle->tekanan_darah && str_contains($cycle->tekanan_darah, '/')) {
                [$sistol, $diastol] = explode('/', $cycle->tekanan_darah, 2);
                $tensiSistolik[] = ctype_digit($sistol) ? (int)$sistol : null;
                $tensiDiastolik[] = ctype_digit($diastol) ? (int)$diastol : null;
            } else {
                $tensiSistolik[] = null;
                $tensiDiastolik[] = null;
            }
            
            // Gabungkan Data Ventilator
            $fio2 = $cycle->vent_fio2_nasal ?? $cycle->vent_fio2_cpap ?? $cycle->vent_fio2_hfo ?? $cycle->vent_fio2_mekanik;
            $fio2Data[] = $fio2;
            $peepMap = $cycle->vent_peep_cpap ?? $cycle->vent_map_hfo ?? $cycle->vent_peep_mekanik;
            $peepMapData[] = $peepMap;
        }
        
        // 3. Set public property $this->chartData
        $this->chartData = [
            'labels' => $labels, 
            'datasets' => [
                // Data TTV
                ['label' => 'Suhu (°C)', 'data' => $tempData, 'borderColor' => '#10B981', 'yAxisID' => 'yTemp', 'tension' => 0.1, 'fill' => true, 'backgroundColor' => 'rgba(16, 185, 129, 0.1)'],
                ['label' => 'HR (x/mnt)', 'data' => $hrData, 'borderColor' => '#EF4444', 'yAxisID' => 'yRate', 'tension' => 0.1],
                ['label' => 'RR (x/mnt)', 'data' => $rrData, 'borderColor' => '#3B82F6', 'yAxisID' => 'yRate', 'tension' => 0.1],
                ['label' => 'Sistol (mmHg)', 'data' => $tensiSistolik, 'borderColor' => '#F59E0B', 'yAxisID' => 'yRate', 'tension' => 0.1, 'pointStyle' => 'triangle'],
                ['label' => 'Diastol (mmHg)', 'data' => $tensiDiastolik, 'borderColor' => '#F59E0B', 'yAxisID' => 'yRate', 'tension' => 0.1, 'pointStyle' => 'cross'],
                ['label' => 'SpO2 (%)', 'data' => $satO2Data, 'borderColor' => '#6366F1', 'yAxisID' => 'ySupport', 'tension' => 0.1],
                
                // Data Intervensi
                ['label' => 'FiO2 (%)', 'data' => $fio2Data, 'borderColor' => '#8B5CF6', 'yAxisID' => 'ySupport', 'tension' => 0.1, 'pointStyle' => 'rectRot', 'borderDash' => [5, 5]],
                ['label' => 'PEEP/MAP', 'data' => $peepMapData, 'borderColor' => '#EC4899', 'yAxisID' => 'ySupport', 'tension' => 0.1, 'pointStyle' => 'star', 'borderDash' => [5, 5]],
            ]
        ];
    }
    
    #[On('cycle-updated')]
    public function refreshChart()
    {
        $this->buildChartData(); 
        $this->dispatch('refresh-chart', data: $this->chartData);
    }

    public function render()
    {
        return view('livewire.picu-vital-chart')->layout('layouts.app');
    }
}