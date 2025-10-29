<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuCycle;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class PicuVitalChart extends Component
{
    public $monitoringSheetId;

    // Kita buat array hours untuk label X-axis
    public $hours = [];

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;
        // Jam 6 pagi s/d 5 pagi besok
        $this->hours = array_merge(range(6, 23), range(0, 5));
    }

    /**
     * Ambil data cycle, dan key-nya dengan jam_grid
     */
    #[Computed]
    #[On('cycle-updated')] // Agar refresh saat perawat simpan data baru
    public function vitalSigns()
    {
        return PicuCycle::where('picu_monitoring_id', $this->monitoringSheetId)
            ->get()
            ->keyBy('jam_grid');
    }

    /**
     * Fungsi untuk menyiapkan data dalam format Chart.js
     */
    #[Computed]
    public function chartData()
    {
        $hrData = [];
        $rrData = [];
        $tempData = [];
        $satO2Data = [];

        foreach ($this->hours as $hour) {
            $cycle = $this->vitalSigns()->get($hour);

            // Mengambil data untuk setiap jam. Jika kosong, isi null agar garis putus.
            $hrData[]     = $cycle->heart_rate ?? null;
            $rrData[]     = $cycle->respiratory_rate ?? null;
            $tempData[]   = $cycle->temp_skin ?? null;
            $satO2Data[]  = $cycle->sat_o2 ?? null;
        }

        return [
            'labels' => array_map(fn($h) => $h . ':00', $this->hours), // cth: [6:00, 7:00, ...]
            'datasets' => [
                [
                    'label' => 'Heart Rate (x/mnt)',
                    'data' => $hrData,
                    'borderColor' => 'rgb(255, 99, 132)', // Merah
                    'yAxisID' => 'y1',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Resp. Rate (x/mnt)',
                    'data' => $rrData,
                    'borderColor' => 'rgb(54, 162, 235)', // Biru
                    'yAxisID' => 'y1',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Temp. Skin (°C)',
                    'data' => $tempData,
                    'borderColor' => 'rgb(75, 192, 192)', // Hijau Muda
                    'yAxisID' => 'y2',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Sat. O2 (%)',
                    'data' => $satO2Data,
                    'borderColor' => 'rgb(255, 159, 64)', // Orange
                    'yAxisID' => 'y3',
                    'tension' => 0.4
                ],
            ]
        ];
    }

    public function render()
    {
        return view('livewire.picu-vital-chart')->layout('layouts.app');
    }
}
