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
    public function loadCycles()
    {
        $this->cycles = PicuCycle::where('picu_monitoring_id', $this->monitoringSheetId)
            ->get()
            ->keyBy('jam_grid');
    }

    public function render()
    {
        return view('livewire.picu-review-grid');
    }
}
