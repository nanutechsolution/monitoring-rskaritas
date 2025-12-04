<?php

namespace App\Livewire\Icu;

use App\Models\MonitoringCycleIcu;
use App\Models\MonitoringRecordIcu;
use App\Models\RegPeriksa;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ObservationGrid extends Component
{
    public MonitoringCycleIcu $cycle;
    public string $filterShift = 'all';
    public array $staticState = [];

    /**
     * Method 'mount' ini sudah diperbaiki dengan 'Tanggal RS'.
     */
    public function mount(MonitoringCycleIcu $cycle)
    {
        $this->cycle = $cycle; // Terima cycle dari Induk (Workspace)
    }

    /**
     * [Computed Property BARU]
     * Mengambil semua record inputan real-time.
     * Ini akan menjadi "Kolom" (header) kita.
     */
    #[Computed(persist: true)]
    public function allRecords(): Collection
    {
        // Ambil semua record, urutkan berdasarkan waktu
        return $this->cycle->records()
            ->with('inputter')
            ->orderBy('observation_time', 'asc')
            ->get();
    }

    /**
     * Helper: Daftar Parameter (Baris)
     * (Sudah di-update dengan Ventilator, EKG, dll.)
     */
    public function parameters(): array
    {
        return [
            // [Nama di DB, Label di Tampilan, Grup]
            ['key' => 'suhu', 'label' => 'Suhu (°C)', 'group' => 'HEMODINAMIK'],
            ['key' => 'nadi', 'label' => 'Nadi (x/mnt)', 'group' => 'HEMODINAMIK'],
            ['key' => 'tensi', 'label' => 'Tensi', 'group' => 'HEMODINAMIK'],
            ['key' => 'map', 'label' => 'MAP', 'group' => 'HEMODINAMIK'],
            ['key' => 'rr', 'label' => 'RR (x/mnt)', 'group' => 'RESPIRASI'],
        ];
    }

    /**
     * [Computed Property]
     * Menyiapkan data untuk Chart.js.
     * (Sudah di-update dengan CVP)
     */
    #[Computed(persist: true)]
    public function chartData(): array
    {
        $labels = [];
        $suhuData = [];
        $nadiData = [];
        $rrData = [];
        $sistolData = [];
        $diastolData = [];
        $mapData = [];

        $ttvRecords = $this->allRecords->filter(function ($record) {
            return $record->suhu || $record->nadi || $record->tensi_sistol || $record->rr || $record->map;
        });

        foreach ($ttvRecords as $record) {
            $labels[] = $record->observation_time->format('H:i');
            $suhuData[] = $record->suhu ?? null;
            $nadiData[] = $record->nadi ?? null;
            $rrData[] = $record->rr ?? null;
            $sistolData[] = $record->tensi_sistol ?? null;
            $diastolData[] = $record->tensi_diastol ?? null;
            $mapData[] = $record->map ?? null;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Suhu (°C)',
                    'data' => $suhuData,
                    'borderColor' => 'rgb(239, 68, 68)', // red-600
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'yAxisID' => 'ySuhu',
                ],
                [
                    'label' => 'Nadi (x/mnt)',
                    'data' => $nadiData,
                    'borderColor' => 'rgb(34, 197, 94)', // green-600
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'RR (x/mnt)',
                    'data' => $rrData,
                    'borderColor' => 'rgb(168, 85, 247)', // purple-600
                    'backgroundColor' => 'rgba(168, 85, 247, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'Tensi Sistol',
                    'data' => $sistolData,
                    'borderColor' => 'rgb(59, 130, 246)', // blue-600
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'Tensi Diastol',
                    'data' => $diastolData,
                    'borderColor' => 'rgb(234, 179, 8)', // yellow-600
                    'backgroundColor' => 'rgba(234, 179, 8, 0.2)',
                    'yAxisID' => 'yTtv',
                ],
                [
                    'label' => 'MAP',
                    'data' => $mapData,
                    'borderColor' => 'rgb(217, 119, 6)', // amber-600 (warna baru)
                    'backgroundColor' => 'rgba(217, 119, 6, 0.2)',
                    'yAxisID' => 'yTtv',
                ],

            ],
        ];
    }


    public function render()
    {
        return view('livewire.icu.observation-grid', [
            'allParameters' => $this->parameters()
        ])->layout('layouts.app');
    }
}
