<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PicuCycle;
use Livewire\Attributes\On;

class PicuReviewGrid extends Component
{
    public $monitoringSheetId;

    /**
     * @var \Illuminate\Support\Collection
     * Ini akan menyimpan data cycle, di-key berdasarkan jam_grid
     */
    public $cycles;

    /**
     * @var array
     * Defini urutan jam untuk kolom grid
     */
    public $hours = [];

    public function mount($monitoringSheetId)
    {
        $this->monitoringSheetId = $monitoringSheetId;

        // Membuat array jam dari 6:00 pagi sampai 5:00 pagi besok
        $this->hours = array_merge(range(6, 23), range(0, 5));

        // Muat data saat komponen pertama kali di-load
        $this->loadCycles();
    }

    /**
     * Listener: Dipanggil oleh PicuInputRealtime
     * untuk refresh grid ini.
     */
    #[On('cycle-updated')]
    #[On('cycle-updated')]
    public function loadCycles()
    {
        $this->cycles = PicuCycle::where('picu_monitoring_id', $this->monitoringSheetId)
            ->get()
            // 1. Kelompokkan berdasarkan slot jam (6, 7, 8, dst)
            ->groupBy('jam_grid')
            // 2. Untuk setiap grup jam, urutkan (desc) berdasarkan waktu input
            //    dan ambil HANYA 1 (yang pertama/terbaru)
            ->map(function ($cyclesInHour) {
                return $cyclesInHour->sortByDesc('waktu_observasi')->first();
            });
    }

    public function render()
    {
        return view('livewire.picu-review-grid');
    }
}
